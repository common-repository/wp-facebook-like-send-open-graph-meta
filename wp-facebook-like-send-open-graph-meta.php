<?php
/*
Plugin Name: WP Facebook Like Send & Open Graph Meta
Version: 1.3.5
Description: Add Facebook Like and Send buttons after each post. Automatically include Open Graph Meta Tags
Author: Marvie Pons
Author URI: http://tutskid.com/
Donate URI: http://tutskid.com/
Plugin URI: http://tutskid.com/facebook-like-send-opengraph-wp-plugin/
  
Copyright 2013  Marvie Pons (email: celebritybanderas@gmail.com)

Released under GPL License.
*/

define('FBLSOGM_VERSION', '1.3.5');

// REQUIRE MINIMUM VERSION OF WORDPRESS:                                               

function fblsogm_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.0", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.0 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'fblsogm_requires_wordpress_version' );

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'fblsogm_add_defaults');
register_uninstall_hook(__FILE__, 'fblsogm_delete_plugin_options');
add_action('admin_init', 'fblsogm_init' );
add_action('admin_menu', 'fblsogm_add_options_page');
add_filter( 'plugin_action_links', 'fblsogm_plugin_action_links', 10, 2 );

// Delete options table entries ONLY when plugin deactivated AND deleted
function fblsogm_delete_plugin_options() {
	delete_option('fblsogm_options');
}

// Define default option settings
function fblsogm_add_defaults() {
	$tmp = get_option('fblsogm_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('fblsogm_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"drp_select_box" => "true",
						"drp_select_box2" => "light",
						"drp_select_box3" => "like",
						"drp_select_font" => "arial",
						"drp_select_layout" => "standard",
						"chk_button2" => "",
						"txt_one" => "",
						"txt_two" => "",
						"txt_three" => ""
		);
		update_option('fblsogm_options', $arr);
	}
}

// Init plugin options to white list our options
function fblsogm_init(){
	register_setting( 'fblsogm_plugin_options', 'fblsogm_options', 'fblsogm_validate_options' );
}

// Add menu page
function fblsogm_add_options_page() {
	add_options_page('WP Facebook Like Send & Open Graph Meta Options Page', 'Facebook Like Send', 'manage_options', 'fblsogm', 'fblsogm_render_form');
}

