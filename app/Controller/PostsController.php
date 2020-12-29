<?php
class PostsController extends AppController {
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array('Flash');

	public function index() {
		$this->set('posts', $this->Post->find('all', ['Post.user_id' => 'User.id', 'recursive' => 1]));
	}

	public function view($id = null) {
		if (!$id) {
			$this->Flash->error(__('Invalid post'));
			return $this->redirect(array('action' => 'index'));
		}

		$post = $this->Post->findById($id);
		if (!$post) {
			$this->Flash->error(__('Invalid post'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->set('post', $post);
	}

	public function add() {
		if (!empty($this->Session->read('Auth.User'))) {
			if ($this->request->is('post')) {
				$this->Post->create();
				$this->request->data['Post']['user_id'] = $this->Session->read('Auth.User.User.id');
				if ($this->Post->save($this->request->data)) {
					$this->Flash->success(__('Your post has been saved'));
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error(__('Unable to add your post.'));
			}
		} else {
			$this->redirect(array('controller' => 'Users', 'action' => 'add'));
		}
	}

	public function edit($id = null) {
		if (!$id) {
			$this->Flash->error(__('Invalid post'));
			return $this->redirect(array('action' => 'index'));
		}

		$post = $this->Post->findById($id);
		$user_id = $post['Post']['user_id'];
		if (!$post) {
			$this->Flash->error(__('Invalid post'));
			return $this->redirect(array('action' => 'index'));
		}

		if ($user_id == $this->Session->read('Auth.User.User.id')) {
			if ($this->request->is(array('post', 'put'))) {
				$this->Post->id = $id;

				if ($this->request->data['Post']['id'] !== $this->request->params['pass'][0]) {
					$this->Flash->error(__('Invalid Post ID'));
					$this->redirect(array('action' => 'index'));
				}

				if ($this->Post->save($this->request->data)) {
					$this->Flash->success(__('Your post has been updated'));
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error(__('Unable to update your post'));
			}
		} else {
			$this->Flash->error(__('Unable to access to the post'));
			$this->redirect(array('action' => 'index'));
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		$post = $this->Post->findById($id);

		if (!$post) {
			$this->Flash->error(__('Invalid post'));
			return $this->redirect(array('action' => 'index'));
		}

		$user_id = $post['Post']['user_id'];

		if ($user_id == $this->Session->read('Auth.User.User.id')) {

			if ($this->Post->delete($id)) {
				$this->Flash->success(__('The post with id : %s has been deleted.' , h($id)));
			} else {
				$this->Flash->error(__('The post with id : %s could not be deleted' . h($id)));
			}
		} else {
			$this->Flash->error(__('Unable to access to the post'));
		}

		return $this->redirect(array('action' => 'index'));
	}

}


?>

