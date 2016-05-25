<?php
/**
 * @package BookWidget
 * @version 1.7.4 BETA
 */
/*
 Plugin Name: BookWidget
 Plugin URI: http://www.librimondadori.it
 Description: Plugin che permette la creazione di caroselli di libri
 Author: Marco Borrelli
 Version: 1.7.4 <strong>BETA</strong>
 Author URI: http://www.librimondadori.it
 */

require_once("scripts.php");
require_once("functions.php");
require_once("inc/cpo/custom-post-order.php");

class ameBookWidget {

	public function __construct() {		
		add_action('admin_menu', 'aggiungi_menu');
		add_action('wp_footer', 'load_custom_scripts_frontend');
		add_action('wp_head', 'load_custom_styles_frontend');
		add_action('admin_init', 'load_custom_styles_backend');
		// add_action('admin_init', 'register_wpsiliweb_settings');
		register_activation_hook( __FILE__, 'myplugin_activate' );
		// register_deactivation_hook( __FILE__, 'myplugin_deactivate' );
	}
}


function myplugin_activate() {
	// Activation code here...
	run_activate_plugin('custom-field-suite/cfs.php', "activate");
	
	// se plugin è attivato
	$current = get_option( 'active_plugins' );
	$plugin = plugin_basename( trim('custom-field-suite/cfs.php') );
	
	if ( in_array( $plugin, $current ) ) {
		
		// Creo campi del plugin CFS
		
		/* GALLERY */
		$my_post = array(
				'post_title'    => 'Gallery',
				'name'    => 'gallery',
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'		=> 'cfs'
		);
		
		$the_query = new WP_Query( $my_post );
		
		if ($the_query->have_posts()) :
			while ($the_query->have_posts()) : $the_query->the_post();
				$post_id = get_the_ID();
			endwhile;
			wp_reset_query();
			wp_reset_postdata();
		else:
			$post_id = wp_insert_post( $my_post );
		endif;
		
		// Inserisco metadati
		update_post_meta($post_id, 'cfs_fields', unserialize('a:6:{i:0;a:8:{s:2:"id";s:1:"1";s:4:"name";s:5:"libro";s:5:"label";s:5:"Libro";s:4:"type";s:4:"loop";s:5:"notes";s:0:"";s:9:"parent_id";i:0;s:6:"weight";i:0;s:7:"options";a:5:{s:11:"row_display";s:1:"0";s:9:"row_label";s:5:"Libro";s:12:"button_label";s:14:"Aggiungi Libro";s:9:"limit_min";s:0:"";s:9:"limit_max";s:0:"";}}i:1;a:8:{s:2:"id";s:1:"2";s:4:"name";s:5:"cover";s:5:"label";s:5:"Cover";s:4:"type";s:4:"file";s:5:"notes";s:0:"";s:9:"parent_id";i:1;s:6:"weight";i:1;s:7:"options";a:2:{s:12:"return_value";s:2:"id";s:8:"required";s:1:"0";}}i:2;a:8:{s:2:"id";s:1:"6";s:4:"name";s:13:"isbn_cartaceo";s:5:"label";s:13:"ISBN cartaceo";s:4:"type";s:4:"text";s:5:"notes";s:26:"Inserire l\'isbn a 13 cifre";s:9:"parent_id";i:1;s:6:"weight";i:2;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}i:3;a:8:{s:2:"id";s:2:"15";s:4:"name";s:10:"isbn_ebook";s:5:"label";s:10:"ISBN ebook";s:4:"type";s:4:"text";s:5:"notes";s:26:"Inserire l\'isbn a 13 cifre";s:9:"parent_id";i:1;s:6:"weight";i:3;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}i:4;a:8:{s:2:"id";s:2:"11";s:4:"name";s:6:"titolo";s:5:"label";s:6:"Titolo";s:4:"type";s:4:"text";s:5:"notes";s:0:"";s:9:"parent_id";i:1;s:6:"weight";i:4;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}i:5;a:8:{s:2:"id";s:2:"12";s:4:"name";s:6:"autore";s:5:"label";s:6:"Autore";s:4:"type";s:4:"text";s:5:"notes";s:12:"Nome Cognome";s:9:"parent_id";i:1;s:6:"weight";i:5;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}}'));
		update_post_meta($post_id, 'cfs_rules', unserialize('a:1:{s:10:"post_types";a:2:{s:8:"operator";s:2:"==";s:6:"values";a:1:{i:0;s:7:"gallery";}}}'));
		update_post_meta($post_id, 'cfs_extras', unserialize('a:3:{s:5:"order";s:1:"0";s:7:"context";s:6:"normal";s:11:"hide_editor";s:1:"0";}'));
		
		/* STORES */
		$my_post = array(
				'post_title'    => 'Stores',
				'name'    => 'stores',
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'		=> 'cfs'
		);
		
		$the_query = new WP_Query( $my_post );
		
		if ($the_query->have_posts()) :
			while ($the_query->have_posts()) : $the_query->the_post();
					$post_id = get_the_ID();
			endwhile;
			wp_reset_query();

			wp_reset_postdata();		else:
			$post_id = wp_insert_post( $my_post );
		endif;
		
		// Inserisco metadati
		update_post_meta($post_id, 'cfs_fields', unserialize('a:2:{i:0;a:8:{s:2:"id";i:28;s:4:"name";s:9:"tipologia";s:5:"label";s:9:"Tipologia";s:4:"type";s:6:"select";s:5:"notes";s:0:"";s:9:"parent_id";i:0;s:6:"weight";i:0;s:7:"options";a:4:{s:7:"choices";a:2:{s:8:"cartaceo";s:8:"cartaceo";s:5:"ebook";s:5:"ebook";}s:8:"multiple";s:1:"0";s:7:"select2";s:1:"0";s:8:"required";s:1:"0";}}i:1;a:8:{s:2:"id";i:29;s:4:"name";s:11:"target_link";s:5:"label";s:11:"Target Link";s:4:"type";s:10:"true_false";s:5:"notes";s:0:"";s:9:"parent_id";i:0;s:6:"weight";i:1;s:7:"options";a:2:{s:7:"message";s:31:"Apri link in una nuova finestra";s:8:"required";s:1:"0";}}}'));
		update_post_meta($post_id, 'cfs_rules', unserialize('a:1:{s:10:"post_types";a:2:{s:8:"operator";s:2:"==";s:6:"values";a:1:{i:0;s:5:"store";}}}'));
		update_post_meta($post_id, 'cfs_extras', unserialize('a:3:{s:5:"order";s:1:"0";s:7:"context";s:4:"side";s:11:"hide_editor";s:1:"0";}'));
		

		
		/* STORES DATA */
		$my_post = array(
				'post_title'    => 'Stores Data',
				'name'    => 'stores-data',
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_type'		=> 'cfs'
		);
		
		$the_query = new WP_Query( $my_post );
		
		if ($the_query->have_posts()) :
			while ($the_query->have_posts()) : $the_query->the_post();
				$post_id = get_the_ID();
			endwhile;
			wp_reset_query();
			wp_reset_postdata();
		else:
			$post_id = wp_insert_post( $my_post );
		endif;
		
		// Inserisco metadati
		update_post_meta($post_id, 'cfs_fields', unserialize('a:2:{i:0;a:8:{s:2:"id";i:30;s:4:"name";s:15:"algoritmo_store";s:5:"label";s:15:"Algoritmo Store";s:4:"type";s:4:"text";s:5:"notes";s:369:"<strong>{isbn_13}</strong> = isbn a 13 cifre<br /><strong>{isbn_12}</strong> = isbn a 13 cifre<br /><strong>{title_slug}</strong> = Slug del titolo<br /><strong>{title}</strong> = Titolo completo<br /><strong>{author}</strong> = Nome autore completo (Nome Cognome)<br /><strong>{author_slug}</strong> = Slug del nome autore<br /><strong>{isbn_chk}</strong> = Check Isbn";s:9:"parent_id";i:0;s:6:"weight";i:0;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}i:1;a:8:{s:2:"id";i:31;s:4:"name";s:9:"alt_title";s:5:"label";s:9:"Alt Title";s:4:"type";s:4:"text";s:5:"notes";s:0:"";s:9:"parent_id";i:0;s:6:"weight";i:1;s:7:"options";a:2:{s:13:"default_value";s:0:"";s:8:"required";s:1:"0";}}}'));
		update_post_meta($post_id, 'cfs_rules', unserialize('a:1:{s:10:"post_types";a:2:{s:8:"operator";s:2:"==";s:6:"values";a:1:{i:0;s:5:"store";}}}'));
		update_post_meta($post_id, 'cfs_extras', unserialize('a:3:{s:5:"order";s:1:"0";s:7:"context";s:6:"normal";s:11:"hide_editor";s:1:"0";}'));
		
	}
	
	
	// Popolo gli Stores
	createStores();	
	
	
	// Se non esiste, Creo la pagina del popup BookPicker Repository
	if (get_page_by_title('BookPicker Repository') == NULL) {
		$post = array(
				'post_date' => date('Y-m-d H:i:s'),
				'post_name' => 'bookpicker-repository',
				'post_status' => 'publish',
				'post_title' => 'BookPicker Repository',
				'post_type' => 'page',
		);
		//insert page and save the id
		$newvalue = wp_insert_post($post, false);
		//save the id in the database
		update_option('movpage', $newvalue);		
		
	}
	
}

