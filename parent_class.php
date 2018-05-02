<?
	
	// pp app parent class
	
	class pp_app_core {

		function __construct() {
			
			// declare data array
			$data = array();
			
		}
		
		// method to load a view
		function load_view($view, $data = NULL) {

			if (isset($data)) {
				extract($data);
			}
			
			$view_path = ABSPATH . 'wp-content/pp_app/views/' . $view . '.php';

			return include($view_path);
		
		}
		
		//method to load a helper
		function load_helper($helper) {
			
			$helper_path = ABSPATH . 'wp-content/pp_app/helpers/' . $helper . '.php';
			require_once($helper_path);
			
			$this->$helper = new $helper;
		
		}

		//method to load a library
		function load_library($library) {
			
			$library_path = ABSPATH . 'wp-content/pp_app/libraries/' . $library . '.php';
			require_once($library_path);
			
			$this->$library = new $library;
		
		}

		
	}