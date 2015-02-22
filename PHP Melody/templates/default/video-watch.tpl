{include file="header.tpl" p="detail" tpl_name="video-watch"}
<div id="wrapper">
{if $show_addthis_widget == '1'}
{include file='widget-addthis.tpl'}
{/if}
    <div class="container-fluid">
    
    {if $playlist}
	<div class="row-fluid">
    <div class="span12">
        <div class="pm-video-head">
        {if $logged_in && $is_admin == 'yes'}
        <div class="btn-action-group pull-right">
        <a href="#" onclick="return confirm_action('Are you sure you want to delete this video?', '{$smarty.const._URL}/{$smarty.const._ADMIN_FOLDER}/modify.php?vid={$video_data.uniq_id}&a=1'); return false;" rel="tooltip" class="btn btn-mini btn-danger" title="{$lang.delete} ({$lang._admin_only})" target="_blank">{$lang.delete}</a> <a href="{$smarty.const._URL}/admin/modify.php?vid={$video_data.uniq_id}" rel="tooltip" class="btn btn-mini" title="{$lang.edit} ({$lang._admin_only})" target="_blank">{$lang.edit}</a>
        </div>
        {/if}
        <h1 class="entry-title" itemprop="name">{$video_data.video_title}</h1>
        <meta itemprop="duration" content="{$video_data.iso8601_duration}" />
        <meta itemprop="thumbnailUrl" content="{$video_data.thumb_img_url}" />
        <meta itemprop="contentURL" content="{$smarty.const._URL2}/videos.php?vid={$video_data.uniq_id}" />
        <meta itemprop="embedURL" content="{$video_data.embed_href}" />
        <meta itemprop="uploadDate" content="{$video_data.html5_datetime}" />
        <div class="row-fluid">
            <div class="span6">
            {if $video_data.featured == 1}
                <span class="label label-featured">{$lang.featured}</span>
            {/if}
            </div>
            <div class="span6">
            <ul class="pm-video-adjust">
                <li><a id="player_extend" href="#"><i class="icon-resize-full opac7"></i> {$lang.resize}</a></li>
            </ul>
            </div>
        </div>
        </div><!--.pm-video-head-->
    </div>
    </div>
    {/if}
    <div class="row-fluid">
        <div class="span8">
		<div id="primary" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
        {if ! $playlist}
        <div class="row-fluid">
        <div class="span12">
            <div class="pm-video-head">
            {if $logged_in && $is_admin == 'yes'}
            <div class="btn-action-group pull-right">
            <a href="#" onclick="return confirm_action('Are you sure you want to delete this video?', '{$smarty.const._URL}/{$smarty.const._ADMIN_FOLDER}/modify.php?vid={$video_data.uniq_id}&a=1'); return false;" rel="tooltip" class="btn btn-mini btn-danger" title="{$lang.delete} ({$lang._admin_only})" target="_blank">{$lang.delete}</a> <a href="{$smarty.const._URL}/admin/modify.php?vid={$video_data.uniq_id}" rel="tooltip" class="btn btn-mini" title="{$lang.edit} ({$lang._admin_only})" target="_blank">{$lang.edit}</a>
            </div>
            {/if}
            <h1 class="entry-title" itemprop="name">{$video_data.video_title}</h1>
            <meta itemprop="duration" content="{$video_data.iso8601_duration}" />
            <meta itemprop="thumbnailUrl" content="{$video_data.thumb_img_url}" />
            <meta itemprop="contentURL" content="{$smarty.const._URL2}/videos.php?vid={$video_data.uniq_id}" />
            <meta itemprop="embedURL" content="{$video_data.embed_href}" />
            <meta itemprop="uploadDate" content="{$video_data.html5_datetime}" />
            <div class="row-fluid">
                <div class="span6">
                {if $video_data.featured == 1}
                    <span class="label label-featured">{$lang.featured}</span>
                {/if}
                </div>
                <div class="span6">
                <ul class="pm-video-adjust">
                    <li><a id="player_extend" href="#"><i class="icon-resize-full opac7"></i> {$lang.resize}</a></li>
                </ul>
                </div>
            </div>
            </div><!--.pm-video-head-->
        </div>
        </div>
        {/if}
