<?php
class Coapplicant extends AppModel {
	var $name = 'Coapplicant';

	public $belongsTo = array(
		'Applicant' => array(
			'className' => 'Applicant',
			'foreignKey' => 'applicant_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Proposal' => array(
			'className' => 'Proposal',
			'foreignKey' => 'proposal_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',)
	);
	
	var $actsAs = array(
		'Containable'
	);
	

}
?>