// Render the Plugin options form
function fblsogm_render_form() {
	?>
	<div class="wrap">
	
	<!-- Display Plugin Icon, Header, and Description -->
	
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>WP Facebook Like Send & Open Graph Meta Plugin</h2>
		
		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('fblsogm_plugin_options'); ?>
			<?php $options = get_option('fblsogm_options'); ?>
			
<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">

			<div class="postbox">
			<h3 class="hndle"><span>Plugin Settings</span></h3>
				<div class="inside">

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">
			
			
				<!-- Textbox Control -->
				<tr>
					<th scope="row">Enter locale code</th>
					<td>
						<input type="text" size="57" name="fblsogm_options[txt_three]" value="<?php echo $options['txt_three']; ?>" /><span style="color:#666666;margin-left:2px;"><br />Default is <strong>en_US</strong>. Complete listing of all the locales supported by Facebook are <a href="http://www.facebook.com/translations/FacebookLocales.xml" target="_blank">here</a>.</span>
					</td>
				</tr>
				
				<!-- Checkbox Button -->
				<tr valign="top">
					<th scope="row">Enable Buttons on Homepage</th>
					<td>
						<!-- First checkbox button -->
						<label><input name="fblsogm_options[chk_button1]" type="checkbox" value="1" <?php if (isset($options['chk_button1'])) { checked('1', $options['chk_button1']); } ?> /> Check this box if you want the buttons to show on homepage as well</label><br />
											
					</td>
				</tr>
				
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Layout Style</th>
					<td>
						<select name='fblsogm_options[drp_select_layout]'>
							<option value='standard' <?php selected('standard', $options['drp_select_layout']); ?>>standard</option>
							<option value='button_count' <?php selected('button_count', $options['drp_select_layout']); ?>>button_count</option>
							<option value='box_count' <?php selected('box_count', $options['drp_select_layout']); ?>>box_count</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Determines the size and amount of social context next to the button</span>
					</td>
				</tr>
				
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Show Faces</th>
					<td>
						<select name='fblsogm_options[drp_select_box]'>
							<option value='true' <?php selected('true', $options['drp_select_box']); ?>>Show</option>
							<option value='false' <?php selected('false', $options['drp_select_box']); ?>>Hide</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Show or Hide profile pictures below the buttons</span>
					</td>
				</tr>
				
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Font</th>
					<td>
						<select name='fblsogm_options[drp_select_font]'>
							<option value='arial' <?php selected('arial', $options['drp_select_font']); ?>>Arial</option>
							<option value='lucida grande' <?php selected('lucida grande', $options['drp_select_font']); ?>>Lucida Grande</option>
							<option value='segoe ui' <?php selected('segoe ui', $options['drp_select_font']); ?>>Segoe UI</option>
							<option value='tahoma' <?php selected('tahoma', $options['drp_select_font']); ?>>Tahoma</option>
							<option value='trebuchet ms' <?php selected('trebuchet ms', $options['drp_select_font']); ?>>Trebuchet MS</option>
							<option value='verdana' <?php selected('verdana', $options['drp_select_font']); ?>>Verdana</option>
						</select>
						<span style="color:#666666;margin-left:2px;">The font of the plugin</span>
					</td>
				</tr>

				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Color Scheme</th>
					<td>
						<select name='fblsogm_options[drp_select_box2]'>
							<option value='light' <?php selected('light', $options['drp_select_box2']); ?>>Light</option>
							<option value='dark' <?php selected('dark', $options['drp_select_box2']); ?>>Dark</option>
						</select>
						<span style="color:#666666;margin-left:2px;">The color scheme of our plugin</span>
					</td>
				</tr>
				
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Verb to display</th>
					<td>
						<select name='fblsogm_options[drp_select_box3]'>
							<option value='like' <?php selected('like', $options['drp_select_box3']); ?>>Like</option>
							<option value='recommend' <?php selected('recommend', $options['drp_select_box3']); ?>>Recommend</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Like or Recommend?</span>
					</td>
				</tr>
				
				<!-- Checkbox Button -->
				<tr valign="top">
					<th scope="row">Hide Send Button</th>
					<td>
						<!-- Second checkbox button -->
						<label><input name="fblsogm_options[chk_button3]" type="checkbox" value="1" <?php if (isset($options['chk_button3'])) { checked('1', $options['chk_button3']); } ?> /> Check this box to hide the send button</label>										
					</td>
				</tr>	

				<!-- Textbox Control -->
				<tr>
					<th scope="row">Hide Buttons in Page/Pages</th>
					<td>
						<input type="text" size="57" name="fblsogm_options[txt_two]" value="<?php echo $options['txt_two']; ?>" /><span style="color:#666666;margin-left:2px;"><br />Enter the Page IDs, separated by commas</span>
					</td>
				</tr>
				
				<!-- Textbox Control -->
				<tr>
					<th scope="row">Default Image URL</th>
					<td>
						<input id="upload_image" type="text" size="57" name="fblsogm_options[txt_one]" value="<?php echo $options['txt_one']; ?>" />
						<input id="upload_image_button" class="button" type="button" value="Upload Image" /><br />
						Enter full URL including http:// or upload an image.<br />
						<span style="color:#666666;margin-left:2px;">Image will be use if there is no featured image or any image in the posts/pages or in the content. The recommended image referenced by og:image must be at least 200px in both dimensions.</span>
					</td>
				</tr>

				<!-- Checkbox Button -->
				<tr valign="top">
					<th scope="row">Show Your Support</th>
					<td>
						<!-- Second checkbox button -->
						<label><input name="fblsogm_options[chk_button2]" type="checkbox" value="1" <?php if (isset($options['chk_button2'])) { checked('1', $options['chk_button2']); } ?> /> Support this free plug-in with a small powered by link at your page footer. Thank you!</label>										
					</td>
				</tr>				
				
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="fblsogm_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
			</table>
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

		</form>

				</div><!-- .inside -->
			</div><!-- .postbox -->
		
		


	</div> <!-- #post-body-content -->
	
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
				
					<div id="about" class="postbox">
						<h3 class="hndle"><span>About the Plugin:</span></h3>
						<div class="inside">
							<p>You are using <a href="http://tutskid.com/facebook-like-send-opengraph-wp-plugin/" target="_blank" style="color:#72a1c6;"><strong>WP Facebook Like Send & Open Graph Meta</strong></a> Plugin v<?php echo FBLSOGM_VERSION; ?><br /><br />
							WordPress Plugin from <a href="http://tutskid.com/" title="TutsKid.com" target="_blank">TutsKid</a>.</p>
							<p>More Plugins by Marvie Pons:
							<ul>
								<li><a href="http://wordpress.org/plugins/wp-facebook-recommendations-bar/">WP Facebook Recommendations Bar</a></li>
								<li><a href="http://wordpress.org/extend/plugins/yet-another-social-plugin/">Yet Another Social Plugin</a></li>
								<li><a href="http://wordpress.org/extend/plugins/pinterest-verify-meta-tag/">Pinterest Verify Meta Tag</a></li>
								<li><a href="http://wordpress.org/extend/plugins/wp-nofollow-more-links/">WP Nofollow More Links</a></li>
								<li><a href="http://wordpress.org/extend/plugins/rel-nofollow-categories/">Rel Nofollow Categories</a></li>
							</ul>
							</p>
							
						</div><!-- .inside -->
					</div><!-- #about.postbox -->
			
					<div id="about" class="postbox">
						<h3 class="hndle"><span>Enjoy the plugin?</span></h3>
						<div class="inside">		
							<p>If you have found this plugin at all useful, why not consider <a href="http://wordpress.org/extend/plugins/wp-facebook-like-send-open-graph-meta/" target="_blank">giving it a good rating on WordPress.org</a>, <a href="http://twitter.com/?status=Facebook Like and Send Buttons Plugin for WordPress with Open Graph Meta - check it out! http://tutskid.com/?p=144" target="_blank">Tweet about it</a> and buying me a cup of coffee. Thank you!<br />
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="XPHXDYW2PDE38">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form></p>
						</div><!-- .inside -->
					</div><!-- #about.postbox -->

					<div id="about" class="postbox">
						<h3 class="hndle"><span>Latest Blog Posts from TutsKid</span></h3>
						<div class="inside">
							<?php echo tutskid_news(); ?>
							<span><a href="http://tutskid.com/feed/" title="Subscribe with RSS" target="_blank"><img style="border:0px #ccc solid;" src="<?php echo plugins_url(); ?>/wp-facebook-like-send-open-graph-meta/images/rss-icon.png" /></a></span>
							<span><a href="https://www.facebook.com/Tutskidcom" title="Become a fan on Facebook" target="_blank"><img style="border:0px #ccc solid;" src="<?php echo plugins_url(); ?>/wp-facebook-like-send-open-graph-meta/images/facebook-icon.png" /></a></span>
							<span><a href="http://twitter.com/tutskid" title="Follow us on Twitter" target="_blank"><img style="border:0px #ccc solid;" src="<?php echo plugins_url(); ?>/wp-facebook-like-send-open-graph-meta/images/twitter-icon.png" /></a></span>
						</div><!-- .inside -->
					</div><!-- #about.postbox -->

				</div><!-- #side-sortables.meta-box-sortables -->
			</div><!-- .postbox-container -->
	
	</div> <!-- #post-body -->
</div> <!-- #poststuff -->
	
</div>
	<?php	
}

