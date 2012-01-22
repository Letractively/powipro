<h1>Eingereichte Proposals verschieben</h1>

<p>Wenn Sie dieses Proposal tats&auml;chlich verschieben wollen,
sollten Sie dies mit dem/der Bewerber/in (Semesterverschiebung) oder dem/der Bereichsverantwortlichen
des betreffenden Bereiches abkl&auml;ren!</p>

<h3>Bewerber/in</h3>
<p>Name: <?php echo $applicant['Applicant']['name'] . ' '. $applicant['Applicant']['last_name']; ?></p>
<p>E-Mail: <?php echo $this->Html->link('schreiben', 'mailto:' . $applicant['Applicant']['email']); ?></p>
<p>Telefon: <?php echo $applicant['Applicant']['phone']; ?></p>
<p>Mobil: <?php echo $applicant['Applicant']['mobile']; ?></p>

<h3>Proposal verschieben</h3>
<p>Die voreingestellten Werte sind die aktuellen Einstellungen des Proposals.<br/>
Wenn Sie z.B. nur den Bereich ändern wollen, wählen Sie nur hierfür einen neuen aus der Liste.<br/>
</p>
<p>
Überprüfen Sie auch, ob die gewünschte Semester-Bereich-Kurstyp-Kombination überhaupt ein
Kontingent erhalten hat. Gehen Sie dazu zur 
<?php echo $this->Html->link('Semesterübersicht', array('controller' => 'semesters', 'action' => 'overview')); ?>
<br/>
</p>
<?php 
	
	echo $this->Form->create(false, array('url' => '/proposals/move/' . $proposal['Proposal']['id']));
	
	$semester_options = array();
	foreach ($semesters as $semester) {
		$semester_options[$semester['Semester']['id']] = $semester['Semester']['name'];
	}
	
	$section_options = array();
	foreach ($sections as $section) {
		$section_options[$section['Section']['id']] = $section['Section']['abbreviation'] . ': '
			. $section['Section']['name'];
	}
	
	$course_type_options = array();
	foreach ($course_types as $course_type) {
		$course_type_options[$course_type['CourseType']['id']] = $course_type['CourseType']['abbreviation']
		 . ': ' . $course_type['CourseType']['description'] . '('
		 . $course_type['CourseType']['ECTS'] . ' ECTS, ' 
		 . $course_type['CourseType']['hours'] . ' SStd.)';
	}
	
	echo '<p>Neues Semester:<br/>' . $this->Form->select('semester_id', $semester_options, $proposal['Proposal']['filed_for'], array('empty' => false)) . '</p>'
	. '<p>Neuer Bereich:<br/>' . $this->Form->select('section_id', $section_options, $proposal['Proposal']['section_id'], array('empty' => false)) . '</p>'
	. '<p>Neuer Kurstyp:<br/>' . $this->Form->select('course_type_id', $course_type_options, $proposal['Proposal']['course_type_id'], array('empty' => false))
	. '</p>';
	
	echo $this->Form->end('Verschieben');
?>
</p><p>
<?php
	if (isset($back_link))
		echo $this->Html->link('Zurück', $back_link);
	else {
		echo $this->Html->link('Zurück zur Proposalübersicht', '/proposals/overview/' . $proposal['Proposal']['filed_for'])
		. '<br/>' . $this->Html->link('Zurück zur Startseite', '/users/home');
	} 
?>
</p>