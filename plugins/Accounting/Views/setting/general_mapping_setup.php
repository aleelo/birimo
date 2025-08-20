<?php 

  $acc_invoice_automatic_conversion = get_setting('acc_invoice_automatic_conversion');
  $acc_payment_automatic_conversion = get_setting('acc_payment_automatic_conversion');
  $acc_expense_automatic_conversion = get_setting('acc_expense_automatic_conversion');
  $acc_tax_automatic_conversion = get_setting('acc_tax_automatic_conversion');
  $acc_payment_expense_automatic_conversion = get_setting('acc_payment_expense_automatic_conversion');
  $acc_payment_sale_automatic_conversion = get_setting('acc_payment_sale_automatic_conversion');

  $acc_invoice_payment_account = get_setting('acc_invoice_payment_account');
  $acc_invoice_deposit_to = get_setting('acc_invoice_deposit_to');
  $acc_payment_payment_account = get_setting('acc_payment_payment_account');
  $acc_payment_deposit_to = get_setting('acc_payment_deposit_to');
  $acc_expense_payment_payment_account = get_setting('acc_expense_payment_payment_account');
  $acc_expense_payment_deposit_to = get_setting('acc_expense_payment_deposit_to');
  
  $acc_expense_payment_account = get_setting('acc_expense_payment_account');
  $acc_expense_deposit_to = get_setting('acc_expense_deposit_to');
  $acc_tax_payment_account = get_setting('acc_tax_payment_account');
  $acc_tax_deposit_to = get_setting('acc_tax_deposit_to');
  $acc_expense_tax_payment_account = get_setting('acc_expense_tax_payment_account');
  $acc_expense_tax_deposit_to = get_setting('acc_expense_tax_deposit_to');

  $acc_active_payment_mode_mapping = get_setting('acc_active_payment_mode_mapping');
  $acc_active_expense_category_mapping = get_setting('acc_active_expense_category_mapping');

  $acc_credit_note_automatic_conversion = get_setting('acc_credit_note_automatic_conversion');
  $acc_credit_note_payment_account = get_setting('acc_credit_note_payment_account');
  $acc_credit_note_deposit_to = get_setting('acc_credit_note_deposit_to');
 ?>
 
