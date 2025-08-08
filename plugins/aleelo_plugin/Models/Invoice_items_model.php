<?php

namespace aleelo_plugin\Models;
use App\Models\Crud_model;

class Invoice_items_model extends Crud_model {

    protected $table = null;
    private $_Invoices_model = null;

    function __construct() {
        $this->table = 'invoice_items';
        parent::__construct($this->table);

        $this->_Invoices_model = model("App\Models\Invoices_model");
    }
function get_details($options = array()) {
    $invoice_items_table = $this->db->prefixTable('invoice_items');
    $invoices_table = $this->db->prefixTable('invoices');
    $clients_table = $this->db->prefixTable('clients');

    $where = "";
    $id = $this->_get_clean_value($options, "id");
    if ($id) {
        $where .= " AND $invoice_items_table.id=$id";
    }

    $invoice_id = $this->_get_clean_value($options, "invoice_id");
    if ($invoice_id) {
        $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
    }

    $sql = "SELECT 
                $invoice_items_table.id,
                $invoice_items_table.title,
                $invoice_items_table.description,
                $invoice_items_table.quantity,
                $invoice_items_table.unit_type,
                $invoice_items_table.rate,
                IFNULL($invoice_items_table.services, 0) AS services,          /* ✅ Always return services */
                IFNULL($invoice_items_table.service_cost, 0) AS service_cost,  /* ✅ Always return service_cost */
             IFNULL($invoice_items_table.alltotal, 0) AS alltotal,
                $invoice_items_table.total,
                $invoice_items_table.days,
                $invoice_items_table.taxable,
                $invoice_items_table.is_section,
                $invoice_items_table.invoice_id,
                $invoice_items_table.sort,
                (SELECT $clients_table.currency_symbol 
                 FROM $clients_table 
                 WHERE $clients_table.id=$invoices_table.client_id 
                 LIMIT 1) AS currency_symbol
            FROM $invoice_items_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
            WHERE $invoice_items_table.deleted=0 $where
            ORDER BY $invoice_items_table.sort ASC";

    return $this->db->query($sql);
}
    function get_details_with_sections($options = array())
    {
        $invoice_items_table = $this->db->prefixTable('invoice_items');
        $section_invoice_table = $this->db->prefixTable('items_section');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');
        $suppliers_table = $this->db->prefixTable('supplier');

        $where = "";
        $invoice_id = $this->_get_clean_value($options, "invoice_id");
        if ($invoice_id) {
            $where .= " AND invoice_id=$invoice_id";
        }

        $sections_query = "
        SELECT 
            id,
            invoice_id,
            title,
            NULL AS quantity,
            NULL AS rate,
            null AS services,
            null AS service_cost,
            null AS alltotal,
            total,
            sort,
            description,
            unit_type,
            taxable,
            days,
            1 AS is_section,
            NULL AS supplier_name,
            NULL AS currency_symbol
        FROM $section_invoice_table
        WHERE deleted = 0 $where
    ";

        $items_query = "
        SELECT 
            $invoice_items_table.id,
            $invoice_items_table.invoice_id,
            $invoice_items_table.title,
            $invoice_items_table.quantity,
            $invoice_items_table.rate,
            $invoice_items_table.services,
            $invoice_items_table.service_cost,
            $invoice_items_table.alltotal,
            $invoice_items_table.total,
            $invoice_items_table.sort,
            $invoice_items_table.description,
            $invoice_items_table.unit_type,
            $invoice_items_table.taxable,
            $invoice_items_table.days,

            0 AS is_section,
            $suppliers_table.supplier_name AS supplier_name,
            (SELECT $clients_table.currency_symbol 
             FROM $clients_table 
             WHERE $clients_table.id = $invoices_table.client_id 
             LIMIT 1) AS currency_symbol
        FROM $invoice_items_table
        LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.invoice_id
        LEFT JOIN $suppliers_table ON $suppliers_table.id = $invoice_items_table.supplier_id
        WHERE $invoice_items_table.deleted = 0 $where
    ";

        $final_query = "
        ($sections_query)
        UNION ALL
        ($items_query)
        ORDER BY sort ASC
    ";

        return $this->db->query($final_query);
    }

