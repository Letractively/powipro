<table><tr><th></th><th>Fortlaufende Nummer</th><th>Semester</th>
<th>Teilgebiet (Abkürzung)</th><th>Teilgebiet (Bezeichnung)</th>
<th>'Teilgebiet (Abk. & Bez.)</th><th>zusätzlich anrechenbar in</th>
<th>LV-Nummer</th><th>Name</th><th>Bezeichnung der Lehrveranstaltung</th>
<th>Englische Bezeichnung der Lehrveranstaltung</th><th>Englischer LV-Titel (i3v)</th>
<th>LV-Titel (i3v)</th><th>ECTS</th><th>Art der LV</th><th>Stunden</th>
<th>Gewichtung</th><th>Budget (x/bx/mx/doppelt)</th>
<th>Vergütungscode</th><th>Anmerkungen / Freigeschaltet</th><th>Hörsaal Wünsche</th>
<th>Hörsaal und Zeiten</th><th>Gewichtete Stunden</th></tr>
	
<?php
	
	$number = 0;
	foreach ($proposals as $proposal) {
		$row = $number+1;
		
		$coapplicants = '';
		if (!empty($proposal['Coapplicant'])) {
			foreach ($proposal['Coapplicant'] as $coapplicant) {
				$coapplicants .= '/'
				. mb_strtoupper($coapplicant['Applicant']['last_name']) . ' '
				. $coapplicant['Applicant']['name'];
			}
		}
			
		$cells = array('1', $number++,
			 $proposal['SelectedSemester']['name'],
			 $proposal['Section']['abbreviation'],
			 $proposal['Section']['name'],
			 '=CONCATENATE(D2;" ";E2)',
			 '', '',
			 mb_strtoupper($proposal['Applicant']['last_name'])
			 	. ' ' . $proposal['Applicant']['name'] . $coapplicants,
			 $proposal['Proposal']['title'],
			 $proposal['Proposal']['english_title'],
			 '=CONCATENATE(D' . $row . ';G' . $row . ';": ";O' . $row . ';" ";K' . $row . ')',
			 '=CONCATENATE(D' . $row . ';G' . $row . ';": ";O' . $row . ';" ";J' . $row . ')',
			 $proposal['CourseType']['ECTS'],
			 $proposal['CourseType']['abbreviation'],
			 $proposal['CourseType']['hours'],
			 '0', '', '', '', '', '', '=P' . $row . '*Q' . $row,
		);
			 
		echo $this->Html->tableCells($cells);
	}
	
	foreach ($free_contingents as $contingent) {
		
			
		for ($i = 0; $i < $contingent['Contingent']['contingent'] - $contingent['Contingent']['booked']; ++$i) {

			$row = $number+1;
			$cells = array('0', $number++,
			$contingent['Semester']['name'],
			$contingent['Section']['abbreviation'],
			$contingent['Section']['description'],
				'=CONCATENATE(D2;" ";E2)',
				'', '', '', '', '',
				'=CONCATENATE(D' . $row . ';G' . $row . ';": ";O' . $row . ';" ";K' . $row . ')',
				'=CONCATENATE(D' . $row . ';G' . $row . ';": ";O' . $row . ';" ";J' . $row . ')',
			$contingent['CourseType']['ECTS'],
			$contingent['CourseType']['abbreviation'],
			$contingent['CourseType']['hours'],
				 '0', '', 'Vergütungscode', '', '', '',
				 '=P' . $row . '*Q' . $row,
			);

			echo $this->Html->tableCells($cells);
		}
	}
	
?></table>























