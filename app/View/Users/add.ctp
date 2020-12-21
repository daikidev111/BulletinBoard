<div class="user form">
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php __('Register'); ?></legend>
<?php
echo $this->Form->input('username');
echo $this->Form->input('mail');
echo $this->Form->input('password');
echo $this->Form->input('password_confirmation', array('type' => 'password'));
?>
</fieldset>
<?php echo $this->Form->end(__('Register', true)); ?>
</div>

