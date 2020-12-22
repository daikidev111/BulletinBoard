<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class User extends AppModel {
	public $hasMany = array(
		'Post' => array(
			'className' => 'Post'
		)
	);

	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A username is required'
			)
		),
		'mail' => array(
			'NotEmpty' => array(
				'rule' => 'isUnique',
				'message' => 'That mail has already been taken.',
				'required' => true
			),
			'Email' => array(
				'rule' => array('email'),
				'message' => 'Invalid mail.',
				'required' => true
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A password is required'
			),
			'The passwords do not match' => array(
				'rule' => 'matchPasswords',
				'message' => 'The passwords do not match'
			)
		)
	);

	public function matchPasswords($data) {
		if ($data['password'] == $this->data['User']['password_confirmation']) {
			return true;
		}
		$this->invalidate('password_confirmation', 'The passwords do not match');
		return false;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password'], 'blowfish');
		}
		return true;
	}
}
