<h1>Semester verwalten</h1>
<small>
<?php echo $this->Html->link('Zurück zur Semesterübersicht', array('controller' => 'semesters', 'action' => 'overview')); ?>
</small>

<?php

	echo $this->Form->create('Semester', array('url' => array('controller' => 'semesters', 'action' => 'edit', $semester['Semester']['id'])));

	echo $this->Form->input('Semester.name', array('value' => $semester['Semester']['name'],
							'label' => 'Name'));
	echo $this->DatePicker->picker('Semester.start', array('value' => $semester['Semester']['start'],
								'label' => 'Start der Einreichfrist'));
	echo $this->DatePicker->picker('Semester.deadline', array('value' => $semester['Semester']['deadline'],
								'label' => 'Ende der Einreichfrist'));
	echo $this->DatePicker->picker('Semester.final_deadline', array('value' => $semester['Semester']['final_deadline'],
								'label' => 'Ende der Nachfrist'));
	
	
	if (!empty($all_semesters)) {
		$all_semesters_options = array();
		foreach ($all_semesters as $each_semester) {
			$all_semesters_options[$each_semester['Semester']['id']] = $each_semester['Semester']['name'];
		}
		echo '<h3>Kontingente übernehmen</h3><p>Zusätzlich alle Kontingente aus dem '
		. $this->Form->select('copy_id', $all_semesters_options, null, array('empty' => true, 'legend' => 'Kontingente übernehmen aus dem'))
		. ' übernehmen.<br/>Übernommene Kontingente auf 0 setzen?<span>'
		. $this->Form->Checkbox('set_copy_null')
		. '</span></p>';


	}
	$i = 0;
	foreach ($contingents as $contingent) {
		
		echo '<h4>Kontingent für ' . $contingent['Section']['name'] . ' für ' . $contingent['CourseType']['description'] . '</h4>';
		
		echo $this->Form->input('Contingent.' . $i . '.contingent', array('value' => $contingent['Contingent']['contingent'], 'type' => 'text', 'label' => 'Geplante Kursanzahl'));
		echo $this->Form->hidden('Contingent.' . $i . '.id', array('value' => $contingent['Contingent']['id']));		
		echo $this->Html->link('Löschen', array('controller' => 'contingents', 'action' => 'delete', $contingent['Contingent']['id']));		
		
		echo '</p>';
		
		$i++;
	}
	
	
	echo '<h4>Kontingent hinzuf&uuml;gen</h4><p>Leer lassen, falls Sie keine neuen Kontingente hinzufügen wollen<br/>';
	echo $this->Form->select('Contingent.' . $i . '.section_id', $section_options, null, array('empty' => true, 'label' => 'Bereich'));
	echo $this->Form->select('Contingent.' . $i . '.course_type_id', $coursetype_options, null, array('empty' => true, 'label' => 'Kurstyp'));
	echo $this->Form->input('Contingent.' . $i . '.contingent', array('type' => 'text', 'label' => 'Geplante Kursanzahl'));
	echo $this->Form->hidden('Contingent.' . $i . '.semester_id', array('value' => $semester['Semester']['id']));
	echo '</p>';
	
	
	echo $this->Form->submit('Speichern');
	echo $this->Form->end();

	echo $this->Html->link('Zurück zur Semesterübersicht', array('controller' => 'semesters', 'action' => 'overview'));
?>

