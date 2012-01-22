<?php
class Section extends AppModel {
	public $name = 'Section';
	
	public $order = 'Section.abbreviation';
	
	public $hasMany = array(
		'Proposal' => array(
			'className' => 'Proposal',
			'conditions' => array('Proposal.section_id' => 'Section.id')
		),
		'Contingent' => array(
			'className' => 'Contingent',
		),
	);
	
	public $validate = array(
	'abbreviation' => array(
		'rule' => array('notEmpty'),
		'message' => 'Abkürzung angeben (z.B. BAK8, BA12, M3b, ...)',
		'required' => true,
		'allowEmpty' => false),
	'name' => array(
		'rule' => array('minLength', 8),
		'message' => 'Der Name ist zu kurz',
		'required' => true,
		'allowEmpty' => false),
	'description' => array(
		'rule' => array('minLength', 10),
		'message' => 'Die Beschreibung muss länger sein',
		'required' => true,
		'allowEmpty' => false),
	'comment' => array(
		'rule' => array('minLength', 50),
		'message' => 'Das Kommentar muss länger sein, sonst kennt sich niemand aus!',
		'required' => true,
		'allowEmpty' => false),
	);
	
	/* find all sections, which have a contingent for all available semesters */	
	public function getAvailableSections () {
		return $this->Contingent->find('all',
				array('conditions' => array('Semester.start <= CURRENT_DATE()',
							    'Semester.final_deadline >= CURRENT_DATE()',
							    'Semester.published' => '<> 0',
							    'Contingent.contingent > 0'),
				      'fields' => array('DISTINCT Section.id', 'Section.abbreviation', 'Section.name', 'Section.description'),
				      'limit' => 40,	      		
		      		)
		);
	}
	
	public function isAvailable ($section_id = null) {
		if (!$this->valid($section_id)) return false;	
		return $this->exists();
	}
}
