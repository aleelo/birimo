<?php echo form_open(get_uri('accounting/add_transfer'),array('id'=>'transfer-form', "class" => "general-form", "role" => "form"));?>
 <?php echo acc_form_hidden('id', $id); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
      <div class="form-group">
          <div class="row">
              <label for="transfer_funds_from" class=" col-md-3"><?php echo app_lang('transfer_funds_from'); ?></label>
              <div class="col-md-9">
                  <?php
                  $value = isset($model_info) ? $model_info->transfer_funds_from : "";
                  echo form_dropdown("transfer_funds_from", $accounts_dropdown, array($value), "class='select2 validate-hidden' id='transfer_funds_from' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                  ?>
              </div>
          </div>
      </div>
      <div class="form-group">
          <div class="row">
              <label for="transfer_funds_to" class=" col-md-3"><?php echo app_lang('transfer_funds_to'); ?></label>
              <div class="col-md-9">
                  <?php
                  $value = isset($model_info) ? $model_info->transfer_funds_to : "";
                  echo form_dropdown("transfer_funds_to", $accounts_dropdown, array($value), "class='select2 validate-hidden' id='transfer_funds_to' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                  ?>
              </div>
          </div>
      </div>
      <div class="form-group">
        <div class="row">
          <label for="date" class=" col-md-3"><?php echo app_lang('date'); ?></label>
          <div class="col-md-9">
              <?php
              echo form_input(array(
                  "id" => "date",
                  "name" => "date",
                  "value" => isset($model_info) ? $model_info->date : get_my_local_time("Y-m-d"),
                  "class" => "form-control recurring_element",
                  "placeholder" => app_lang('date'),
                  "autocomplete" => "off",
                  "data-rule-required" => true,
                  "data-msg-required" => app_lang("field_required"),
              ));
              ?>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
            <label for="transfer_amount" class=" col-md-3"><?php echo app_lang('transfer_amount'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "transfer_amount",
                    "name" => "transfer_amount",
                    "value" => isset($model_info) ? $model_info->transfer_amount : "",
                    "class" => "form-control",
                    "placeholder" => app_lang('transfer_amount'),
                    "data-rule-required" => true,
                    "data-msg-required" => app_lang("field_required"),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label for="description" class=" col-md-3"><?php echo app_lang('description'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => isset($model_info) ? $model_info->description : "",
                    "class" => "form-control",
                    "placeholder" => app_lang('note'),
                    "data-rich-text-editor" => true
                ));
                ?>
            </div>
        </div>
    </div>
 </div>
 </div>

 <div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
</div>
 <?php echo form_close(); ?>  
<?php require 'plugins/Accounting/assets/js/transfer/transfer_form_js.php'; ?>
