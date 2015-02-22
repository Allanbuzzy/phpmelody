<?php
// +------------------------------------------------------------------------+
// | PHP Melody ( www.phpsugar.com )
// +------------------------------------------------------------------------+
// | PHP Melody IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// | than www.phpsugar.com or if you have received
// | this software from someone who is not a representative of
// | PHPSUGAR, you are involved in an illegal activity.
// | ---
// | In such case, please contact: support@phpsugar.com.
// +------------------------------------------------------------------------+
// | Developed by: PHPSUGAR (www.phpsugar.com) / support@phpsugar.com
// | Copyright: (c) 2004-2013 PHPSUGAR. All rights reserved.
// +------------------------------------------------------------------------+

$showm = '2';
/*
$load_uniform = 0;
$load_ibutton = 0;
$load_tinymce = 0;
$load_swfupload = 0;
$load_colorpicker = 0;
$load_prettypop = 0;
$load_chzn_drop = 0;
*/
$load_scrolltofixed = 1;
$load_chzn_drop = 1;
$load_tagsinput = 1;
$load_ibutton = 1;
$load_prettypop = 1;
$load_import_js = 1;
$_page_title = 'Import from youtube';
include('header.php');

$action = '';
$action = trim($_GET['action']);

$post_n_get = 0;
$post_n_get = count($_POST) + count($_GET);

set_time_limit(120);
/*
 ** Define Local Functions Here **
 */
function get_micro_time()
{
	list($microsec, $sec) = explode(" ", microtime());
	return ((float)$microsec + (float)$sec);
}

function get_exec_time($end, $start)
{
	return round($end - $start, 2);
}

function startElement($parser, $name, $attrs) 
{
	global $youtube, $youtube_array_counter, $youtube_index;
	switch($name) 
	{
		case 'ENTRY':
			$youtube_index = "";
			break;
		case 'YT:DURATION':
			$youtube_index = $name;
			$youtube[$youtube_array_counter][$youtube_index] = $attrs['SECONDS'];
			break;
		case 'MEDIA:RESTRICTION':
			$youtube_index = $name;
			$youtube[$youtube_array_counter][$youtube_index] = array('TYPE' => $attrs['TYPE'],
																	 'RELATIONSHIP' => $attrs['RELATIONSHIP'],
																	 'LIST' => ''
																	);
			break;
		case 'YT:STATE':
			$youtube_index = $name;
			$youtube[$youtube_array_counter][$youtube_index] = array('NAME' => $attrs['NAME'],
																	 'REASONCODE' => $attrs['REASONCODE'],
																	 'REASON' => ''
																	);
			break;
		case 'MEDIA:CONTENT':
			$youtube_index = $name;
			$youtube[$youtube_array_counter][$youtube_index][$attrs['YT:FORMAT']] = array('URL' => $attrs['URL'],
																	 'TYPE' => $attrs['TYPE']
																	);
		break;
		default:
			$youtube_index = $name;
			$youtube[$youtube_array_counter][$youtube_index] = "";
			break;
	}
}

function endElement($parser, $name) 
{
	global $youtube, $youtube_index, $youtube_array_counter;
	switch($name) 
	{
		case "ENTRY":
			$youtube_array_counter++;
			break;
	}
	$youtube_index = "";
}

function characterData($parser, $data) 
{
	global $youtube, $youtube_array_counter, $youtube_index;
	if ($youtube_index != "")
	{
		if ($youtube_index == 'MEDIA:RESTRICTION')
		{
			$youtube[$youtube_array_counter][$youtube_index]['LIST'] .= trim($data);
		}
		else if ($youtube_index == 'YT:STATE')
		{
			$youtube[$youtube_array_counter][$youtube_index]['REASON'] .= trim($data);
		}
		else
		{
			$youtube[$youtube_array_counter][$youtube_index] .= trim($data);
		}
	}
}
/*
	** Finish defining Local Functions **
*/
$search_categories = array(
							'all' => 'All',
							'Autos' => 'Autos &amp; Vehicles',
							'Comedy' => 'Comedy',
							'Education' => 'Education',
							'Entertainment' => 'Entertainment',
							'Film' => 'Film &amp; Animation',
							'Games' => 'Gaming',
							'Howto' => 'Howto &amp; Style',
							'Music' => 'Music',
							'News' => 'News &amp; Politics',
							'Nonprofit' => 'Nonprofits &amp; Activism',
							'People' => 'People &amp; Blogs',
							'Animals' => 'Pets &amp; Animals',
							'Tech' => 'Science &amp; Technology',
							'Sports' => 'Sports',
							'Travel' => 'Travel &amp; Events',
						 );
$search_languages = array(
						  'all' => 'All',  
						  'af' => 'Afrikaans',
						  'sq' => 'Albanian',
						  'ar' => 'Arabic',
						  'hy' => 'Armenian',
						  'az' => 'Azerbaijani',
						  'be' => 'Belarusian',
						  'bs' => 'Bosnian',
						  'bg' => 'Bulgarian',
						  'ca' => 'Catalan; Valencian',
						  'cs' => 'Czech',
						  'zh-Hans' => 'Chinese (simplified)',
						  'zh-Hant' => 'Chinese (traditional)',
						  'cs' => 'Czech',
						  'da' => 'Danish',
						  'de' => 'German',
						  'nl' => 'Dutch',
						  'el' => 'Greek',
						  'en' => 'English',
						  'et' => 'Estonian',
						  'fi' => 'Finnish',
						  'fr' => 'French',
						  'ka' => 'Georgian',
						  'de' => 'German',
						  'gd' => 'Gaelic',
						  'ga' => 'Irish',
						  'ht' => 'Haitian',
						  'he' => 'Hebrew',
						  'hi' => 'Hindi',
						  'hr' => 'Croatian',
						  'hu' => 'Hungarian',
						  'is' => 'Icelandic',
						  'id' => 'Indonesian',
						  'it' => 'Italian',
						  'ja' => 'Japanese',
						  'kk' => 'Kazakh',
						  'ko' => 'Korean',
						  'lt' => 'Lithuanian',
						  'no' => 'Norwegian',
						  'pa' => 'Panjabi',
						  'pl' => 'Polish',
						  'pt' => 'Portuguese',
						  'ro' => 'Romanian',
						  'ru' => 'Russian',
						  'sk' => 'Slovak',
						  'sl' => 'Slovenian',
						  'es' => 'Spanish',
						  'sr' => 'Serbian',
						  'sv' => 'Swedish',
						  'ty' => 'Tahitian',
						  'ta' => 'Tamil',
						  'th' => 'Thai',
						  'tr' => 'Turkish',
						  'uk' => 'Ukrainian',
						  'vi' => 'Vietnamese',
						  'cy' => 'Welsh',
						);
