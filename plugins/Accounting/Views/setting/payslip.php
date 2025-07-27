<?php 

  $acc_pl_total_insurance_automatic_conversion = get_setting('acc_pl_total_insurance_automatic_conversion');
  $acc_pl_total_insurance_payment_account = get_setting('acc_pl_total_insurance_payment_account');
  $acc_pl_total_insurance_deposit_to = get_setting('acc_pl_total_insurance_deposit_to');

  $acc_pl_tax_paye_automatic_conversion = get_setting('acc_pl_tax_paye_automatic_conversion');
  $acc_pl_tax_paye_payment_account = get_setting('acc_pl_tax_paye_payment_account');
  $acc_pl_tax_paye_deposit_to = get_setting('acc_pl_tax_paye_deposit_to');


  $acc_pl_net_pay_automatic_conversion = get_setting('acc_pl_net_pay_automatic_conversion');
  $acc_pl_net_pay_payment_account = get_setting('acc_pl_net_pay_payment_account');
  $acc_pl_net_pay_deposit_to = get_setting('acc_pl_net_pay_deposit_to');
 ?>

<?php echo form_open(admin_url('accounting/update_payslip_automatic_conversion'),array('id'=>'payslip-mapping-setup-form', 'class' => 'general-form')); ?>
<div class="row">
  <div class="col-md-12">
    <h4><?php echo _l('automatic_conversion'); ?></h4>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('ps_total_insurance') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pl_total_insurance_automatic_conversion" data-perm-id="3" class="form-check-input" <?php if($acc_pl_total_insurance_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pl_total_insurance_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pl_total_insurance_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pl_total_insurance_automatic_conversion == 0){echo 'hide';} ?>" id="div_pl_total_insurance_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pl_total_insurance_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pl_total_insurance_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pl_total_insurance_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pl_total_insurance_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('ps_income_tax_paye') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pl_tax_paye_automatic_conversion" data-perm-id="3" class="form-check-input" <?php if($acc_pl_tax_paye_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pl_tax_paye_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pl_tax_paye_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pl_tax_paye_automatic_conversion == 0){echo 'hide';} ?>" id="div_pl_tax_paye_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pl_tax_paye_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pl_tax_paye_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pl_tax_paye_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pl_tax_paye_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
      <div class="div_content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6 border-right">
                <h5 class="title mbot5"><?php echo _l('ps_net_pay') ?></h5>
              </div>
              <div class="col-md-6 mtop5">
                  <div class="onoffswitch">
                      <input type="checkbox" id="acc_pl_net_pay_automatic_conversion" data-perm-id="3" class="form-check-input" <?php if($acc_pl_net_pay_automatic_conversion == '1'){echo 'checked';} ?>  value="1" name="acc_pl_net_pay_automatic_conversion">
                      <label class="onoffswitch-label" for="acc_pl_net_pay_automatic_conversion"></label>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row <?php if($acc_pl_net_pay_automatic_conversion == 0){echo 'hide';} ?>" id="div_pl_net_pay_automatic_conversion">
          <div class="col-md-6">
            <?php echo render_select('acc_pl_net_pay_payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$acc_pl_net_pay_payment_account,array(),array(),'','',false); ?>
          </div>
          <div class="col-md-6">
            <?php echo render_select('acc_pl_net_pay_deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$acc_pl_net_pay_deposit_to,array(),array(),'','',false); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<hr>
  <div class="col-md-12">
<button type="submit" class="btn btn-info pull-right text-white <?php if(!acc_has_permission('acc_can_edit_setting')){echo 'hide';} ?>"><i data-feather="check-circle" class="icon-16"></i> <?php echo app_lang('submit'); ?></button>
  </div>
<?php echo form_close(); ?>

<?php require 'plugins/Accounting/assets/js/setting/automatic_conversion_js.php'; ?>
