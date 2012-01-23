<h1>Bewerbungsunterlagen von <?php echo $applicant['Applicant']['name'] . ' ' . $applicant['Applicant']['last_name']; ?></h1>

<?php
if ($editable) {
	echo '<p>' . $this->Html->link('Daten bearbeiten', array('controller' => 'applicants',
			'action' => 'edit', $applicant['Applicant']['id'])) . '</p>';
}
?>

<?php if ($detail && isset($applicant['Applicant']['user_id'])) { ?>
<p>Diese Bewerbungsunterlagen wurden vom User <?php echo $applicant['User']['name'] . ' ' . $applicant['User']['last_name']; ?> erstellt.<br/>
E-Mail des Benutzers: <?php echo $this->Html->link($applicant['User']['email'], 'mailto:' . $applicant['User']['email']); ?>
</p>
<?php } ?>
<h3>Pers&ouml;nliche Daten</h3>
<p>
<?php echo $applicant['Applicant']['title']; ?> <?php echo $applicant['Applicant']['name'] . ' ' . $applicant['Applicant']['last_name'] ?><br/>
Akademische(r) Grad(e): <?php echo $applicant['Applicant']['degrees']; ?><br/>
Habilitiert: <?php echo $applicant['Applicant']['habilitated'] == 1 ? 'Ja' : 'Nein'; ?><br/>
<?php if ($applicant['Applicant']['habilitated'] == 1 ) { ?>
Habilitationsdatum: <?php echo $applicant['Applicant']['habilitation_date']; ?><br/>
Habilitationsort: <?php echo $applicant['Applicant']['habilitation_place']; ?><br/>
<?php } ?>
</p>

<h3>Eingereichte Proposals</h3>
<?php if (count($proposals) > 0) { ?>
<p>Insgesamt <?php echo $this->Paginator->counter(array('format' => '%count%')); ?> Proposal(s)</p>
<center><div class="paging"><?php
 echo $this->Paginator->prev('<< Zurück ', null, null, array('class' => 'disabled')) . '&nbsp;'
	 . $this->Paginator->numbers() . '&nbsp;'
	 . $this->Paginator->next(' Weiter >>', null, null, array('class' => 'disabled')); 
?></div></center>
<table>
<?php
	$tableHeaders = array(
		$this->Paginator->sort('Titel', 'Proposal.title'),
	 	$this->Paginator->sort('Untertitel', 'Proposal.subtitle'),
		$this->Paginator->sort('Semester', 'FiledSemester.name'),
		$this->Paginator->sort('Bereich', 'Section.abbreviation'),
		'Aktionen',
	);
	
	echo $this->Html->tableHeaders($tableHeaders);

	$counter = 0;
	foreach ($proposals as $proposal) {

		if ($proposal['Proposal']['filed_for'] == null) continue;
		
		$tableCells = array(
			$proposal['Proposal']['title'],
			$proposal['Proposal']['subtitle'],
			$proposal['FiledSemester']['name'],
			$proposal['Section']['abbreviation'] . ' ' . $proposal['Section']['name'],
			$this->Html->link('Ansehen', array('controller' => 'proposals',
				'action' => 'view', $proposal['Proposal']['id'])),
		);
		
		echo $this->Html->tableCells($tableCells);
		$counter++;
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
<?php } else echo 'Keine'; ?>

<h3>Akademische Daten</h3>
<h4>Publikationen</h4>
<p><?php echo $this->Bbcode->parse($applicant['Applicant']['publications']); ?></p>
<h4>Universit&auml;re Lehrerfahrung</h4>
<p><?php echo $this->Bbcode->parse($applicant['Applicant']['university_teaching']); ?></p>
<h4>Andere Lehrerfahrung</h4>
<p><?php echo $this->Bbcode->parse($applicant['Applicant']['general_teaching']); ?></p>

<?php if ($detail) { ?>
<h3>Zus&auml;tzliche Daten</h3>
<p>Sozialversicherungsnummer: <?php echo $applicant['Applicant']['social_security']; ?><br/>
Nationalit&auml;t: <?php echo $applicant['Applicant']['nationality']; ?><br/>
Bundesdienstverh&auml;ltnis: <?php echo $applicant['Applicant']['federal_employment'] == 1 ? 'Ja' : 'Nein'; ?><br/>
</p>
<?php } ?>
<h3>Kontaktdaten</h3>
<p>Telefon: <?php echo $applicant['Applicant']['phone'] ?><br/>
Mobil: <?php echo $applicant['Applicant']['mobile']; ?><br/>
E-Mail des Bewerbers: 
<?php echo $this->Html->link($applicant['Applicant']['email'], 'mailto:' . $applicant['Applicant']['email']); ?><br/>
Adresse:<br/><?php echo nl2br($applicant['Applicant']['address']); ?>
</p>
<p><?php echo isset($back_link) ? $this->Html->link('Zurück', $back_link) : $this->Html->link('Zurück zur Startseite', '/users/home');?></p>