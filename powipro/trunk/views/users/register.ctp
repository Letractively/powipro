<h1>Registrieren</h1>

<?php
	if (!isset($email)) {

		?>		
<p>Geben Sie ihre pers&ouml;nlichen Daten an.<br/>
An die hier angegebene E-Mail-Adresse wird ein Aktivierungscode verschickt, 
kontrollieren Sie Ihre Daten auf Richtigkeit!<br/>
Dies sind nicht Ihre Bewerbungsdaten - Diese k&ouml;nnen Sie erst nach ihrer Account-Aktivierung
eingeben. Geben Sie also hier eine E-Mail-Adresse an, die sie auch regelm&auml;&szlig;ig benutzen!</p>
		<?php
		
		echo $this->Session->flash('auth');

		echo $this->Form->create('User', array('action' => 'register'));

		echo $this->Form->inputs(array(
		'legend' => 'Registrieren',
		'email' => array('label' => 'E-Mail (wird zum Einloggen verwendet)', 'size' => 50),
		'password' => array('label' => 'Selbst gewähltes Passwort (min. 5 Zeichen)', 'size' => 50),
		'password_check' => array('label' => 'Passwort zur Bestätigung erneut eingeben', 'size' => 50, 'type' => 'password', 'value' => ''),
		'name' => array('label' => 'Vorname', 'size' => 50),
		'last_name' => array('label' => 'Nachname', 'size' => 50),
		));

		echo $this->Form->end('Registrieren');

	} else {
?>
<h2>Sie haben sich erfolgreich registriert!</h2>>
<strong>Damit Sie ein Proposal einreichen können, müssen Sie Ihr Konto erst noch aktivieren!</strong> 
<p>Es wurde Ihnen an die Adresse <?php echo $email ?> eine E-Mail dem Aktivierungscode geschickt.<br/>
Öffnen Sie diese E-Mail und klicken Sie auf den dort angegeben Link, um Ihr Konto zu aktivieren.</p>
<p>Dieses Prozedere ist notwendig, um die Echtheit Ihrer E-Mail-Adresse zu überprüfen.</p>
<p>Hilfe zum weiteren Vorgehen finden Sie auf unserer 
<?php echo $this->Html->link('Hilfeseite', array('controller' => 'pages', 'action' => 'help')); ?>
</p> 
<?php } ?>