<?php

class Contingent extends AppModel {
	public $name = 'Contingent';
	
	public $belongsTo = array(
		'Semester' => array(
				'className' => 'Semester',
				'foreignKey' => 'semester_id',
		),
		'Section' => array(
				'className' => 'Section',
				'foreignKey' => 'section_id',
		),
		'CourseType' => array(
				'className' => 'CourseType',
				'foreignKey' => 'course_type_id',
		),
	);
	
	public $actsAs = array('Containable');
	
	public function isAvailable ($semester_id, $section_id, $course_type_id) {
		$contingent = $this->find('first', array(
			'conditions' => array('Contingent.section_id' => $section_id,
					      'Contingent.course_type_id' => $course_type_id,
					      'Contingent.semester_id' => $semester_id),
			'fields' => array('Contingent.contingent')));
			
		if ($contingent['Contingent']['contingent'] > 0) {
			return true;
		} else {
			return false;
		}
	}
}

?>
