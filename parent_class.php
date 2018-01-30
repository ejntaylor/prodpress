<?
	
	// mvc app parent class
	
	class mvc_app_core {

		function __construct() {
			
			// declare data array
			$data = array();
			
		}

		// method to load a model
		function load_model($model) {
			
			$model_path = ABSPATH . 'wp-content/mvc_app/models/' . $model . '.php';
			require_once($model_path);
			
			$this->$model = new $model;
		
		}
		
		// method to load a view
		function load_view($view, $data = NULL) {
			
			$view_path = ABSPATH . 'wp-content/mvc_app/views/' . $view . '.php';
			
			if (isset($data)) {
				extract($data);
			}
			
			return include($view_path);
		
		}
		
		//method to load a helper
		function load_helper($helper) {
			
			$helper_path = ABSPATH . 'wp-content/mvc_app/helpers/' . $helper . '.php';
			require_once($helper_path);
			
			$this->$helper = new $helper;
		
		}

		//method to load a library
		function load_library($library) {
			
			$library_path = ABSPATH . 'wp-content/mvc_app/libraries/' . $library . '.php';
			require_once($library_path);
			
			$this->$library = new $library;
		
		}

		
	}