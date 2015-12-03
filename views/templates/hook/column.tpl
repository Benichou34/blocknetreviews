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

{if !empty($av_block_shop_update) && $av_block_shop_rating > 0}
	{$rating = ($av_block_shop_rating * $av_block_max_rating / 100)|string_format:"%.1f"}
	<div class="av-shop-widget">
		{if !empty($av_block_reviews_url)}
			<a href="{$av_block_reviews_url}" target="_blank">
		{/if}
			<img src="{$modules_dir}blocknetreviews/views/img/{l s='logo_full_en.png' mod='blocknetreviews'}" alt="{l s='Verified Reviews' mod='blocknetreviews'}" title="{l s='Verified Reviews' mod='blocknetreviews'}" />
			<div class="av-stars" title="{l s='Opinions of our customers' mod='blocknetreviews'} {$rating}/{$av_block_max_rating}">
				<div class="av-base-stars av-background-stars">
					{section name="background-stars" loop=5 start=0}
						<i class="icon-star"></i>
					{/section}
				</div>
				<div class="av-base-stars av-rating-stars" style="width:{$av_block_shop_rating}%;">
					{section name="forground-stars" loop=5 start=0}
						<i class="icon-star"></i>
					{/section}
				</div>
			</div>
		{if !empty($av_block_reviews_url)}
			</a>
		{/if}
		<span class="av-rating">{$rating}/{$av_block_max_rating}</span>
		<span class="av-rating-reviews">{$av_block_shop_reviews} {l s='reviews' mod='blocknetreviews'}</span>
	</div>
{/if}
