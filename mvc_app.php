<?
/*
Plugin Name: MVC App
Plugin URI: http://www.hazlitteastman.com
Version: 1.2
Author: Hazlitt Eastman
Description: MVC application development plugin for WordPress
*/



//  Create php class for plugin
if (!class_exists("mvc_app_plugin")) {
    class mvc_app_plugin {
	 	//constructor
        function __construct() {
        }

	}
}

//  Instantiate the plugin class

if (class_exists("mvc_app_plugin")) {
    $mvc_app_plugin = new mvc_app_plugin();
}



// load app config

$config_path = ABSPATH . 'wp-content/mvc_app/config/config.php';
include($config_path);



//  add custom query variables
function add_query_vars_filter($vars){
  $vars[] = "mvc_app_route";
  $vars[] = "role";
  $vars[] = "user_id";
  return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');



// Include core class files

include('parent_class.php');
include('model_class.php');
include('controller_class.php');
include('view_class.php');
include('helper_class.php');



// enable sessions and kill them at log out

add_action('init', 'start_session', 1);
add_action('wp_logout', 'end_session');
add_action('wp_login', 'end_session');

function start_session() {
    if(!session_id()) {

       	session_start();
    }
}

function end_session() {
    session_destroy ();
}



// add mvc api endpoint

function mvc_api_endpoint() {

    add_rewrite_endpoint( 'mvc_api', EP_ALL );
}
add_action( 'init', 'mvc_api_endpoint' );



// interupt Wordpress loading templates if /mvc_api/ is in the URL

function mvc_api_redirect() {
    global $wp_query;

    // if this is not a request for json or a singular object then bail
    if ( ! isset( $wp_query->query_vars['mvc_api'] ) ) return;

	mvc_app();
    exit;
}
add_action( 'template_redirect', 'mvc_api_redirect' );




// instantiate the routed class

function mvc_app( $route = NULL ) {





	// get the route if not passed to this function
	if ($route == NULL) {
		$route = '';

		// check if module or app
		if (isset($_GET['mvc_module_route'])) {
			$route = $_GET['mvc_module_route'];
		} elseif (isset($_GET['mvc_app_route'])) {
			$route = $_GET['mvc_app_route'];
		} else {
			$route = "start";
		}
	}


	// breakdown the route
	$route_slugs = explode('/', $route);

	// if the first slug is empty go to the default route
	if (!isset($route_slugs[0])) {
		$controller_name = "start";
	} elseif(isset($route_slugs[2])) {
		$module_name = $route_slugs[0];
		$controller_name = $route_slugs[1];
	} else {
		$controller_name = $route_slugs[0];
	}

	// use specified method in the route if not use default
	$method_name = '';
	if (isset($route_slugs[2])) {
		$method_name = $route_slugs[2];
	} elseif(isset($route_slugs[1])) {
		$method_name = $route_slugs[1];
	} else {
		$method_name = 'default';
	}


	// load controller
	if (isset($route_slugs[2])) {
		$controller_file = ABSPATH . 'wp-content/mvc_app/modules/' . $module_name . '/controllers/' . $controller_name . ".php";
	} else {
		$controller_file = ABSPATH . 'wp-content/mvc_app/controllers/' . $controller_name . ".php";
	}


	// check if the controller file exists

	if (file_exists($controller_file) == 1) {

		include $controller_file;

		// instantiate object
		$mvc_app = new $controller_name();

		// check if method exists
		if (($method_name != '') && (method_exists($mvc_app, $method_name)))  {

			// execute method
			$result = $mvc_app->$method_name();
		} else {

			echo "Bunk!";

		}


	} else {

		echo "Doh!";

	}

	if (isset($result)) {

		return $result;

	}

}

//  instantiate the routed class via a shortcode

add_shortcode( 'mvc_app', 'mvc_app' );



// locate template


/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/woocommerce-plugin-templates/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/woocommerce-plugin-templates/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param 	string 	$template_name			Template to load.
 * @param 	string 	$string $template_path	Path to templates.
 * @param 	string	$default_path			Default path to template files.
 * @return 	string 							Path to the template file.
 */
function wcpt_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// Set variable to search in woocommerce-plugin-templates folder of theme.
	if ( ! $template_path ) :
		$template_path = 'woocommerce-plugin-templates/';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
	endif;
	// Search template file in theme folder.
	$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'wcpt_locate_template', $template, $template_name, $template_path, $default_path );
}




// auto setup url

add_action('init', function() {

	// get url path of current page
	$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');

	// check if custom mvc path set in wp-config.php
	if (defined('MVC_PATH')) {
		$path = MVC_PATH;
	} else {
		$path = 'mvc';
	}

	if ( $url_path === $path ) {
		// load the mvc app if exists
		mvc_app();
		exit(); // just exit if template was found and loaded
	}
});



// add WP Admin Button

function mvc_admin_button($wp_admin_bar){
	$args = array(
		'id' => 'mvc-button',
		'title' => 'MVC Welcome',
		'href' => '/mvc-welcome/',
		'meta' => array(
			'class' => 'mvc-admin-button'
		)
	);
	$wp_admin_bar->add_node($args);
}

add_action('admin_bar_menu', 'mvc_admin_button', 90);


