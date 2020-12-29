<h1><b>Account Information</b></h1>
<br>
<h1>Profile Icon</h1>
<?php if (empty($profile['User']['image'])): ?>
<p>Not Uploaded Yet..</p>
<?php else: ?>
<?php echo $this->Html->image($profile['User']['image'], array('width' => '100px', 'height' => '100px')); ?>
<?php endif; ?>
<br>
<br>
<h1>User Name: </h1>
<p><?php echo $profile['User']['username']; ?></p>
<br>
<h1>Email Address: </h1>
<p><?php echo $profile['User']['mail']; ?></p>
<br>
<h1>Comment</h1>
<?php if (empty($profile['User']['comment'])): ?>
<p>Not Uploaded Yet..</p>
<?php else: ?>
<?php echo $profile['User']['comment']; ?>
<?php endif; ?>
<br>
<br>
<br>
<?php echo $this->Html->link('Back to posts', array('controller' => 'posts', 'action' => 'index')); ?>
<br>
<br>
<?php if ($validate == true): ?>
<?php echo $this->Html->link('Edit User Account', array('action' => 'edit')); ?>
<?php endif; ?>
