<div class="user form">
<?php echo $this->Form->create(); ?>
<fieldset>
<legend>
<?php echo __('Please enter your email address'); ?>
</legend>
<?php echo $this->Form->input('mail'); ?>
</fieldset>
<?php echo $this->Form->end('submit'); ?>
<p>*If you have forgotten your login password, we will send a password reset form to your email address.</p>
</div>
