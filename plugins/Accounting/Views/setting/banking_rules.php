<div>
	<a href="<?php echo get_uri('accounting/new_rule'); ?>" class="btn btn-default mbot15 <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>"><i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?></a>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="display table-banking-rules" cellspacing="0" width="100%">            
		</table>
	</div>
</div>
<div class="clearfix"></div>
<?php require 'plugins/Accounting/assets/js/setting/banking_rules_js.php'; ?>
