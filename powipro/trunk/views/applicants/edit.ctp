<h1>Bewerbungsdaten verwalten</h1>
<?php
	
	echo $this->Html->script('ckeditor/ckeditor.js');
	echo $this->Form->create('Applicant', array('url' => array('controller' => 'applicants', 
								   'action' => 'edit', $applicant['Applicant']['id'])));
	
		
	echo $this->Form->inputs(array('legend' => 'Persönliche Daten',
		'name' => array('label' => 'Vorname', 'value' => $applicant['Applicant']['name'], 'size' => 50),
		'last_name' => array('label' => 'Nachname', 'value' => $applicant['Applicant']['last_name'], 'size' => 50),
		'email' => array('label' => 'E-Mail Adresse', 'value' => $applicant['Applicant']['email'], 'size' => 50),
		'phone' => array('label' => 'Telefon', 'value' => $applicant['Applicant']['phone'], 'size' => 16),
		'mobile' => array('label' => 'Mobile Nummer', 'value' => $applicant['Applicant']['mobile'], 'size' => 16),
		'title' => array('label' => 'Anrede (z.B. Frau, Herr)', 'value' => $applicant['Applicant']['title'], 'size' => 4),
		'degrees' => array('label' => 'Akademische Grade', 'value' => $applicant['Applicant']['degrees'], 'size' => 20),
		'social_security' => array('label' => 'Sozialversicherungsnummer', 'value' => $applicant['Applicant']['social_security'], 'size' => 12),
		'nationality' => array('label' => 'Staatsbürgerschaft', 'value' => $applicant['Applicant']['nationality'], 'size' => 20),
		'address' => array('label' => 'Wohnadresse', 'value' => $applicant['Applicant']['address'], 'cols' => 50, 'rows' => 3),
		'federal_employment' => array('label' => 'Bundesdienstverhältnis?', 'value' => $applicant['Applicant']['federal_employment']),
	));
	
	echo $datePicker->picker('birthday', array('label' => 'Geburtsdatum', 'value' => $applicant['Applicant']['birthday'], 'minYear' => date('Y')-80, 'maxYear' => date('Y')-18));
	
	echo $this->Form->inputs(array('legend' => 'Akademische Daten',
		'publications' => array('label' => 'Veröffentlichungen', 'value' => $applicant['Applicant']['publications']),
		'university_teaching' => array('label' => 'Universitäre Lehrerfahrung', 'value' => $applicant['Applicant']['university_teaching']),
		'general_teaching' => array('label' => 'Außeruniversitäre Lehrerfahrung', 'value' => $applicant['Applicant']['general_teaching']),
	));
	
	echo $this->Form->input('habilitated', array('label' => 'Habilitiert?', 'value' => $applicant['Applicant']['habilitated']));
	echo $datePicker->picker('habilitation_date',
		array('label' => 'Wenn ja, wann?',
			  'value' => $applicant['Applicant']['habilitation_date'], 
			  'minYear' => date('Y')-60, 'maxYear' => date('Y')));
	echo $this->Form->input('habilitation_place', array('label' => 'Wenn ja, wo?', 'value' => $applicant['Applicant']['habilitation_place'], 'size' => 50));
	
	
	echo $this->Html->scriptBlock("CKEDITOR.replace('ApplicantPublications', { extraPlugins : 'bbcode' });");
	echo $this->Html->scriptBlock("CKEDITOR.replace('ApplicantUniversityTeaching', { extraPlugins : 'bbcode' });");
	echo $this->Html->scriptBlock("CKEDITOR.replace('ApplicantGeneralTeaching', { extraPlugins : 'bbcode' });");
	
	echo $this->Form->end('Speichern', true);

	echo '<p>' . $this->Html->link('Zurück zur Homepage...', array('controller' => 'users', 'action' => 'home')) . '<br />'
	   . '</p>';
?>
