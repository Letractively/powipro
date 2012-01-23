<h1>Willkommen, <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>

<?php if ($proposal['Proposal']['filed_for'] == null) { ?>

<h2>Schritt 3: Proposal einreichen</h2>
<p>Sie haben Ihr erstes Proposal erfolgreich gespeichert!<br/>
Sie k&ouml;nnen es nun nocheinmal
<?php echo $this->Html->link('ansehen', 
	array('controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id'])); ?>,
	um alle Daten auf Richtigkeit und Vollst&auml;ndigkeit zu &uuml;berpr&uuml;fen.<br/>
Sie k&ouml;nnen es 
<?php echo $this->Html->link('editieren',
	array('controller' => 'proposals', 'action' => 'edit',
	$proposal['Proposal']['applicant_id'], $proposal['Proposal']['id'])); ?>, um fehlerhafte
	Angaben zu korrigieren.<br/>
Au&szlig;erdem k&ouml;nnen Sie es 
<?php echo $this->Html->link('löschen',
	array('controller' => 'proposals', 'action' => 'delete',
	$proposal['Proposal']['id']), null, 'Wirklich löschen? Sie müssen es dann neu schreiben!'); ?>, falls Sie damit unzufrieden sind.</p> 

<p>Nachdem Sie nun Ihr erstes Proposal erstellt haben, m&uuml;ssen Sie es noch einreichen.</p>
<h3>Einreichen</h3>
<?php if (count($semesters) == 0) { ?>
<p><strong>Im Moment gibt es keine Semester, f&uuml;r die Sie Ihr Proposal einreichen k&ouml;nnen.</strong><br/>
Die Einreichfristen sind bereits vorbei oder haben noch nicht begonnen!</p>
<p>Wenden Sie sich an die <a href="mailto:manuela.egger@univie.ac.at">Administration</a>!</p>
<?php 
	} else if ($proposal['Proposal']['section_id'] == null
	   || $proposal['Proposal']['course_type_id'] == null) {
?>
<p><strong>
Sie m&uuml;ssen zum Einreichen einen Bereich und einen Lehrveranstaltungstyp f&uuml;r Ihr Proposal ausw&auml;hlen!
</strong></p>
<p>
<?php echo $this->Html->link('Bearbeiten Sie dafür Ihr Proposal',
	array('controller' => 'proposals', 'action' => 'edit', 
	$proposal['Proposal']['applicant_id'], $proposal['Proposal']['id'])); ?> 
und w&auml;hlen Sie (ganz unten) einen Bereich und einen Kurstyp aus.<br/></p>
<p>Klicken Sie auf ein Semester, um zu erfahren, welche Bereiche und welche Kurstypen
 daf&uuml;r m&ouml;glich sind</p>
<ul>
<?php
	foreach ($semesters as $semester) {
		echo '<li>' . $this->Html->link($semester['Semester']['name'],
			array('controller' => 'semesters', 'action' => 'summary', $semester['Semester']['id']))
		. '</li>';
	} 
?>
</ul>
<?php } else { ?>
<p>Um das Proposal einzureichen, m&uuml;ssen Sie ein Semester ausw&auml;hlen, f&uuml;r das Sie es einreichen.<br/>
Es kann sein, dass Ihr Proposal im Zuge des Auswahlprozesses in ein anderes Semester verschoben wird.<br/>
Wir bitten in diesem Fall um Ihr Verst&auml;ndnis, und werden Sie davon nat&uuml;rlich pers&ouml;nlich in Kenntnis setzen.</p>

<strong>Nachdem Sie Ihr Proposal eingereicht haben, k&ouml;nnen Sie es nicht mehr bearbeiten oder l&ouml;schen!</strong>

<p>Die Semesterauswahl erreichen Sie auf der 
<?php echo $this->Html->link('Einreichseite',
	array('controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id'])); ?>
 ihres Proposals. Zun&auml;chst sehen Sie dort Ihr Proposal, so wie es von den Bereichsverantwortlichen gesehen wird.<br/>
 Weiter unten sehen Sie eine Auswahlliste, mit der Sie das gew&uuml;schte Semester ausw&auml;hlen k&ouml;nnen.<br/></p>
<?php } ?>

<h3>Ein weiteres Proposal schreiben!</h3>
<p>Sie k&ouml;nnen ein weiteres 
<?php echo $this->Html->link('Proposal schreiben', 
	array('controller' => 'proposals', 'action' => 'edit',
		  $proposal['Proposal']['applicant_id'])); ?>!<br/>
Wir empfehlen Ihnen allerdings, zun&auml;chst Ihr erstes Proposal einzureichen, um sich mit dem Prozedere vertraut zu machen.<br/>

