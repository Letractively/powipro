<h1>Email-Benachrichtigungen</h1>
<p>Folgende Variablen werden durch die richtigen Werte ersetzt:<br/>
<ul>
<li>%TITEL% - Anrede (Herr, Frau)</li>
<li>%AKADEMISCHE_GRADE% - Titel (Dr., MA, etc.)</li>
<li>%VORNAME% - Vorname des Bewerbers/der Bewerberin</li>
<li>%NACHNAME% - Nachname des Bewerbers/der Bewerberin</li>
<li>%PROPOSAL% - Titel des Proposals, f&uuml;r das die Nachricht versendet wird</li>
<li>%SEMESTER% - Name des Semesters, das ausgew&auml;hlt ist (nur bei akzeptierten Proposals!)</li>
</ul></p>

<?php 

if (!$sent && $message_count == 0) {
	
?>
<p>F&uuml;r dieses Semester wurden bereits alle Benachrichtigungen versendet.</p>	
<?php 
	
} else if (!$sent && $message_count > 0) {
	
	?>
	<p>In diesem Semester m&uuml;ssen noch <?php echo $message_count; ?> Nachrichten versendet werden.<br/>
	<strong>Wenn Sie auf Emails senden klicken, kann dies eine Weile dauern. Schlie&szlig;en Sie in der Zwischenzweit diese Seite nicht!</strong>
	<?php
	
	echo $this->Html->script('ckeditor/ckeditor.js');
	echo $this->Form->create(false, array('type' => 'file', 'url' => '/semesters/notify/' . $semester_id));
	
	echo '<p>Text f&uuml;r angenommene Proposals:<br/>';
	$accepted_message = 'Hallo %TITEL% %AKADEMISCHE_GRADE% %VORNAME% %NACHNAME%!<br />Ihr Proposal mit dem Titel %PROPOSAL% wurde f&uuml;r das %SEMESTER% angenommen!<br/>Weitere Informationen finden Sie im Dateianhang';
	echo $this->Form->textarea('accepted', array('value' => $accepted_message));
	echo $this->Html->scriptBlock("CKEDITOR.replace('accepted');");
	
	echo '<p>Attachment f&uuml;r angenommene Proposals:<br/>';
	echo $this->Form->file('attachment');
	
	echo '<p>Text f&uuml;r abgelehnte Proposals:<br/>';
	$refused_message = 'Hallo %TITEL% %AKADEMISCHE_GRADE% %VORNAME% %NACHNAME%!<br/>Leider wurde ihr Proposal "%PROPOSAL%" nicht angenommen.<br/>Viel Gl&uuml;ck beim n&auml;chsten Mal!';
	echo $this->Form->textarea('refused', array('value' => $refused_message));
	echo $this->Html->scriptBlock("CKEDITOR.replace('refused');");
	
	echo '<p/>';
	echo $this->Form->end('Emails senden');
	
} else {

?>
<p>Status der versendeten Nachrichten.</p>

<h3>Angenommene Proposals</h3>
<?php 
	echo 'Es wurden ' . $count_sent_selected . ' Nachrichten von ' . $count_selected . ' n&ouml;tigen Benachrichtigungen versendet.<br/>';
	
	if ($count_sent_selected != $count_selected) {
		echo 'Folgende Benachrichtigungen konnten nicht versendet werden:<br/>';
		
		foreach ($selected_not_sent as $message) {
			echo 'Nachricht an ' . $message['name'] . ' (Adresse: ' . $message['email'] . '): ' . $message['proposal']['Proposal']['name'] . '<br />';
		}
	}
?>

<h3>Abgelehnte Proposals</h3>

<?php 
	echo 'Es wurden ' . $count_sent_other . ' Nachrichten von ' . $count_other . ' n&ouml;tigen Benachrichtigungen versendet.<br/>';

	if ($count_sent_other != $count_other) {
		echo 'Folgende Benachrichtigungen konnten nicht versendet werden:<br/><list>';

		foreach ($other_not_sent as $message) {
			echo '<ul>Nachricht an ' . $message['name'] . ' (Adresse: ' . $message['email'] . '): ' . $message['proposal']['Proposal']['title'] . '</ul>';
		}
		
		echo '</list>';
	}
}
?>