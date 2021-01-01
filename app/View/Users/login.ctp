<div class="user form">
<?php echo $this->Flash->render('auth') ?>
<?php echo $this->Form->create('User') ?>
<fieldset>
<legend>
<?php echo __('Please enter your email and password'); ?>
</legend>
<?php echo $this->Form->input('mail'); ?>
<?php echo $this->Form->input('password');?>
<small><?php echo $this->Html->link('Forgot your password?', array('action' => 'forgot_password')); ?></small>
</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