<div class="row-fluid">
	<div class="span12">
        <div class="pm-player-full-width">

	   	    <div id="video-wrapper">
            {if $display_preroll_ad == true}
            <div id="preroll_placeholder" class="border-radius4">
				<div class="preroll_countdown">
				{$lang.preroll_ads_timeleft} <span class="preroll_timeleft">{$preroll_ad_data.timeleft_start}</span>
				</div>
				{$preroll_ad_data.code}
				{if $preroll_ad_data.skip}
				<div class="preroll_skip_countdown">
				   {$lang.preroll_ads_skip_msg} <span class="preroll_skip_timeleft">{$preroll_ad_data.skip_delay_seconds}</span>
				</div>
				<div class="preroll_skip_button">
				<button class="btn btn-blue hide" id="preroll_skip_btn">{$lang.preroll_ads_skip}</button>
				</div>
				{/if}
				{if $preroll_ad_data.disable_stats == 0}
					<img src="{$smarty.const._URL}/ajax.php?p=stats&do=show&aid={$preroll_ad_data.id}&at={$smarty.const._AD_TYPE_PREROLL}" width="1" height="1" border="0" />
				{/if}
            </div>
            {else}
                {include file="player.tpl" page="detail"}
            {/if}
	        </div><!--#video-wrapper-->


            <div class="pm-video-control">
            <div class="row-fluid">
                <div class="span4">
                    <button class="btn btn-small border-radius0 btn-video {if $bin_rating_vote_value == 1}active{/if}" id="bin-rating-like" type="button"><i class="pm-vc-sprite i-vote-up"></i> {$lang._like}</button>
                    <button class="btn btn-small border-radius0 btn-video {if $bin_rating_vote_value == 0 && $bin_rating_vote_value !== false}active{/if}" id="bin-rating-dislike" type="button"><i class="pm-vc-sprite i-vote-down"></i></button>
                </div>
                
                <div class="span8">
                	<div class="pull-right">
                        <button class="btn btn-small border-radius0 btn-video" type="button" data-toggle="button" id="pm-vc-share"><i class="icon-share-alt"></i> {$lang._share}</button>
                        <input type="hidden" name="bin-rating-uniq_id" value="{$video_data.uniq_id}">
                        {if $logged_in}
						<button class="btn btn-small border-radius0 btn-video" type="button" data-toggle="button" id="pm-vc-playlists" data-video-id="{$video_data.id}" title="{$lang.add_to_playlist}"><i class="pm-vc-sprite i-playlists"></i> {$lang.add_to}</button>
						{/if}
                        <button class="btn btn-small border-radius0 btn-video" type="button" data-toggle="button" id="pm-vc-report" rel="tooltip" data-placement="right" title="{$lang.report_video}"><i class="pm-vc-sprite i-report"></i></button>
					</div>
                </div>
            </div>
            </div><!--.pm-video-control-->

            <div id="bin-rating-response" class="hide well well-small"></div>
            <div id="bin-rating-like-confirmation" class="hide well well-small alert alert-well">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p>{$lang.confirm_like}</p>
                <div class="row-fluid">
                    <div class="span3 panel-1">
                    <a href="http://www.facebook.com/sharer.php?u={$facebook_like_href}&amp;t={$facebook_like_title}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on FaceBook"><i class="pm-vc-sprite facebook-icon"></i></a>
                    <a href="http://twitter.com/home?status=Watching%20{$facebook_like_title}%20on%20{$facebook_like_href}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on Twitter"><i class="pm-vc-sprite twitter-icon"></i></a>
                    <a href="https://plus.google.com/share?url={$facebook_like_href}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on Google+"><i class="pm-vc-sprite google-icon"></i></a>
                    </div>
                    <div class="span9 panel-3">
                    <div class="input-prepend"><span class="add-on">URL</span><input name="share_video_link" id="share_video_link" type="text" value="{$video_data.video_href_urldecoded}" class="span11" onClick="SelectAll('share_video_link');"></div>
                    </div>
                </div>
            </div>
            <div id="bin-rating-dislike-confirmation" class="hide-dislike hide well well-small">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p>{$lang.confirm_dislike}</p>
            </div><!--#bin-rating-like-confirmation-->
    
			<div id="pm-vc-playlists-content" class="hide">
			{if $logged_in}
                <h2 class="upper-blue">{$lang.my_playlists}</h2>
				<div id="playlist-ajax-response" class="hide"></div>
				{include file="video-watch-playlists.tpl"}
				<div id="playlist-create-ajax-response" class="hide"></div>
				
				<form class="form-inline pm-playlist-add-form">
					<input type="text" class="span7" name="playlist_name" placeholder="{$lang.playlist_enter_name}">
					<select name="visibility" class="span3">
						<option value="{$smarty.const.PLAYLIST_PUBLIC}">{$lang.public}</option>
						<option value="{$smarty.const.PLAYLIST_PRIVATE}">{$lang.private}</option>
					</select>
					<input type="hidden" name="video_id" value="{$video_data.id}" />
					<button type="submit" id="create_playlist_submit_btn" class="btn" onclick="playlist_create(this, 'video-watch'); return false;" disabled>{$lang.playlist_create_new}</button>
				</form>
            {else}
				<div class="alert alert-danger">
					{$lang.playlist_msg_login_required}
				</div>
			{/if}
			</div>  
    
            <div id="pm-vc-report-content" class="hide well well-small alert alert-well">
                <div id="report-confirmation" class="hide"></div>
                <form name="reportvideo" action="" method="POST" class="form-inline">
                  <input type="hidden" id="name" name="name" class="input-small" value="{if $logged_in}{$s_name}{/if}">
                  <input type="hidden" id="email" name="email" class="input-small" value="{if $logged_in}{$s_email}{/if}">
                
                  <select name="reason" class="input-medium inp-small">
                    <option value="{$lang.report_form1}" selected="selected">{$lang.report_form1}</option>
                    <option value="{$lang.report_form4}">{$lang.report_form4}</option>
                    <option value="{$lang.report_form5}">{$lang.report_form5}</option>
                    <option value="{$lang.report_form6}">{$lang.report_form6}</option>
                    <option value="{$lang.report_form7}">{$lang.report_form7}</option>
                  </select>
                  
                  {if ! $logged_in}
                    <input type="text" name="imagetext" class="input-small inp-small" autocomplete="off" placeholder="{$lang.confirm_comment}">
                    <button class="btn btn-small btn-link" onclick="document.getElementById('securimage-report').src = '{$smarty.const._URL}/include/securimage_show.php?sid=' + Math.random(); return false;"><i class="icon-refresh"></i> </button>
                    <img src="{$smarty.const._URL}/include/securimage_show.php?sid={echo_securimage_sid}" id="securimage-report" alt="" class="border-radius3">
                  {/if}
                  <button type="submit" name="Submit" class="btn btn-danger" value="{$lang.submit_send}">{$lang.report_video}</button>
                  <input type="hidden" name="p" value="detail">
                  <input type="hidden" name="do" value="report">
                  <input type="hidden" name="vid" value="{$video_data.uniq_id}">
                </form>
            </div><!-- #pm-vc-report-content-->
    
            <div id="pm-vc-share-content" class="alert alert-well">
                <div class="row-fluid">
                    <div class="span4 panel-1">
                    <div class="input-prepend"><span class="add-on">URL</span><input name="video_link" id="video_link" type="text" value="{$video_data.video_href_urldecoded}" class="input-medium" onClick="SelectAll('video_link');"></div>
                    </div>
                    <div class="span5 panel-2">
                    <button class="btn border-radius0 btn-video" type="button" id="pm-vc-embed">{$lang._embed}</button>
                    <button class="btn border-radius0 btn-video" type="button" data-toggle="button" id="pm-vc-email"><i class="icon-envelope"></i> {$lang.email_video}</button>
                    </div>
                    <div class="span3 panel-3">
                    <a href="http://www.facebook.com/sharer.php?u={$facebook_like_href}&amp;t={$facebook_like_title}" onclick="javascript:window.open(this.href,
      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on FaceBook"><i class="pm-vc-sprite facebook-icon"></i></a>
                    <a href="http://twitter.com/home?status=Watching%20{$facebook_like_title}%20on%20{$facebook_like_href}" onclick="javascript:window.open(this.href,
      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on Twitter"><i class="pm-vc-sprite twitter-icon"></i></a>
                    <a href="https://plus.google.com/share?url={$facebook_like_href}" onclick="javascript:window.open(this.href,
      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" rel="tooltip" title="Share on Google+"><i class="pm-vc-sprite google-icon"></i></a>  
                    </div>
                </div>
    
                <div id="pm-vc-embed-content">
                  <hr />
                  <textarea name="pm-embed-code" id="pm-embed-code" rows="3" class="span12" onClick="SelectAll('pm-embed-code');">{$embedcode_to_share}</textarea>
                </div>
                <div id="pm-vc-email-content">
                    <hr />
                    <div id="share-confirmation" class="hide well well-small"></div>
                    <form name="sharetofriend" action="" method="POST" class="form-inline">
                      <input type="text" id="name" name="name" class="input-small inp-small" value="{$s_name}" placeholder="{$lang.your_name}">
                      <input type="text" id="email" name="email" class="input-small inp-small" placeholder="{$lang.friends_email}">
                      {if ! $logged_in}   
                          <input type="text" name="imagetext" class="input-small inp-small" autocomplete="off" placeholder="{$lang.confirm_comment}">
                          <button class="btn btn-small btn-link" onclick="document.getElementById('securimage-share').src = '{$smarty.const._URL}/include/securimage_show.php?sid=' + Math.random(); return false;"><i class="icon-refresh"></i> </button>
                          <img src="{$smarty.const._URL}/include/securimage_show.php?sid={echo_securimage_sid}" id="securimage-share" alt="">
                      {/if}
                      <input type="hidden" name="p" value="detail">
                      <input type="hidden" name="do" value="share">
                      <input type="hidden" name="vid" value="{$video_data.uniq_id}">
                      <button type="submit" name="Submit" class="btn btn-success">{$lang.submit_send}</button>
                    </form>
                </div>
            </div><!-- #pm-vc-share-content -->
            
			<div class="row-fluid pm-author-data">
                <div class="span2">
                    <span class="pm-avatar"><a href="{$video_data.author_profile_href}"><img src="{$video_data.author_avatar_url}" height="50" width="50" alt="" class="img-polaroid" border="0"></a></span>
                </div>

                <div class="span7">
                	<div class="pm-submit-data">{$lang.articles_published} <time datetime="{$video_data.html5_datetime}" title="{$video_data.full_datetime}">{$video_data.time_since_added} {$lang.ago}</time> {$lang.articles_by} <a href="{$smarty.const._URL}/profile.{$smarty.const._FEXT}?u={$video_data.author_username}">{$video_data.author_name}</a> {$lang._in} {$category_name}</div>   
                    <div class="clearfix"></div>
                    {if $smarty.const._MOD_SOCIAL && $logged_in == '1' && $video_data.author_user_id != $s_user_id}
                        {include file="user-follow-button.tpl" profile_data=$video_data profile_user_id=$video_data.author_user_id}
                    {/if}
                </div>

                <div class="span3 pm-video-views-count pull-right">
                    <span class="pm-vc-views">
                    <strong>{$video_data.site_views_formatted}</strong>
                    <small>{$lang.views}</small>
                    </span>
                    <div class="clearfix"></div>
                    <div class="progress" title="{$video_data.up_vote_count_formatted} {$lang._likes}, {$video_data.down_vote_count_formatted} {$lang._dislikes}">
                      <div class="bar bar-success" id="rating-bar-up-pct" style="width: {$video_data.up_pct}%;"></div>
                      <div class="bar bar-danger" id="rating-bar-down-pct" style="width: {$video_data.down_pct}%;"></div>
                    </div>
                </div><!--.pm-video-control-->
            </div><!--.pm-author-data-->
        </div><!--end pm-player-full-width -->
	</div>
