<?php

$db = db_connect('default');
$dbprefix = get_db_prefix();

/**
 * Add setting
 *
 * @since  Version 1.0.0
 *
 * @param string  $name      Option name (required|unique)
 * @param string  $value     Option value
 *
 */

if (!function_exists('add_setting')) {

  function add_setting($name, $value = '')
  {
      if (!setting_exists($name)) {
        $db = db_connect('default');
        $db_builder = $db->table(get_db_prefix() . 'settings');
        $newData = [
                'setting_name'  => $name,
                'setting_value' => $value,
            ];

        $db_builder->insert($newData);

        $insert_id = $db->insertID();

        if ($insert_id) {
            return true;
        }

        return false;
      }

      return false;
  }
}

/**
 * @since  1.0.0
 * Check whether an setting exists
 *
 * @param  string $name setting name
 *
 * @return boolean
 */
if (!function_exists('setting_exists')) {

  function setting_exists($name)
  { 
   
    $db = db_connect('default');
    $db_builder = $db->table(get_db_prefix() . 'settings');

    $count = $db_builder->where('setting_name', $name)->countAllResults();

    return $count > 0;
  }
}


/**
 * check account exists
 * @param  string $key_name 
 * @return boolean or integer           
 */
if (!function_exists('acc_account_exists')) {
  function acc_account_exists($key_name){
    $db = db_connect('default');

    $Accounting_model = model("Accounting\Models\Accounting_model");
    if(get_setting('acc_add_default_account') == '0'){
    }

    $sql = 'select * from '.get_db_prefix().'acc_accounts where key_name = "'.$key_name.'"';
    $account = $db->query($sql)->getRow();

    if($account){
      return $account->id;
    }else{
      return false;
    }
  }
}

  /**
   * add default account
   */