$search_most_popular_regions = array(
									'all' => 'All',
									'DZ' => 'Algeria',
									'AR' => 'Argentina',
									'AU' => 'Australia',
									'AT' => 'Austria',
									'BH' => 'Bahrain',
									'BE' => 'Belgium',
									'BA' => 'Bosnia and Herzegovina',
									'BR' => 'Brazil',
									'BG' => 'Bulgaria',
									'CA' => 'Canada',
									'CL' => 'Chile',
									'CO' => 'Colombia',
									'HR' => 'Croatia',
									'CZ' => 'Czech Republic',
									'DK' => 'Denmark',
									'EG' => 'Egypt',
									'EE' => 'Estonia',
									'FI' => 'Finland',
									'FR' => 'France',
									'DE' => 'Germany',
									'GH' => 'Ghana',
									'GB' => 'Great Britain',
									'GR' => 'Greece',
									'HK' => 'Hong Kong',
									'HU' => 'Hungary',
									'IN' => 'India',
									'ID' => 'Indonesia',
									'IE' => 'Ireland',
									'IL' => 'Israel',
									'IT' => 'Italy',
									'JP' => 'Japan',
									'JO' => 'Jordan',
									'KE' => 'Kenya',
									'KW' => 'Kuwait',
									'LV' => 'Latvia',
									'LB' => 'Lebanon',
									'MK' => 'Macedonia',
									'MY' => 'Malaysia',
									'MX' => 'Mexico',
									'ME' => 'Montenegro',
									'MA' => 'Morocco',
									'NL' => 'Netherlands',
									'NZ' => 'New Zealand',
									'NG' => 'Nigeria',
									'NO' => 'Norway',
									'OM' => 'Oman',
									'PE' => 'Peru',
									'PH' => 'Philippines',
									'PL' => 'Poland',
									'PT' => 'Portugal',
									'QA' => 'Qatar',
									'RO' => 'Romania',
									'RU' => 'Russia',
									'SA' => 'Saudi Arabia',
									'SN' => 'Senegal',
									'RS' => 'Serbia',
									'SG' => 'Singapore',
									'SK' => 'Slovakia',
									'ZA' => 'South Africa',
									'KR' => 'South Korea',
									'ES' => 'Spain',
									'SE' => 'Sweden',
									'CH' => 'Switzerland',
									'TW' => 'Taiwan',
									'TH' => 'Thailand',
									'TN' => 'Tunisia',
									'TR' => 'Turkey',
									'UG' => 'Uganda',
									'UA' => 'Ukraine',
									'AE' => 'United Arab Emirates',
									'GB' => 'United Kingdom',
									'US' => 'United States',
									'YE' => 'Yemen'
							 		);
?>
<div id="adminPrimary">
    <div class="row-fluid" id="help-assist">
        <div class="span12">
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#help-overview" data-toggle="tab">Overview</a></li>
            <li><a href="#help-onthispage" data-toggle="tab">On this page</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade in active" id="help-overview">
			<p>This page puts the entire YouTube.com video database at your fingertips.  Impressive as this is, PHP MELODY will also retrieve all the extra info from each Youtube video so you don't have to. You can keep your site fresh and timely while saving massive amounts of time.</p>
			<p>Start by entering some keywords, select how many results you wish to see and click on the &quot;options” link to auto-populate data from youtube.
Note: at this time, Youtube allows a maximum of 50 results per search.</p>
            </div>
            <div class="tab-pane fade" id="help-onthispage">
			<p>Each result is organized in a stack containing thumbnails, the video title, category, description and tags. Data such as video duration, original URL and more will be imported automatically.</p>
            <p>Youtube provides three thumbnails for each video and PHP MELODY allows you to choose the best one for your site. By default, the chosen thumbnail is the largest one, but changing it will be represented by a blue border.
            You can also do a quality control by using the video preview. Just click the play button overlaying the large thumbnail image and the video will be loaded in a window.</p>
            <p>By default none of the results is selected for import. Clicking on the top right switch from each stack will select it for importing. This is indicated by a green highlight of the stack. If you're satisfied with all the results and wish to import them all at once, you can do that as well by selecting the &quot;SELECT ALL VIDEOS” checkbox (bottom left).<br />
            Enjoy!</p>
            </div>
          </div>
        </div> <!-- /tabbable -->
        </div><!-- .span12 -->
    </div><!-- /help-assist -->
    <div class="content">
	<a href="#" id="show-help-assist">Help</a>
	<h2>Import from Youtube</h2>
	<?php echo $info_msg; ?>

<?php 

load_categories();
if (count($_video_categories) == 0) 
{
	echo pm_alert_error('Please <a href="edit_category.php?do=add&type=video">create a category</a> first.');
}
?>

<form name="search_yt_videos" action="import.php?action=search" method="post" class="form-inline">
<div class="input-append">
<input name="keyword" type="text" value="<?php echo ($_POST['keyword'] != '') ? $_POST['keyword'] : str_replace("+", " ", $_GET['keyword']); ?>" size="35" placeholder="Type keywords to search for..." class="span5" />
<button type="button" class="btn" data-toggle="button" id="import-options">options</button>
<button type="submit" name="submit" class="btn" id="searchVideos" data-loading-text="Searching...">Search</button>
</div>
<span class="searchLoader"><img src="img/ico-loading.gif" width="16" height="16" /></span>


<?php if (($action == 'search' && ($_POST['keyword'] != '' || $_GET['keyword'] != '')) || $action == 'search-popular') : ?>
<div class="opac7 list-choice pull-right">
<button class="btn btn-normal btn-small" data-toggle="button" id="list"><i class="icon-th"></i> </button>
<button class="btn btn-normal btn-small" data-toggle="button" id="stacks"><i class="icon-th-list"></i> </button>
</div>

<div class="pull-right">
<?php if (empty($_GET['sub_id'])) : ?>
<a href="#unsubscribe" data-subscription-id="0" class="btn btn-success btn-small hide" id="btn-unsubscribe" title="Unsubscribe"><i class="icon-ok icon-white"></i> Subscribed</a>
	<a href="#modal_subscribe" data-toggle="modal" class="btn btn-small btn-info" rel="tooltip" title="Save this search for quick access" id="btn-subscribe"><i class="icon-star icon-white"></i> Save this search</a>
<?php else : ?>
<a href="#modal_subscribe" data-toggle="modal" class="btn btn-info btn-small hide" rel="tooltip" title="Save this search for quick access" id="btn-subscribe"><i class="icon-star icon-white"></i> Save this search</a>
<!--<a href="#unsubscribe" data-subscription-id="<?php echo (int) $_GET['sub_id'];?>" class="btn" id="btn-unsubscribe" title="Unsubscribe">Unsubscribe</a>-->
<a href="#unsubscribe" data-subscription-id="<?php echo (int) $_GET['sub_id'];?>" class="btn btn-success btn-small" id="btn-unsubscribe" title="Unsubscribe"><i class="icon-ok icon-white"></i> Subscribed</a>
<?php endif; ?>
<?php echo csrfguard_form('_admin_import_subscriptions'); ?>
</div>
<?php endif; ?>

