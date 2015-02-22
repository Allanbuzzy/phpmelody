{if $video_data.restricted == '1' && ! $logged_in}
<div class="restricted-video border-radius4">
    <h2>{$lang.restricted_sorry}</h2>
	<p>{$lang.restricted_register}</p>
	<div class="restricted-login">
	{include file='user-auth-login-form.tpl'}
    </div>
</div>
{else}
{if $page == "detail"}
  {if $video_data.video_player == "flvplayer"}
	{literal}
	<div id="Playerholder">
		You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
		a browser with JavaScript support.
	</div>
	<script type="text/javascript">
	var clips = "[ { name: 'ClickToPlay', overlayId: 'play', url: '{/literal}{$video_data.preview_image}{literal}' },";
	{/literal}	
	 {if $total_video_ads > 0}
	  {literal} 
	  
	   clips = clips + "{ name: 'Advertisment', url: '{/literal}{$smarty.const._URL2}/videoads.php?h={$video_ad_hash}{literal}', linkUrl:  '{/literal}{$smarty.const._URL2}/videoads.php?h={$video_ad_hash}%26tz=t{literal}', linkWindow: '{/literal}{$video_ad_target}{literal}', controlEnabled: false, showOnLoadBegin: true },";
	  {/literal}
	 {/if}
	{literal}
	
	clips = clips + " { name: '{/literal}{$video_data.uniq_id}{literal}', url: '{/literal}{$smarty.const._URL2}/videos.php?vid={$video_data.uniq_id}{literal}' } ]";
	
	var flashvars = {
		config: "{ playList: " + clips + ", useSmoothing: true, baseURL: '', autoPlay: {/literal}{if $playlist}true{else}{$smarty.const._AUTOPLAY}{/if}{literal}, autoBuffering: {/literal}{$smarty.const._AUTOBUFF}{literal}, startingBufferLength: 2, bufferLength: 5, loop: false,hideControls: false,initialScale: 'fit', showPlayListButtons: false, useNativeFullScreen: true,controlBarGloss: 'high', controlsOverVideo: 'locked', watermarkUrl: '{/literal}{$smarty.const._WATERMARKURL}{literal}', showWatermark: '{/literal}{$smarty.const._WATERMARKSHOW}{literal}', watermarkLinkUrl: '{/literal}{$smarty.const._WATERMARKLINK}{literal}', controlBarBackgroundColor: '0x{/literal}{$smarty.const._BGCOLOR}{literal}', progressBarColor1: '0xFFFFFF', progressBarColor2: '0x000000', timeDisplayFontColor: '0x{/literal}{$smarty.const._TIMECOLOR}{literal}', noVideoClip: { url: '{/literal}{$smarty.const._URL}/{$smarty.const._UPFOLDER}/notfound.jpg{literal}' }, menuItems: [ false, false, true, true, true, false, false ], showStopButton: true, useHwScaling: false, showOnLoadBegin: true }"
	};
	
	var params = {
		allowfullscreen: "true",
		allowScriptAccess: "always",
		wmode: "opaque"
	};
	var attributes = {};
	swfobject.embedSWF("{/literal}{$smarty.const._URL2}{literal}/players/flowplayer2/flowplayer.swf", "Playerholder", "{/literal}{$smarty.const._PLAYER_W}{literal}", "{/literal}{$smarty.const._PLAYER_H}{literal}", "9.0.115", false, flashvars, params, attributes);

	function onStreamNotFound(a){
 	   reportNotFound(a.name);
	   {/literal}{if $playlist}{literal}
	   	window.location = "{/literal}{$playlist_next_url}{literal}";
		{/literal}{/if}{literal}
	}
	{/literal}{if $playlist}{literal}
	function onClipDone(clip) {
		if (clip.name != "Advertisment") {
			window.location = "{/literal}{$playlist_next_url}{literal}";
		}
	}
	{/literal}{/if}{literal}
	</script>
	{/literal}
	
  {elseif $video_data.video_player == "jwplayer"}
  
		<div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and a browser with JavaScript support.			
		</div>
		{literal}
		<script type="text/javascript" src="{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/jwplayer.js"></script>
		<script type='text/javascript'>
		   	var flashvars = {
				"flashplayer": "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/jwplayer.swf",
		        "playlist": [{
				{/literal}
				{if $video_data.source_id == 0 || $video_data.source_id == 0}
				file: '{$video_data.jw_flashvars.file}',
				streamer: '{$video_data.jw_flashvars.streamer}',
				{if $video_data.jw_flashvars.provider != ''} provider: '{$video_data.jw_flashvars.provider}',
				{/if}
				{if $video_data.jw_flashvars.startparam != ''} 'http.startparam': '{$video_data.jw_flashvars.startparam}',
				{/if}
				{if $video_data.jw_flashvars.loadbalance != ''} 'rtmp.loadbalance': '{$video_data.jw_flashvars.loadbalance}',
				{/if}
				{if $video_data.jw_flashvars.subscribe != ''} 'rtmp.subscribe': '{$video_data.jw_flashvars.subscribe}',
				{/if}
				{elseif $video_data.source_id == 3}
				{literal}
				file: '{/literal}{$video_data.direct}{literal}',
				{/literal}
				{else}		
				{literal}
				file: '{/literal}{$smarty.const._URL2}{literal}/videos.php?vid={/literal}{$video_data.uniq_id}{literal}',
				type: 'video'
				{/literal}
				{/if}
				{literal}
		        }],
		        "controlbar": {
		            "position": "bottom",
		        },
				'wmode': 'transparent',
				'allowfullscreen': 'true',
				'allowscriptaccess': 'always',
				'allownetworking': 'all',
				'name': '{/literal}{$video_data.uniq_id}{literal}',
				'id': '{/literal}{$video_data.uniq_id}{literal}',
                'width': "100%",
				{/literal}{if $playlist}{literal}
                'height': "401px",
				'autostart': "true", 
				{/literal}{else}{literal}
                'height': "{/literal}{$smarty.const._PLAYER_H}{literal}",
				'autostart': "{/literal}{$smarty.const._AUTOPLAY}{literal}", 
				{/literal}{/if}{literal}
				'enablejs': "true",
				'backcolor': "{/literal}{$smarty.const._BGCOLOR}{literal}",
				'frontcolor': "{/literal}{$smarty.const._TIMECOLOR}{literal}",
				'screencolor': "000000",
				'repeat': "false",
				'logo': "{/literal}{$smarty.const._WATERMARKURL}{literal}",
				'linktarget': "_blank",
				'link': "{/literal}{$smarty.const._WATERMARKLINK}{literal}",
				'image': "{/literal}{$video_data.preview_image}{literal}",
				'bufferlength': "5",
				'shownavigation':"true",
				'skin': "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/skins/{/literal}{$smarty.const._JWSKIN}{literal}",
				'stretching': "fill",
		        "plugins": {
				{/literal}{if !$playlist}{literal}
		        		'{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/plugins/drelated.swf': {
							'dxmlpath': '{/literal}{$smarty.const._URL2}{literal}/relatedclips.php?vid={/literal}{$video_data.uniq_id}{literal}',
							'dposition': 'center', // center, bottom, top
							'dskin': '{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/skins/grayskin2.swf',
							'dtarget': '_self' // where to open the related videos when clicked on   			        			
		        		},
		        {/literal}{/if}{literal}
		        		'timeslidertooltipplugin-3': {},
		        		'sharing-3': {
		        			'link': '{/literal}{$video_data.video_href}{literal}',
		        			'code': encodeURIComponent('{/literal}{$embedcode}{literal}')
		        		}
				},
               "events": {
						{/literal}{if $playlist}{literal}
                    	onComplete: function() {
							window.location = "{/literal}{$playlist_next_url}{literal}";
						},
						{/literal}{/if}{literal}
                    	onError: function(object) { 
							ajax_request("video", "do=report&vid={/literal}{$video_data.uniq_id}{literal}&error-message="+ object.message, "", "", false);
							{/literal}{if $playlist}{literal}
							window.location = "{/literal}{$playlist_next_url}{literal}";
							{/literal}{/if}{literal}
						}
                    }
		    };
		{/literal}{$jw_flashvars_override}{literal}
		jwplayer("Playerholder").setup(flashvars);
		</script>

	    {/literal}	
	
  {elseif $video_data.video_player == "jwplayer6"}
		<div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
		</div>
		{literal}
		<script type="text/javascript" src="{/literal}{$smarty.const._URL2}{literal}/players/jwplayer6/jwplayer.js"></script>
		<script type="text/javascript">jwplayer.key="{/literal}{$jwplayerkey}{literal}";</script>
        <script type="text/javascript">
        	var flashvars = {
                    {/literal}
                        {if $video_data.source_id == 0 || $video_data.source_id == 0}
                            file: '{$video_data.jw_flashvars.file}',
                            streamer: '{$video_data.jw_flashvars.streamer}',
                            {literal}rtmp: {{/literal}
                            {if $video_data.jw_flashvars.provider != ''} provider: '{$video_data.jw_flashvars.provider}',
                            {/if}
                            {if $video_data.jw_flashvars.startparam != ''} startparam: '{$video_data.jw_flashvars.startparam}',
                            {/if}
                            {if $video_data.jw_flashvars.loadbalance != ''} loadbalance: {$video_data.jw_flashvars.loadbalance},
                            {/if}
                            {if $video_data.jw_flashvars.subscribe != ''} subscribe: {$video_data.jw_flashvars.subscribe},
                            {/if}
			    {if $video_data.jw_flashvars.securetoken != ''} securetoken: "{$video_data.jw_flashvars.securetoken}",
                            {/if}
                            },
                        {elseif $video_data.source_id == 3}
                            {literal}
                            file: '{/literal}{$video_data.direct}{literal}',
                            {/literal}
                        {elseif $video_data.source_id == 1}
                            {literal}
                            file: '{/literal}{$video_data.url_flv}{literal}',
                            {/literal}
        				{else}		
                            {literal}
                            file: '{/literal}{$video_data.url_flv}{literal}',
                            {/literal}
                        {/if}
                        {literal}
                        flashplayer: "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer6/jwplayer.flash.swf",                        
        				primary: "flash",
                        width: "100%",
						{/literal}{if $playlist}{literal}
		                height: "401",
						autostart: true, 
						{/literal}{else}{literal}
		                height: "{/literal}{$smarty.const._PLAYER_H}{literal}",
						autostart: "{/literal}{$smarty.const._AUTOPLAY}{literal}", 
						{/literal}{/if}{literal}
                        image: '{/literal}{$video_data.preview_image}{literal}',
                        stretching: "fill",
                        events: {
							{/literal}{if $playlist}{literal}
                        	onComplete: function() {
								window.location = "{/literal}{$playlist_next_url}{literal}";
							},
							{/literal}{/if}{literal}
                        	onError: function(object) { 
								ajax_request("video", "do=report&vid={/literal}{$video_data.uniq_id}{literal}&error-message="+ object.message, "", "", false);
								{/literal}{if $playlist}{literal}
								window.location = "{/literal}{$playlist_next_url}{literal}";
								{/literal}{/if}{literal}
							}
                        },
                        logo: {
                        	file: '{/literal}{$smarty.const._WATERMARKURL}{literal}',
                        	link: '{/literal}{$smarty.const._WATERMARKLINK}{literal}',
                        }
	                    };
	                    {/literal}{$jw_flashvars_override}{literal}
            jwplayer("Playerholder").setup(flashvars);
        </script>
	    {/literal}

  {elseif $video_data.video_player == "embed"}
	{if $total_video_ads > 0}
		{literal}
		<script type="text/javascript">
		var Player;
		var embed_code = {/literal}'{$video_data.embed_code}'{literal};
	
		function init() {
			if (document.getElementById) {
				Player = document.getElementById("Playerholder");
			}
		}
		// wait for the page to fully load before initializing
		window.onload = init;
		
		function onClipDone(clip) {
			if (clip.name == "Advertisment") {
				Player.DoStop();
				swfobject.removeSWF("Playerholder");
				ajax_request("video", "do=request&vid={/literal}{$video_data.uniq_id}{literal}", "#embed_Playerholder", "html");
			}
		}
		</script>
		<div id="embed_Playerholder">
		 <div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
		 </div>
		</div>
	
		<script type="text/javascript">
		var clips = "[ { name: 'ClickToPlay', overlayId: 'play' }, { name: 'Advertisment', url: '{/literal}{$smarty.const._URL2}/videoads.php?h={$video_ad_hash}{literal}', linkUrl:  '{/literal}{$smarty.const._URL2}/videoads.php?h={$video_ad_hash}%26tz=t{literal}', linkWindow: '{/literal}{$video_ad_target}{literal}', controlEnabled: false, showOnLoadBegin: true } ]";
		
		var flashvars = {
			config: "{ playList: " + clips + ", useSmoothing: true, baseURL: '', autoPlay: {/literal}{$smarty.const._AUTOPLAY}{literal}, autoBuffering: {/literal}{$smarty.const._AUTOBUFF}{literal}, startingBufferLength: 2, bufferLength: 5, loop: false,hideControls: false,initialScale: 'fit', showPlayListButtons: false, useNativeFullScreen: true,controlBarGloss: 'high', controlsOverVideo: 'locked', watermarkUrl: '{/literal}{$smarty.const._WATERMARKURL}{literal}', showWatermark: '{/literal}{$smarty.const._WATERMARKSHOW}{literal}', watermarkLinkUrl: '{/literal}{$smarty.const._WATERMARKLINK}{literal}', controlBarBackgroundColor: '0x{/literal}{$smarty.const._BGCOLOR}{literal}', progressBarColor1: '0xFFFFFF', progressBarColor2: '0x000000', timeDisplayFontColor: '0x{/literal}{$smarty.const._TIMECOLOR}{literal}', noVideoClip: { url: '{/literal}{$smarty.const._URL}/{$smarty.const._UPFOLDER}/notfound.jpg{literal}' }, menuItems: [ false, false, true, true, true, false, false ], showStopButton: true, useHwScaling: false, showOnLoadBegin: true }"
		};
		
		var params = {
			allowfullscreen: "true",
			allowScriptAccess: "always",
			wmode: "opaque"
		};
		var attributes = {};
		
		swfobject.embedSWF("{/literal}{$smarty.const._URL2}{literal}/players/flowplayer2/flowplayer.swf", "Playerholder", "{/literal}{$smarty.const._PLAYER_W}{literal}", "{/literal}{$smarty.const._PLAYER_H}{literal}", "9.0.115", false, flashvars, params, attributes);
		</script>
		{/literal}
 	{else}
		<div id="Playerholder">
			<noscript>
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
			</noscript>
			{$video_data.embed_code}
		</div>
    {/if}
   {/if}
{/if}


