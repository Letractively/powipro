<h1><?php echo $coursetype['CourseType']['abbreviation'] . ' - ' . $coursetype['CourseType']['description']; ?></h1> 
<h3>Daten</h3>
<p>Typ: <?php echo $coursetype['CourseType']['type']?><br/>
ECTS: <?php echo $coursetype['CourseType']['ECTS'] ?><br/>
Semesterstunden: <?php echo $coursetype['CourseType']['hours'] ?></p>
<h4>Beschreibung</h4>
<p><?php echo $this->Bbcode->parse($coursetype['CourseType']['comment']); ?></p>

<?php
	if ($permission['update']) {
		echo '<h3>Aktionen</h3>';
 		echo '<p>' . $this->Html->link('Kurstyp bearbeiten', array('controller' => 'course_types',
			'action' => 'edit', $coursetype['CourseType']['id'])) . '</p>';
	}
?>

<?php if ($permission['read']) { ?>
<h3>Kontingente</h3>
<?php foreach ($coursetype['Contingent'] as $contingent) { ?>
	
  <h4><?php echo $contingent['Semester']['name'] ?></h4><p>
  	<?php echo $contingent['contingent'] ?> Kurse geplant im Bereich
  	<?php echo $contingent['Section']['abbreviation'] . ' - ' . $contingent['Section']['name'] ?>.<br/>
  	<?php echo $this->Html->link('Semester bearbeiten', array('controller' => 'semesters',
  		'action' => 'edit', $contingent['Semester']['id'])) . '<br/>'; ?>
  	</p>  

<?php } ?>
<?php } ?>