// Sanitize and validate input
function fblsogm_validate_options($input) {
	if ( ! isset( $input['chk_button1'] ) )
		$input['chk_button1'] = null;
	$input['chk_button1'] = ( $input['chk_button1'] == 1 ? 1 : 0 );
	
	if ( ! isset( $input['chk_button2'] ) )
		$input['chk_button2'] = null;
	$input['chk_button2'] = ( $input['chk_button2'] == 1 ? 1 : 0 );

	if ( ! isset( $input['chk_button3'] ) )
		$input['chk_button3'] = null;
	$input['chk_button3'] = ( $input['chk_button3'] == 1 ? 1 : 0 );
	
	 // strip html from textboxes
	$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	
	// strip html from textboxes
	$input['txt_two'] =  wp_filter_nohtml_kses($input['txt_two']); // Sanitize textbox input (strip html tags, and escape characters)
	
	// strip html from textboxes
	$input['txt_three'] =  wp_filter_nohtml_kses($input['txt_three']); // Sanitize textbox input (strip html tags, and escape characters)
	
	return $input;
}

// Display a Settings link on the main Plugins page
function fblsogm_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$fblsogm_links = '<a href="'.get_admin_url().'options-general.php?page=fblsogm">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $fblsogm_links );
	}

	return $links;
}

