{*
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
*}

<section id="blocknetreviews" class="panel widget">
	<div class="panel-heading">
		<i class="icon-star"></i> {l s='Shop reviews' mod='blocknetreviews'}
		<span class="panel-heading-action">
			<a class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&amp;configure=blocknetreviews" title="{l s='Configure' mod='blocknetreviews'}">
				<i class="process-icon-configure"></i>
			</a>
			<a class="list-toolbar-btn" href="#" onclick="refreshDashboard('blocknetreviews', false, 'update'); return false;" title="{l s='Refresh' mod='blocknetreviews'}">
				<i class="process-icon-refresh"></i>
			</a>
		</span>
	</div>
	<section id="dash_rating" class="loading">
		<ul class="data_list_large">
			<li>
				<span class="data_label size_l">
					{if !empty($av_block_reviews_url)}<a href="{$av_block_reviews_url}" target="_blank">{/if}
					{l s='Rating' mod='blocknetreviews'}
					{if !empty($av_block_reviews_url)}</a>{/if}
				</span>
				<span class="data_value size_xxl"><span id="rating"></span></span>
			</li>
			<li>
				<span class="data_label size_l">
					{if !empty($av_block_reviews_url)}<a href="{$av_block_reviews_url}" target="_blank">{/if}
					{l s='Reviews' mod='blocknetreviews'}
					{if !empty($av_block_reviews_url)}</a>{/if}
				</span>
				<span class="data_value size_xxl"><span id="reviews"></span></span>
			</li>
			<li>
				<span class="data_label">{l s='Updated on' mod='blocknetreviews'}</span>
				<span class="data_value"><span id="update"></span></span>
			</li>
		</ul>
	</section>
</section>
