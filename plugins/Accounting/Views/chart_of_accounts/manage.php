<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
            <div class="title-button-group">
                <a href="#" class="btn btn-default add-new-account mbot15 <?php if(!acc_has_permission('acc_can_create_account')){echo 'hide';} ?>"><i data-feather="plus-circle" class="icon-16"></i> <?php echo _l('add'); ?></a>
                
              </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-accounts" id="accounts-table">
                
              </table>
            </div>
        </div>
    </div>
</div>

<?php $arrAtt = array();
      $arrAtt['data-type']='currency';
?>
<div class="modal fade" id="account-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo _l('acc_account')?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <?php echo form_open_multipart(admin_url('accounting/account'),array('id'=>'account-form', "class" => "general-form", "role" => "form"));?>
      <?php echo form_hidden('id'); ?>
      <?php echo form_hidden('update_balance'); ?>
      <div class="modal-body">
          <?php echo render_select('account_type_id',$account_types,array('id','name'),'account_type','',array(),array(),'','',false); ?>
          <?php echo render_select('account_detail_type_id',$detail_types,array('id','name'),'detail_type','',array(),array(),'','',false); ?>
          <p><i class="detail_type_note"><?php echo html_entity_decode($detail_types[0]['note']); ?></i></p>
        <?php echo render_input('name','name'); ?>
        <?php if(get_setting('acc_enable_account_numbers') == 1){
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
            <div class="form-group">
                    <label for="description" class=""><?php echo app_lang('description'); ?></label>
                        <?php
                        echo form_textarea(array(
                            "id" => "description",
                            "name" => "description",
                            "value" => "",
                            "class" => "form-control",
                            "placeholder" => app_lang('description'),
                            "data-rich-text-editor" => true
                        ));
                        ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info btn-submit"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>  
    </div>
  </div>
</div>


<div class="modal fade bulk_actions" id="accounts_bulk_actions" tabindex="-1" role="dialog" data-table=".table-accounts">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <?php if(acc_has_permission('acc_can_edit_account')){ ?>
               <div class="checkbox checkbox-info">
                  <input type="checkbox" name="mass_activate" id="mass_activate">
                  <label for="mass_activate"><?php echo _l('mass_activate'); ?></label>
               </div>
            <?php } ?>
            <?php if(acc_has_permission('acc_can_edit_account')){ ?>
               <div class="checkbox checkbox-info">
                  <input type="checkbox" name="mass_deactivate" id="mass_deactivate">
                  <label for="mass_deactivate"><?php echo _l('mass_deactivate'); ?></label>
               </div>
            <?php } ?>
            <?php if(acc_has_permission('acc_can_detele_account')){ ?>
               <div class="checkbox checkbox-danger">
                  <input type="checkbox" name="mass_delete" id="mass_delete">
                  <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
               </div>
            <?php } ?>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo _l('close'); ?></button>
         <a href="#" class="btn btn-info" onclick="bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
      </div>
   </div>
   <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<?php require 'plugins/Accounting/assets/js/chart_of_accounts/manage_js.php'; ?>
