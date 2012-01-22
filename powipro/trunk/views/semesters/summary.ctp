<h1><?php echo $semester['Semester']['name'] ?></h1>
<?php
	if ($has_passed) {
		?>Im Semester <?php echo $semester['Semester']['name']; ?> wurden
		<?php echo $filed; ?> Proposals eingereicht. Davon wurden insgesamt <?php echo $selected; ?> ausgew&auml;hlt.
		<?php
	} else if ($will_open) {
		echo 'F&uuml;r das ' . $semester['Semester']['name'] . ' beginnt die Einreichfrist am '
		. strftime('%e. %B %Y', strtotime($semester['Semester']['start']))
		. ' und endet am ' . strftime('%e. %B %Y', strtotime($semester['Semester']['deadline']))
		. '<br />Sie k&ouml;nnen in der Zwischenzeit Proposals f&uuml;r dieses Semester zwar anlegen '
		. 'und bearbeiten, aber sie nicht einreichen. Eventuell m&uuml;ssen Sie beim Einreichen auch '
		. 'den angegebenen Studienplanpunkt Ihres Proposals &auml;ndern, sollte es bis zur Einreichfrist '
		. 'zu &Auml;nderungen im Studienplan kommen.';
	} else if ($late) {
		echo '<p>Die Einreichfrist f&uuml; das ' . $semester['Semester']['name'] . ' ist abgelaufen.'
		. '<br />Sie k&ouml;nnen bis zum ' . strftime('%e. %B %Y', strtotime($semester['Semester']['final_deadline']))
		. ' eventuell Proposals nachreichen, doch es ist nicht garantiert, dass diese auch bearbeitet werden.'
		. '<br />Eventuell ist diese Funktion f&uml;r dieses Semester auch deaktiviert.'
		. '<br />Bitte kontaktieren Sie in diesem Fall die Studienprogrammleitung pers&ouml;nlich.</p>'
		. '<p>Bis jetzt wurden ' . $filed . ' Proposals eingereicht.</p>';
	} else if ($open) {
		echo '<p>Im ' . $semester['Semester']['name'] . ' wurden bis jetzt ' . $filed . ' Proposals eingereicht.'
		. '<br />Die Einreichfrist endet am ' . strftime('%e. %B %Y', strtotime($semester['Semester']['deadline']))
		 .'</p>';
	} else {
		echo 'Kleiner Fehler.';
	}
	
	$headers = array('Bereich', 'Kurstyp', 'Vorgesehene Kurse');
	
	?><br/><h3>Kontingent</h3><table><?php 
	
	echo $this->Html->tableHeaders($headers);
	
	foreach ($semester['Contingent'] as $contingent) {
		$cells = array(
			$this->Html->link($contingent['Section']['abbreviation'] . ' - ' . $contingent['Section']['name'],
				array('controller' => 'sections', 'action' => 'view', $contingent['Section']['id'])),
			
			$this->Html->link($contingent['CourseType']['description'] 
				. ' (' . $contingent['CourseType']['ECTS']
				. ' ECTS, ' . $contingent['CourseType']['hours'] . ' SStd.)',
				array('controller' => 'course_types', 'action' => 'view', $contingent['CourseType']['id'])),
				
				$contingent['contingent'],
			);
		
		
		
		echo $this->Html->tableCells($cells);
	}
?></table><?php echo $this->Html->link('Zur Startseite', '/users/home'); ?>
