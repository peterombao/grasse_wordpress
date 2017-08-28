<?php
/*
This extension is based on from Genesis Simple Sidebar plugin by Nathan Rice http://www.nathanrice.net/
Licensed under GPL
*/
add_action('admin_init', 'register_prima_sidebars_settings');
function register_prima_sidebars_settings() {
	register_setting(PRIMA_SIDEBAR_SETTINGS, PRIMA_SIDEBAR_SETTINGS);
	add_option(PRIMA_SIDEBAR_SETTINGS, '__return_empty_array', '', 'yes');
}
add_action('admin_menu', 'prima_sidebars_settings_init');
function prima_sidebars_settings_init() {
	global $menu, $prima, $prima_child_data;
	$theme_data = $prima_child_data;
	$prima->sidebar_settings = add_theme_page( sprintf( __( '%1$s Sidebar Settings', 'primathemes' ), $theme_data['Name'] ), __( 'Sidebar Settings', 'primathemes' ), apply_filters( "prima_settings_capability", 'edit_theme_options' ), 'primathemes-sidebars', 'prima_sidebars_settings_admin' );
}
add_action('admin_init', 'prima_sidebars_action_functions');
function prima_sidebars_action_functions() {
	if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] != 'primathemes-sidebars' ) {
		return;
	}
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'create' ) {
		prima_sidebars_create_sidebar( $_POST['new_sidebar'] );
	}
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delete' && isset( $_REQUEST['id'] ) ) {
		prima_sidebars_delete_sidebar( $_REQUEST['id'] );
	}
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' && !isset( $_REQUEST['id'] ) ) {	
		prima_sidebars_edit_sidebar( $_POST['edit_sidebar'] );
	}
}
function prima_sidebars_settings_admin() {
	global $prima, $prima_child_data;
	$theme_data = $prima_child_data; 
	?>
	<div class="wrap" id="prima-settings">
		<?php
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) :
			$sidebars = get_option( PRIMA_SIDEBAR_SETTINGS );
			if ( array_key_exists( $_REQUEST['id'], (array)$sidebars ) ) {
				$_sidebar = stripslashes_deep( $sidebars[$_REQUEST['id']] );
			} else {
				wp_die( __('Nice try, partner. But that sidebar doesn\'t exist. Click back and try again.','primathemes') );
			}
		
		?>
		<?php screen_icon('themes'); ?>
		<h2><?php _e('Edit Sidebar','primathemes'); ?></h2>
		<form method="post" action="<?php echo admin_url( 'themes.php?page=primathemes-sidebars&amp;action=edit' ); ?>">
		<?php wp_nonce_field('primathemes-sidebars-action_edit-sidebar'); ?>
		<table class="form-table">
			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[name]"><?php _e('Name','primathemes'); ?></label></th> 
				<td><input name="edit_sidebar[name]" id="edit_sidebar[name]" type="text" value="<?php echo esc_html( $_sidebar['name'] ); ?>" size="40" /> 
				<p class="description"><?php _e('A recognizable name for your new sidebar widget area','primathemes'); ?></p></td>
			</tr>
			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[id]"><?php _e('ID','primathemes'); ?></label></th> 
				<td>
				<input type="text" value="<?php echo esc_html( $_REQUEST['id'] ); ?>" size="40" disabled="disabled" />
				<input name="edit_sidebar[id]" id="edit_sidebar[id]" type="hidden" value="<?php echo esc_html( $_REQUEST['id'] ); ?>" size="40" /> 
				<p class="description"><?php _e('The unique ID is used to register the sidebar widget area (cannot be changed)','primathemes'); ?></p></td> 
			</tr>
			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[description]"><?php _e('Description','primathemes'); ?></label></th> 
				<td><textarea name="edit_sidebar[description]" id="edit_sidebar[description]" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( $_sidebar['description'] ); ?></textarea></td> 
			</tr> 
		</table>
		<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Update', 'primathemes'); ?>" /></p> 
		</form>
		<?php else : ?>
		<?php screen_icon('themes'); ?>
		<h2><?php printf( __( '%1$s Sidebar Settings', 'primathemes' ), $theme_data['Name'] ); ?></h2>
		<div id="col-container"> 
		<div id="col-right"> 
		<div class="col-wrap"> 
		<h3><?php _e('Current Sidebars','primathemes'); ?></h3>
		<table class="widefat tag fixed" cellspacing="0"> 
			<thead> 
			<tr> 
			<th scope="col" id="name" class="manage-column column-name"><?php _e('Name','primathemes'); ?></th> 
			<th scope="col" class="manage-column column-slug"><?php _e('ID','primathemes'); ?></th> 
			<th scope="col" id="description" class="manage-column column-description"><?php _e('Description','primathemes'); ?></th> 
			</tr> 
			</thead> 
			<tfoot> 
			<tr> 
			<th scope="col" class="manage-column column-name"><?php _e('Name','primathemes'); ?></th> 
			<th scope="col" class="manage-column column-slug"><?php _e('ID','primathemes'); ?></th> 
			<th scope="col" class="manage-column column-description"><?php _e('Description','primathemes'); ?></th> 
			</tr> 
			</tfoot>
			<tbody id="the-list" class="list:tag">
				<?php prima_sidebars_create_table_rows(); ?>
			</tbody> 
		</table>
		</div> 
		</div><!-- /col-right -->
		<div id="col-left"> 
		<div class="col-wrap"> 
		<div class="form-wrap"> 
		<h3><?php _e('Add New Sidebar','primathemes'); ?></h3>
		<form method="post" action="<?php echo admin_url( 'themes.php?page=primathemes-sidebars&amp;action=create' ); ?>">
		<?php wp_nonce_field('primathemes-sidebars-action_create-sidebar'); ?>	
		<div class="form-field form-required"> 
			<label for="sidebar-name"><?php _e('Name','primathemes'); ?></label> 
			<input name="new_sidebar[name]" id="sidebar-name" type="text" value="" size="40" aria-required="true" /> 
			<p><?php _e('A recognizable name for your new sidebar widget area','primathemes'); ?></p> 
		</div>
		<div class="form-field"> 
			<label for="sidebar-id"><?php _e('ID','primathemes'); ?></label> 
			<input name="new_sidebar[id]" id="sidebar-id" type="text" value="" size="40" /> 
			<p><?php _e('The unique ID is used to register the sidebar widget area','primathemes'); ?></p> 
		</div>
		<div class="form-field"> 
			<label for="sidebar-description"><?php _e('Description','primathemes'); ?></label> 
			<textarea name="new_sidebar[description]" id="sidebar-description" rows="5" cols="40"></textarea> 
		</div> 
		<p class="submit"><input type="submit" class="button" name="submit" id="submit" value="<?php _e('Add New Sidebar','primathemes'); ?>" /></p> 
		</form></div> 
		</div> 
		</div><!-- /col-left -->
		</div><!-- /col-container -->
		<?php endif; ?>
	</div><!-- /wrap -->
