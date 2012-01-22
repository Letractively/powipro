<?php 

class ContingentsController extends AppController {
	public $name = 'Contingents';
	
	public function delete ($contingent_id = null) {
		if ($contingent_id == null) {
			$this->revisit('/semesters/overview');
		} 
		
		$this->Contingent->id = $contingent_id;
		$semester_id = $this->Contingent->field('semester_id');
		
		$this->Access->check($this->Contingent->Semester, $semester_id,
			array('flash' => 'Dieses Semester dürfen Sie nicht sehen!',
				  'redirect' => '/semesters/overview',
				  'mode' => 'update'));
		
		$this->Contingent->delete();
		
		$this->redirect('/semesters/edit/' . $semester_id);
	}
	
	/* increases a given contingent +1 
	 * -- only admins (via controllers/Contingents/increase
	 */
	public function increase ($contingent_id = null) {
		if (isset($contingent_id)) {
			$this->Contingent->id = $contingent_id;
			if ($this->Contingent->exists()) {
				$this->Contingent->saveField('contingent', $this->Contingent->field('contingent')+1);
			}
		}
		
		$this->revisit('/users/home');
	}
	
	/* decreases a given contingent -1
	 * -- only admins
	 */
	public function decrease ($contingent_id = null) {
		if (isset($contingent_id)) {
			$this->Contingent->id = $contingent_id;
			
			if ($this->Contingent->exists()) {
				$contingent = $this->Contingent->field('contingent');
				$booked = $this->Contingent->field('booked');
				
				if ($contingent == 1) {
					$this->Session->setFlash('Sie k&ouml;nnen das Kontingent nicht auf null verringern!');
					
				} else if ($contingent > $booked) {
					$this->Contingent->saveField('contingent', $contingent - 1);
					
				} else {
					$this->Session->setFlash('Kontingent nicht verringert, weil es bereits ' . $booked . ' Proposals angenommen wurden.');
				}
			}
		}
		
		$this->revisit('/users/home');
	}
}

?>