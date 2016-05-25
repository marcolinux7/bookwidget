<?php

include_once("inc/aws_signed_request.php");



function cptui_register_my_cpts() {
	
	/* GALLERY */
	$labels = array(
		"name" => __( 'Sliders', '' ),
		"singular_name" => __( 'Slider', '' ),
	);

	$args = array(
		"label" => __( 'Sliders', '' ),
		"labels" => $labels,
		"description" => "",
		'menu_icon' => 'dashicons-format-gallery',
		"public" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => true,
		"show_in_menu" => true,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "gallery", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title" ),				
	);
	register_post_type( "gallery", $args );
	
	
	/* STORES */
	$labels = array(
			"name" => __( 'Stores', '' ),
			"singular_name" => __( 'Store', '' ),
	);
	
	$args = array(
			"label" => __( 'Stores', '' ),
			"labels" => $labels,
			"description" => "",
			'menu_icon' => 'dashicons-cart',
			"public" => true,
			"show_ui" => true,
			"show_in_rest" => false,
			"rest_base" => "",
			"has_archive" => false,
			"show_in_menu" => true,
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => array( "slug" => "store", "with_front" => true ),
			"query_var" => true,
			"supports" => array( "title", "thumbnail" ),
	);
	register_post_type( "store", $args );
	
// End of cptui_register_my_cpts() 
}

add_action('init', 'cptui_register_my_cpts');

add_image_size('cover-thumb', 250 );

add_filter($filter, 'wpautop');	// forza gli 'a capo'


/* SHORTCODES */

function book_gallery($atts) {
	
	$pull_quote_atts = shortcode_atts(array(
		'pid' => 0
	), $atts);
	
	$args = array (
		'p'				=> $pull_quote_atts['pid'],
		'post_type'		=> 'gallery',
		'post_status'	=> 'publish'
	);
	
	$the_query = new WP_Query( $args );
	
	$html = "";
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			
			$slider_id = get_the_ID();
			$m = get_post_meta(get_the_ID());
			
			$slider_class = ( count($m['cover']) > 1) ? 'slider1' : 'slider2';
			
			$html .= "<div class='{$slider_class} book-section'>";
			$c = 0;
			
			foreach ($m['cover'] as $k=>$cover) {
				
				$cartaceo = get_post_meta( $slider_id, $k.'-cartaceo');
				$ebook = get_post_meta( $slider_id, $k.'-ebook');
				
				$title = $m['titolo'][$k];
				$title_slug = sanitize_title($m['titolo'][$k]);
				
				$author =  sanitize_title($m['autore'][$k]);
				$author_slug =  sanitize_title($m['autore'][$k]);
				
				$isbn_13 = $m['isbn_cartaceo'][$k];
				$isbn_12 = substr($isbn_13, 0, 12);
				$isbn_chk = substr($isbn_13, 12, 1);
				
				$html .= "<div class='slide d{$slider_id}-{$c}' data-ord='d{$slider_id}-{$c}'>";
				$html .= wp_get_attachment_image( $cover, 'cover-thumb' );
				$html .= "	<!-- Inizio Store -->";
				$html .= "	<div class='col-lg-8 col-lg-offset-4 data-block' style='width:100%;margin-left:0px;'>";
				$html .= "		<ul class='nav navbar-nav'>";
				$html .= "			<li><a id='paper' class='dot' href='#'>cartaceo</a><span class='apex-paper'></span></li>";
				$html .= "			<li><a id='ebook' class='dot' href='#'>e-book</a><span class='apex-ebook'></span></li>";
				$html .= "		</ul>";
				$html .= "	</div>";
							
				$html .= "	<div class='col-lg-8 col-lg-offset-3' style='width:100%;margin-left:0px;'>";
				$html .= "		<!-- PAPER -->";
				
				$html .= "		<ul class='store-block paper'>";
				
				$args = array (
					'post_type'		=> 'store',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'desc',
					'meta_key' => 'tipologia',
					'order' => 'ASC',
					'posts_per_page' => '5',
					'meta_query' => array(
						array(
							'key' => 'tipologia',
							'value' => 'cartaceo',
							'compare' => '='
						)
					)
				);
				
				$store_query = new WP_Query( $args );
				
				if ($store_query->have_posts()) :
					while ($store_query->have_posts()) : $store_query->the_post();
						
						$store_meta = get_post_meta(get_the_ID());
						$alt = $store_meta['alt_title'];
						$post_slug = get_post_field( 'post_name', get_the_ID() );
						$store_url = urldecode($cartaceo[0][$post_slug]);
						
						$target = ($store_meta['target_link'][0] == 1) ? "_blank": "_self";
						
						$html .= "	<li>";
						$html .= "		<a href='{$store_url}' class='".sanitize_title(get_the_title())."' target='{$target}' onclick='ga('send', 'event', ' button', ' click', '{$title} - {$alt[0]}');'>";
						$html .= get_the_post_thumbnail(get_the_ID(), 'full');					
						$html .= "		</a>";
						$html .= "	</li>";
					endwhile;
				endif;
				wp_reset_query();
				
				$html .= "			</ul>";
							
				$html .= "			<!-- EBOOK -->";
				$html .= "			<ul class='store-block ebook'>";
				
				// EBOOK
				$args = array (
					'post_type'		=> 'store',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'desc',
					'meta_key' => 'tipologia',
					'order' => 'ASC',
					'posts_per_page' => '5',
					'meta_query' => array(
						array(
							'key' => 'tipologia',
							'value' => 'ebook',
							'compare' => '='
						)
					)
				);
				
				$store_query = new WP_Query( $args );
				
				if ($store_query->have_posts()) :
					while ($store_query->have_posts()) : $store_query->the_post();
						
						$store_meta = get_post_meta(get_the_ID());
						
						$alt = $store_meta['alt_title'];
						
						$post_slug = get_post_field( 'post_name', get_the_ID() );
						$store_url = urldecode($ebook[0][$post_slug]);
						
						$html .= "	<li>";
						$html .= "		<a href='{$store_url}' class='".sanitize_title(get_the_title())."' target='{$target}' onclick='ga('send', 'event', ' button', ' click', '{$title} - {$alt[0]}');'>";
						$html .= get_the_post_thumbnail(get_the_ID(), 'full');					
						$html .= "		</a>";
						$html .= "	</li>";
					endwhile;
				endif;
				wp_reset_query();
				
				$html .= "		</ul>";
				$html .= "	<!-- Fine Store -->";
				$html .= "	</div>";
				$html .= "</div>";
				
				$c++;
				
			}	// fine foreach
			
			$html .= "</div>";
							
			endwhile;
		endif;
		wp_reset_query();
		
	return $html;
}

