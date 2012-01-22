<h1>PoWiPro - Hilfe</h1>
<p>PowiPro ist das neue Online-System der SPL Politikwissenschaft,
um Proposals f&uuml;r Lehrveranstaltungen zu verwalten.</p>
<p>Diese Seite soll Ihnen eine Hilfestellung dabei sein, sollten Sie selbst
einem <i>call-for-proposals</i> folgen und ein Proposal einreichen wollen.</p>

<h3>Inhalt</h3>
<ol>
<li><a href="#prozedere">Das Prozedere</a></li>
<li><a href="#registrieren">Registrieren</a></li>
<li><a href="#bewerben">Bewerbungsunterlagen erstellen</a></li>
<li><a href="#proposals">Proposals erstellen</a></li>
<li><a href="#einreichen">Proposals einreichen</a></li>
<li><a href="#datenschutz">Datenschutz</a></li>
<li><a href="#hilfe">Zu kompliziert?</a></li>
</ol>

<h2><a name="prozedere">1. Das Prozedere</a></h2>
<p>Das Prozedere, um Proposals einzureichen ist grunds&auml;tzlich viergegliedert und
soll f&uuml;r Sie so einfach wie m&ouml;glich sein.</p>
<ol>
<li>Zuerst m&uuml;ssen Sie sich <?php echo $this->Html->link('registrieren', array('controller' => 'users', 'action' => register)); ?>.
Dies m&uuml;ssen Sie nur einmal tun, egal wie viele Proposals Sie erstellen wollen! Ihr Account bleibt auch &uuml;ber den aktuellen Call hinaus aktiv.</li>
<li>Wenn Sie registriert sind, m&uuml;ssen Sie <a href="#bewerben">Bewerbungsunterlagen erstellen</a>.
Diese beinhalten grunds&auml;tzliche Daten zu Ihrer Person. Auch diese Bewerbungsunterlagen m&uuml;ssen
sie nur einmal erstellen - Sie k&ouml;nnen Sie im nachhinein auch beliebig oft &auml;ndern und erweitern.</li>
<li>Dann k&ouml;nnen Sie <a href="#proposals">Proposals schreiben</a>. Ein Proposal k&ouml;nnen Sie auch dann schreiben,
wenn gerade kein Call offen ist - Sie reichen es dann einfach sp&auml;ter ein (sobald die Einreichfrist beginnt)!</li>
<li>Zuletzt m&uuml;ssen Sie Ihr Proposal noch <a href="#einreichen">einreichen</a>. Das d&uuml;rfen Sie auf keinen
Fall vergessen - denn solange Sie das Proposal nicht eingereicht haben, k&ouml;nnen nur Sie es sehen.</li>
</ol>
 
<h2><a name="registrieren">2. Registrieren</a></h2>
<p>Zum Registrieren m&uuml;ssen Sie eine g&uuml;ltige E-Mail-Adresse, ein Passwort sowie Ihren Namen angeben.<br/>
An diese E-Mail-Adresse wird Ihnen eine Nachricht mit einem Aktivierungslink geschickt. Solange
Sie ihren Account nicht aktivieren, k&ouml;nnen Sie sich nicht einloggen!<br />
Sollten Sie keine E-Mail bekommen oder ihren Account nicht aktivieren k&ouml;nnen, kontaktieren Sie bitte
direkt die Ansprechperson bei der SPL!</p><br/>
Wenn Sie Ihren Account aktiviert haben, k&ouml;nnen Sie sich 
<?php echo $this->Html->link('einloggen', array('controller' => 'users', 'action' => 'login')); ?>.<br/>
<p>Sobald Sie eingeloggt sind, haben Sie Zugriff auf alle weiteren Funktionen!</p>

<p>Wenn Sie eingeloggt sind, befinden Sie sich zun&auml;chst auf der
<?php echo $this->Html->link('Startseite', array('controller' => 'users', 'action' => 'home')); ?>.
Dort finden Sie kurze Hilfetexte sowie einen &Uuml;berblick &uuml;ber alle Ihre Bewerbungsunterlagen
und Proposals. Von dieser Seite aus k&ouml;nnen Sie Ihre Daten bearbeiten, neue Proposals erstellen,
Proposals einreichen und Ihre eingereichte Proposals ansehen.</p> 

<h2><a name="bewerben">3. Bewerben</a></h2>
<p>Nun m&uuml;ssen Sie als erstes Ihre 
<?php echo $this->Html->link('Bewerbungsunterlagen erstellen', array('controller' => 'applicants', 'action' => 'edit')); ?>.

