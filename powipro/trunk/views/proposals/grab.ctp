<h2>Proposal manuell einreichen</h2>
<p>Wenn Sie sicher sind, dass sie das Proposal manuell einreichen wollen, dann
wählen Sie ein Semester. Sie können jedes Semester auswählen - das Proposal
können Sie dann im jeweiligen Semester finden und dann bearbeiten, um etwa den Bereich, den Kurstyp
 oder das Semester im nachhinein zu ändern.</p>

<?php 
	$semester_options = array();
	foreach ($semesters as $semester) {
		$semester_options[$semester['Semester']['id']] = $semester['Semester']['name'];
	}
	$section_options = array();
	foreach ($sections as $section) {
		$section_options[$section['Section']['id']] = $section['Section']['abbreviation'] 
			. ': ' . $section['Section']['name'];
	}
	$course_type_options = array();
	foreach ($course_types as $course_type) {
		$course_type_options[$course_type['CourseType']['id']] = 
			$course_type['CourseType']['abbreviation'] . ': '
			. $course_type['CourseType']['description'];
	}
	
	echo $this->Form->create(false, array('url' => array(
		'controller' => 'proposals', 'action' => 'grab', $proposal_id)));
	echo $this->Form->select('semester_id', $semester_options, null, array('empty' => false));
	echo $this->Form->select('section_id', $section_options, null, array('empty' => false));
	echo $this->Form->select('course_type_id', $course_type_options, null, array('empty' => false));
	echo $this->Form->end('Manuell einreichen');
	
	echo $this->Html->link('Zurück zur Rechteverwaltung', array('controller' => 'users', 'action' => 'overview'));
?>