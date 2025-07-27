<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    <h4 class="modal-title"><?php echo _l('acc_account')?></h4>
  </div>
  <?php echo form_open_multipart(admin_url('accounting/account'),array('id'=>'account-form'));?>
  <?php echo form_hidden('id'); ?>
  <?php echo form_hidden('update_balance'); ?>
  <div class="modal-body">
      <?php echo render_select('account_type_id',$account_types,array('id','name'),'account_type','',array(),array(),'','',false); ?>
      <?php echo render_select('account_detail_type_id',$detail_types,array('id','name'),'detail_type','',array(),array(),'','',false); ?>
      <p><i class="detail_type_note"><?php echo html_entity_decode($detail_types[0]['note']); ?></i></p>
    <?php echo render_input('name','name'); ?>
    <?php if(get_option('acc_enable_account_numbers') == 1){
       echo render_input('number','number'); 
    } ?>
    <?php echo render_select('parent_account',$accounts,array('id','name'),'parent_account'); ?>
    <div class="row hide" id="div_balance">
      <div class="col-md-6">
      <?php echo render_input('balance','balance','','text', $arrAtt); ?>
      </div>
      <div class="col-md-6">
      <?php echo render_date_input('balance_as_of','as_of'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <p class="bold"><?php echo _l('dt_expense_description'); ?></p>
        <?php echo render_textarea('description','','',array(),array(),'','tinymce'); ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo _l('close'); ?></button>
    <button type="submit" class="btn btn-info btn-submit"><?php echo _l('submit'); ?></button>
  </div>
  <?php echo form_close(); ?>  
</div>