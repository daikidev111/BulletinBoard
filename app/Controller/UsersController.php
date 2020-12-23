<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function beforeFilter() {
		//call the previous logic in this controller as well
		parent::beforeFilter();
		$this->Auth->allow('add');
	}

	public function login() {
		if (empty($this->Session->read('Auth.User.User.id'))) {
			if ($this->request->is('post')) {
				if ($this->Auth->login()) {

					//get the result of the database
					$result = $this->User->find('all', ['recursive' => -1]);
					foreach ($result as $res) {
						if ($res['User']['mail'] == $this->request->data['User']['mail']) {
							$username = $res['User']['username'];
							$id = $res['User']['id'];
						}
					}

					$this->Session->write('Auth.User.User.username', $username);
					$this->Session->write('Auth.User.User.id', $id);

					return $this->redirect($this->Auth->redirectUrl());

				} else {
					$this->Flash->error(__('Invalid email or password. Please try again'));
				}
			}
		} else {
			$this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}
	}

	public function add() {
		if ($this->Session->read('Auth.User')) {
			$this->redirect($this->Auth->redirect());
		}
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('controller' => 'Users', 'action' => 'login'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please try again', true));
			}
		}
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
}

?>
