<div class="card">
    <div class="clearfix p-3">
  <div class="row general-form">
    <div class="col-md-3">
          <?php echo render_select('product',$products,array('id','title'),'products', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
    </div>
    <div class="col-md-3">
          <?php echo render_select('routing',$routings,array('id','routing_name'),'routing', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
    </div>
    <div class="col-md-3">
      <?php $status = [ 
            1 => ['id' => 'converted', 'name' => _l('acc_converted')],
            2 => ['id' => 'has_not_been_converted', 'name' => _l('has_not_been_converted')],
          ]; 
          ?>
          <?php echo render_select('status',$status,array('id','name'),'status', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
    </div>
  </div>
  <a href="#" onclick="manufacturing_order_transaction_bulk_actions(); return false;" data-toggle="modal" data-target="#manufacturing_order_bulk_actions" class="hide bulk-actions-btn table-btn" data-table=".table-manufacturing-order" class="form-check-input"><?php echo _l('bulk_actions'); ?></a>
  <table class="table table-manufacturing-order">
    <thead>
      <th><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="manufacturing-order" class="form-check-input"><label></label></div></th>
      <th><?php echo _l('manufacturing_order_code'); ?></th>
      <th><?php echo _l('product_label'); ?></th>
      <th><?php echo _l('bill_of_material_label'); ?></th>
      <th><?php echo _l('product_qty'); ?></th>
      <th><?php echo _l('unit_id'); ?></th>
      <th><?php echo _l('routing_label'); ?></th>
      <th><?php echo _l('status_label'); ?></th>
      <th><?php echo _l('mapping_status'); ?></th>
      <th><?php echo _l('acc_convert'); ?></th>
    </thead>
    <tbody>
      
    </tbody>
  </table>
</div>
</div>

<div class="modal fade bulk_actions" id="manufacturing_order_bulk_actions" tabindex="-1" role="dialog" data-table=".table-manufacturing-order">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
          <?php echo form_hidden('bulk_actions_type', 'manufacturing_order'); ?>
            <?php if(acc_has_permission('acc_can_create_transaction')){ ?>
               <div class="checkbox checkbox-info">
                  <input type="checkbox" name="mass_convert" id="mass_convert" checked class="form-check-input">
                  <label for="mass_convert"><?php echo _l('mass_convert'); ?></label>
               </div>
            <?php } ?>
            <?php if(acc_has_permission('acc_can_delete_transaction')){ ?>
               <div class="checkbox checkbox-danger">
                  <input type="checkbox" name="mass_delete_convert" id="mass_delete_convert" class="form-check-input">
                  <label for="mass_delete_convert"><?php echo _l('mass_delete_convert'); ?></label>
               </div>
            <?php } ?>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i> <?php echo app_lang('close'); ?></button>
          <a type="submit" onclick="manufacturing_order_bulk_action(this); return false;" class="btn btn-info btn-submit text-white"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('confirm'); ?></a>
      </div>
   </div>
   <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php require 'plugins/Accounting/assets/js/transaction/manufacturing_js.php';?>
