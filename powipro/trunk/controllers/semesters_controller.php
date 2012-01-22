<?php

/* Permissions:
 * The parentNode Semesters sets the basic permissions: if a user could ever view/summary a semester,
 * then the permission would have to be granted. Individual semesters, however, may be excluded
 * Thus: a user with only summary permissions could in principle see an empty list on the "overview" page
 * 
 * summary: see name, start-date and end-date, summary-page (summary)
 * read: see semester details (late-end-date), view-detail page (view)
 * update: change semester stats (admins or noone)
 * delete: delete semesters OR close semesters (admins)
 * approve: see proposals (admins/shs or noone)
 * create: create semesters (admins)
 * 
 * permission changes:
 * -- create: +CRUDS-A for admins, -RAS for sh, -S for users
 * -- publish: -U+A for admins, +RAS for shs, +S for users
 * -- close: -A for shs
 * -- reopen: +A for shs
 * -- edit: no changes
 * -- delete: node gets deleted
 * -- summary: no changes
 * -- view: no changes
 * -- overview: no changes
 *
 * 
 * parentNode-permissions:
 * - admins: +CRUDAS
 * - shs: +RAS
 * - users: +S
 */

class SemestersController extends AppController {
	public $name = 'Semesters';
	public $uses = array('Semester', 'Proposal');
	public $components = array('Message');
	public $helpers = array('DatePicker');
	
	public $paginate = array(
		'Contingent' => array(
			'contain' => array('Section', 'CourseType'),
			'limit' => 20,
			'order' => 'Section.abbreviation'),
		'Semester' => array(
			'contain' => array(),
			'limit' => 10,
			'order' => 'Semester.start'),
	);
	
