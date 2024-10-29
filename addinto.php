<?php
/**
* Plugin Name: AddInto
* Plugin URI: http://www.addinto.com/tools/wordpress/
* Description: AddInto is a bookmarking and sharing button that helps website publishers and bloggers spread their content across the web by making it easy for visitors to bookmark and share content to their favorite social destinations. (<a href="options-general.php?page=addinto/addinto.php">Settings</a>)
* Version: 2.3.4
* Author: AddInto
* Author URI: http://www.addinto.com
*/


if (!defined('ADDINTOPLUGINDEFINE')) define('ADDINTOPLUGINDEFINE', 1);
else return;

if ( !defined('WP_CONTENT_URL') ) define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if ( !defined('WP_PLUGIN_URL')  ) define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');

$addIntoPluginPath = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__));

load_plugin_textdomain('addinto', $addIntoPluginPath.'/lang', plugin_basename(dirname(__FILE__)).'/lang');

function addIntoPluginInit ()
{
	global $addIntoPluginPath;
	
	if (get_option('ai2_align') || get_option('addinto_settings') === false) {
	    addIntoPluginDefaultOptions();
	}
	
	add_filter('admin_menu', 'addIntoPluginAdminMenu');
	
	add_filter('the_content', 'addIntoPluginDisplayButton');
	
	$options = get_option('addinto_settings');
	
	if (isset($options['ai2_on_excerpts']) && $options['ai2_on_excerpts'] == 'yes') {
		add_filter('the_excerpt', 'addIntoPluginDisplayButton');
	}
}

// 
function addIntoPluginDefaultOptions ()
{
 	$options = array(
		'ai2_button_logo' => 'text_button',
		'ai2_text_button' => __('Bookmark / Share', 'addinto'),
		'ai2_url_button' => '',
		'ai2_align' => 'left',
		'ai2_on_home' => 'yes',
		'ai2_on_pages' => 'no',
		'ai2_on_archives' => 'yes',
		'ai2_on_categories' => 'yes',
		'ai2_on_excerpts' => 'yes',
		'ai2_button_type' => 'dropdown',
		'ai2_hide_embeds' => 'no',
		'ai2_dd_onclick' => 'onmouseover_dd',
		'ai2_nb_srvs' => '',
		'ai2_nb_columns' => '',
		'ai2_srv' => '',
		'ai2_srvs' => '',
		'ai2_sharebox_srvs' => ''
	);
	
	// Recover old values if exists
	foreach ($options as $name => $value) {
		$old_value = get_option($name);
		if($old_value === false) {
		    $options[$name] = $value;
		} else {
		    $options[$name] = $old_value;
		}
		delete_option($name);
	}
	
	update_option('addinto_settings', $options);
}

function addIntoPluginAdminMenu ()
{
	$page = add_options_page('AddInto Options', 'AddInto', 'manage_options', __FILE__, 'addIntoPluginOptions');
	add_filter('admin_print_styles-'.$page, 'addIntoPluginStyles');
	add_filter('admin_print_scripts-'.$page, 'addIntoPluginScripts');
}

function addIntoPluginStyles()
{
	global $addIntoPluginPath;
	wp_enqueue_style('addinto', $addIntoPluginPath.'/css/ai2css.css');
}

function addIntoPluginScripts()
{
	global $addIntoPluginPath;
	wp_enqueue_script('addinto', $addIntoPluginPath.'/js/ai2js.js');  
}