add_shortcode('book-gallery', 'book_gallery');


// INIZIO Blocco Amazon
function getAmazonStore($isbn) {
	$public_key = "AKIAIRZDMN7BXRXECW6A";
	$private_key = "zcapf9JiuVsxyTn/DZdgmNfVzFgHiVkgMUfrgHFV";
	$host =   "webservices.amazon.it";
	
	$request_parameters['Operation'] = "ItemLookup";
	$request_parameters['ItemId'] = $isbn;
	$request_parameters['IdType'] = "ISBN";
	$request_parameters['ResponseGroup'] = "Images,ItemAttributes,Offers";
	$request_parameters['AssociateTag'] = "librimonda-21";
	$request_parameters['SearchIndex'] = "Books";
	
	$response = aws_signed_request($host, $request_parameters, $public_key, $private_key);
	$url =  $response->Items->Item->DetailPageURL;
	
	if ($response  === False) {
		$url = "http://www.amazon.it/gp/search?keywords=".$isbn."&index=books&linkCode=qs&tag=".$amazon_access_key;
	} else {
		if (!isset($response ->Items->Item->ItemAttributes->Title)) {
			$url = "http://www.amazon.it/gp/search?keywords=".$isbn."&index=books&linkCode=qs&tag=".$amazon_access_key;
		}
	}
	
	return $url;
}






// BUTTON ON EDITOR ----------------------------------

add_action('init', 'mylink_button');

function mylink_button() {

	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
		return;
	}
	
	if ( get_user_option('rich_editing') == 'true' ) {
		add_filter( 'mce_external_plugins', 'add_plugin' );
		add_filter( 'mce_buttons', 'register_button' );
	}
}

function register_button( $buttons ) {

array_push( $buttons, "|", "tinymcebut" );

return $buttons;

}

function add_plugin( $plugin_array ) {

$plugin_array['tinymcebut'] = plugins_url( '/js/bookpicker-mce-plugin.js',__FILE__ );

return $plugin_array;

}



// NUOVO BUTTON ON EDITOR ----------------------------------
// Funzione che controlla i permessi di editing e se
// il componete WYSIWYG è attivo in WordPress