<div class="clearfix"></div>

<div id="import-opt-content">
<div class="row-fluid">
	<div class="span3">
	<h4>Autocomplete</h4>

	<!--
	<input type="checkbox" name="autofilling" id="autofilling" value="1" <?php if($_POST['autofilling'] == "1" || $_GET['autofilling'] == "1" || $post_n_get == 0) echo 'checked="checked"'; ?> />
	<label for="autofilling">Auto-populate the video title</label>-->
	<label>Autocomplete results with this category</label>
	<?php 
	$selected_categories = array();
	if (is_array($_POST['use_this_category']))
	{
	    $selected_categories = $_POST['use_this_category'];
	}
	else if (is_string($_POST['use_this_category']) && $_POST['use_this_category'] != '') 
	{
	    $selected_categories = (array) explode(',', $_POST['use_this_category']);
	}
	if ($_GET['utc'] != '')
	{
	    $selected_categories = (array) explode(',', $_GET['utc']);
	}

	$categories_dropdown_options = array(
	                                'attr_name' => 'use_this_category[]',
	                                'attr_id' => 'main_select_category',
	                                'select_all_option' => false,
	                                'spacer' => '&mdash;',
	                                'selected' => $selected_categories,
	                                'other_attr' => 'multiple="multiple" size="3" data-placeholder="Import videos into..."',
	                                'option_attr_id' => 'check_ignore'
	                                );
	echo categories_dropdown($categories_dropdown_options);
	?>
	<br>
	<input type="checkbox" name="autodata" id="autodata" value="1" <?php if($_POST['autodata'] == "1" || $_GET['autodata'] == "1" || $post_n_get == 0) echo 'checked="checked"'; ?> />
	<label for="autodata">Autocomplete data from Youtube</label>
	</div>
	<div class="span9">
		<h4>Filter Youtube Videos</h4>
		<div class="row-fluid">
		<div class="span3">
			<label>Category</label><br>
			<select name="search_category" class="span12">
				<?php foreach ($search_categories as $value => $label) : ?>
				<option value="<?php echo $value; ?>" <?php echo ($_GET['search_category'] == $value || $_POST['search_category'] == $value) ? 'selected="selected"' : '';?>><?php echo $label;?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="span3">
			<label>Duration</label><br>
			<select name="search_duration" class="span12">
				<option value="all">All</option>
				<option value="short" <?php echo ($_GET['search_duration'] == 'short' || $_POST['search_duration'] == 'short') ? 'selected="selected"' : ''; ?>>Short (~4 minutes)</option>
				<option value="medium" <?php echo ($_GET['search_duration'] == 'medium' || $_POST['search_duration'] == 'medium') ? 'selected="selected"' : ''; ?>>Medium (4-20 minutes)</option>
				<option value="long" <?php echo ($_GET['search_duration'] == 'long' || $_POST['search_duration'] == 'long') ? 'selected="selected"' : ''; ?>>Long (20+ minutes)</option>
			</select>
		</div>
		<div class="span3">
			<label>Upload date</label><br>
			<select name="search_time" class="span12">
				<option value="all_time">All time</option>
				<option value="today" <?php echo ($_GET['search_time'] == 'today' || $_POST['search_time'] == 'today') ? 'selected="selected"' : ''; ?>>Today</option>
				<option value="this_week" <?php echo ($_GET['search_time'] == 'this_week' || $_POST['search_time'] == 'this_week') ? 'selected="selected"' : ''; ?>>This week</option>
				<option value="this_month" <?php echo ($_GET['search_time'] == 'this_month' || $_POST['search_time'] == 'this_month') ? 'selected="selected"' : ''; ?>>This month</option>
			</select>
		</div>
		<div class="span3">
			<label>Order by</label><br>
			<select name="search_orderby" class="span12">
				<option value="relevance" <?php echo ($_GET['search_orderby'] == 'relevance' || $_POST['search_orderby'] == 'relevance') ? 'selected="selected"' : '';?>>Relevance</option>
				<option value="published" <?php echo ($_GET['search_orderby'] == 'published' || $_POST['search_orderby'] == 'published') ? 'selected="selected"' : '';?>>Upload date</option>
				<option value="viewCount" <?php echo ($_GET['search_orderby'] == 'viewCount' || $_POST['search_orderby'] == 'viewCount') ? 'selected="selected"' : '';?>>View count</option>
				<option value="rating" <?php echo ($_GET['search_orderby'] == 'rating' || $_POST['search_orderby'] == 'rating') ? 'selected="selected"' : '';?>>Rating</option>
			</select> 
		</div>
		</div>
		<hr />
		<div class="row-fluid">

		<div class="span3">
			<label>Results per page <i class="icon-info-sign" rel="tooltip" title="The maximum number of videos/page allowed by the YouTube API is 50."></i></label><br>
			<select name="results" class="span11">
			<option value="10" <?php if($_POST['results'] == 10 || $_GET['results'] == 10) echo 'selected="selected"'; ?>>10 results</option>
			<option value="20" <?php if($_POST['results'] == 20 || $_GET['results'] == 20) echo 'selected="selected"'; ?>>20 results</option>
			<option value="30" <?php if($_POST['results'] == 30 || $_GET['results'] == 30) echo 'selected="selected"'; ?>>30 results</option>
			<option value="50" <?php if($_POST['results'] == 50 || $_GET['results'] == 50 || $post_n_get == 0) echo 'selected="selected"'; ?>>50 results</option>
			</select>
		</div>

		<div class="span3">
			<label>Language</label><br>
			<select name="search_language" class="span12">
				<?php foreach ($search_languages as $lang_code => $label): ?>
				<option value="<?php echo $lang_code; ?>" <?php echo ($_GET['search_language'] == $lang_code || $_POST['search_language'] == $lang_code ) ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="span3">
			<label>License</label><br>
			<select name="search_license" class="span12">
				<option value="all">All</option>
				<option value="cc" <?php echo ($_GET['search_license'] == 'cc' || $_POST['search_license'] == 'cc') ? 'selected="selected"' : '';?>>Creative Commons</option>
				<option value="youtube" <?php echo ($_GET['search_license'] == 'youtube' || $_POST['search_license'] == 'youtube') ? 'selected="selected"' : '';?>>Youtube</option>
			</select>
		</div>
		<div class="span3">
			<label>Features</label><br>
			<label class="checkbox">
				<input type="checkbox" name="search_hd" value="true" <?php echo ($_GET['search_hd'] == 'true' || $_POST['search_hd'] == 'true') ? 'checked="checked"' : ''; ?> /> HD
			</label>
			<label class="checkbox">
				<input type="checkbox" name="search_3d" value="true" <?php echo ($_GET['search_3d'] == 'true' || $_POST['search_3d'] == 'true') ? 'checked="checked"' : ''; ?> /> 3D
			</label>
		</div>

		</div>
	</div>