</div>

{if $ad_3 != ''}
<div class="pm-ad-zone" align="center">{$ad_3}</div>
{/if}

{if !empty($video_data.description)}
<h2 class="upper-blue">{$lang.description}</h2>
<div class="description text-exp">
    <p itemprop="description">{$video_data.description}</p>
    <p class="show-more"><a href="#" class="show-now">{$lang.show_more}</a></p>
</div>
{/if}

{if !empty($tags)}
<div class="video-tags">
	<strong>{$lang.tags}</strong>: {$tags}
</div>
{/if}

{if $video_data.allow_comments == '1'}
<h2 class="upper-blue">{$lang.post_comment}</h2>
{include file='comment-form.tpl'}
{if ! $logged_in && ! $guests_can_comment}
	{$must_sign_in}
{/if}
{/if}

<h2 class="upper-blue">{$lang.comments}</h2>
<div class="pm-comments comment_box">
{if $video_data.allow_comments == '1'}
{if $comment_count == 0}
    <ul class="pm-ul-comments">
    	<li id="preview_comment"></li>
    </ul>
    <div id="be_the_first">{$lang.be_the_first}</div>
{else}
    <span id="comment-list-container">
		{include file="comment-list.tpl" tpl_name="video-watch"}
		<!-- comment pagination -->
		{if $comment_pagination_obj != ''}
			{include file="comment-pagination.tpl"}
		{/if}
	</span>
{/if}
{else}
	<div>{$lang.comments_disabled}</div>
{/if}
</div>
		</div><!-- #primary -->
        </div><!-- .span8 -->
        
        <div class="span4 pm-sidebar {if $playlist}pm-sidebar-with-playlist{/if}">
		<div id="secondary">

		{if $playlist}
		<div class="pm-sidebar-playlist">

			<div class="pm-playlist-header">
				<div class="pm-playlist-name">
					<a href="#">{smarty_fewchars s=$playlist.title length=40}</a>
				</div>
				<div class="pm-playlist-data">
					<span class="pm-playlist-creator">
                		{$lang._by} <a href="{$smarty.const._URL}/profile.{$smarty.const._FEXT}?u={$playlist.user_username}">{$playlist.user_name}</a>
					</span> 
					&ndash; 
					<span class="pm-playlist-video-count">
						{if $playlist.items_count == 1}
							1 {$lang._video}
						{else}
							{$playlist.items_count} {$lang.videos}
						{/if}
					</span>
				</div>
			</div>
			<div class="pm-playlist-controls">
				<a href="{$playlist_prev_url}" rel="nofollow"><i class="icon-fast-backward icon-white opac7" rel="tooltip" title="{$lang.playlist_prev_video}"></i></a> 
				<a href="{$playlist_next_url}" rel="nofollow"><i class="icon-fast-forward icon-white opac7" rel="tooltip" title="{$lang.playlist_next_video}"></i></a> 
			</div>


            <div class="pm-video-playlist">
                <ul class="unstyled">
                	{foreach from=$playlist_items key=index item=list_video}
                	<li {if $video_data.id == $list_video.id}class="pm-video-playlist-playing"{/if}>
                    <a href="{$list_video.playlist_video_href}" class="pm-video-playlist-href" rel="nofollow"></a>

                		<span class="pm-video-index">
                		{if $video_data.id == $list_video.id}
							&#9658;
						{else}
							{$index+1}
						{/if}
						</span>
						<span class="pm-video-thumb pm-thumb-80">
							<span class="pm-video-li-thumb-info">
								<span class="pm-video-li-thumb-info">
									<span class="pm-label-duration border-radius3 opac7">{$list_video.duration}</span>
								</span>
								<a href="{$list_video.playlist_video_href}" class="pm-thumb-fix pm-thumb-80" rel="nofollow">
									<span class="pm-thumb-fix-clip">
										<img src="{$list_video.thumb_img_url}" alt="{$list_video.video_title}" width="80">
										<span class="vertical-align"></span>
									</span>
								</a>
							</span>
						</span>
						<h3><a href="{$list_video.playlist_video_href}" class="pm-title-link"  rel="nofollow">{$list_video.video_title}</a></h3>
						<div class="pm-video-attr">
							<span class="pm-video-attr-numbers">
								<small>{$list_video.site_views} {$lang.views}</small></small></span>
						</div>
						{if $list_video.featured}
						<span class="pm-video-li-info"> 
							<span class="label label-featured">{$lang._feat}</span>
						</span>
						{/if}
						
                	</li>
					{/foreach}
				</ul>
			</div>
        </div>
        <div class="clearfix"></div>
		{/if}
		

        <div class="widget-related widget" id="pm-related">
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#bestincategory" data-target="#bestincategory" data-toggle="tab">{$lang.tab_related}</a></li>
            <li> / </li>
            <li><a href="#popular" data-target="#popular" data-toggle="tab">{$lang._popular}</a></li>
          </ul>
 
          <div id="pm-tabs" class="tab-content">
            <div class="tab-pane fade in active" id="bestincategory">
                <ul class="pm-ul-top-videos">
                {foreach from=$related_video_list key=k item=related_video_data}
				  <li>
					<div class="pm-li-top-videos">
					<span class="pm-video-thumb pm-thumb-106 pm-thumb-top border-radius2">
					<span class="pm-video-li-thumb-info">
					{if $related_video_data.duration != 0}<span class="pm-label-duration border-radius3 opac7">{$related_video_data.duration}</span>{/if}
					</span>
                    <a href="{$related_video_data.video_href}" class="pm-thumb-fix pm-thumb-145"><span class="pm-thumb-fix-clip"><img src="{$related_video_data.thumb_img_url}" alt="{$related_video_data.attr_alt}" width="145"><span class="vertical-align"></span></span></a>
					</span>
					<h3><a href="{$related_video_data.video_href}" class="pm-title-link">{$related_video_data.video_title}</a></h3>
                    <div class="pm-video-attr">
                    	<span class="pm-video-attr-numbers"><small>{$related_video_data.views_compact} {$lang.views}</small></span>
                    </div>
					{if $related_video_data.featured}
					<span class="pm-video-li-info">
					    <span class="label label-featured">{$lang._feat}</span>
					</span>
					{/if}
					</div>
				  </li>
				{foreachelse}
				  {$lang.top_videos_msg2}
				{/foreach}
                </ul>
            </div>
            <div class="tab-pane fade" id="popular">
                <ul class="pm-ul-top-videos">
                {foreach from=$popular_video_list key=k item=popular_video_data}
				  <li>
					<div class="pm-li-top-videos">
					<span class="pm-video-thumb pm-thumb-106 pm-thumb-top border-radius2">
					<span class="pm-video-li-thumb-info">
					{if $popular_video_data.duration != 0}<span class="pm-label-duration border-radius3 opac7">{$popular_video_data.duration}</span>{/if}
					</span>
                    <a href="{$popular_video_data.video_href}" class="pm-thumb-fix pm-thumb-145"><span class="pm-thumb-fix-clip"><img src="{$popular_video_data.thumb_img_url}" alt="{$popular_video_data.attr_alt}" width="145"><span class="vertical-align"></span></span></a>
					</span>
					<h3><a href="{$popular_video_data.video_href}" class="pm-title-link">{$popular_video_data.video_title}</a></h3>
                    <div class="pm-video-attr">
                    	<span class="pm-video-attr-numbers"><small>{$popular_video_data.views_compact} {$lang.views}</small></span>
                    </div>
					{if $popular_video_data.featured}
					<span class="pm-video-li-info">
					    <span class="label label-featured">{$lang._feat}</span>
					</span>
					{/if}
					</div>
				  </li>
				{foreachelse}
				  {$lang.top_videos_msg2}
				{/foreach}
                </ul>
            </div>
          </div>
          
        </div><!-- .shadow-div -->
        
		</div><!-- #secondary -->
        </div><!-- #sidebar -->
      </div><!-- .row-fluid -->
    </div><!-- .container-fluid -->
{include file="footer.tpl" p="detail" tpl_name="video-watch"}