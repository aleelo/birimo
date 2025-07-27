
<?php 
  $file_header = array();
$file_header[] = _l('acc_name');  
$file_header[] = _l('acc_year');
$file_header[] = _l('acc_type');
$file_header[] = _l('acc_month');
$file_header[] = _l('quarter');
$file_header[] = _l('acc_account');
$file_header[] = _l('acc_amount');
 ?>

<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', base_url()); ?>
    <?php echo form_hidden('admin_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
            <div id="dowload_file_sample">
                
            </div>
            <hr>
            <div class="row">
              <div class="col-md-4">
               <?php echo form_open_multipart(get_uri('accounting/import_account_excel'),array('id'=>'import_form', "class" => "general-form"));?>
                  <?php echo render_input('fiscal_year_for_this_budget','fiscal_year_for_this_budget',date('Y'),'number'); ?>
                  <?php echo _l('year_and_type_note_1'); ?>
                  <br>
                  <?php echo _l('year_and_type_note_2'); ?>
                  <br>
                  <div class="form-group">
                    <div class="radio radio-primary">
                      <input type="radio" id="profit_and_loss_accounts" name="budget_type" value="profit_and_loss_accounts" checked class="form-check-input">
                      <label for="profit_and_loss_accounts"><?php echo _l('profit_and_loss_accounts'); ?></label>
                    </div>

                    <div class="radio radio-primary">
                      <input type="radio" id="balance_sheet_accounts" name="budget_type" value="balance_sheet_accounts" class="form-check-input">
                      <label for="balance_sheet_accounts"><?php echo _l('balance_sheet_accounts'); ?></label>
                    </div>
                  </div>
                    <?php 
                      $import_type = [
                        1 => ['id' => 'month', 'name' => _l('month')],
                        2 => ['id' => 'quarter', 'name' => _l('quarter')],
                        3 => ['id' => 'year', 'name' => _l('year')]
                      ];

                      echo render_select('import_type', $import_type, array('id', 'name'),'type', 'month', array('required' => true), array(), '', '', false); ?>
                    <?php echo render_input('file_csv','choose_excel_file','','file'); ?>
                    <div class="form-group">
                      <button id="uploadfile" type="button" class="btn btn-info import text-white" onclick="return uploadfilecsv();" ><i data-feather='upload' class='icon-16'></i> <?php echo _l('import'); ?></button>
                    </div>
                  <?php echo form_close(); ?>
              </div>
              <div class="col-md-8">
                <div class="form-group" id="file_upload_response">
                  
                </div>
                
              </div>
            </div>
            <?php if(!isset($simulate)) { ?>
            <ul>
              <li class="text-danger"><i class="font-italic">1. <?php echo _l('file_xlsx_budget'); ?></i></li>
              <li class="text-danger"><i class="font-italic">2. <?php echo _l('file_xlsx_budget_1'); ?></i></li>
              <li class="text-danger"><i class="font-italic">3. <?php echo _l('file_xlsx_budget_2'); ?></i></li>
              <li class="text-danger"><i class="font-italic">4. <?php echo _l('file_xlsx_budget_3'); ?></i></li>
            </ul>

              <?php } ?>
    </div>
  </div>
</div>
<!-- box loading -->
<div id="box-loading"></div>

<?php require 'plugins/Accounting/assets/js/budget/import_excel_budget_js.php';?>
</body>
</html>