</div> 
</div>
<hr />
</form>

<!--
<form name="most_popular_feed" action="import.php" method="get" class="form-inline">
<div class="row well">	
<h4>Most Popular</h4>
	<div class="span3">
		Country
		<select name="mp_region">
			<?php foreach ($search_most_popular_regions as $value => $label) : ?>
			<option value="<?php echo $value; ?>" <?php echo ($_GET['mp_region'] == $value || $_POST['mp_region'] == $value) ? 'selected="selected"' : '';?>><?php echo $label;?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="span3">
		Category
		<select name="mp_category">
			<?php foreach ($search_categories as $value => $label) : ?>
			<option value="<?php echo $value; ?>" <?php echo ($_GET['mp_category'] == $value || $_POST['mp_category'] == $value) ? 'selected="selected"' : '';?>><?php echo $label;?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="span3">
		Time<br />
		<select name="mp_time">
			<option value="all" <?php echo ($_GET['mp_time'] == 'all') ? 'selected="selected"' : '';?>>All time</option>
			<option value="today" <?php echo ($_GET['mp_time'] == 'today' || empty($_GET['mp_time'])) ? 'selected="selected"' : ''; ?>>Today</option>
		</select>
	</div>
	<input type="hidden" name="action" value="search-popular" />
	<button type="submit" name="submit" class="btn" id="searchVideos" data-loading-text="Searching...">Search</button> <span class="searchLoader"><img src="img/ico-loading.gif" width="16" height="16" /></span>
</div>
</form>
-->
<?php
if (empty($_GET['action'])) 
{
	$subscriptions = get_import_subscriptions();
	if ($subscriptions['total_results'] > 0)
	{
		$subscriptions_count = $subscriptions['total_results'];
		$subscriptions = $subscriptions['data'];
		
		foreach ($subscriptions as $k => $sub)
		{
			$subscriptions[$k] = unserialize($sub['data']);
			$subscriptions[$k]['sub_id'] = $sub['sub_id'];
			$subscriptions[$k]['sub_name'] = $sub['sub_name'];
			$subscriptions[$k]['last_query_time'] = (int) $sub['last_query_time'];
			$subscriptions[$k]['last_query_results'] = (int) $sub['last_query_results'];
			$subscriptions[$k]['sub_user_id'] = $sub['user_id'];
			$subscriptions[$k]['sub_username'] = $sub['username'];
			$subscriptions[$k]['search_time'] = 'this_week';
			$subscriptions[$k]['page'] = 1;
		}
	?>
	<div class="subscriptions-response-placeholder hide"></div>
	<table class="table table-striped table-bordered pm-tables">
		<thead>
			<th>Subscription</th>
			<th>Videos added this week</th>
			<th></th>
		</thead>
		<tbody>
			<?php foreach ($subscriptions as $k => $sub) : ?>
			<tr id="row-subscription-<?php echo $sub['sub_id']; ?>">
				<td>
					<?php
					$url_params = $sub;
					unset($url_params['sub_name'], $url_params['last_query_time'], $url_params['last_query_results'], $url_params['sub_user_id'], $url_params['sub_username']);
					?>
					<strong><a href="import.php?<?php echo http_build_query($url_params);?>"><?php echo $sub['sub_name'];?></a></strong>
					<br />
					<small>Saved by <a href="<?php echo _URL .'/profile.php?u='. $sub['sub_username'];?>" target="_blank"><?php echo $sub['sub_username'];?></a></small>
				</td>
				<td align="center" style="text-align:center">
					<?php if (import_subscription_cache_fresh($sub['last_query_time'])) : ?>
					<?php echo ($sub['last_query_results'] > 0) ? number_format($sub['last_query_results']) : '0'; ?>
					<?php else : ?>
					<span class="row-subscription-get-results" data-subscription-id="<?php echo $sub['sub_id']; ?>">
						<img src="img/ico-loading.gif" width="16" height="16" />
					</span>
					<?php endif; ?>
				</td>
				<td align="center">
					<a href="#" data-subscription-id="<?php echo $sub['sub_id'];?>" class="link-search-unsubscribe btn btn-small btn-danger pull-right" title="Unsubscribe">Unsubscribe</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo csrfguard_form('_admin_import_subscriptions'); ?>
	<?php 
	}  // end if ($subscriptions['total_results'] > 0)
	?>
<div id="stack-controls" style="display: none;"></div>
<?php
}
/*
 *  Import
 */  
