<div class="row">
  
<div class="col-md-12">
  <a href="<?php echo admin_url('accounting/import_xlsx_posted_bank_transactions'); ?>" class="btn btn-success mr-4 button-margin-r-b pull-right <?php if(!acc_has_permission('acc_can_create_banking')){echo 'hide';} ?>" title="<?php echo _l('import_excel') ?> ">
    <i data-feather="upload" class="icon-16"></i> <?php echo _l('import_excel'); ?>
  </a>
</div>
</div>
  <div class="mbot25 text-center"><h4><?php echo _l('posted_transactions_from_your_bank_account'); ?></h4></div>
  <table class="table table-banking">
  </table>
  
<?php require 'plugins/Accounting/assets/js/banking/posted_bank_transactions_js.php';?>
