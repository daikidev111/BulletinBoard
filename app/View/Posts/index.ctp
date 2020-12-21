<h1>Blog Posts</h1>
<!-- Generate "Add Post" link-->
<?php echo $this->Html->link(
	'Add Post',
	array('controller' => 'posts', 'action' => 'add')
); ?>
<?php if ($logged_in): ?>
<h1>Welcome <?php echo $this->Session->read('Auth.User.User.username'); ?></h1>
<?php echo $this->Html->link('logout', array('controller' => 'users', 'action' => 'logout')); ?>
<?php else: ?>
<h1> Welcome 名無さん！</h1>
<br>
<?php echo $this->Html->link('register', array('controller' => 'users', 'action' => 'add')); ?>
<br>
<?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'index')); ?>
<?php endif; ?>
<table>
<tr>
<th>ID</th>
<th>Title</th>
<th>Action</th>
<th>Created</th>
<th>Creator</th>
</tr>
<?php foreach ($posts as $post): ?>
<tr>
<td><?php echo $post['Post']['id']; ?></td>
<td><?php echo $this->Html->link($post['Post']['title'], array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])); ?></td>
<td>
<?php echo $this->Html->link('Edit', array('action' => 'edit', $post['Post']['id'])); ?>
|
<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $post['Post']['id'])); ?>
</td>
<td><?php echo $post['Post']['created']; ?></td>
<td><?php echo $post['User']['username']; ?></td>
</tr>
<?php endforeach; ?>
<?php unset($post); ?>
</table>
