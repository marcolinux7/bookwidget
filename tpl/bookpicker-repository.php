<?php
	/**
	 * Template Name: BookPicker Repository
	 */
		
	$args = array(
		'post_type' => 'gallery',
		'orderby' => 'date',
		'order' => 'desc',
		'posts_per_page'=> '12',
		'post_status' => 'publish'
	);
	
	$loop = new WP_Query( $args );
	
	echo "<div class='add-new-slider'><a href='post-new.php?post_type=gallery' target='_blank'><span>Crea nuovo</span></a></div>";
	
	if( $loop->have_posts() ):
		while( $loop->have_posts() ): $loop->the_post();
			
			$dots = ( strlen(get_the_title()) > 50 ) ? '...' : '';
			$m = get_post_meta(get_the_ID());
			
			if ( $m['cover'][0] != '' ) {
				$img = wp_get_attachment_image($m['cover'][0], 'thumb');
			} else {
				$img = "<img src='".plugins_url('../images/no-image-icon.jpg', __FILE__)."' title='".addslashes(get_the_title());"' />";
			}
			
			echo '<div id="testimonials">';
			echo '	<div class="three-fourths">';
							echo $img;
			echo "		<p class='bookpicker-item' data-pid='".get_the_ID()."'>";
							echo substr(get_the_title(), 0 , 50).$dots;
			echo "			<span class='slider-date'>".get_the_date('d/m/Y')."</span>";
			echo "		</p>";
			echo '	</div>';
			echo '</div>';
		endwhile;
		
	else:
		echo '<div id="testimonials">';
		echo '	<div class="three-fourths">';
		echo "		<p>Nessuno slider ancora inserito...<br />Comincia a crearne uno <a href='post-new.php?post_type=gallery' target='_blank'><strong>CLICCANDO QUI</strong></a></p>";
		echo '	</div>';
		echo '</div>';
	endif;

?>