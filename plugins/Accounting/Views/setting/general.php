<?php 
  $acc_first_month_of_financial_year = get_setting('acc_first_month_of_financial_year');
  $acc_first_month_of_tax_year = get_setting('acc_first_month_of_tax_year');
  $acc_accounting_method = get_setting('acc_accounting_method');
  $acc_close_the_books = get_setting('acc_close_the_books');
  $acc_closing_date = get_setting('acc_closing_date');
  $acc_allow_changes_after_viewing = get_setting('acc_allow_changes_after_viewing');
  $acc_close_book_password = get_setting('acc_close_book_password');
  $acc_close_book_passwordr = get_setting('acc_close_book_passwordr');
  $acc_enable_account_numbers = get_setting('acc_enable_account_numbers');
  $acc_show_account_numbers = get_setting('acc_show_account_numbers');
  $acc_automatic_conversion = get_setting('acc_automatic_conversion');

  $acc_invoice_payment_account = get_setting('acc_invoice_payment_account');
  $acc_invoice_deposit_to = get_setting('acc_invoice_deposit_to');
  $acc_payment_payment_account = get_setting('acc_payment_payment_account');
  $acc_payment_deposit_to = get_setting('acc_payment_deposit_to');
  $acc_expense_payment_account = get_setting('acc_expense_payment_account');
  $acc_expense_deposit_to = get_setting('acc_expense_deposit_to');
 ?>
 <?php echo form_open(get_uri('accounting/reset_data')); ?>
<div class="row mbot10 hide">
    <div class="col-md-12">
      <button type="submit" class="btn btn-info _delete text-white"><?php echo app_lang('reset_data'); ?></button> <label class="text-danger"><?php echo app_lang('accounting_reset_button_tooltip'); ?></label>
  </div>
