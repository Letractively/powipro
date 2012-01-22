<h1>Rechteverwaltung für <?php echo $user['User']['name'] . ' ' . $user['User']['last_name']; ?></h1>
<p>
Dieser User ist in der Gruppe <?php echo $user['Group']['name'] ?>
</p>
<?php
	echo $this->Form->create('User', 
		array('url' => array('controller' => 'users', 'action' => 'editPermissions', $user['User']['id'])));

	$group_options = array();
	foreach ($groups as $group) {
		$group_options[$group['Group']['id']] = $group['Group']['name'];
	}
	
	echo '<p>Gruppenzugehörigkeit ändern<br/>';
	echo $this->Form->select('Group.id', $group_options, $user['Group']['id'],
		array('empty' => false)) . '</p>';
	
	echo $this->Form->end('Speichern');
?>
<p><?php echo isset($back_link) ? $this->Html->link('Zurück', $back_link) : $this->Html->link('Zurück zur Startseite', '/users/home');?></p>