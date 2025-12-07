<?php

namespace Vendors\Models;

use App\Models\Crud_model;

class Vendor_items_model extends Crud_model
{
    public function __construct()
    {
        // Maps to rise_vendor_items
        parent::__construct('vendor_items');
    }

    public function get_details($options = [])
    {
        $items_table           = $this->db->prefixTable('vendor_items');
        $order_items_table     = $this->db->prefixTable('order_items');   // keep only if you actually use cart/order_items
        $item_categories_table = $this->db->prefixTable('item_categories');

        $where = "";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $items_table.id=$id";
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

        // Optional: added_to_cart count (only if your app uses order_items against vendor items)
        $extra_select   = "";
        $login_user_id  = $this->_get_clean_value($options, "login_user_id");
        $created_by_hash= $this->_get_clean_value($options, "created_by_hash");
        if ($login_user_id || $created_by_hash) {
            $extra_where = "";
            if ($login_user_id && $created_by_hash) {
                $extra_where = " AND ($order_items_table.created_by=$login_user_id OR $order_items_table.created_by_hash='$created_by_hash') ";
            } elseif ($login_user_id) {
                $extra_where = " AND $order_items_table.created_by=$login_user_id ";
            } elseif ($created_by_hash) {
                $extra_where = " AND $order_items_table.created_by_hash='$created_by_hash' ";
            }

            $extra_select = ",
                (SELECT COUNT($order_items_table.id)
                   FROM $order_items_table
                  WHERE $order_items_table.deleted=0
                    AND $order_items_table.order_id=0
                    AND $order_items_table.item_id=$items_table.id
                    $extra_where) AS added_to_cart";
        }

        // Custom fields entity => vendor_items (changed from "items")
        $custom_fields       = get_array_value($options, "custom_fields");
        $custom_field_filter = get_array_value($options, "custom_field_filter");
        $cf = $this->prepare_custom_field_query_string(
            "vendor_items", $custom_fields, $items_table, $custom_field_filter
        );
        $select_cf  = get_array_value($cf, "select_string");
        $join_cf    = get_array_value($cf, "join_string");
        $where_cf   = get_array_value($cf, "where_string");

        $limit_query = "";
        $limit  = $this->_get_clean_value($options, "limit");
        $offset = $this->_get_clean_value($options, "offset");
        if ($limit !== null && $offset !== null) {
            $limit  = (int) $limit;
            $offset = (int) $offset;
            $limit_query = " LIMIT $offset, $limit";
        }

        $sql = "SELECT
                    $items_table.*,
                    $item_categories_table.title AS category_title
                    $extra_select
                    $select_cf
                FROM $items_table
                LEFT JOIN $item_categories_table ON $item_categories_table.id = $items_table.category_id
                $join_cf
                WHERE $items_table.deleted=0
                $where
                $where_cf
                ORDER BY $items_table.title ASC
                $limit_query";

        return $this->db->query($sql);
    }

    // (Unrelated to items but preserved if your code calls it)
    public function delete_account($id)
    {
        $db_builder = $this->db->table(get_db_prefix() . 'acc_account_history');
        $db_builder->where('(account = '. $id .' or split = '. $id.')');
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
