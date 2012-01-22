<?php
class CourseType extends AppModel {
	public $name = 'CourseType';
	
	public $order = 'CourseType.description';
	
	public $hasMany = array(
		'Proposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'course_type_id',
		),
		'Contingent' => array(
			'className' => 'Contingent',
			'foreignKey' => 'course_type_id',
		),
	);
	
	public $validate = array(
		'abbreviation' => array(
			'rule' => array('notEmpty'),
			'message' => 'Abkürzung angeben (z.B. SE, PS, ...)',
			'required' => true,
			'allowEmpty' => false),
		'ECTS' => array(
			'rule' => array('notEmpty'),
			'message' => 'Geben Sie die ECTS-Punktanzahl an',
			'required' => true,
			'allowEmpty' => false),
		'hours' => array(
			'rule' => array('notEmpty'),
			'message' => 'Geben Sie die Semesterstunden an',
			'required' => true,
			'allowEmpty' => false),
		'description' => array(
			'rule' => array('minLength', 10),
			'message' => 'Die Beschreibung muss länger sein',
			'required' => true,
			'allowEmpty' => false),
		'type' => array(
			'rule' => array('notEmpty'),
			'message' => 'Geben Sie einen Typ an (Semester, Vorlesung, ...',
			'required' => true,
			'allowEmpty' => false),
		'comment' => array(
			'rule' => array('minLength', 50),
			'message' => 'Das Kommentar muss länger sein, sonst kennt sich niemand aus!',
			'required' => true,
			'allowEmpty' => false),
	);
	
	public function getAvailableCourseTypes ($section_id = null) {
		$conditions = array('Semester.start <= CURRENT_DATE()',
		 		    'Semester.final_deadline >= CURRENT_DATE()',
		 		    'Semester.published' => '<> 0',
		 		    'Contingent.contingent > 0');
		if (isset($section_id)) {
			$conditions['Contingent.section_id'] = $section_id;
		}
	
		return $this->Contingent->find('all', array(
				'conditions' => $conditions,
				'limit' => 40,
				'fields' => array('DISTINCT CourseType.id', 'CourseType.description', 'CourseType.type',
						  'CourseType.abbreviation', 'CourseType.ECTS', 'CourseType.hours'),
		));
	}
	
	public function isAvailable ($section_id, $course_type_id = null) {
		if (!$this->valid($course_type_id)) return false;
		
		$conditions = array('Semester.start <= CURRENT_DATE()',
				    'Semester.final_deadline >= CURRENT_DATE()',
				    'Contingent.contingent > 0',
				    'Contingent.section_id' => $section_id,
				    'Contingent.course_type_id' => $this->id);
				    
		return $this->Contingent->find('count', array('conditions' => $conditions)) > 0;
	}
}