function my_add_mce_theme_button() {
  if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
    if (get_user_option('rich_editing') == 'true') {
      add_filter("mce_external_plugins","my_register_tinymce_plugin");
      add_filter("mce_buttons",'my_register_tinymce_button');
    }
  } 
}
 
// Funzione per registrare il plugin Javascript da caricare
// durante la composizione del menu in TinyMCE
 
function my_register_tinymce_plugin($plugin_array) {
	if (get_post_type() == "post" || get_post_type() == "page" ) {
		$plugin_array['MY_mce_button'] = plugins_url( '/js/bookpicker-mce-plugin.js',__FILE__ );
		return $plugin_array;
	}
}
 
// Registrazione del pulsante per TinyMCE usando lo stesso 
// nome con cui viene associata la risorsa javascript
 
function my_register_tinymce_button($buttons) {
  array_push($buttons,'MY_mce_button');
  return $buttons;
}
// Aggiungo la registrazione della funzione nella fase che
// riguarda admin_head anche se funziona bene in admin_init
 
add_action('admin_head','my_add_mce_theme_button');



/* Aggiunta colonne nell'Admin degli Store */

add_filter('manage_edit-store_columns', 'my_edit_store_columns');

function my_edit_store_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Titolo' ),
		'tipologia' => __( 'Tipologia' ),
		'icona' => __( 'Icona' ),
		'target' => __( 'Target' ),
		'date' => __( 'Data' )
	);
	return $columns;
}


add_action( 'manage_store_posts_custom_column', 'my_manage_store_columns', 10, 2 );

function my_manage_store_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		
		case 'tipologia' :
			
			$terms = get_post_meta($post_id, 'tipologia');
			
			if ( !empty( $terms ) ) {
				$out = array();

				foreach ( $terms as $term ) {
					$out[] = "<em>$term</em>";
				}
				
				echo join( ', ', $out );
			} else { _e('Nessuna tipologia associata'); }

			break;
			
		case 'icona' :
			echo get_the_post_thumbnail($post_id, 'thumb');
			break;
			
		case 'target' :
					
			$terms = get_post_meta($post_id, 'target_link');
			
			if ( !empty( $terms ) ) {
				$out = array();
		
				foreach ( $terms as $term ) {
					
					$target = ($term == 1) ? "nuova scheda": "stessa scheda";
					
					$out[] = "<em>$target</em>";
				}
		
				echo join( ', ', $out );
			} else { _e('Nessuna tipologia associata'); }
		
			break;
				
		default :
			break;
	}
}


// Collego Template di pagina per il popup BookPicker dal plugin
add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
    global $wp;
    $plugindir = dirname( __FILE__ );
	
	if ($wp->query_vars["pagename"] == 'bookpicker-repository') {
        $templatefilename = 'bookpicker-repository.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/tpl/' . $templatefilename;
        }
        do_theme_redirect($return_template);
    }
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}




function run_activate_plugin( $plugin, $action ) {
	
	$current = get_option( 'active_plugins' );
	$plugin = plugin_basename( trim( $plugin ) );
	
	if ( !in_array( $plugin, $current ) ) {
		$current[] = $plugin;
		sort( $current );
		
		do_action( 'activate_plugin', trim( $plugin ) );
		update_option( 'active_plugins', $current );
		do_action( 'activate_' . trim( $plugin ) );
		do_action( 'activated_plugin', trim( $plugin) );				
	}
	
	return null;
}


function hidePlugin() {
	global $wp_list_table;
	$hidearr = array('custom-field-suite/cfs.php');
	$myplugins = $wp_list_table->items;
	foreach ($myplugins as $key => $val) {
		if (in_array($key,$hidearr)) {
			unset($wp_list_table->items[$key]);
		}
	}
}
// add_action( 'pre_current_active_plugins', 'hidePlugin' );

// Nascondo la pagina BookPicker
add_filter( 'parse_query', 'exclude_pages_from_admin' );
function exclude_pages_from_admin($query) {
	global $pagenow,$post_type;
	if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
		$page = get_page_by_title( "BookPicker Repository", $output, $post_type );
		$query->query_vars['post__not_in'] = array($page->ID);
	}
}



