<h1>Willkommen, <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>

<h2>Schritt 2: Proposals schreiben</h2>
<p>Nachdem Sie ihre Bewerbungsunterlagen erstellt haben, k&ouml;nnen Sie nun Proposals einreichen!</p>
<br/>
<p>Proposals, die Sie nun einreichen, sind mit den von Ihnen erstellten Bewerbungsunterlagen verbunden.<br/>
<?php echo $this->Html->link('Kontrollieren Sie Ihre Bewerbungsdaten',
	array('controller' => 'applicants', 'action' => 'view', $applicant['Applicant']['id'])); ?> 
	daher auf Korrektheit und Vollst&auml;ndigkeit!<br/>Sie k&ouml;nnen 
<?php echo $this->Html->link('Ihre Bewerbungsdaten überarbeiten',
	array('controller' => 'applicants', 'action' => 'edit', $applicant['Applicant']['id'])); ?>,
	falls diese unvollst&auml;ndig oder fehlerhaft sind.
</p>
<br/>
<p>Wenn Sie mit Ihren Bewerbungsunterlagen alles stimmt, dann 
<?php echo $this->Html->link('klicken Sie hier, um ein Proposal zu erstellen',
		array('controller' => 'proposals', 'action' => 'edit', $applicant['Applicant']['id']));
?>!</p>

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


