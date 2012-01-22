<h1>Liste aller verfügbaren Bereiche</h1>
<p><?php 
	if ($permission['create']) {
		echo $this->Html->link('Neuen Bereich erstellen', array('controller' => 'sections',
			'action' => 'edit'));
	}
?></p>

<center>
<div class="paging"><?php echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
		 . $this->Paginator->numbers() . '&nbsp;'
		 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 
	$headers = array($this->Paginator->sort('Abkürzung', 'Section.abbreviation'),
					 $this->Paginator->sort('Name', 'Section.name'), 'Verfügbarkeit', 'Aktionen');
	echo $this->Html->tableHeaders($headers);
	
	foreach ($sections as $section) {
		
		$name_cell = $section['Section']['name'];
		if ($permission['summary']) {
			$name_cell = $this->Html->link($name_cell, array('controller' => 'sections',
			'action' => 'view', $section['Section']['id']));
		}
		
		$cells = array($section['Section']['abbreviation'],
					   $name_cell,
		);

		if (!empty($section['Contingent'])) {
			$semesters = "";
			foreach ($section['Contingent'] as $contingent) {
				if ($permission['update']) {
					$semesters .= $this->Html->link($contingent['Semester']['name']
						 . '/' . $contingent['CourseType']['abbreviation'], 
						 	array('controller' => 'semesters', 'action' => 'view', $contingent['Semester']['id']));	
				} else {
					$semesters .= $contingent['Semester']['name'] . '/' . $contingent['CourseType']['abbreviation'];
				}
				$semesters .= '<br/>';
			}
		} else {
			$semesters = 'Keine';
		}
		 
		$cells[] = $semesters;
		
		$actions = $this->Html->link('Details', array('controller' => 'sections',
			'action' => 'view', $section['Section']['id']));
		
		if ($permission['update']) {
			$actions .= ' - ' . $this->Html->link('Ändern', array('controller' => 'sections',
				'action' => 'edit', $section['Section']['id']));
		}
		
		if ($permission['delete']) {
			$actions .= ' - ' . $this->Html->link('Löschen', array('controller' => 'sections',
				'action' => 'delete', $section['Section']['id']));
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