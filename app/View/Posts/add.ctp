<!--
A use of FormHelepr to generate an HTML form
$this->Form->create() generates <form id="" method="post" action="/posts/add">
input is used to create form elements of the same name
$this->Form->end() generates a submit button that ends the form.
-->
<h1>Add Post</h1>
<?php
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->end('Save Post');
?>