// ------------------------------------------------------------------------------
// OUR PLUGIN FUNCTIONS:
// ------------------------------------------------------------------------------

function fblsogm_like_send_wp($content){
	$options = get_option('fblsogm_options');

	$layout = $options['drp_select_layout'];
	if($layout == '') { $layout = 'standard'; }
	
	$show_faces = $options['drp_select_box'];
	if($show_faces == '') { $show_faces = 'true'; }
	
	$colorscheme = $options['drp_select_box2'];
	if($colorscheme == '') { $colorscheme = 'light'; }
	
	$action = $options['drp_select_box3'];
	if($action == '') { $action = 'like'; }
	
	$font = $options['drp_select_font'];
	if($font == '') { $font = 'arial'; }
	
	if ( isset($options['chk_button3']) && ($options['chk_button3']!="") ) { $send = 'false'; } else { $send = 'true'; }
	
	$permalink = get_permalink();
	$exclude_pages = $options['txt_two'];
	$exclude_pages = explode(',', $exclude_pages);
	$get_locale = $options['txt_three'];
	if($get_locale == '') { $get_locale = 'en_US'; }
	
	$fb = '<div id="fb-root"></div><script src="http://connect.facebook.net/'.$get_locale.'/all.js#xfbml=1"></script><fb:like href="'.$permalink.'" send="'.$send.'" layout="'.$layout.'" width="450" show_faces="'.$show_faces.'" font="'.$font.'" action="'.$action.'" colorscheme="'.$colorscheme.'"></fb:like>';
	
if(!is_feed() && !is_home() && !is_page( $exclude_pages )) {
	$content .= $fb;
		} else if ( isset($options['chk_button1']) && ($options['chk_button1']!="") && !is_page( $exclude_pages ) ) { 
	$content .= $fb;
	}
return $content;
}

add_action('the_content', 'fblsogm_like_send_wp');

add_action('admin_enqueue_scripts', 'fblsogm_scripts');
 
function fblsogm_scripts() {  
    // Include only if we're on our options page  
    if (fblsogm_plugin_screen()) {  
		wp_enqueue_media();
    	wp_register_script('fblsogm-admin-js', plugins_url('/js/fblsogm-admin.js', __FILE__ ),array('jquery'));
        wp_enqueue_script('fblsogm-admin-js');
    } 
} 

// Check if we're on our options page  
function fblsogm_plugin_screen() {  
    $screen = get_current_screen();  
    if (is_object($screen) || $screen->id == 'fblsogm') {  
        return true;  
    } else {  
        return false;  
    }  
}

