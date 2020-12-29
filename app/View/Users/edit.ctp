<h1>Edit Account Information</h1>
<?php
echo $this->Form->create('User', array('type' => 'file', 'enctype' => 'multipart/form_data'));
echo $this->Form->file('image', array('accept' => 'image/jpg, image/png', 'image/jpeg'));
echo $this->Form->input('comment', array('type' => 'text', 'maxlength' => 255, 'default' => $comment_value));
echo $this->Form->end('Edit');
?>
