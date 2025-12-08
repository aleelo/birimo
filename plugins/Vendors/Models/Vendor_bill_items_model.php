<?php

namespace Vendors\Models;

use App\Models\Crud_model;

class Vendor_bill_items_model extends Crud_model
{
    function __construct()
    {
        // -> resolves to rise_vendor_bill_items
        parent::__construct('vendor_bill_items');
    }

    /**
     * Get vendor bill items (and optional sections) for a vendor bill.
     * OPTIONS:
     *   - id
     *   - vendor_bill_id
     */
    function get_details($options = array())
    {
        $bill_items_table   = $this->db->prefixTable('vendor_bill_items'); // rise_vendor_bill_items
        $bills_table        = $this->db->prefixTable('vendor_bills');      // rise_vendor_bills
        $suppliers_table    = $this->db->prefixTable('supplier');          // use suppliers master

        $where = "";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $where .= " AND $bill_items_table.id=$id";
        }

        $vendor_bill_id = $this->_get_clean_value($options, "vendor_bill_id");
        if ($vendor_bill_id) {
            $where .= " AND $bill_items_table.vendor_bill_id=$vendor_bill_id";
        }

        // NOTE: clients/currency don’t apply here; return empty currency_symbol to keep UI happy if it expects it
        $sql = "SELECT 
                    $bill_items_table.*,
                    '' AS currency_symbol,
                    $suppliers_table.supplier_name AS supplier_name
                FROM $bill_items_table
                LEFT JOIN $bills_table     ON $bills_table.id = $bill_items_table.vendor_bill_id
                LEFT JOIN $suppliers_table ON $suppliers_table.id = $bill_items_table.supplier_id
                WHERE $bill_items_table.deleted=0 $where
                ORDER BY $bill_items_table.sort ASC";