<hr>
</div>
<?php echo form_close(); ?>
<?php echo form_open(get_uri('accounting/update_general_setting'),array('id'=>'general-settings-form', "class" => "general-form", "role" => "form")); ?>
<div class="row">
  <div class="col-md-12">
    <div class="col-md-6 row">
      <h5 class="title mbot5"><?php echo app_lang('als_accounting') ?></h5>
        <div class="row">
          <div class="col-md-12">
          <?php
              $month = [
                        'January' => 'January',
                        'February' => 'February',
                        'March' => 'March',
                        'April' => 'April',
                        'May' => 'May',
                        'June' => 'June',
                        'July' => 'July',
                        'August' => 'August',
                        'September' => 'September',
                        'October' => 'October',
                        'November' => 'November',
                        'December' => 'December',
                    ];
              ?>

              <div class="form-group">
                  <label for="acc_first_month_of_financial_year" class=""><?php echo app_lang('first_month_of_financial_year'); ?></label>
                  <?php
                      echo form_dropdown("acc_first_month_of_financial_year", $month, array($acc_first_month_of_financial_year), "class='select2 validate-hidden' id='acc_first_month_of_financial_year'");
                  ?>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          <?php
              $month_of_tax_year = [
                  'same_as_financial_year' => app_lang('same_as_financial_year'),
                  'January' => 'January',
                 ];
               ?>
               <div class="form-group">
                  <label for="acc_first_month_of_tax_year" class=""><?php echo app_lang('first_month_of_tax_year'); ?></label>
                  <?php
                      echo form_dropdown("acc_first_month_of_tax_year", $month_of_tax_year, array($acc_first_month_of_tax_year), "class='select2 validate-hidden' id='acc_first_month_of_tax_year'");
                  ?>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          <?php
              $method = [
                          'cash' => app_lang('cash'),
                          'accrual' => app_lang('accrual'),
                         ];
                ?>
              <div class="form-group">
                  <label for="acc_accounting_method" class=""><?php echo app_lang('accounting_method'); ?></label>
                  <?php
                      echo form_dropdown("acc_accounting_method", $method, array($acc_accounting_method), "class='select2 validate-hidden' id='acc_accounting_method'");
                  ?>
              </div>

              <p><i class="detail_type_note_1"></i></p>
              <p><i class="detail_type_note_2"></i></p>
              <p><i class="detail_type_note_3"></i></p>
          </div>
          <div class="hide">
            <i id="detail_type_note_cash_1"><?php echo app_lang('cash_method_note_1'); ?></i>
            <i id="detail_type_note_cash_2"><?php echo app_lang('cash_method_note_2'); ?></i>
            <i id="detail_type_note_cash_3"><?php echo app_lang('cash_method_note_3'); ?></i>

            <i id="detail_type_note_accrual_1"><?php echo app_lang('accrual_method_note_1'); ?></i>
            <i id="detail_type_note_accrual_2"><?php echo app_lang('accrual_method_note_2'); ?></i>
            <i id="detail_type_note_accrual_3"><?php echo app_lang('accrual_method_note_3'); ?></i>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo app_lang('close_the_books'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo app_lang('close_the_books_note'); ?>"></i></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_close_the_books" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_close_the_books == '1'){echo 'checked';} ?>  value="1" name="acc_close_the_books">
                  <label class="onoffswitch-label" for="acc_close_the_books"></label>
              </div>
          </div>
      </div>
      <div id="div_close_the_books" class="mleft25 <?php if($acc_close_the_books != '1'){echo 'hide';} ?>">
          <?php 
          echo render_date_input('acc_closing_date', 'closing_date', $acc_closing_date) ?>
          <?php
              $allow_changes_after_viewing = [
                          'allow_changes_after_viewing_a_warning' => app_lang('allow_changes_after_viewing_a_warning'),
                         ];
              ?>
            <div class="form-group">
                  <?php
                      echo form_dropdown("acc_allow_changes_after_viewing", $allow_changes_after_viewing, array($acc_allow_changes_after_viewing), "class='select2 validate-hidden' id='acc_allow_changes_after_viewing'");
                  ?>
              </div>
          <div id="div_close_book_password" class="<?php if($acc_allow_changes_after_viewing == 'allow_changes_after_viewing_a_warning'){echo 'hide';} ?>">
            <div class="form-group register-password-group">
                <label class="control-label" for="acc_close_book_password"><?php echo app_lang('clients_register_password'); ?></label>
                <input type="password" class="form-control" name="acc_close_book_password" id="acc_close_book_password" autocomplete="off" value="<?php echo html_entity_decode($acc_close_book_password); ?>">
            </div>
            <div class="form-group register-password-repeat-group">
                <label class="control-label" for="acc_close_book_passwordr"><?php echo app_lang('clients_register_password_repeat'); ?></label>
                <input type="password" class="form-control" name="acc_close_book_passwordr" id="acc_close_book_passwordr" autocomplete="off" value="<?php echo html_entity_decode($acc_close_book_passwordr); ?>">
            </div>
          </div>
      </div>
    </div>
    <div class="col-md-6">
      <h5 class="title mbot5"><?php echo app_lang('chart_of_accounts') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo app_lang('enable_account_numbers'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo app_lang('chart_of_accounts_note'); ?>"></i></span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="acc_enable_account_numbers" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($acc_enable_account_numbers == '1'){echo 'checked';} ?>  value="1" name="acc_enable_account_numbers">
                  <label class="onoffswitch-label" for="acc_enable_account_numbers"></label>
              </div>
          </div>
      </div>
      <div id="div_enable_account_numbers" class="mleft25 <?php if($acc_enable_account_numbers != '1'){echo 'hide';} ?>">
        <div class="form-group">
          <div class="checkbox checkbox-primary">
            <input type="checkbox" name="acc_show_account_numbers" <?php if($acc_show_account_numbers == '1'){echo 'checked';} ?> id="wd_monday" value="1" class="form-check-input">
            <label for="wd_monday"><?php echo app_lang('show_account_numbers'); ?> <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo app_lang('show_account_numbers_note'); ?>"></i></label>
          </div>
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