function addIntoPluginOptions ()
{
	global $addIntoPluginPath;
	$lang = explode(",", get_bloginfo('language'));
	$lang = StrToLower(substr(chop($lang[0]),0,2));
	$lang = ($lang == 'fr') ? 'fr' : 'en';
	
	
	if (!current_user_can('manage_options')) return false;
	
	$new_addinto_options = array();
	
	if(isset($_POST['Submit'])) {
	
		check_admin_referer('addinto-update-options');
		
		$checkbox_array = array('ai2_on_home', 'ai2_on_pages', 'ai2_on_archives', 'ai2_on_categories', 'ai2_on_excerpts', 'ai2_hide_embeds');
		
		foreach($checkbox_array as $checkbox)
		{
			if(!isset($_POST['addinto'][$checkbox])) $new_addinto_options[$checkbox] = 'no';
		}
		
		foreach($_POST['addinto'] as $key => $value)
		{
			$new_addinto_options[$key] = $value;
		}
		
		update_option('addinto_settings', $new_addinto_options);
		
		?>
		<div class="updated fade"><p><strong><?php _e('Options saved', 'addinto') ?></strong></p></div>
		<?php
	}
	
	$options = get_option('addinto_settings');
?>
	
	
	<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e('AddInto Options', 'addinto') ?></h2>
		<form method="post" action="" id="addinto_form">
			<?php wp_nonce_field('addinto-update-options'); ?>
			<table class="form-table addinto_table">
				<tr style="border-top:2px solid #ccc;">
					<th scope="row"><?php _e('Button image (More services button)', 'addinto') ?><div class="addinto_left_min_width"></div></th>
					<td>
						<div class="addinto_right_min_width"></div>
						<div class="addinto_logos addinto_logos_l" style="border-top:1px solid #ccc;"><input type="radio" name="addinto[ai2_button_logo]" value="ai2_16x16.png" id="ai2_16x16" <?php echo ($options['ai2_button_logo'] == 'ai2_16x16.png') ? ' checked="checked"' : '' ?> /></div>
						<div class="addinto_logos addinto_logos_r" style="border-top:1px solid #ccc;"><label for="ai2_16x16"><img src="<?php echo $addIntoPluginPath; ?>/logos/ai2_16x16.png" alt="" border="0" /></label></div>
						
						<?php if($lang == 'fr') { ?>
							<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="partager_v2.gif"<?php echo ($options['ai2_button_logo'] == 'partager_v2.gif') ? ' checked="checked"' : ''; ?> id="partager_v2" /> </div>
							<div class="addinto_logos addinto_logos_r"><label for="partager_v2"><img src="<?php echo $addIntoPluginPath; ?>/logos/partager_v2.gif" alt="" border="0" /></label></div>
							
							<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="favoris_partage_v2.gif"<?php echo ($options['ai2_button_logo'] == 'favoris_partage_v2.gif') ? ' checked="checked"' : ''; ?> id="favoris_partage_v2" /> </div>
							<div class="addinto_logos addinto_logos_r"><label for="favoris_partage_v2"><img src="<?php echo $addIntoPluginPath; ?>/logos/favoris_partage_v2.gif" alt="" border="0" /></label></div>
						<?php } ?>
						
						<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="bookmark_share_v2.gif"<?php echo ($options['ai2_button_logo'] == 'bookmark_share_v2.gif') ? ' checked="checked"' : ''; ?> id="bookmark_share_v2" /> </div>
						<div class="addinto_logos addinto_logos_r"><label for="bookmark_share_v2"><img src="<?php echo $addIntoPluginPath; ?>/logos/bookmark_share_v2.gif" alt="" border="0" /></label></div>
						
						<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="bookmark_v2.gif"<?php echo ($options['ai2_button_logo'] == 'bookmark_v2.gif') ? ' checked="checked"' : ''; ?> id="bookmark_v2" /> </div>
						<div class="addinto_logos addinto_logos_r"><label for="bookmark_v2"><img src="<?php echo $addIntoPluginPath; ?>/logos/bookmark_v2.gif" alt="" border="0" /></label></div>
						
						<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="share_v2.gif"<?php echo ($options['ai2_button_logo'] == 'share_v2.gif') ? ' checked="checked"' : ''; ?> id="share_v2" /> </div>
						<div class="addinto_logos addinto_logos_r"><label for="share_v2"><img src="<?php echo $addIntoPluginPath; ?>/logos/share_v2.gif" alt="" border="0" /></label></div>
						
						<div class="addinto_logos addinto_logos_l addinto_clear"><input type="radio" name="addinto[ai2_button_logo]" value="text_button"<?php echo ($options['ai2_button_logo'] == 'text_button') ? ' checked="checked"' : ''; ?> id="text_button" /> </div>
						<div class="addinto_logos addinto_logos_l"><label for="text_button"><img src="<?php echo $addIntoPluginPath; ?>/logos/ai2_16x16.png" alt="" border="0" style="vertical-align:middle;" /></label></div>
						<div class="addinto_logos" style="width:300px; height:24px; padding:2px 0;"><input type="text" name="addinto[ai2_text_button]" value="<?php echo $options['ai2_text_button']; ?>" onclick="document.getElementById('text_button').checked=true;" /></div>
						
						<div class="addinto_logos addinto_logos_l addinto_clear">
							<input type="radio" name="addinto[ai2_button_logo]" value="url_button"<?php echo ($options['ai2_button_logo'] == 'url_button') ? ' checked="checked"' : ''; ?> id="url_button" /> 
						</div>
						<div class="addinto_logos addinto_logos_r" style="height:24px; padding:2px 0;">
							URL : <input type="text" name="addinto[ai2_url_button]" value="<?php echo $options['ai2_url_button']; ?>" onclick="document.getElementById('url_button').checked=true;" style="width:285px;" />
						</div>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Alignement', 'addinto') ?></th>
					<td>
						<select name="addinto[ai2_align]" style="width:100px;">
							<option value="left"<?php echo ($options['ai2_align'] == 'left') ? ' selected="selected"' : ''; ?>><?php _e('Left', 'addinto') ?></option>
							<option value="center"<?php echo ($options['ai2_align'] == 'center') ? ' selected="selected"' : ''; ?>><?php _e('Center', 'addinto') ?></option>
							<option value="right"<?php echo ($options['ai2_align'] == 'right') ? ' selected="selected"' : ''; ?>><?php _e('Right', 'addinto') ?></option>
						</select>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display on', 'addinto') ?></th>
					<td>
						<div class="addinto_option addinto_option_l" style="border-top:1px solid #ccc;">
							<input id="ai2_on_home" type="checkbox" name="addinto[ai2_on_home]" value="yes"<?php echo ($options['ai2_on_home'] == 'yes') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r" style="border-top:1px solid #ccc;"><label for="ai2_on_home"><?php _e('Home', 'addinto') ?><label></div>
						
						<div class="addinto_option addinto_option_l addinto_clear">
							<input id="ai2_on_pages" type="checkbox" name="addinto[ai2_on_pages]" value="yes"<?php echo ($options['ai2_on_pages'] == 'yes') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r"><label for="ai2_on_pages"><?php _e('Pages', 'addinto') ?><label></div>
						
						<div class="addinto_option addinto_option_l addinto_clear">
							<input id="ai2_on_archives" type="checkbox" name="addinto[ai2_on_archives]" value="yes"<?php echo ($options['ai2_on_archives'] == 'yes') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r"><label for="ai2_on_archives"><?php _e('Archives', 'addinto') ?><label></div>
						
						<div class="addinto_option addinto_option_l addinto_clear">
							<input id="ai2_on_categories" type="checkbox" name="addinto[ai2_on_categories]" value="yes"<?php echo ($options['ai2_on_categories'] == 'yes') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r"><label for="ai2_on_categories"><?php _e('Categories', 'addinto') ?><label></div>
						
						<div class="addinto_option addinto_option_l addinto_clear">
							<input id="ai2_on_excerpts" type="checkbox" name="addinto[ai2_on_excerpts]" value="yes"<?php echo ($options['ai2_on_excerpts'] == 'yes') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r"><label for="ai2_on_excerpts"><?php _e('Excerpts', 'addinto') ?><label></div>
						
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Button type', 'addinto') ?></th>
					<td>
						<select name="addinto[ai2_button_type]" style="width:100px;" id="ai2_button_type">
							<option value="sharebox"<?php echo ($options['ai2_button_type'] == 'sharebox') ? ' selected="selected"' : ''; ?>>ShareBox</option>
							<option value="dropdown"<?php echo ($options['ai2_button_type'] == 'dropdown') ? ' selected="selected"' : ''; ?>>Dropdown</option>
							<option value="static"<?php echo ($options['ai2_button_type'] == 'static') ? ' selected="selected"' : ''; ?>>Static</option>
						</select>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
			</table>
			
			<div id="sharebox_servs" style="display:<?php echo ( $options['ai2_button_type'] == 'sharebox' ) ? 'block' : 'none' ?>;">
			<table class="form-table addinto_table">
				<tr>
					<th scope="row"><?php _e('ShareBox services', 'addinto') ?><div class="addinto_left_min_width"></div></th>
					<td>
						<div class="addinto_right_min_width"></div>
						<fieldset class="addinto_fieldset">
								<legend class="addinto_legend"><?php _e('if empty, default services', 'addinto') ?></legend>
								<i>Bookmark, Facebook, Twitter, Google Buzz, Separator, More</i>
							</fieldset>
						<input size="150" type="hidden" name="addinto[ai2_sharebox_srvs]" id="ai2_sharebox_srvs" value="<?php echo $options['ai2_sharebox_srvs']; ?>" />
						<input size="150" type="hidden" id="addinto_sharebox_services"/>
						<div id="addinto_sharebox_services_sel"></div>
						<div id="addinto_sharebox_services_list_show"><a href="javascript:void(0);"><?php _e('Show services list', 'addinto') ?></a></div>
						<div id="addinto_sharebox_services_list"></div>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
			</table>
			</div>
			
			<!--  dropdown options -->
			<div id="js_options" style="display:<?php echo ( $options['ai2_button_type'] == 'static' ) ? 'none' : 'block' ?>;">
			<table class="form-table addinto_table">
				<tr>
					<th scope="row">
						<?php _e('Hide embedded objects', 'addinto') ?><br />
						(<?php _e('like flash and videos', 'addinto') ?>)
						<div class="addinto_left_min_width"></div>
					</th>
					<td>
						<div class="addinto_right_min_width"></div>
						<input type="checkbox" name="addinto[ai2_hide_embeds]" value="yes"<?php echo ($options['ai2_hide_embeds'] == 'yes') ? ' checked="checked"' : ''; ?> />
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Dropdown menu Options', 'addinto') ?>
					</th>
					<td>
						<div class="addinto_option addinto_option_l" style="border-top:1px solid #ccc;">
							<input id="onmouseover_dd" type="radio" name="addinto[ai2_dd_onclick]" value="onmouseover_dd"<?php echo ($options['ai2_dd_onclick'] == 'onmouseover_dd') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r" style="border-top:1px solid #ccc;">
							<label for="onmouseover_dd"><?php _e('Show the menu onmouseover', 'addinto') ?></label>
						</div>
						<div class="addinto_option addinto_option_l addinto_clear">
							<input id="onclick_dd" type="radio" name="addinto[ai2_dd_onclick]" value="onclick_dd"<?php echo ($options['ai2_dd_onclick'] == 'onclick_dd') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r">
							<label for="onclick_dd"><?php _e('Show the menu onclick', 'addinto') ?></label>
						</div>
						<div class="addinto_option addinto_option_l addinto_clear">
							<input  id="onclick_box"type="radio" name="addinto[ai2_dd_onclick]" value="onclick_box"<?php echo ($options['ai2_dd_onclick'] == 'onclick_box') ? ' checked="checked"' : ''; ?> />
						</div>
						<div class="addinto_option addinto_option_r">
							<label for="onclick_box"><?php _e('Show the lightbox onclick (no dropdown menu)', 'addinto') ?></label>
						</div>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Number of services per page', 'addinto') ?>
					</th>
					<td>
						<input type="text" name="addinto[ai2_nb_srvs]" value="<?php echo $options['ai2_nb_srvs']; ?>" style="width:100px;" /> (<?php _e('if empty, default = 32', 'addinto') ?>)
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Columns number in the lightbox', 'addinto') ?>
					</th>
					<td>
						<select name="addinto[ai2_nb_columns]" style="width:120px;" >
							<option value="2"<?php echo ($options['ai2_nb_columns'] == '2') ? ' selected="selected"' : ''; ?>>2</option>
							<option value="3"<?php echo ($options['ai2_nb_columns'] == '3') ? ' selected="selected"' : ''; ?>>3</option>
							<option value=""<?php  echo ($options['ai2_nb_columns'] == '')  ? ' selected="selected"' : ''; ?>>4 (<?php _e('Default', 'addinto') ?>)</option>
							<option value="5"<?php echo ($options['ai2_nb_columns'] == '5') ? ' selected="selected"' : ''; ?>>5</option>
							<option value="6"<?php echo ($options['ai2_nb_columns'] == '6') ? ' selected="selected"' : ''; ?>>6</option>
						</select>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php _e('Customize services', 'addinto') ?>
					</th>
					<td>
						<div><?php _e('Do not select services for dropdown menu and lightbox to use the default services.', 'addinto') ?></div>
						<div>
							<u><?php _e('Dropdown menu', 'addinto') ?></u> :<br />
							<fieldset class="addinto_fieldset">
								<legend class="addinto_legend"><?php _e('if empty, default services', 'addinto') ?></legend>
								<i>Email, Bookmark, Print, Delicious, Yahoo, Digg, MySpace, Facebook, Live Favorites, Yahoo Buzz, Twitter, FriendFeed, Google Buzz, More</i>
							</fieldset>
							<input size="150" type="hidden" name="addinto[ai2_srv]" id="ai2_srv" value="<?php echo $options['ai2_srv']; ?>" /> 
							<input size="150" type="hidden" id="addinto_service"/>
							<div id="addinto_service_sel"></div>
							<div id="addinto_service_list_show"><a href="javascript:void(0);"><?php _e('Show services list', 'addinto') ?></a></div>
							<div id="addinto_service_list"></div>
						</div>
						<div class="addinto_sparator"></div>
						<div>
							<br /><u>LightBox</u> :<br />
							<fieldset class="addinto_fieldset">
								<legend class="addinto_legend"><?php _e('if empty, default services', 'addinto') ?></legend>
								<i>All services</i>
							</fieldset>
							<input size="150" type="hidden" name="addinto[ai2_srvs]" id="ai2_srvs" value="<?php echo $options['ai2_srvs']; ?>" /> 
							<input size="150" type="hidden" id="addinto_services"/>
							<div id="addinto_services_sel"></div>
							<div id="addinto_services_list_show"><a href="javascript:void(0);"><?php _e('Show services list', 'addinto') ?></a></div>
							<div id="addinto_services_list"></div>
						</div>
						<div class="addinto_sparator"></div>
					</td>
				</tr>
			</table>
			</div>
			
			<!--  Submit button -->
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'addinto') ?>" />
			</p>
		</form>
	</div>
	<?php
}

