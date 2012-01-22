<h1>Semesterübersicht</h1>
<p>
Sehen Sie sich Semester im Detail an, um zu erfahren, für welche Bereiche Lehrveranstaltungen gesucht werden.<br />
</p>

<?php
	if ($access['create']) {
		echo '<p>' . $this->Html->link('Neues Semester hinzufügen', array('controller' => 'semesters',
				'action' => 'edit')) . '</p>';
	}
?>
<center>
<div class="paging"><?php echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
		 . $this->Paginator->numbers() . '&nbsp;'
		 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php

	$headers = array(
		$this->Paginator->sort('Semester', 'Semester.name'),
		$this->Paginator->sort('Beginn der Einreichfrist', 'Semester.start'),
		$this->Paginator->sort('Ende der Einreichfrist', 'Semester.deadline'),
	);
	
	$headers[] = 'Aktionen';
	
	if ($access['delete']) {
		$headers[] = $this->Paginator->sort('Letzter Einreichtermin', 'Semester.final_deadline');
		$headers[] = $this->Paginator->sort('Für BV geschlossen?', 'Semester.closed');
		$headers[] = $this->Paginator->sort('Veröffentlicht?', 'Semester.published');
	}

	if ($access['update'] || $access['delete']) {
		$headers[] = 'Administrieren';
	}

	echo $this->Html->tableHeaders($headers);

	$counter = 0;
	foreach ($semesters as $sem) {		
		$semester = $sem['Semester'];
		$stat = $status[$semester['id']];
		$permission = $permissions[$semester['id']];
	
		if (!$permission['summary']) {
			continue;
		}

		$counter++;
		
		$cells = array($semester['name']);
				
		$cells[] = strftime('%e.%m.%Y', strtotime($semester['start']));
		$cells[] = strftime('%e.%m.%Y', strtotime($semester['deadline']));

				
		if ($permission['read']) {
			$actions = $this->Html->link('Kontingente', '/semesters/view/' . $semester['id']);
		} else if ($permission['summary']) {
			$actions = $this->Html->link('Überblick', '/semesters/summary/' . $semester['id']);
		}

		if ($permission['read'] && $stat == 'published') {
			$actions .= ' - ' . $this->Html->link('Proposals', '/proposals/overview/' . $semester['id']);
		}

		$cells[] = $actions;
		
		if ($permission['delete']) {
			$cells[] = strftime('%e.%m.%Y', strtotime($semester['final_deadline']));
			if ($semester['published'] == 1 && $semester['closed'] == 0) {
				$cells[] = 'Nein<br/><small>'
					. $this->Html->link('Schließen', '/semesters/close/' . $semester['id'], null,
										'Wirklich schließen? Bereichsverantwortliche können dann keine Proposals mehr annehmen!')
					. '</small>';
					
			} else if ($semester['published'] == 1 && $semester['closed'] == 1) {
				$cells[] = 'Ja<br/><small>'
					. $this->Html->link('Öffnen', '/semesters/reopen/' . $semester['id'])
					. '</small>';
		
			} else {
				$cells[] = 'Ja';
			}
		
			if ($permission['update']) {
				$cells[] = 'Nein<br/><small>'
					. $this->Html->link('Veröffentlichen', '/semesters/publish/' . $semester['id'], null,
										'Wirklich veröffentlichen? Sie können dann keine neuen Kontingente mehr hinzufügen!')
					. '</small>';
				
			} else {
				$cells[] = 'Ja';
			}
		}
		
		$admin = "";
		
		if ($permission['update']) {
			$admin .= $this->Html->link('Bearbeiten', '/semesters/edit/' . $semester['id']);
		}

		if ($permission['delete']) {
			if ($admin != "") $admin .= '<br/>';
			$admin .= $this->Html->link('Löschen', '/semesters/delete/' . $semester['id'], null,
				'Wirklich löschen? Alle eingereichten Proposals werden dann abgewählt! Senden Sie vorher eine E-Mail an alle Bewerber*innen!');
			$admin .= '<br/>' . $this->Html->link('Mail an alle', '/semesters/mail/' . $semester['id']);
			
			if ($semester['closed'] == 1) {
				$admin .= '<br/>' . $this->Html->link('Benachrichtigung', '/semesters/notify/' . $semester['id']);
			}
			
			$cells[] = $admin;
		}

		echo $this->Html->tableCells($cells);
	}
	
?>
</table>

<?php 
	if ($counter == 0) {
		echo 'Es sind derzeit keine Semester im System vorhanden.';
	}
?>
<center><p><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></p>
<p>Sie befinden sich auf
<?php echo $this->Paginator->counter(array('format' => 'Seite %page% von %pages%')); ?>
</p></center>