/*
*  scripts.js
*  Tutto il javascript che serve...
*
*  @type	JS
*  @date	02/03/2015
*/ 
	
	
	jQuery(document).ready(function() {
		
		jQuery(".navbar-nav>li>a").click(function(e){ e.preventDefault(); });

		  	jQuery(".navbar-nav>li>a#paper").click(function(e){
		  		var id = jQuery(this).attr('id');
		  		var ord = jQuery(this).parentsUntil('.container')[3];
		  		ord = '.'+ord.getAttribute('data-ord');
		  		
		 			if ( jQuery(ord+" #ebook").hasClass('active') ) {
		 				jQuery(ord+" #ebook").removeClass('active'); 
		 				jQuery(ord+" .apex-ebook").removeClass('sel').addClass('unsel');
		 			}
		 			jQuery(ord+" .apex-paper").removeClass('unsel').addClass('sel');
		 			jQuery(ord+" #ebook").removeClass('active');
		 			jQuery(ord+" ul.ebook").hide();
		 			jQuery(this).addClass('active');
		 			jQuery(ord+" ul."+id).show();
		  	});
		  	
			jQuery(".navbar-nav>li>a#paper").trigger( "click" );
			
			jQuery(".navbar-nav>li>a#ebook").click(function(e){
			var id = jQuery(this).attr('id');
			var ord = jQuery(this).parentsUntil('.container')[3];
			ord = '.'+ord.getAttribute('data-ord');

	  		if ( jQuery(ord+" #paper").hasClass('active') ) {
	  			jQuery(ord+" #paper").removeClass('active'); 
	 			jQuery(ord+" .apex-paper").removeClass('sel').addClass('unsel');
	 		}
 			jQuery(ord+" .apex-"+id).removeClass('unsel').addClass('sel');
 			jQuery(ord+" #paper").removeClass('active');
 			jQuery(ord+" ul.paper").hide();
 			jQuery(this).addClass('active');
 			jQuery(ord+" ul."+id).show();
		});
		
		if ( jQuery(window).width() < 767 ) {
			jQuery('.slider1').bxSlider({
				minSlides: 1,
				maxSlides: 1,
				slideWidth: 260,
				slideMargin: 10,
				pager: false
			});
		} else {
			jQuery('.slider1').bxSlider({
				minSlides: 1,
				maxSlides: 3,
				slideWidth: 160,
				slideMargin: 60,
				pager: false
			});
		}
		
	});

