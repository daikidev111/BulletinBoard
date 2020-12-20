<?php
class PostsController extends AppController {
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array('Flash');

	public function index(){
		$this->set('posts', $this->Post->find('all'));
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
		//This is to check a form method
		if ($this->request->is('post')) {
			//to save new information
			$this->Post->create();
			//check the validation error and check if it needs to be stopped
			if ($this->Post->save($this->request->data)) {
				//$this->request->data contains data from the post method
				//Flash method sets the success message into the session variable
				$this->Flash->success(__('Your post has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('Unable to add your post.'));
		}
	}

	public function edit($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid Post'));
		}

		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid Post'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Post->id = $id;
			if ($this->Post->save($this->request->data)) {
				$this->Flash->success(__('Your post has been updated'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('Unable to update your post'));
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if ($this->Post->delete($id)) {
			$this->Flash->success(__('The post with id : %s has been deleted.' , h($id)));
		} else {
			$this->Flash->error(__('The post with id : %s could not be deleted' . h($id)));
		}

		return $this->redirect(array('action' => 'index'));
	}
}


?>

