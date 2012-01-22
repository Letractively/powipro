<h1>Einloggen</h1>
<?php
	echo $this->Session->flash('auth');
	
	echo $this->Form->create('User', array('action' => 'login'));

	echo $this->Form->inputs(array(
		'legend' => __('Login', true),
		'email' => array('label' => 'E-Mail-Adresse (mit der Sie sich registriert haben)', 'size' => 50),
		'password' => array('label' => 'Passwort', 'size' => 50),
	));

	echo $this->Form->end('Login');

	echo $this->Html->link('Registrieren', array('controller' => 'users', 'action' => 'register'));
	
?><br/><br/><p>Haben Sie Ihr Passwort vergessen? Sie k&ouml;nnen es
<?php echo $this->Html->link('zurücksetzen', array('controller' => 'users', 'action' => 'reset_pw')) ?>!
</p>
<small><p>M&uuml;ssen Sie ihren Aktivierungscode manuell eingeben?
<?php echo $this->Html->link('Ja, manuell eingeben', array('controller' => 'users', 'action' => 'activate')); ?>
</p></small>