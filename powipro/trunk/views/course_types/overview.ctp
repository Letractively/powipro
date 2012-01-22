<h1>Liste aller verfügbaren Kurstypen</h1>
<p><?php
	if ($permission['create']) {
		echo $this->Html->link('Neuen Kurstyp erstellen', array('controller' => 'course_types',
			'action' => 'edit'));
	}
?></p>

<center><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 
	$headers = array($this->Paginator->sort('Typ', 'CourseType.type'),
		$this->Paginator->sort('Beschreibung', 'CourseType.description'),
		$this->Paginator->sort('ECTS', 'CourseType.ECTS'),
		$this->Paginator->sort('SStd.', 'CourseType.hours'),
	 'Angeboten im Semester', 'Aktionen');
	echo $this->Html->tableHeaders($headers);
	
	
	foreach ($coursetypes as $coursetype) {
		$cells = array($coursetype['CourseType']['type'],
					   $this->Html->link($coursetype['CourseType']['description'],
					   	array('controller' => 'course_types', 'action' => 'view', $coursetype['CourseType']['id'])),
					   $coursetype['CourseType']['ECTS'],
					   $coursetype['CourseType']['hours']);

		if (!empty($coursetype['Contingent'])) {
			$semesters = "";
			foreach ($coursetype['Contingent'] as $contingent) {
				if ($permission['update']) {
					$semesters .= $this->Html->link(
						$contingent['Semester']['name'] . '/' . $contingent['Section']['abbreviation'],
					array('controller' => 'semesters', 'action' => 'view', $contingent['Semester']['id']));	
				} else {
					$semesters .= $contingent['Semester']['name'];
				}
				$semesters .= '<br/>';
			}
		} else {
			$semesters = 'Keine';
		}
		 
		$cells[] = $semesters;
		
		$actions = $this->Html->link('Details', array('controller' => 'course_types',
			'action' => 'view', $coursetype['CourseType']['id']));
		
		if ($permission['update']) {
			$actions .= ' - ' . $this->Html->link('Ändern', array('controller' => 'course_types',
				'action' => 'edit', $coursetype['CourseType']['id']));
		}
		
		if ($permission['delete']) {
			$actions .= ' - ' . $this->Html->link('Löschen', array('controller' => 'course_types',
				'action' => 'delete', $coursetype['CourseType']['id']));
		}
		
		$cells[] = $actions;
		
		echo $this->Html->tableCells($cells);
	}
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
