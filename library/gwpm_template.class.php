<?php
class GwpmTemplate {

	protected $variables;
	protected $_controller;
	protected $_action;
	protected $_qryVars;

	function __construct($controller, $action, $qryVars) {
		$this->_controller = $controller;
		$this->_action = $action;
		$this->_qryVars = $qryVars;
	}

	/** Set Variables **/

	function set($name, $value) {
		$this->variables[$name] = $value;
	}

	function setVariables($variables) {
		$keys = array_keys($variables);
		foreach ($keys as $key) {
			$this->variables[$key] = $variables[$key];
		}
	}

	function get($name) {
		if(isset($this->variables[$name]))
			return $this->variables[$name];
		else return null;
	}

	function getModeContent($fieldHTML, $value) {
		if ($this->isEditMode()) {
			printf($fieldHTML);
		} else
			printf("<span class='edit-mode-value'>%s</span>", $value);
	}

	function get_gwpm_formated_url($params, $addPid = true) {
		$url = '?' . $params ;
		if (isset ($_GET['page_id'])) {
			$url .= '&page_id=' . $_GET['page_id'] ;
		}
		if(isset($_GET['pid']) && $_GET['pid'] != "" && $addPid) {
			$url .= '&pid=' . $_GET['pid'] ;
		}
		echo wp_nonce_url($url, 'gwpm');
	}

	function get_gwpm_edit_link() {
		if (!$this->isEditMode()) {
			if ($this->isUpdateMode()) {
				echo str_replace('&action=update', '', $_SERVER['REQUEST_URI']) . '&action=edit'; 
			} else  {
				echo str_replace('&action=view', '', $_SERVER['REQUEST_URI']) . '&action=edit'; 
			}
		}
	}

	function isEditMode() {
		if ($this->get('action') == 'edit') {
			return true;
		}
		return false;
	}
	
	function isUpdateMode() {
		if ($this->get('action') == 'update')
			return true;
		return false;
	}
	
	function isOwnPage() {
		if(isset($_GET['pid']) && $_GET['pid'] != "") {
			if($_GET['pid'] != get_current_user_id() ) {
				return false;
			}
		}
		return true ;
	}

	function getActionURL() {
		if ($this->isEditMode()) {
			$newurl = str_replace('&action=view', '', $_SERVER['REQUEST_URI']) ;
			echo str_replace('&action=edit', '', $newurl) . '&action=update';
		} elseif ($this->isUpdateMode()) {
			echo $_SERVER['REQUEST_URI'] . '&action=update';
		} else {
			echo $_SERVER['REQUEST_URI'];
		}
	}

	function getSelectItem($options, $name, $value = null) {
		if(isset($value) && $value !=  '' && is_array($value)) {
			$value = implode($value) ;
		}
		$selectObj = '<select name=' . $name . ' id=' . $name . ' >';
		$selectObj .= '<option value="">Select</option>';
		$counter = 0;
		foreach ($options as $option) {
			$counter++;
			$selectObj .= "<option value='$counter'";
			if ($counter == $value) {
				$selectObj .= ' selected="selected" ';
			}
			$selectObj .= " >$option</option>";
		}
		$selectObj .= "</select>";
		echo $selectObj;
	}
	
	function getUserImageURL($userId, $imageName, $gender = null) {
		if(!isset($imageName)) {
			if($gender == "Male")
				return GWPM_PUBLIC_IMG_URL . URL_S . 'male.jpg' ;
			elseif($gender == "Female")
				return GWPM_PUBLIC_IMG_URL . URL_S . 'female.jpg' ;
			else 
				return GWPM_PUBLIC_IMG_URL . URL_S . 'gwpm_icon.png' ;
		} else {
			// added time to the image url to get image from url without using the cache - helpful while updating the image
			return GWPM_GALLERY_URL . URL_S . $userId . URL_S . $imageName . '?' . gettimeofday(true);
		}
	}

	function getBackURL() {
		$url = '<a href="javascript:window.history.back();" rel="prev">';
		$url .= 'Click to Go Back';
		$url .= '</a>';
		return $url ;
	}

	function getPlainURL() {
		$url = str_replace('&action=edit', '', $_SERVER['REQUEST_URI']);
		$url = str_replace('&action=update', '', $url);
		$url .= '&action=view';
		$url = '<a href="' . $url . '" rel="prev">';
		$url .= 'Go to Home page';
		$url .= '</a>';
		return $url ;
	}

	/** Display Template **/

	function render() {

		$this->setVariables($this->_qryVars);
		$actionURL = GWPM_APPLICATION_URL . DS . 'views' . DS . $this->_controller . DS . 'gwpm_pg_' . $this->_action . '.php';
		if (!file_exists($actionURL)) {
			if ($this->_action == 'update') {
				$actionURL = GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_message.php';
			} else {
				$this->_action = 'invalid_action';
				$actionURL = GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_' . $this->_action . '.php';
			}
		}
		include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_header.php');
		include ($actionURL);
		include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_footer.php');

	}

}