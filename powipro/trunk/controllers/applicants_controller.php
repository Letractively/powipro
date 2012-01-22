<?php
App::import('Sanitize');

class ApplicantsController extends AppController {

	public $name = 'Applicants';
	public $helpers = array('Bbcode', 'DatePicker');
	
	public $paginate = array(
		'Proposal' => array(
			'contain' => array('FiledSemester', 'Section'),
			'limit' => 10,
			'order' => array('FiledSemester.name', 'Proposal.title'),
		),
	);
	
	private function saveApplicant ($applicant_id) {
		$this->data['Applicant']['id'] = $applicant_id;
		$this->data['Applicant']['user_id'] = $this->Auth->user('id');
		
		$this->Applicant->set($this->data);
		if ($this->Applicant->validates()) {
			$this->Applicant->save();
			$applicant = $this->Applicant->data;
			
			//a new applicant - set up the permissions
			if ($applicant_id == null) {
				$applicant_id = $applicant['Applicant']['id'];
				$this->Applicant->setOwner($this->Acl, $this->Applicant->field('id'), $this->Auth->user('id'));
			}
			
		} else {
			$applicant = $this->data;
		}
	
		return $applicant;
	}
	
	
	public function edit($applicant_id = null) {
		
		//create a new applicant if applicant_id == null and no form data exists
		//save a new applicant if applicant_id == null and form data exists
		//update an applicant if applicant_id != null and form data exists
		//edit an applicant if applicant_id != null and no form data exists
		
		if (isset($applicant_id) && $applicant_id != null) {
			$this->Access->check($this->Applicant, $applicant_id, 
				array('flash' => 'Zugriff verweigert', 'redirect' => '/users/home'));
		}
		
		if (!empty($this->data)) {
			$applicant = $this->saveApplicant($applicant_id);
			
			if (empty($this->Applicant->validationErrors)) {
				$this->Session->setFlash('Daten erfolgreich ge&auml;ndert!');
				$this->revisit('/users/home');
			} else {
				$this->Session->setFlash('Ihre Eingabe ist unvollständig oder fehlerhaft');
			}
			
		} else {
			if ($applicant_id == null) {
				$this->Applicant->create(array('id' => null,
					'name' => $this->Session->read('Auth.User.name'),
					'last_name' => $this->Session->read('Auth.User.last_name'),
					'email' => $this->Session->read('Auth.User.email'),
					'birthday' => '', 'phone' => '', 'mobile' => '', 'title' => '',
					'degrees' => '', 'habilitated' => '', 'habilitation_date' => '',
					'habilitation_place' => '', 'social_security' => '',
					'nationality' => '', 'federal_employment' => '', 'address' => '',
					'publications' => '', 'university_teaching' => '', 'general_teaching' => '',
				));
				$applicant = $this->Applicant->data;
				
			} else {
				$applicant = $this->Applicant->read(null, $applicant_id);
			}
		}
				     
		$this->set('applicant', $applicant);
	}
	
	/* access controlled via Applicants/Applicant.id */
	public function view($applicant_id = null) {
		if (!isset($applicant_id)) {
			$this->revisit('/users/home');
		}
		
		$this->Access->check($this->Applicant, $applicant_id, array('mode' => 'read',
			'flash' => 'Sie haben nicht die nötige Berechtigung für diese Aktion',
			'redirect' => '/users/home'));
		
		$this->Applicant->Behaviors->attach('Containable');
		$this->Applicant->contain(array('User'));
		$applicant = $this->Applicant->find('first', array('conditions' =>
		array('Applicant.id' => $applicant_id)));
		
		$this->loadModel('Proposal');
		$proposals = $this->paginate('Proposal', array('Proposal.applicant_id' => $applicant_id));
		
		$this->set('proposals', $proposals);
		$this->set('editable', $this->Access->check($this->Applicant, $applicant_id, array('mode' => 'update')));
		$this->set('applicant', Sanitize::clean($applicant, array('escape' => false)));
		$this->set('detail', $this->Auth->user('group_id') == $this->groups['Administrators']);
		
		$this->visited();
	}
}




