<?php
class User extends AppModel {
	var $name = 'User';
	
	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Applicant' => array(
			'className' => 'Applicant',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	var $actsAs = array(
		'Acl' => array('type' => 'requester'),
	);
	
	public $validate = array(
	'name' => array(
		'rule' => array('minLength', 2),
		'required' => true,
		'allowEmpty' => false,
		'last' => false,
		'message' => 'Sie müssen eine Vornamen eingeben'),
	'last_name' => array(
			'rule' => array('minLength', 2),
			'required' => true,
			'allowEmpty' => false,
			'last' => false,
			'message' => 'Sie müssen eine Nachnamen eingeben'),
	'password' => array(
			'rule' => array('minLength', 5),
			'required' => true,
			'allowEmpty' => false,
			'last' => false,
			'message' => 'Das Passwort muss mindestens 6 Zeichen lang sein'),
	'email' => array(
			'isEmail' => array(
				'rule' => array('email', true),
				'required' => true,
				'allowEmpty' => false,
				'last' => false,
				'message' => 'Geben Sie eine gültige E-Mail-Adresse an'),
			'isUnique' => array(
				'rule' => array('isUnique', true),
				'message' => 'Diese E-Mail-Adresse ist bereits in Verwendung'),
			),	
	);

	function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}

		
		if (isset($this->data['User']['group_id'])) {
			$group_id = $this->data['User']['group_id'];
		} else {
			$group_id = $this->field('group_id');
		}

		if (!$group_id) {
			return null;
		} else {
			return array('Group' => array('id' => $group_id));
		}
	}
	
	function bindNode($user) {
		return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
	}

	function countApplicants($user_id = null) {
		if (!isset($user_id) && isset($this->id)) {
			$user_id = $this->id;
		}
	
		if (!isset($this->id)) {
			return 0;
		}

		return $this->Applicant->find('count', array('conditions' => array('Applicant.user_id' => $user_id)));
	}
}

?>










