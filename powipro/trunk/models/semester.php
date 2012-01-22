<?php

class Semester extends AppModel {
	public $name = 'Semester';

	public $hasMany = array(
		'FiledProposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'filed_for',
		),
		'SelectedProposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'selected_for',
		),
		'Contingent' => array(
			'className' => 'Contingent',
			'dependent' => true,
		),
	);
	
	public $validate = array(
	'name' => array(
		'rule' => array('minLength', 10),
		'message' => 'Ein Semestername muss länger sein',
		'required' => true,
		'allowEmpty' => false),
	'start' => array(
		'rule' => array('date'),
		'message' => 'Es muss ein Datum angegeben werden',
		'required' => true,
		'allowEmpty' => false),
	'deadline' => array(
			'rule' => array('date'),
			'message' => 'Es muss ein Datum angegeben werden',
			'required' => true,
			'allowEmpty' => false),
	'final_deadline' => array(
			'rule' => array('date'),
			'message' => 'Es muss ein Datum angegeben werden',
			'required' => true,
			'allowEmpty' => false),
	);
	
	public $actsAs = array(
		'Acl' => array('type' => 'controlled'),
	);
	
	public function parentNode () {
		return 'Semesters';
	}

	function getAvailableSemesters ($section_id, $course_type_id) {
		return $this->Contingent->find('all',
			      array('conditions' => array('Contingent.section_id' => $section_id,
			      				  'Contingent.course_type_id' => $course_type_id,
			      				  'Contingent.contingent > 0',
			      				  'Semester.start <= CURRENT_DATE()',
			      				  'Semester.final_deadline >= CURRENT_DATE()',
			      				  'Semester.published' => '<> 0'),
			      	    'limit' => '10',
				    'fields' => array('DISTINCT Semester.id', 'Semester.name', 'Contingent.contingent'),
		));
	}

	function isOpen ($semester_id = null) {
		if (isset($semester_id)) {
			$this->id = $semester_id;
		}

		if (!isset($this->id)) {
			return false;
		}

		return $this->isPublished() && date('Y-m-d') <= $this->field('deadline');
	}

	function isLate ($semester_id = null) {
		if (isset($semester_id)) {
			$this->id = $semester_id;
		}

		if (!isset($this->id)) {
			return false;
		}

		return $this->isPublished()
		&& date('Y-m-d') <= $this->field('final_deadline')
		&& date('Y-m-d') > $this->field('deadline');
	}

	function isPublished ($semester_id = null) {
		if (isset($semester_id)) {
			$this->id = $semester_id;
		}

		if (!isset($this->id)) {
			return false;
		}

		return $this->field('published') != 0;
	}

	function willOpen ($semester_id = null) {
		if (isset($semester_id)) {
			$this->id = $semester_id;
		}

		if (!isset($this->id)) {
			return false;
		}

		return $this->isPublished() && $this->field('start') > date('Y-m-d');
	}

	function hasPassed ($semester_id = null) {
		if (isset($semester_id)) {
			$this->id = $semester_id;
		}

		if (!isset($this->id)) {
			return false;
		}

		return $this->isPublished() && $this->field('final_deadline') < date('Y-m-d');
	}
	
	public function status ($id = null) {
		if (!$this->valid($id)) return null;
	
		if ($this->isPublished()) return 'published';
		else return 'new';
	}
/*
	function getOpenSemesters () {
		$result = $this->find('all', array(
			'conditions' => array('Semester.deadline >= CURRENT_DATE()'.
					      ' AND Semester.start <= CURRENT_DATE()'),
			'fields' => array('Semester.name', 'Semester.id', 'Semester.deadline'),
			'recursive' => -1
		));
		return $result;
	}

	function getLateSemesters () {
		$result = $this->find('all', array(
			'conditions' => array('Semester.final_deadline >= CURRENT_DATE()' .
					      ' AND Semester.start <= CURRENT_DATE()'),
			'fields' => array('Semester.name', 'Semester.id', 'Semester.deadline'),
			'recursive' => -1,
			'order' => array('Semester.deadline DESC'),
		));
		return $result;
	}

	function getAvailableSemesters () {
		$open = $this->getOpenSemesters();
		if (!isset($open)) {
			$open = array();
		}
		$late = $this->getLateSemesters();

		foreach ($late as $sem) {
			$open[] = $sem;
		}

		return $open;
	}*/
}

?>
