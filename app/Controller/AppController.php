<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array(
		'DebugKit.Toolbar',
		'Flash',
		'Session',
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => 'Blowfish',
					'fields' => array('username' => 'mail')
				)
			),
			'authorize' => array('controller')
		)
	);

	public function beforeFilter() {
		$this->Auth->allow('index', 'view');
		$this->Auth->authError = 'Please login to view that page';
		$this->Auth->loginError = 'Incorrect username/password';
		$this->Auth->loginRedirect = array('controller' => 'posts', 'action' => 'index');
		$this->Auth->logoutRedirect = array('controller' => 'posts', 'action' => 'index');
		$this->set('logged_in', $this->_loggedIn());
		$this->set('users_username', $this->_usersUsername());
	}

	function _loggedIn() {
		$logged_in = false;
		if ($this->Auth->user()) {
			$logged_in = true;
		}
		return $logged_in;
	}

	function _usersUsername() {
		$users_username = null;
		if ($this->Auth->user()) {
			$users_username = $this->Auth->user('username');
		}
		return $users_username;
	}

	public function isAuthorized($user) {
		if (isset($user)) {
			return true;
		} else {
			return false;
		}
	}

}

