<div class="card">
    <div class="clearfix">
        <ul id="transaction-sale-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('warehouse') ?></h4></li>
            <li>
                <a role="presentation" data-bs-toggle="tab" href="<?php echo get_uri('accounting/transaction?group=warehouse&tab=stock_import'); ?>" data-bs-target="#transaction_stock_import"><?php echo app_lang('stock_import'); ?> <span class="text-danger"><?php echo '('.$count_stock_import.')'; ?></span></a></li>
            
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_stock_export_list"); ?>" data-bs-target="#transaction_stock_export"><?php echo app_lang('stock_export'); ?> <span class="text-danger"><?php echo '('.$count_stock_export.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_loss_adjustment_list"); ?>" data-bs-target="#transaction_loss_adjustment"><?php echo app_lang('loss_adjustment'); ?> <span class="text-danger"><?php echo '('.$count_loss_adjustment.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_opening_stock_list"); ?>" data-bs-target="#transaction_opening_stock"><?php echo app_lang('opening_stock'); ?> <span class="text-danger"><?php echo '('.$count_opening_stock.')'; ?></span></a></li>
        </ul>

        <div class="tab-content p-3">
            <div role="tabpanel" class="tab-pane fade" id="transaction_stock_import">
               <?php  echo view('Accounting\Views\transaction/stock_import'); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_loss_adjustment"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_stock_export"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_opening_stock"></div>
        </div>
    </div>
</div>