<div class="card">
    <div class="clearfix">
        <ul id="transaction-sale-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('purchase') ?></h4></li>
            <li>
                <a role="presentation" data-bs-toggle="tab" href="<?php echo get_uri('accounting/transaction?group=purchase&tab=purchase_order'); ?>" data-bs-target="#purchase_order"><?php echo app_lang('purchase_order'); ?> <span class="text-danger"><?php echo '('.$count_purchase_order.')'; ?></span></a></li>
                
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction?group=purchase&tab=purchase_invoice"); ?>" data-bs-target="#purchase_invoice"><?php echo app_lang('purchase_invoice'); ?> <span class="text-danger"><?php echo '('.$count_purchase_invoice.')'; ?></span></a></li>

            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction?group=purchase&tab=purchase_payment"); ?>" data-bs-target="#purchase_payment"><?php echo app_lang('purchase_payment'); ?> <span class="text-danger"><?php echo '('.$count_purchase_payment.')'; ?></span></a></li>

        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="purchase_order">

                <?php echo view('Accounting\Views\transaction\purchase_order'); ?>
     
            </div>
            <div role="tabpanel" class="tab-pane fade" id="purchase_invoice">
                 <?php echo view('Accounting\Views\transaction\purchase_invoice'); ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="purchase_payment">
                <?php echo view('Accounting\Views\transaction\purchase_payment'); ?>
            </div>
        </div>
    </div>
</div>

<?php require 'plugins/Accounting/assets/js/transaction/purchase_js.php'; ?>