if($_POST['submit'] == 'Import' && ($action == 'import'))
{
	$exec_start = get_micro_time();

	$arr_video_title = $_POST['video_title'];
	$arr_description = $_POST['description'];
	$arr_tags		 = $_POST['tags'];
	$arr_category	 = $_POST['genre'];
	$arr_url		 = $_POST['video_ids'];
	$yt_username	 = $_POST['yt_username'];
	$featured		 = $_POST['featured'];
	$thumb_url		 = $_POST['thumb_url'];
	
	$sources = a_fetch_video_sources();
	$source_id = $sources['youtube']['source_id'];

	$total_videos = count($arr_url);
	$imported_total = 0;
	
	define('PHPMELODY', true);
	require_once( "./src/youtube.php");
	
	$duplicate = 0;

	if(is_array($arr_url))
	foreach($arr_url as $i => $v)
	{	
		$duplicate = count_entries('pm_videos_urls', 'direct', $arr_url[$i]);
		
		if($duplicate == 0)
		{
			$video_details = array(	
								'uniq_id' => '',	
								'video_title' => '',	
								'description' => '',	
								'yt_id' => '',	
								'yt_length' => '',	
								'category' => '',	
								'submitted' => '',	
								'source_id' => '',	
								'language' => '',	
								'age_verification' => '',
								'url_flv' => '',	
								'yt_thumb' => '',
								'mp4' => '',	
								'direct' => '',	
								'tags' => '', 
								'featured' => 0,
								'restricted' => 0,
								'allow_comments' => 1 
								);
			
			$video_details['video_title'] = trim( $arr_video_title[$i] );
			$video_details['description'] = trim( $arr_description[$i] );
			$video_details['tags'] 		  = trim( $arr_tags[$i]		   );
			$video_details['category'] 	  = is_array($arr_category[$i]) ? implode(',', $arr_category[$i]) : '';
			$video_details['direct'] 	  = trim( $arr_url[$i]		   );
			$video_details['source_id']	  = $source_id;
			$video_details['language']	  = 1;
			$video_details['submitted']	  = $userdata['username'];

			$video_details['description'] = nl2br($video_details['description']);
			
			//	generate unique id;
			$found = 0;
			$uniq_id = '';
			//$i = 0;
			do
			{
				$found = 0;
				if(function_exists('microtime'))
					$str = microtime();
				else
					$str = time();
				$str = md5($str);
				$uniq_id = substr($str, 0, 9);
				if(count_entries('pm_videos', 'uniq_id', $uniq_id) > 0)
					$found = 1;
			} while($found === 1);
	
			$video_details['uniq_id'] = $uniq_id;
			
			if($video_details['video_title'] != '')
			{
				$temp = array();
				//	grab video information
				do_main($temp, $video_details['direct'], false);
				
				$video_details['url_flv']	=	$temp['url_flv'];
				$video_details['mp4']		=	$temp['mp4'];
				$video_details['yt_length']	=	$temp['yt_length'];
				$video_details['yt_thumb']	=	$temp['yt_thumb'];
				$video_details['yt_id']	=	$temp['yt_id'];
				
				if($video_details['tags'] == '')
				{
					$video_details['tags']	=	$temp['tags'];
				}
				
				if ($thumb_url[$i] != '')
				{
					$video_details['yt_thumb'] = $thumb_url[$i];
				}
				
				//	download thumbnail
				$img = download_thumb($video_details['yt_thumb'], _THUMBS_DIR_PATH, $uniq_id);
				
				if ($featured[$i] == "1")
				{	
					$video_details['featured'] = 1;
				}
				$modframework->trigger_hook('admin_import_insertvideo_pre');
				$new_video = insert_new_video($video_details, $new_video_id);
				if ($new_video !== true)
				{
					echo pm_alert_error('An error occurred while inserting this video in your database.<br /><strong>MySQL reported:</strong> '. $new_video[0]);
				}
				else
				{
					$modframework->trigger_hook('admin_import_insertvideo_post');
					//	tags?
					if($video_details['tags'] != '')
					{
						$tags = explode(",", $video_details['tags']);
						foreach($tags as $k => $tag)
						{
							$tags[$k] = stripslashes(trim($tag));
						}
						//	remove duplicates and 'empty' tags
						$temp = array();
						for($i = 0; $i < count($tags); $i++)
						{
							if($tags[$i] != '')
								if($i <= (count($tags)-1))
								{
									$found = 0;
									for($j = $i + 1; $j < count($tags); $j++)
									{
										if(strcmp($tags[$i], $tags[$j]) == 0)
											$found++;
									}
									if($found == 0)
										$temp[] = $tags[$i];
								}
						}
						$tags = $temp;
						//	insert tags
						if(count($tags) > 0)
							insert_tags($video_details['uniq_id'], $tags);
					}
					$imported_total++;
				}
				unset($video_details, $temp);
			}
		}
		sleep(1);
	}	//	end for()
	
	$exec_end = get_micro_time();
	
	if ($imported_total == $total_videos)
	{
			$info_msg = 'The selected videos were successfully imported.';
	}
	else
	{
		$info_msg = 'Imported <strong>'.$imported_total.'</strong> out of <strong>'.$total_videos.'</strong> selected videos.';
	}

	if ($imported_total < $total_videos)
	{
		$info_msg .= '<br />Duplicated videos and videos without a title were skipped.';
	}
	
	$info_msg .= '<br />Import took <strong>' . get_exec_time($exec_end, $exec_start) . '</strong> seconds.';

	if($yt_username != '') 
	{
		$params = '';
		$params .= 'action=search&username='. $yt_username;
		$params .= '&results=' .$_POST['results'];
		$params .= '&autofilling=' .$_POST['autofilling'];
		$params .= '&autodata='. $_POST['autodata'];
		$params .= (is_array($_POST['use_this_category'])) ? '&oc=1&utc='. implode(',', $_POST['use_this_category']) : '&oc=0&utc=';
		$info_msg .= '</div><hr /><a href="import_user.php?'. $params .'" class="btn">&larr; Return to <em>'. $yt_username .'\'s</em> videos</a>';
	}
	echo pm_alert_success($info_msg);
	echo '<div id="stack-controls" style="display: none;"></div>';
}

/*
 *  Search
 */ 
