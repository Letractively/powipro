<?php

App::import('Sanitize');
class UsersController extends AppController {

	public $name = 'Users';
	public $uses = array('Proposal', 'User');
	public $components = array('Email', 'Message');
	
	public $paginate = array(
		'User' => array(
			'contain' => array('Group', 'Applicant'),
			'limit' => 25,
			'order' => 'User.email',
		),
		'Proposal' => array(
			'contain' => array('Applicant'),
			'limit' => 25,
			'order' => 'Proposal.id',
		),
	);
	
	function login () {
		if ($this->Session->read('Auth.User')) {
			$this->Session->setFlash('Sie sind bereits eingeloggt!');
			$this->redirect(array('controller' => 'users', 'action' => 'home'));
		}
	}
	
	function activate ($new_user_id = null, $activation_code = null) {
		if (!empty($this->data)) {
			if (isset($this->data['email'])) {
				$user = $this->User->find('first', array(
					'conditions' =>	array('User.email' => $this->data['email']),
					'field' => array('User.id')));
				
				$new_user_id = $user['User']['id'];
			}
			
			if (isset($this->data['activation_code'])) {
				$activation_code = $this->data['activation_code'];
			}
		}
		
		if (isset($new_user_id)) {		
			$this->User->id = $new_user_id;

			if (!$this->User->exists()) {
				$this->Session->setFlash('Der angegebene Nutzer existiert nicht!');
				$this->set('activated', false);
					
			} else if ($this->Access->isAdmin() ||
			(isset($activation_code)
			&& $this->User->field('activation_code') == $activation_code)) {
					
				$this->User->saveField('active', 1);
				
				if ($this->Access->isAdmin()) {
					$this->set('activated', 'admin');
					
					
				} else {
					$this->set('activated', true);
				}
				
			} else {
				$this->Session->setFlash('Der Aktivierungscode ist falsch!');
				$this->set('activated', false);
			}
		}
	}
	
	function change_pw ($user_id = null) {
		if (!isset($user_id)) {
			$this->revisit('/users/home');
		}
		
		if (!empty($this->data)) {			
			$pw = $this->Auth->password($this->data['password']);
			$new_pw = $this->data['new_password'];
			
			if ($new_pw != $this->data['new_password_check']) {
				$this->Session->setFlash('Passw&ouml;rter stimmen nicht &uuml;berein!');
				
			} else {
				$this->User->id = $user_id;
				if (!$this->User->exists()) {
					$this->revisit('/users/home');
				}
					
				if (!$this->User->field('password') == $pw && !$this->Access->isAdmin()) {
					$this->Session->setFlash('Falsches Passwort!');

				} else {
					$this->User->saveField('password', $this->Auth->password($new_pw));
					$this->Session->setFlash('Passwort erfolgreich ge&auml;ndert');
					$this->revisit('/users/home');
				}
			}
		} 
		
		$this->set('user_id', $user_id);
	}
	
	function reset_pw ($user_id = null, $activation_code = null) {
		if (!isset($activation_code) || !isset($user_id)) {
			//input data / process input
			if (!empty($this->data)) {
				$email = $this->data['User']['email'];
				
				//look, wether the mail exists
				$exists = $this->User->find('count', array('conditions' =>
					array('email' => $email),
				));
				
				//if not
				if ($exists == 0) {
					//then show an error message in the view
					$this->Session->setFlash('Zur angegebene E-Mail-Adresse gibt es keinen Benutzer');
					
				} else {
					//if it exists, then send an email with the reactivation link
					$user = $this->User->find('first', array('conditions' =>
						array('email' => $email)
					));
					
					$this->User->id = $user['User']['id'];
					$code = $this->create_activation_code(63);
					$this->User->saveField('activation_code', $code);
					$this->send_reactivation_email($this->User->read(), $code);
					$this->Session->setFlash('Eine E-Mail mit weiteren Instruktionen wurde Ihnen gesendet');
					$this->redirect('/users/login');
				}
			}
			
		} 

		if ($this->Access->isAdmin() || (isset($user_id) && isset($activation_code))) {
			//check & reset password, send a mail
			
			$this->User->id = $user_id;
			if ((!$this->User->exists() 
				|| $this->User->field('activation_code') != $activation_code)
				&& !$this->Access->isAdmin()) {
				//error
				$this->Session->setFlash('Falscher Code!');
				$this->set('reset', false);
				
			} else {
				//change password, send mail
				$assigned_pw = $this->create_activation_code(10);
				$this->User->saveField('password', $this->Auth->password($assigned_pw));
				$this->send_new_password ($this->User->read(), $assigned_pw);
				$this->set('reset', true);
			}
		}
	}
	
