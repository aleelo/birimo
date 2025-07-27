<?php 
  	$plaid_environment = get_setting('acc_plaid_environment');
  	$live_secret = get_setting('acc_live_secret');
  	$sandbox_secret = get_setting('acc_sandbox_secret');
  	$plaid_client_id = get_setting('acc_plaid_client_id');
?>

<?php echo form_open(get_uri('accounting/update_plaid_environment'),array('id'=>'general-settings-form', 'class' => 'general-form')); ?>
	<div class="row">
		<div class="col-md-12">
	      <p class="mbot5"><?php echo _l('plaid_environment'); ?></p>
	      <label class="radio-inline"><input type="radio" id="production" name="acc_plaid_environment" value="production" class="form-check-input" <?php if($plaid_environment == 'production' || $plaid_environment == ''){ echo "checked" ;} ?>><?php echo _l('live'); ?></label>
	      <label class="radio-inline"><input type="radio" id="sandbox" name="acc_plaid_environment" value="sandbox" class="form-check-input" <?php if($plaid_environment == 'sandbox'){ echo "checked" ;} ?>><?php echo _l('sandbox'); ?></label>
		</div>
	</div>
	<br>
	<?php echo render_input('acc_plaid_client_id', 'client_id', $plaid_client_id); ?>
	<h5><?php echo _l('secrets'); ?></h5>
	<?php echo render_input('acc_live_secret', 'live', $live_secret); ?>
	<?php echo render_input('acc_sandbox_secret', 'sandbox', $sandbox_secret); ?>
	<hr>
	<div class="col-md-12">
	  <?php if(acc_has_permission('acc_can_edit_setting')){ ?>
	  <button type="submit" class="btn btn-info text-white pull-right"><i data-feather="check-circle" class="icon-16"></i> <?php echo _l('submit'); ?></button>
	<?php } ?>
	</div>
<?php echo form_close(); ?>
	           