    // function get_details($options = array()) {
    //     $invoice_items_table = $this->db->prefixTable('invoice_items');
    //     $invoices_table = $this->db->prefixTable('invoices');
    //     $clients_table = $this->db->prefixTable('clients');
    //     $where = "";
    //     $id = $this->_get_clean_value($options, "id");
    //     if ($id) {
    //         $where .= " AND $invoice_items_table.id=$id";
    //     }
    //     $invoice_id = $this->_get_clean_value($options, "invoice_id");
    //     if ($invoice_id) {
    //         $where .= " AND $invoice_items_table.invoice_id=$invoice_id";
    //     }

    //     $sql = "SELECT $invoice_items_table.*, (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol
    //     FROM $invoice_items_table
    //     LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_items_table.invoice_id
    //     WHERE $invoice_items_table.deleted=0 $where
    //     ORDER BY $invoice_items_table.sort ASC";
    //     return $this->db->query($sql);
    // }
    
    // function get_details_with_sections($options = array())
    // {
    //     $invoice_items_table = $this->db->prefixTable('invoice_items');
    //     $section_invoice_table = $this->db->prefixTable('items_section');
    //     $invoices_table = $this->db->prefixTable('invoices');
    //     $clients_table = $this->db->prefixTable('clients');
    //     $suppliers_table = $this->db->prefixTable('supplier');

    //     $where = "";
    //     $invoice_id = $this->_get_clean_value($options, "invoice_id");
    //     if ($invoice_id) {
    //         $where .= " AND invoice_id=$invoice_id";
    //     }

    //     $sections_query = "
    //     SELECT 
    //         id,
    //         invoice_id,
    //         title,
    //         NULL AS quantity,
    //         NULL AS rate,
    //         NULL AS total,
    //         sort,
    //         description,
    //         unit_type,
    //         taxable,
    //         days,
    //         NULL AS services,
    //         1 AS is_section,
    //         NULL AS supplier_name,
    //         NULL AS currency_symbol
    //     FROM $section_invoice_table
    //     WHERE deleted = 0 $where
    // ";

    //     $items_query = "
    //     SELECT 
    //         $invoice_items_table.id,
    //         $invoice_items_table.invoice_id,
    //         $invoice_items_table.title,
    //         $invoice_items_table.quantity,
    //         $invoice_items_table.rate,
    //         $invoice_items_table.total,
    //         $invoice_items_table.sort,
    //         $invoice_items_table.description,
    //         $invoice_items_table.unit_type,
    //         $invoice_items_table.taxable,
    //         $invoice_items_table.days,
    //         $invoice_items_table.services, -- ✅ This must exist

    //         0 AS is_section,
    //         $suppliers_table.supplier_name AS supplier_name,
    //         (SELECT $clients_table.currency_symbol 
    //          FROM $clients_table 
    //          WHERE $clients_table.id = $invoices_table.client_id 
    //          LIMIT 1) AS currency_symbol
    //     FROM $invoice_items_table
    //     LEFT JOIN $invoices_table ON $invoices_table.id = $invoice_items_table.invoice_id
    //     LEFT JOIN $suppliers_table ON $suppliers_table.id = $invoice_items_table.supplier_id
    //     WHERE $invoice_items_table.deleted = 0 $where
    // ";

    //     $final_query = "
    //     ($sections_query)
    //     UNION ALL
    //     ($items_query)
    //     ORDER BY sort ASC
    // ";

    //     return $this->db->query($final_query);
    // }

    function get_item_suggestion($keyword = "", $user_type = "", $company_id = "") {
        $items_table = $this->db->prefixTable('items');
    
        $keyword = $this->_get_clean_value($keyword);
        $where = "";
    
        if ($keyword) {
            $keyword = $this->db->escapeLikeString($keyword);
            $where .= " AND $items_table.title LIKE '%$keyword%' ESCAPE '!' ";
        }
    
        if ($user_type && $user_type === "client") {
            $where .= " AND $items_table.show_in_client_portal=1";
        }
    
        if ($company_id) {
            $where .= " AND $items_table.company_id=$company_id";
        }
    
        $sql = "SELECT $items_table.id, $items_table.title
            FROM $items_table
            WHERE $items_table.deleted=0 $where
            LIMIT 10";
        
        return $this->db->query($sql)->getResult();
    }
    