if (!function_exists('acc_add_default_account')) {
    function acc_add_default_account(){
        $db = db_connect('default');
        $db_builder = $db->table(get_db_prefix().'acc_accounts');

        if($db_builder->countAllResults() > 1){
            return false;
        }

        $accounts = [
            [
                'name' => '',
                'key_name' => 'acc_accounts_receivable',
                'account_type_id' => 1,
                'account_detail_type_id' => 1,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_accrued_holiday_payable',
                'account_type_id' => 9,
                'account_detail_type_id' => 61,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_accrued_liabilities',
                'account_type_id' => 8,
                'account_detail_type_id' => 44,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_accrued_non_current_liabilities',
                'account_type_id' => 9,
                'account_detail_type_id' => 62,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_accumulated_depreciation_on_property_plant_and_equipment',
                'account_type_id' => 4,
                'account_detail_type_id' => 22,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_allowance_for_bad_debts',
                'account_type_id' => 2,
                'account_detail_type_id' => 2,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_amortisation_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 106,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_assets_held_for_sale',
                'account_type_id' => 5,
                'account_detail_type_id' => 32,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_available_for_sale_assets_short_term',
                'account_type_id' => 2,
                'account_detail_type_id' => 3,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_bad_debts',
                'account_type_id' => 14,
                'account_detail_type_id' => 108,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_bank_charges',
                'account_type_id' => 14,
                'account_detail_type_id' => 109,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_billable_expense_income',
                'account_type_id' => 11,
                'account_detail_type_id' => 89,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_cash_and_cash_equivalents',
                'account_type_id' => 3,
                'account_detail_type_id' => 15,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_change_in_inventory_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_commissions_and_fees',
                'account_type_id' => 14,
                'account_detail_type_id' => 111,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_cost_of_sales',
                'account_type_id' => 13,
                'account_detail_type_id' => 104,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_deferred_tax_assets',
                'account_type_id' => 5,
                'account_detail_type_id' => 33,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_direct_labour_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_discounts_given_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_dividend_disbursed',
                'account_type_id' => 10,
                'account_detail_type_id' => 69,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_dividend_income',
                'account_type_id' => 12,
                'account_detail_type_id' => 92,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_dividends_payable',
                'account_type_id' => 8,
                'account_detail_type_id' => 48,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_dues_and_subscriptions',
                'account_type_id' => 14,
                'account_detail_type_id' => 113,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_equipment_rental',
                'account_type_id' => 14,
                'account_detail_type_id' => 114,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_equity_in_earnings_of_subsidiaries',
                'account_type_id' => 10,
                'account_detail_type_id' => 70,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_freight_and_delivery_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_goodwill',
                'account_type_id' => 5,
                'account_detail_type_id' => 34,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_income_tax_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 116,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_income_tax_payable',
                'account_type_id' => 8,
                'account_detail_type_id' => 50,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_insurance_disability',
                'account_type_id' => 14,
                'account_detail_type_id' => 117,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_insurance_general',
                'account_type_id' => 14,
                'account_detail_type_id' => 117,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_insurance_liability',
                'account_type_id' => 14,
                'account_detail_type_id' => 117,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_intangibles',
                'account_type_id' => 5,
                'account_detail_type_id' => 35,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_interest_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 118,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_interest_income',
                'account_type_id' => 12,
                'account_detail_type_id' => 93,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_inventory',
                'account_type_id' => 2,
                'account_detail_type_id' => 5,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_inventory_asset',
                'account_type_id' => 2,
                'account_detail_type_id' => 5,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_legal_and_professional_fees',
                'account_type_id' => 14,
                'account_detail_type_id' => 119,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_liabilities_related_to_assets_held_for_sale',
                'account_type_id' => 9,
                'account_detail_type_id' => 63,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_long_term_debt',
                'account_type_id' => 9,
                'account_detail_type_id' => 64,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_long_term_investments',
                'account_type_id' => 5,
                'account_detail_type_id' => 38,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_loss_on_discontinued_operations_net_of_tax',
                'account_type_id' => 14,
                'account_detail_type_id' => 120,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_loss_on_disposal_of_assets',
                'account_type_id' => 12,
                'account_detail_type_id' => 94,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_management_compensation',
                'account_type_id' => 14,
                'account_detail_type_id' => 121,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_materials_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_meals_and_entertainment',
                'account_type_id' => 14,
                'account_detail_type_id' => 122,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_office_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 123,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_comprehensive_income',
                'account_type_id' => 10,
                'account_detail_type_id' => 73,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_general_and_administrative_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 123,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_operating_income_expenses',
                'account_type_id' => 12,
                'account_detail_type_id' => 97,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_selling_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 125,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_other_type_of_expenses_advertising_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 105,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_overhead_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_payroll_clearing',
                'account_type_id' => 8,
                'account_detail_type_id' => 55,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_payroll_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 126,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_payroll_liabilities',
                'account_type_id' => 8,
                'account_detail_type_id' => 56,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_prepaid_expenses',
                'account_type_id' => 2,
                'account_detail_type_id' => 11,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_property_plant_and_equipment',
                'account_type_id' => 4,
                'account_detail_type_id' => 26,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_purchases',
                'account_type_id' => 14,
                'account_detail_type_id' => 130,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_reconciliation_discrepancies',
                'account_type_id' => 15,
                'account_detail_type_id' => 139,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_rent_or_lease_payments',
                'account_type_id' => 14,
                'account_detail_type_id' => 127,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_repair_and_maintenance',
                'account_type_id' => 14,
                'account_detail_type_id' => 128,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_retained_earnings',
                'account_type_id' => 10,
                'account_detail_type_id' => 80,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_revenue_general',
                'account_type_id' => 11,
                'account_detail_type_id' => 86,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_sales',
                'account_type_id' => 11,
                'account_detail_type_id' => 89,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_sales_retail',
                'account_type_id' => 11,
                'account_detail_type_id' => 87,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_sales_wholesale',
                'account_type_id' => 11,
                'account_detail_type_id' => 88,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_sales_of_product_income',
                'account_type_id' => 11,
                'account_detail_type_id' => 89,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_share_capital',
                'account_type_id' => 10,
                'account_detail_type_id' => 81,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_shipping_and_delivery_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 129,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_short_term_debit',
                'account_type_id' => 8,
                'account_detail_type_id' => 54,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_stationery_and_printing',
                'account_type_id' => 14,
                'account_detail_type_id' => 123,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_subcontractors_cos',
                'account_type_id' => 13,
                'account_detail_type_id' => 100,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_supplies',
                'account_type_id' => 14,
                'account_detail_type_id' => 130,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_travel_expenses_general_and_admin_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 132,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_travel_expenses_selling_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 133,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_unapplied_cash_payment_income',
                'account_type_id' => 11,
                'account_detail_type_id' => 91,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_uncategorised_asset',
                'account_type_id' => 2,
                'account_detail_type_id' => 10,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_uncategorised_expense',
                'account_type_id' => 14,
                'account_detail_type_id' => 124,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_uncategorised_income',
                'account_type_id' => 11,
                'account_detail_type_id' => 89,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_undeposited_funds',
                'account_type_id' => 2,
                'account_detail_type_id' => 13,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_unrealised_loss_on_securities_net_of_tax',
                'account_type_id' => 12,
                'account_detail_type_id' => 99,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_utilities',
                'account_type_id' => 14,
                'account_detail_type_id' => 135,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_wage_expenses',
                'account_type_id' => 14,
                'account_detail_type_id' => 126,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_credit_card',
                'account_type_id' => 7,
                'account_detail_type_id' => 43,
                'default_account' => 1
            ],
            [
                'name' => '',
                'key_name' => 'acc_accounts_payable',
                'account_type_id' => 6,
                'account_detail_type_id' => 42,
                'default_account' => 1
            ],
        ];

        $db_builder = $db->table(get_db_prefix().'acc_accounts');
        $affectedRows = $db_builder->insertBatch($accounts);

        if ($affectedRows > 0) {

            $db_builder = $db->table(get_db_prefix().'settings');


            $db_builder->where('setting_name', 'acc_add_default_account');
            $db_builder->update([
                    'setting_value' => 1,
                ]);

            return true;
        }

        return false;
    }
}