Auf dieser Seite f&uuml;llen Sie allgemeine Angaben zu Ihrer Person aus, auf deren Basis
(unter anderem) entschieden wird, ob Ihr Proposal angenommen wird.</p><br/>

<p>Sie k&ouml;nnen auch mehrere Bewerbungsunterlagen erstellen, etwa falls
Sie f&uuml;r ein Proposal bestimmte pers&ouml;liche Daten in einer anderen Reihenfolge
oder anders formuliert darstellen wollen.</p> 

<p>Im Normalfall sollte dies aber nicht n&ouml;tig sein, da Sie einmal erstellte
Bewerbungsunterlagen sp&auml;ter immer wieder &uuml;berarbeiten k&ouml;nnen - falls Sie
etwa knapp nach Ende der Einreichfrist noch eine zus&auml;tzliche Ver&ouml;ffentlichung
dazuschreiben wollen, ist dies kein Problem!</p>

<p>Die <strong>E-Mail-Adresse</strong>, die Sie hier angeben, wird dazu verwendet, Ihnen
mitzuteilen, ob Ihr Proposal angenommen oder abgelehnt wurde - andere Benachrichtigungen,
die Proposals betreffen, die Sie mit diesen Bewerbungsunterlagen erstellt haben, werden
Ihnen ebenfalls an die hier angegebene E-Mail-Adresse geschickt.</p>
<p>Diese Adresse muss <strong>nicht</strong> die gleiche sein, wie jene, die Sie zum
Einloggen verwendet haben!</p>

<h2><a name="proposals">4. Proposals schreiben</a></h2>
<p>Sobald Sie Bewerbungsunterlagen erstellt haben, k&ouml;nnen Sie anfangen,
Proposals zu schreiben - auf der 
<?php echo $this->Html->link('Startseite', array('controller' => 'users', 'action' => 'home')); ?>
finden Sie den Link dazu.</p>

<p>Als erstes geben Sie allgemeine Daten zu der Lehrveranstaltung an, die Sie halten 
m&ouml;chten - wie Titel, englischer Titel, eine Beschreibung, etc. - Diese Daten erscheinen
letzten Endes auch im Vorlesungsverzeichnis. Informationen, die Sie zus&auml;tzliche f&uuml;r
Ihre Bewerbung hinzuf&uuml;gen wollen, k&ouml;nnen Sie im Kommentarfeld angeben.</p><br/>

<p>Als letztes m&uuml;ssen Sie einen Bereich und einen Kurstyp ausw&auml;hlen.<br/>
<strong>Erschrecken Sie sich nicht, wenn keine Bereiche ausgew&auml;hlt werden k&ouml;nnen!</strong><br/>
Dies passiert dann, wenn die Einreichfrist f&uml;r Proposals noch nicht begonnen hat,
da dann noch nicht feststeht, f&uml;r welche Bereiche &uuml;berhaupt Proposals gesucht werden.
</p>

<h3>Proposal zu zweit</h3>
<p>Es ist m&ouml;glich, ein Proposal zu zweit einzureichen.<br/>
Daf&uuml;r m&uuml;ssen beide Personen registriert sein und Bewerbungsunterlagen erstellt haben.</p>

<p>Eine der beiden Personen muss das Proposal formulieren und erstellen (Autor*in), diese Person
wird auch als erste angezeigt, wenn sich Bereichsverantwortliche Proposals ansehen.</p><br/>

<p>Die zweite Person muss dann der ersten Person die ID (Identifikationsnummer) einer Ihrer Bewerbungsunterlagen
zukommen lassen. Die ID f&uuml;r Bewerbungsunterlagen findet man auf der
<?php echo $this->Html->link('Startseite', array('controller' => 'users', 'action' => 'home')); ?>
als erste Spalte bei den Daten der jeweiligen Bewerbungsunterlagen.</p>

<p>Der/die Autor*in muss nun diese ID bei seinem/ihrem Proposal eingeben - Das Eingabefeld kann &uuml;ber
die Bearbeiten-Ansicht des jeweiligen Proposals erreicht werden (ganz unten auf der jeweiligen Proposal-Seite).</p>

<p>Die zweite Person sieht nun auf ihrer Startseite bei den Bewerbungsunterlagen, deren ID
sie weitergegeben hat, eine Einladung, die Sie nun noch best&auml;tigen oder ablehnen muss.</p><br/>

<p>Beide Personen k&ouml;nnen dabei nicht die jeweiligen Bewerbungsunterlagen der anderen Person
sehen, sondern nur das Proposal - wobei nur der/die Autor*in es auch bearbeiten und einreichen kann.
</p>

