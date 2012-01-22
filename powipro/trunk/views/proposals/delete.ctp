1<h1>Proposal l&ouml;schen</h1>
<h2>Wollen Sie wirklich das Proposal <?php echo $proposal['Proposal']['title']; ?> l&ouml;schen?</h2>
<?php
	echo '<p>' . $this->Html->link('Ja, ich will es löschen', array('controller' => 'proposals',
								'action' => 'delete',
								$proposal['Proposal']['id'],
								'confirm'));
	echo '</p><p>' . $this->Html->link('Zurück zur Homepage', array('controller' => 'users', 'action' => 'home')) . '</p>';
?>
