<h1>Passwort zur&uuml;cksetzen</h1>
<?php
	if (!isset($reset)) {
		echo $this->Form->create('User', array('action' => 'reset_pw'));

		echo $this->Form->inputs(array(
		'legend' => __('Login', true),
		'email' => array('label' => 'E-Mail-Adresse (mit der Sie sich registriert haben)'),
		));

		echo $this->Form->end('Neues Passwort anfordern');
		
	} else if ($reset == true) {
		echo '<p>Ein neues Passwort wurde per Mail verschickt.</p>';
	} else {
		echo '<p>Es wurde kein neues Passwort erstellt.</p>';
	}
	
?>