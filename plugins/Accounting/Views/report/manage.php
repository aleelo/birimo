<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
          <h4 class="panel-title bold">
            <?php echo app_lang('business_overview'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_balance_sheet_comparison'); ?>"><h4 class="no-margin"><?php echo app_lang('balance_sheet_comparison'); ?></h4></a>
              <p><?php echo app_lang('balance_sheet_comparison_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_balance_sheet_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('balance_sheet_detail'); ?></h4></a>
              <p><?php echo app_lang('balance_sheet_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_balance_sheet_summary'); ?>"><h4 class="no-margin"><h4 class="no-margin"><?php echo app_lang('balance_sheet_summary'); ?></h4></a>
              <p><?php echo app_lang('balance_sheet_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_balance_sheet'); ?>"><h4 class="no-margin"><h4 class="no-margin"><?php echo app_lang('balance_sheet'); ?></h4></a>
              <p><?php echo app_lang('balance_sheet_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_business_snapshot'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('business_snapshot'); ?></h4></a>
              <p class="hide"><?php echo app_lang('business_snapshot_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_custom_summary_report'); ?>" class="hide"><h4 class="no-margin"><h4 class="no-margin"><?php echo app_lang('custom_summary_report'); ?></h4></a>
              <p class="hide"><?php echo app_lang('custom_summary_report_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_as_of_total_income'); ?>"><h4 class="no-margin"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_as_of_total_income'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_as_of_total_income_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_comparison'); ?>"><h4 class="no-margin"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_comparison'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_comparison_note'); ?></p>
            </div>
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_detail'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_year_to_date_comparison'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_year_to_date_comparison'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_year_to_date_comparison_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_by_customer'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_by_customer'); ?></h4></a>
              <p class="hide"><?php echo app_lang('profit_and_loss_by_customer_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_by_month'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_by_month'); ?></h4></a>
              <p class="hide"><?php echo app_lang('profit_and_loss_by_month_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_12_months'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_12_months'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_12_months_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_quarterly_profit_and_loss_summary'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('quarterly_profit_and_loss_summary'); ?></h4></a>
              <p class="hide"><?php echo app_lang('quarterly_profit_and_loss_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_statement_of_cash_flows'); ?>"><h4 class="no-margin"><?php echo app_lang('statement_of_cash_flows'); ?></h4></a>
              <p><?php echo app_lang('statement_of_cash_flows_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_statement_of_changes_in_equity'); ?>"><h4 class="no-margin"><?php echo app_lang('statement_of_changes_in_equity'); ?></h4></a>
              <p><?php echo app_lang('statement_of_changes_in_equity_note'); ?></p>
            </div>
          </div>
          <hr>
            <h4 class="panel-title">
              <?php echo app_lang('bookkeeping'); ?>
            </h4>
            <div class="row">
               <div class="col-md-6">
                  <a href="<?php echo get_uri('accounting/rp_account_list'); ?>"><h4 class="no-margin"><?php echo app_lang('account_list'); ?></h4></a>
                <p><?php echo app_lang('account_list_note'); ?></p>
                  <a href="<?php echo get_uri('accounting/rp_balance_sheet_comparison'); ?>"><h4 class="no-margin"><?php echo app_lang('balance_sheet_comparison'); ?></h4></a>
                <p><?php echo app_lang('balance_sheet_comparison_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_balance_sheet'); ?>"><h4 class="no-margin"><?php echo app_lang('balance_sheet'); ?></h4></a>
                <p><?php echo app_lang('balance_sheet_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_exceptions_to_closing_date'); ?>"  class="hide"><h4 class="no-margin"><?php echo app_lang('exceptions_to_closing_date'); ?></h4></a>
                <p class="hide"><?php echo app_lang('exceptions_to_closing_date_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_general_ledger'); ?>"><h4 class="no-margin"><?php echo app_lang('general_ledger'); ?></h4></a>
                <p><?php echo app_lang('general_ledger_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_journal'); ?>"><h4 class="no-margin"><?php echo app_lang('journal'); ?></h4></a>
                <p><?php echo app_lang('journal_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_profit_and_loss_comparison'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_comparison'); ?></h4></a>
                <p><?php echo app_lang('profit_and_loss_comparison_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_profit_and_loss'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss'); ?></h4></a>
                <p><?php echo app_lang('profit_and_loss_note'); ?></p>
              </div>
              <div class="col-md-6">
                <a href="<?php echo get_uri('accounting/rp_account_history'); ?>"><h4 class="no-margin"><?php echo app_lang('account_history'); ?></h4></a>
                <p><?php echo app_lang('account_history_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_recent_transactions'); ?>"><h4 class="no-margin"><?php echo app_lang('recent_transactions'); ?></h4></a>
                <p><?php echo app_lang('recent_transactions_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_reconciliation_reports'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('reconciliation_reports'); ?></h4></a>
                <p class="hide"><?php echo app_lang('reconciliation_reports_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_recurring_template_list'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('recurring_template_list'); ?></h4></a>
                <p class="hide"><?php echo app_lang('recurring_template_list_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_statement_of_cash_flows'); ?>"><h4 class="no-margin"><?php echo app_lang('statement_of_cash_flows'); ?></h4></a>
                <p><?php echo app_lang('statement_of_cash_flows_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_transaction_detail_by_account'); ?>"><h4 class="no-margin"><?php echo app_lang('transaction_detail_by_account'); ?></h4></a>
                <p><?php echo app_lang('transaction_detail_by_account_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_transaction_list_by_date'); ?>"><h4 class="no-margin"><?php echo app_lang('transaction_list_by_date'); ?></h4></a>
                <p><?php echo app_lang('transaction_list_by_date_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_transaction_list_with_splits'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('transaction_list_with_splits'); ?></h4></a>
                <p class="hide"><?php echo app_lang('transaction_list_with_splits_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_trial_balance'); ?>"><h4 class="no-margin"><?php echo app_lang('trial_balance'); ?></h4></a>
                <p><?php echo app_lang('trial_balance_note'); ?></p>
              </div>
            </div>
          <hr>  
          <h4 class="panel-title">
            <?php echo app_lang('budget'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_budget_overview'); ?>"><h4 class="no-margin"><?php echo app_lang('budget_overview'); ?></h4></a>
              <p><?php echo app_lang('budget_overview_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_budget_vs_actual'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_budget_vs_actual'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_budget_vs_actual_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_profit_and_loss_budget_performance'); ?>"><h4 class="no-margin"><?php echo app_lang('profit_and_loss_budget_performance'); ?></h4></a>
              <p><?php echo app_lang('profit_and_loss_budget_performance_note'); ?></p>
            </div>
          </div>
          <hr>  
          <h4 class="panel-title">
            <?php echo app_lang('sales_tax'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_tax_detail_report'); ?>"><h4 class="no-margin"><?php echo app_lang('tax_detail_report'); ?></h4></a>
              <p><?php echo app_lang('tax_detail_report_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_tax_exception_report'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('tax_exception_report'); ?></h4></a>
              <p class="hide"><?php echo app_lang('tax_exception_report_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_tax_summary_report'); ?>"><h4 class="no-margin"><?php echo app_lang('tax_summary_report'); ?></h4></a>
              <p><?php echo app_lang('tax_summary_report_note'); ?></p>
            </div>
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_tax_liability_report'); ?>"><h4 class="no-margin"><?php echo app_lang('tax_liability_report'); ?></h4></a>
              <p><?php echo app_lang('tax_liability_report_note'); ?></p>
            </div>
          </div>
          <hr>  
          <h4 class="panel-title">
            <h4><?php echo app_lang('who_owes_you'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_accounts_receivable_ageing_summary'); ?>"><h4 class="no-margin"><?php echo app_lang('accounts_receivable_ageing_summary'); ?></h4></a>
              <p><?php echo app_lang('accounts_receivable_ageing_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_accounts_receivable_ageing_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('accounts_receivable_ageing_detail'); ?></h4></a>
              <p><?php echo app_lang('accounts_receivable_ageing_detail_note'); ?></p>
            </div>
            <div class="col-md-6 hide">
              <a href="<?php echo get_uri('accounting/rp_accounts_payable_ageing_summary'); ?>"><h4 class="no-margin"><?php echo app_lang('accounts_payable_ageing_summary'); ?></h4></a>
              <p><?php echo app_lang('accounts_payable_ageing_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_accounts_payable_ageing_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('accounts_payable_ageing_detail'); ?></h4></a>
              <p><?php echo app_lang('accounts_receivable_ageing_detail_note'); ?></p>
            </div>
          </div>
          <hr>  
          <h4 class="panel-title">
            <?php echo app_lang('sales_and_customers'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_customer_contact_list'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('customer_contact_list'); ?></h4></a>
              <p class="hide"><?php echo app_lang('customer_contact_list_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_deposit_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('deposit_detail'); ?></h4></a>
              <p><?php echo app_lang('deposit_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_estimates_by_customer'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('estimates_by_customer'); ?></h4></a>
              <p class="hide"><?php echo app_lang('estimates_by_customer_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_income_by_customer_summary'); ?>"><h4 class="no-margin"><?php echo app_lang('income_by_customer_summary'); ?></h4></a>
              <p><?php echo app_lang('income_by_customer_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_inventory_valuation_detail'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('inventory_valuation_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('inventory_valuation_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_inventory_valuation_summary'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('inventory_valuation_summary'); ?></h4></a>
              <p class="hide"><?php echo app_lang('inventory_valuation_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_payment_method_list'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('payment_method_list'); ?></h4></a>
              <p class="hide"><?php echo app_lang('payment_method_list_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_product_service_list'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('product_service_list'); ?></h4></a>
              <p class="hide"><?php echo app_lang('product_service_list_note'); ?></p>
            </div>
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_customer_statement'); ?>" class=""><h4 class="no-margin"><?php echo app_lang('customer_statement'); ?></h4></a>
              <p class=""><?php echo app_lang('customer_statement_note'); ?></p>
              
            </div>
            <div class="col-md-6">
              <a href="<?php echo get_uri('accounting/rp_sales_by_customer_detail'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('sales_by_customer_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('sales_by_customer_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_sales_by_customer_summary'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('sales_by_customer_summary'); ?></h4></a>
              <p class="hide"><?php echo app_lang('sales_by_customer_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_sales_by_product_service_detail'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('sales_by_product_service_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('sales_by_product_service_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_sales_by_product_service_summary'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('sales_by_product_service_summary'); ?></h4></a>
              <p class="hide"><?php echo app_lang('sales_by_product_service_summary_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_stock_take_worksheet'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('stock_take_worksheet'); ?></h4></a>
              <p class="hide"><?php echo app_lang('stock_take_worksheet_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_time_activities_by_customer_detail'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('time_activities_by_customer_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('time_activities_by_customer_detail_note'); ?></p>
              <a href="<?php echo get_uri('accounting/rp_transaction_list_by_customer'); ?>" class="hide"><h4 class="no-margin"><?php echo app_lang('transaction_list_by_customer'); ?></h4></a>
              <p class="hide"><?php echo app_lang('transaction_list_by_customer_note'); ?></p>
            </div>
          </div>
          <hr>
          <h4 class="panel-title">
            <?php echo app_lang('expenses_and_suppliers'); ?>
          </h4>
          <div class="row">
            <div class="col-md-6">
                <a href="<?php echo get_uri('accounting/rp_check_detail'); ?>"><h4 class="no-margin"><?php echo app_lang('cheque_detail'); ?></h4></a>
              <p><?php echo app_lang('check_detail_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_expenses_by_supplier_summary'); ?>" class="hide"><h4><?php echo app_lang('expenses_by_supplier_summary'); ?></h4></a>
              <p class="hide"><?php echo app_lang('expenses_by_supplier_summary_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_purchases_by_product_service_detail'); ?>" class="hide"><h4><?php echo app_lang('purchases_by_product_service_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('purchases_by_product_service_detail_note'); ?></p>
            </div>
            <div class="col-md-6">
                <a href="<?php echo get_uri('accounting/rp_purchases_by_supplier_detail'); ?>" class="hide"><h4><?php echo app_lang('purchases_by_supplier_detail'); ?></h4></a>
              <p class="hide"><?php echo app_lang('purchases_by_supplier_detail_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_supplier_contact_list'); ?>" class="hide"><h4><?php echo app_lang('supplier_contact_list'); ?></h4></a>
              <p class="hide"><?php echo app_lang('supplier_contact_list_note'); ?></p>
                <a href="<?php echo get_uri('accounting/rp_transaction_list_by_supplier'); ?>" class="hide"><h4><?php echo app_lang('transaction_list_by_supplier'); ?></h4></a>
              <p class="hide"><?php echo app_lang('transaction_list_by_supplier_note'); ?></p>
            </div>
          </div>
        </div>
    </div>
</div>