if (($action == 'search' && ($_POST['keyword'] != '' || $_GET['keyword'] != '')) || $action == 'search-popular')
{
	?>

	<div class="subscriptions-response-placeholder hide"></div>
	<div class="clearfix"></div>
	<form name="import_videos" action="import.php?action=import" method="post">
    <?php $modframework->trigger_hook('admin_import_importopts'); ?>
    <div id="vs-grid">
	<?php
	
	$page = (int) $_GET['page'];

	if(empty($page))
		$page = 1;
	
	$autodata = 0;
	$autofilling = 0;
	$overwrite_category = array();
	
	if(isset($_POST['submit']) && !empty($_POST['keyword']))
	{
		$v				= trim($_POST['keyword']);
		$import_results	= $_POST['results'];
		
		if($_POST['autofilling'] == '1') 
		{
			$autofill = $_POST['keyword'];			
			$autofilling = 1;
		}
		if($_POST['autodata'] == '1')
		{
			$autodata = 1;
		}
		if (is_array($_POST['use_this_category']))
		{
			$overwrite_category = $_POST['use_this_category'];
		}
	}
	elseif($_GET['keyword'] != '')
	{
		$v				= urldecode($_GET['keyword']);
		
		if($_GET['results'] != '')
		{
			$import_results	= (int) $_GET['results'];
		}
		else
		{
			$import_results = 20;
		}
		
		if($_GET['autofilling'] == 1)
		{
			$autofill = urldecode($_GET['keyword']);
			$autofilling = 1;
		}
		if($_GET['autodata'] == 1)
		{
			$autodata = 1;
		}
		if($_GET['oc'] == 1)	//	oc = overwrite_category
		{
			$overwrite_category = (array) explode(',', $_GET['utc']);	//	utc = use_this_cateogory
		}
	}

	$search_in_category = ($_GET['search_category'] != '') ? trim($_GET['search_category']) : $_POST['search_category'];
	$search_orderby = ($_GET['search_orderby'] != '') ? $_GET['search_orderby'] : $_POST['search_orderby'];
	$search_duration = ($_GET['search_duration'] != '') ? $_GET['search_duration'] : $_POST['search_duration'];
	$search_language = ($_GET['search_language'] != '') ? $_GET['search_language'] : $_POST['search_language'];
	$search_time = ($_GET['search_time'] != '') ? $_GET['search_time'] : $_POST['search_time'];
	$search_license = ($_GET['search_license'] != '') ? $_GET['search_license'] : $_POST['search_license'];
	$search_hd = ($_GET['search_hd'] == 'true' || $_POST['search_hd'] == 'true') ? true : false;
	$search_3d = ($_GET['search_3d'] == 'true' || $_POST['search_3d'] == 'true') ? true : false;
	$search_region = ($_GET['search_region'] != '') ? $_GET['search_region'] : $_POST['search_region'];
	
	$start_from = ($page * $import_results) - $import_results + 1;
	
	if ($action == 'search-popular')
	{
		$start_from = 1;
		$import_results = 50;
		
		$yt_api_url = 'http://gdata.youtube.com/feeds/api/standardfeeds/';

		if ($_GET['mp_region'] != '' && $_GET['mp_region'] != 'all')
		{
			$yt_api_url .= strtoupper($_GET['mp_region']) .'/';
		}
		
		$yt_api_url .= 'most_popular';

		if ($_GET['mp_category'] != '' && $_GET['mp_category'] != 'all')
		{
			$yt_api_url .= '_'. trim($_GET['mp_category']);
		}
		
		$yt_api_url .= '?v=2';
		
		if ($_GET['mp_time'] == 'today')
		{
			$yt_api_url .= '&time=today';
		}

		$yt_api_url .= '&start-index='. $start_from .'&max-results='. $import_results;
	}
	else
	{
		$search_term = str_replace("-", " ", $v);	
		$search_term = urlencode($search_term);
		$v 		 = urlencode($v);
		
		$yt_api_url 	 = 'http://gdata.youtube.com/feeds/api/videos?q='. $search_term .'&v=2&start-index='. $start_from .'&max-results='. $import_results;
		$yt_api_url 	.= '&format=5';

		if ($search_in_category != '' && $search_in_category != 'all')
		{
			$yt_api_url .= '&category='. $search_in_category;
		}
		
		if (in_array($search_orderby, array('relevance', 'published', 'viewCount', 'rating')))
		{
			$yt_api_url .= '&orderby='. $search_orderby;
		}
		
		if (in_array($search_duration, array('short', 'medium', 'long')))
		{
			$yt_api_url .= '&duration='. $search_duration;
		}
		if ($search_language != '' && $search_language != 'all')
		{
			$yt_api_url .= '&lr='. $search_language;
		}
		if (in_array($search_time, array('today', 'this_week', 'this_month'/*, 'all_time'*/)))
		{
			$yt_api_url .= '&time='. $search_time;
		}
		
		$yt_api_url .= ($search_hd) ? '&hd=true' : '';
		$yt_api_url .= ($search_3d) ? '&3d=true' : '';
		
		if (in_array($search_license, array('cc', 'youtube')))
		{
			$yt_api_url .= '&license='. $search_license;
		}
	}

	$youtube = array();
	$youtube_index = "";
	$youtube_array_counter = 0;
	
	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData");
	$error = 0;
	$curl_error = '';
	
	if ( function_exists('curl_init') ) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $yt_api_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
		
		$data = curl_exec($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);
		
		if ( !xml_parse($xml_parser, $data, TRUE) ) 
		{
		   if (xml_get_error_code($xml_parser) == XML_ERROR_INVALID_TOKEN && xml_get_current_line_number($xml_parser) == 1 && strlen($data) > 0)
		   {
		       die(pm_alert_error(sprintf('<strong>API response:</strong> %s', htmlentities($data))));
		   }
		   
		   die(pm_alert_error(sprintf('XML error: %s at line %d',
								       xml_error_string(xml_get_error_code($xml_parser)),
								       xml_get_current_line_number($xml_parser))));
		}
	}
	else if ( ini_get('allow_url_fopen') == 1 ) 
	{
	   if ( $fp = @fopen($yt_api_url, "r") ) 
	   {
	      while ($data = fread($fp, 4096)) 
	      {
	        if ( !xml_parse($xml_parser, $data, feof($fp)) ) 
	        {
	           if (xml_get_error_code($xml_parser) == XML_ERROR_INVALID_TOKEN && xml_get_current_line_number($xml_parser) == 1 && strlen($data) > 0)
	           {
	               die(pm_alert_error(sprintf('<strong>API response:</strong> %s', htmlentities($data))));
	           }
			   
	           die(pm_alert_error(sprintf('XML error: %s at line %d',
							            xml_error_string(xml_get_error_code($xml_parser)),
							            xml_get_current_line_number($xml_parser))));
	         }
	      }
	   }
	   else 
	   {
	      $error = 1;
	   }
	   @fclose($fp);
	}
	else 
	{
		$error = 1;
	}

	if( $error == 1 )
	{
	   // fopen failed, cURL failed, there's nothing else to do but quit
	   ?>
		<div class="alert alert-error">
			<strong>Unable to retrieve requested data.</strong>
			<br />
			<br />
			<?php if ($curl_error != '') 
			{
				echo $curl_error .'<br />';
			}
			?>
			<?php if ( ! function_exists('curl_init') && ! ini_get('allow_url_fopen')) : ?>
			Your system doesn't support remote connections.
			<br /> 
			Ask your hosting provider to enable either <strong>allow_url_fopen</strong> or the <strong>cURL extension</strong>.
			<?php endif;?>
		</div>
   </div><!-- .content -->
</div><!-- .primary -->
			<?php
			include('footer.php');
			exit();
	}
	xml_parser_free($xml_parser);

	// begin formatting
	$alt = 0;
	$total_results = count($youtube);
	$counter = 1;
	$duplicates = 0;
	$total_search_results = $youtube[0]['OPENSEARCH:TOTALRESULTS'];

	if($youtube[0]['OPENSEARCH:TOTALRESULTS'] != 0 && $youtube[0]['OPENSEARCH:TOTALRESULTS'] > $start_from)
	{
		for($i = 0; $i < $total_results; $i++) 
		{
			if ($youtube[$i]['YT:VIDEOID'] != '')
			{
				$id = $youtube[$i]['YT:VIDEOID'];
			}
			else
			{
				//$yid		 = explode("/", $youtube[$i]["ID"]); // v1
				$yid		 = explode(":", $youtube[$i]['ID']); // v2
				$id 		 = $yid[ count($yid)-1 ];
			}
			$count_vids	 = 0;
			$count_vids	 = count_entries('pm_videos', 'yt_id', $id);
			if($count_vids == 0)
			{
				$title		 = str_replace('"', '&quot;', $youtube[$i]['MEDIA:TITLE']);
				$url		 = 'http://www.youtube.com/watch?v='.$id;
				$description = $youtube[$i]["MEDIA:DESCRIPTION"];
				$keywords	 = $youtube[$i]["MEDIA:KEYWORDS"];		
				$restriction = $youtube[$i]['MEDIA:RESTRICTION'];
				$yt_length = $youtube[$i]['YT:DURATION'];

				$no_embed	 = 0;
				if(array_key_exists('YT:NOEMBED', $youtube[$i]))
				{
					$no_embed = 1;
				}
				
				$private	 = 0;
				if(array_key_exists('YT:NOEMBED', $youtube[$i]))
				{
					$private = 1;
				}
				$col = ($alt % 2) ? 'table_row1' : 'table_row2';
				$alt++;		
				
				$buff_video_title = $youtube[$i]['MEDIA:TITLE'];

		?>



<?php
$col_unembed = '';
if($no_embed == 1 || $private == 1)
{
$col_unembed = 'table_row_unembed';
?>
<!--<div class="css_yellow_warn"><span onMouseover="showhint('This video will not work since the owner decided to disable embedding.', this, event, '350px')">YouTube disabled embedding for this video.</span></div>-->
<?php 
}
if(is_array($restriction))
{
$col_unembed = 'table_row_unembed';
	$georestriction = 'This video is ';
	$georestriction .=  ($restriction['RELATIONSHIP'] == 'deny') ? 'geo-restricted' : 'available only'; 
	$georestriction .= ' in the following countries: '.$restriction['LIST'];
}
?>
<div class="video-stack" id="stackid-[<?php echo $counter;?>]">
	<div style="font-size: 10px; font-weight: normal">
	<div class="on_off" rel="tooltip" title="Select this video for import">
    <label for="video_ids[<?php echo $counter;?>]">IMPORT</label>
    <input type="checkbox" id="import-[<?php echo $counter;?>]" name="video_ids[<?php echo $counter;?>]" value="<?php echo $url.'" '; if($no_embed == 1 || $private == 1) echo 'disabled="disabled" id="check_ignore"'; ?> />
    </div>
	</div>
	<a id="video-id-[<?php echo $counter;?>]"></a>
    <input id="video-title[<?php echo $counter;?>]" name="video_title[<?php echo $counter;?>]" type="text" value="<?php echo ucwords($buff_video_title); ?>" size="20" class="video-stack-title required_field" rel="tooltip" title="Click to edit" onClick="SelectAll('video-title[<?php echo $counter;?>]');" />
    <div class="clearfix"></div>
    <div class="video-stack-left">
	<ul class="thumbs_ul_import">
                    <li class="stack-thumb-selected stack-thumb">
                    <?php if (is_array($restriction)) : ?>
                    <span class="video-stack-geo"><a href="#video-id-[<?php echo $counter;?>]" rel="tooltip" data-placement="right" title="<?php echo $georestriction; ?>"><img src="img/ico-geo-warn.png" /></a></span>
                    <?php endif; ?>
                    <span class="stack-thumb-text"><a href="#video-id-[<?php echo $counter;?>]" rel="tooltip" data-placement="right" title="The default thumbnail for this video."><i class="icon-ok icon-white"></i></a></span>
                    <span class="stack-video-duration"><?php echo sec2hms($yt_length); ?></span>
                    <span class="stack-preview"><a href="//www.youtube.com/v/<?php echo $id; ?>?&autoplay=1&v=<?php echo $id; ?>&version=3" rel="prettyPop[flash]" title="<?php echo $title; ?>"><div class="pm-sprite ico-playbutton"></div></a></span>
                    <img src="http://img.youtube.com/vi/<?php echo $id; ?>/mqdefault.jpg" alt="Thumb 1" width="154" height="117" border="0" name="video_thumbnail" videoid="<?php echo $id; ?>" rowid="<?php echo $counter;?>" class="" />
                    </li>
                    <li class="thumbs_li_default stack-thumb-small">
                    <span class="stack-thumb-text"><a href="#video-id-[<?php echo $counter;?>]" rel="tooltip" data-placement="right" title="The default thumbnail for this video."><i class="icon-ok icon-white"></i></a></span>
                    <img src="http://img.youtube.com/vi/<?php echo $id; ?>/2.jpg" alt="Thumb 2" width="71" height="53" border="0" name="video_thumbnail" videoid="<?php echo $id; ?>" rowid="<?php echo $counter;?>" class="" />
                    </li>
                    <li class="thumbs_li_default stack-thumb-small">
                    <span class="stack-thumb-text"><a href="#video-id-[<?php echo $counter;?>]" rel="tooltip" data-placement="right" title="The default thumbnail for this video."><i class="icon-ok icon-white"></i></a></span>
                    <img src="http://img.youtube.com/vi/<?php echo $id; ?>/3.jpg" alt="Thumb 3" width="71" height="53" border="0" name="video_thumbnail" videoid="<?php echo $id; ?>" rowid="<?php echo $counter;?>" class="" />
                    </li>
	</ul>
    <div class="clearfix"></div>
    <label>
    <input type="checkbox" name="featured[<?php echo $counter;?>]" id="check_ignore" value="1" /> <small>Mark as <span class="label label-featured">FEATURED</span></small>
    </label>
    </div><!-- .video-stack-left -->
    <div class="video-stack-right noSearch clearfix">
    <label>CATEGORY <small style="color:red;">*</small></label>
    <div class="video-stack-cats">
	<?php
    $categories_dropdown_options = array(
                'attr_name' => 'genre['. $counter .'][]',
                'attr_id' => 'select_category-'. $counter,
                'select_all_option' => false,
                'spacer' => '&mdash;',
                'selected' => $overwrite_category,
                'other_attr' => 'multiple="multiple" size="3"',
                'option_attr_id' => 'check_ignore'
                );
    echo categories_dropdown($categories_dropdown_options);
    ?>
    </div>

    <div class="clear"></div>
    <label>DESCRIPTION</label>
    <textarea name="description[<?php echo $counter;?>]" id="description[<?php echo $counter;?>]" rows="2" class="video-stack-desc"><?php if($autodata) echo $description;?></textarea>
    <label class="control-label" for="tags">TAGS</label>
    <div class="tagsinput">
    <input type="text" id="tags_addvideo_<?php echo $counter;?>" name="tags[<?php echo $counter;?>]" value="<?php if($autodata) echo $keywords;?>" class="tags" />
    </div>          
    <input type="hidden" id="thumb_url_<?php echo $counter;?>" name="thumb_url[<?php echo $counter;?>]" value="http://img.youtube.com/vi/<?php echo $id; ?>/mqdefault.jpg" />
    </div> <!-- .video-stack-right -->
</div><!-- .video-stack -->
		<?php
				$counter++;
			}
			else
			{
				$duplicates++;
			}
		}	//	end for()
	}	//	end if()
	else
	{
		?>
 
        <div class="alert alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        Your search did not return any results. Try using different keywords or options.
        </div>

		<?php
	}
	
	if($duplicates == $total_results)
	{
		//	All videos found 
		?>

        <div class="alert alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <p>The videos results for this page are already in your database.</p>
        <p>Please try again by visiting the next page, selecting more than <?php echo $import_results; ?> results or by using different keywords.</p>
        </div>

	<?php
	}
	?>
        
        
			<div class="clearfix"></div>
            <div id="stack-controls" class="row-fluid">
            <div class="span4" style="text-align: left;">
                <label class="checkbox import-all">
                <input type="checkbox" name="checkall" id="checkall" class="btn" onclick="checkUncheckAll(this);"/> <small>SELECT ALL VIDEOS</small>
                </label>				
            </div>

			<div class="span4">
			
			<?php
			//	generate pagination	
			if($total_search_results > 0)
			{
			 if($total_search_results > 1000)
			 {
				$total_search_results = 1000; // API limit
			 }
			?>
			
			<div class="pagination pagination-centered">
			<?php
			
			// generate smart pagination
			$filename = 'import.php';
			$query_params = array(	'action' => 'search',
									'keyword' => ($_POST['keyword'] != '') ? $_POST['keyword'] : $_GET['keyword'],
									'results' => $import_results,
									'page' => $page,
									'autofilling' => $autofilling,
									'autodata' => $autodata,
									);
			
			if (count($overwrite_category) > 0)
			{
				$query_params['oc'] = 1;
				$query_params['utc'] = implode(',', $overwrite_category);
			}
			else
			{
				$query_params['oc'] = 0;
				$query_params['utc'] = '';
			}
			
			if ($action == 'search-popular')
			{
				$query_params['action'] = 'search-popular';
				$query_params['mp_category'] = $_GET['mp_category'];
				$query_params['mp_time'] = $_GET['mp_time'];
				$query_params['mp_region'] = $_GET['mp_region'];
			}
			else
			{
				if ($search_in_category != '' && $search_in_category != 'all')
				{
					$query_params['search_category'] = $search_in_category;
				}
				
				if (in_array($search_orderby, array('relevance', 'published', 'viewCount', 'rating')))
				{
					$query_params['search_orderby'] = $search_orderby;
				}
				
				if (in_array($search_duration, array('short', 'medium', 'long')))
				{
					$query_params['search_duration'] = $search_duration;
				}
				
				if ($search_language != '' && $search_language != 'all')
				{
					$query_params['search_language'] = $search_language;
				}
				
				if (in_array($search_time, array('today', 'this_week', 'this_month'/*, 'all_time'*/)))
				{
					$query_params['search_time'] = $search_time;
				}
				
				if ($search_license != '' && $search_license != 'all')
				{
					$query_params['search_license'] = $search_license;
				}
				
				if ($search_hd)
				{
					$query_params['search_hd'] = 'true';
				}
				
				if ($search_3d)
				{
					$query_params['search_3d'] = 'true';
				}
				
				if ($_GET['sub_id'] != '')
				{
					$query_params['sub_id'] = (int) $_GET['sub_id'];
				}
			}

			echo a_generate_smart_pagination($page, $total_search_results, $import_results, 1, $filename, http_build_query($query_params));
			?>
			</div>
		   
		
			<div class="clearfix"></div>
			<?php
			} // end if($youtube[0]['OPENSEARCH:TOTALRESULTS'] > 0)
			?>
			
			</div>
            
			<div class="span4">
			<div style="padding-right: 10px;">
            <span class="importLoader"><img src="img/ico-loader.gif" width="16" height="16" /></span>
            <button type="submit" name="submit" class="btn btn-success btn-strong" value="Import" id="submitImport" data-loading-text="Importing...">Import <span id="status"><span id="count">0</span></span> videos </button>
	        </div>
			</div>
            </div><!-- #stack-controls -->
		</div><!-- #vs-grid -->
        <?php
		// end <table>
		?>
	<!-- search form information -->
	<?php if ($action == 'search') : ?>
	<input type="hidden" name="keyword" value="<?php echo htmlspecialchars(urldecode($v), ENT_COMPAT,'UTF-8',true); ?>" />
	<?php endif ;?>
	<input type="hidden" name="results" value="<?php echo $import_results; ?>" />
	<input type="hidden" name="autofilling" value="<?php echo $autofilling; ?>" />
	<input type="hidden" name="autodata" value="<?php echo $autodata; ?>" />
	<input type="hidden" name="overwrite_category" value="<?php echo ($_GET['oc'] == 1 || is_array($_POST['use_this_category'])) ? '1' : '0'; ?>" />
	<input type="hidden" name="use_this_category" value="<?php echo implode(',', $overwrite_category); ?>" />

   </form>