{if $page == "index"}
  {if $video_data.video_player == "flvplayer"}
		{literal}
		<div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
		</div>
	
		<script type="text/javascript">

		var clips = "[ { name: 'ClickToPlay', overlayId: 'play', url: '{/literal}{$video_data.preview_image}{literal}' }, { name: '', url: '{/literal}{$smarty.const._URL2}/videos.php?vid={$voth}{literal}' } ]";
		
		var flashvars = {
			config: "{ playList: " + clips + ", showPlayList: true,useSmoothing: true, baseURL: '', autoPlay: {/literal}{$smarty.const._AUTOPLAY_FEATURED}{literal}, autoBuffering: {/literal}{$smarty.const._AUTOBUFF}{literal}, startingBufferLength: 2, bufferLength: 5, loop: false,hideControls: false,initialScale: 'fit', showPlayListButtons: false, useNativeFullScreen: true,controlBarGloss: 'high', controlsOverVideo: 'ease', controlBarBackgroundColor: '0x{/literal}{$smarty.const._BGCOLOR}{literal}', progressBarColor1: '0xFFFFFF', progressBarColor2: '0x000000', timeDisplayFontColor: '0x{/literal}{$smarty.const._TIMECOLOR}{literal}', noVideoClip: { url: '{/literal}{$smarty.const._URL}/{$smarty.const._UPFOLDER}/notfound.jpg{literal}' },	useHwScaling: false,showMenu: false }"
		};
		var params = {
			allowfullscreen: "true",
			allowScriptAccess: "always",
			wmode: "transparent"
		};
		var attributes = {};
		swfobject.embedSWF("{/literal}{$smarty.const._URL2}{literal}/players/flowplayer2/flowplayer.swf", "Playerholder", "{/literal}{$smarty.const._PLAYER_W_INDEX}{literal}", "{/literal}{$smarty.const._PLAYER_H_INDEX}{literal}", "9.0.115", false, flashvars, params, attributes);
		
		</script>
		{/literal}
  
  {elseif $video_data.video_player == "jwplayer"}
	   <div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
		</div>
		{literal}
		<script type="text/javascript" src="{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/jwplayer.js"></script>
		<script type='text/javascript'>

		   	var flashvars = {
				"flashplayer": "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/jwplayer.swf",
		        "playlist": [{
				{/literal}
				{if $video_data.source_id == 0 || $video_data.source_id == 0}
				file: '{$video_data.jw_flashvars.file}',
				streamer: '{$video_data.jw_flashvars.streamer}',
				{if $video_data.jw_flashvars.provider != ''} provider: '{$video_data.jw_flashvars.provider}',
				{/if}
				{if $video_data.jw_flashvars.startparam != ''} 'http.startparam': '{$video_data.jw_flashvars.startparam}',
				{/if}
				{if $video_data.jw_flashvars.loadbalance != ''} 'rtmp.loadbalance': '{$video_data.jw_flashvars.loadbalance}',
				{/if}
				{if $video_data.jw_flashvars.subscribe != ''} 'rtmp.subscribe': '{$video_data.jw_flashvars.subscribe}',
				{/if}
				{elseif $video_data.source_id == 3}
				{literal}
				file: '{/literal}{$video_data.direct}{literal}',
				{/literal}
				{else}		
				{literal}
				file: '{/literal}{$smarty.const._URL2}{literal}/videos.php?vid={/literal}{$video_data.uniq_id}{literal}',
				type: 'video'
				{/literal}
				{/if}
				{literal}
		        }],
		        "controlbar": {
		            "position": "bottom",
		        },
				'wmode': 'transparent',
				'allowfullscreen': 'true',
				'allowscriptaccess': 'always',
				'allownetworking': 'all',
				'name': '{/literal}{$video_data.uniq_id}{literal}',
				'id': '{/literal}{$video_data.uniq_id}{literal}',
                'width': "100%",
				{/literal}{if $playlist}{literal}
                'height': "401px",
				'autostart': "true", 
				{/literal}{else}{literal}
                'height': "{/literal}{$smarty.const._PLAYER_H_INDEX}{literal}",
				'autostart': "{/literal}{$smarty.const._AUTOPLAY_FEATURED}{literal}", 
				{/literal}{/if}{literal}
				'enablejs': "true",
				'backcolor': "{/literal}{$smarty.const._BGCOLOR}{literal}",
				'frontcolor': "{/literal}{$smarty.const._TIMECOLOR}{literal}",
				'screencolor': "000000",
				'repeat': "false",
				'logo': "{/literal}{$smarty.const._WATERMARKURL}{literal}",
				'linktarget': "_blank",
				'link': "{/literal}{$smarty.const._WATERMARKLINK}{literal}",
				'image': "{/literal}{$video_data.preview_image}{literal}",
				'bufferlength': "5",
				'shownavigation':"true",
				'skin': "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/skins/{/literal}{$smarty.const._JWSKIN}{literal}",
				'stretching': "fill",
		        "plugins": {
		        		'{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/plugins/drelated.swf': {
							'dxmlpath': '{/literal}{$smarty.const._URL2}{literal}/relatedclips.php?vid={/literal}{$video_data.uniq_id}{literal}',
							'dposition': 'center', // center, bottom, top
							'dskin': '{/literal}{$smarty.const._URL2}{literal}/players/jwplayer5/skins/grayskin2.swf', //link to the skin
							'dtarget': '_self' // where to open the related videos when clicked on   			        			
		        		},
		        		'timeslidertooltipplugin-3': {},
		        		'sharing-3': {
		        			'link': '{/literal}{$video_data.video_href}{literal}',
		        			'code': encodeURIComponent('{/literal}{$embedcode}{literal}')
		        		}
				},
               "events": {
                    	onError: function(object) { 
                    	    ajax_request("video", "do=report&vid={/literal}{$video_data.uniq_id}{literal}&error-message="+ object.message, "", "", false);
                    	}
                    },
		    };
		{/literal}{$jw_flashvars_override}{literal}
		jwplayer("Playerholder").setup(flashvars);
		</script>

	    {/literal}	

  {elseif $video_data.video_player == "jwplayer6"}
		<div id="Playerholder">
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
		</div>
		{literal}
		<script type="text/javascript" src="{/literal}{$smarty.const._URL2}{literal}/players/jwplayer6/jwplayer.js"></script>
		<script type="text/javascript">jwplayer.key="{/literal}{$jwplayerkey}{literal}";</script>
        <script type="text/javascript">
        var flashvars = {
                {/literal}
                    {if $video_data.source_id == 0 || $video_data.source_id == 0}
                        file: '{$video_data.jw_flashvars.file}',
                        streamer: '{$video_data.jw_flashvars.streamer}',
                        {literal}rtmp: {{/literal}
                        {if $video_data.jw_flashvars.provider != ''} provider: '{$video_data.jw_flashvars.provider}',
                        {/if}
                        {if $video_data.jw_flashvars.startparam != ''} startparam: '{$video_data.jw_flashvars.startparam}',
                        {/if}
                        {if $video_data.jw_flashvars.loadbalance != ''} loadbalance: {$video_data.jw_flashvars.loadbalance},
                        {/if}
                        {if $video_data.jw_flashvars.subscribe != ''} subscribe: {$video_data.jw_flashvars.subscribe},
                        {/if}
			{if $video_data.jw_flashvars.securetoken != ''} securetoken: "{$video_data.jw_flashvars.securetoken}",
			{/if}
                        },
                    {elseif $video_data.source_id == 3}
                        {literal}
                        file: '{/literal}{$video_data.direct}{literal}',
                        {/literal}
                    {elseif $video_data.source_id == 1}
                        {literal}
                        file: '{/literal}{$video_data.url_flv}{literal}',
                        {/literal}
    				{else}		
                        {literal}
                        file: '{/literal}{$video_data.url_flv}{literal}',
                        {/literal}
                    {/if}
                    {literal}
                    flashplayer: "{/literal}{$smarty.const._URL2}{literal}/players/jwplayer6/jwplayer.flash.swf",                    
    				primary: "flash",
                    width: "100%",
                    height: "{/literal}{$smarty.const._PLAYER_H_INDEX}{literal}",
                    image: '{/literal}{$video_data.preview_image}{literal}',
                    stretching: "fill",
                    logo: {
                    	file: '{/literal}{$smarty.const._WATERMARKURL}{literal}',
                    	link: '{/literal}{$smarty.const._WATERMARKLINK}{literal}',
                    	},
                    events: {
                    	onError: function(object) { 
                    	   ajax_request("video", "do=report&vid={/literal}{$video_data.uniq_id}{literal}&error-message="+ object.message, "", "", false);
                    	}
                    },
    		    autostart: '{/literal}{$smarty.const._AUTOPLAY_FEATURED}{literal}',
                };
        {/literal}{$jw_flashvars_override}{literal}
            jwplayer("Playerholder").setup(flashvars);
        </script>
	    {/literal}
          
  {elseif $video_data.video_player == "embed"}
		<div id="Playerholder">
			<noscript>
			You need to have the <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> installed and
			a browser with JavaScript support.
			</noscript>
			{$video_data.embed_code}
		</div>
   {/if}  
{/if}

{/if}
