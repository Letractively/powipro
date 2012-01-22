<h1>Kurstyp verwalten</h1>
<p>Verwalten Sie auf dieser Seite einen Kurstyp.<br/>
Das Kommentarfeld dient Bewerber*innen dazu, beim Einreichen eines Proposals den richtigen Kurstypen
f&uuml;r ihr Proposal auszuw&auml;hlen.
</p>
<?php 
	echo $this->Html->script('ckeditor/ckeditor.js');

	echo $this->Form->create('CourseType', array('url' => array('controller' => 'course_types', 
								   'action' => 'edit', $coursetype['CourseType']['id'])));
	
	echo $this->Form->inputs(array('legend' => 'Kurstyp bearbeiten',
		'abbreviation' => array('label' => 'Abkürzung (SE, PS, VO+UE, ...)', 'value' => $coursetype['CourseType']['abbreviation']),
		'ECTS' => array('label' => 'ECTS', 'value' => $coursetype['CourseType']['ECTS']),
		'hours' => array('label' => 'Semesterstunden', 'value' => $coursetype['CourseType']['hours']),
		'type' => array('label' => 'LV-Typ (ausgeschrieben: Seminar, Proseminar, ...)', 'value' => $coursetype['CourseType']['type']),
		'description' => array('label' => 'Einzeilige Beschreibung (Bachelor Lektürekurs, Master FoP, ...)', 'value' => $coursetype['CourseType']['description'], 'size' => 50),
		'comment' => array('label' => 'Kommentar (als Orientierung für die Bewerber*innen)', 'value' => $coursetype['CourseType']['comment']),
	));
	echo $this->Form->end('Speichern', true);

	echo $this->Html->scriptBlock("CKEDITOR.replace('CourseTypeComment', { extraPlugins : 'bbcode' });");
?>