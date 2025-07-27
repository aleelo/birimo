<?php
$db = db_connect('default');
$dbprefix = get_db_prefix();

if ($db->tableExists($dbprefix . 'acc_accounts')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_accounts`;');
}

if ($db->tableExists($dbprefix . 'acc_account_history')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_account_history`;');
}

if ($db->tableExists($dbprefix . 'acc_transfers')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_transfers`;');
}

if ($db->tableExists($dbprefix . 'acc_journal_entries')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_journal_entries`;');
}

if ($db->tableExists($dbprefix . 'acc_transaction_bankings')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_transaction_bankings`;');
}

if ($db->tableExists($dbprefix . 'acc_reconciles')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_reconciles`;');
}

if ($db->tableExists($dbprefix . 'acc_banking_rules')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_banking_rules`;');
}

if ($db->tableExists($dbprefix . 'acc_banking_rule_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_banking_rule_details`;');
}

if ($db->tableExists($dbprefix . 'acc_item_automatics')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_item_automatics`;');
}

if ($db->tableExists($dbprefix . 'acc_tax_mappings')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_tax_mappings`;');
}

if ($db->tableExists($dbprefix . 'acc_expense_category_mappings')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_expense_category_mappings`;');
}

if ($db->tableExists($dbprefix . 'acc_payment_mode_mappings')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_payment_mode_mappings`;');
}

if ($db->tableExists($dbprefix . 'acc_account_type_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_account_type_details`;');
}

if ($db->tableExists($dbprefix . 'acc_budgets')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_budgets`;');
}

if ($db->tableExists($dbprefix . 'acc_budget_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_budget_details`;');
}

if ($db->tableExists($dbprefix . 'acc_plaid_transaction_logs')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_plaid_transaction_logs`;');
}

if ($db->tableExists($dbprefix . 'acc_matched_transactions')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_matched_transactions`;');
}

if ($db->tableExists($dbprefix . 'acc_expense_category_mapping_details')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_expense_category_mapping_details`;');
}

if ($db->tableExists($dbprefix . 'acc_bank_reconciles')) {
    $db->query('DROP TABLE `'.$dbprefix .'acc_bank_reconciles`;');
}