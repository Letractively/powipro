<?php 

class MessageComponent extends Object {
	private $controller;
	
	public $components = array('Email');
	
	public function startup (&$controller) {
		$this->controller =& $controller;
	}
	
	public function send ($name, $email, $subject, $message, $replacements = null, $attachments = null) {
		$this->Email->reset();
		$this->Email->from = "Call for Proposals <spl21-call-for-proposals@univie.ac.at>";
		$this->Email->to = $name . '<' . $email . '>';
		$this->Email->subject = $subject;
		$this->Email->sendAs = 'html';
		
		if (isset($attachments) && is_array($attachments) && !empty($attachments)) {
			$this->Email->attachments = $attachments;
		}
		
		if (is_array($replacements)) {
			$message = str_replace(array_keys($replacements), array_values($replacements), $message);
		}
		
		$message .= '<br/><br/>HINWEIS: Dies ist eine automatisch generierte Mail. Bitte klicken Sie nicht auf Antworten!<br/>'
		. 'Bei Fragen kontaktieren Sie Ihre Ansprechperson <a href="mailto:manuela.egger@univie.ac.at">Manuela Egger</a>.';
		
		return $this->Email->send($message);
	}
	
	public function getSmtpError () {
		return $this->Email->smtpError;
	}
	
}

?>