    function get_item_info_suggestion($options = array()) {

        $items_table = $this->db->prefixTable('items');

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

        $user_type = $this->_get_clean_value($options, "user_type");
        if ($user_type && $user_type === "client") {
            $where = " AND $items_table.show_in_client_portal=1 ";
        }

        $sql = "SELECT $items_table.*
        FROM $items_table
        WHERE $items_table.deleted=0 $where
        ORDER BY id DESC LIMIT 1
        ";

        $result = $this->db->query($sql);

        if ($result->resultID->num_rows) {
            return $result->getRow();
        }
    }

   function save_item_and_update_invoice($data, $id, $invoice_id)
    {
        // Fetch existing record if it's an update
        $data_before = $id ? (array)$this->get_one($id) : [];

        // Save the invoice item
        $result_id = $this->ci_save($data, $id);

        // Update invoice totals
        $invoices_model = model("aleelo_plugin\Models\Invoices_model");
        $invoices_model->update_invoice_total_meta($invoice_id);
        $saved_item = (array)$this->get_one($result_id);

        // Fetch the saved invoice item after update/create
        $saved_item = (array)$this->get_one($result_id);

        // Compute the changes
        $fields_changed = [];
        if ($id) {
            // Updating — log only changed fields
            foreach ($saved_item as $field => $new_value) {
                if (isset($data_before[$field]) && $data_before[$field] != $new_value) {
                    $from = $data_before[$field];
                    $to = $new_value;

                    if ($field === "supplier_id") {
                        $projects_model = model("aleelo_plugin\Models\Supplier_model");
                        $from = $from ? $projects_model->get_one($from)->supplier_name : "N/A";
                        $to   = $to ? $projects_model->get_one($to)->supplier_name : "N/A";
                    }
                    $pretty_key = preg_replace('/_id\d*$/', '', $field);
                    $fields_changed[$pretty_key] = ["from" => $from, "to" => $to];
                }
            }
        } else {
            // Creating — log all fields as "to"
            foreach ($saved_item as $field => $new_value) {
                $fields_changed[$field] = [
                    "from" => null,
                    "to"   => $new_value,
                ];
            }
        }

        // Prepare log action label
        $log_action = $id ? 'updated' : 'created';

        // Prepare the log data
        $log_data = [
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id'),
            'action'     => $log_action,
            'log_type'   => 'item',
            'log_type_id' => $result_id,
            'log_for'    => 'invoice',
            'log_for_id' => $invoice_id,
            'log_type_title' => $saved_item['title'] ?? 'New Item',
            'changes'    => serialize($fields_changed),
        ];

        $this->db->table($this->db->prefixTable('activity_logs'))->insert($log_data);

        return $result_id;
    }

    function delete_item_and_update_invoice($id, $undo = false) {
        $item_info = $this->get_one($id);

        $result = $this->delete($id, $undo);

        $invoices_model = model("App\Models\Invoices_model");
        $invoices_model->update_invoice_total_meta($item_info->invoice_id);

        return $result;
    }
        public function log_deletion($invoice_id, $title, $item_id, $log_for = 'invoice')
    {
        $item = $this->get_one($item_id);
        $type_label = ($item->is_section == "1") ? "section" : "item";

        $log_data = [
            "created_at"     => date("Y-m-d H:i:s"),
            "created_by"     => session()->get("user_id"),
            "action"         => "deleted",
            "log_type"       => $type_label,
            "log_for"        => $log_for,
            "log_for_id"     => $invoice_id,
            "log_type_title" => $title,
            "log_type_id"    => $item_id,
            "changes"        => ""
        ];

        $this->db->table($this->db->prefixTable('activity_logs'))->insert($log_data);
    }

    public function log_restoration($invoice_id, $title, $item_id, $log_for = 'invoice')
    {
        $item = $this->get_one($item_id);
        $type_label = ($item->is_section == "1") ? "section" : "item";

        $log_data = [
            "created_at"     => date("Y-m-d H:i:s"),
            "created_by"     => session()->get("user_id"),
            "action"         => "restored",
            "log_type"       => $type_label,
            "log_for"        => $log_for,
            "log_for_id"     => $invoice_id,
            "log_type_title" => $title,
            "log_type_id"    => $item_id,
            "changes"        => ""
        ];

        $this->db->table($this->db->prefixTable('activity_logs'))->insert($log_data);
    }
}