function addIntoPluginCreateButton ()
{
	global $addIntoPluginPath, $post;
	$options = get_option('addinto_settings');
	$post_link  = urlencode(get_permalink($post->ID));
	$post_title = urlencode(get_the_title($post->ID));
	$logo = $options['ai2_button_logo'];
	$logo_txt = $options['ai2_text_button'];
	$logo_url = $options['ai2_url_button'];
	$dd_srvs = ($options['ai2_srv'] != '') ? 'var ai2_bkmk = "'.$options['ai2_srv'].'";' : ''; // Dropdown services
	$pi_srvs = ($options['ai2_srvs'] != '') ? 'var ai2_bkmks = "'.$options['ai2_srvs'].'";' : ''; // PopIn services
	$hide_embeds = ($options['ai2_hide_embeds'] == 'yes') ? 'var ai2_hide_embeds = true;' : '';
	$servs_nb = ( $options['ai2_nb_srvs'] && ctype_digit($options['ai2_nb_srvs']) ) ? 'var ai2_per_page = "'.$options['ai2_nb_srvs'].'";' : '';
	$cols_nb = ($options['ai2_nb_columns'] != '') ? 'var ai2_cols = "'.$options['ai2_nb_columns'].'";' : '';
	if($options['ai2_dd_onclick'] == 'onclick_dd') { $onclick_dd = 'onclick'; $onclick_box = ''; }
	else if($options['ai2_dd_onclick'] == 'onmouseover_dd') { $onclick_dd = 'onmouseover'; $onclick_box = ''; }
	else { $onclick_box = 'bkmk'; $onclick_dd = 'onclick'; }
	$onmouseout = ($options['ai2_dd_onclick'] != 'onclick_box') ? ' onmouseout="ai2close_bkmk();"' : '';
	$text_button_style = ($logo == 'text_button') ? ' style="background:url(\''.$addIntoPluginPath.'/logos/ai2_16x16.png\') no-repeat scroll 0 0 transparent !important;  vertical-align:text-bottom; line-height:16px; height:16px; padding:0 0 0 20px; display:inline-block; margin:0 3px;"' : '';
	$btnType = $options['ai2_button_type'];
	
	$button = '';
	$button .= '<div style="text-align:'.$options['ai2_align'].'; margin-bottom: 7px;">';
	//
	// Start building button
	if($btnType != 'sharebox') {
		if($btnType == 'static') // Static button, anchor opening
		{
			$button .= '<a'.$text_button_style.' href="http://www.addinto.com/ai?type=bkmk&amp;url='.$post_link.'&amp;title='.$post_title.'" onclick="javascript:(function(){window.open(\'http://www.addinto.com/ai?type=bkmk&amp;url='.$post_link.'&amp;title='.$post_title.'\', \'AddInto\', \'location=no, resizable=yes, scrollbars=yes, toolbar=no, menubar=no, status=no, width=420, height=312, left=\'+(screen.width-420)/2+\', top=\'+(screen.height-312)/2)})(); return false;">';
		}
		else // Dropdown button, anchor opening
		{
			$button .= '<a'.$text_button_style.' href="http://www.addinto.com/ai?type=bkmk&amp;url='.$post_link.'&amp;title='.$post_title.'" '.$onclick_dd.'="ai2display_bkmk(this, \''.$onclick_box.'\', \''.$post_title.'\', \''.$post_link.'\'); return false;"'.$onmouseout.'>';
		}
		// anchor content
		if($logo == 'url_button' && $logo_url != '')
		{
			$button .= '<img src="'.$logo_url.'" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
		}
		else if($logo == 'text_button' && $logo_txt != '')
		{
			$button .= $logo_txt;
		}
		else if($logo != '' && $logo != 'url_button' && $logo != 'text_button')
		{
			$button .= '<img src="'.$addIntoPluginPath.'/logos/'.$logo.'" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
		} else {
			$button .= '<img src="'.$addIntoPluginPath.'/logos/ai2_16x16.png" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
		}
		// colsing anchor
		$button .= '</a>';
	}
	else // ShareBox
	{
		$sharebox_servs = ($options['ai2_sharebox_srvs'] != '') ? strtolower($options['ai2_sharebox_srvs']) : 'bookmark,facebook,twitter,google_buzz,separator,more';
		$arr_sharebox_servs = explode(",", $sharebox_servs);
		$button .= '<div class="addinto_sharebox" addinto:url="'.$post_link.'" addinto:title="'.$post_title.'">';
		foreach($arr_sharebox_servs as $key => $srv)
		{
			$srv = str_replace(array(' ', '.'), '_', trim($srv));
			if($srv == 'more')
			{
				switch($options['ai2_dd_onclick'])
				{
					case 'onclick_dd'	  :	$srv = 'more_dd_click';		break;
					case 'onmouseover_dd' : $srv = 'more_dd'; break;
					default				  :	$srv = 'more';
				}
				
				if($logo == 'url_button' && $logo_url != '')
				{
					$button .= '<a style="vertical-align:text-bottom; line-height:16px; height:16px; display:inline-block;" class="addinto_button_'.$srv.'">';
					$button .= '<img src="'.$logo_url.'" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
				}
				else if($logo == 'text_button' && $logo_txt != '')
				{
					$button .= '<a'.$text_button_style.' class="addinto_button_'.$srv.'">';
					$button .= $logo_txt;
					
				}
				else
				{
					$button .= '<a class="addinto_button_'.$srv.'" style="line-height:16px; vertical-align:text-bottom; height:16px; margin:0 3px; display:inline-block;">';
					if($logo != '' && $logo != 'url_button' && $logo != 'text_button') {
						$button .= '<img src="'.$addIntoPluginPath.'/logos/'.$logo.'" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
					} else {
						$button .= '<img src="'.$addIntoPluginPath.'/logos/ai2_16x16.png" alt="'.__('Bookmark/share via AddInto', 'addinto').'" border="0" />';
					}
				}
				$button .= '</a>';
			}
			else
			{
				$button .= '<a class="addinto_button_'.$srv.'" style="margin:0 3px;"></a>';
			}
		}
		$button .= '</div>';
	}
	//
	// Options and script for Dropdown button/ShareBox
	if($btnType != 'static')
	{
		if( $dd_srvs != '' || $pi_srvs != '' || $hide_embeds != '' || $servs_nb != '' || $cols_nb != '' )
		{
			$button .= '<script type="text/javascript">'.$dd_srvs.$pi_srvs.$hide_embeds.$servs_nb.$cols_nb.'</script>';
		}
		$button .= '<script type="text/javascript" src="http://www.addinto.com/ai/ai2_bkmk.js"></script>';
	}
	// End building button
	$button .= '</div>';
	
	return $button;
}

function addIntoPluginDisplayButton ($content)
{
	$options = get_option('addinto_settings');
	if
	(
		( is_single()																  ) ||
		( is_home()		&&					 $options['ai2_on_home']		 == 'yes' ) ||
		( is_page()		&&					 $options['ai2_on_pages']		 == 'yes' ) ||
		( is_archive()	&& !is_category() && $options['ai2_on_archives']	 == 'yes' ) ||
		( is_category() &&					 $options['ai2_on_categories'] == 'yes' )
	)
	{
		return $content .= addIntoPluginCreateButton();
	}
	else
	{
		return $content;
	}
}

add_action('init', 'addIntoPluginInit');
?>