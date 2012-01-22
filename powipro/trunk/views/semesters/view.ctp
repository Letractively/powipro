<h1><?php echo $semester['name']; ?></h1>

<?php
	if (!$semester['published']) {
		echo '<h2>Dieses Semester ist noch nicht veröffentlicht.</h2>'
		. '<p>Bitte bearbeiten Sie dieses Semester und kontrollieren die Angaben auf Richtigkeit, bevor Sie es veröffentlichen.</p>'
		. '<p>Solange dieses Semester nicht veröffentlicht ist, können keine Proposals dafür eingereicht werden,'
		. ' auch wenn die Startfrist dafür bereits in der Vergangenheit liegt.</p>'
		. '<p>Nur AdministratorInnen können dieses Semester sehen, bearbeiten oder veröffentlichen.</p><p>'
		. $this->Html->link('Veröffentlichen', '/semesters/publish/' . $semester['id'],
					array(), 'Wollen Sie das Semester wirklich veröffentlichen?' .
					' Falsche und unvollständige Angaben können danach nicht mehr korrigiert werden!')
		. '</p>';
	}
	
	echo '<h3>Fristen</h3>';

	echo '<p>Startdatum: ' . strftime('%e.%m.%Y', strtotime($semester['start'])) . '<br />'
	. 'Ab diesem Datum dürfen Proposals eingereicht werden.</p>';

	echo '<p>Deadline: ' .strftime('%e.%m.%Y', strtotime($semester['deadline'])) . '<br />'
	. 'Bis zu diesem Datum (inklusive) dürfen Proposals eingereicht werden.</p>';

	if ($permission['delete']) {
		echo '<p>Letzte Möglichkeit: ' . strftime('%e.%m.%Y', strtotime($semester['final_deadline'])) . '<br />'
		. 'Bis zu diesem Datum (inklusive) dürfen Proposals nachgereicht werden. Diese Frist ist nur für Admins sichtbar.</p>';
	}

?>
<h3>Hinweise</h3><p>
Im Folgenden sehen Sie eine Liste jener Bereiche, für die in diesem Semester Proposals eingereicht werden dürfen.
Für jeden Bereich gibt es dabei eine Auswahl an Kurstypen und ein bestimmtes Kontingent an Lehrveranstaltungen.
Es d&uuml;rfen dabei von Bereichsverantwortlichen nicht mehr Proposals angenommen werden, als Kontingent vorhanden ist.<br/>

<?php
	if (!$semester['published']) {
		?>
		<strong>Kontrollieren Sie diese Liste auf Richtigkeit, bevor Sie das Semester freigeben.</strong><br />
		Sollten Sie Fehler finden, so können Sie dieses Semester
		<?php echo $this->Html->link('bearbeiten', '/semesters/edit/' . $semester['id']); ?>
		und die nötigen Korrekturen vornehmen.<br />
		<?php
	} else if ($permission['delete']) {
		?>
		<strong>Sie k&ouml;nnen diese Liste nicht mehr &auml;ndern, da das Semester bereits ver&ouml;ffentlicht ist.</strong><br/>
		Sie k&ouml;nnen allerdings ein neues Semester mit den richtigen Daten anlegen und danach dieses
		<?php echo $this->Html->link('löschen', '/semesters/delete/' . $semester['id']); ?>.<br />
		Die bis dahin bereits eingereichten Proposals m&uuml;ssen dann allerdings f&uuml;r das neue Semester auch erneut
		eingereicht werden!
		<?php
	}
?>
</p>
<h3>Kontingente</h3>
<center>
<div class="paging"><?php echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
		 . $this->Paginator->numbers() . '&nbsp;'
		 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php
	echo '<thead>' . $this->Html->tableHeaders(array(
		$this->Paginator->sort('Studienplanpunkt', 'Section.abbreviation'),
		$this->Paginator->sort('Kurstyp', 'CourseType.description'),
		$this->Paginator->sort('Vergeben / Gesamt', 'Contingent.booked')))
	 . '</thead>';
	echo '<tbody id="contingents_table">';
	foreach ($contingents as $contingent) {
		$contingent_cell = $contingent['Contingent']['booked'] . ' von ' . $contingent['Contingent']['contingent']
			. ' Plätzen vergeben';
		
		if ($permission['delete']) {
			$contingent_cell .= ' ' . $this->Html->link('+', 
				array('controller' => 'contingents', 'action' => 'increase', $contingent['Contingent']['id']))
			. '/' . $this->Html->link('-',
				array('controller' => 'contingents', 'action' => 'decrease', $contingent['Contingent']['id']));
		}
		
		echo $this->Html->tableCells(array(
			$this->Html->link($contingent['Section']['abbreviation'] . ' - ' . $contingent['Section']['name'], 
				array('controller' => 'sections', 'action' => 'view', $contingent['Section']['id'])),
			
			$this->Html->link($contingent['CourseType']['abbreviation'] . ' - ' . $contingent['CourseType']['description'],
				array('controller' => 'course_types', 'action' => 'view', $contingent['CourseType']['id'])),
				
			$contingent_cell,
		));
	}
	echo '</tbody>';

?>
</table>
<center><p><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></p>
<p>Sie befinden sich auf
<?php echo $this->Paginator->counter(array('format' => 'Seite %page% von %pages%')); ?>
</p></center>