<?php
}
else if ($action == 'search' && (empty($_GET['keyword']) || empty($_POST['keyword'])))
{
	echo pm_alert_error('Please enter your keywords first.');
}
?>
    </div><!-- .content -->
</div><!-- .primary -->

<?php 
if ($action == 'search' || $action == 'search-popular')
{
	if ($action == 'search-popular')
	{	
		$sub_name = 'Popular';
		$sub_name .= ($query_params['mp_region'] != '' && $query_params['mp_region'] != 'all') ? ', '. $search_most_popular_regions[$query_params['mp_region']] : '';
		$sub_name .= ($query_params['mp_category'] != '' && $query_params['mp_category'] != 'all') ? ', '. $query_params['mp_category'] : '';
		$sub_name .= ($query_params['mp_time'] == 'all') ? ', All time' : ', Today';
	}
	else
	{	
		$sub_name = $query_params['keyword'];
		$sub_name .= ($search_in_category != '' && $search_in_category != 'all') ? ', '. $search_in_category : '';
		$sub_name .= (in_array($search_time, array('today', 'this_week', 'this_month'/*, 'all_time'*/))) ? ', '. str_replace('_', ' ', ucfirst($search_time)) : '';
		$sub_name .= (in_array($search_duration, array('short', 'medium', 'long'))) ? ', '. $search_duration : '';
		$sub_name .= ($search_hd) ? ', HD' : '';
		$sub_name .= ($search_3d) ? ', 3D' : '';
	}
	
	$sub_params = serialize($query_params); 

?>

	<!-- subscribe modal -->
	<div class="modal hide" id="modal_subscribe" tabindex="-1" role="dialog" aria-labelledby="modal_subscribe" aria-hidden="true">
	
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
	        <h3>Subscribe</h3>
	    </div>
		
	
		<form name="subscribe-to-search" method="post" action="">
	        <div class="modal-body">
	        	
				<div class="modal-response-placeholder hide"></div>
				
				<div style="padding: 10px; margin: 10px;">
						<label>Subscription label</label>
			            <input type="text" name="sub-name" value="<?php echo htmlspecialchars($sub_name);?>" size="40" />
						<input type="hidden" name="sub-params" value="<?php echo htmlspecialchars($sub_params); ?>" />
						<input type="hidden" name="sub-type" value="search" />
				</div>
			</div>
	        <div class="modal-footer">
		        <input type="hidden" name="status" value="1" />
		        <a class="btn btn-strong btn-normal" data-dismiss="modal" aria-hidden="true">Cancel</a>
		        <button type="submit" name="Submit" value="Submit" class="btn btn-success btn-strong" id="btn-subscribe-modal-save" />Save</button>
		    </div>
	    </form>
	
	</div>
<?php
} 
include('footer.php');
?>