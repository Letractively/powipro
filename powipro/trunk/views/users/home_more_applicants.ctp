<h1>Willkommen, <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>
<p>Da Sie mehrere Bewerbungsunterlagen erstellt haben, m&uuml;ssen Sie nun
ausw&auml;hlen, von welchen Sie Ihre erstellten Proposals ansehen bzw. mit welchen Sie neue
Proposals erstellen wollen.</p>

<center>
<div class="paging"><?php echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
		 . $this->Paginator->numbers() . '&nbsp;'
		 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 
	$tableHeaders = array(
		$this->Paginator->sort('ID', 'Applicant.id'),
		$this->Paginator->sort('Name', 'Applicant.last_name'),
		$this->Paginator->sort('E-Mail', 'Applicant.email'),
		$this->Paginator->sort('Adresse', 'Applicant.address'),
		'Proposals',
		'Aktionen',
	);
	echo $this->Html->tableHeaders($tableHeaders);
	
	foreach ($applicants as $applicant) {
		$cells = array();
		
		$applicant_id = $applicant['Applicant']['id'];
		
		$cells[] = $applicant['Applicant']['id'];
		$cells[] = $applicant['Applicant']['name'] . ' ' . $applicant['Applicant']['last_name'];
		$cells[] = $this->Html->link($applicant['Applicant']['email'],
			'mailto:' . $applicant['Applicant']['email']);
		$cells[] = $applicant['Applicant']['address'];
		$cells[] = $proposals[$applicant_id];
		
		$actions = $this->Html->link('Ansehen', 
			array('controller' => 'applicants', 'action' => 'view', $applicant_id))
		. '<br/>'
		. $this->Html->link('Bearbeiten', 
			array('controller' => 'applicants', 'action' => 'edit', $applicant_id))
		. '<br/>'
		. $this->Html->link('Proposal-Übersicht', 
			array('controller' => 'applicants', 'action' => 'proposals', $applicant_id))
		. '<br/>'
		. $this->Html->link('Neues Proposal', 
			array('controller' => 'proposals', 'action' => 'edit', $applicant_id));
		
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

<?php if (count($coapplicants) > 0) { ?>
<h3>Ko-Bewerbungen</h3>
<p>F&uuml;r folgende Proposals sind Sie als Zweitbewerber/in eingetragen oder eingeladen worden</p>
<table>
<?php 
	$tableHeaders = array('Titel', 'Untertitel', 'Aktionen');
	echo $this->Html->tableHeaders($tableHeaders);
	
	foreach ($coapplicants as $coapplicant) {
		$cells = array();
		
		$cells[] = $coapplicant['Proposal']['title'];
		$cells[] = $coapplicant['Proposal']['subtitle'];
		
		$actions = '';
		if ($coapplicant['Coapplicant']['accepted'] == false) {
			$actions = 'Sie sind noch nicht als Zweitbewerber/in eingetragen.<br/><small>'
			. 'Einladung zum/zur Zweitbewerber/in '
			. $this->Html->link('annehmen',
				array('controller' => 'proposals', 'action' => 'coaccept', 
					$coapplicant['Coapplicant']['id']))
			. ' oder '
			. $this->Html->link('ablehnen',
				array('controller' => 'proposals', 'action' => 'corefuse', 
					$coapplicant['Coapplicant']['id']))
			. '</small>';
			
		} else {
			$actions = 'Sie sind als Zweitbewerber/in eingetragen.<br/><small>'
			. $this->Html->link('Proposal ansehen',
				array('controller' => 'proposals', 'action' => 'view',
					$coapplicant['Coapplicant']['proposal_id']))
			. '</small>';
			
		}
		
		$cells[] = $actions;
		
		echo $this->Html->tableCells($cells);
	}
?>
</table>
<?php } ?>
<h3>Ko-Bewerbung</h3>
<p>Falls Sie ein Proposal gemeinsam mit einem/einer zweite/n Bewerber/in einreichen wollen,
muss nur eine Person das Proposal schreiben.</p>
<p><strong>Falls Ihr/e Partner/in das Proposal bereits erstellt hat, geben sie ihm/ihr die ID jener
Bewerbungsunterlagen, mit denen Sie als Zweit-Bewerber/in abgespeichert werden wollen!</strong><br/>
Die ID der Bewerbungsunterlagen finden Sie in obiger Tabelle.</p><br/><p>
Er/Sie muss das Proposal dann editieren und Ihre Bewerbungs-ID dort eintragen.<br/>
Dies funktioniert nur, solange das Proposal noch nicht eingereicht wurde!</p>
<br/>
<p>Sie sehen dann auf der Startseite (Home) jene Proposals, f&uuml;r die Sie als Ko-Bewerber/in
eingetragen wurden. Dies m&uuml;ssen Sie dann noch best&auml;tigen.</p>
<p><strong>Wenn das Proposal eingereicht wird, bevor Sie die Ko-Bewerbung best&auml;tigen,
werden Sie nicht als Ko-Bewerber/in angef&uuml;hrt!</strong></p>


<br/><p><small>
Sie k&ouml;nnen au&szlig;erdem neue Bewerbungsdaten erstellen. Dies sollte normalerweise
nicht n&ouml;tig sein, da Sie Ihre aktuellen Bewerbungsunterlagen jederzeit aktualisieren k&ouml;nnen.
Sollten Sie dennoch neue Bewerbungsunterlagen anlegen wollen, m&uuml;ssen Sie in Zukunft f&uuml;r
jedes neue Proposal ausw&auml;heln, mit welchen Bewerbungsunterlagen Sie es erstellen m&ouml;chten.<br/>
Ja, ich wei&szlig;, ich m&ouml;chte aber trotzdem 
<?php echo $this->Html->link('zusätzliche Bewerbungsunterlagen erstellen',
	array('controller' => 'applicants', 'action' => 'edit')); ?>.
</small></p>