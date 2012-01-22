<?php
class Applicant extends AppModel {
	var $name = 'Applicant';

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Proposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'applicant_id',
			'dependent' => true,
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
		'Acl' => array('type' => 'controlled'),
		'Cipher' => array('autoDecrypt' => true,
						  'fields' => array('social_security', 'email', 'phone', 'mobile', 'address')),
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
		'email' => array(
			'rule' => array('email', true),
			'required' => true,
			'allowEmpty' => false,
			'last' => false,
			'message' => 'Geben Sie eine gültige E-Mail-Adresse an'),
		'phone' => array(
			'rule' => array('minLength', 7),
			'required' => true,
			'allowEmpty' => false,
			'last' => false,
			'message' => 'Geben Sie hier Ihre primäre Telefonnummer ein'),
		'mobile' => array(
			'rule' => array('minLength', 7),
			'required' => false,
			'allowEmpty' => true,
			'last' => false,
			'message' => 'Geben Sie eine gültige Telefonnummer ein'
		),
		'habilitated' => array(
			'rule' => array('boolean'),
			'required' => false,
			'allowEmpty' => true,
			'message' => 'Sie sind entweder habilitiert oder nicht'),
		'habilitation_date' => array(
			'rule' => array('date'),
			'required' => false,
			'allowEmpty' => true,
			'message' => 'Sie müssen hier ein Datum angeben'),
		'habilitation_place' => array(
			'rule' => array('minLength', 3),
			'required' => false,
			'allowEmpty' => true,
			'message' => 'Geben Sie einen Ort an'),
		'federal_employment' => array(
			'rule' => array('boolean'),
			'required' => false,
			'allowEmpty' => true,
			'message' => 'Sie sind entweder im Bundesdienst angestellt oder nicht'),
		'title' => array(
			'rule' => array('between', 4, 4),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Eine Anrede besteht aus vier Buchstaben'),
		'degrees' => array(
			'rule' => array('notEmpty'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Geben Sie ihre akademischen Grade ein'),
		'birthday' => array(
			'rule' => array('date'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Sie müssen hier ein Datum angeben'),
		'social_security' => array(
			'rule' => array('numeric'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Geben Sie eine gültige (vollständige) SV-Nummer an'),
		'nationality' => array(
			'rule' => array('notEmpty'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Geben Sie Ihre Staatsbürgerschaft an'),
		'address' => array(
			'rule' => array('notEmpty'),
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Geben Sie ihre Wohnadresse an'),
	);
	

	function parentNode() {
		return 'Applicants';
	}

	/*function createWithOwner ($acl, $user_id, $data = array()) {
		$data['user_id'] = $user_id;

		$this->create($data);
		$this->save();

		$aco = $this->node();
		$acl->allow(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco']);
	}*/
	
	function setOwner ($acl, $applicant_id, $user_id) {
		$this->id = $applicant_id;
		$aco = $this->node();
		$acl->allow(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco']);
	}
	

	function hasAccess ($acl, $user_id) {
		if (!isset($this->id)) {
			return false;
		}

		$aco = @$this->node();
		return @$acl->check(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco']);
	}
}
