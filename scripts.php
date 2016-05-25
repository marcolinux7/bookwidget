<?php
	function load_custom_scripts_backend() {
		wp_register_script('my-jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
		wp_register_script('my-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js');
		wp_enqueue_script('my-jquery-core');
		wp_enqueue_script('my-jquery-ui');
	}
	
	 
	function load_custom_styles_backend() {
		wp_register_style('my-jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css');
// 		wp_register_style('bar-css', plugins_url('ame-bookwidget').'/css/bars.css');
		wp_register_style('admin', plugins_url('ame-bookwidget').'/css/admin-style.css');
		wp_enqueue_style( 'my-jquery-ui-css');
//		wp_enqueue_style( 'bar-css');
		wp_enqueue_style( 'admin');
	}
	
	
	function load_custom_scripts_frontend() {
		wp_register_script('my-jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
		wp_register_script('my-jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js');
		wp_register_script('my-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
		wp_register_script('bxslider-js', plugins_url('ame-bookwidget').'/js/jquery.bxslider.js', '1.0', true);
		wp_register_script('bookwidget-js', plugins_url('ame-bookwidget').'/js/bookwidget.js');
// 		wp_enqueue_script('my-jquery-core');
		wp_enqueue_script('my-jquery-ui');
// 		wp_enqueue_script('my-bootstrap-js');
		wp_enqueue_script('bxslider-js');
		wp_enqueue_script('bookwidget-js');
	}
	
	
	function load_custom_styles_frontend() {
		wp_register_style('my-jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css');
		wp_register_style('my-bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
		wp_register_style('bxslider-css', plugins_url('ame-bookwidget').'/css/jquery.bxslider.css');
		wp_register_style('main-css', plugins_url('ame-bookwidget').'/css/style.css');
		wp_enqueue_style( 'my-jquery-ui-css');
// 		wp_enqueue_style( 'my-bootstrap-css');
		wp_enqueue_style( 'bxslider-css');
		wp_enqueue_style( 'main-css');
	}
	
?>
