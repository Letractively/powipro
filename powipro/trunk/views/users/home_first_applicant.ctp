<h1>Willkommen, <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>

<h2>Schritt 1: Bewerbungsunterlagen</h2>
<p>Als erstes m&uuml;ssen Sie Ihre Bewerbungsunterlagen erstellen</p>
<p>Dazu geh&ouml;ren:<br/>
<ul>
<li>Pers&ouml;nliche Daten (Geburtsdatum, Adresse, Telefonnummern, ...)</li>
<li>Administrative Daten (Sozialversicherungsnummer, ...)</li>
<li>Akademische Daten (Ver&ouml;ffentlichungen, Lehrerfahrung, ...)</li>
</ul>
<p>Wenn Sie diese einmal erstellt haben, k&ouml;nnen Sie sie f&uuml;r <strong>mehrere Proposals</strong> wiederverwenden.<br/>
Sie k&ouml;nnen Sie auch &uuml;ber <strong>mehrere Semester</strong> hinweg wiederverwenden, da Sie sie laufend aktualisieren k&ouml;nnen,
auch wenn gerade kein Call-for-Proposals offen ist.<br/>
Damit haben Sie den Vorteil, Bewerbungsunterlagen nur einmal an dieser zentralen Stelle zu erstellen, die Sie dann
immer <strong>wieder verwenden</strong> können.</p>
<p>Sie m&uuml;ssen zuerst Bewerbungsdaten erstellen, bevor Sie Proposals schreiben k&ouml;nnen!</p>
<br/>
<?php 
	echo '<p>' . $this->Html->link('Klicken Sie hier, um jetzt Ihre Bewerbungsunterlagen auszufüllen!',
		array('controller' => 'applicants',
	  	'action' => 'edit')) . '</p>';
?>
