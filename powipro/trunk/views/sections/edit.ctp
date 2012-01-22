<h1>Bereich verwalten</h1>
<p>Bearbeiten Sie Daten zu Bereichen.<br/>
Das Kommentarfeld dienst dazu, Bewerber*innen dabei zu helfen, sich beim Einreichen
eines Proposals f&uuml;r den inhaltlich passenden Bereich zu entscheiden.</p>
<?php 
	echo $this->Html->script('ckeditor/ckeditor.js');

	echo $this->Form->create('Section', array('url' => array('controller' => 'sections', 
								   'action' => 'edit', $section['Section']['id'])));
	
	echo $this->Form->inputs(array('legend' => 'Bereich bearbeiten',
		'abbreviation' => array('label' => 'Abkürzung', 'value' => $section['Section']['abbreviation'], 'size' => 8),
		'name' => array('label' => 'Name', 'value' => $section['Section']['name'], 'size' => 50),
		'description' => array('label' => 'Einzeilige Beschreibung', 'value' => $section['Section']['description'], 'size' => 50),
		'comment' => array('label' => 'Kommentare', 'value' => $section['Section']['comment']),
	));
	echo $this->Form->end('Speichern', true);

	echo $this->Html->scriptBlock("CKEDITOR.replace('SectionComment', { extraPlugins : 'bbcode' });");
?>