	private function send_reactivation_email ($user, $code) {
		$message = 'Sehr geehrte(r) ' . $user['User']['name'] . ' ' . $user['User']['last_name'] . '<br/>' 
			. 'Falls Sie Ihr Passwort vergessen haben, klicken Sie auf folgenden Link:<br/>' 
			. '<a href="http://www.univie.ac.at/spl21-call-for-proposals/users/reset_pw/' . $user['User']['id'] . '/'
			. $user['User']['activation_code'] . '">Neues Passwort anfordern</a><br/>'
			. "<br/>\n\nSollte der Link nicht funktionieren, so wenden Sie sich bitte direkt an die SPL!";
		
		$this->Message->send($user['User']['name'] . ' ' . $user['User']['last_name'],
			$user['User']['email'],
			'SPL Proposals - Passwort vergessen?', $message);
	}
	
	private function send_new_password ($user, $pw) {
		$message = 'Sehr geehrte(r) ' . $user['User']['name'] . ' ' . $user['User']['last_name'] . '<br/>' 
					. 'Ihr neues Passwort lautet: ' . $pw;
		
		$this->Message->send($user['User']['name'] . ' ' . $user['User']['last_name'],
			 $user['User']['email'],
			'Ihr neues Passwort', $message);
	}
	
	function logout () {
		$this->Session->setFlash('Sie sind nun ausgeloggt!');
		$this->redirect($this->Auth->logout());		
	}
	
	private function create_activation_code ($length) {
		$characters = "0123456789ABCDEFGHJIKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$code = "";
		
		for ($i = 0; $i < $length; ++$i) {
			$code .= $characters[mt_rand(0, strlen($characters)-1)];
		}
		
		return $code;
	}
	
	private function send_activation_email ($new_user) {		
		$message = 'Sehr geehrte(r) ' . $new_user['User']['name'] . ' ' . $new_user['User']['last_name'] . '!<br/>'
		. 'Bitte klicken sie auf den folgenden Link, um Ihren Account zu aktivieren:<br/>'
		. '<a href="http://www.univie.ac.at/spl21-call-for-proposals/users/activate/' . $new_user['User']['id'] 
		. '/' . $new_user['User']['activation_code'] . '">Account aktivieren</a><br/><br/>' . "\n\n"
		. "Sollte der Link nicht funktionieren, dann geben Sie Ihren Aktivierungscode manuell ein!\n" 
		. " Gehen Sie dazu auf die PowiPro-Login-Seite und wählen Sie den Link \"Aktivieren\".\n"
		. " Ihr Aktivierungscode lautet (ohne Leerzeichen): " . $new_user['User']['activation_code'] . "\n\n<br/><br/>"
		. " Viel Erfolg beim Einreichen Ihrer Proposals!";
		
		$this->Message->send($new_user['User']['name'] . ' ' . $new_user['User']['last_name'],
							 $new_user['User']['email'],
							 'Proposals: Account Aktivierung (SPL21)',
							 $message);
	}

	function register () {
		if (!empty($this->data)) {
			$this->User->create();
				
			$pw = $this->data['User']['password'];
			$pw_check_clear = $this->data['User']['password_check'];
		
			if (strlen($pw_check_clear) < 6) {
				$this->User->validationErrors['password'] = 'Das Passwort muss aus mindestens 6 Zeichen bestehen';
				$this->data['User']['password'] = "";
				$this->data['User']['password_check'] = "";
				
			} else if ($pw != $this->Auth->password($pw_check_clear)) {
				$this->User->validationErrors['password'] = 'Die Passwörter stimmen nicht überein!';
				$this->data['User']['password'] = "";
				$this->data['User']['password_check'] = "";
				
			} else {
					
				$new_user = array('User' => array(
				'activation_code' => $this->create_activation_code(63),
				'password' => $this->data['User']['password'],
				'email' => $this->data['User']['email'],
				'name' => $this->data['User']['name'],
				'last_name' => $this->data['User']['last_name'],
				'active' => 0, 
				'group_id' => $this->groups['Users'],
				));

				if ($this->User->save($new_user)) {
					$this->set('email', $new_user['User']['email']);
					$this->send_activation_email($this->User->read());

				} else {
					$this->data['User']['password'] = "";
				}
			}
		}
	}

