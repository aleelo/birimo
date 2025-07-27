<div id="page-content" class="page-wrapper clearfix">
    <?php echo acc_form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
          <?php $arrAtt = array();
                $arrAtt['data-type']='currency';
                $arrAtt['data-msg-required']=app_lang("field_required");

                $arrAtt2 = array();
                $arrAtt2['data-type']='currency';
                $arrAtt2['readonly']='true';

                $arrAtt3 = array();
                $arrAtt3['data-msg-required']=app_lang("field_required");

                ?>
          <?php echo form_open(get_uri('accounting/reconcile'),array('id'=>'reconcile-account-form','autocomplete'=>'off', "class" => "general-form", "role" => "form")); ?>
          <?php echo acc_form_hidden('resume', $resume); ?>
          <p ><?php echo app_lang('open_your_statement_and_we_will_get_started'); ?></p>
            <h5><?php echo app_lang('which_account_do_you_want_to_reconcile'); ?></h5>
            <?php echo render_select('account',$accounts,array('id','name', 'account_type_name'),'acc_account','',array(),array(),'','',false); ?>
            <div id="divInfo" class="<?php if($resume == 1){echo 'hide';} ?>">
            <br>
            <h5><?php echo app_lang('add_the_following_information'); ?></h5>
            <div class="row">
              <div class="col-md-4">
                <?php echo render_input('beginning_balance','beginning_balance', to_currency($beginning_balance, $currency_symbol),'text', $arrAtt2); ?>
              </div>
              <div class="col-md-4">
                <?php echo render_input('ending_balance','ending_balance','','text', $arrAtt); ?>
              </div>
              <div class="col-md-4">
                <?php echo render_date_input('ending_date','ending_date', '', $arrAtt3); ?>
              </div>
            </div>
              <br>
              <h5 class="hide"><?php echo app_lang('enter_the_service_charge_or_interest_earned_if_necessary'); ?></h5>
              <div class="row hide">
                <div class="col-md-4">
                  <?php echo render_date_input('expense_date','invoice_payments_table_date_heading'); ?>
                </div>
                <div class="col-md-4">
                  <?php echo render_input('service_charge','service_charge','','text'); ?>
                </div>
                <div class="col-md-4">
                  <?php echo render_select('expense_account',$accounts,array('id','name', 'account_type_name'),'expense_account','',array(),array(),'','',false); ?>
                </div>
              </div>
              <div class="row hide">
                <div class="col-md-4">
                  <?php echo render_date_input('income_date','invoice_payments_table_date_heading'); ?>
                </div>
                <div class="col-md-4">
                  <?php echo render_input('interest_earned','interest_earned','','text'); ?>
                </div>
                <div class="col-md-4">
                  <?php echo render_select('income_account',$accounts,array('id','name', 'account_type_name'),'income_account','',array(),array(),'','',false); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <hr>
                    <button type="button" class="btn_start_reconciling btn btn-info pull-right text-white <?php if(!acc_has_permission('acc_can_create_reconcile')){echo 'hide';} ?>"><i data-feather='play' class='icon-16'></i> <?php echo app_lang('start_reconciling'); ?></button>
                    <button type="button" class="btn btn-warning pull-right text-white mright5 hide_restored <?php echo html_entity_decode($hide_restored); ?> <?php if(!acc_has_permission('acc_can_edit_reconcile')){echo 'hide';} ?>"><i data-feather='corner-up-left' class='icon-16'></i> <?php echo app_lang('acc_restored_last'); ?></button>
                </div>
              </div>
            </div>
            <div id="divResume" class="<?php if($resume == 0){echo 'hide';} ?>">
              <div class="row">
                <div class="col-md-12">
                    <hr>
                    <button type="submit" class="btn btn-info pull-right text-white <?php if(!acc_has_permission('acc_can_edit_reconcile')){echo 'hide';} ?>"><i data-feather='play' class='icon-16'></i> <?php echo app_lang('resume_reconciling'); ?></button>
                    <button type="button" class="btn btn-warning pull-right text-white mright5 hide_restored <?php echo html_entity_decode($hide_restored); ?> <?php if(!acc_has_permission('acc_can_edit_reconcile')){echo 'hide';} ?>"><i data-feather='corner-up-left' class='icon-16'></i> <?php echo app_lang('acc_restored_last'); ?></button>
                   
                </div>
              </div>
            </div>
          <?php echo form_close(); ?>
    </div>
  </div>
</div>