<h2><a href="einreichen">5. Proposals einreichen</a></h2>
<p>Wenn Sie ein Proposal erstellt haben, m&uuml;ssen Sie, bevor Sie es einreichen k&ouml;nnen,
einen 
<?php echo $this->Html->link('Bereich (Studienplanpunkt)', array('controller' => 'sections', 'action' => 'overview')); ?>
und einen 
<?php echo $this->Html->link('Kurstyp (PS, SE, VO)', array('controller' => 'course_types', 'action' => 'overview')); ?>
ausw&auml;hlen. &Uuml;ber die hier angef&uuml;hrten Links kommen Sie zu einem &Uuml;berblicksansicht daf&uuml;r.<br/></p>

<p>Sie k&ouml;nnen sich auch jene Semester ansehen, f&uuml;r die gerade die Einreichfrist offen ist: 
<?php echo $this->Html->link('offene Semester', array('controller' => 'semesters', 'action' => 'overview')); ?><br/>
Dort finden Sie Informationen &uuml;ber die Fristen und die geplanten Kontingente an Lehrveranstaltungen f&uuml;r
bestimmte Bereiche, wobei sich die Kontingente im Lauf der Einreichfrist auch noch &auml;ndern k&ouml;nnen.</p>

<p><strong>Sollten Sie eine Bereich-Kurstyp-Kombination gew&auml;hlt haben, f&uuml;r die es kein Kontingent gibt,
k&ouml;nnen Sie Ihr Proposal nicht einreichen!</strong></p>

<p>Um das Proposal schlie&szlig;lich einzureichen, gehen Sie auf die
<?php echo $this->Html->link('Startseite', array('controller' => 'users', 'action' => 'home')); ?>
und w&auml;hlen den Link zum Einreichen des jeweiligen Proposals.<br/>
Sie kommen dann zu einer &Uuml;bersicht Ihres Proposals, wie es auch die Bereichsverantwortlichen
sehen werden (ohne die M&ouml;glichkeit, es zu bearbeiten) - Kontrollieren Sie nun noch einmal
die Daten auf ihre Richtigkeit und w&auml;hlen Sie sich in der Dropdown-Liste das Semester, f&uuml;r
das Sie Ihr Proposal einreichen wollen.</p>

<p><strong>Mit einem Klick auf Einreichen haben Sie Ihr Proposal eingereicht und k&ouml;nnen es danach nicht mehr bearbeiten!</strong></p>

<p>Sollten Sie dennoch einen Fehler entdecken, kontaktieren Sie bitte die SPL, die ihnen einen tempor&auml;ren 
Schreibzugriff auf das bereits eingereichte Proposal erlauben kann - Sobald das getan wird, werden Sie per
E-Mail benachrichtigt.</p>

<h2><a href="datenschutz">6. Datenschutz</a></h2>
<p>Sie k&ouml;nnen Ihren Account jederzeit l&ouml;schen.</p>
<p>Einmal eingereichte Proposals bleiben jedoch im System vorhanden, um die Zeitreihe aufrecht zu erhalten.</p><br/>

<p>Alle Ihre pers&ouml;nlichen Daten (E-Mail, SV-Nummer, Adresse, Passw&ouml;rter) werden jedoch verschl&uuml;sselt gespeichert - Zugriff
darauf haben Bereichsverantwortliche und die SPL selbst. Sollten Sie Bewerbungsunterlagen und/oder Proposals nach 
Einreichen l&ouml;schen wollen, kontaktieren Sie bitte die SPL.</p>

<p>Auf Ihre Unterlagen haben nur Sie Zugriff, solange bis Sie ein Proposal einreichen.<br/>
Sie d&uuml;rfen auch keine fremden Proposals sehen, au&szlig;er, Sie sind Zweitbewerber*in daf&uuml;r (siehe oben).
</p>

<p>Auf die E-Mail-Adresse, mit der Sie sich registriert haben, hat nur die SPL Zugriff, 
auf die E-Mail-Adressen Ihrer Bewerbungsunterlagen sowohl SPL als auch Bereichsverantwortliche.</p>

<h2><a href="hilfe">7. Hilfe</a></h2>
<p>Wir sind bem&uuml;ht, Ihnen die Arbeit mit Proposals zu erleichtern.<br/>
Sollten Sie Feedback haben oder Probleme mit dem Prozedere, kontaktieren Sie die SPL.
</p><p>
Kontaktadressen:<br/>
<pre>manuela [PUNKT] egger [AT] univie.ac.at</pre>
<pre>regina [PUNKT] koepl [AT] univie.ac.at</pre>