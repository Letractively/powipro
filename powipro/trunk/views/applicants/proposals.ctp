<h1>Proposal-&Uuml;bersicht</h1>
<p><strong>Sie haben mehrere, verschiedene Bewerbungsunterlagen erstellt.</strong><br/>
<p>Im Folgenden sehen Sie eine &Uuml;bersicht &uuml;ber die Proposals, die Sie
mit den aktuell ausgew&auml;hlten Unterlagen erstellt bzw. eingereicht haben.</p>
<p>Falls Sie Ihr Proposal hier nicht finden, dann
<?php echo $this->Html->link('sehen Sie sich die Liste mit all Ihren Bewerbungsunterlagen',
	array('controller' => 'users', 'action' => 'home')); ?> an.<br/>
	Eventuell haben Sie mit anderen Bewerbungsunterlagen bereits Proposals erstellt.</p>

<h3>Bewerbungsunterlagen</h3>
<p>Sie haben mit 

<?php echo $this->Html->link('diesen Bewerbungsunterlagen',
	array('controller' => 'applicants', 'action' => 'view', $applicant['Applicant']['id'])); ?>	 
	die im folgenden aufgelisteten Proposals erstellt.<br/>
Sie k&ouml;nnen diese 
<?php echo $this->Html->link('Bewerbungsunterlagen bearbeiten',
	array('controller' => 'applicants', 'action' => 'view', $applicant['Applicant']['id'])); ?>,
	um Sie auf dem aktuellsten Stand zu halten.<br/>
</p>

<h3>Proposals</h3>
<p>
<?php echo $this->Html->link('Ein neues Proposal',
	array('controller' => 'proposals', 'action' => 'edit', $applicant['Applicant']['id'])); ?>
	mit den ausgew&auml;hlten Bewerbungsunterlagen erstellen.
</p>
<?php if (count($proposals) > 0 ) { ?>
<p>Sie k&ouml;nnen die folgende Liste
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
		$applicant_id = $applicant['Applicant']['id'];
		
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
<?php } else { ?>
<p>Mit diesen Bewerbungsunterlagen haben Sie noch kein Proposal erstellt.</p>
<?php } ?>