add_setting('acc_first_month_of_financial_year', 'January');
add_setting('acc_first_month_of_tax_year', 'same_as_financial_year');
add_setting('acc_accounting_method', 'accrual');
add_setting('acc_close_the_books', 0);
add_setting('acc_allow_changes_after_viewing', 'allow_changes_after_viewing_a_warning');
add_setting('acc_close_book_password');
add_setting('acc_close_book_passwordr');
add_setting('acc_enable_account_numbers', 0);
add_setting('acc_show_account_numbers', 0);
add_setting('acc_closing_date');

add_setting('acc_add_default_account', 0);
add_setting('acc_add_default_account_new', 0);
add_setting('acc_invoice_automatic_conversion', 1);
add_setting('acc_payment_automatic_conversion', 1);
add_setting('acc_credit_note_automatic_conversion', 1);
add_setting('acc_expense_automatic_conversion', 1);
add_setting('acc_tax_automatic_conversion', 1);

add_setting('acc_invoice_payment_account', 66);
add_setting('acc_invoice_deposit_to', 1);
add_setting('acc_payment_payment_account', 1);
add_setting('acc_payment_deposit_to', 13);
add_setting('acc_credit_note_payment_account', 1);
add_setting('acc_credit_note_deposit_to', 13);
add_setting('acc_expense_payment_account', 13);
add_setting('acc_expense_deposit_to', 80);
add_setting('acc_tax_payment_account', 29);
add_setting('acc_tax_deposit_to', 1);
add_setting('acc_expense_tax_payment_account', 13);
add_setting('acc_expense_tax_deposit_to', 29);

add_setting('acc_active_payment_mode_mapping', 1);
add_setting('acc_active_expense_category_mapping', 1);

