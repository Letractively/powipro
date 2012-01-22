<h1>Proposal ansehen und einreichen</h1>
<p>Ihr Proposal wird in folgender Form den Bereichsverantwortlichen gezeigt.<br/>
Kontrollieren Sie die Daten auf Richtigkeit!<br/>
Wenn Sie bereits einen Bereich und einen Kurstyp für das Proposal ausgewählt haben,
können Sie es einreichen: Wählen Sie weiter unten dafür das gewünschte Semester aus und 
klicken Sie dann auf Einreichen!</p>
 
<?php

if ($permissions['summary']) {

?>
<h2><?php echo $proposal['Proposal']['title']; ?></h2>
<?php
}

if ($permissions['read']) {
?>
	<h4>Englischer Titel: <?php echo $proposal['Proposal']['english_title']; ?></h4>
	<h4>Untertitel: <?php echo $proposal['Proposal']['subtitle']; ?></h4>
	<h3>Beschreibung</h3>
	<p><?php echo nl2br($proposal['Proposal']['description']); ?></p>
	<h3>Methoden, Ablauf und andere Kommentare</h3>
	<p><?php echo $this->Bbcode->parse($proposal['Proposal']['comment']); ?></p>
	<h3>Administratives</h3>
	<?php if ($proposal['Proposal']['filed_for'] != null) { ?>
	<p>Eingereicht f&uuml;r das <?php echo $proposal['FiledSemester']['name']; ?></p>
	<p>Eingereich am <?php echo strftime('%e.&thinsp;%m.&thinsp;%Y', strtotime($proposal['Proposal']['submission_date'])); ?></p>
	<?php } ?> 
	<p>Bereich: <?php echo $proposal['Proposal']['section_id'] == null ? 'Noch nicht ausgew&auml;hlt' : $proposal['Section']['name']; ?></p>
	<p>Kurstyp: <?php echo $proposal['Proposal']['course_type_id'] == null ? 'Noch nicht ausgew&auml;hlt' : ($proposal['CourseType']['abbreviation'] . ' - ' . $proposal['CourseType']['description']); ?></p>

<?php 
}

echo '<h3>Bewerber*in - Daten</h3>';	
echo $proposal['Applicant']['title'] . ' ' . $proposal['Applicant']['name']
	 . ' ' . $proposal['Applicant']['last_name'] 
	 . ' - '
	 . $this->Html->link('Bewerbungsdaten ansehen', array('controller' => 'applicants',
		'action' => 'view', $proposal['Applicant']['id'])); 

if (!empty($proposal['Coapplicant'])) {
	echo '<h3>Zweitbewerber*innen</h3>';	
}

foreach ($proposal['Coapplicant'] as $coapplicant) {
	echo $coapplicant['Applicant']['name'] . ' ' . $coapplicant['Applicant']['last_name']
	. ' - '
	. $this->Html->link('Bewerbungsdaten ansehen', array('controller' => 'applicants',
		'action' => 'view', $coapplicant['applicant_id']));
	
}

echo '<br/>';

if ($permissions['update']) {
	echo '<h3>Bearbeiten</h3><p>';
	echo $this->Html->link('Proposal bearbeiten', array('controller' => 'proposals',
		'action' => 'edit', $proposal['Proposal']['applicant_id'], $proposal['Proposal']['id'])) . '</p>';
	
	//if ($proposal['Proposal']['filed_for'] == null) {
	if ($proposal['Proposal']['filed_for'] != null) {
		echo '<br/><strong><font style="color: red">Sie haben das Proposal bereits einmal eingereicht, d&uuml;rfen es aber aufgrund von besonderen Berechtigungen &uuml;berarbeiten und erneut einreichen.</font></strong><p />';
	}
	
	echo '<br/><strong><font style="color: red">Wenn Sie das Proposal einreichen, ist es f&uuml;r die weitere Bearbeitung gesperrt.</font></strong><p/>';
		
		if (isset($proposal['Section']['name']) && isset($proposal['CourseType']['description'])) { 
		?><h3>Einreichen</h3>Mit Ihrer Auswahl des Bereiches  
		<?php echo $proposal['Section']['abbreviation'] . ' - ' . $proposal['Section']['name']; ?>
		und dem Kurstyp <?php echo $proposal['CourseType']['description']; ?> können Sie das Proposal
		für folgende Semester einreichen:<br/>
		<?php 
		} else {
			echo 'Sie müssen einen Bereich und einen Kurstyp für ihr Proposal auswählen: <strong>Bearbeiten Sie Ihr Proposal!</strong><br/>'
			. 'Andernfalls können Sie es <strong>nicht</strong> (für kein Semester) einreichen.<br/>';
		}
		
		if (count($semesters) > 0) {
			echo $this->Form->create('Proposal', array('url' => '/proposals/file/' . $proposal['Proposal']['id']));
	
			$semester_options = array();
			foreach ($semesters as $semester) {
				$semester_options[$semester['Semester']['id']] = $semester['Semester']['name'];
			}
			echo $this->Form->select('Proposal.filed_for', $semester_options,
						 $proposal['Proposal']['filed_for'],
						 array('id' => 'proposalSemesterSelect', 'empty' => 'Bitte wählen Sie ein Semester aus'));
			echo $this->Form->submit('Proposal einreichen');
			echo $this->Form->end();
			
		} else {
			echo '<strong>Keine Semester zu Auswahl.</strong><br/>Im Moment können Sie keine Proposals einreichen, da entweder für kein Semester die Einreichfrist begonnen hat, oder sie einen Bereich/Kurstyp ausgewählt haben, für den es derzeit kein Kontingent gibt.<br/>';
			echo 'Auf der Übersichtsseite zu allen Semestern finden Sie Informationen über Einreichfristen und Kontingente.<br/>';
			echo $this->Html->link('Semesterübersicht', array('controller' => 'semesters',
				'action' => 'overview'));
			echo '</p><p>';
		}
			
	/*} else {
		echo 'Das Proposal wurde eingereicht und ist in Bearbeitung.<br />';
	}*/
	
}

if ($permissions['delete']) {
	echo '<p/><strong><font style="color: red">Aufgrund Ihrer Berechtigungen d&uuml;rfen Sie dieses Proposal auch </font></strong>';
	echo $this->Html->link('löschen',
			       '/proposals/delete/' . $proposal['Proposal']['id'],
			       array(),
			       'Wirklich löschen? Dies kann nicht mehr rückgängig gemacht werden!') . '<br />';
}


?>
</p><p>
<?php
	if (isset($back_link))
		echo $this->Html->link('Zurück', $back_link);
	else
		echo $this->Html->link('Zurück zur Startseite', '/users/home'); 
?>
</p>
