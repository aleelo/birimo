<?php

namespace aleelo_plugin\Models;

use App\Models\Crud_model;

class Items_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'items';
        parent::__construct($this->table);
    }

    function get_details($options = array())
    {
        $items_table = $this->db->prefixTable('items');
        $order_items_table = $this->db->prefixTable('order_items');
        $item_categories_table = $this->db->prefixTable('item_categories');
        $company_table = $this->db->prefixTable('company');



        $join_accounts = "";
        $select_accounts = "";

        // ✅ Check if acc_accounts table exists
        if ($this->db->tableExists('acc_accounts')) {
            $accounts_table = $this->db->prefixTable('acc_accounts');
            $join_accounts = "LEFT JOIN $accounts_table ON $accounts_table.id = $items_table.account_id";
            $select_accounts = ", $accounts_table.key_name as key_name, $accounts_table.name as account_name";
        }
        $where = "";
        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $items_table.id=$id";
        }

        $company_id = $this->_get_clean_value($options, "company_id");
        if ($company_id) {
            $where .= " AND $items_table.company_id=$company_id";
        }
        $search = $this->_get_clean_value($options, "search");
        if ($search) {
            $search = $this->db->escapeLikeString($search);
            $where .= " AND ($items_table.title LIKE '%$search%' ESCAPE '!' OR $items_table.description LIKE '%$search%' ESCAPE '!')";
        }

        $show_in_client_portal = $this->_get_clean_value($options, "show_in_client_portal");
        if ($show_in_client_portal) {
            $where .= " AND $items_table.show_in_client_portal=1";
        }

        $category_id = $this->_get_clean_value($options, "category_id");
        if ($category_id) {
            $where .= " AND $items_table.category_id=$category_id";
        }

        $extra_select = "";
        $login_user_id = $this->_get_clean_value($options, "login_user_id");
        $created_by_hash = $this->_get_clean_value($options, "created_by_hash");
        if ($login_user_id || $created_by_hash) {

            $extra_where = "";
            if ($login_user_id) {
                $extra_where = " AND $order_items_table.created_by=$login_user_id ";
            } else if ($created_by_hash) {
                $extra_where = " AND $order_items_table.created_by_hash='$created_by_hash' ";
            }

            if ($login_user_id && $created_by_hash) {
                $extra_where = " AND ($order_items_table.created_by=$login_user_id OR $order_items_table.created_by_hash='$created_by_hash') ";
            }

            $extra_select = ", (SELECT COUNT($order_items_table.id) FROM $order_items_table WHERE $order_items_table.deleted=0 AND $order_items_table.order_id=0 AND $order_items_table.item_id=$items_table.id $extra_where ) AS added_to_cart";
        }

        $limit_query = "";
        $limit = $this->_get_clean_value($options, "limit");
        if ($limit) {
            $offset = $this->_get_clean_value($options, "offset");
            $limit_query = "LIMIT $offset, $limit";
        }

        $sql = "SELECT $items_table.*, $item_categories_table.title as category_title $extra_select $select_accounts,$company_table.name AS company_name 
        FROM $items_table
        LEFT JOIN $item_categories_table ON $item_categories_table.id= $items_table.category_id
                LEFT JOIN $company_table ON $company_table.id= $items_table.company_id

        $join_accounts
        WHERE $items_table.deleted=0 $where
        ORDER BY $items_table.title ASC
        $limit_query";

        return $this->db->query($sql);
    }

    public function delete_account($id)
    {
        $db_builder = $this->db->table(get_db_prefix() . 'acc_account_history');
        $db_builder->where('(account = ' . $id . ' or split = ' . $id . ')');
        $count = $db_builder->countAllResults();

        if ($count > 0) {
            return 'have_transaction';
        }

        $db_builder = $this->db->table(get_db_prefix() . 'acc_accounts');
        $db_builder->where('id', $id);
        $db_builder->where('default_account', 0);
        $db_builder->delete();
        if ($this->db->affectedRows() > 0) {
            $db_builder = $this->db->table(get_db_prefix() . 'acc_account_history');
            $db_builder->where('account', $id);
            $db_builder->delete();

            return true;
        }
        return false;
    }
}
