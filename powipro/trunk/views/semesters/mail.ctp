<h1>E-Mail versenden</h1>
<p>Hier k&ouml;nnen Sie eine E-Mail schreiben und an alle Bewerber*innen versenden, die f&uuml;r das <?php echo $semester['Semester']['name']; ?> ein Proposal eingereicht haben!</p>
<p>Folgende Variablen werden durch die richtigen Werte ersetzt:<br/>
<ul><li>%TITEL% - Anrede (Herr, Frau)</li>
<li>%AKADEMISCHE_GRADE% - Titel (Dr., MA, etc.)</li>
<li>%VORNAME% - Vorname des Bewerbers/der Bewerberin</li>
<li>%NACHNAME% - Nachname des Bewerbers/der Bewerberin</li>
<li>%PROPOSAL% - Titel des Proposals, f&uuml;r das die Nachricht versendet wird</li>
<li>%SEMESTER% - Name des Semesters, das ausgew&auml;hlt ist</li></ul>
</p>

<?php 
	echo $this->Html->script('ckeditor/ckeditor.js');
	echo $this->Form->create(false, array('url' => '/semesters/mail/' . $semester['Semester']['id']));
	
	echo '<p>';
	echo $this->Form->input('subject', array('value' => 'Betreff', 'label' => 'Betreff:'));
	
	$message = 'Hallo %TITEL% %AKADEMISCHE_GRADE% %VORNAME% %NACHNAME%!<br/>Dies ist eine Benachrichtigung f&uuml;r das Proposal %PROPOSAL%, das Sie f&uuml;r das %SEMESTER% eingereicht haben.';
	echo '</p>Nachricht:<br/>';
	echo $this->Form->textarea('message', array('value' => $message));
	echo $this->Html->scriptBlock("CKEDITOR.replace('message');");
	echo '</p>';
	
	echo $this->Form->end('Nachricht an alle senden');
	
?>
