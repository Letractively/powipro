<?php

/* simple class for section editing
 * -- everyone can take a look
 * -- only admins can edit sections
 * TODO -- deletion is only possible, if there is no contingent associated with the section
 */
class SectionsController extends AppController {
	public $name = 'Sections';
	public $components = array('RequestHandler');
	public $helpers = array('Bbcode');
	
	public $paginate = array(
		'Section' => array(
			'contain' => array('Contingent' => array('Semester', 'CourseType')),
			'limit' => 12,
			'order' => 'Section.abbreviation',
		)
	);
	
	private function saveSection ($id) {
		$this->data['Section']['id'] = $id;
	
		$this->Section->set($this->data);
		if ($this->Section->validates()) {
			$this->Section->save();
			$s = $this->Section->data;
		} else {
			$this->Session->setFlash('Fehler in der Eingabe: Bereich wurde nicht gespeichert!');
			$s = $this->data;
		}
	
		return $s;
	}

	//access managed through controllers/Sections/edit
	public function edit ($section_id = null) {
		//create section if section_id == null and no form data exists
		//save new section if section_id == null and form data exists
		//update section if section_id != null and form data exists
		//edit section if section_id != null and no form data exists
		
		if (!empty($this->data)) {
			$section = $this->saveSection($section_id);
			
			if (empty($this->Section->validationErrors)) {
				$this->Session->setFlash('Bereich gespeichert');
				$this->revisit('/sections/overview');
			} else {
				$this->Session->setFlash('Fehlerhafte Eingabe');
			}
			
		} else {
			if ($section_id == null) {
				$this->Section->create(array('abbreviation' => 'Abkürzung',
								 'name' => 'Name',
								 'description' => 'Beschreibung',
								 'comment' => 'Kommentar als Hilfestellung für Bewerber*innen.',
								 'id' => null));
				$section = $this->Section->data;
				
			} else {
				$this->Section->read(null, $section_id);
				$section = $this->Section->data;
			}
		}
		
		//prepare info for the view
		$this->set('section', $section);
	}
	
	//access managed throug controllers/Sections/delete
	public function delete ($section_id = null) {
		if (!isset($section_id)) {
			$this->revisit('/sections/overview');
		}
		
		$this->Section->id = $section_id;
		
		if ($this->Section->Contingent->find('count', array('conditions' =>
			array('section_id' => $section_id))) > 0) {
			$this->Session->setFlash('Für diesen Bereich sind bereits Daten (Kontingent) vorhanden. Er kann daher nicht gelöscht werden.');
			$this->revisit('/sections/overview');
		}
		
		$this->Section->delete();
		$this->revisit('/sections/overview');
	}
	
	public function overview () {
		$permission = $this->Access->getPermissions();
		if (!$permission['summary']) {
			$this->revisit('/users/home');
		}
		
		$this->Section->Behaviors->attach('Containable');
		/*$this->Section->contain(array('Contingent' => array('Semester', 'CourseType')));
		$sections = $this->Section->find('all');*/
		$sections = $this->paginate('Section');
		
		$this->set('sections', $sections);
		$this->set('permission', $permission);
		
		$this->visited();
	}
	
	public function view ($section_id = null) {
		if (!isset($section_id)) {
			$this->redirect('/sections/overview');
		}

		$permission = $this->Access->getPermissions();
		if (!$permission['summary']) {
			$this->revisit('/users/home');
		}
		
		$this->Section->Behaviors->attach('Containable');
		$this->Section->contain(array('Contingent' => array('Semester', 'CourseType')));
		$section = $this->Section->find('first', array('conditions' => array('Section.id' => $section_id)));

		$this->set('section', $section);
		$this->set('permission', $permission);
		
		$this->visited();
	}
	
	public function json_info ($section_id = null) {
		$this->view = 'Json';
		
		if (isset($this->params['form']['section_id']) || isset($section_id)) {
			if (!isset($section_id)) {
				$section_id = $this->params['form']['section_id'];
			}
			$section = $this->Section->find('first', array(
				'conditions' => array('Section.id' => $section_id),
				'recursive' => -1,
			));
			
			App::import('Helper', 'Bbcode');
			$bbcode = new BbcodeHelper();
			
			if (isset($section['Section']['comment'])) {
				$section['Section']['comment'] = $bbcode->parse($section['Section']['comment']);
			}
			
			$json = array('data' => $section);
			$this->set('json', $json);
		}
	}
}

?>
