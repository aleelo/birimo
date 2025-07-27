
<div class="card">
    <div class="clearfix">
        <ul id="transaction-sale-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs rounded classic mb20 scrollable-tabs" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo app_lang('fixed_equipment') ?></h4></li>
            <li>
                <a role="presentation" data-bs-toggle="tab" href="<?php echo get_uri('accounting/transaction?group=fixed_equipment&tab=fe_assets'); ?>" data-bs-target="#transaction_assets"><?php echo app_lang('assets'); ?> <span class="text-danger"><?php echo '('.$count_asset.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_fe_licenses_list"); ?>" data-bs-target="#transaction_licenses"><?php echo app_lang('fe_licenses'); ?> <span class="text-danger"><?php echo '('.$count_license.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_fe_components_list"); ?>" data-bs-target="#transaction_components"><?php echo app_lang('fe_components'); ?> <span class="text-danger"><?php echo '('.$count_component.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_fe_consumables_list"); ?>" data-bs-target="#transaction_consumables"><?php echo app_lang('fe_consumables'); ?> <span class="text-danger"><?php echo '('.$count_consumable.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_fe_maintenances_list"); ?>" data-bs-target="#transaction_maintenances"><?php echo app_lang('fe_maintenances'); ?> <span class="text-danger"><?php echo '('.$count_maintenance.')'; ?></span></a></li>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("accounting/transaction_fe_depreciations_list"); ?>" data-bs-target="#transaction_depreciations"><?php echo app_lang('fe_depreciations'); ?> <span class="text-danger"><?php echo '('.$count_depreciation.')'; ?></span></a></li>
        </ul>

        <div class="tab-content p-3">
            <div role="tabpanel" class="tab-pane fade" id="transaction_assets">
               <?php  echo view('Accounting\Views\transaction/fe_assets'); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_components"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_consumables"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_licenses"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_maintenances"></div>
            <div role="tabpanel" class="tab-pane fade" id="transaction_depreciations"></div>
        </div>
    </div>
</div>

