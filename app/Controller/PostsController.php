<?php
class PostsController extends AppController {
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array('Flash');

	public function index() {
		//$this->set('posts', $this->Post->find('all'));
		$this->set('posts', $this->Post->find('all', ['Post.user_id' => 'User.id', 'recursive' => 1]));
	}

	//File Name: view.ctp
	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid Post'));
		}

		//Finding a post corresponding to the ID from a table named Post
		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('invalid post'));
		}
		$this->set('post', $post);
	}

	public function add() {
		if (!empty($this->Session->read('Auth.User.User.id'))) {
			//This is to check a form method
			if ($this->request->is('post')) {
				//to save new information
				$this->Post->create();
				//manipulate the post data so that it can be inserted into the database
				//$this->Post->save = INSERT INTO POST ...
				$this->request->data['Post']['user_id'] = $this->Session->read('Auth.User.User.id');
				if ($this->Post->save($this->request->data)) {
					//$this->request->data contains data from the post method
					//Flash method sets the success message into the session variable, allowing it to be diplayed on the redirected page.
					$this->Flash->success(__('Your post has been saved'));
					return $this->redirect(array('action' => 'index'));
				}
				$this->Flash->error(__('Unable to add your post.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid Post'));
		}

		$post = $this->Post->findById($id);
		$user_id = $post['Post']['user_id'];
		if (!$post) {
			throw new NotFoundException(__('Invalid Post'));
		}

		if ($user_id == $this->Session->read('Auth.User.User.id')) {
			if ($this->request->is(array('post', 'put'))) {
				$this->Post->id = $id;
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
			throw new NotFoundException(__('Invalid Post'));
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

	public function isAuthorized($user) {
		if ($this->action == 'add') {
			return true;
		}

		if (in_array($this->action, array('edit', 'delete'))) {
			$postId = (int) $this->request->params['pass'][0];
			if ($this->Post->isOwnedBy($postId, $user['id'])) {
				return true;
			} else {
				throw new MethodNotAllowedException('他人の投稿は編集できません');
			}
		}
	}
}


?>

