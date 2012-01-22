<h1>Account Aktivierung</h1>
<?php 
	if (!isset($activated)) { 
		echo '<h3>Aktivieren Sie Ihren Account manuell</h3>';
		
		echo $this->Form->create(false, array('controller' => 'users', 'action' => 'activate'));
		echo $this->Form->inputs(array(
			'legend' => 'Daten',
			'email' => array('label' => 'E-Mail-Adresse', 'size' => 50),
			'activation_code' => array('label' => 'Aktivierungscode', 'size' => 50),
		));
		echo $this->Form->end('Aktivieren');
		
		
	} else if ($activated == true) {
		echo '<p>Ihr Account wurde erfolgreich aktiviert. Loggen Sie sich nun ein!</p>';
		echo $this->Html->link('Einloggen', array('controller' => 'users', 'action' => 'login'));
		
	} else if ($activated == 'admin') {
		echo '<p>Der Account wurde aktiviert. Der gew&uuml;nschte Benutzer kann sich nun einloggen</p>';
		
	} else {
?>

<h3>Die Aktivierung schlug fehl.</h3>
<ul><li>&Ouml;ffnen Sie die Ihnen zugesendete E-Mail erneut.</li> 
<li>W&auml;hlen Sie den Link mittels Rechtsklick, Adresse (oder Link) kopieren aus.</li>
<li>F&uuml;gen Sie den Link in der Adresszeile Ihres Browsers ein.</li>
</ul>
<p>Sollte dieses Prozedere nicht funktionieren, so wenden Sie sich bitte direkt an die SPL 21!</p>
<?php } ?>