<?php echo form_open(get_uri('accounting/update_automatic_conversion'),array('id'=>'general-settings-form', "class" => "general-form", "role" => "form")); ?>
<div class="row">
  <div class="col-md-12">
    <h4><?php echo app_lang('automatic_conversion'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo app_lang('automatic_conversion_note'); ?>"></i></h4>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo app_lang('invoice_default_for_all_item') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_invoice_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_invoice_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_invoice_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_invoice_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_invoice_automatic_conversion == 0){echo 'hide';} ?>" id="div_invoice_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_invoice_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_invoice_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_invoice_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_invoice_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <h5 class="title mbot5"><?php echo app_lang('payment') ?></h5>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6 border-right">
                    <h5><?php echo app_lang('sales'); ?></h5>
                  </div>
                  <div class="col-md-6 mtop5">
                      <div class="onoffswitch">
                          <input type="checkbox" id="acc_payment_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_payment_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_payment_automatic_conversion">
                          <label class="onoffswitch-label" for="acc_payment_automatic_conversion"></label>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="<?php if($acc_payment_automatic_conversion == 0){echo 'hide';} ?>" id="div_payment_automatic_conversion">
          <div class="row">
            <div class="col-md-6">
              <?php echo render_select('acc_payment_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_payment_payment_account,array(),array(),'','',false); ?>
            </div>
            <div class="col-md-6">
              <?php echo render_select('acc_payment_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_payment_deposit_to,array(),array(),'','',false); ?>
            </div>
          </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6 border-right">
                    <h5><?php echo app_lang('expenses'); ?></h5>
                  </div>
                  <div class="col-md-6 mtop5">
                      <div class="onoffswitch">
                          <input type="checkbox" id="acc_payment_expense_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_payment_expense_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_payment_expense_automatic_conversion">
                          <label class="onoffswitch-label" for="acc_payment_expense_automatic_conversion"></label>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="<?php if($acc_payment_expense_automatic_conversion == 0){echo 'hide';} ?>" id="div_payment_expense_automatic_conversion">
          <div class="row">
            <div class="col-md-6">
              <?php echo render_select('acc_expense_payment_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_expense_payment_payment_account,array(),array(),'','',false); ?>
            </div>
            <div class="col-md-6">
              <?php echo render_select('acc_expense_payment_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_payment_deposit_to,array(),array(),'','',false); ?>
            </div>
            </div>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo app_lang('credit_note') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_credit_note_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_credit_note_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_credit_note_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_credit_note_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_credit_note_automatic_conversion == 0){echo 'hide';} ?>" id="div_credit_note_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_credit_note_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_credit_note_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_credit_note_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_credit_note_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo app_lang('expense_default') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_expense_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_expense_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_expense_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_expense_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_expense_automatic_conversion == 0){echo 'hide';} ?>" id="div_expense_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_expense_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_expense_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_expense_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo app_lang('tax_default') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_tax_automatic_conversion" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_tax_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_tax_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_tax_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_tax_automatic_conversion == 0){echo 'hide';} ?>" id="div_tax_automatic_conversion">
          <div class="col-md-12">
            <h5><?php echo app_lang('sales'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_tax_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_tax_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_tax_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_tax_deposit_to,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-12">
            <h5><?php echo app_lang('expenses'); ?></h5>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_expense_tax_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_expense_tax_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_expense_tax_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_tax_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<hr>
<div class="row">
  <div class="col-md-12">
    <button type="submit" class="btn btn-info pull-right text-white <?php if(!acc_has_permission('acc_can_edit_setting')){echo 'hide';} ?>"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('submit'); ?></button>
  </div>
</div>
<?php echo form_close(); ?>
<div class="row">
  <div class="col-md-12">
    <hr>
  </div>
</div>
  <h4 class="no-margin font-bold"><?php echo app_lang('item_mapping_setup'); ?></h4>
<hr>
<a href="#" onclick="add_item_automatic(); return false;" class="btn btn-default mr-4 button-margin-r-b <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>" title="<?php echo app_lang('add') ?> "><i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?>
</a>
<hr>
<table class="table table-item-automatic">
 
</table>
<div class="row">
  <div class="col-md-12">
    <hr>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-6 border-right">
        <h4 class="no-margin font-bold"><?php echo app_lang('payment_mode_mapping'); ?></h4>
      </div>
      <div class="col-md-6">
          <div class="onoffswitch">
              <input type="checkbox" id="acc_active_payment_mode_mapping" data-perm-id="3" class="onoffswitch-checkbox form-check-input mt-3" <?php if($acc_active_payment_mode_mapping == '1'){echo 'checked';} ?>  value="1" name="acc_active_payment_mode_mapping" data-switch-url="<?php echo get_uri('accounting/change_active_payment_mode_mapping') ?>" data-id="0">
              <label class="onoffswitch-label" for="acc_active_payment_mode_mapping"></label>
          </div>
      </div>
    </div>
  </div>
</div>
<hr>
<a href="#" onclick="add_payment_mode_mapping(); return false;" class="btn btn-default mr-4 button-margin-r-b <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>" title="<?php echo app_lang('add') ?> "><i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?>
</a>
<hr>
<table class="table table-payment-mode-mapping">
  
</table>
<div class="row">
  <div class="col-md-12">
    <hr>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-6 border-right">
        <h4 class="no-margin font-bold"><?php echo app_lang('expense_category_mapping'); ?></h4>
      </div>
      <div class="col-md-6">
          <div class="onoffswitch">
              <input type="checkbox" id="acc_active_expense_category_mapping" data-perm-id="3" class="onoffswitch-checkbox form-check-input mt-3" <?php if($acc_active_expense_category_mapping == '1'){echo 'checked';} ?>  value="1" name="acc_active_expense_category_mapping" data-switch-url="<?php echo get_uri('accounting/change_active_expense_category_mapping') ?>" data-id="0">
              <label class="onoffswitch-label" for="acc_active_expense_category_mapping"></label>
          </div>
      </div>
    </div>
  </div>
</div>
<hr>
<a href="#" onclick="add_expense_category_mapping(); return false;" class="btn btn-default mr-4 button-margin-r-b <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>" title="<?php echo app_lang('add') ?> ">
  <i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?>
</a>
<hr>
<table class="table table-expense-category-mapping">
 
</table>
<div class="row">
  <div class="col-md-12">
    <hr>
  </div>
</div>
  <h4 class="no-margin font-bold"><?php echo app_lang('tax_mapping_setup'); ?></h4>
<hr>
<a href="#" onclick="add_tax_mapping(); return false;" class="btn btn-default mr-4 button-margin-r-b <?php if(!acc_has_permission('acc_can_create_setting')){echo 'hide';} ?>" title="<?php echo app_lang('add') ?> ">
  <i data-feather="plus-circle" class="icon-16"></i> <?php echo app_lang('add'); ?>
</a>
<hr>
<table class="table table-tax-mapping">
 
</table>

<?php $arrAtt = array();
      $arrAtt['data-type']='currency';
?>
<div class="modal fade" id="item-automatic-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('item_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/item_automatic'),array('id'=>'item-automatic-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         <div class="modal-body">
              <?php echo render_select('item[]',$items,array('id','title', 'sku_code'),'acc_item', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', true); ?>
              <?php echo render_select('income_account',$account_income,array('id','name','account_type_name'),'income_account', '69', array(), array(), '', '', false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
            <button type="submit" class="btn btn-info btn-submit text-white"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>  
      </div>
   </div>
</div>

<div class="modal fade" id="edit-item-automatic-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('item_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/item_automatic'),array('id'=>'edit-item-automatic-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
              <?php echo render_select('item_id',$_items,array('id','title', 'sku_code'),'acc_item', '',array('disabled' => true), array(), '', '', false); ?>
              <?php echo render_select('income_account',$account_income,array('id','name','account_type_name'),'income_account', '69', array(), array(), '', '', false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
            <button type="submit" class="btn btn-info btn-submit text-white"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('submit'); ?></button>
         </div>
         <?php echo form_close(); ?>  
      </div>
   </div>
</div>


<div class="modal fade" id="tax-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('tax_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/tax_mapping'),array('id'=>'tax-mapping-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         <div class="modal-body">
              <?php 
              echo render_select('tax[]',$taxes,array('id','title'),'tax', '', array(), array(), '', '', true); ?>
              <div class="row">
                <div class="col-md-12">
                  <h5><?php echo app_lang('sales'); ?></h5>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_tax_payment_account,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_tax_deposit_to,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <h5><?php echo app_lang('expenses'); ?></h5>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('expense_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_expense_tax_payment_account,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('expense_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_tax_deposit_to,array(),array(),'','',false); ?>
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

<div class="modal fade" id="edit-tax-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('tax_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/tax_mapping'),array('id'=>'edit-tax-mapping-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
              <?php 
              echo render_select('tax_id',$_taxes,array('id','title', 'taxrate'),'tax', '',array('disabled' => true), array(), '', '', false); ?>
              <div class="col-md-12">
                  <h5><?php echo app_lang('sales'); ?></h5>
                </div>
              <div class="row">
                <div class="col-md-6">
                  <?php echo render_select('payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_tax_payment_account,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_tax_deposit_to,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <h5><?php echo app_lang('expenses'); ?></h5>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('expense_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_expense_tax_payment_account,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-6">
                  <?php echo render_select('expense_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_tax_deposit_to,array(),array(),'','',false); ?>
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

<div class="modal fade" id="expense-category-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('expense_category_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/expense_category_mapping'),array('id'=>'expense-category-mapping-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         <div class="modal-body">
              <?php echo render_select('category[]',$categories,array('id','title'),'category', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
              <div class="row">

                <div class="col-md-12">
                  <?php echo render_select('deposit_to',$account_expense,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_deposit_to,array(),array(),'','',false); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="preferred_payment_method" id="preferred_payment_method" value="1" class="onoffswitch-checkbox form-check-input" checked>
                    <label for="preferred_payment_method"><?php echo _l('acc_preferred_payment_method'); ?></label>
                  </div>
                </div>
              </div>
              <div class="row list-payment-method-mapping">
                
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

<div class="modal fade" id="edit-expense-category-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('expense_category_mapping_setup')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/expense_category_mapping'),array('id'=>'edit-expense-category-mapping-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
              <?php echo render_select('category_id',$_categories,array('id','title'),'category', '',array('disabled' => true), array(), '', '', false); ?>
              <div class="row">
                <div class="col-md-12">
                  <?php echo render_select('deposit_to',$account_expense,array('id','name', 'account_type_name'),'deposit_to',$acc_expense_deposit_to,array(),array(),'','',false); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="preferred_payment_method" id="edit_preferred_payment_method" value="1">
                    <label for="edit_preferred_payment_method"><?php echo _l('acc_preferred_payment_method'); ?></label>
                  </div>
                </div>
              </div>
              <div class="row list-payment-method-mapping">
                
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


<div class="modal fade" id="payment-mode-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('payment_mode_mapping')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/payment_mode_mapping'),array('id'=>'payment-mode-mapping-form', "class" => "general-form", "role" => "form"));?>
         <?php echo form_hidden('id'); ?>
         <div class="modal-body">
              <?php echo render_select('payment_mode[]',$payment_modes,array('id','title', 'payment_moderate'),'payment_method', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', true); ?>
              <div class="row">
                <div class="col-md-12">
                  <h5><?php echo app_lang('sales'); ?></h5>
                </div>

                <div class="col-md-12">
                  <?php echo render_select('deposit_to',$account_cash,array('id','name', 'account_type_name'),'deposit_to',$acc_payment_deposit_to,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <h5><?php echo app_lang('expenses'); ?></h5>
                </div>
                <div class="col-md-12">
                  <?php echo render_select('expense_payment_account',$account_cash,array('id','name', 'account_type_name'),'payment_account',$acc_expense_payment_payment_account,array(),array(),'','',false); ?>
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

<div class="modal fade" id="edit-payment-mode-mapping-modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo app_lang('payment_mode_mapping')?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <?php echo form_open_multipart(get_uri('accounting/payment_mode_mapping'),array('id'=>'edit-payment-mode-mapping-form', 'class' => 'general-form'));?>
         <?php echo form_hidden('id'); ?>
         
         <div class="modal-body">
              <?php echo render_select('payment_mode_id',$_payment_modes,array('id','title', 'payment_moderate'),'payment_method', '',array('disabled' => true), array(), '', '', false); ?>
              <div class="row">
                  <h5><?php echo app_lang('sales'); ?></h5>

                <div class="col-md-12">
                  <?php echo render_select('deposit_to',$account_cash,array('id','name', 'account_type_name'),'deposit_to',$acc_payment_deposit_to,array(),array(),'','',false); ?>
                </div>
                <div class="col-md-12">
                  <h5><?php echo app_lang('expenses'); ?></h5>
                </div>
                <div class="col-md-12">
                  <?php echo render_select('expense_payment_account',$account_cash,array('id','name', 'account_type_name'),'payment_account',$acc_expense_payment_payment_account,array(),array(),'','',false); ?>
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

<?php require 'plugins/Accounting/assets/js/setting/automatic_conversion_js.php'; ?>
