 <?php echo form_open(get_uri('accounting/reset_account_detail_types')); ?>
<div class="row mbot10 <?php if(!acc_has_permission('acc_can_delete_setting')){echo 'hide';} ?>">
    <div class="col-md-12">
      <button type="submit" class="btn btn-info _delete text-white"><i data-feather="refresh-ccw" class="icon-16"></i> <?php echo app_lang('reset_account_detail_types'); ?></button> <label class="text-danger"><?php echo app_lang('accounting_reset_account_detail_types_button_tooltip'); ?></label>
  </div>
</div>
<hr class=" <?php if(!acc_has_permission('acc_can_delete_setting')){echo 'hide';} ?>">
<?php echo form_close(); ?>
<div>
	<a href="#" class="btn btn-default add-new-account-type-detail mbot15 <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>"><i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?></a>
</div>
<div class="row">
	<div class="col-md-12">
    <table class="display table-account-type-details" cellspacing="0" width="100%">            
</table>
		<?php 
			$table_data = array(
				app_lang('account_type'),
				app_lang('name'),
				);
		?>
	</div>
</div>
<div class="clearfix"></div>
<div class="modal fade" id="account-type-detail-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app_lang('account_type_detail')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?php echo form_open_multipart(get_uri('accounting/account_type_detail'),array('id'=>'account-type-detail-form', 'class' => 'general-form'));?>
      <?php echo form_hidden('id'); ?>
      <?php echo form_hidden('note'); ?>
      <div class="modal-body">
        <?php echo render_select('account_type_id',$account_types,array('id','name'),'account_type','',array(),array(),'','',false); ?>
        <?php echo render_input('name','name'); ?>
        <?php 
        	$statement_of_cash_flows = [
                  1 => ['id' => 'cash_flows_from_operating_activities', 'name' => app_lang('cash_flows_from_operating_activities')],
                  2 => ['id' => 'cash_flows_from_investing_activities', 'name' => app_lang('cash_flows_from_investing_activities')],
                  3 => ['id' => 'cash_flows_from_financing_activities', 'name' => app_lang('cash_flows_from_financing_activities')],
                  4 => ['id' => 'cash_and_cash_equivalents_at_beginning_of_year', 'name' => app_lang('cash_and_cash_equivalents_at_beginning_of_year')],
                 ];
          	echo render_select('statement_of_cash_flows', $statement_of_cash_flows, array('id', 'name'),'statement_of_cash_flows', '', array(), array(), '', '', false);
        ?>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="note" class=""><?php echo app_lang('description'); ?></label>
              <?php
              echo form_textarea(array(
                  "id" => "note",
                  "name" => "note",
                  "value" => "",
                  "class" => "form-control",
                  "placeholder" => app_lang('note'),
                  "data-rich-text-editor" => true
              ));
              ?>
          </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-info btn-submit text-white"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>  
    </div>
  </div>
</div>
<?php require 'plugins/Accounting/assets/js/setting/account_type_details_js.php'; ?>
