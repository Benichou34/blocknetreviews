<?php
/*
* The MIT License (MIT)
*
* Copyright (c) 2016 Benichou
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
*  @author    Benichou <benichou.software@gmail.com>
*  @copyright 2016 Benichou
*  @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
*/

if (!defined('_PS_VERSION_'))
	exit;

class BlockNetReviews extends Module
{
	public function __construct()
	{
		$this->bootstrap = true;
		$this->name = 'blocknetreviews';
		$this->tab = 'advertising_marketing';
		$this->author = 'Benichou';
		$this->version = '1.1';
		$this->allow_push = false;

		parent::__construct();
		$this->displayName = $this->l('Verified Reviews Block');
		$this->description = $this->l('Display Verified Reviews widget');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		if (!parent::install()
			|| !$this->registerHook('dashboardZoneOne')
			|| !$this->registerHook('dashboardData')
			|| !$this->registerHook('displayFooter')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayLeftColumn'))
 			return false;

		return true;
	}

	public function uninstall()
	{
		$this->unregisterHook('dashboardZoneOne');
		$this->unregisterHook('dashboardData');
		$this->unregisterHook('displayFooter');
		$this->unregisterHook('displayRightColumn');
		$this->unregisterHook('displayLeftColumn');

		Configuration::deleteByName('AV_BLOCK_SOURCE_FILE');
		Configuration::deleteByName('AV_BLOCK_REVIEWS_URL');
		Configuration::deleteByName('AV_BLOCK_RATING');
		Configuration::deleteByName('AV_BLOCK_REVIEWS');
		Configuration::deleteByName('AV_BLOCK_LAST_UPDATE');

		return parent::uninstall();
	}

	public function getContent()
	{
		// If form has been sent
		$output = '';

		if (Tools::isSubmit('submit'.$this->name))
		{
			Configuration::updateValue('AV_BLOCK_SOURCE_FILE', Tools::getValue('AV_BLOCK_SOURCE_FILE'));
			Configuration::updateValue('AV_BLOCK_REVIEWS_URL', Tools::getValue('AV_BLOCK_REVIEWS_URL'));
			Configuration::updateValue('AV_BLOCK_RATING_TEN', Tools::getValue('AV_BLOCK_RATING_TEN_on'));

			$output .= $this->upateShopRating($this->context->shop->id);
		}

		$output .= $this->renderForm();

		$this->context->smarty->assign(array(
			'av_block_cron' => _PS_BASE_URL_SSL_._MODULE_DIR_.$this->name.'/cron.php',
			'av_block_reviews_url' => Configuration::get('AV_BLOCK_REVIEWS_URL'),
			'av_block_shop_rating' => Configuration::get('AV_BLOCK_RATING'),
			'av_block_shop_reviews' => Configuration::get('AV_BLOCK_REVIEWS'),
			'av_block_shop_update' => Configuration::get('AV_BLOCK_LAST_UPDATE')
		));

		$output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/config.tpl');
		return $output;
	}

	public function renderForm()
	{
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->submit_action = 'submit'.$this->name;

		$fields_forms = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('General settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Source file'),
						'name' => 'AV_BLOCK_SOURCE_FILE',
						'size' => 40,
						'required' => false,
						'hint' => $this->l('This file allows you to dynamically retrieve your rating and your number of reviews.')
					),
					array(
						'type' => 'text',
						'label' => $this->l('Certificate URL'),
						'name' => 'AV_BLOCK_REVIEWS_URL',
						'size' => 40,
						'required' => false,
						'hint' => $this->l('Your link to your shop certificate.')
					),
					array(
						'type' => 'checkbox',
						'name' => 'AV_BLOCK_RATING_TEN',
						'desc' => $this->l('Display the score out of 10, instead of 5.'),
						'values' => array(
							'query' => array(
								array(
									'id' => 'on',
									'name' => $this->l('Score out of 10'),
									'val' => '1'
								),
							),
							'id' => 'id',
							'name' => 'name'
						)
					)
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			)
		);

		// Load current value
		$helper->fields_value['AV_BLOCK_SOURCE_FILE'] = Configuration::get('AV_BLOCK_SOURCE_FILE');
		$helper->fields_value['AV_BLOCK_REVIEWS_URL'] = Configuration::get('AV_BLOCK_REVIEWS_URL');
		$helper->fields_value['AV_BLOCK_RATING_TEN_on'] = Configuration::get('AV_BLOCK_RATING_TEN');

		return $helper->generateForm(array($fields_forms));
	}

	public function upateShopRating($id_shop = null)
	{
		$source_file = Configuration::get('AV_BLOCK_SOURCE_FILE');
		if(empty($source_file))
		{
			Configuration::deleteByName('AV_BLOCK_LAST_UPDATE');
			return $this->displayError($this->l("Empty source url"));
		}

		$content = Tools::file_get_contents($source_file);
		$ratings = explode(";", $content);
		if(empty($ratings))
		{
			Configuration::deleteByName('AV_BLOCK_LAST_UPDATE');
			return $this->displayError($this->l("Error when retrieving reviews"));
		}

		Configuration::updateValue('AV_BLOCK_REVIEWS', (int)$ratings[0]);
		Configuration::updateValue('AV_BLOCK_RATING', (float)$ratings[1]);
		Configuration::updateValue('AV_BLOCK_LAST_UPDATE', time());

		return $this->displayConfirmation($this->l('Reviews updated successfully'));
	}

	private function hookDefault($params, $template)
	{
		$shop_rating = Configuration::get('AV_BLOCK_RATING') * 20; // percent
		$max_rating = Configuration::get('AV_BLOCK_RATING_TEN')? 10: 5;

		$this->context->controller->addCss($this->_path.'views/css/blocknetreviews.css');

		$this->context->smarty->assign(array(
			'av_block_reviews_url' => Configuration::get('AV_BLOCK_REVIEWS_URL'),
			'av_block_shop_rating' => $shop_rating,
			'av_block_max_rating' => $max_rating,
			'av_block_shop_reviews' => Configuration::get('AV_BLOCK_REVIEWS'),
			'av_block_shop_update' => Configuration::get('AV_BLOCK_LAST_UPDATE')
		));

		return $this->display(__FILE__, $template);
	}

	public function hookFooter($params)
	{
		return $this->hookDefault($params, 'views/templates/hook/footer.tpl');
	}

	public function hookLeftColumn($params)
	{
		$this->context->controller->addJs($this->_path.'views/js/blocknetreviews.js');
		return $this->hookDefault($params, 'views/templates/hook/column.tpl');
	}

	public function hookRightColumn($params)
	{
		return $this->hookLeftColumn($params);
	}

	public function hookDashboardZoneOne($params)
	{
		$this->context->smarty->assign(array(
			'av_block_reviews_url' => Configuration::get('AV_BLOCK_REVIEWS_URL')
		));

		return $this->display(__FILE__, 'views/templates/hook/dashboard_zone_one.tpl');
	}

	public function hookDashboardData($params)
	{
		if (Tools::getValue('extra') == 'update')
			$this->upateShopRating($this->context->shop->id);

		return array(
			'data_value' => array(
				'rating' => Configuration::get('AV_BLOCK_RATING')."/5",
				'reviews' => Configuration::get('AV_BLOCK_REVIEWS'),
				'update' => date("Y-m-d H:i:s", Configuration::get('AV_BLOCK_LAST_UPDATE'))
			)
		);
	}
}
