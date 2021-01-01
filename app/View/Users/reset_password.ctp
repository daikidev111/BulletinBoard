<div class="user form">
<?php echo $this->Form->create(); ?>
<fieldset>
<legend><?php __('Reset Password'); ?></legend>
<?php
echo $this->Form->input('password');
echo $this->Form->input('password_confirmation', array('type' => 'password'));
?>
</fieldset>
<?php echo $this->Form->end(__('Reset')); ?>
</div>

