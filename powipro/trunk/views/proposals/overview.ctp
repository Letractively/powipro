<h1>Proposals im <?php echo $semester['Semester']['name'] ?></h1>
<?php if (!$semester_permissions['approve']) { ?>
<p><strong>Dieses Semester ist momentan geschlossen.</strong><br/>Sie können zwar Proposals sehen, sie aber
nicht auswählen oder ablehnen.</p>
<?php } ?>
<?php if (isset($section)) echo '<h2>' . $section['Section']['name'] . '</h2>'; ?>
<h4>Stellen Sie einen Filter ein, um Proposals nach Kriterien auszuw&auml;hlen!</h4>
<?php
	echo $this->Form->create(false, array('url' => 
		array('controller' => 'proposals', 'action' => 'overview', $semester['Semester']['id']),
		'type' => 'GET'));
	
	$options = array();
	foreach ($sections as $section) {
		$options[$section['Section']['id']] = $section['Section']['abbreviation'] . ' - ' . $section['Section']['name'];
	}
	$status_options = array(
		'approved' => 'Bereits akzeptierte',
		'open' => 'Noch nicht akzeptierte'
	);
	
	echo '<div class="proposalFilter">'
	. $this->Form->select('status', $status_options, $selected_status, array('empty' => 'Alle'))
	. ' aus '
	. $this->Form->select('section', $options, $selected_section, array('empty' => 'allen Bereichen'))
	. $this->Form->end('Filter anwenden', array('class' => ''))
	. '</div>';

 	echo 'Mit Ihrer Auswahl finden sich ' . $this->Paginator->counter(array('format' => '%count%'))
	 . ' Proposal(s).';
 	
?>
<center><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 
	echo $this->Html->tableHeaders(
		array(
			$this->Paginator->sort('Titel', 'Proposal.title'),
			$this->Paginator->sort('Untertitel', 'Proposal.subtitle'),
			$this->Paginator->sort('CV', 'Applicant.name'),
			$this->Paginator->sort('Bereich', 'Section.abbreviation'),
			$this->Paginator->sort('Kurstyp', 'CourseType.description'),
			'Aktion'
		)
	);
	
	foreach ($proposals as $proposal) {
		$id = $proposal['Proposal']['id'];
		$permission = $permissions[$id];
				
		if ($permission['summary'] || $permission['read']) {
			$title_cell = $proposal['Proposal']['title'];
			if ($permission['read']) {
				$title_cell = $this->Html->link($title_cell, array(
				'controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id']));
			}
			
			$cells = array(
				$title_cell,
				$proposal['Proposal']['subtitle'],
				$proposal['Applicant']['name'] . ' ' . $proposal['Applicant']['last_name'],
				$proposal['Section']['abbreviation'] . ' - ' . $proposal['Section']['name'],
				$proposal['CourseType']['description'],
			);
			
			$action = $this->Html->link('Ansehen', '/proposals/view/' . $id);
			if ($permission['update']) {
				$action .= ' - ' . $this->Html->link('Bearbeiten', array('controller' => 'proposals',
					'action' => 'edit', $proposal['Proposal']['applicant_id'], $id));
			}
			
			if ($permission['read']) {
				$cells[2] .= ' - ' . $this->Html->link('CV', array('controller' => 'applicants',
					'action' => 'view', $proposal['Proposal']['applicant_id']));
			}
			
			if ($permission['delete']) {
				$action .= ' - ' . $this->Html->link('Löschen',
								     '/proposals/delete/' . $id,
								     array(), 
								     'Wollen Sie das Proposal wirklich löschen? Es kann nicht wiederhergestellt werden!');
			}
			if ($permission['approve'] && $semester_permissions['approve']) {
				$linkname = 'Akzeptieren';
				$linkaction = '/proposals/approve/' . $id . '/' . $proposal['Proposal']['filed_for'];
				
				if ($status[$id] == 'approved') {
					$linkname = 'Abwählen';
					$linkaction = '/proposals/disprove/' . $id;
				}
				
				if ($status[$id] != 'approved') {
					$action .= ' - ' . $this->Html->link('Verschieben',
						array('controller' => 'proposals', 'action' => 'move', $id));
				}
				
				$action .= ' - ' . $this->Html->link($linkname, $linkaction);
				
			}
			
			if (!$permission['approve']) {
				$action .= ' - Fixplatz';
			}
			
			if ($permission['update'] && $permission['approve']) {
				if ($editable[$id]) {
					$action .= ' - ' . $this->Html->link('Sperren', array('controller' => 'proposals',
						'action' => 'revokeEditRights', $id));
				} else {
					$action .= ' - ' . $this->Html->link('Entsperren', array(
						'controller' => 'proposals', 'action' => 'grantEditRights', $id));
				}
			}
			
			if ($permission['update'] && $permission['approve']) {
				if ($approvable[$id]) {
					$action .= ' - ' . $this->Html->link('Fixplatz', 
						array('controller' => 'proposals', 'action' => 'lock', $id));
				} else {
					$action .= ' - ' . $this->Html->link('Doch kein Fixplatz',
						array('controller' => 'proposals', 'action' =>  'lock', $id));
				}
			}
			
			$cells[] = $action;
			echo $this->Html->tableCells($cells);
		}
	}
?>
</table><p>
<?php

if ($exportable) {

	$exportlink = '/proposals/export/' . $semester['Semester']['id'];
	if (isset($section)) {
		$exportlink .= '/' . $section['Section']['id'];
	}

	echo '<p>' . $this->Html->link('Angenommene Proposals als Excel-Datei exportieren', $exportlink) . '</p>';
}
?></p>
<center><p><div class="paging"><?php
echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
. $this->Paginator->numbers() . '&nbsp;'
. $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled'));
?></div></p>
<p>Sie befinden sich auf
<?php echo $this->Paginator->counter(array('format' => 'Seite %page% von %pages%')); ?>
</p></center>
