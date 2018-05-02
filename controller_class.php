<?
	
	// mvc app controller class
	
	class pp_app_controller extends pp_app_core {

		function __construct() {
			
			parent::__construct();

		}

		public function module_name($dir) {
			$module_name = basename(dirname($dir,1));
			return $module_name;
		}

	}