function fblsogm_footer() {
	$options = get_option('fblsogm_options');

if ( isset($options['chk_button2']) && ($options['chk_button2']!="") ) { 

		print('<p style="text-align:center;font-size:x-small;color:#666;"><a style="font-weight:normal;color:#666" href="http://tutskid.com/facebook-like-send-opengraph-wp-plugin/" title="WP Facebook Like Send & Open Graph Meta" target="_blank">WP Facebook Like Send & Open Graph Meta</a> powered by <a style="font-weight:normal;color:#666" href="http://tutskid.com/" title="Web Tutorials | How-To Guides | TutsKid" target="_blank">TutsKid.com</a>.</p>');
	}
}

add_action('wp_footer', 'fblsogm_footer');

function tutskid_news() {
	include_once( ABSPATH . WPINC . '/feed.php' );
	$rss = fetch_feed( 'http://feeds.feedburner.com/triptrippertips' );
		if ( ! is_wp_error( $rss ) ) {
			$maxitems = $rss->get_item_quantity( 3 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}
		
echo '<ul>';
    if ( $maxitems == 0 ) {
    echo '<li>';
		echo '<p>The feed is either empty or unavailable.</p>';
	echo '</li>';
    } else {
        foreach ( $rss_items as $item ) {
         echo '<li>';
         echo '<a href="'.esc_url( $item->get_permalink() ).'">'.esc_html( $item->get_title() ).'</a>';
         echo '</li>';
		}
	}
echo '</ul>';
}

//Adding the Open Graph in the Language Attributes
function fblsogm_add_og_doctype_wp( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'fblsogm_add_og_doctype_wp');

add_action('wp_head', 'fblsogm_add_facebook_og_wp');

function fblsogm_add_facebook_og_wp() {
	global $post,$posts;
	
		// get image
		if(function_exists('get_post_thumbnail_id')){
			$catch_thumbs = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
			$catch_thumb = $catch_thumbs[0];
		}

		// If there is no featured image or any image, search post for images and display first one. If none exists, then show the default url.
		if($catch_thumb[0] == ''){
				$out = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $match);
				if ( $out > 0 ){
					$catch_thumb = $match[1][0];					
				} else {
			$options = get_option('fblsogm_options');
			$catch_thumb = $options['txt_one'];
			}
		}
		
		$excerpt = ""; 
		if (has_excerpt($post->ID)) {
			$excerpt = esc_attr(strip_tags(get_the_excerpt($post->ID)));
		}else{
			$excerpt = esc_attr(str_replace("\r\n",' ',substr(strip_tags(strip_shortcodes($post->post_content)), 0, 160)));
		}
		
		$site_description = get_bloginfo( 'description', 'display' );
		if (is_home() || is_front_page() ) {
			$fblsogm_url = get_bloginfo( 'url' );
		} else {
			$fblsogm_url = 'http' . (is_ssl() ? 's' : '') . "://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}

?>

<!-- Facebook Open Graph Tags added by WP Facebook Like Send & Open Graph Meta v<?php echo FBLSOGM_VERSION; ?>: http://tutskid.com/facebook-like-send-opengraph-wp-plugin/ -->
<meta property="og:title" content="<?php if(is_home()) { bloginfo('name'); } elseif(is_category()) { echo single_cat_title();}	elseif(is_tag()) { echo single_tag_title();} else { echo the_title($post->id); } ?>" />
<meta property="og:type" content="<?php if (is_single() || is_page()) { echo "article"; } else { echo "website";} ?>" />
<meta property="og:image" content="<?php echo $catch_thumb; ?>" />
<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
<meta property="og:description" content="<?php if (is_singular()) { echo $excerpt;} else {echo $site_description;} ?>" />
<meta property="og:url" content="<?php echo esc_url( apply_filters( 'fblsogm_url', $fblsogm_url ) ); ?>" />
<!-- Facebook Open Graph Tags end -->

<?php

	}
?>