	//Permission changers
	private function setPermissionsForNewSemester ($semester_id) {
		$this->Semester->id = $semester_id;
		$aco = $this->Semester->node();
		
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
						  $aco[0]['Aco'], 'approve');
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						  $aco[0]['Aco'], 'approve');
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						  $aco[0]['Aco'], 'read');
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						  $aco[0]['Aco'], 'summary');
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
						  $aco[0]['Aco'], 'summary');
	}
	
	private function setPermissionsForPublishedSemester ($semester_id) {
		$this->Semester->id = $semester_id;
		$aco = @$this->Semester->node();
		
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
						  $aco[0]['Aco'], 'update');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
						   $aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						   $aco[0]['Aco'], 'approve');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						   $aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
						   $aco[0]['Aco'], 'summary');
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
						   $aco[0]['Aco'], 'summary');
	}
	
	private function setPermissionsForClosedSemester ($semester_id) {
		$this->Semester->id = $semester_id;
		$aco = @$this->Semester->node();
		
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
			$aco[0]['Aco'], 'approve');
	}
	
	private function setPermissionsForReopenedSemester ($semester_id) {
		$this->Semester->id = $semester_id;
		$aco = @$this->Semester->node();
		
		$this->Acl->allow(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
			$aco[0]['Aco'], 'approve');
	}
	
	private function setPermissionsForUnfiledProposal ($proposal_id) {
		$this->Proposal->id = $proposal_id;
		$aco = $this->Proposal->node();
		$owner_id = $this->Proposal->getOwnerID($proposal_id);
	
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Administrators']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['SectionHeads']),
		$aco[0]['Aco']);
		$this->Acl->deny(array('model' => 'Group', 'foreign_key' => $this->groups['Users']),
		$aco[0]['Aco']);
		
		if ($owner_id == null) return;
	
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'read');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'update');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'delete');
		$this->Acl->allow(array('model' => 'User', 'foreign_key' => $owner_id),
		$aco[0]['Aco'], 'summary');
	}
	
	/* send notifications to all applicants
	 * -- only admins (via controllers/Semesters/notify
	 * -- only closed semesters
	 * -- only if Semester.notified = 0
	 */	
	public function notify ($semester_id = null) {
		$this->Semester->id = $semester_id;

		if (!$this->Semester->exists()) {
			$this->revisit('/users/home');
		}

		if ($this->Semester->field('closed') == 0) {
			$this->Session->setFlash('Das Semester ist noch nicht geschlossen!');
			$this->revisit('/users/home');
		}
					
		if (empty($this->data)) {
			
			$message_count = $this->Proposal->find('count', array('conditions' => array(
				'Proposal.filed_for' => $semester_id,
				'Proposal.notified' => 0,
			)));
			
			$this->set('message_count', $message_count);
			$this->set('sent', false);
			$this->set('semester_id', $semester_id);
			
		} else {
			$accepted_text = $this->data['accepted'];
			$refused_text = $this->data['refused'];
			$attachment_files = array();
			
			$val = $this->data['attachment'];
			if ((isset($val['error']) && $val['error'] == 0) ||
				(!empty( $val['tmp_name']) && $val['tmp_name'] != 'none'))
			{	
				$tmp_name = $this->data['attachment']['tmp_name'];
				$name = $this->data['attachment']['name'];
				if (is_uploaded_file($tmp_name)) {
					if (move_uploaded_file($tmp_name, '/tmp/' . $name)) {
						$attachment_files[] = '/tmp/' . $name;
					}
				}
			}

			$this->Proposal->contain(array('Applicant', 'SelectedSemester'));
			$selected_proposals = $this->Proposal->find('all', array('conditions' => array(
				'Proposal.selected_for' => $semester_id,
				'Proposal.notified' => 0,
			)));

			$this->Proposal->contain('Applicant');
			$other_proposals = $this->Proposal->find('all', array('conditions' => array(
				'Proposal.filed_for' => $semester_id,
				'Proposal.selected_for' => null,
				'Proposal.notified' => 0, 
			)));

			$count_selected = 0;
			$count_sent_selected = 0;
			$selected_not_sent = array();
			foreach ($selected_proposals as $proposal) {
				$email = $proposal['Applicant']['email'];
				$name = $proposal['Applicant']['name'] . ' ' . $proposal['Applicant']['last_name'];
					
				$message = $accepted_text;
					
				$replacements = array(
					'%PROPOSAL%' => $proposal['Proposal']['title'],
					'%SEMESTER%' => $proposal['SelectedSemester']['name'],
					'%VORNAME%' => $proposal['Applicant']['name'],
					'%NACHNAME%' => $proposal['Applicant']['last_name'],
					'%TITEL%' => $proposal['Applicant']['title'],
					'%AKADEMISCHE_GRADE%' => $proposal['Applicant']['degrees']);
				
					
				if ($this->Message->send($name, $email, 'Proposal wurde akzeptiert', $message, $replacements, $attachment_files)) {
					$count_sent_selected++;
					$this->Proposal->id = $proposal['Proposal']['id'];
					$this->Proposal->saveField('notified', 1);
					
				} else {
					$selected_not_sent[] = array('name' => $name,
					'email' => $email,
					'proposal' => $proposal);
				}
				$count_selected++;
			}

			$count_other = 0;
			$count_sent_other = 0;
			$other_not_sent = array();
			foreach ($other_proposals as $proposal) {
				$email = $this->Proposal->Applicant->decrypted($proposal['Applicant']['email']);
				$name = $proposal['Applicant']['name'] . ' ' . $proposal['Applicant']['last_name'];
					
				$message = $refused_text;
					
				$replacements = array(
					'%PROPOSAL%' => $proposal['Proposal']['title'],
					'%VORNAME%' => $proposal['Applicant']['name'],
					'%NACHNAME%' => $proposal['Applicant']['last_name'],
					'%TITEL%' => $proposal['Applicant']['title'],
					'%AKADEMISCHE_GRADE%' => $proposal['Applicant']['degrees']);
					
				if ($this->Message->send($name, $email, 'Proposal wurde leider abgelehnt', $message, $replacements)) {
					$count_sent_other++;
					$this->Proposal->id = $proposal['Proposal']['id'];
					$this->Proposal->saveField('notified', 1);
					
				} else {
					$other_not_sent[] = array('name' => $name,
					'email' => $email,
					'proposal' => $proposal);
				}
				$count_other++;
			}

			$this->set('count_selected', $count_selected);
			$this->set('count_sent_selected', $count_sent_selected);
			$this->set('selected_not_sent', $selected_not_sent);
			$this->set('count_other', $count_other);
			$this->set('count_sent_other', $count_sent_other);
			$this->set('other_not_sent', $other_not_sent);
			$this->set('sent', true);
		}
	}

	/* summary-page for a single semester
	   -- access for everyone
	   -- gives an overview over the selected semester stats
	   --- deadlines, proposal count
	*/
	public function summary ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/users/home');
		}
		
		$this->Access->check($this->Semester, $semester_id,
			array('flash' => 'Dieses Semester dürfen Sie nicht sehen!',
				  'redirect' => '/semesters/overview',
				  'mode' => 'summary'));
		
		$this->Semester->Behaviors->attach('Containable');
		$this->Semester->contain(array('Contingent' => array('Section', 'CourseType'),
				'FiledProposal.id', 'SelectedProposal.id'));
		$semester = $this->Semester->find('first', array('conditions' => array('Semester.id' => $semester_id)));
		$filed_count = count($semester['FiledProposal']);
		$selected_count = count($semester['SelectedProposal']);

		$this->set('semester', $semester);
		$this->set('filed', $filed_count);
		$this->set('selected', $selected_count);
		$this->set('will_open', $this->Semester->willOpen($semester_id));
		$this->set('has_passed', $this->Semester->hasPassed());
		$this->set('open', $this->Semester->isOpen());
		$this->set('late', $this->Semester->isLate());

		$this->visited('/semesters/summary/' . $semester_id);
	}

	/* overview over all semesters
	   -- access for everyone
	   -- central hub
	   --- checks permissions for other functions
	   --- summary, read, update, create, delete, proposals, proposals by sections (=approve)
	*/
	public function overview () {
		//$semesters = $this->Semester->find('all', array('recursive' => -1,
			//											'order' => 'Semester.start DESC'));
		$semesters = $this->paginate('Semester');	
		
		$access = $this->Access->getPermissions();
		if (!$access['summary']) {
			$this->revisit('/users/home');
		}
		
		$permissions = array();
		$status = array();
		foreach ($semesters as $semester) {
			$permissions[$semester['Semester']['id']] = $this->Access->read($this->Semester, $semester['Semester']['id']);
			$status[$semester['Semester']['id']] = $this->Semester->status($semester['Semester']['id']);
		}
		
		$closeable = array();
		foreach ($semesters as $sem) {
			$this->Semester->id = $sem['Semester']['id'];
			$aco = @$this->Semester->node();
			
			$perm = $this->Acl->check(
				array('model' => 'Group',
					  'foreign_key' => $this->groups['SectionHeads']),
				$aco[0]['Aco'], 'approve');
			
			if ($this->Semester->hasPassed($sem['Semester']['id']) && $perm) {
				$closeable[$sem['Semester']['id']] = true;
			} else {
				$closeable[$sem['Semester']['id']] = false;
			}
		}
		
		$this->set('access', $access);
		$this->set('closeable', $closeable);
		$this->set('semesters', $semesters);
		$this->set('permissions', $permissions);
		$this->set('status', $status);
		$this->visited();
	}

	/* extended view for a single semester
	   -- only admins and section heads
	   -- shows detailed information for a selected semester
	   -- secondary hub for other functions
	   --- edit, proposals
	*/
	public function view ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/users/home');
		}
		
		$this->Access->check($this->Semester, $semester_id,
			array('redirect' => '/semesters/overview',
				  'mode' => 'read'));

		$this->Semester->Behaviors->attach('containable');
		$this->Semester->contain(/*array('Contingent' => array('Section', 'CourseType'),*/
								 	   'FiledProposal.id', 'SelectedProposal.id');

		$semester = $this->Semester->find('first', array('conditions' => array('Semester.id' => $semester_id)));
		$permission = $this->Access->read($this->Semester, $semester['Semester']['id']);
		
		$this->loadModel('Contingent');
		$contingents = $this->paginate('Contingent', array('Contingent.semester_id' => $semester_id));

		$this->set('semester', $semester['Semester']);
		$this->set('contingents', $contingents);
		$this->set('permission', $permission);
		
		$this->visited();
	}

	private function saveSemester ($semester_id) {
		$this->data['Semester']['id'] = $semester_id;
		$new_contingent = array_pop($this->data['Contingent']);
		
		$semester = array('Semester' => $this->data['Semester']);
		$this->Semester->set($semester);
		if ($this->Semester->validates()) {
			$this->Semester->save();
			
			if ($semester_id == null) {
				$semester_id = $this->Semester->id;
				$semester['Semester']['id'] = $semester_id;
				$this->setPermissionsForNewSemester($semester_id);
			}
		}
		
		if (!empty($this->data['Semester']['copy_id'])) {
			$copy_id = $this->data['Semester']['copy_id'];
			$set_copy_null = $this->data['Semester']['set_copy_null'];
			$contingents = $this->Semester->Contingent->find('all', array(
				'conditions' => array('Contingent.semester_id' => $copy_id),
				'recursive' => -1,
			));
			
			unset($this->data['Semester']['copy_id']);
			
			for ($i = 0; $i < count($contingents); ++$i) {
				$contingents[$i]['Contingent']['semester_id'] = $semester_id;
				$contingents[$i]['Contingent']['booked'] = 0;
				unset($contingents[$i]['Contingent']['id']);
				
				if ($set_copy_null) {
					$contingents[$i]['Contingent']['contingent'] = 0;
				}
			}
			
			$this->Semester->Contingent->saveAll($contingents);
		}
		
		if (!empty($this->data['Contingent'])) {
			$this->Semester->Contingent->saveAll($this->data['Contingent']);
		}
		
		if ($new_contingent['section_id'] != null &&
		$new_contingent['course_type_id'] != null &&
		$new_contingent['contingent'] > 0) {
			$new_contingent['semester_id'] = $semester_id;
			$new_contingent['id'] = null;
				
			$this->Semester->Contingent->set($new_contingent);
			if ($this->Semester->Contingent->validates()) {
				$this->Semester->Contingent->save();
			}
		}
		
		return $semester;
	}
	
	/* changes semester's stats
	   -- only admins
	*/
	public function edit ($semester_id = null, $new_contingent = null) {
		
		//create a new semester if $semester_id == null and no form data exists
		//save a new semester if $semester_id == null and form data exists
		//save an existing semester if $semester_id != null and form data exists
		//edit an existing semester if $semester_id != null and no form data exists
		//permissions: extra-check for updating
		if ($semester_id != null) {
			$this->Access->check($this->Semester, $semester_id,
				array('flash' => 'Editieren nicht möglich!', 'redirect' => '/semesters/view/' . $semester_id,
					  'mode' => 'update'));
		}
		
		if (!empty($this->data)) {
			$semester = $this->saveSemester($semester_id);
			$semester_id = $semester['Semester']['id'];
			
			if (empty($this->Semester->validationErrors)) {
				$this->Session->setFlash('Semester gespeichert. Vergessen Sie nicht, es zu veröffentlichen!');
				
			} else {
				$this->Session->setFlash('Fehlerhafte Eingabe');
			}
			
		} else {
			if ($semester_id == null) {
				$this->Semester->create(array('id' => null,
											  'name' => 'Name',
											  'start' => null,
											  'deadline' => null,
											  'final_deadline' => null,
											  'published' => false));
				$semester = $this->Semester->data;
				
			} else {
				$this->Semester->read(null, $semester_id);
				$semester = $this->Semester->data;
			}
		}
		
		//load the data for the view		
		$this->Semester->Contingent->contain(array('Section', 'CourseType'));
		
		$contingents = $this->Semester->Contingent->find('all', array(
				'conditions' => array('Contingent.semester_id' => $semester_id)));
				
		$sections = $this->Semester->Contingent->Section->find('all', array('recursive' => -1));
		$coursetypes = $this->Semester->Contingent->CourseType->find('all', array('recursive' => -1));
		
		$section_options = array();
		foreach ($sections as $section) {
			$section_options[$section['Section']['id']] 
				= $section['Section']['abbreviation'] . ': ' . $section['Section']['name'];
		}
		
		$coursetype_options = array();
		foreach ($coursetypes as $coursetype) {
			$coursetype_options[$coursetype['CourseType']['id']]
				= $coursetype['CourseType']['abbreviation'] . ' - '
				. $coursetype['CourseType']['description'] . ' ('
				. $coursetype['CourseType']['ECTS'] . ' ECTS, '
				. $coursetype['CourseType']['hours'] . ' SStd.)';
		}
		
		$all_semesters = $this->Semester->find('all', array('recursive' => -1,
			'conditions' => array('Semester.id <>' => $semester_id)));
		
		$this->set('all_semesters', $all_semesters);
		$this->set('semester', $semester);
		$this->set('contingents', $contingents);
		$this->set('section_options', $section_options);
		$this->set('coursetype_options', $coursetype_options);
		
		$this->visited();
	}
	
	/* sends a message to all applicants of a given semester
	 * permissions: controlled via controllers/Semesters/mail
	 * -- only admins (delete permission) => should be used *before* a semester is deleted.
	 */
	public function mail ($semester_id = null) {
		if (isset($semester_id) && $semester_id != 0) {
			$this->Access->check($this->Semester, $semester_id,
				array('flash' => 'Keine E-Mails erlaubt!', 'redirect' => '/semesters/overview',
					  'mode' => 'delete'));
			
			if (!empty($this->data)) {
				$message = $this->data['message'];
				$subject = $this->data['subject'];
				
				$this->Proposal->contain(array('Applicant', 'FiledSemester.name'));
				$proposals = $this->Proposal->find('all', array('conditions' => array(
					'OR' => array('selected_for' => $semester_id, 
								  'filed_for' => $semester_id,
					),
				)));
				
				foreach ($proposals as $proposal) {
					$name = $proposal['Applicant']['name'] . ' ' . $proposal['Applicant']['last_name'];
					$title = $proposal['Proposal']['title'];
					$semester = $proposal['FiledSemester']['name'];
					$email = $this->Proposal->Applicant->decrypted($proposal['Applicant']['email']);
					
					$replacements = array(
					'%PROPOSAL%' => $proposal['Proposal']['title'],
					'%SEMESTER%' => $proposal['SelectedSemester']['name'],
					'%VORNAME%' => $proposal['Applicant']['name'],
					'%NACHNAME%' => $proposal['Applicant']['last_name'],
					'%TITEL%' => $proposal['Applicant']['title'],
					'%AKADEMISCHE_GRADE%' => $proposal['Applicant']['degrees']);
					$real_subject = str_replace(array_keys($replacements), array_values($replacements), $subject);
					
					echo 'Sende an ' . $email . '<br/>';
					
					$this->Message->send($name, $email, $real_subject, $message, $replacements);
				}
				
				$this->Session->setFlash('Nachricht gesendet!');
				$this->revisit('/users/home');
				
			} else {
				$semester = $this->Semester->find('first', array('conditions' => array('Semester.id' => $semester_id)));
				$this->set('semester', $semester);
			}
		} else {
			$this->Session->setFlash('Kein Semester ausgewählt!');
			$this->revisit('/users/home');
		}
	}

	/* deletes a semester
	   -- only admins
	   -- filed_for-proposals get unfiled
	   -- selected_for-proposals get unselected and unfiled
	   -- redirects to semesters/overview
	*/
	public function delete ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/semesters/overview');
		}
		
		$this->Access->check($this->Semester, $semester_id,
			array('flash' => 'Löschen nicht erlaubt!', 'redirect' => '/semesters/overview',
				  'mode' => 'delete'));
		
		$this->Semester->id = $semester_id;
		
		$this->Proposal->contain();
		$proposals = $this->Proposal->find('all', array(
			'conditions' => array(
				'OR' => array('Proposal.filed_for' => $semester_id,
							  'Proposal.selected_for' => $semester_id),
			),
			'fields' => array('Proposal.id'),
		));
		
		echo print_r($proposals);
	
		foreach ($proposals as $proposal) {
			$this->setPermissionsForUnfiledProposal($proposal['Proposal']['id']);
		}
		
		$this->Proposal->updateAll(array('Proposal.selected_for' => null), 
								   array('Proposal.selected_for' => $semester_id));
		$this->Proposal->updateAll(array('Proposal.filed_for' => null, 'Proposal.notified' => null),
								   array('Proposal.filed_for' => $semester_id));
		
		$this->Session->setFlash('Das Semester wurde gelöscht. Alle Proposals wurden abgewählt.');
		$this->Semester->delete();
		
		$this->revisit('/semesters/overview');
	}

	/* publishes a semester
	   -- only admins
	   -- cannot be undone (except by deletion)
	   -- sets its start-date either to current or selected date
	   -- checks if deadlines are in the future
	   -- after publishing, everyone can file proposals
	   -- redirects to semesters/view
	*/
	public function publish ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/semesters/overview');
		}
		
		$this->Access->check($this->Semester, $semester_id,
			array('flash' => 'Veröffentlichen nicht möglich!', 'redirect' => '/semesters/overview',
				  'mode' => 'update'));
		
		$this->Semester->id = $semester_id;
		$this->Semester->saveField('published', true);
		$this->setPermissionsForPublishedSemester($semester_id);
		$this->revisit('/semesters/overview');
	}
	
	/* closes a semester
	 * -- only admins (controlled via controllers/Semesters/close)
	 * -- prevents section heads from approving/disproving proposals
	 * -- useful to first approve/disprove proposals as admins
	 * --- or to set a deadline for approving/disproving proposals
	 */
	public function close ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/semesters/overview');
		}
		
		$this->Semester->id = $semester_id;
		$this->Semester->saveField('closed', 1);
		$this->setPermissionsForClosedSemester($semester_id);
		$this->Session->setFlash('Bereichsverantwortliche können nun keine Proposals in diesem Semester akzeptieren');
		$this->revisit('/semesters/overview');
	}
	
	/* reopens a semester
	 * -- only admins (controlled via controllers/Semesters/reopen)
	 * -- allows section heads another round of approving/disproving proposals
	 * -- used when a new semester begins and there are (many) changes to do (pregnancy, sickness, ...)
	 * -- only if semester is closed
	 */
	public function reopen ($semester_id = null) {
		if (!isset($semester_id)) {
			$this->revisit('/semesters/overview');
		}
		
		$this->Semester->id = $semester_id;
		
		if ($this->Semester->field('closed') == 0) {
			$this->Session->setFlash('Das Semester ist bereits/noch immer offen.');
			$this->revisit('/semesters/overview');
		}
		
		$this->Semester->id = $semester_id;
		$this->Semester->saveField('closed', 0);
		$this->setPermissionsForReopenedSemester($semester_id);
		$this->Session->setFlash('Bereichsverantwortliche können nun wieder Proposals für dieses Semester akzeptieren');
		$this->revisit('/semesters/overview');
	}
}




?>