<?php
}
function prima_sidebars_create_table_rows() {
	global $wp_registered_sidebars;
	$sidebars = $wp_registered_sidebars;
	$alt = true;
	foreach ( (array)$sidebars as $id => $info ) { ?>
		<?php
			$is_editable = isset( $info['editable'] ) && $info['editable'] ? true : false;
		?>
		<tr <?php if ( $alt ) { echo 'class="alternate"'; $alt = false; } else { $alt = true; } ?>>
			<td class="name column-name">
				<?php
					if ( $is_editable ) {
						printf( '<a class="row-title" href="%s" title="Edit %s">%s</a>', admin_url('themes.php?page=primathemes-sidebars&amp;action=edit&amp;id=' . esc_html( $id ) ), esc_html( $info['name'] ), esc_html( $info['name'] ) );
					} else {
						printf( '<strong class="row-title">%s</strong>', esc_html( $info['name'] ) );
					}
				?>
				<?php if ( $is_editable ) : ?>
				<br />
				<div class="row-actions">
					<span class="edit"><a href="<?php echo admin_url('themes.php?page=primathemes-sidebars&amp;action=edit&amp;id=' . esc_html( $id ) ); ?>"><?php _e('Edit','primathemes'); ?></a> | </span>
					<span class="delete"><a class="delete-tag" href="<?php echo wp_nonce_url( admin_url( 'themes.php?page=primathemes-sidebars&amp;action=delete&amp;id=' . esc_html( $id ) ), 'primathemes-sidebars-action_delete-sidebar' ); ?>"><?php _e('Delete','primathemes'); ?></a></span>
				</div>
				<?php endif; ?>
			</td>
			<td class="slug column-slug"><?php echo esc_html( $id ); ?></td>
			<td class="description column-description"><?php echo esc_html( $info['description'] )?></td>
		</tr>

	<?php
	}
	
}
function prima_sidebars_create_sidebar( $args = array() ) {
	if ( empty( $args['name'] ) || empty( $args['id'] ) ) {
		wp_die( prima_sidebars_error_message(1) );
		exit;
	}
	//	nonce verification
	check_admin_referer('primathemes-sidebars-action_create-sidebar');
	$db = (array)get_option(PRIMA_SIDEBAR_SETTINGS);
	$new = array( 
		sanitize_title_with_dashes( $args['id'] ) => array(
			'name' => esc_html( $args['name'] ),
			'description' => esc_html( $args['description'] )
		)
	);
	if ( array_key_exists( $args['id'], $db ) ) {
		wp_die( prima_sidebars_error_message(2) );
		exit;
	}
	$sidebars = wp_parse_args( $new, $db );
	update_option( PRIMA_SIDEBAR_SETTINGS, $sidebars );
	wp_redirect( admin_url('themes.php?page=primathemes-sidebars&created=true') );
	exit;
}
function prima_sidebars_edit_sidebar( $args = array() ) {
	if ( empty( $args['name'] ) || empty( $args['id'] ) ) {
		wp_die( prima_sidebars_error_message(3) );
		exit;
	}
	//	nonce verification
	check_admin_referer('primathemes-sidebars-action_edit-sidebar');
	$db = (array)get_option(PRIMA_SIDEBAR_SETTINGS);
	$new = array( 
		sanitize_title_with_dashes( $args['id'] ) => array(
			'name' => esc_html( $args['name'] ),
			'description' => esc_html( $args['description'] )
		)
	);
	if ( !array_key_exists( $args['id'], $db ) ) {
		wp_die( prima_sidebars_error_message(3) );
		exit;
	}
	$sidebars = wp_parse_args( $new, $db );
	update_option( PRIMA_SIDEBAR_SETTINGS, $sidebars );
	wp_redirect( admin_url('themes.php?page=primathemes-sidebars&edited=true') );
	exit;
}
function prima_sidebars_delete_sidebar( $id = '' ) {
	if ( empty( $id ) ) {
		wp_die( prima_sidebars_error_message(4) );
		exit;
	}
	//	nonce verification
	check_admin_referer('primathemes-sidebars-action_delete-sidebar');
	$sidebars = (array)get_option( PRIMA_SIDEBAR_SETTINGS );
	if ( !isset( $sidebars[$id] ) ) {
		wp_die( prima_sidebars_error_message(4) );
		exit;
	}
	unset( $sidebars[$id] );
	update_option( PRIMA_SIDEBAR_SETTINGS, $sidebars );
	wp_redirect( admin_url('themes.php?page=primathemes-sidebars&deleted=true') );
	exit;
}
function prima_sidebars_error_message( $error = false ) {
	if ( !$error ) return false;
	switch( (int)$error ) {
		case 1:
			return __('Oops! Please choose a valid Name and ID for this sidebar','primathemes');
			break;
		case 2:
			return __('Oops! That sidebar ID already exists','primathemes');
			break;
		case 3:
			return __('Oops! You are trying to edit a sidebar that does not exist, or is not editable','primathemes');
			break;	
		case 4:
			return __('Oops! You are trying to delete a sidebar that does not exist, or cannot be deleted','primathemes');
			break;
		default:
			return __('Oops! Something went wrong. Try again.','primathemes');
	}
}
add_action('admin_notices', 'prima_sidebars_succeprima_sidebars_message');
function prima_sidebars_succeprima_sidebars_message() {
	if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] != 'primathemes-sidebars' ) {
		return;
	}
	$format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';
	if ( isset( $_REQUEST['created'] ) && $_REQUEST['created'] === 'true' ) {
		printf( $format, __('New sidebar successfully created!','primathemes') );
		return;
	}
	if ( isset( $_REQUEST['edited'] ) && $_REQUEST['edited'] === 'true' ) {
		printf( $format, __('Sidebar successfully edited!','primathemes') );
		return;
	}
	if ( isset( $_REQUEST['deleted'] ) && $_REQUEST['deleted'] === 'true' ) {
		printf( $format, __('Sidebar successfully deleted.','primathemes') );
		return;
	}
	return;
}
add_action('admin_menu', 'prima_sidebars_add_post_metabox');
function prima_sidebars_add_post_metabox() {		
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'primathemes-sidebars' ) || $type == 'post' || $type == 'page' ) {
			add_meta_box('prima_sidebars_post_metabox', __('Custom Sidebar','primathemes'), 'prima_sidebars_post_metabox', $type, 'side', 'low');
		}	
	}
}
function prima_sidebars_post_metabox() { 
	$sidebars = stripslashes_deep( get_option( PRIMA_SIDEBAR_SETTINGS ) );
	?>
	<input type="hidden" name="prima_sidebars_post_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	<?php
	global $prima_registered_sidebar_areas;
	if ( !empty( $prima_registered_sidebar_areas ) ) :
	foreach ( $prima_registered_sidebar_areas as $sidebar_area ) :
	?>
	<p>
		<label class="" for="prima_sidebar<?php echo $sidebar_area['id'] ?>"><span><?php echo $sidebar_area['label'] ?><span></label>
		<select name="_prima_sidebar<?php echo $sidebar_area['id'] ?>" id="prima_sidebar<?php echo $sidebar_area['id'] ?>" style="width: 99%">
			<option value=""><?php _e('Default','primathemes'); ?></option>
			<?php
			foreach ( (array)$sidebars as $id => $info ) {
				if ( $id != '0' ) {
					printf( '<option value="%s" %s>%s</option>', esc_html( $id ), selected( $id, prima_get_post_meta("_prima_sidebar{$sidebar_area['id']}"), false), esc_html( $info['name'] ) );
				}
			}
			?>
		</select>
	</p>
	<?php endforeach; ?>
	<p><a href="<?php echo admin_url('themes.php?page=primathemes-sidebars') ?>"><?php _e( 'Manage Sidebars', 'primathemes' ); ?></a> &middot; <a href="<?php echo admin_url('widgets.php') ?>"><?php _e( 'Manage Widgets', 'primathemes' ); ?></a></p>
	<?php 
	else :
	_e( 'Sorry, no defined sidebar areas', 'primathemes' );
	endif;
}
add_action('save_post', 'prima_sidebars_post_metabox_save', 1, 2);
function prima_sidebars_post_metabox_save( $post_id, $post ) {
	if ( !isset($_POST['prima_sidebars_post_nonce']) || !wp_verify_nonce( $_POST['prima_sidebars_post_nonce'], plugin_basename(__FILE__) ) )
		return $post_id;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;
	if ( ( 'page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id) ) || !current_user_can('edit_post', $post_id ) )
		return $post_id;
	global $prima_registered_sidebar_areas;
	if ( empty( $prima_registered_sidebar_areas ) )
		return $post_id;
	$sidebars = array();
	foreach ( $prima_registered_sidebar_areas as $sidebar_area ) {
		$sidebars["_prima_sidebar{$sidebar_area['id']}"] = $_POST["_prima_sidebar{$sidebar_area['id']}"];
	}
	foreach ( $sidebars as $key => $value ) {
		if ( $post->post_type == 'revision' ) return;
		if ( $value ) update_post_meta($post_id, $key, $value);
		else delete_post_meta($post_id, $key);
	}
}