<?php

namespace Vendors\Models;

use App\Models\Crud_model;

class Vendor_bill_payments_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'vendor_bill_payments';
        parent::__construct($this->table);
    }

    // Vendors/Models/Vendor_bill_payments_model.php
    public function get_details($options = [])
    {
        $payments = $this->db->prefixTable('vendor_bill_payments');
        $bills    = $this->db->prefixTable('vendor_bills');
        $items    = $this->db->prefixTable('vendor_bill_items');
        $methods  = $this->db->prefixTable('payment_methods');
        // Branch functionality removed - not needed in vendors plugin
        // $branch   = $this->db->prefixTable('branch');

        $where = "WHERE $payments.deleted=0";

        if ($id = $this->_get_clean_value($options, "id")) {
            $where .= " AND $payments.id=$id";
        }
        if ($bill_id = $this->_get_clean_value($options, "vendor_bill_id")) {
            $where .= " AND $payments.vendor_bill_id=$bill_id";
        }
        if ($pm = $this->_get_clean_value($options, "payment_method_id")) {
            $where .= " AND $payments.payment_method_id=$pm";
        }
        if ($sd = $this->_get_clean_value($options, "start_date")) {
            if ($ed = $this->_get_clean_value($options, "end_date")) {
                $where .= " AND ($payments.payment_date BETWEEN '$sd' AND '$ed')";
            }
        }

        // Optional: filter by project (exists in vendor_bill_items.project_id)
        if ($proj = $this->_get_clean_value($options, "project_id")) {
            $where .= " AND $payments.vendor_bill_id IN (
            SELECT $items.vendor_bill_id
            FROM $items
            WHERE $items.deleted=0 AND $items.project_id=$proj
        )";
        }

        // Branch functionality removed - not needed in vendors plugin
        // Optional: filter by branch through vendor_bills.branch_id
        // if ($branch_id = $this->_get_clean_value($options, "branch_id")) {
        //     if (is_array($branch_id)) {
        //         $escaped = array_map(fn($v) => db_connect()->escape($v), $branch_id);
        //         $where .= " AND $bills.branch_id IN (" . implode(",", $escaped) . ")";
        //     } elseif (is_numeric($branch_id)) {
        //         $where .= " AND $bills.branch_id=" . db_connect()->escape($branch_id);
        //     }
        // }

        $default_symbol = get_setting("currency_symbol");

        // Branch functionality removed - not needed in vendors plugin
        $sql = "SELECT
                $payments.*,
                $bills.display_id,
                $bills.id AS vendor_bill_id,
                '$default_symbol' AS currency_symbol,
                $methods.title AS payment_method_title
            FROM $payments
            LEFT JOIN $bills   ON $bills.id=$payments.vendor_bill_id
            LEFT JOIN $methods ON $methods.id=$payments.payment_method_id
            $where
            ORDER BY $payments.payment_date DESC, $payments.id DESC";

        return $this->db->query($sql);
    }



    function get_yearly_payments_chart($options = array())
    {
        $payments_table = $this->db->prefixTable('invoice_payments');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');

        $year = $this->_get_clean_value($options, "year");
        $project_id = $this->_get_clean_value($options, "project_id");

        $where = "";
        $currency = $this->_get_clean_value($options, "currency");
        if ($currency) {
            $where = $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        if ($project_id) {
            $where .= " AND $payments_table.invoice_id IN(SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.deleted=0 AND $invoices_table.project_id=$project_id)";
        }

        $show_own_client_invoice_user_id = get_array_value($options, "show_own_client_invoice_user_id");
        if ($show_own_client_invoice_user_id) {
            $where .= " AND $payments_table.invoice_id IN(SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.client_id IN(SELECT $clients_table.id FROM $clients_table WHERE $clients_table.owner_id = $show_own_client_invoice_user_id))";
        }

        $show_own_invoices_only_user_id = get_array_value($options, "show_own_invoices_only_user_id");
        if ($show_own_invoices_only_user_id) {
            $where .= " AND $payments_table.invoice_id IN(SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.created_by = $show_own_invoices_only_user_id)";
        }

        $payments = "SELECT SUM($payments_table.amount) AS total, MONTH($payments_table.payment_date) AS month,
            (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=(
                SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$payments_table.invoice_id
                )
            ) AS currency
            FROM $payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.invoice_id
            WHERE $payments_table.deleted=0 AND YEAR($payments_table.payment_date)= $year AND $invoices_table.deleted=0 $where
            GROUP BY MONTH($payments_table.payment_date), currency";

        return $this->db->query($payments)->getResult();
    }

    function get_used_projects($type)
    {
        $payments_table = $this->db->prefixTable('invoice_payments');
        $invoices_table = $this->db->prefixTable('invoices');
        $projects_table = $this->db->prefixTable('projects');
        $expenses_table = $this->db->prefixTable('expenses');

        $payments_where = "SELECT $invoices_table.project_id FROM $invoices_table WHERE $invoices_table.deleted=0 AND $invoices_table.project_id!=0 AND $invoices_table.id IN(SELECT $payments_table.invoice_id FROM $payments_table WHERE $payments_table.deleted=0 GROUP BY $payments_table.invoice_id) GROUP BY $invoices_table.project_id";
        $expenses_where = "SELECT $expenses_table.project_id FROM $expenses_table WHERE $expenses_table.deleted=0 AND $expenses_table.project_id!=0 GROUP BY $expenses_table.project_id";

        $where = "";
        if ($type == "all") {
            $where = " AND $projects_table.id IN($payments_where) OR $projects_table.id IN($expenses_where)";
        } else if ($type == "payments") {
            $where = " AND $projects_table.id IN($payments_where)";
        } else if ($type == "expenses") {
            $where = " AND $projects_table.id IN($expenses_where)";
        }

        $sql = "SELECT $projects_table.id, $projects_table.title 
            FROM $projects_table 
            WHERE $projects_table.deleted=0 $where
            GROUP BY $projects_table.id";

        return $this->db->query($sql);
    }

    function get_yearly_summary_details($options = array())
    {
        $payments_table = $this->db->prefixTable('invoice_payments');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');

        $where = "";

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $payment_method_id = $this->_get_clean_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $payments_table.payment_method_id=$payment_method_id";
        }

        $selected_currency = get_array_value($options, "currency");
        $default_currency = get_setting("default_currency");
        $currency = $selected_currency ? $selected_currency : get_setting("default_currency");

        $currency = $this->_get_clean_value(array("currency" => $currency), "currency");

        $where .= ($currency == $default_currency) ? " AND ($clients_table.currency='$default_currency' OR $clients_table.currency='' OR $clients_table.currency IS NULL)" : " AND $clients_table.currency='$currency'";

        $sql = "SELECT COUNT($payments_table.id) AS payment_count, SUM($payments_table.amount) AS amount,  MONTH($payments_table.payment_date) AS month, $clients_table.currency, $clients_table.currency_symbol 
        FROM $payments_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.invoice_id
        LEFT JOIN $clients_table ON $clients_table.id=(SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$payments_table.invoice_id LIMIT 1)
        WHERE $payments_table.deleted=0 $where
        GROUP BY MONTH($payments_table.payment_date)";

        return $this->db->query($sql);
    }

    function get_clients_summary_details($options = array())
    {
        $payments_table = $this->db->prefixTable('invoice_payments');
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');

        $where = "";

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $where .= " AND ($payments_table.payment_date BETWEEN '$start_date' AND '$end_date') ";
        }

        $payment_method_id = $this->_get_clean_value($options, "payment_method_id");
        if ($payment_method_id) {
            $where .= " AND $payments_table.payment_method_id=$payment_method_id";
        }

        $selected_currency = get_array_value($options, "currency");
        $default_currency = get_setting("default_currency");
        $currency = $selected_currency ? $selected_currency : get_setting("default_currency");
        $currency = $this->_get_clean_value(array("currency" => $currency), "currency");

        $where .= ($currency == $default_currency) ? " AND ($clients_table.currency='$default_currency' OR $clients_table.currency='' OR $clients_table.currency IS NULL)" : " AND $clients_table.currency='$currency'";

        $sql = "SELECT COUNT($payments_table.id) AS payment_count, SUM($payments_table.amount) AS amount, $invoices_table.client_id, $clients_table.company_name AS client_name, $clients_table.currency, $clients_table.currency_symbol
        FROM $payments_table
        LEFT JOIN $invoices_table ON $invoices_table.id=$payments_table.invoice_id
        LEFT JOIN $clients_table ON $clients_table.id=(SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$payments_table.invoice_id LIMIT 1)
        WHERE $payments_table.deleted=0 $where
        GROUP BY $invoices_table.client_id";

        return $this->db->query($sql);
    }

    function get_client_statement($options = array())
    {
        $payments_table = $this->db->prefixTable('invoice_payments');
        $invoices_table = $this->db->prefixTable('invoices');
        $payment_methods_table = $this->db->prefixTable('payment_methods');
        $client_wallet_table = $this->db->prefixTable('client_wallet');

        $invoices_where = "";
        $invoices_where_for_payments = "";
        $payments_where = "";
        $client_wallet_where = "";

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");

        $generate_reports_based_on = "$invoices_table.due_date";
        if (get_setting("generate_reports_based_on") == "bill_date") {
            $generate_reports_based_on = "$invoices_table.bill_date";
        }

        if ($start_date && $end_date) {
            $invoices_where .= " AND ($generate_reports_based_on BETWEEN '$start_date' AND '$end_date')";
            $payments_where .= " AND ($payments_table.payment_date BETWEEN '$start_date' AND '$end_date')";
            $client_wallet_where .= " AND ($client_wallet_table.payment_date BETWEEN '$start_date' AND '$end_date')";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $invoices_where .= " AND $invoices_table.client_id=$client_id";
            $invoices_where_for_payments .= " AND $invoices_table.client_id=$client_id";
            $client_wallet_where .= " AND $client_wallet_table.client_id=$client_id";
        }

        $sql = "SELECT $generate_reports_based_on AS date, $invoices_table.display_id AS description, $invoices_table.invoice_total AS invoice_total, 0 AS payment, 'invoice' AS type
        FROM $invoices_table
        WHERE $invoices_table.deleted=0 AND $invoices_table.status!='draft' AND $invoices_table.status!='cancelled' $invoices_where
        UNION
        SELECT $payments_table.payment_date AS date, $payment_methods_table.title AS description, 0 AS invoice_total, $payments_table.amount AS payment, 'payment' AS type
        FROM $payments_table
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id=$payments_table.payment_method_id
        WHERE $payments_table.deleted=0 
            AND $payments_table.invoice_id IN(
                SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.deleted=0 AND $invoices_table.status!='draft' AND $invoices_table.status!='cancelled' $invoices_where_for_payments
            ) AND $payments_table.payment_method_id!=(
                SELECT $payment_methods_table.id FROM $payment_methods_table WHERE $payment_methods_table.type='client_wallet'
            ) $payments_where
        UNION
        SELECT $client_wallet_table.payment_date AS date, (SELECT $payment_methods_table.title FROM $payment_methods_table WHERE $payment_methods_table.type='client_wallet') AS description, 0 AS invoice_total, $client_wallet_table.amount AS payment, 'payment' AS type
        FROM $client_wallet_table
        WHERE $client_wallet_table.deleted=0 $client_wallet_where
        ORDER by date ASC";

        return $this->db->query($sql);
    }

    function get_opening_balance_of_client($options = array())
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $invoice_payments_table = $this->db->prefixTable('invoice_payments');
        $payment_methods_table = $this->db->prefixTable('payment_methods');
        $client_wallet_table = $this->db->prefixTable('client_wallet');

        $start_date = $this->_get_clean_value($options, "start_date");
        $client_id = $this->_get_clean_value($options, "client_id");

        $generate_reports_based_on = "$invoices_table.due_date";
        if (get_setting("generate_reports_based_on") == "bill_date") {
            $generate_reports_based_on = "$invoices_table.bill_date";
        }

        $result = new \stdClass();

        $where = "";
        $invoices_where = "";
        if ($start_date) {
            $where .= " AND $generate_reports_based_on<='$start_date' ";
        }

        if ($client_id) {
            $invoices_where .= " AND $invoices_table.client_id=$client_id";
        }

        $sql = "SELECT SUM($invoices_table.invoice_total) AS total_invoiced
        FROM $invoices_table
        WHERE $invoices_table.deleted=0 AND $invoices_table.status!='draft' AND $invoices_table.status!='cancelled' $where $invoices_where";
        $total_invoiced = $this->db->query($sql)->getRow()->total_invoiced;

        $where = "";
        if ($start_date) {
            $where .= " AND $invoice_payments_table.payment_date<='$start_date' ";
        }

        $sql = "SELECT SUM($invoice_payments_table.amount) AS payment_received
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 
            AND $invoice_payments_table.payment_method_id!=(
                SELECT $payment_methods_table.id FROM $payment_methods_table WHERE deleted=0 AND type='client_wallet') 
            AND $invoice_payments_table.invoice_id IN(
                SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.deleted=0 $invoices_where) $where";

        $payment_received = $this->db->query($sql)->getRow()->payment_received;

        $where = "";
        if ($start_date) {
            $where .= " AND $client_wallet_table.payment_date<='$start_date' ";
        }

        if ($client_id) {
            $where .= " AND $client_wallet_table.client_id=$client_id";
        }

        $sql = "SELECT SUM($client_wallet_table.amount) AS total_client_wallet_amount
        FROM $client_wallet_table
        WHERE $client_wallet_table.deleted=0 $where";
        $total_client_wallet_amount = $this->db->query($sql)->getRow()->total_client_wallet_amount;

        return $total_invoiced - $payment_received - $total_client_wallet_amount;
    }

    // application/models/Invoice_payments_model.php
    function save_payment_and_log_changes($data, $id = 0)
    {
        // Fetch existing record if updating
        $data_before = $id ? (array)$this->get_one($id) : [];

        // Save the payment
        $save_id = $this->ci_save($data, $id);

        // Fetch saved payment
        $saved_payment = (array)$this->get_one($save_id);

        // Fetch related invoice_id
        $invoice_id = $saved_payment['vendor_bill_id'];

        // Fetch payment method title
        $payment_methods_model = model("Payment_methods_model");
        $payment_method_title = $payment_methods_model->get_one($saved_payment['payment_method_id'])->title;

        // Optional: update invoice total/meta
        // $this->update_invoice_total_meta($invoice_id);

        // Compute changed fields if this is an update
        $fields_changed = [];
        if ($id) {
            foreach ($saved_payment as $field => $new_value) {
                if (isset($data_before[$field]) && $data_before[$field] != $new_value) {
                    $from = $data_before[$field];
                    $to = $new_value;

                    // Translate invoice_id
                    if ($field === "invoice_id") {
                        $invoices_model = model("Invoices_model");
                        $from = $from ? $invoices_model->get_one($from)->invoice_no : "N/A";
                        $to = $to ? $invoices_model->get_one($to)->invoice_no : "N/A";
                    }

                    // Translate payment_method_id
                    if ($field === "payment_method_id") {
                        $payment_methods_model = model("Payment_methods_model");
                        $from = $from ? $payment_methods_model->get_one($from)->title : "N/A";
                        $to = $to ? $to = $payment_methods_model->get_one($to)->title : "N/A";
                    }

                    $pretty_key = preg_replace('/_id\d*$/', '', $field);
                    $fields_changed[$pretty_key] = ["from" => $from, "to" => $to];
                }
            }
        }

        // ✅ Record summary if created
        $changes = $id
            ? serialize($fields_changed) // if updated
            : serialize([               // if created
                "created_invoice_id" => $invoice_id,
                "amount"             => $saved_payment['amount'],
                "payment_method"     => $payment_method_title,
                "payment_date"        => $saved_payment['payment_date'],
            ]);

        // Prepare log data
        $log_data = [
            "created_at"     => date('Y-m-d H:i:s'),
            "created_by"     => session()->get('user_id'),
            "action"         => $id ? "updated" : "created",
            "log_type"       => "payment",
            "log_for"        => "invoice",
            "log_for_id"    => $invoice_id,
            "log_type_id"   => $save_id,
            "log_type_title" => $payment_method_title
                . " $" . number_format($saved_payment['amount'], 2)
                . " (Invoice #" . $invoice_id . ")",
            "changes"        => $changes
        ];

        $this->db->table($this->db->prefixTable('activity_logs'))->insert($log_data);

        return $save_id;
    }


    public function get_total_paid($vendor_bill_id)
    {
        $t = $this->db->prefixTable('vendor_bill_payments');
        $row = $this->db->query("SELECT SUM(amount) AS total FROM $t WHERE deleted=0 AND vendor_bill_id=" . (int)$vendor_bill_id)->getRow();
        return (float) ($row->total ?? 0);
    }

    public function get_bill_balance_due($vendor_bill_id)
    {
        $bills = $this->db->prefixTable('vendor_bills');
        $row   = $this->db->query("SELECT invoice_total AS total FROM $bills WHERE id=" . (int)$vendor_bill_id . " AND deleted=0")->getRow();
        $total = (float) ($row->total ?? 0);
        return max(0, $total - $this->get_total_paid($vendor_bill_id));
    }
}
