<h1>Willkommen auf Ihrer persönlichen Startseite, <?php echo $username ?>!</h1>
<p>Hier können Sie Ihre Bewerbungsunterlagen erstellen und Ihre Proposals verwalten</p>
<small><?php echo $this->Html->link('Hilfe, ich kenne mich nicht aus!', array('controller' => 'pages', 'action' => 'help')); ?></small>

<?php if (empty($applicants)) { ?>
<h3>Schritt 1: Bewerbungsunterlagen</h3>
<p>Sie können mit denselben Bewerbungsunterlagen (Lebenslauf, Kontaktadressen, ...) über mehrere Semester hinweg Proposals einreichen.
Diese Daten können Sie laufend aktualisieren, auch wenn gerade kein Call-for-Proposals offen ist.<br/>
Damit haben Sie den Vorteil, Bewerbungsunterlagen nur einmal an dieser zentralen Stelle zu erstellen, die Sie dann
immer wieder verwenden können</p>

<?php echo '<p>' . $this->Html->link('Klicken Sie hier, um jetzt Ihre Bewerbungsdaten auszufüllen!',
array('controller' => 'applicants',
			      'action' => 'edit')) . '</p>';
?>

<?php } else { ?>
<h3>Proposals erstellen und einreichen</h3>
<p>Mit den ausgefüllten Bewerbungsunterlagen können Sie nun Proposals schreiben.</p>

<p>Jedes Proposal muss einem bestimmten <?php $this->Html->link('Bereich', array('controller' => 'sections', 'action' => 'overview')); ?> zugewiesen werden.
Sie können dabei Ihre Proposals zwischenspeichern und ein anderes Mal weiterschreiben.
Solange Sie Ihr Proposal nicht <strong>einreichen</strong>, hat darauf niemand Zugriff - Sie können es auch problemlos löschen.</p>

<p>Innerhalb einer gewissen Einreichfrist (siehe <?php echo $this->Html->link('Semesterübersicht', array('controller' => 'semesters', 'action' => 'overview')); ?>)
können Sie dann ihre Proposals einreichen. <br/>

<strong>Vorsicht:</strong> Einmal eingereichte Proposals können nicht mehr von Ihnen bearbeitet werden. Wenden Sie sich in diesem Fall an die Administration.</p>
<p>Vergessen Sie nicht, ihre Proposals innerhalb dieser Frist einzureichen. Andernfalls werden ihre Einreichungen <strong>nicht berücksichtigt</strong></p>

<h3>Ihre Daten</h3>
<?php

	if (empty($applicants)) {
		echo '<p>' . $this->Html->link('Bewerbungsdaten erstellen...', 
			array('controller' => 'applicants',
			      'action' => 'edit')) . '</p>';
	} else {
		echo '<p>Sie haben ' . count($applicants) 
		     . ' Bewerbungsunterlage(n) hochgeladen und insgesamt '
		     . $proposals_count . ' Proposal(s) eingereicht.</p>'
			 .'<p>Sie können zusätzliche Bewerbungsdaten erstellen oder die bereits '
			 .'vorhandenen editieren.';
		
		echo '<p>' . $this->Html->link('Neue Bewerbungsdaten erstellen...',
			array('controller' => 'applicants',
			      'action' => 'edit')) . '</p>';
	}

	foreach ($applicants as $applicant_id => $applicant) {
		echo '<table>';
		echo $this->Html->tableHeaders(array('ID', 'Name', 'Email', 'Telefon', 'Proposals'));
		
		$applicant['proposals'] = '';

		if (!empty($proposals[$applicant_id])) {
			foreach ($proposals[$applicant_id] as $proposal) {
				$applicant['proposals'] .= $proposal['title'];
				
				if ($proposal['filed_for'] != null) {
					$applicant['proposals']	.= ' - Eingereicht';
				} 
				// else {
				if ($permissions[$proposal['id']]['delete']) {
					$applicant['proposals'] .= ' - ' . $this->Html->link('Löschen',
						array('controller' => 'proposals', 'action' => 'delete', $proposal['id']));
				}
				
				//if ($editable[$proposal['id']] || $proposal['filed_for'] == null) {
				if ($permissions[$proposal['id']]['update']) {
					$applicant['proposals'] .= ' - ' . $this->Html->link('Bearbeiten',
						array('controller' => 'proposals', 'action' => 'edit',
						$applicant_id, $proposal['id']));
				}
				
				//if ($proposal['filed_for'] == null) {
				if ($permissions[$proposal['id']]['update']) {
					$applicant['proposals'] .= ' - ' . $this->Html->link('Einreichen',
						array('controller' => 'proposals', 'action' => 'view', $proposal['id']));
				} else {
					$applicant['proposals'] .= ' - ' . $this->Html->link('Ansehen', 
						array('controller' => 'proposals', 'action' => 'view', $proposal['id']));
				}
				
				$applicant['proposals'] .= '<br/>';
			}
			
		}

		$applicant['proposals'] .= $this->Html->link('Neues Proposal',
			 array('controller' => 'proposals', 
				'action' => 'edit',
				$applicant_id))
			 . ' als ' . $applicant['name']  . ' einreichen...';

		echo $this->Html->tableCells($applicant);
		
		foreach ($coapplicants[$applicant_id] as $coapplicant_invitations) {
			echo '<tr><td colspan="5">';

			if ($coapplicant_invitations['Coapplicant']['accepted'] == 0) {
				echo $coapplicant_invitations['Proposal']['Applicant']['name'] . ' '
				. $coapplicant_invitations['Proposal']['Applicant']['last_name']
				. ' m&ouml;chte das Proposal '
				. $coapplicant_invitations['Proposal']['title']
				. ' mit Ihnen gemeinsam einreichen. '
				. $this->Html->link('OK, ich will', array('controller' => 'proposals',
				'action' => 'coaccept', $coapplicant_invitations['Coapplicant']['id']))
				. ' - '
				. $this->Html->link('Nein, ablehnen', array('controller' => 'proposals',
				'action' => 'corefuse', $coapplicant_invitations['Coapplicant']['id']));
				
			} else {
				echo 'Mit ' . $coapplicant_invitations['Proposal']['Applicant']['name']
				. ' ' . $coapplicant_invitations['Proposal']['Applicant']['last_name'] 
				. ' haben Sie gemeinsam das Proposal '
				. $coapplicant_invitations['Proposal']['title'] 
				. ' eingereicht. '
				. $this->Html->link('Proposal ansehen ', array('controller' => 'proposals',
				'action' => 'view', $coapplicant_invitations['Coapplicant']['proposal_id']));
			}
			
			echo '</td></tr>';
		}

		echo '<tr><td colspan="5">' . $this->Html->link('Obige Bewerbungsdaten bearbeiten...', 
					array('controller' => 'applicants',
					      'action' => 'edit',
					      $applicant_id)) . '</td></tr>';

		echo '</table>';
	}	
}

if (!empty($applicants)) {

?>
<p><strong>Vergessen Sie nicht, Ihre Proposals innerhalb der Einreichfrist einzureichen!<br/>
Klicken Sie dazu bei jenem Proposal, dass Sie einreichen möchten, auf den Link "Einreichen".<br/>
</strong>
Sie kommen dann zu einer Übersichtseite für Ihr Proposal. Kontrollieren Sie dort alle Daten
auf ihre Richtigkeit und wählen Sie ein passendes Semester, für das Sie Ihr Proposal einreichen wollen!</p><br/>

<?php } ?> 
<h3>Account</h3>
<p>Falls Sie Ihr Passwort ändern wollen, klicken Sie auf den folgenden Link:
<?php echo $this->Html->link('Passwort ändern', '/users/change_pw/' . $user_id) ?></p>

<small>Falls Sie Datenschutzbedenken haben oder nie mehr Proposals einreichen wollen, können Sie ihren Account 
<?php echo $this->Html->link('löschen', array('controller' => 'users', 'action' => 'delete', $user_id), null, "Wollen Sie Ihren Account wirklich löschen? Sie können dann nie mehr auf die von Ihnen bis jetzt erstellten Proposals und Bewerbungsunterlagen zugreifen!"); ?>
.</small>

