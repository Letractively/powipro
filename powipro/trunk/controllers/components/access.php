<?php
/* -- permissions pose a bit of a problem:
 * --- the function has the ACO controllers/ControllerName+s/functionName - here, everyone has to have
 * --- access, but the view has to be different for different users.
 * --- as for now, the permissions are stored **separately** in
 * --- ControllerName+s/permissions.
 * --- If there are more specific permissions (e.g. permissions for EACH Applicants/Proposals)
 * --- those are stored under ControllerName+s/ControllerName.ForeignId (standard)
 */

class AccessComponent extends Object {
	private $controller;
	private $user_id;
	public $modes = array('create', 'read', 'update', 'delete', 'approve', 'summary');
	public $components = array('Session', 'Auth');
	
	public function startup (&$controller) {
		$this->controller =& $controller;
		$this->user_id = $controller->Session->read('Auth.User.id');
	}

	/* checks wether the current or specified user has acccess to the ACO
	   options: 'flash' => sets Session-Flash, 'redirect' => sets controller redirect, 'mode' => specifies access mode
	*/
	public function check ($Aco, $id, $options = array(), $user_id = null) {
		$Aco->id = $id;
		if (!$Aco->exists()) {
			$access = false;
		}
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}
		if (!isset($options['mode'])) {
			$options['mode'] = '*';
		}

		$aco_node = @$Aco->node();
		$access = @$this->controller->Acl->check(array('model' => 'User', 'foreign_key' => $user_id),
				       			$aco_node[0]['Aco'], $options['mode']);

		if (isset($options['flash']) && !$access) {
			$this->controller->Session->setFlash($options['flash']);
		}

		if (isset($options['redirect']) && !$access) {
			$this->controller->redirect($options['redirect']);
		}

		return $access;
	}

	public function reset ($Aco, $id, $user_id = null, $mode = null) {
		$Aco->id = $id;
		if (!$Aco->exists()) {
			return;
		}
		if (!isset($mode)) {
			$mode = '*';
		}
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}

		$aco = @$Aco->node();
		@$this->controller->Acl->inherit(array('model' => 'User', 'foreign_key' => $user_id),
						 $aco[0]['Aco'], $mode);
	}

	public function grant ($Aco, $id, $user_id = null, $mode = null) {
		$Aco->id = $id;
		if (!$Aco->exists()) {
			return;
		}
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}
		if (!isset($mode)) {
			$mode = '*';
		}

		$aco = @$Aco->node();
		@$this->controller->Acl->allow(array('model' => 'User', 'foreign_key' => $user_id),
					       $aco[0]['Aco'], $mode);
	}
	
	public function revoke ($Aco, $id, $user_id = null, $mode = null) {
		$Aco->id = $id;
		if (!$Aco->exists()) {
			return;
		}
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}
		if (!isset($mode)) {
			$mode = '*';
		}
	
		$aco = @$Aco->node();
		@$this->controller->Acl->deny(array('model' => 'User', 'foreign_key' => $user_id),
			$aco[0]['Aco'], $mode);
	}

	/* returns an array with all permissions the current or specified user has for some ACO */
	public function read ($Aco, $id, $user_id = null) {
		$return = array();

		$Aco->id = $id;
		if (!$Aco->exists()) {
			return $return;
		}
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}

		$aco = @$Aco->node();
		foreach ($this->modes as $mode) {
			$return[$mode] = $this->controller->Acl->check(array('model' => 'User', 'foreign_key' => $user_id),
									$aco[0]['Aco'], $mode);

		}

		return $return;
	}

	/* get permissions for the some (or the current) user for the current controller */
	public function getPermissions ($user_id = null) {
		if (!isset($user_id)) {
			$user_id = $this->user_id;
		}

		$return = array();
		foreach ($this->modes as $mode) {
			$return[$mode] = $this->controller->Acl->check(array('model' => 'User', 'foreign_key' => $user_id),
									'controllers/' . $this->controller->name,
									$mode);
		}

		return $return;
	}

	public function isAdmin () {
		if (isset($this->controller->Auth))
			return $this->Auth->user('group_id') == $this->controller->groups['Administrators'];
		else return false;
	}

	public function isExecutive () {
		if (isset($this->controller->Auth))
			return $this->Auth->user('group_id') == $this->controller->groups['SectionHeads'];
		else return false;
	}

	public function isUser () {
		if (isset($this->controller->Auth))
			return $this->Auth->user('group_id') == $this->controller->groups['Users'];
		else return false;
	}
}


?>