<h3>Bewerbungsunterlagen</h3>
<p>Die Bewerbungsdaten, mit denen Sie Ihr Proposal geschrieben haben, k&ouml;nnen Sie jederzeit
<?php echo $this->Html->link('ansehen',
	array('controller' => 'applicants', 'action' => 'view', $proposal['Proposal']['applicant_id'])); ?>
	und
<?php echo $this->Html->link('bearbeiten',
	array('controller' => 'applicants', 'action' => 'edit', $proposal['Proposal']['applicant_id'])); ?></p>	
<p><small>Generell sollten Sie mit den bereits
	erstellten Bewerbungsdaten auskommen. Sie k&ouml;nnen aber auch jederzeit
<?php echo $this->Html->link('zusätzliche Bewerbungsdaten erstellen',
	array('controller' => 'applicants', 'action' => 'edit'), null,
	'Sie haben bereits Bewerbungsunterlagen erstellt. Wollen Sie wirklich weitere anlegen?'); ?>, doch raten wir Ihnen dazu,
	zun&auml;chst Ihr erstes Proposal einzureichen. Sie m&uuml;ssen dann auch f&uuml;r jedes
	Proposal, dass Sie erstellen ausw&auml;hlen, mit welchen Bewerbungsdaten Sie es es erstellen wollen.
</small></p>

<?php } else { ?>

<h2>Fertig!</h2>
<p>Sie haben Ihr Proposal erfolgreich eingereicht!</p>
<p><strong>Damit haben Sie das Prozedere abgeschlossen!</strong></p>
<p>Sie werden per E-Mail benachrichtigt, sobald entschieden sein wird, ob Ihr
Proposal angenommen oder abgelehnt wird.</p>
<p>Sie k&ouml;nnen Sich das Proposal jetzt nur noch 
<?php echo $this->Html->link('ansehen',
	array('controller' => 'proposals', 'action' => 'view', $proposal['Proposal']['id'])); ?><br/>
<?php if ($permissions['update']) { ?>
Aufgrund besonderer Berechtigungen d&uuml;rfen Sie Ihr Proposal 
<?php echo $this->Html->link('editieren',
	array('controller' => 'proposals', 'action' => 'edit',
		$proposal['Proposal']['applicant_id'], $proposal['Proposal']['id'])); ?><br/>
<?php } if ($permissions['delete']) { ?>
Aufgrund besonderer Berechtigungen d&uuml;rfen Sie Ihr Proposal 
<?php echo $this->Html->link('löschen',
	array('controller' => 'proposals', 'action' => 'delete',
			$proposal['Proposal']['id']), null, 'Sind Sie sicher?'); ?><br/>
<?php } ?>
<h3>Weitere Proposals schreiben</h3>
<p>Sie k&ouml;nnen nun 
<?php echo $this->Html->link('ein weiteres Proposal erstellen',
	array('controller' => 'proposals', 'action' => 'edit',
		$proposal['Proposal']['applicant_id'])); ?>.<br/>
Das Prozedere läuft dabei gleich ab wie bisher, Sie sehen dann allerdings auf ihrer
Startseite eine &Uuml;bersicht &uuml;ber alle Ihre erstellten und eingereichten Proposals
anstelle dieser Einf&uuml;hrung.</p>

<h3>Bewerbungsunterlagen</h3>
<p>Die Bewerbungsdaten, mit denen Sie Ihr Proposal geschrieben haben, k&ouml;nnen Sie jederzeit
<?php echo $this->Html->link('ansehen',
	array('controller' => 'applicants', 'action' => 'view', $proposal['Proposal']['applicant_id'])); ?>
	und
<?php echo $this->Html->link('bearbeiten',
	array('controller' => 'applicants', 'action' => 'edit', $proposal['Proposal']['applicant_id'])); ?></p>	
<p><small>Generell sollten Sie mit den bereits
	erstellten Bewerbungsdaten auskommen.<br/>Neue Proposals verwenden automatisch die bereits von
	Ihnen erstellten Bewerbungsunterlagen. Sie k&ouml;nnen aber auch jederzeit
<?php echo $this->Html->link('zusätzliche Bewerbungsdaten erstellen',
	array('controller' => 'applicants', 'action' => 'edit'), null,
	'Sie haben bereits Bewerbungsunterlagen erstellt. Wollen Sie wirklich weitere anlegen?'); ?>, doch raten wir Ihnen dazu,
	immer die bereits erstellten Bewerbungsunterlagen zu verwenden. Die Option, zus&auml;tzliche Bewerbungsunterlagen zu 
	erstellen ist nur sinnvoll, wenn Sie mehrere Proposals mit unterschiedlichen Bewerbungsunterlagen anlegen wollen.
</small></p>

<?php } ?>

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
