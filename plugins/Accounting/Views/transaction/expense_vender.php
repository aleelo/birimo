<div class="card">
    <div class="clearfix">
        <ul id="transaction-sale-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('expenses_vendor') ?></h4></li>
            <li>
                <a role="presentation" data-bs-toggle="tab" data-bs-toggle="tab" href="<?php echo get_uri('accounting/transaction?group=sales&tab=expenses'); ?>" data-bs-target="#transaction_payments"><?php echo app_lang('vender_expense'); ?> <span class="text-danger"><?php echo '('.$count_expense.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_payment_vender_list"); ?>" data-bs-target="#transaction_invoices"><?php echo app_lang('vendor_expense_payment'); ?> <span class="text-danger"><?php echo '('.$count_expense_payment.')'; ?></span></a></li>
        </ul>

        <div class="tab-content p-3">
            <div role="tabpanel" class="tab-pane fade" id="transaction_payments">
               <?php  echo view('Accounting\Views\transaction/expenses'); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_invoices"></div>
        </div>
    </div>
</div>
<?php require 'plugins/Accounting/assets/js/transaction/payment_js.php'; ?>
