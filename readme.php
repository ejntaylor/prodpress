<!DOCTYPE html>
<html lang="en-GB">
<head>
	<link rel='stylesheet' href='bootstrap.min.css' type='text/css' media='all' />
</head>
<body>
	<header>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>MCV App plugin notes</h1>
				</div>
			</div>
		</div>
	</header>
	<main>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					
					<h2>File paths</h2>
					<p>Here are the paths to models, views and controllers</p>
					<ul>
						<li>Models :  /wpcontent/pp_app/models</li>
						<li>Views :  /wpcontent/pp_app/views</li>
						<li>Controllers :  /wpcontent/pp_app/controllers</li>
					</ul>
					
					
					<h2>Controller example</h2>
					<p>A controller:</p>
					<ul>
						<li>Is a PHP OOP class</li>
						<li>Loads models and requests data from them</li>
						<li>Contains logic to prepare data for output in a view</li>
						<li>Appends all data values to the '$data' array</li>
						<li>Loads a view and parses the data array to it</li>
					</ul>
					
					<h3>Code:</h3>
					<pre>
			
class alternative extends pp_app_controller {

	// this function executes whenever this class is loaded
	function __construct() {
		
		parent::__construct();
		
		// load a model
		$this->load_model('alternative_model');
		
		// get a data value from the model by calling one of it's methods
		$data['model_name'] = $this->alternative_model->get_model_name();
		
		// set some other data values
		$data['wine'] = "wine infromation";
		$data['sku'] = "8907f087gf0987dfg";
		$data['slurp'] = "gently glugged";
	
		// load a view and pass the $data array to it	
		$this->load_view('alternative', $data);
	}
}
					
					</pre>
				
					<h2>Model example</h2>
					<p>A model:</p>
					<ul>
						<li>Is a PHP OOP class</li>
						<li>Contains methods that request data from the database</li>
						<li>Returns database data to the controller that called it</li>
					</ul>
					
					<h3>Code:</h3>
					<pre>
			
class alternative_model extends pp_app_model {

	function __construct() {
		
		parent::__construct();
			
	}

	// example method for providing some data
	function get_model_name() {
		
		return "alternative model name";
	
	}	
}
					
					</pre>

					<h2>View example</h2>
					<p>A view:</p>
					<ul>
						<li>Is an HTML file</li>
						<li>Outputs the data values provided by the controller</li>
						<li>The $data array from the controller is extracted for output in the view e.g. $data['my_value'] in the controller becomes $my_value in the view.</li>
					</ul>
					
					<h3>Code:</h3>
					<pre>
			
&lt;h1&gt; Alternative MVC view&lt;/h1&gt;

&lt;p>model name :  <? echo htmlspecialchars('<?= $model_name ?>'); ?>&lt;/p&gt;

&lt;p>wine :  <? echo htmlspecialchars('<?= $wine ?>'); ?>&lt;/p&gt;

&lt;p>sku :  <? echo htmlspecialchars('<?= $sku ?>'); ?>&lt;/p&gt;

&lt;p>slurp :  <? echo htmlspecialchars('<?= $slurp ?>'); ?>&lt;/p&gt;

&lt;a href="&lt;?= $_SERVER['PHP_SELF'] ?&gt;"&gt;Start&lt;/a&gt;
					
					</pre>

					
					<h2>Routing</h2>
					<p>Routing to a controller method is done by setting the GET variable 'pp_app_route'. Example:</p>
					<p>/?pp_app_route=alternative/show_name</p>
					<p>In this example the route is 'alternative/show_name'.  This will load the 'alternative' controller and execute the 'show_name' method.</p>
					
					<h3>Default route</h3>
					<p>The default route is implemented by adding the following shortcode to a page:</p>
					<p>[pp_app]</p>
					<p>By default this loads the controller 'start.php' and calls the constructor method.</p>
					
					<h3>Example code:</h3>
					<pre>

class start extends pp_app_controller {

	function __construct() {
		
		parent::__construct();
		
		// load a model
		$this->load_model('start_model');
		
		// get data from the model method
		$data['model_name']  = $this->start_model->get_model();
		
		//set some other data
		$data['text'] = "text infromation";
		$data['number'] = "075674563";
		$data['eggs'] = "coggled please";
		
		// load a view and pass the $data array to it
		$this->load_view('initial', $data);
	}
}
					</pre>


					<h2>Sessions</h2>
					
					<p>The session helper can be loaded to get and set session variables.  Here is sample controller code:</p>
					
					<pre>
						
	$this->load_helper('session');
			
	$this->session->set_variable('user_name', 'Bob');
			
	$data['user_name'] = $this->session->get_variable('user_name');

					</pre>
				</div>
			</div>
		</div>	
	</main>
	<footer>
		<p><br><br><br><br><br><br><br><br></p>
		
	</footer>
</body>
</html>