function myplugin_deactivate() {
	// Activation code here...
	run_activate_plugin('custom-field-suite/cfs.php', "deactivate");
}

function aggiungi_menu() {
	
// 	add_menu_page('BookWidget Options', 'BookWidget', 'manage_options', 'bookwidget-settings', 'bookwidget_options');
	
	/*
	if (function_exists('p2p_register_connection_type'))
		add_submenu_page('wpsiliweb-settings', 'Connection Types', 'Connection Types', 'administrator', 'wpsiliweb-connections', 'wpsiliweb_connections');
	*/
// 	add_submenu_page('bookwidget-settings', 'Stores', 'Stores', 'administrator', 'edit.php?post_type=store');
// 	add_submenu_page('wpsiliweb-settings', 'Cronjobs', 'Cronjobs', 'administrator', 'wpsiliweb-cronjobs', 'wpsiliweb_cronjobs');
}
 
function register_bookwidget_settings() {
	// registro i campi opzione del plugin
}

function bookwidget_options() {
	?>
<div class="plugin-title">
	<!-- <img src="<?php echo plugins_url('images/logo_mondadori.png', __FILE__);?>" width="60" height="41" class="ame-logo" /> -->
	<h2 class="plugin-title">BookWidget <small>Beta</small></h2>
</div>

<div class="siliwrap mainwrap">
<?php // wpsiliweb_main(); ?>
				
</div>
<?php
}



function wpsiliweb_main() {
?>
	<div class="plugin-title"><img src="<?php echo plugins_url('images/logo_mondadori.png', __FILE__);?>" width="60" height="41" class="ame-logo" />
		<?php // contenuto ?>
	</div>
<?php
}

$wpPlugin = new ameBookWidget();
?>
