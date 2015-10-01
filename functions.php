<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */

/**
 * Kleo Child Theme Functions
 * Add custom code below
*/ 

<?php

// Logo personalizado en la página de login
function custom_login() {
echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').'/wp-admin.css" />';
}
add_action('login_head', 'custom_login');

// Personalización de administración
// css personalizado en la página de login
add_action('admin_head', 'my_custom_css');
function my_custom_css() {
 echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').'/wp-admin.css" />';
}

//Quita el acceso al Escritorio al que no sea Admin
add_filter('login_redirect', 'dashboard_redirect');
function dashboard_redirect($url) {
global $current_user;
get_currentuserinfo();
$level = (int) $current_user->wp_user_level;
if ( $level < 10 && $level > 3 ) {
$url = 'wp-admin/post-new.php';
}
return $url;
}

// url personalizada en la página de login
function h1_custom_url(){
    return (bloginfo( 'wpurl' ));
}
add_filter('login_headerurl', 'h1_custom_url');

// Cambiar el pie de pagina del panel de Administración
function change_footer_admin() {  
    echo '(c) 2013 Copyright artesvisuales. Todos los derechos reservados - Web creada por <a href="http://www.artesvisuales.com">artesvisuales</a>';  
}  
add_filter('admin_footer_text', 'change_footer_admin');

//miniaturas en admin de entradas
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) {
 
	// para entrada y página
	add_theme_support('post-thumbnails', array( 'post', 'page' ) );
 
	function fb_AddThumbColumn($cols) {
 
		$cols['thumbnail'] = __('Miniatura');
 
		return $cols;
	}
 
	function fb_AddThumbValue($column_name, $post_id) {
 
			$width = (int) 45;
			$height = (int) 45;
 
			if ( 'thumbnail' == $column_name ) {
				// miniatura de WP 2.9
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				// imagen de la galería
				$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
				if ($thumbnail_id)
					$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				elseif ($attachments) {
					foreach ( $attachments as $attachment_id => $attachment ) {
						$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
					}
				}
					if ( isset($thumb) && $thumb ) {
						echo $thumb;
					} else {
						echo __('Ninguna');
					}
			}
	}
 
	// para entradas
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
 
	// para páginas
	add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}
// Fin personalización de administración

// Limpiar el head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
// Fin limpiar el head

// Insertar Breadcrumb    
function the_breadcrumb() {
	if (!is_home()) {
		echo '<span class="removed_link" title="&#039;;
		echo get_option(&#039;home&#039;);
	        echo &#039;">';
		bloginfo('name');
		echo "</span> » ";
		if (is_category() || is_single()) {
			the_category('title_li=');
			if (is_single()) {
				echo " » ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}
	}
}    
// fin breadcrumb
	
// Avatar image con hCard-compliant photo class
function commenter_link() {
        $commenter = get_comment_author_link();
        if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
                $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
        } else {
                $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
        }
        $avatar_email = get_comment_author_email();
        $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 50 ) );
        echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
} // end commenter_link   

//Desactivar revisiones
function disable_autosave() {
	wp_deregister_script('autosave');
}
add_action('wp_print_scripts','disable_autosave');
//Fin desactivar revisiones

//Añadir gestor de enlaces
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// Remove query strings from static resources
function _remove_script_version( $src ){
$parts = explode( '?', $src );
return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

//WOOCOMMERCE

// Override theme default specification for product # per row
function loop_columns() {
return 3; // 3 products per row
}
add_filter('loop_shop_columns', 'loop_columns', 999);

?>