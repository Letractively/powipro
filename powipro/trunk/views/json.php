<?php
 
class JsonView extends View {
	public $content = null;
	public $debugKit = null;
 
	function __construct(&$controller, $register = true) {
 		if (is_object($controller) && isset($controller->viewVars['json'])) {
			parent::__construct($controller, $register);
			$this->content = $controller->viewVars['json'];
		}
		
		if ($register) {
			ClassRegistry::addObject('view', $this);
		}
		Configure::write('debug', 0);
	}
 
	function render($action = null, $layout = null, $file = null) {
		
		if ($this->content === null) {
			$content = array('empty' => true);
		}
		
		$data = json_encode($this->content);

		return $data;
  	}
}

?>
