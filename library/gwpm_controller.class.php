<?php
class GwpmMainController {

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;

	function __construct($controller, $action, $variables, $model) {

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = new $model;

		$this->_template = new GwpmTemplate($controller, $action, $variables);

	}

	function set($name, $value) {
		$this->_template->set($name, $value);
	}

	function __destruct() {
		$this->_template->render();
	}

	function get($name) {
		return $this->_template->get($name);
	}
	
	function view() {
		
	}
	
	function edit() {
		
	}
	
	function update() {
		
	}

}