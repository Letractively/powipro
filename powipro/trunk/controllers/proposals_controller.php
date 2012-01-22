<?php
App::import('Sanitize');

class ProposalsController extends AppController {

	public $name = 'Proposals';
	public $uses = array('Proposal', 'Section', 'Semester', 'CourseType');
	public $components = array('RequestHandler', 'Message');
	public $helpers = array('Google', 'Bbcode');
	
	public $paginate = array(
		'Proposal' => array(
			'contain' => array('Section', 'CourseType', 'Applicant'),
			'limit' => 8,
			'order' => 'Proposal.id',
		),
	);
	
	private function setPermissionsForLockedProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
		
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
			$aco[0]['Aco'], 'approve');
	}
	
	private function setPermissionsForUnlockedProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
	
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
			$aco[0]['Aco'], 'approve');
	}
	
	private function setPermissionsForNewProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
	
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
		$aco[0]['Aco']);
	
		if ($owner_id == null)
			return;
		
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'update');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'delete');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'summary');
	}
	
	private function setPermissionsForFiledProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
	
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
		$aco[0]['Aco']);
	
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'update');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'delete');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'summary');
	
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'summary');
	
		if ($owner_id == null) return;
		
		$this->Acl->inherit(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco']);
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'summary');
	}
	
	private function setPermissionsForApprovedProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
	
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
		$aco[0]['Aco']);
	
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'update');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco'], 'summary');
	
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco'], 'summary');
	
		if ($owner_id == null) return;
		
		$this->Acl->inherit(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco']);
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'summary');
	}

	/* files a proposal
	   -- only if the selected semester is open (or late)
	   -- only with proper access-level
	   -- only if the proposal is properly filled out
	   -- only if the contingent in the selected semester allows it
	   -- redirects to proposal/view
	   -- denies the proposal's owner update rights (except s/he has special rights)
	   -- cannot be undone
	*/
	public function file ($proposal_id = null) {
		if (!isset($proposal_id) || empty($this->data)) {
			$this->redirect('/users/home');
		}

		//update-permissions allows for filing
		$this->Access->check($this->Proposal, $proposal_id,
				     array('flash' => 'Zugriff verweigert', 'redirect' => '/proposals/view' . $proposal_id,
				     	   'mode' => 'update'));
		
		//filing is only available ONCE
		/*if ($this->Proposal->isFiled($proposal_id)) {
			$this->revisit('/users/home');
		}*/

		//check if the proposal is ready for filing
		if (!$this->Proposal->isReady($proposal_id)) {
			$this->Session->setFlash('Das Proposal ist noch nicht vollständig ausgefüllt und kann daher nicht abgegeben werden.');
			$this->Proposal->id = $proposal_id;
			$this->redirect('/proposals/edit/' . $this->Proposal->field('applicant_id') . '/' . $proposal_id);
		}

		//get POSTed data
		if (isset($this->data['Proposal']['filed_for'])) {
			$semester_id = $this->data['Proposal']['filed_for'];
		}

		//permissions exception: admins can file for every semester (?)
		if (!$this->Access->isAdmin() && !$this->Semester->isOpen($semester_id)) {
			if ($this->Semester->isLate()) {
				$this->Session->setFlash('Sie haben das Proposal erst in der Nachfrist eingereicht. Eine Garantie für die rechtzeitige Bearbeitung kann daher nicht mehr gegeben werden');
			} else {
				$this->Session->setFlash('Sie können keine Proposals mehr für dieses Semester einreichen, da die Einreichfrist und die Nachfrist bereits vorbei sind. Wenden Sie sich an die Administration oder wählen Sie ein anderes Semester');
				$this->revisit('/proposals/view/' . $proposal_id);
			}
		}

		//check if the selected semester/section/course_type-combination is available
		$this->Proposal->id = $proposal_id;
		$section_id = $this->Proposal->field('section_id');
		$course_type_id = $this->Proposal->field('course_type_id');
		if (!$this->Semester->Contingent->isAvailable($semester_id, $section_id, $course_type_id)) {
			$this->Session->setFlash('Für das gewählte Semester sind in diesem Bereich keine Kurse der gewählten Art verfügbar.'
						.'Wähhlen Sie einen anderen Bereich, Kurstyp oder ein anderes Semester beim einreichen aus!');
			$this->redirect('/proposals/edit/' . $this->Proposal->field('applicant_id') . '/' . $proposal_id);
		}

		//effectively denies the owner of the proposal any more update/delete operations on the proposal,
		//except s/he is an admin: the reset method resets all rights to the proposal to the
		//general group rights for the given ACO.
		/*$owner_id = $this->Proposal->getOwnerID($proposal_id);
		$this->Access->reset($this->Proposal, $proposal_id, $owner_id);
		//but: the user should still be able to get more than a summary of his own proposal.
		$this->Access->grant($this->Proposal, $proposal_id, $owner_id, 'read');
		*/
		$this->Proposal->file($semester_id, $proposal_id);
		$this->setPermissionsForFiledProposal($proposal_id);
		
		$this->Proposal->contain('Applicant');
		$proposal = $this->Proposal->find('first', array('conditions' => array(
			'Proposal.id' => $proposal_id)));
		$this->sendFiledEmail($proposal);
		
		$this->Session->setFlash('Sie haben Ihr Proposal erfolgreich eingereicht!');
		$this->revisit('/proposals/view/' . $proposal_id);
	}
	
	private function sendFiledEMail ($proposal) {
		$name = $this->Auth->user('name') . ' ' . $this->Auth->user('last_name');
		$message = 'Sehr geehrte(r) ' . $new_user['User']['name'] . ' ' . $new_user['User']['last_name'] . '!<br/>'
		. 'Ihr Proposal mit dem Titel "' . $proposal['Proposal']['title']
		. '" wurde eingereicht und ist in Bearbeitung.<br/>'
		. 'Sobald entschieden sein wird, ob Ihr Proposal angenommen oder abgelehnt wird, werden '
		. ' Sie via E-Mail von der Entscheidung benachrichtigt.<br/>'
		. 'Diese Entscheidung wird frühestens zwei Wochen nach Ende der Einreichfrist stattfinden.<br/>'
		. 'Wir bitten um Ihr Verständnis.';
		
		
		$this->Message->send($name,
							 $proposal['Applicant']['email'],
							 'Sie haben ein Proposal eingereicht!',
							 $message);
	}

	private function repairSectionAndCourseTypeInFormData () {
		if (isset($this->data['Proposal']['section_id'])) {
			$section_id = $this->data['Proposal']['section_id'];
		} else {
			$section_id = null;
		}
		if (isset($this->data['Proposal']['course_type_id'])) {
			$course_type_id = $this->data['Proposal']['course_type_id'];
		} else {
			$course_type_id = null;
		}

		if ($this->CourseType->isAvailable($section_id, $course_type_id)) {
			return;
		} else if ($this->Section->isAvailable($section_id) && $course_type_id != null) {
			$this->data['Proposal']['course_type_id'] = null;
			$this->Session->setFlash('Für diesen Bereich ist der ausgewählte Kurstyp nicht verfügbar.<br />'
									.'Bitte wählen Sie einen passenden Kurstyp aus.');
		} else {
			$this->data['Proposal']['course_type_id'] = null;
			$this->data['Proposal']['section_id'] = null;
		}
	}

	private function saveProposal ($proposal_id, $applicant_id) {
		$this->data['Proposal']['id'] = $proposal_id;
		$this->data['Proposal']['applicant_id'] = $applicant_id;
		
		$this->Proposal->set($this->data);
		if ($this->Proposal->validates()) {
			$this->Proposal->save();
			
			//a new proposal - set up the permissions
			if ($proposal_id == null) {
				$this->setPermissionsForNewProposal($this->Proposal->field('id'));
			}
			
		} else {
			$this->set('errors', $this->Proposal->invalidFields());
			$this->Session->setFlash('Fehler in der Eingabe: Proposal wurde nicht gespeichert!');
		}
		
		$proposal = $this->data;
		
		return $proposal;
	}
	
	/* adds a coapplicant
	 * -- update permission for proposal must be set
	 */
	private function coapplicant ($proposal_id = null, $second_applicant_id = null) {
		
		if (!$this->Access->check($this->Proposal, $proposal_id, array('mode' => 'update'))) {
			return false;
		}
		
		$this->Proposal->id = $proposal_id;
		$applicant_id = $this->Proposal->field('applicant_id');
		
		if ($applicant_id == $second_applicant_id) {
			$this->Session->setFlash('Sie können sich nicht selbst auswählen');
			return false;
		}
		
		$this->loadModel('Coapplicant');
		
		$exists = $this->Coapplicant->find('count', array('conditions' =>
			array('Coapplicant.applicant_id' => $second_applicant_id,
				  'Coapplicant.proposal_id' => $proposal_id)));
		
		if ($exists > 0) {
			$this->Session->setFlash('Die ausgewählte Person ist schon als Ko-Bewerber*in geführt. Vielleicht hat sie es noch nicht bestätigt!');
			return false;
		}
		
		$this->Coapplicant->create(array(
				'applicant_id' => $second_applicant_id,
				'proposal_id' => $proposal_id,
				'accepted' => 0,
				'id' => null,
			));
		$this->Coapplicant->save();
		
		$this->Session->setFlash('Die Person wurde als Ko-Bewerber*in angefragt. Sie muss dies erst bestätigen!');
		return true;
	}
	
	public function coaccept ($coapplicant_id = null) {
		if (!isset($coapplicant_id)) {
			$this->revisit('/users/home');
		}
		
		$this->loadModel('Coapplicant');
		$this->Coapplicant->id = $coapplicant_id;
		
		if ($this->Coapplicant->exists()) {
			$this->Coapplicant->contain(array('Applicant.user_id', 'Proposal.id'));
			$coapplicant = $this->Coapplicant->find('first', array('conditions' =>
				array('Coapplicant.id' => $coapplicant_id)));
			
			if ($this->Auth->user('id') != $coapplicant['Applicant']['user_id']) {
				$this->Session->setFlash('Sie können fremde Einladungen nicht annehmen!');
				$this->revisit('/users/home');
			}
			
			$this->Coapplicant->id = $coapplicant_id;
			if ($this->Coapplicant->field('accepted') == 1) {
				$this->Session->setFlash('Sie haben die Einladung schon einmal angenommen.');
				$this->revisit('/users/home');
			}
			
			$this->Coapplicant->saveField('accepted', 1);
			$this->Access->grant($this->Proposal, $coapplicant['Coapplicant']['proposal_id'],
				$this->Session->read('Auth.User.id'), 'read');
			$this->Access->grant($this->Proposal, $coapplicant['Coapplicant']['proposal_id'],
				$this->Session->read('Auth.User.id'), 'summary');
			$this->Session->setFlash('Einladung angenommen!');
			
		}
		
		$this->revisit('/users/home');
	}
	
	public function corefuse ($coapplicant_id = null) {
		if (!isset($coapplicant_id)) {
			$this->revisit('/users/home');
		}
		
		$this->loadModel('Coapplicant');
		$this->Coapplicant->id = $coapplicant_id;
		
		if ($this->Coapplicant->exists()) {
			$this->Coapplicant->contain(array('Applicant.user_id', 'Proposal.id'));
			$coapplicant = $this->Coapplicant->find('first', array('conditions' =>
			array('Coapplicant.id' => $coapplicant_id)));
				
			if ($this->Auth->user('id') != $coapplicant['Applicant']['user_id']) {
				$this->Session->setFlash('Sie können fremde Einladungen nicht ablehnen!');
				$this->revisit('/users/home');
			}
				
			$this->Coapplicant->id = $coapplicant_id;
			if ($this->Coapplicant->field('accepted') == 1) {
				$this->Session->setFlash('Sie haben die Einladung schon angenommen. Das können Sie nicht mehr rückgängig machen!');
				$this->revisit('/users/home');
			}
						
			$this->Coapplicant->delete();
			$this->Session->setFlash('Einladung abgelehnt');		
		}
		
		$this->revisit('/users/home');
	}
	
	/* opens an editor for the proposal (new proposal or updating an existing one)
	  -- if the proposal is not yet filed
	  -- OR the permissions allow it (admin)
	  -- otherwise redirect to proposal/view
	  -- tricky part about section/course_type-selection:
	  --- the view has to update (AJAX) which course_types are available given some section
	  --- sends info about the course_types to the view
	  ---- if no section is selected (yet), send all available course_types to the view
	  --- as you see in modify, the course_type can only be set, if the section is selected.
	  -- unobstrusive javascript
	*/
	public function edit ($applicant_id = null, $proposal_id = null) {
		if (!isset($applicant_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Access->check($this->Proposal->Applicant, $applicant_id,
			array('flash' => 'Sie können ein Proposal nur mit gültigen Bewerbungsunterlagen erstellen',
				  'redirect' => '/applicants/edit'));
				
				
		//create a new proposal, if the proposal_id == null no form data exists
		//save a new proposal, if the proposal_id == null and form data exists
		//save an existing proposal, if the proposal_id != null and form data exists
		//edit an existing proposal if proposal_id != null and no form data exists
		//maybe a proposal got un-filed. in that case, the ownership has to be reset!
		if ($proposal_id != null) {
			if (!$this->Access->check($this->Proposal, $proposal_id, array('mode' => 'update'))) {
				$this->Proposal->id = $proposal_id;
				if ($applicant_id == $this->Proposal->field('applicant_id') &&
					!$this->Proposal->isFiled($proposal_id)) {
					//it's safe to reset the owner!
					$this->Proposal->setOwner($this->Acl, $proposal_id, $this->Auth->user('id'));
				} else {
					$this->Session->setFlash('Bereits eingereichte Proposals sind nicht editierbar. Wenden Sie sich an die Administration');
					$this->redirect('/proposals/view/' . $proposal_id);
				}
			}
		}
		
		if (!empty($this->data)) {
			$this->repairSectionAndCourseTypeInFormData();
			$proposal = $this->saveProposal($proposal_id, $applicant_id);

			if (empty($this->Proposal->validationErrors)) {
				
				if (!empty($this->data['Proposal']['second_applicant_id'])) {
					if ($this->coapplicant($proposal['Proposal']['id'],
							$this->data['Proposal']['second_applicant_id'])) {
						
						$this->Session->setFlash('Proposal und Ko-Bewerber*in gespeichert');
						$this->revisit('/users/home');
					}
					
				} else {
					$this->Session->setFlash('Proposal gespeichert');
					$this->revisit('/users/home');
				}
			}
			
		} else {
			$proposal = null;
			if ($proposal_id == null) {
				$this->Proposal->create(
				array('applicant_id' => $applicant_id,
					  'id' => null,
					  'title' => 'Titel', 'subtitle' => 'Untertitel',
					  'section_id' => null, 'course_type_id' => null,
					  'english_title' => 'English translation',
					  'description' => 'Beschreiben Sie hier die Inhalte der Lehrveranstaltung',
					  'comment' => 'Beschreiben Sie hier den geplanten Ablauf, verwendete Methoden, '
					   			. 'Literatur und andere relevante Details Ihrer Lehrveranstaltung.',
				));
				$proposal = $this->Proposal->data;
			} else {
				$proposal = $this->Proposal->read(null, $proposal_id);
			}
		}
		
		//load the data for the view
		$sections = $this->Section->getAvailableSections();

		$section_id = null;
		if ($proposal['Proposal']['section_id'] != 0) {
			$section_id = $proposal['Proposal']['section_id'];
		}
		$course_types = $this->CourseType->getAvailableCourseTypes($section_id);

		$this->set('proposal', $proposal);
		$this->set('sections', $sections);
		$this->set('course_types', $course_types);
	}
	
	/* moves a proposal from one semester to another
	 * -- only possible if there is a contingent for the other semester
	 * -- only admins, controlled via controllers/Proposals/move
	 */
	public function move ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->Session->setFlash('Nichts ausgewählt.');
			$this->revisit('/users/home');
			
		} else {

			if (!empty($this->data)) {
				$semester_id = $this->data['semester_id'];
				$section_id = $this->data['section_id'];
				$course_type_id = $this->data['course_type_id'];
					
				$this->Proposal->id = $proposal_id;
				$this->Semester->id = $semester_id;

				if ($this->Semester->field('published') == 0) {
					$this->Session->setFlash('Verschieben nicht möglich. Ausgewähltes Semester ist noch in Bearbeitung.');
					$this->revisit('/users/home');
				}

				if ($this->Proposal->field('filed_for') == null) {
					$this->Session->setFlash('Verschieben nicht möglich. Ausgewähltes Proposal ist noch in Bearbeitung.');
					$this->revisit('/users/home');
				}

				if ($this->Proposal->field('selected_for') != null) {
					$this->Session->setFlash('Verschieben nicht möglich. Ausgewähltes Proposal ist bereits angenommen.');
					$this->revisit('/users/home');
				}
				
				$this->loadModel('Contingent');
				$this->Contingent->contain();
				$contingent = $this->Contingent->find('first', array('conditions' => array(
					'Contingent.semester_id' => $semester_id,
					'Contingent.section_id' => $section_id,
					'Contingent.course_type_id' => $course_type_id,
					'Contingent.contingent >' => 0,
				)));
				
				if (!isset($contingent['Contingent'])) {
					$this->Session->setFlash('Verschieben nicht möglich. Kein Kontingent im gewünschten Semester vorhanden.');
					$this->revisit('/users/home');
				}
				
				$this->Proposal->saveField('filed_for', $semester_id);
				$this->Proposal->saveField('section_id', $section_id);
				$this->Proposal->saveField('course_type_id', $course_type_id);
				$this->Session->setFlash('Das Proposal wurde verschoben!');
				$this->revisit('/users/home');
								
			} else {
			//populate data
				
				$semesters = $this->Semester->find('all', array(
					'conditions' => array('Semester.closed' => 0),
					'recursive' => -1,
				));
				
				$sections = $this->Section->find('all', array(
					'order' => 'Section.abbreviation',
					'recursive' => -1,
				));
				
				$course_types = $this->CourseType->find('all', array(
					'order' => 'CourseType.description',
					'recursive' => -1,
				));

				$this->Proposal->contain();
				$proposal = $this->Proposal->find('first', array(
					'conditions' => array('Proposal.id' => $proposal_id),
				));
				
				$applicant = $this->Proposal->Applicant->find('first', array(
					'conditions' => array('Applicant.id' => $proposal['Proposal']['applicant_id']),
				));

				$this->set('proposal', Sanitize::clean($proposal, array('escape' => false)));
				$this->set('applicant', Sanitize::clean($applicant, array('escape' => false)));
				$this->set('semesters', $semesters);
				$this->set('sections', $sections);
				$this->set('course_types', $course_types);
				
				$this->visited();
			}
		}
		
	}

	/* shows all (filed) proposals for a given semester and optionally a given section
	   -- depending on permissions:
	   --- users: only basic data (title, type, count)
	   --- section-heads: full data (expandable) plus approve
	   --- admins: full data plus delete/modify
	   -- works by setting up a permission-array, which contains all the permissions
	   -- the current user has for all selected proposals.
	*/
	public function overview ($semester_id = null, $section_id = null, $selected_status = null) {
		if (!isset($semester_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Access->check($this->Semester, $semester_id,
			array('flash' => 'Dieses Semester dürfen Sie nicht sehen!',
				  'redirect' => '/semesters/overview',
				  'mode' => 'read'));
		
		$conditions = array('Proposal.filed_for' => $semester_id);

		if (!empty($this->params['url']['section'])) {
			$section_id = $this->params['url']['section'];
			$this->params['named']['section'] = $section_id;
		} else if (isset($this->params['named']['section'])) {
			$section_id = $this->params['named']['section'];
		} 
		if (isset($section_id)) {
			$conditions['Proposal.section_id'] = $section_id;
		}
		
		if (!empty($this->params['url']['status'])) {
			$selected_status = $this->params['url']['status'];
			$this->params['named']['status'] = $selected_status;
		} else if (isset($this->params['named']['status'])) {
			$selected_status = $this->params['named']['status'];
		}		
		if (isset($selected_status)) {
			if ($selected_status == 'approved')
				$conditions['Proposal.selected_for <>'] = null;
			else if ($selected_status == 'open')
				$conditions['Proposal.selected_for'] = null;
		}

		$proposals = $this->paginate('Proposal', $conditions);
		
		
		$permissions = array();
		$status = array();
		$editable = array();
		$approvable = array();
		foreach ($proposals as $proposal) {
			$permissions[$proposal['Proposal']['id']] = $this->Access->read($this->Proposal, $proposal['Proposal']['id']);
			$status[$proposal['Proposal']['id']] = $this->Proposal->status($proposal['Proposal']['id']);
			
			$owner_id = $this->Proposal->getOwnerID($proposal['Proposal']['id']);
			if ($owner_id != null &&
				$this->Access->check($this->Proposal, $proposal['Proposal']['id'], 
					array('mode' => 'update'), $owner_id)) {
				$editable[$proposal['Proposal']['id']] = true;
			} else {
				$editable[$proposal['Proposal']['id']] = false;
			}
			
			$this->Proposal->id = $proposal['Proposal']['id'];
			$aco = $this->Proposal->node();
			$approvable[$proposal['Proposal']['id']] = 
				$this->Acl->check(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
					$aco[0]['Aco'], 'approve');
		}
		
		
		$conditions = array(
			'Semester.id' => $semester_id,
			'Contingent.contingent <>' => 0,
		);
		
		if (isset($section_id)) {
			$this->set('section', $section_id);
			$conditions['Section.id'] = $section_id;
		}
				
		$sections = $this->Section->Contingent->find('all', array(
			'conditions' => array('Semester.id' => $semester_id),
			'fields' => array('DISTINCT Section.id', 
				'Section.abbreviation', 'Section.name', 'Section.description'),
		));
		
		$semester_permissions = $this->Access->read($this->Semester, $semester_id);
		
		$this->set('semester_permissions', $semester_permissions);
		$this->set('selected_status', $selected_status);
		$this->set('selected_section', $section_id);
		$this->set('sections', $sections);
		$this->set('proposals', $proposals);
		$this->set('permissions', $permissions);
		$this->set('status', $status);
		$this->set('editable', $editable);
		$this->set('approvable', $approvable);
		$this->set('exportable', $this->Access->isAdmin());
				
		$semester = $this->Semester->find('first', array('conditions' => array('id' => $semester_id), 'recursive' => -1));
		$this->set('semester', $semester);

		if (isset($section_id)) {
			$section = $this->Section->find('first', array('conditions' => array('id' => $section_id), 'recursive' => -1));
			$this->set('section', $section);
		}

		$this->visited();
	}

	/* approves a proposal (sets it selected_for-semester)
	   -- only with proper permissions
	*/
	public function approve ($proposal_id = null, $semester_id = null) {
		if (!isset($proposal_id) || !isset($semester_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Access->check($this->Proposal, $proposal_id, array('flash' => 'Zugriff verweigert',
							 'redirect' => '/users/home',
							 'mode' => 'approve'));
		
		$this->Access->check($this->Semester, $semester_id,
				array('flash' => 'Annehmen von Proposals ist nicht mehr aktiviert! Wenden Sie sich an die Administration.', 'redirect' => '/semesters/overview',
					  'mode' => 'approve'));
		
		$this->Proposal->id = $proposal_id;
		$course_type_id = $this->Proposal->field('course_type_id');
		$section_id = $this->Proposal->field('section_id');
		
		$this->loadModel('Contingent');
		$this->Contingent->contain(array('Section.name', 'CourseType.abbreviation'));
		$contingent = $this->Contingent->find('first', array(
		'conditions' => array(
			'Contingent.section_id' => $section_id,
			'Contingent.semester_id' => $semester_id,
			'Contingent.course_type_id' => $course_type_id,)
		));
		
		if ($contingent['Contingent']['contingent'] > $contingent['Contingent']['booked']) {
			$this->Contingent->id = $contingent['Contingent']['id'];
			$this->Contingent->saveField('booked', $contingent['Contingent']['booked'] + 1);

		} else {
			$this->Session->setFlash('Sie haben das Kontingent ausgeschöpft. Proposal wurde nicht ausgewählt');
			$this->revisit('/users/home');
		}
		
		$rest = $contingent['Contingent']['contingent'] - $contingent['Contingent']['booked'] - 1;
		
		$this->Proposal->approve($semester_id, $proposal_id);
		$this->setPermissionsForApprovedProposal($proposal_id);
		if ($rest == 0) {
			$this->Session->setFlash('Proposal wurde ausgewählt.'
			 . ' Sie dürfen keine weiteren Proposals mehr auswählen (Kontingent ausgeschöpft).');
		} else {
			$this->Session->setFlash('Proposal wurde ausgewählt.'
			. ' Sie dürfen noch ' . $rest . ' weitere Proposals in diesem Bereich auswählen.');
		}
		$this->revisit('/proposals/overview/' . $semester_id);
	}
	

	/* removes a certain proposal. only admins and sectionheads can do that, 
	   whenever they want. */
	public function disprove ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Access->check($this->Proposal, $proposal_id, array('flash' => 'Zugriff verweigert',
									  'redirect' => '/users/home',
									  'mode' => 'approve'));
		
		$this->Proposal->id = $proposal_id;
		$semester_id = $this->Proposal->field('selected_for');
				
		$this->Access->check($this->Semester, $semester_id,
			array('flash' => 'Abwählen von Proposals ist nicht mehr aktiviert! Wenden Sie sich an die Administration.', 'redirect' => '/semesters/overview',
				  'mode' => 'approve'));
		
		$section_id = $this->Proposal->field('section_id');
		$course_type_id = $this->Proposal->field('course_type_id');
		
		$this->loadModel('Contingent');
		$this->Contingent->contain(array('Section.name', 'CourseType.abbreviation'));
		$contingent = $this->Contingent->find('first', array(
		'conditions' => array(
			'Contingent.section_id' => $section_id,
			'Contingent.semester_id' => $semester_id,
			'Contingent.course_type_id' => $course_type_id, 
		)));
		
		$this->Contingent->id = $contingent['Contingent']['id'];
		$this->Contingent->saveField('booked', $contingent['Contingent']['booked']-1);

		$rest = $contingent['Contingent']['contingent'] - $contingent['Contingent']['booked'] + 1;
		
		$this->Proposal->disprove($proposal_id);
		$this->setPermissionsForFiledProposal($proposal_id);
		$this->Session->setFlash('Das Proposal wurde abgewählt.'
		. ' Sie dürfen jetzt wieder ' . $rest . ' Proposal(s) für diesen Bereich auswählen.');

		$this->revisit('/proposals/overview/' . $semester_id);
	}

	/* central hub for the proposal:
	   -- edit (given the update-permission)
	   -- approve (given the approve-permission, will also need a semester select)
	   -- file (--> needs a semester-select; if open/late; given the update-permission)
	   -- view (given the view-permission)
	   -- summary (given the summary-permission)
	   -- works by setting up a permission array
	*/
	public function view ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Proposal->id = $proposal_id;
		if (!$this->Proposal->exists()) {
			$this->Session->setFlash('Das ausgewählte Proposal existiert nicht (mehr).');
			$this->redirect('/users/home');
		}

		$this->Proposal->contain(array('Section', 'CourseType', 'Applicant', 'FiledSemester', 
			'Coapplicant' => array('Applicant.name', 'Applicant.last_name')));
		$proposal = $this->Proposal->find('first', array('conditions' => array('Proposal.id' => $proposal_id)));

		$section_id = $proposal['Proposal']['section_id'];
		$course_type_id = $proposal['Proposal']['course_type_id'];
		$semesters = $this->Semester->getAvailableSemesters($section_id, $course_type_id);
		$permissions = $this->Access->read($this->Proposal, $proposal_id);

		$this->set('status', $this->Proposal->status($proposal_id));
		$this->set('proposal', Sanitize::clean($proposal, array('escape' => false)));
		$this->set('permissions', $permissions);
		$this->set('semesters', $semesters);

		$this->visited();
	}

	/* exports all (approved) proposals for a given semester and OPTIONAL: section
	   -- only admins can access this (Proposals/export is only granted to Group.1)
	*/
	public function export ($semester_id = null, $section_id = null) {
		if (!isset($semester_id) || !$this->Access->isAdmin()) {
			$this->revisit('/users/home');
		}
		
		$conditions = array('selected_for' => $semester_id);
		if (isset($section_id)) {
			$conditions['section_id'] = $section_id;
		}
		$this->Proposal->contain(array('SelectedSemester', 
			'Applicant', 'Section', 'Coapplicant' => array('Applicant')));
		$proposals = $this->Proposal->find('all', array('conditions' => $conditions));
		
		$this->loadModel('Contingent');
		$this->Contingent->contain('Semester', 'Section', 'CourseType');
		$free_contingents = $this->Contingent->find('all', array('conditions' => 
			array('Contingent.semester_id' => $semester_id)));  
		
		$this->set('proposals', Sanitize::clean($proposals, array('escape' => false)));
		$this->set('free_contingents', $free_contingents);
		
		$this->Semester->id = $semester_id;
		$this->set('filename', $this->Semester->field('name'));

		$this->render('export', 'xls');
	}

	/* deletes a proposal
	   -- users: only if not yet filed
	   -- admins: always
	   -- section-heads: never
	*/
	public function delete ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->redirect('/users/home');
		}
		
		$this->Access->check($this->Proposal, $proposal_id,
				     array('flash' => 'Zugriff verweigert', 'redirect' => '/users/home',
				     	   'mode' => 'delete'));

		$this->Proposal->delete($proposal_id, true);
		$this->revisit(array('controller' => 'users', 'action' => 'home'));
	}
	
	//access controlled via controllers/Proposals/grantEditRights all
	public function grantEditRights ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->revisit('/users/home');
		}
		
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
		if ($owner_id == null) $this->revisit('/users/home');
		
		$this->Access->grant($this->Proposal, $proposal_id, $owner_id, 'update');
				
		$this->Proposal->id = $proposal_id;
		$this->Proposal->Applicant->id = $this->Proposal->field('applicant_id');
		
		$email = $this->Proposal->Applicant->field('email');
		$subject = 'Proposal ' . $this->Proposal->field('title') . ' ist wieder editierbar!';
		$name = $this->Proposal->Applicant->field('name');
		$last_name = $this->Proposal->Applicant->field('last_name');
		$message = 'Das Proposal "' . $this->Proposal->field('title') . '" ist nun tempor&auml;r f&uuml;r Sie wieder editierbar!<br/>';

		$this->Message->send($name + ' ' + $last_name, $email, $subject, $message);
		
		$this->Session->setFlash('Das Proposal kann nun wieder editiert werden.');
		$this->revisit('/users/home');
	}
	
	//access controlled via controllers/Proposals/revokeEditRights all
	public function revokeEditRights ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->revisit('/users/home');
		}
		
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
		if ($owner_id == null) $this->revisit('/users/home');
		
		$this->Access->revoke($this->Proposal, $proposal_id, $owner_id, 'update');
		
		$this->Session->setFlash('Das Proposal ist für die weitere Bearbeitung gesperrt.');
		$this->revisit('/users/home');
	}
	
	/* approves a proposal & changes the proposals permissions,
	 * -- so that section heads can no longer approve/disprove it
	 * -- useful for proposals where admins have to decide that they are OK.
	 * -- access only for admins (via controllers/Proposals/lock)
	 */
	public function lock ($proposal_id = null) {
		$this->setPermissionsForLockedProposal($proposal_id);
		$this->Session->setFlash('Das Akzeptieren/Ablehnen des Proposals wurde für Bereichsverantwortliche gesperrt.');
		$this->revisit('/users/home');
	}
	
	/* same for unlock */
	public function unlock ($proposal_id = null) {
		$this->setPermissionsForUnlockedProposal($proposal_id);
		$this->Session->setFlash('Das Proposal darf von Bereichsverantwortlichen wieder angenomen/abgelehnt werden.');
		$this->revisit('/users/home');
	}
	
	/* grab takes a proposal, opens a semester editor and files it
	 * it does that without checking for contingents, permissions, etc.
	 * access: admins only (controlled via controllers/Proposals/grab
	 * used in the extreme circumstance when an admin wants to manually 
	 * include a proposal in an otherwise already closed semester
	 */
	public function grab ($proposal_id = null) {
		if (!isset($proposal_id)) {
			$this->revisit('/users/home');
		}
		
		$this->Proposal->id = $proposal_id;
		if (!$this->Proposal->exists()) {
			$this->Session->setFlash('Nicht existentes Proposal ausgewählt!');
		}
		
		if (empty($this->data['semester_id']) || empty($this->data['section_id'])
			|| empty($this->data['course_type_id'])) {
						
			$semesters = $this->Semester->find('all', array('recursive' => -1));
			$sections = $this->Section->find('all', array('recursive' => -1));
			$course_types = $this->CourseType->find('all', array('recursive' => -1));
			
			$this->set('proposal_id', $proposal_id);
			$this->set('semesters', $semesters);
			$this->set('sections', $sections);
			$this->set('course_types', $course_types);
			
		} else {
			$semester_id = $this->data['semester_id'];
			$section_id = $this->data['section_id'];
			$course_type_id = $this->data['course_type_id'];
			
			if ($this->Semester->find('count', array('conditions' => 
				array('Semester.id' => $semester_id))) == 0) {
				$this->Session->setFlash('Nicht existentes Semester ausgewählt!');
				$this->revisit('/users/home');
			}
			
			$this->Proposal->saveField('filed_for', $semester_id);
			$this->Proposal->saveField('section_id', $section_id);
			$this->Proposal->saveField('course_type_id', $course_type_id);
			$this->setPermissionsForFiledProposal($proposal_id);
			
			$this->Session->setFlash('Proposal wurde manuell eingereicht!');
			$this->revisit('/users/home');
		}
	}
}

?>



