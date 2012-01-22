<?php

/* simple class for course type administration
 * -- everyone can take a look
 * -- only admins can edit/create
 * TODO: --  deletion only, if there is no contingent associated with the coursetype
 */

class CourseTypesController extends AppController {
	public $name = 'CourseTypes';
	public $helpers = array('Bbcode');
	
	public $paginate = array(
		'CourseType' => array(
			'contain' => array('Contingent' => array('Semester', 'Section')),
			'limit' => 12,
			'order' => 'CourseType.type',
		),
	);
	
	/* returns available course types, given some section
	   -- is called via (POSTed) ajax-request, so data is in this->params['form']['param']
	   -- returns a json-object
	   -- only those course types are avaible, for which there is some contingent
	   -- for some of the currently available semesters
	*/
	public function json_available ($section_id = null) {
		$this->view = 'Json';
		
		if (isset($this->params['form']['section_id'])) {
			$section_id = $this->params['form']['section_id'];
		}		
		$course_types = $this->CourseType->getAvailableCourseTypes($section_id);
		$json = array('data' => $course_types);
		$this->set('json', $json);
	}
	
	private function saveCourseType ($id) {
		$this->data['CourseType']['id'] = $id;
		
		$this->CourseType->set($this->data);
		if ($this->CourseType->validates()) {
			$this->CourseType->save();
			$ct = $this->CourseType->data;
		} else {
			$this->Session->setFlash('Fehler in der Eingabe: Kurstyp wurde nicht gespeichert!');
			$ct = $this->data;
		}
		
		return $ct;
	}
	
	//access managed through controllers/CourseTypes/edit
	public function edit ($ct_id = null) {
		//create if id == null and no form data exists
		//save new if id == null and form data exists
		//update if id != null and form data exists
		//edit if id != null and no form data exists
		
		if (!empty($this->data)) {
			$coursetype = $this->saveCourseType($ct_id);
			
			if (empty($this->CourseType->validationErrors)) {
				$this->Session->setFlash('Kurstyp gespeichert');
				$this->revisit('/course_types/overview');
			} else {
				$this->Session->setFlash('Fehlerhafte Eingabe');
			}
			
		} else {
			if ($ct_id == null) {
				$this->CourseType->create(array('abbreviation' => 'PS',
								 'ECTS' => '6',
								 'hours' => '2',
								 'type' => 'Proseminar',
								 'description' => 'Beschreibung',
								 'comment' => 'Zusätzliche Informationen',
								'id' => null));
				$coursetype = $this->CourseType->data;
				
			} else {
				$this->CourseType->read(null, $ct_id);
				$coursetype = $this->CourseType->data;
			}
		}
		
		//prepare info for the view
		$this->set('coursetype', $coursetype);
		$this->visited();			
	}
	
	//access managed through controllers/Sections/delete
	public function delete ($ct_id = null) {
		if (!isset($ct_id)) {
			$this->revisit('/course_types/overview');
		}
		
		$this->CourseType->id = $ct_id;
		
		if ($this->CourseType->Contingent->find('count', array('conditions' =>
			array('course_type_id' => $section_id))) > 0) {
			$this->Session->setFlash('Für diesen Kurstyp sind bereits Daten (Kontingent) vorhanden. Er kann daher nicht gelöscht werden.');
			$this->revisit('/sections/overview');
		}
		
		$this->CourseType->delete();
		$this->revisit('/course_types/overview');
	}
	
	public function overview () {
		$permission = $this->Access->getPermissions();
		if (!$permission['summary']) {
			$this->revisit('/users/home');
		}
		
		$this->CourseType->Behaviors->attach('Containable');
		/*$this->CourseType->contain(array('Contingent' => array('Semester', 'Section')));
		$cts = $this->CourseType->find('all');*/
		$cts = $this->paginate('CourseType');
		$this->set('coursetypes', $cts);
		$this->set('permission', $permission);
		
		$this->visited();
	}
	
	public function view ($ct_id = null) {
		if (!isset($ct_id)) {
			$this->redirect('/course_types/overview');
		}
		
		$permission = $this->Access->getPermissions();
		if (!$permission['summary']) {
			$this->revisit('/users/home');
		}
		
		$this->CourseType->Behaviors->attach('Containable');
		$this->CourseType->contain(array('Contingent' => array('Semester', 'Section')));
		$ct = $this->CourseType->find('first', array('conditions' => array('CourseType.id' => $ct_id)));

		$this->set('coursetype', $ct);
		$this->set('permission', $permission);
		
		$this->visited();
	}
}

?>
