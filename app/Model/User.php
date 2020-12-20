<?php


class User extends AppModel {
	public $validate = array(
		'mail' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A password is required'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => 'notBlank',
				'message' => 'A password is required'
			)
		)
	);
}
