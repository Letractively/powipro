<h1>Passwort &auml;ndern</h1>
<p>&Auml;ndern Sie Ihr Passwort.<br/>
Geben Sie dazu ihr aktuelles Passwort ein.<br/>
W&auml;hlen Sie ein neues Passwort (mindestens 6 Zeichen) und best&auml;tigen Sie es.
</p>

<?php 
	echo $this->Form->create(false, array('url' => '/users/change_pw/' . $user_id));
	
	echo '<p>Passwort:<br/>';
	echo $this->Form->password('password');
	
	echo '</p><br/>Neues Passwort:<br/>';
	echo $this->Form->password('new_password');
	
	echo '<br/>Neues Passwort best&auml;tigen:<br/>';
	echo $this->Form->password('new_password_check');
	
	echo $this->Form->end('Speichern');
?>