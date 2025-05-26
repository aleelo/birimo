<?php

namespace aleelo_plugin\Models;

use App\Models\Crud_model;

class Expense_categories_model extends Crud_model {

    protected $table = null;

    function __construct() {
        $this->table = 'expense_categories';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $expense_categories_table = $this->db->prefixTable('expense_categories');
        $company_table = $this->db->prefixTable('company');
                $join_accounts = "";
        $select_accounts = "";
    
        // ✅ Check if acc_accounts table exists
        if ($this->db->tableExists('acc_accounts')) {
            $accounts_table = $this->db->prefixTable('acc_accounts');
            $join_accounts = "LEFT JOIN $accounts_table ON $accounts_table.id = $expense_categories_table.account_id";
            $select_accounts = ", $accounts_table.key_name as key_name, $accounts_table.name as account_name";

        }
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where = " AND $expense_categories_table.id=$id";
        }
        $department = $this->_get_clean_value($options, "department");
        if ($department) {
            $where .= " AND $expense_categories_table.company_id=$department";
        }

        $sql = "SELECT $expense_categories_table.* $select_accounts , $company_table.name as company_name
        FROM $expense_categories_table
        LEFT JOIN $company_table ON $company_table.id= $expense_categories_table.company_id
        
        $join_accounts
        WHERE $expense_categories_table.deleted=0 $where";
        return $this->db->query($sql);
    }

}
