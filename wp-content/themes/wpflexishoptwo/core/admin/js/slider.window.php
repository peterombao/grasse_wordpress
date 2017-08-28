<?php
// Get the path to the root.
$full_path = __FILE__;
$path_prima = explode( 'wp-content', $full_path );
$url = $path_prima[0];
// Require WordPress bootstrap.
require_once( $url . '/wp-load.php' );
if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here", 'primathemes'));
    global $wpdb;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php _e("Shortcode Generator", 'primathemes'); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript">
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}
	function PRIMAinsertShortcode() {
		var shortcodetext;
		var prima_panel = document.getElementById('prima_panel');
		shortcodetext = "[prima_slider";
		// who is active ?
		if (prima_panel.className.indexOf('current') != -1) {
		
			var primasc_id = document.getElementById('primasc_id').value;
			shortcodetext = shortcodetext + " id=\"" + primasc_id + "\"";
			
			var primasc_width = document.getElementById('primasc_width').value;
			if (primasc_width != '' )
				shortcodetext = shortcodetext + " width=\"" + primasc_width + "\"";
				
			var primasc_height = document.getElementById('primasc_height').value;
			if (primasc_height != '' )
				shortcodetext = shortcodetext + " height=\"" + primasc_height + "\"";
				
			var primasc_animation = document.getElementById('primasc_animation').value;
			shortcodetext = shortcodetext + " animation=\"" + primasc_animation + "\"";
			
			var primasc_speed = document.getElementById('primasc_speed').value;
			shortcodetext = shortcodetext + " speed=\"" + primasc_speed + "\"";
			
			var primasc_duration = document.getElementById('primasc_duration').value;
			shortcodetext = shortcodetext + " duration=\"" + primasc_duration + "\"";
			
			var primasc_direction = document.getElementById('primasc_direction').value;
			shortcodetext = shortcodetext + " direction=\"" + primasc_direction + "\"";
			
			var primasc_control = document.getElementById('primasc_control').value;
			shortcodetext = shortcodetext + " control=\"" + primasc_control + "\"";
			
		}
		shortcodetext = shortcodetext + " ]<br/><br/>";
		if(window.tinyMCE) {
			window.tinyMCE.activeEditor.execCommand( "mceInsertContent",false,shortcodetext);
			//Peforms a clean up of the current editor HTML. 
			//tinyMCEPopup.editor.execCommand('mceCleanup');
			//Repaints the editor. Sometimes the browser has graphic glitches. 
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
		return;
	}
	</script>
	<base target="_self" />
<style>
#link .panel_wrapper, #link div.current { height: 210px; }	
</style>
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
	<form name="cetsHelloWorld" action="#">
	<div class="tabs">
		<ul>
			<li id="prima_tab" class="current"><span><a href="javascript:mcTabs.displayTab('prima_tab','prima_panel');" onmousedown="return false;"><?php _e("Slider", 'primathemes'); ?></a></span></li>
		</ul>
	</div>
	<div class="panel_wrapper">
		<!-- primathemes shortcode panel -->
		<div id="prima_panel" class="panel current">
		<br />
		<?php
		$posts = &get_posts( array( 'post_type' => 'slider', 'numberposts' => 200, 'orderby' => 'title', 'order' => 'ASC' ) );
		if ( $posts ) :
		?>
		<table border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td nowrap="nowrap"><label for="primasc_id"><?php _e("Slider ID:", 'primathemes'); ?></label></td>
            <td>
				<?php
				echo '<select name="primasc_id" id="primasc_id">';
				foreach ( $posts as $post ) {
					echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
				}
				echo '</select>';
				?>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_width"><?php _e("Slider Width:", 'primathemes'); ?></label></td>
            <td>
				<input type="text" id="primasc_width" name="primasc_width" style="width: 70px" value=""/> <?php _e("px", 'primathemes'); ?>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_height"><?php _e("Slider Height:", 'primathemes'); ?></label></td>
            <td>
				<input type="text" id="primasc_height" name="primasc_height" style="width: 70px" value=""/> <?php _e("px", 'primathemes'); ?>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_animation"><?php _e("Slider Animation:", 'primathemes'); ?></label></td>
            <td>
				<select style="width: 70px" name="primasc_animation" id="primasc_animation">
					<option value="fade"><?php _e("Fade", 'primathemes'); ?></option>
					<option value="slide"><?php _e("Slide", 'primathemes'); ?></option>
				</select>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_speed"><?php _e("Slideshow Speed:", 'primathemes'); ?></label></td>
            <td>
				<input type="text" id="primasc_speed" name="primasc_speed" style="width: 70px" value="4000"/> <?php _e("miliseconds", 'primathemes'); ?>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_duration"><?php _e("Animation Duration:", 'primathemes'); ?></label></td>
            <td>
				<input type="text" id="primasc_duration" name="primasc_duration" style="width: 70px" value="600"/> <?php _e("miliseconds", 'primathemes'); ?>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_direction"><?php _e("Direction Nav. (Prev/Next):", 'primathemes'); ?></label></td>
            <td>
				<select style="width: 70px" name="primasc_direction" id="primasc_direction">
					<option value="yes"><?php _e("Yes", 'primathemes'); ?></option>
					<option value="no"><?php _e("No", 'primathemes'); ?></option>
				</select>
            </td>
		</tr>
        <tr>
            <td nowrap="nowrap"><label for="primasc_control"><?php _e("Paging Control Nav.:", 'primathemes'); ?></label></td>
            <td>
				<select style="width: 70px" name="primasc_control" id="primasc_control">
					<option value="yes"><?php _e("Yes", 'primathemes'); ?></option>
					<option value="no"><?php _e("No", 'primathemes'); ?></option>
				</select>
            </td>
		</tr>
        </table>
		<?php
		else :
			printf( __('No Sliders found. Go to <a href="%s" target="_blank">Slider &gt; Add New</a> page to create one.', 'primathemes'), admin_url('post-new.php?post_type=slider') );
		endif;
		?>
		</div>
		<!-- end primathemes shortcode panel -->
	</div>
	<div class="mceActionPanel">
		<?php if ( $posts ) : ?>
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'primathemes'); ?>" onclick="tinyMCEPopup.close();" />
		</div>
		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'primathemes'); ?>" onclick="PRIMAinsertShortcode();" />
		</div>
		<?php endif; ?>
	</div>
</form>
</body>
</html>
