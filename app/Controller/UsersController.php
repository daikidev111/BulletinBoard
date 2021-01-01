<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail','Network/Email');
class UsersController extends AppController {
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array('Security');
	public function beforeFilter() {
		//call the previous logic in this controller as well
		parent::beforeFilter();
		$this->Auth->allow('add', 'forgot_password', 'reset_password', 'error');
		$this->Security->blackHoleCallback = 'blackhole';
	}

	public function blackhole() {
		$this->Flash->error(__('Unable to perform the operation'));
		return $this->redirect(array('action' => 'view'));
	}

	public function login() {
		if (empty($this->Session->read('Auth.User'))) {
			if ($this->request->is('post')) {
				if ($this->Auth->login()) {

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

	public function view($id = null) {
		$validate = false;
		if (!$id) {
			$this->Flash->error(__('Invalid User ID'));
			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}
		$profile = $this->User->findById($id);

		if (!$profile['Post']) {
			$this->Flash->error(__('Invalid User Access'));
			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}

		if (!$profile) {
			$this->Flash->error(__('Invalid User Account'));
			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}
		$this->set('profile', $profile);

		if (!empty($this->Session->read('Auth.User'))) {
			if ($this->Session->read('Auth.User.User.id') == $this->request->params['pass'][0]) {
				$validate = true;
			}
		}
		$this->set('validate', $validate);

	}


	public function edit() {
		if (!empty($this->Session->read('Auth.User.User.id'))) {
			$id = $this->Session->read('Auth.User.User.id');
			$user_info = $this->User->findById($id);
			$this->set('comment_value', $user_info['User']['comment']);

			if ($this->request->is(array('post', 'put'))) {
				$this->User->create();
				$image_id = mt_rand(0, 99999999);
				$tmp_name = $this->request->data['User']['image']['tmp_name'];
				$image_name = $this->request->data['User']['image']['name'];
				$extension = explode('/', $this->request->data['User']['image']['type'], PATHINFO_EXTENSION);
				$file_name = $image_id . "." . array_pop($extension);
				$this->request->data['User']['id'] = $id;
				if (!empty($image_name)) {
					if (!in_array($this->request->data['User']['image']['type'], array('image/png', 'image/jpg', 'image/jpeg'))) {
						$this->Flash->error(__('Invalid File Extension'));
						$this->redirect(array('action' => 'view', $id));
					}
					if (!getimagesize($tmp_name)) {
						$this->Flash->error(__('The Extension path has been edited'));
						return $this->redirect(array('action' => 'view', $id));
					}

					if (!empty($user_info['User']['image'])) {
						unlink('../webroot/img/' . $user_info['User']['image']);
					}
					$this->request->data['User']['image'] = $file_name;
					move_uploaded_file($tmp_name, '../webroot/img/' . $file_name);
				} else {
					if (!empty($user_info['User']['image'])) {
						$this->request->data['User']['image'] = $user_info['User']['image'];
					}
				}

				if (empty($this->request->data['User']['comment'])) {
					$this->request->data['User']['comment'] = null;
				}

				if ($this->User->save($this->request->data, false, array('image', 'comment', 'id'))) {
					$this->Flash->success(__('Successfully edited'));
					return $this->redirect(array('action' => 'view', $id));
				} else {
					$this->Flash->error(__('Failed to edit'));
				}
			}
		} else {
			$this->Flash->error(__('unauthorised access'));
			return $this->redirect(array('controller' => 'Posts', 'action' => 'index'));
		}
	}

	public function forgot_password() {
		if ($this->request->is('post')) {
			if (isset($this->request->data['User']['mail'])) {
				$mail = $this->request->data['User']['mail'];
				$data = $this->User->findByMail($mail);
				if ($data) {
					$this->request->data['User']['pass_token'] = sha1(uniqid(rand(), true));
					$this->request->data['User']['reset_time'] = strval(date("Y-m-d H:i:s"));
					$email = new CakeEmail();
					$email->to($mail);
					$email->from('daikikubo2@gmail.com');
					$email->subject('Password reset form');
					$url = 'https://procir-study.site/kubo421/CakePHP/cakephp/users/reset_password?pass_token=' . $this->request->data['User']['pass_token'];
					if ($email->send($url)) {
						$this->User->id = $data['User']['id'];
						$this->User->save($this->request->data, false, array('pass_token', 'reset_time'));
					}
				}
				$this->Flash->success(__('Successfully sent a password reset form to the mail.'));
				return $this->redirect(array('action' => 'login'));
			}
		}
	}

	public function reset_password() {
		$valid_search = false;
		$valid_time = false;
		if (!empty($this->request->query['pass_token'])) {
			$user_data = $this->User->findByPass_token($this->request->query['pass_token']);
			if (!empty($user_data['User'])) {
				$reset_time = $user_data['User']['reset_time'];
				$limit_time = date("Y-m-d H:i:s", strtotime("-30mins"));
				$valid_search = true;
				if (strtotime($reset_time) >= strtotime($limit_time)) {
					$valid_time = true;
				}
			}
		}

		if ($this->request->is('post')) {
			if ($valid_time && $valid_search) {
				$this->User->id = $user_data['User']['id'];
				$this->request->data['User']['pass_token'] = null;
				$this->request->data['User']['reset_time'] = null;
				if ($this->User->save($this->request->data, true, array('password', 'pass_token', 'reset_time'))) {
					$this->Flash->success(__('Successfully reset the password'));
					$this->redirect(array('action' => 'login'));
				} else {
					$this->Flash->error(__('Failed to reset the password. Please try again'));
				}
			} else {
				$this->redirect(array('action' => 'error'));
			}
		}
	}

	public function error() {
		$this->Flash->error(__('Unauthorized access'));
	}

	public function logout() {
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
}

?>
