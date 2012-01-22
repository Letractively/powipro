<?php

class AppController extends Controller {
	public $components = array('Acl', 'Auth', 'Session', 'Access');
	public $helpers = array('Html', 'Form', 'Session', 'Js' => array('Jquery'));

	public $groups = array('Administrators' => 1,
			       'SectionHeads' => 2,
			       'Users' => 3);
	protected $publicMenu = array(
		'Login' => array('controller' => 'users', 'action' => 'login'),
		'Registrieren' => array('controller' => 'users', 'action' => 'register'),
		'Hilfe' => array('controller' => 'pages', 'action' => 'help'),
	);
	
	protected $userMenu = array(
		'Home' => array('controller' => 'users', 'action' => 'home'),
		'Logout' => array('controller' => 'users', 'action' => 'logout'),
		'Hilfe' => array('controller' => 'pages', 'action' => 'help'),
		'Kontingente und Fristen' => array('controller' => 'semesters', 'action' => 'overview'),
		'Bereiche' => array('controller' => 'sections', 'action' => 'overview'),
	);
	
	protected $sectionHeadMenu = array(
		'Proposals' => array('controller' => 'semesters', 'action' => 'overview'),
	);
	
	protected $adminMenu = array(
		'Rechte' => array('controller' => 'users', 'action' => 'overview'),
		'Semesterverwaltung' => array('controller' => 'semesters', 'action' => 'overview'),
		/*'Neues Semester' => array('controller' => 'semesters', 'action' => 'edit'),*/
		'Bereiche' => array('controller' => 'sections', 'action' => 'overview'),
		'Kurstypen' => array('controller' => 'course_types', 'action' => 'overview'),
	);
	
	public function beforeRender() {
		//setlocale(LC_ALL, 'de_AT@euro', 'de_AT', 'de_DE', 'deu_deu');
		
		if ($this->Access->isAdmin())
			$this->set('menu', array_merge($this->userMenu, $this->adminMenu));
		else if ($this->Access->isExecutive())
			$this->set('menu', array_merge($this->userMenu, $this->sectionHeadMenu));
		else if ($this->Access->isUser()) 
			$this->set('menu', $this->userMenu);
		else
			$this->set('menu', $this->publicMenu);
		
	}
	
	public function beforeFilter() {
		$this->Auth->fields = array(
			'username' => 'email',
			'password' => 'password',
		);
		$this->Auth->userScope = array('User.active' => 1);
		$this->Auth->loginError = 'Falsche E-Mail/Passwort-Kombination. Haben Sie Ihren Account schon aktiviert?';
		$this->Auth->authorize = 'actions';
		$this->Auth->loginAction =
			array('controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect =
			array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect =
			array('controller' => 'users', 'action' => 'home');
		$this->Auth->actionPath = 'controllers/';
		$this->Auth->allowedActions = array('display');
		
		if ($this->Session->check('last_visited')) {
			$this->set('back_link', $this->Session->read('last_visited'));
		}
		
	}

	protected function visited($page = null) {
		if (!isset($page)) {
			foreach ($this->params['url'] as $named => $part) {
				if ($named == 'url') {
					$page .= '/' . $part ;
				} else if (!empty($part)) {
					$page .= '/' . $named . ':' . $part;
				}
			}
		}
		$this->Session->write('last_visited', $page);
	}

	protected function revisit ($default) {
		if ($this->Session->check('last_visited')) {
			$visit = $this->Session->read('last_visited');
			$this->Session->delete('last_visited');
			$this->redirect($visit);
		} else {
			$this->redirect($default);
		}
	}

	public function debug_print_array ($arr) {
		foreach ($arr as $a => $b) {
			echo $a . ': ' . $b . '<br />';
			foreach ($b as $c => $d) {
				echo '-- ' . $c . ': ' . $d . '<br />';

				if (is_array($d)) {
					foreach ($d as $e => $f) {
						echo '---- ' . $e . ': ' . $f . '<br />';
						if (is_array($f)) {
							foreach ($f as $g => $h) {
								echo '------ ' . $g . ': ' . $h . '<br />';
							}
						}
					}
				}
			}
			echo '<br />';
		}
	}
}

?>
