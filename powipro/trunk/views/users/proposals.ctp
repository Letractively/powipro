<h1>Alle Proposals von <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']?></h1>
<p>Hier sehen Sie alle <strong>nicht eingereichten</strong> Proposals dieses Users. Sie können sie hier manuell
für jedes beliebige Semester auswählen! Falls das gewählte Semester nicht das nötige Kontingent
beinhaltet, werden Sie das Proposal dort allerdings nicht akzeptieren können!</p>
<p>Sobald allerdings das Proposal im gewünschten Semester gelandet ist, können Sie es dann 
dort verschieben!</p>
<table>
<?php
	$headers = array(
		'Titel',
		'Untertitel',
		'CV',
		'Aktionen',
	);

	echo $this->Html->tableHeaders($headers);

	foreach ($proposals as $proposal) {
		$cells = array(
			$this->Html->link($proposal['Proposal']['title'], 
				array('controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id'])),
			$proposal['Proposal']['subtitle'],
			$proposal['Applicant']['name'] . ' ' . $proposal['Applicant']['last_name'] . ' - '
			. $this->Html->link('CV', array('controller' => 'applicants', 'action' => 'view', $proposal['Applicant']['id'])),
		);

		$actions = $this->Html->link('Aufnehmen', array('controller' => 'proposals', 'action' => 'grab', $proposal['Proposal']['id']));
		$cells[] = $actions;

		echo $this->Html->tableCells($cells);
	}

?></table>