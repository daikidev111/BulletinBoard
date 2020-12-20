<?php

class Post extends AppModel {
	//this is to validate when save method is called.
	public $validate = array(
		'title' => array(
			'rule' => 'notBlank'
		),
		'body' => array(
			'rule' => 'notBlank'
		)
	);
}


?>
