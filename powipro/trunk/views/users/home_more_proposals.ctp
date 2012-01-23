<h1>Willkommen, <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>

<p>Da Sie bereits mehrere Proposals geschrieben haben, sehen Sie hier eine
&Uuml;bersicht &uuml;ber all Ihre Proposals.<br/>
Wenn Sie ein neues Proposal erstellen, vergessen Sie nicht, dass Sie es
dann auch noch einreichen m&uuml;ssen!</p>

<h3>Bewerbungsunterlagen</h3>
<p>Sie haben mit 
<?php echo $this->Html->link('ihren Bewerbungsunterlagen',
	array('controller' => 'applicants', 'action' => 'view', $applicant['Applicant']['id'])); ?> 
	die im folgenden aufgelisteten Proposals erstellt.<br/>
Sie k&ouml;nnen Ihre 
<?php echo $this->Html->link('Bewerbungsunterlagen bearbeiten',
	array('controller' => 'applicants', 'action' => 'view', $applicant['Applicant']['id'])); ?>,
	um Sie auf dem aktuellsten Stand zu halten.<br/>
</p><p><small>
Sie k&ouml;nnen au&szlig;erdem neue Bewerbungsdaten erstellen. Dies sollte normalerweise
nicht n&ouml;tig sein, da Sie Ihre aktuellen Bewerbungsunterlagen jederzeit aktualisieren k&ouml;nnen.
Sollten Sie dennoch neue Bewerbungsunterlagen anlegen wollen, m&uuml;ssen Sie in Zukunft f&uuml;r
jedes neue Proposal ausw&auml;heln, mit welchen Bewerbungsunterlagen Sie es erstellen m&ouml;chten.<br/>
Ja, ich wei&szlig;, ich m&ouml;chte aber trotzdem 
<?php echo $this->Html->link('zusätzliche Bewerbungsunterlagen erstellen',
	array('controller' => 'applicants', 'action' => 'edit')); ?>.
</small></p>
<h3>Proposals</h3>
<p>
<?php echo $this->Html->link('Ein neues Proposal',
	array('controller' => 'proposals', 'action' => 'edit', $applicant['Applicant']['id'])); ?>
	mit den bereits vorhandenen Bewerbungsunterlagen erstellen.
</p><p>Sie k&ouml;nnen die folgende Liste
sortieren, indem Sie auf die jeweilige Spalten&uuml;berschrift klicken.</p>
<center>
<div class="paging"><?php echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
		 . $this->Paginator->numbers() . '&nbsp;'
		 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 
	$tableHeaders = array(
		$this->Paginator->sort('Titel', 'Proposal.title'),
		$this->Paginator->sort('Bereich', 'Section.abbreviation'),
		$this->Paginator->sort('Eingereicht', 'FiledSemester.name'),
		'Aktionen',
	);	
	
	echo $this->Html->tableHeaders($tableHeaders);
	
	foreach ($proposals as $proposal) {
		$cells = array();
		
		$proposal_id = $proposal['Proposal']['id'];
		$applicant_id = $proposal['Applicant']['id'];
		
		$cells[] = $this->Html->link($proposal['Proposal']['title'],
			array('controller' => 'proposals', 'action' => 'view', $proposal_id));
		
		if ($proposal['Proposal']['section_id'] != null) {
			$cells[] = $this->Html->link(
				$proposal['Section']['abbreviation'],
				array('controller' => 'sections', 'action' => 'view', 
					  $proposal['Section']['id']))
			. ' - ' . $proposal['Section']['name'];
			
		} else {
			$cells[] = 'Noch nicht ausgew&auml;hlt<br/><small>'
				. $this->Html->link('Auswählen',
					array('controller' => 'proposals', 'action' => 'edit',
					  $applicant_id, $proposal_id))
				. '</small>'; 
		}
		
		if ($proposal['Proposal']['filed_for'] == null) {
			$filing = 'Noch nicht eingereicht.';
			
			if ($proposal['Proposal']['section_id'] == null
				|| $proposal['Proposal']['course_type_id'] == null) {
				
				$filing .= '<br/><small>Sie m&uessen erst einen Bereich und '
				. 'einen Kurstyp ausw&auml;hlen.</small>';
				
			} else {
				if (count($semesters) > 0) {
					$filing .= '<br/><small>'
					. $this->Html->link('Einreichen',
						array('controller' => 'proposals', 'action' => 'view', $proposal_id))
					. '</small>';
					
				} else {
					$filing .= '<br/><small>Im Moment sind keine Semester zur Auswahl</small>';	
				}
			}
			$cells[] = $filing;
			
		} else {
			if ($proposal['Proposal']['submission_date'] == null) {
				$cells[] = 'Ja, f&uuml;r das '
				. $proposal['FiledSemester']['name']
				. '<br/><small>Manuell eingereicht von der Administration</small>';
			} else {
				$cells[] = 'Ja, f&uuml;r das ' . $proposal['FiledSemester']['name']
				. '<br><small>Datum: ' 
				. $proposal['Proposal']['submission_date']
				. '</small>';
			}
			
		}
		
		$actions = $this->Html->link('Ansehen',
			array('controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id']));
				
		if ($permissions[$proposal_id]['update']) {
			$actions .= '<br/>' . $this->Html->link('Bearbeiten',
			array('controller' => 'proposals', 'action' => 'edit',
			$applicant_id, $proposal_id));
		}
		
		if ($permissions[$proposal_id]['delete']) {
		$actions .= '<br/>' . $this->Html->link('Löschen',
					array('controller' => 'proposals', 'action' => 'delete', $proposal_id));
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
<p><strong>Falls Ihr/e Partner/in das Proposal bereits erstellt hat, geben sie ihm/ihr folgende Bewerbungs-ID: 
<?php echo $applicant['Applicant']['id']; ?></strong></p><br/><p>
Er/Sie muss das Proposal dann editieren und Ihre Bewerbungs-ID dort eintragen.<br/>
Dies funktioniert nur, solange das Proposal noch nicht eingereicht wurde!</p>
<br/>
<p>Sie sehen dann auf der Startseite (Home) jene Proposals, f&uuml;r die Sie als Ko-Bewerber/in
eingetragen wurden. Dies m&uuml;ssen Sie dann noch best&auml;tigen.</p>
<p><strong>Wenn das Proposal eingereicht wird, bevor Sie die Ko-Bewerbung best&auml;tigen,
werden Sie nicht als Ko-Bewerber/in angef&uuml;hrt!</strong></p>