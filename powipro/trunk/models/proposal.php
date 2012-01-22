<?php
class Proposal extends AppModel {
	public $name = 'Proposal';
	public $order = array('Proposal.title DESC');
	public $belongsTo = array(
		'Applicant' => array(
			'className' => 'Applicant',
			'foreignKey' => 'applicant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FiledSemester' => array(
			'className' => 'Semester',
			'foreignKey' => 'filed_for',
		),
		'SelectedSemester' => array(
			'className' => 'Semester',
			'foreignKey' => 'selected_for',
		),
		'Section' => array(
			'className' => 'Section',
			'foreignKey' => 'section_id',
		),
		'CourseType' => array(
			'className' => 'CourseType',
			'foreignKey' => 'course_type_id'
		),
	);
	
	var $hasMany = array(
		'Coapplicant' => array(
		'className' => 'Coapplicant',
		'foreignKey' => 'proposal_id',
		'dependent' => true,),
	);

	public $actsAs = array(
		'Acl' => array('type' => 'controlled'),
		'Containable'
	);
	
	public $validate = array(
		'title' => array(
			'rule' => array('minLength', 6),
			'required' => true,
			'allowEmpty' => false,
			'last' => false,
			'message' => 'Sie müssen eine Titel eingeben, der zumindest 6 Zeichen lang ist'),
		'english_title' => array(
				'rule' => array('minLength', 6),
				'required' => false,
				'allowEmpty' => true,
				'last' => false,
				'message' => 'Die englische Übersetzung muss mindestens 6 Zeichen lang sein'),
		'subtitle' => array(
				'rule' => array('notEmpty'),
				'required' => true,
				'allowEmpty' => false,
				'last' => false,
				'message' => 'Sie müssen einen Untertitel angeben'),
		'description' => array(
				'rule' => array('minLength', 100),
				'required' => true,
				'allowEmpty' => false,
				'last' => false,
				'message' => 'Eine Beschreibung muss aussagekräftig sein'),
	);

	public function parentNode() {
		return 'Proposals';
	}
	
	public function setOwner ($acl, $proposal_id, $user_id) {
		/*$this->id = $proposal_id;
		$aco = $this->node();
		
		$acl->allow(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco'], 'read');
		$acl->allow(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco'], 'update');
		$acl->allow(array('model' => 'User', 'foreign_key' => $user_id), $aco[0]['Aco'], 'delete');*/
	}
	
	public function getOwnerID ($id) {
		if (!$this->valid($id)) return -1; //$this->id = $id;
		$this->Applicant->id = $this->field('applicant_id');
		return $this->Applicant->field('user_id');		
	}

	/* checks if the proposal is ready. this means
	   -- a section is selected
	   -- a course type is selected
	   -- probably more
	*/
	public function isReady ($id = null) {
		if (!$this->valid($id)) return false;
		return $this->field('section_id') != 0 && $this->field('course_type_id') != 0;
	}

	public function isFiled ($id = null) {
		if (!$this->valid($id)) return false;
		return $this->field('filed_for') != NULL;
	}
	
	public function isApproved ($id = null) {
		if (!$this->valid($id)) return false;		
		return $this->field('selected_for') != NULL;
	}
	
	public function status ($id = null) {
		if (!$this->valid($id)) return null;
		
		if ($this->isApproved()) return 'approved';
		else if ($this->isFiled()) return 'filed';
		else return 'new';
	}		
	
	public function file ($semester_id, $id = null) {
		if (!$this->valid($id)) return false;	
		$this->saveField('filed_for', $semester_id);
		if ($this->field('submission_date') == null) {
			$this->saveField('submission_date', date('Y-m-d'));
		}
		return true;
	}
	
	public function approve ($semester_id, $id = null) {
		if (!$this->valid($id)) return false;
		$this->saveField('selected_for', $semester_id);
		return true;
	}
	
	public function disprove ($id = null) {
		return $this->approve(null, $id);
	}
	
}


?>