if (!$db->tableExists($dbprefix . 'acc_accounts')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_accounts (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `key_name` VARCHAR(255) NULL,
    `number` VARCHAR(45) NULL,
    `parent_account` INT(11) NULL,
    `account_type_id` INT(11) NOT NULL,
    `account_detail_type_id` INT(11) NOT NULL,
    `balance` DECIMAL(15,2) NULL,
    `balance_as_of` DATE NULL,
    `description` TEXT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_account_history')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_account_history (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `account` INT(11) NOT NULL,
      `debit` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `credit` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `description` TEXT NULL,
      `rel_id` INT(11) NULL,
      `rel_type` VARCHAR(45) NULL,
      `datecreated` DATETIME NULL,
      `addedfrom` INT(11) NULL,
      `customer` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_transfers')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_transfers (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `transfer_funds_from` INT(11) NOT NULL,
      `transfer_funds_to` INT(11) NOT NULL,
      `transfer_amount` DECIMAL(15,2) NULL,
      `date` VARCHAR(45) NULL,
      `description` TEXT NULL,
      `datecreated` DATETIME NULL,
      `addedfrom` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_journal_entries')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_journal_entries (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `number` VARCHAR(45) NULL,
      `description` TEXT NULL,
      `journal_date` DATE NULL,
      `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `datecreated` DATETIME NULL,
      `addedfrom` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_transaction_bankings')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_transaction_bankings (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `date` DATE NOT NULL,
      `withdrawals` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `deposits` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `payee` VARCHAR(255) NULL,
      `description` TEXT NULL,
      `datecreated` DATETIME NULL,
      `addedfrom` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_reconciles')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_reconciles (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `account` INT(11) NOT NULL,
      `beginning_balance` DECIMAL(15,2) NOT NULL,
      `ending_balance` DECIMAL(15,2) NOT NULL,
      `ending_date` DATE NOT NULL,
      `expense_date` DATE NULL,
      `service_charge` DECIMAL(15,2) NULL,
      `expense_account` INT(11) NULL,
      `income_date` DATE NULL,
      `interest_earned` DECIMAL(15,2) NULL,
      `income_account` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('reconcile' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `reconcile` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->fieldExists('finish' ,$dbprefix . 'acc_reconciles')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_reconciles`
    ADD COLUMN `finish` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->fieldExists('split' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `split` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->tableExists($dbprefix . 'acc_banking_rules')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_banking_rules (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(255) NOT NULL,
      `transaction` VARCHAR(45) NULL,
      `following` VARCHAR(45) NULL,
      `then` VARCHAR(45) NULL,
      `payment_account` INT(11) NULL,
      `deposit_to` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_banking_rule_details')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_banking_rule_details (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `rule_id` INT(11) NOT NULL,
      `type` VARCHAR(45) NULL,
      `subtype` VARCHAR(45) NULL,
      `text` VARCHAR(255) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('auto_add' ,$dbprefix . 'acc_banking_rules')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_banking_rules`
    ADD COLUMN `auto_add` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->fieldExists('subtype_amount' ,$dbprefix . 'acc_banking_rule_details')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_banking_rule_details`
    ADD COLUMN `subtype_amount` VARCHAR(45) NULL;');
}

if (!$db->fieldExists('default_account' ,$dbprefix . 'acc_accounts')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_accounts`
    ADD COLUMN `default_account` INT(11) NOT NULL DEFAULT 0,
    ADD COLUMN `active` INT(11) NOT NULL DEFAULT 1;');
}

if (!$db->fieldExists('item' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `item` INT(11) NULL,
    ADD COLUMN `paid` INT(1) NOT NULL DEFAULT 0;');
}

if (!$db->tableExists($dbprefix . 'acc_item_automatics')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_item_automatics (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `item_id` INT(11) NOT NULL,
      `inventory_asset_account` INT(11) NOT NULL DEFAULT 0,
      `income_account` INT(11) NOT NULL DEFAULT 0,
      `expense_account` INT(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_tax_mappings')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_tax_mappings (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `tax_id` INT(11) NOT NULL,
      `payment_account` INT(11) NOT NULL DEFAULT 0,
      `deposit_to` INT(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('date' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `date` DATE NULL;');
}

if (!$db->tableExists($dbprefix . 'acc_expense_category_mappings')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_expense_category_mappings (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `category_id` INT(11) NOT NULL,
      `payment_account` INT(11) NOT NULL DEFAULT 0,
      `deposit_to` INT(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('tax' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `tax` INT(11) NULL;');
}


if (!$db->fieldExists('expense_payment_account' ,$dbprefix . 'acc_tax_mappings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_tax_mappings`
    ADD COLUMN `expense_payment_account` INT(11) NOT NULL DEFAULT \'0\',
    ADD COLUMN `expense_deposit_to` INT(11) NOT NULL DEFAULT \'0\';');
}

if (!$db->tableExists($dbprefix . 'acc_payment_mode_mappings')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_payment_mode_mappings (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `payment_mode_id` INT(11) NOT NULL,
      `payment_account` INT(11) NOT NULL DEFAULT 0,
      `deposit_to` INT(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

add_setting('acc_payment_expense_automatic_conversion', 1);
add_setting('acc_payment_sale_automatic_conversion', 1);
add_setting('acc_expense_payment_payment_account', 1);
add_setting('acc_expense_payment_deposit_to', 1);

if (!$db->tableExists($dbprefix . 'acc_account_type_details')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_account_type_details (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `account_type_id` INT(11) NOT NULL,
      `name` VARCHAR(255) NOT NULL,
      `note` TEXT NULL,
      `statement_of_cash_flows` VARCHAR(255) NULL,
      PRIMARY KEY (`id`)
    ) AUTO_INCREMENT=200, ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('preferred_payment_method' ,$dbprefix . 'acc_expense_category_mappings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_expense_category_mappings`
    ADD COLUMN `preferred_payment_method` INT(11) NOT NULL DEFAULT \'0\';');
}

if (!$db->fieldExists('expense_payment_account' ,$dbprefix . 'acc_payment_mode_mappings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_payment_mode_mappings`
    ADD COLUMN `expense_payment_account` INT(11) NOT NULL DEFAULT \'0\',
    ADD COLUMN `expense_deposit_to` INT(11) NOT NULL DEFAULT \'0\';');
}

if (!$db->fieldExists('payslip_type' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `payslip_type` VARCHAR(45) NULL;');
}
      acc_add_default_account();

if (!acc_account_exists('acc_opening_balance_equity')) {
  $db->query("INSERT INTO `". $dbprefix ."acc_accounts` (`name`, `key_name`, `account_type_id`, `account_detail_type_id`, `default_account`, `active`) VALUES ('', 'acc_opening_balance_equity', '10', '71', '1', '1');");
}

add_setting('acc_pl_total_insurance_automatic_conversion', 1);
add_setting('acc_pl_total_insurance_payment_account', 13);
add_setting('acc_pl_total_insurance_deposit_to', 32);

add_setting('acc_pl_tax_paye_automatic_conversion', 1);
add_setting('acc_pl_tax_paye_payment_account', 13);
add_setting('acc_pl_tax_paye_deposit_to', 28);

add_setting('acc_pl_net_pay_automatic_conversion', 1);
add_setting('acc_pl_net_pay_payment_account', 13);
add_setting('acc_pl_net_pay_deposit_to', 56);

add_setting('acc_wh_stock_import_automatic_conversion', 1);
add_setting('acc_wh_stock_import_payment_account', 87);
add_setting('acc_wh_stock_import_deposit_to', 37);

add_setting('acc_wh_stock_export_automatic_conversion', 1);
add_setting('acc_wh_stock_export_payment_account', 37);
add_setting('acc_wh_stock_export_deposit_to', 1);

add_setting('acc_wh_loss_adjustment_automatic_conversion', 1);
add_setting('acc_wh_decrease_payment_account', 37);
add_setting('acc_wh_decrease_deposit_to', 1);

add_setting('acc_wh_increase_payment_account', 87);
add_setting('acc_wh_increase_deposit_to', 37);

add_setting('acc_wh_opening_stock_automatic_conversion', 1);

if (acc_account_exists('acc_opening_balance_equity')) {
    add_setting('acc_wh_opening_stock_payment_account', acc_account_exists('acc_opening_balance_equity'));
}
add_setting('acc_wh_opening_stock_deposit_to', 37);

add_setting('acc_pur_order_automatic_conversion', 1);
add_setting('acc_pur_order_payment_account', 13);
add_setting('acc_pur_order_deposit_to', 80);

add_setting('acc_pur_payment_automatic_conversion', 1);
add_setting('acc_pur_payment_payment_account', 16);
add_setting('acc_pur_payment_deposit_to', 37);

//Version 1.0.8

if (!$db->tableExists($dbprefix . 'acc_budgets')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_budgets (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `year` INT(11) NOT NULL,
      `name` VARCHAR(200) NULL,
      `type` VARCHAR(45) NULL,
      `data_source` VARCHAR(45) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->tableExists($dbprefix . 'acc_budget_details')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_budget_details (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `budget_id` INT(11) NOT NULL,
      `month` INT(11) NOT NULL,
      `year` INT(11) NOT NULL,
      `account` INT(11) NULL,
      `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('vendor' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `vendor` INT(11) NULL;');
}

if (!$db->fieldExists('itemable_id' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `itemable_id` INT(11) NULL;');
}


//-------------------------

if (!$db->fieldExists('cleared' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `cleared` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->fieldExists('access_token' ,$dbprefix . 'acc_accounts')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_accounts`
    ADD COLUMN `access_token` TEXT NULL,
    ADD COLUMN `account_id` VARCHAR(255) NULL,
    ADD COLUMN `plaid_status` TINYINT(5) NOT NULL DEFAULT 0 COMMENT "1=>verified, 0=>not verified",
    ADD COLUMN `plaid_account_name` VARCHAR(255) NULL;');
}

if (!$db->fieldExists('transaction_id' ,$dbprefix . 'acc_transaction_bankings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_transaction_bankings`
    ADD COLUMN `transaction_id` varchar(150) NULL,
    ADD COLUMN `bank_id` INT(11) NULL,
    ADD COLUMN `status` TINYINT(5) NOT NULL DEFAULT 0 COMMENT "1=>posted, 2=>pending";');
}

if (!$db->fieldExists('matched' ,$dbprefix . 'acc_transaction_bankings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_transaction_bankings`
    ADD COLUMN `matched` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->tableExists($dbprefix . 'acc_plaid_transaction_logs')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_plaid_transaction_logs (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `bank_id` int(11) DEFAULT NULL,
        `last_updated` date DEFAULT NULL,
        `transaction_count` int(11) DEFAULT NULL,
        `created_at` datetime DEFAULT NULL,
        `addedFrom` int(11) DEFAULT NULL,
        `company` int(11) DEFAULT NULL,
        `status` int(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('opening_balance' ,$dbprefix . 'acc_reconciles')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_reconciles`
    ADD COLUMN `opening_balance` INT(11) NOT NULL DEFAULT 0;');
}


if (!$db->fieldExists('debits_for_period' ,$dbprefix . 'acc_reconciles')) {
    $db->query('ALTER TABLE `' . $dbprefix . 'acc_reconciles`
  ADD COLUMN `debits_for_period` DECIMAL(15,2) NULL');
}

if (!$db->fieldExists('credits_for_period' ,$dbprefix . 'acc_reconciles')) {
    $db->query('ALTER TABLE `' . $dbprefix . 'acc_reconciles`
  ADD COLUMN `credits_for_period`  DECIMAL(15,2) NULL');
}

if (!$db->fieldExists('dateadded' ,$dbprefix . 'acc_reconciles')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_reconciles`
    ADD COLUMN `dateadded` DATETIME NULL,
    ADD COLUMN `addedfrom` INT(11) NULL
    ');
}

if (!$db->fieldExists('reconcile' ,$dbprefix . 'acc_transaction_bankings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_transaction_bankings`
    ADD COLUMN `reconcile` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->fieldExists('adjusted' ,$dbprefix . 'acc_transaction_bankings')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_transaction_bankings`
    ADD COLUMN `adjusted` INT(11) NOT NULL DEFAULT 0;');
}

if (!$db->tableExists($dbprefix . 'acc_matched_transactions')) {
    $db->query('CREATE TABLE ' . $dbprefix . 'acc_matched_transactions (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `account_history_id` INT(11) NULL,
        `history_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
        `rel_id` INT(11) NULL,
        `rel_type` VARCHAR(255) NULL,
        `amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
        `company` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $db->charset . ';');
}

if (!$db->fieldExists('reconcile' ,$dbprefix . 'acc_matched_transactions')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_matched_transactions`
    ADD COLUMN `reconcile` INT(11) NOT NULL DEFAULT 0;');
}

add_setting('acc_plaid_client_id', '');
add_setting('acc_live_secret', '');
add_setting('acc_sandbox_secret', '');
add_setting('acc_plaid_environment', 'sandbox');


if (!$db->fieldExists('number' ,$dbprefix . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
    ADD COLUMN `number` VARCHAR(45) NULL;');
}

add_setting('acc_pur_invoice_automatic_conversion', 1);
add_setting('acc_pur_invoice_payment_account', 13);
add_setting('acc_pur_invoice_deposit_to', 80);


add_setting('acc_mrp_manufacturing_order_automatic_conversion', 1);
add_setting('acc_mrp_material_cost_payment_account', 13);
add_setting('acc_mrp_material_cost_deposit_to', 45);
add_setting('acc_mrp_labour_cost_payment_account', 13);
add_setting('acc_mrp_labour_cost_deposit_to', 18);

add_setting('acc_fe_asset_automatic_conversion', 1);
add_setting('acc_fe_asset_payment_account', 13);
add_setting('acc_fe_asset_deposit_to', 1);

add_setting('acc_fe_license_automatic_conversion', 1);
add_setting('acc_fe_license_payment_account', 13);
add_setting('acc_fe_license_deposit_to', 1);

add_setting('acc_fe_consumable_automatic_conversion', 1);
add_setting('acc_fe_consumable_payment_account', 13);
add_setting('acc_fe_consumable_deposit_to', 1);

add_setting('acc_fe_component_automatic_conversion', 1);
add_setting('acc_fe_component_payment_account', 13);
add_setting('acc_fe_component_deposit_to', 1);

add_setting('acc_fe_maintenance_automatic_conversion', 1);
add_setting('acc_fe_maintenance_payment_account', 13);
add_setting('acc_fe_maintenance_deposit_to', 1);

add_setting('acc_fe_depreciation_automatic_conversion', 1);
add_setting('acc_fe_depreciation_payment_account', 13);
add_setting('acc_fe_depreciation_deposit_to', 1);

if (!$db->fieldExists('sub_type' ,get_db_prefix() . 'acc_account_history')) {
  $db->query('ALTER TABLE `' . get_db_prefix() . 'acc_account_history`
    ADD COLUMN `sub_type` VARCHAR(45) NULL;');
}

// update permission
if (!$db->fieldExists("plugins_permissions" ,$dbprefix . "roles")) { 
  $db->query("ALTER TABLE `" . $dbprefix . "roles`
    ADD COLUMN `plugins_permissions` LONGTEXT NULL
;");
}

if (!$db->tableExists($dbprefix . 'acc_expense_category_mapping_details')) {
    $db->query('CREATE TABLE `' . $dbprefix . "acc_expense_category_mapping_details` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `category_mapping_id` INT(11) NOT NULL,
      `payment_mode_id` INT(11) NOT NULL,
      `payment_account` INT(11) NOT NULL DEFAULT 0,
      `deposit_to` INT(11) NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $db->charset . ';');
}

if (!$db->fieldExists('added_from_reconcile' ,$dbprefix . 'acc_account_history')) {
    $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
  ADD COLUMN `added_from_reconcile` INT(11) NOT NULL DEFAULT 0');
}

if (!$db->fieldExists('bank_reconcile' ,$dbprefix . 'acc_account_history')) {
    $db->query('ALTER TABLE `' . $dbprefix . 'acc_account_history`
  ADD COLUMN `bank_reconcile` INT(11) NOT NULL DEFAULT 0');
}

if (!$db->tableExists($dbprefix . 'acc_bank_reconciles')) {
    $db->query('CREATE TABLE `' . $dbprefix . "acc_bank_reconciles` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `account` INT(11) NOT NULL,
      `opening_balance` DECIMAL(15,2) NOT NULL DEFAULT 0,
      `beginning_balance` DECIMAL(15,2) NOT NULL,
      `ending_balance` DECIMAL(15,2) NOT NULL,
      `ending_date` DATE NOT NULL,
      `finish` INT(11) NOT NULL DEFAULT 0,
      `debits_for_period` DECIMAL(15,2) NOT NULL,
      `credits_for_period` DECIMAL(15,2) NOT NULL,
      `dateadded` DATETIME NULL,
      `addedfrom` INT(11) NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $db->charset . ';');
}


if (!$db->tableExists($dbprefix . 'pur_vendor')) {
    $db->query('CREATE TABLE `' . $dbprefix . "pur_vendor` (
      `userid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `company` varchar(200) NULL,
      `vat` varchar(200) NULL,
      `phonenumber` varchar(30) NULL,
      `country` int(11) NOT NULL DEFAULT '0',
      `city` varchar(100) NULL,
      `zip` varchar(15) NULL,
      `state` varchar(50) NULL,
      `address` varchar(100) NULL,
      `website` varchar(150) NULL,
      `datecreated` DATETIME NOT NULL,
      `active` INT(11) NOT NULL DEFAULT '1',
      `leadid` INT(11) NULL,
      `billing_street` varchar(200) NULL,
      `billing_city` varchar(100) NULL,
      `billing_state` varchar(100) NULL,
      `billing_zip` varchar(100) NULL,
      `billing_country` int(11) NULL DEFAULT '0',
      `shipping_street` varchar(200) NULL,
      `shipping_city` varchar(100) NULL,
      `shipping_state` varchar(100) NULL,
      `shipping_zip` varchar(100) NULL,
      `shipping_country` int(11) NULL DEFAULT '0',
      `longitude` varchar(191) NULL,
      `latitude` varchar(191) NULL,
      `default_language` varchar(40) NULL,
      `default_currency` INT(11) NOT NULL DEFAULT '0',
      `show_primary_contact` INT(11) NOT NULL DEFAULT '0',
      `stripe_id` varchar(40) NULL,
      `registration_confirmed` INT(11) NOT NULL DEFAULT '1',
      `addedfrom` INT(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`userid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $db->charset . ';');
}

if (!$db->fieldExists('category' ,$dbprefix . 'pur_vendor')) { 
  $db->query('ALTER TABLE `' . $dbprefix . "pur_vendor`
      ADD COLUMN `category` TEXT  NULL
  ;");
}

if (!$db->fieldExists('bank_detail' ,$dbprefix . 'pur_vendor')) { 
  $db->query('ALTER TABLE `' . $dbprefix . "pur_vendor`
      ADD COLUMN `bank_detail` TEXT  NULL
  ;");
}

if (!$db->fieldExists('payment_terms' ,$dbprefix . 'pur_vendor')) { 
  $db->query('ALTER TABLE `' . $dbprefix . "pur_vendor`
      ADD COLUMN `payment_terms` TEXT  NULL
  ;");
}

if (!$db->fieldExists('vendor_code' ,$dbprefix . 'pur_vendor')) { 
  $db->query('ALTER TABLE `' . $dbprefix . "pur_vendor`
      ADD COLUMN `vendor_code` VARCHAR(100)  NULL
  ;");
}

if ($db->fieldExists('address' ,$dbprefix . 'pur_vendor')) { 
  $db->query('ALTER TABLE `' . $dbprefix . "pur_vendor`
      CHANGE COLUMN `address` `address` TEXT NULL DEFAULT NULL
  ;");
}

if (!$db->fieldExists('return_within_day' ,$dbprefix . 'pur_vendor')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'pur_vendor`
  ADD COLUMN `return_within_day` INT(11) NULL
  ');
}

if (!$db->fieldExists('return_order_fee' ,$dbprefix . 'pur_vendor')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'pur_vendor`
  ADD COLUMN `return_order_fee` DECIMAL(15,2) NULL
  ');
}

if (!$db->fieldExists('return_policies' ,$dbprefix . 'pur_vendor')) {
  $db->query('ALTER TABLE `' . $dbprefix . 'pur_vendor`
  ADD COLUMN `return_policies` TEXT NULL
  ');
}

add_setting('acc_wh_stock_export_profit_payment_account', 66);
add_setting('acc_wh_stock_export_profit_deposit_to', 1);