        return $this->db->query($sql);
    }

    /**
     * If you use line 'sections' for vendor bills, adapt the table name below.
     * Otherwise you can remove this method or keep it as-is (it won’t be called).
     * OPTIONS:
     *   - vendor_bill_id
     */
    function get_details_with_sections($options = array())
    {
        $bill_items_table    = $this->db->prefixTable('vendor_bill_items');   // rise_vendor_bill_items
        $sections_table      = $this->db->prefixTable('items_section');       // CHANGE to 'vendor_items_section' if you have one
        $bills_table         = $this->db->prefixTable('vendor_bills');        // rise_vendor_bills
        $suppliers_table     = $this->db->prefixTable('supplier');

        $where = "";
        $vendor_bill_id = $this->_get_clean_value($options, "vendor_bill_id");
        if ($vendor_bill_id) {
            $where .= " AND vendor_bill_id=$vendor_bill_id";
        }

        $sections_query = "
            SELECT 
                id,
                vendor_bill_id,
                title,
                NULL AS quantity,
                NULL AS rate,
                NULL AS total,
                sort,
                CAST(NULL AS SIGNED) AS days,
                description,
                unit_type,
                taxable,
                1 AS is_section,
                NULL AS vendor_name,
                ''   AS currency_symbol
            FROM $sections_table
            WHERE deleted = 0 $where
        ";

        $items_query = "
            SELECT 
                $bill_items_table.id,
                $bill_items_table.vendor_bill_id,
                $bill_items_table.title,
                $bill_items_table.quantity,
                $bill_items_table.rate,
                $bill_items_table.total,
                $bill_items_table.sort,
                $bill_items_table.days,
                $bill_items_table.description,
                $bill_items_table.unit_type,
                $bill_items_table.taxable,
                0 AS is_section,
                $suppliers_table.supplier_name AS supplier_name,
                '' AS currency_symbol
            FROM $bill_items_table
            LEFT JOIN $bills_table     ON $bills_table.id = $bill_items_table.vendor_bill_id
            LEFT JOIN $suppliers_table ON $suppliers_table.id = $bill_items_table.supplier_id
            WHERE $bill_items_table.deleted = 0 $where
        ";

        $final_query = "
            ($sections_query)
            UNION ALL
            ($items_query)
            ORDER BY sort ASC
        ";

        return $this->db->query($final_query);
    }

    /**
     * Suggestions should come from vendor_items, not items.
     */
    function get_item_suggestion($keyword = "", $user_type = "")
    {
        $items_table = $this->db->prefixTable('vendor_items'); // rise_vendor_items

        $keyword = $this->_get_clean_value($keyword);
        $where = "";

        if ($keyword) {
            $keyword = $this->db->escapeLikeString($keyword);
            $where .= " AND $items_table.title LIKE '%$keyword%' ESCAPE '!' ";
        }

        $sql = "SELECT $items_table.id, $items_table.title
                FROM $items_table
                WHERE $items_table.deleted=0 $where
                LIMIT 10";

        return $this->db->query($sql)->getResult();
    }

    /**
     * Info suggestion from vendor_items.
     */
    function get_item_info_suggestion($options = array())
    {
        $items_table = $this->db->prefixTable('vendor_items'); // rise_vendor_items

        $where = "";
        $item_name = $this->_get_clean_value($options, "item_name");
        if ($item_name) {
            $item_name = $this->db->escapeLikeString($item_name);
            $where .= " AND $items_table.title LIKE '%$item_name%' ESCAPE '!' ";
        }

        $item_id = $this->_get_clean_value($options, "item_id");
        if ($item_id) {
            $where .= " AND $items_table.id=$item_id ";
        }

        $sql = "SELECT $items_table.*
                FROM $items_table
                WHERE $items_table.deleted=0 $where
                ORDER BY id DESC LIMIT 1";

        $result = $this->db->query($sql);
        if ($result->resultID->num_rows) {
            return $result->getRow();
        }
    }

    /**
     * SAVE helper for vendor bills: insert/update an item AND refresh bill totals.
     * Returns the saved item id.
     */
    function save_item_and_update_vendor_bill($data, $id, $vendor_bill_id)
    {
        // Keep a snapshot before update (if editing)
        $data_before = $id ? (array)$this->get_one($id) : [];

        // Save row
        $result_id = $this->ci_save($data, $id);

        // Recompute header totals on vendor bill
        $this->_recompute_and_update_bill_totals($vendor_bill_id);

        // Fetch saved row
        $saved_item = (array)$this->get_one($result_id);

        // Compute change log
        $fields_changed = [];
        if ($id) {
            foreach ($saved_item as $field => $new_value) {
                if (isset($data_before[$field]) && $data_before[$field] != $new_value) {
                    $from = $data_before[$field];
                    $to   = $new_value;

                    if ($field === "supplier_id") {
                        $suppliers_model = model("aleelo_plugin\Models\Supplier_model");
                        $from = $from ? $suppliers_model->get_one($from)->supplier_name : "N/A";
                        $to   = $to ? $suppliers_model->get_one($to)->supplier_name : "N/A";
                    }
                    $pretty_key = preg_replace('/_id\d*$/', '', $field);
                    $fields_changed[$pretty_key] = ["from" => $from, "to" => $to];
                }
            }
        } else {
            foreach ($saved_item as $field => $new_value) {
                $fields_changed[$field] = ["from" => null, "to" => $new_value];
            }
        }

        // Log activity against the VENDOR BILL
        $this->db->table($this->db->prefixTable('activity_logs'))->insert([
            'created_at'      => date('Y-m-d H:i:s'),
            'created_by'      => session()->get('user_id'),
            'action'          => $id ? 'updated' : 'created',
            'log_type'        => 'item',
            'log_type_id'     => $result_id,
            'log_for'         => 'vendor_bill',     // <— important: vendor_bill, not invoice
            'log_for_id'      => $vendor_bill_id,
            'log_type_title'  => $saved_item['title'] ?? 'New Item',
            'changes'         => serialize($fields_changed),
        ]);

        return $result_id;
    }

    /**
     * DELETE helper for vendor bills + totals update.
     */
    function delete_item_and_update_vendor_bill($id, $vendor_bill_id, $undo = false)
    {
        $item_info = $this->get_one($id);  // before delete/undo
        $result    = $this->delete($id, $undo);

        // Update header totals after delete/restore
        $this->_recompute_and_update_bill_totals($vendor_bill_id);

        return $result;
    }

    /**
     * Backward-compatible wrappers if any code still calls the old invoice methods.
     * These just forward to the vendor-bill versions.
     */
    function save_item_and_update_invoice($data, $id, $invoice_id)
    {
        // FORWARDER: vendor bills only
        return $this->save_item_and_update_vendor_bill($data, $id, $invoice_id);
    }

    function delete_item_and_update_invoice($id, $undo = false)
    {
        // We need vendor_bill_id to recompute totals; try to fetch it from the row.
        $row = $this->get_one($id);
        $vendor_bill_id = $row && isset($row->vendor_bill_id) ? $row->vendor_bill_id : null;
        $result = $this->delete($id, $undo);
        if ($vendor_bill_id) {
            $this->_recompute_and_update_bill_totals($vendor_bill_id);
        }
        return $result;
    }

    public function log_deletion($vendor_bill_id, $title, $item_id, $log_for = 'vendor_bill')
    {
        $item = $this->get_one($item_id);
        $type_label = ($item->is_section == "1") ? "section" : "item";

        $this->db->table($this->db->prefixTable('activity_logs'))->insert([
            "created_at"     => date("Y-m-d H:i:s"),
            "created_by"     => session()->get("user_id"),
            "action"         => "deleted",
            "log_type"       => $type_label,
            "log_for"        => $log_for,            // vendor_bill
            "log_for_id"     => $vendor_bill_id,
            "log_type_title" => $title,
            "log_type_id"    => $item_id,
            "changes"        => ""
        ]);
    }

    public function log_restoration($vendor_bill_id, $title, $item_id, $log_for = 'vendor_bill')
    {
        $item = $this->get_one($item_id);
        $type_label = ($item->is_section == "1") ? "section" : "item";

        $this->db->table($this->db->prefixTable('activity_logs'))->insert([
            "created_at"     => date("Y-m-d H:i:s"),
            "created_by"     => session()->get("user_id"),
            "action"         => "restored",
            "log_type"       => $type_label,
            "log_for"        => $log_for,            // vendor_bill
            "log_for_id"     => $vendor_bill_id,
            "log_type_title" => $title,
            "log_type_id"    => $item_id,
            "changes"        => ""
        ]);
    }

    // ----------------- INTERNALS -----------------

    /**
     * Sum bill items and write totals back to rise_vendor_bills.
     * (keeps cloned columns: invoice_total/invoice_subtotal)
     */
    private function _recompute_and_update_bill_totals($vendor_bill_id)
    {
        $bill_items_table = $this->db->prefixTable('vendor_bill_items');
        $bills_table      = $this->db->prefixTable('vendor_bills');

        $sumRow = $this->db->query("
            SELECT IFNULL(SUM(total),0) AS subtotal
            FROM $bill_items_table
            WHERE deleted=0 AND vendor_bill_id = ?
        ", [$vendor_bill_id])->getRow();

        $subtotal = (float)($sumRow->subtotal ?? 0);
        $total    = $subtotal; // no tax/discount logic here; extend if needed

        $this->db->table($bills_table)
            ->where('id', $vendor_bill_id)
            ->update([
                'invoice_subtotal' => $subtotal,
                'invoice_total'    => $total,
                'discount_total'   => 0,
                'tax'              => 0,
                'tax2'             => 0,
                'tax3'             => 0,
            ]);
    }


    // In Vendors\Models\Vendor_bill_items_model

    public function delete_by_bill(int $bill_id): void
    {
        $t = $this->db->prefixTable('vendor_bill_items');

        // Prefer soft-delete when column exists; otherwise hard delete.
        if ($this->db->fieldExists('deleted', $t)) {
            $this->db->table($t)->where('vendor_bill_id', $bill_id)->update(['deleted' => 1]);
        } else {
            $this->db->table($t)->where('vendor_bill_id', $bill_id)->delete();
        }
    }
}
