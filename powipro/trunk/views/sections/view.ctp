<h1><?php echo $section['Section']['name'] ?></h1>
<h3>Daten zum Bereich</h3> 
<p>Abkürzung: <?php echo $section['Section']['abbreviation']?><br/>
Name: <?php echo $section['Section']['name'] ?><br/>
Beschreibung: <?php echo $section['Section']['description'] ?></br>
</p>
<h4>Inhalte</h4><p><?php echo $this->Bbcode->parse($section['Section']['comment']); ?></p>
<?php
	if ($permission['update']) {
		echo '<h3>Aktionen</h3>';
 		echo $this->Html->link('Bereich bearbeiten', array('controller' => 'sections',
			'action' => 'edit', $section['Section']['id']));
	}
?>
<p>
<?php if ($permission['read']) { ?>
<h3>Kontingente</h3>
<?php foreach ($section['Contingent'] as $contingent) { ?>
  	<h4><?php echo $contingent['Semester']['name'] ?></h4><p>
  	<?php echo $contingent['contingent'] ?> Kurse vom Typ 
  	<?php echo $contingent['CourseType']['type'] . ' - ' . $contingent['CourseType']['description'] ?>.<br />
  	<?php echo $this->Html->link('Semester bearbeiten', array('controller' => 'semesters',
  		'action' => 'edit', $contingent['Semester']['id'])) . '<br/>'; ?>
  	</p>  

<?php } ?>
<?php } ?>
</p>