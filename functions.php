<?php

define('MARINE_DIR', plugin_dir_path( __FILE__ ));

require_once dirname( get_bloginfo( 'stylesheet_url' ) ) . '/shortcodes/interactive-gallery.php';
wp_register_style( 'interactive-slide', dirname( get_bloginfo( 'stylesheet_url' ) ) .'/js/interactive-slide.js', array('jquery'), '1.0', true );

// include_once( MARINE_DIR .'/library/zoho_intergration.php');

// Zoho_Intergrator::get_instance();

function geeque_enqueue_styles() {

	wp_register_style( 'app.css', dirname( get_bloginfo( 'stylesheet_url' ) ) . '/css/app.css', array(), null, 'all' );

	wp_enqueue_style( 'app.css' );

}

add_action( 'wp_enqueue_scripts', 'geeque_enqueue_styles' );

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	// remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' );    // Right Now Widget
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Comments Widget
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' );  // Incoming Links Widget
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' );         // Plugins Widget

	// remove_meta_box('dashboard_quick_press', 'dashboard', 'core' );   // Quick Press Widget
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' );   // Recent Drafts Widget
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' );         //
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' );       //

	// removing plugin dashboard boxes
	remove_meta_box( 'yoast_db_widget', 'dashboard', 'normal' );         // Yoast's SEO Plugin Widget

}


// DMG Widget
function dmg_welcome_widget() {
	?>
	<h2> Downing Management Group </h2>
	<p> Merry Christmas and a warm welcome from the Think Generic{} Team!</p>
	<p>Anyways, this is the custom theme for Downing Management Group. Hope you guys enjoy it.</p>
	<p>Soon we'd like this to become a persistent todo list or CMR widget.</p>
<?php
}

// calling all custom dashboard widgets
function dmg_custom_dashboard_widgets() {
	wp_add_dashboard_widget( 'dmg_welcome_widget', 'Welcome Home', 'dmg_welcome_widget' );
	/*
	Be sure to drop any other created Dashboard Widgets
	in this function and they will all load.
	*/
}


// removing the dashboard widgets
add_action( 'admin_menu', 'disable_default_dashboard_widgets' );
// adding any custom widgets
add_action( 'wp_dashboard_setup', 'dmg_custom_dashboard_widgets' );


/************* CUSTOM LOGIN PAGE *****************/

// calling your own login css so you can style it

//Updated to proper 'enqueue' method
//http://codex.wordpress.org/Plugin_API/Action_Reference/login_enqueue_scripts
function dmg_login_css() {
	wp_enqueue_style( 'dmg_login_css', get_stylesheet_directory_uri() . '/css/login.css', false );
	wp_enqueue_style( 'lato_fonts', 'http://fonts.googleapis.com/css?family=Lato', false );
}

// changing the logo link from wordpress.org to your site
function dmg_login_url() {
	return home_url();
}

// changing the alt text on the logo to show your site name
function dmg_login_title() {
	return get_option( 'blogname' );
}

// calling it only on the login page
add_action( 'login_enqueue_scripts', 'dmg_login_css', 10 );
add_filter( 'login_headerurl', 'dmg_login_url' );
add_filter( 'login_headertitle', 'dmg_login_title' );


/************* CUSTOMIZE ADMIN *******************/

/*
I don't really recommend editing the admin too much
as things may get funky if WordPress updates. Here
are a few functions which you can choose to use if
you like.
*/

// Custom Backend Footer
function dmg_custom_admin_footer() {
	_e( '<span id="footer-thankyou">Proudly developed by <a href="http://thinkgeneric.com" target="_blank">Think.genEric{}</a></span>.', 'dmgtheme' );
}

// adding it to the admin area
add_filter( 'admin_footer_text', 'dmg_custom_admin_footer' );


/************* Gravity Forms to Zoho *****************/


?>