{*
* The MIT License (MIT)
*
* Copyright (c) 2015 Benichou
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
*  @copyright 2015 Benichou
*  @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
*}

{if !empty($av_block_cron)}
	<div class="bootstrap panel">
		<div class="panel-heading">
			<i class="icon-refresh"></i> {l s='Automatic update' mod='blocknetreviews'}
		</div>
		<span>
			{l s='For automatically update your shop rating, create a "Cron task" to load the following URL at the time you would like:' mod='blocknetreviews'}
			<a href="{$av_block_cron|escape:'htmlall':'UTF-8'}" target="_blank">{$av_block_cron|escape:'htmlall':'UTF-8'}</a>
		</span>
	</div>
{/if}
{if !empty($av_block_shop_update)}
	<div class="bootstrap panel" {if !empty($av_block_reviews_url)}style="height:600px;"{/if}>
		<div class="panel-heading">
			<i class="icon-star"></i> {l s='Shop reviews' mod='blocknetreviews'}
		</div>
		<span>{l s='Reviews updated on ' mod='blocknetreviews'} {$av_block_shop_update|date_format:'%Y-%m-%d %H:%M:%S'}</span>, <span>{$av_block_shop_reviews} {l s='reviews' mod='blocknetreviews'}</span>, <span>{l s='rating:' mod='blocknetreviews'} {$av_block_shop_rating}/5</span>
		{if !empty($av_block_reviews_url)}
			<span><a href="{$av_block_reviews_url}" target="_blank">{$av_block_reviews_url}</a></span>
			<iframe style="margin-top:10px;" frameBorder="0" width="100%" height="100%" src="{$av_block_reviews_url}"></iframe>
		{/if}
	</div>
{/if}