	function home () {
		$this->User->id = $this->Session->read('Auth.User.id');
		$this->User->find();
	
		$this->set('username', Sanitize::clean($this->User->field('name'))
		 . ' ' . Sanitize::clean($this->User->field('last_name')));

		$data = $this->User->Applicant->find('all', array(
			'conditions' => array('user_id' => $this->User->id)
		));

		$applicants = array();
		$coapplicants = array();
		$proposals = array();

		$proposals_count = 0;
		
		$this->loadModel('Coapplicant');
		$permissions = array();
		foreach ($data as $applicant) {
			$this->Coapplicant->contain(array('Proposal' => array('Applicant')));
			$coapplicants[$applicant['Applicant']['id']] = $this->Coapplicant->find('all', 
				array('conditions' => array('Coapplicant.applicant_id' => $applicant['Applicant']['id']),
					  'limit' => 5));
			
			$applicants[$applicant['Applicant']['id']] = array(
				'id' => $applicant['Applicant']['id'],
				'name' => $applicant['Applicant']['name'] . ' ' . $applicant['Applicant']['last_name'],
				'email' => $applicant['Applicant']['email'],
				'phone' => $applicant['Applicant']['phone'],
				'proposals' => ''
			);

			foreach ($applicant['Proposal'] as $proposal) {
				$proposals[$applicant['Applicant']['id']][] = array(
					'title' => $proposal['title'],
					'filed_for' => $proposal['filed_for'],
					'id' => $proposal['id']
				);				
			
				$permissions[$proposal['id']] = $this->Access->read($this->Proposal, $proposal['id']);
				$proposals_count++;
			}
			
		}
		
		$this->set('user_id', $this->Auth->user('id'));
		$this->set('permissions', $permissions);
		$this->set('applicants', Sanitize::clean($applicants, array('escape' => false)));
		$this->set('coapplicants', Sanitize::clean($coapplicants, array('escape' => false)));
		$this->set('proposals', Sanitize::clean($proposals, array('escape' => false)));
		$this->set('proposals_count', $proposals_count);
		$this->visited();
	}
	
	/* only admins, access controlled via controllers/User/overview all */
	public function overview ($filter = null) {
		if (!isset($filter)) {
			if (!empty($this->data)) {
				$filter = $this->data['filter'];
				$filter = str_replace('*', '%', $filter);
				$this->params['url'][] = urlencode(str_replace('%', '*', $filter));
			}
		} else {
			$filter = str_replace('*', '%', $filter);
		}
		
		
		$conditions = array();
		if (isset($filter) && $filter != "") {
			$conditions['OR'] = array(
				'User.name LIKE' => $filter,
				'User.last_name LIKE' => $filter,
				'User.email LIKE' => $filter,
			);
		}
		
		$this->User->Behaviors->attach('Containable');
		$users = $this->paginate('User', $conditions);
		
		$this->set('filter', str_replace('%', '*', $filter));
		$this->set('users', $users);
		
		$this->visited();
	}
	
	/* only admins, access controlled via controllers/User/editPermissions */
	public function editPermissions ($user_id = null) {
		if (!isset($user_id)) {
			$this->revisit('/users/home');
		}
		
		if (!empty($this->data)) {
			$this->User->id = $user_id;
			$this->User->saveField('group_id', $this->data['Group']['id']);
			$this->revisit('/users/overview');
		}
		
		$this->User->Behaviors->attach('Containable');
		$this->User->contain(array('Group'));
		$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
				
		$this->set('groups', $this->User->Group->find('all'));
		$this->set('user', $user);
	}
	
	/* only admins and the own user, access to everyone */
	public function delete ($user_id = null) {
		if ($this->Access->isAdmin()) {
			if ($user_id == $this->Auth->user('id')) {
				$this->Session->setFlash('Admins können nicht gelöscht werden.');
				$this->revisit('/users/home');
			}
		} else if ($user_id != $this->Auth->user('id')) {
			$this->revisit('/users/home');
		}
		
		$this->User->id = $user_id;
		$this->User->Applicant->updateAll(
			array('user_id' => null),
			array('user_id' => $user_id)
		);
		$this->User->delete();
		
		if ($user_id == $this->Auth->user('id')) {
			$this->Auth->logout();
			$this->Session->setFlash('Sie haben Ihren Account erfolgreich gelöscht!');
			$this->redirect('/users/login');
		}
		
		$this->revisit('/users/login');
	}
		
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('register', 'logout', 'login', 'activate', 'reset_pw'));
		/*$this->Email->smtpOptions = array(
			'port'=>'465',
			'timeout'=>'30',
			'host' => 'ssl://smtp.gmail.com',
			'username'=>'davidlukas.m@gmail.com',
			'password'=>'dragonballsuper',
		);

		$this->Email->delivery = 'smtp';;*/
	}
	
	public function proposals ($user_id = null) {
		if (!isset($user_id)) {
			$this->revisit('/users/home');
		}
		
		$this->loadModel('Proposal');
		$proposals = $this->paginate('Proposal', array(
			'Applicant.user_id' => $user_id,
			'Proposal.filed_for' => null,
		));
		
		$user = $this->User->find('first', array('conditions' => array(
			'User.id' => $user_id,
		)));
		
		$this->set('proposals', $proposals);
		$this->set('user', $user);
		
		$this->visited();
	}
}







?>