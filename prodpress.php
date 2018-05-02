<?
/*
Plugin Name: ProdPress
Plugin URI: http://prod.press
Version: 0.1
Author: Hazlitt Eastman and Elliot Taylor
Description: An MVC application development plugin for WordPress
*/



//  Create php class for plugin
if (!class_exists("pp_app_plugin")) {
	class pp_app_plugin {
		//constructor
		function __construct() {
		}

	}
}

//  Instantiate the plugin class

if (class_exists("pp_app_plugin")) {
	$pp_app_plugin = new pp_app_plugin();
}



// list controller files

$files = array_slice(scandir($_SERVER['DOCUMENT_ROOT'] . '/wp-content/pp_app/controllers/'), 2);

$GLOBALS['controllers'] = array();

foreach ($files as $file){
	
	$fileinfo = pathinfo($file);
	
	if ($fileinfo["extension"] == "php") { 
		$controllers[] = $fileinfo['filename'];
	}
	
}



// load app config

$config_path = ABSPATH . 'wp-content/pp_app/config/config.php';
include($config_path);



// first slug routing

function pp_api_routes() {
	
	//explode the url
	$slugs = explode('/', $_SERVER['REQUEST_URI']);
	
	// if module then just execute module code else continue to run controller code
	if (isset($slugs[3])) {

			// trim slashes and load the corresponding controller and method
			$uri = ltrim ($_SERVER['REQUEST_URI'], '/');
			$uri = rtrim($uri, '/');
			
			pp_app($uri);

		exit;
	}


	//  if there are any matching controllers
	foreach ($GLOBALS['controllers'] as $route) {

		

		if ($slugs[1] == $route) {
	
			// trim slashes and load the corresponding controller and method
			$uri = ltrim ($_SERVER['REQUEST_URI'], '/');
			$uri = rtrim($uri, '/');
			pp_app($uri);
			
			exit();
		}


	
	}
			
}
add_action( 'init', 'pp_api_routes', 10 );


// custom routes

function pp_api_custom_routes_process($custom_routes) {
	//explode the url
	$slugs = explode('/', $_SERVER['REQUEST_URI']);

	// loop through custom urls
	foreach ($custom_routes as $custom_route => $controller_method) {
		if ($slugs[1] == $custom_route) {
			// load the events controller
			pp_app($controller_method);

			exit();
		}
	}
}


//  add custom query variables

function add_query_vars_filter($vars){
  $vars[] = "pp_route";
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



// add pp api endpoint

function pp_api_endpoint() {

	add_rewrite_endpoint( 'pp_api', EP_ALL );
}
add_action( 'init', 'pp_api_endpoint' );



// instantiate the routed class

function pp_app( $route = NULL ) {

	// get the route if not passed to this function

	if ($route == NULL) {
		var_dump('No Route Set');
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

	// module controller
	if (isset($route_slugs[2])) {
		$method_name = $route_slugs[2];
	}
	// standard controller
	elseif(isset($route_slugs[1])) {
		$method_name = $route_slugs[1];
	} else {
		$method_name = 'default';
	}


	// load controller
	
	if (isset($route_slugs[2])) {
		$controller_file = ABSPATH . 'wp-content/pp_app/modules/' . $module_name . '/controllers/' . $controller_name . ".php";
	} else {
		$controller_file = ABSPATH . 'wp-content/pp_app/controllers/' . $controller_name . ".php";
	}


	// check if the controller file exists

	if (file_exists($controller_file) == 1) {

		include $controller_file;

		// instantiate object
		$pp_app = new $controller_name();

		// check if method exists
		if (($method_name != '') && (method_exists($pp_app, $method_name)))  {

			// execute method
			$result = $pp_app->$method_name();
		} else {

			echo "<pre>ProdPress Error: No controller method found</pre>";

		}


	} else {

		echo "<pre>ProdPress Error: No controller found</pre>";

	}

	if (isset($result)) {

		return $result;

	}

}



