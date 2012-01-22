<h1>User-Übersicht</h1>
<?php
	echo '<p>Filter nach beliebigen Kriteren (Wildcard erlaubt)';
	echo $this->Form->create(false, array('controller' => 'users', 'action' => 'overview'));
	if (isset($filter)) {
		echo $this->Form->text('filter', array('value' => $filter));
	} else {
		echo $this->Form->text('filter');
	}
	echo $this->Form->end('Filtern');
	echo '</p>';
?>

<center><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php 

	$tableHeaders = array(
		$this->Paginator->sort('Name', 'User.name'),
		$this->Paginator->sort('E-Mail', 'User.email'),
		$this->Paginator->sort('Gruppe', 'Group.name'),
	'Passwort', 'Unterlagen', 'Aktionen', 'Aktiv');
	echo $this->Html->tableHeaders($tableHeaders);
	
	foreach ($users as $user) {
		$tableCells = array(
			$user['User']['name'] . ' ' . $user['User']['last_name'],
			$this->Html->link($user['User']['email'], 'mailto:' . $user['User']['email']),
			$this->Html->link($user['Group']['name'], array('controller' => 'users',
				'action' => 'editPermissions', $user['User']['id'])),
		);
		
		$tableCells[] =
			$this->Html->link('Reset', '/users/reset_pw/' . $user['User']['id'])
			. '<br/>'
			. $this->Html->link('Ändern', '/users/change_pw/' . $user['User']['id']);
		
		$tableCells[] = $this->Html->link('Nicht eingereichte Proposals',
			array('controller' => 'users', 'action' => 'proposals', $user['User']['id']));
		
		$tableCells[] =
			$this->Html->link('User löschen', array('controller' => 'users',
			'action' => 'delete', $user['User']['id']), null, 
			'Wollen Sie ' . $user['User']['name'] . ' ' . $user['User']['last_name'] . ' wirklich löschen?');
		
		if ($user['User']['active'] == 1) {
			$tableCells[] = 'Ja';
		} else {
			$tableCells[] = $this->Html->link('Nein', 
				array('controller' => 'users', 'action' => 'activate',
					  $user['User']['id'], $user['User']['activation_code']));
		}
		
		echo $this->Html->tableCells($tableCells);
	}

?>
</table>
<center><p><div class="paging"><?php
echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
. $this->Paginator->numbers() . '&nbsp;'
. $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled'));
?></div></p>
<p>Sie befinden sich auf
<?php echo $this->Paginator->counter(array('format' => 'Seite %page% von %pages%')); ?>
</p></center>
