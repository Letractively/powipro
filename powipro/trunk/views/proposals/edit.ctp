<h1>Proposal bearbeiten</h1>
<p>Hier k&ouml;nnen Sie Ihr Proposal bearbeiten. Das Proposal wird mit den angegebenen Daten, sobald Sie es
einreichen, &uuml;berpr&uuml;ft und eventuell angenommen. Sie erhalten auf jeden Fall ein Best&auml;tigungsmail
an die E-Mail-Adresse angeschickt, die Sie in jenen Bewerbungsdaten angegeben haben, unter denen Sie dieses Proposal erstellt haben.<br/>
Die Daten, die Sie hier angeben, werden auch im Vorlesungsverzeichnis aufscheinen.<br/>
<br/><strong>In das Kommentarfeld k&ouml;nnen Sie zus&auml;tzliche Informationen zum Proposal angeben, die nicht &ouml;ffentlich einsehbar sind.<br/>
<font color="red">F&uuml;r alle anderen Daten geben Sie mit dem Einreichen die Best&auml;tigung, dass Sie &ouml;ffentlich angezeigt werden k&ouml;nnen (VLVZ).</font></strong></p>
<h3>Inhalt und Beschreibung</h3>
<?php
	echo $this->Html->script('ckeditor/ckeditor.js');

	echo $this->Form->create('Proposal', array('url' => array('controller' => 'proposals', 'action' => 'edit', $proposal['Proposal']['applicant_id'], $proposal['Proposal']['id'])));
	
	/*echo $this->Form->input('Proposal.title', array('value' => $proposal['Proposal']['title'],
							'label' => 'Titel'));
	echo $this->Form->input('Proposal.subtitle', array('value' => $proposal['Proposal']['subtitle'],
							   'label' => 'Untertitel'));*/
	echo $this->Form->inputs(array('legend' => 'Proposal bearbeiten',
	'title' => array('label' => 'Titel', 'value' => $proposal['Proposal']['title'], 'size' => 60),
	'english_title' => array('label' => 'Englischer Titel (optional)', 'value' => $proposal['Proposal']['english_title'], 'size' => 60),
	'subtitle' => array('label' => 'Untertitel', 'value' => $proposal['Proposal']['subtitle'], 'rows' => 5, 'cols' => 60),
	'description' => array('label' => 'Aussagekräftige Beschreibung', 'value' => $proposal['Proposal']['description'], 'rows' => 12, 'cols' => 60),
	'comment' => array('label' => 'Geplanter Ablauf, Beurteilungskriterien, Methoden und Kommentare', 'value' => $proposal['Proposal']['comment']),
	));
	
	echo $this->Html->scriptBlock("CKEDITOR.replace('ProposalComment', { extraPlugins : 'bbcode' });");
	
	echo '<h3>Bereich und Kurstyp</h3><p>Wählen Sie einen Bereich und einen Kurstyp für Ihr Proposal aus, ansonsten können Sie es <strong>nicht</strong> einreichen.<br/>'
	. 'Falls Sie keinen Bereich auswählen können, ist im Moment (noch) nicht festgelegt, welche Bereiche welches Kontingent an Lehrveranstaltungen bekommen.<br/>'
	. 'Sie können Ihr Proposal dennoch abspeichern und den Bereich später festlegen.<br/><br/>'
	. '</p><p/>';

	
	$section_options = array();
	foreach ($sections as $section) {
		$section_options[$section['Section']['id']] = $section['Section']['name'];
	}
	echo '<p>Bereich<br/>';
	echo $this->Form->select('Proposal.section_id', $section_options,
				 $proposal['Proposal']['section_id'],
				 array('id' => 'proposalSectionSelect', 'empty' => 'Bitte wählen Sie einen Bereich aus'));

	echo '</p><p>Kurstyp<br/>';
	$course_type_options = array();
	foreach ($course_types as $course_type) {
		$course_type_options[$course_type['CourseType']['id']]
			= $course_type['CourseType']['abbreviation']
			. ' - '	. $course_type['CourseType']['description']
			. ' (' . $course_type['CourseType']['ECTS'] . ' ECTS'
			. ', ' . $course_type['CourseType']['hours'] . ' SStd.'
			. ', ' . $course_type['CourseType']['type'] . ')';
	}
	echo $this->Form->select('Proposal.course_type_id', $course_type_options,
				 $proposal['Proposal']['course_type_id'],
				 array('id' => 'proposalCourseTypeSelect', 'empty' => 'Bitte wählen Sie die Art der Lehrveranstaltung'));
	echo '</p>';
	
	if ($proposal['Proposal']['section_id'] != null) {
		echo '<div id="sectionInfo"><h3 class="title">'
		. $proposal['Section']['abbreviation'] . ' - '
		. $proposal['Section']['name'] . '</h3><h4 class="description">'
		. $proposal['Section']['description'] .'</h4><p class="comment">'
		. $this->Bbcode->parse($proposal['Section']['comment'])
		. '</p></div>';
	} else {
		echo '<div id="sectionInfo" style="display:none;"><h3 class="title">'
		. '</h3><h4 class="description"></h4><p class="comment"></p></div>';
	}
	echo '<p><font color="red"><strong>Vergessen Sie nicht, dass Sie ihr Proposal erst noch einreichen müssen!</strong></font><br/>'
	. 'Die Einreichfunktion erreichen Sie auf der Startseite.<br/></p>';
	
	echo '<h3>Zweite/r Bewerber/in?</h3><p>ID des/der zweiten Bewerber/in (freilassen, falls sie die LV alleine f&uuml;hren wollen): ';
	echo $this->Form->input('second_applicant_id', array('label' => false, 'type' => 'text'));
	echo '<p>Die ID erhalten Sie nur pers&ouml;nlich von der anderen Person: Sie findet diese auf der Startseite. Daf&uuml;r muss Sie ebenfalls angemeldet sein.</p><br/>';
	
	echo $this->Form->submit('Speichern');
	echo $this->Form->end();
	
	
	echo '<p>Sie finden eine Liste über jene Bereiche und Kurstypen inklusive der jeweiligen Beschreibungen, für die Lehrveranstaltungen angeboten werden sollen '
		. 'in der Semester-Detailansicht, indem Sie bei dem gewünschten Semester auf <i>Details</i> klicken.<br/>' 
		. $this->Html->link('Semester-Detailansicht im neuen Fenster öffnen', array('controller' => 'semesters', 'action' => 'overview'), array('target' => '_blank'))
		. '</p><p>Bitte wählen Sie den Bereich sorgfältig aus - Falls Sie ihr Proposal falsch zuordnen, wird es wahrscheinlich <strong>nicht</strong> angenommen werden.<br>'
		. $this->Html->link('Liste aller Bereiche im neuen Fenster öffnen', array('controller' => 'sections', 'action' => 'overview'), array('target' => '_blank'))
		. '</p>';

	$this->Js->get('#proposalSectionSelect')->event('change',
			$this->Js->request('/course_types/json_available/' ,
				array('async' => true,
				      'data' => '{section_id:$("#proposalSectionSelect option:selected").val()}',
				      'method' => 'POST',
				      'dataExpression' => true,
				      'dataType' => 'json',
				      'update' => false,
				      'success' => 'reloadCourseTypeSelect("#proposalCourseTypeSelect", $.parseJSON(data).data);',
				      )));
	
	$this->Js->get('#proposalSectionSelect')->event('change',
		$this->Js->request('/sections/json_info/' ,
			array('async' => true,
		    	  'data' => '{section_id:$("#proposalSectionSelect option:selected").val()}',
			      'method' => 'POST',
			      'dataExpression' => true,
			      'dataType' => 'json',
		    	  'update' => false,
				  'success' => 'setSectionInfo("#sectionInfo", $.parseJSON(data).data);',
	)));		
	
	echo $this->Html->script('proposal_helpers.js');
	echo $this->Js->writeBuffer();

?>