function createStores() {
	
	/* CARTACEO */
	
	/* Amazon */
	$my_post = array(
			'post_title'    => 'Amazon',
			'name'    => 'amazon-cartaceo',
			'post_name'    => 'amazon-cartaceo',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "#");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su Amazon");
	update_post_meta($post_id, 'target_link', 1);
	
	/* MondadoriStore */
	$my_post = array(
			'post_title'    => 'Mondadori',
			'name'    => 'mondadori-cartaceo',
			'post_name'    => 'mondadori-cartaceo',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	

	update_post_meta($post_id, 'algoritmo_store', "http://www.mondadoristore.it/{title_slug}-{author_slug}/eai{isbn_12}/");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su Mondadori Store");
	update_post_meta($post_id, 'target_link', 1);
		
		
	/* IBS */
	$my_post = array(
			'post_title'    => 'IBS',
			'name'    => 'ibs-cartaceo',
			'post_name'    => 'ibs-cartaceo',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;

	update_post_meta($post_id, 'algoritmo_store', "http://www.ibs.it/libro/{author_slug}/{title_slug}/{isbn_13}.html");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su IBS");
	update_post_meta($post_id, 'target_link', 1);
	

	/* Feltrinelli */
	$my_post = array(
			'post_title'    => 'Feltrinelli',
			'name'    => 'feltrinelli-cartaceo',
			'post_name'    => 'feltrinelli-cartaceo',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "http://www.lafeltrinelli.it/products/{isbn_13}.html");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su Feltrinelli");
	update_post_meta($post_id, 'target_link', 1);
	
	/* Libreria Universitaria */
	$my_post = array(
			'post_title'    => 'Libreria Universitaria',
			'name'    => 'libreriauniversitaria-cartaceo',
			'post_name'    => 'libreriauniversitaria-cartaceo',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "http://www.libreriauniversitaria.it/{title_slug}-{author_slug}-mondadori/libro/{isbn_13}");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su Libreria Universitaria");
	update_post_meta($post_id, 'target_link', 1);
	
	/* EBOOK */
	
	/* Amazon */
	$my_post = array(
			'post_title'    => 'Amazon',
			'name'    => 'amazon-ebook',
			'post_name'    => 'amazon-ebook',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "#");
	update_post_meta($post_id, 'alt_title', "Acquista ebook su Amazon");
	update_post_meta($post_id, 'target_link', 1);
	
	
	/* Kobo */
	$my_post = array(
			'post_title'    => 'Kobo',
			'name'    => 'kobo-ebook',
			'post_name'    => 'kobo-ebook',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "https://store.kobobooks.com/it-it/ebook/{title_slug}");
	update_post_meta($post_id, 'alt_title', "Acquista ebook su Kobo");
	update_post_meta($post_id, 'target_link', 1);
	
	/* iTunes */
	$my_post = array(
			'post_title'    => 'iTunes',
			'name'    => 'itunes-ebook',
			'post_name'    => 'itunes-ebook',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "http://itunes.apple.com/it/book/isbn{isbn_13}");
	update_post_meta($post_id, 'alt_title', "Acquista ebook su iTunes");
	update_post_meta($post_id, 'target_link', 1);
	
	/* Google Play */
	$my_post = array(
			'post_title'    => 'Google Play',
			'name'    => 'googleplay-ebook',
			'post_name'    => 'googleplay-ebook',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "https://play.google.com/store/search?q={isbn_13}&c=books");
	update_post_meta($post_id, 'alt_title', "Acquista ebook su Google Play");
	update_post_meta($post_id, 'target_link', 1);
	
	
	/* Feltrinelli */
	$my_post = array(
			'post_title'    => 'Feltrinelli',
			'name'    => 'feltrinelli-ebook',
			'post_name'    => 'feltrinelli-ebook',
			'post_content'  => '',
			'post_status'   => 'publish',
			'post_type'		=> 'store'
	);
	
	$the_query = new WP_Query( $my_post );
	
	// Inserisco metadati
	
	if ($the_query->have_posts()) :
		while ($the_query->have_posts()) : $the_query->the_post();
			$post_id = get_the_ID();
		endwhile;
		wp_reset_query();
		wp_reset_postdata();
	else:
		$post_id = wp_insert_post( $my_post );
	endif;
	
	update_post_meta($post_id, 'algoritmo_store', "http://www.lafeltrinelli.it/products/{isbn_13}.html");
	update_post_meta($post_id, 'alt_title', "Acquista cartaceo su Feltrinelli");
	update_post_meta($post_id, 'target_link', 1);
	
}



function save_book_meta( $post_id, $post, $update ) {
	
	if ( get_post_type($post_id) ) {
		
		$m = get_post_meta($post_id);
		
		foreach ($m['cover'] as $k=>$cover) {
			
			$title = $m['titolo'][$k];
			$title_slug = sanitize_title($m['titolo'][$k]);
			
			$author =  sanitize_title($m['autore'][$k]);
			$author_slug =  sanitize_title($m['autore'][$k]);
		
			$isbn_13 = $m['isbn_cartaceo'][$k];
			$isbn_12 = substr($isbn_13, 0, 12);
			$isbn_chk = substr($isbn_13, 12, 1);
			
			// PAPER
			
			$args = array (
					'post_type'		=> 'store',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'desc',
					'meta_key' => 'tipologia',
					'order' => 'ASC',
					'posts_per_page' => '5',
					'meta_query' => array(
							array(
									'key' => 'tipologia',
									'value' => 'cartaceo',
									'compare' => '='
							)
					)
			);
		
			$store_query = new WP_Query( $args );
			$stores = array();
			
			if ($store_query->have_posts()) :
				while ($store_query->have_posts()) : $store_query->the_post();
					
					$store_meta = get_post_meta(get_the_ID());
					
					$isbn_13 = $m['isbn_cartaceo'][$k];
					$isbn_12 = substr($isbn_13, 0, 12);
					$isbn_chk = substr($isbn_13, 12, 1);
					
					$post_slug = get_post_field( 'post_name', get_the_ID() );
					
					if ( sanitize_title(get_the_title()) == "amazon" ) {
						$alg = getAmazonStore($isbn_13);
					} else {
						$algoritmo = $store_meta['algoritmo_store'];
						$alg = $algoritmo[0];
							
						// elaboro metadati
						$alg = str_replace('{isbn_13}', $isbn_13, $alg);
						$alg = str_replace('{isbn_12}', $isbn_12, $alg);
						$alg = str_replace('{title_slug}', $title_slug, $alg);
						$alg = str_replace('{title}', $title, $alg);
						$alg = str_replace('{author}', $author, $alg);
						$alg = str_replace('{author_slug}', $author_slug, $alg);
						$alg = str_replace('{isbn_chk}', $isbn_chk, $alg);
					}
					
					$stores[$post_slug] = urlencode($alg);
					
				endwhile;
			endif;
			wp_reset_query($store_query);
			
			// UPDATE
			update_post_meta($post_id, $k.'-cartaceo', $stores);
			
			
			// EBOOK
			$args = array (
					'post_type'		=> 'store',
					'post_status'	=> 'publish',
					'orderby'		=> 'menu_order',
					'order'			=> 'desc',
					'meta_key' => 'tipologia',
					'order' => 'ASC',
					'posts_per_page' => '5',
					'meta_query' => array(
							array(
									'key' => 'tipologia',
									'value' => 'ebook',
									'compare' => '='
							)
					)
			);
		
			$store_query = new WP_Query( $args );
			$stores = array();
			
			if ($store_query->have_posts()) :
				while ($store_query->have_posts()) : $store_query->the_post();
			
					$store_meta = get_post_meta(get_the_ID());
					
					$isbn_13 = $m['isbn_ebook'][$k];
					$isbn_12 = substr($isbn_13, 0, 12);
					$isbn_chk = substr($isbn_13, 12, 1);
					
					$post_slug = get_post_field( 'post_name', get_the_ID() );
					
					if ( $post_slug == "amazon-cartaceo" || $post_slug == "amazon-ebook" ) {
						$alg = getAmazonStore($isbn_13);
					} else {
						$algoritmo = $store_meta['algoritmo_store'];
						$alg = $algoritmo[0];
							
						// elaboro metadati
						$alg = str_replace('{isbn_13}', $isbn_13, $alg);
						$alg = str_replace('{isbn_12}', $isbn_12, $alg);
						$alg = str_replace('{title_slug}', $title_slug, $alg);
						$alg = str_replace('{title}', $title, $alg);
						$alg = str_replace('{author}', $author, $alg);
						$alg = str_replace('{author_slug}', $author_slug, $alg);
						$alg = str_replace('{isbn_chk}', $isbn_chk, $alg);
					}
					
					$stores[$post_slug] = urlencode($alg);
					
				endwhile;
			endif;
			wp_reset_query($store_query);
			
			// UPDATE
			update_post_meta($post_id, $k.'-ebook', $stores);
			
		}	// fine foreach
			
	}
	
}

add_action( 'save_post', 'save_book_meta', 10, 3 );



?>
