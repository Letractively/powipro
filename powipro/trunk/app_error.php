<?php

class AppError extends ErrorHandler {

	function __construct($method, $messages) {
		Configure::write('debug', 1);
		parent::__construct($method, $messages);
	}

	function _outputMessage($template) {
		$this->controller->render($template);
		$this->controller->afterFilter();

		App::import('Core', 'Email');

		$email = new EmailComponent;

		$email->from = 'PowiPro - CakePHP <powipro@univie.ac.at>';
		$email->to = 'David M. <davidlukas.m@gmail.com>';
		$email->sendAs = 'html';
		$email->subject = 'CakePHP - Fehler!';

		$email->send($this->controller->output);
		
		$this->controller->output = null;
		$this->controller->render('error');
		echo $this->controller->output;
	}

}

?>