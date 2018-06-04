<?
	
	// pp app parent class
	
	class pp_app_core {

		function __construct() {
			
			// declare data array
			$data = array();
			
		}

		function get_view_path($file) {

			// check module
			$module_name = $this->check_module();

			if($module_name) {
				$module = 'modules/' . $module_name . '/';
			} else {
				$module = null;
			}

			$return = WP_CONTENT_DIR . '/pp_app/' . $module . 'views/' . $file . '.php';
			return $return;
		}

		/**
		 * Checks if a Module and returns module name
		 *
		 * @return string or false
		 */

		function check_module() {

			$backtrace = debug_backtrace()[1]['file'];
			$backtrace_explode = preg_split("#/#", $backtrace); 


			// if module then just execute module code else continue to run controller code
			if ('modules' == $backtrace_explode[5]) {
				$module = $backtrace_explode[6];
				return $module;


			} else {				
				return false;
			}

		}

		// method to load a model
		function load_model($model) {

			// check module exists
			$module = $this->check_module();

			if ($module) {
				$model_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/models/' . $model . '.php';
			} else {
				$model_path = ABSPATH . 'wp-content/pp_app/models/' . $model . '.php';
			}

			
			require_once($model_path);
			
			$this->$model = new $model;
		
		}
		
		// method to load a view
		function load_view($view, $data = NULL) {

			// check module exists
			$module = $this->check_module();

			// check if module
			if ($module) {
				$view_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/views/' . $view . '.php';
			} else {
				$view_path = ABSPATH . 'wp-content/pp_app/views/' . $view . '.php';
			}

			if (isset($data)) {
				extract($data);
			}
			
			return include($view_path);
		
		}
		
		//method to load a helper
		function load_helper($helper) {

			// check module exists
			$module = $this->check_module();

			if ($module) {
				$helper_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/helpers/' . $helper . '.php';
			} else {
				$helper_path = ABSPATH . 'wp-content/pp_app/helpers/' . $helper . '.php';
			}
			
			require_once($helper_path);
			
			$this->$helper = new $helper;
		
		}

		//method to load a library
		function load_library($library) {
			
			// check module exists
			$module = $this->check_module();

			if ($module) {
				$model_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/models/' . $model . '.php';
				$library_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/libraries/' . $library . '.php';
			} else {
				$library_path = ABSPATH . 'wp-content/pp_app/libraries/' . $library . '.php';
			}

			require_once($library_path);
			
			$this->$library = new $library;
		
		}


		// method to load a module
		function load_module($module) {

			$module_path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '.php';
			require_once($module_path);

			$this->$module = new $module;

		}

				// method to load a module
		function load_module_controller($module, $controller) {

			$path = ABSPATH . 'wp-content/pp_app/modules/' . $module . '/controllers/' . $controller . '.php';
			require_once($path);

			$this->$controller = new $controller;

		}



	}