<?php

namespace Vendors\Models;

use App\Models\Crud_model;

class Vendor_bills_model extends Crud_model
{

    protected $table = null;

    function __construct()
    {
        $this->table = 'vendor_bills';
        parent::__construct($this->table);
    }

    // public function get_details($options = array())
    // {
    //     $bills_table     = $this->db->prefixTable('vendor_bills');          // rise_vendor_bills
    //     $payments_table  = $this->db->prefixTable('vendor_bill_payments');  // rise_vendor_bill_payments
    //     $vendor_table    = $this->db->prefixTable('vendor');                // rise_vendor
    //     $projects_table  = $this->db->prefixTable('projects');             // rise_projects
    //     $branch_table = $this->db->prefixTable('branch');



    //     $where = "";

    //     // filters
    //     $id = $this->_get_clean_value($options, "id");
    //     if ($id) {
    //         $where .= " AND $bills_table.id=" . $this->db->escape($id);
    //     }

    //     $status = $this->_get_clean_value($options, "status");
    //     if ($status) {
    //         $where .= " AND $bills_table.status=" . $this->db->escape($status);
    //     }

    //     $vendor_id = $this->_get_clean_value($options, "vendor_id");
    //     if ($vendor_id) {
    //         $where .= " AND $bills_table.vendor_id=" . $this->db->escape($vendor_id);
    //     }

    //     $project_id = $this->_get_clean_value($options, "project_id");
    //     if ($project_id) {
    //         $where .= " AND $bills_table.project_id=" . $this->db->escape($project_id);
    //     }

    //     // date range (bill_date)
    //     $start_date = $this->_get_clean_value($options, "start_date");
    //     $end_date   = $this->_get_clean_value($options, "end_date");
    //     if ($start_date && $end_date) {
    //         $where .= " AND ($bills_table.bill_date BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date) . ") ";
    //     }

    //     // custom fields for entity: vendor_bills
    //     $custom_fields       = get_array_value($options, "custom_fields");
    //     $custom_field_filter = get_array_value($options, "custom_field_filter");
    //     $cf_info             = $this->prepare_custom_field_query_string("vendor_bills", $custom_fields, $bills_table, $custom_field_filter);
    //     $select_cfs          = get_array_value($cf_info, "select_string");
    //     $join_cfs            = get_array_value($cf_info, "join_string");
    //     $where_cfs           = get_array_value($cf_info, "where_string");

    //     // payments aggregation (per bill)
    //     $payments_sql = "SELECT vendor_bill_id, SUM(amount) AS payment_received
    //                  FROM $payments_table
    //                  WHERE deleted=0
    //                  GROUP BY vendor_bill_id";

    //     // stub labels (if you don’t yet have a labels join)
    //     $labels_select = ", '' AS labels_list";

    //     $sql = "SELECT 
    //             b.*,
    //             b.invoice_total AS bill_value,
    //             v.id   AS vendor_id,
    //             v.vendor_name,
    //             p.id   AS project_id,
    //             p.title AS project_title,
    //             br.name AS branch_name,
    //             IFNULL(pay.payment_received, 0) AS payment_received,
    //             (COALESCE(b.invoice_total,0) - COALESCE(pay.payment_received,0)) AS due_amount
    //             $labels_select
    //             $select_cfs
    //         FROM $bills_table b
    //         LEFT JOIN ($payments_sql) AS pay ON pay.vendor_bill_id = b.id
    //         LEFT JOIN $vendor_table v   ON v.id = b.vendor_id
    //         LEFT JOIN $projects_table p ON p.id = b.project_id
    //         LEFT JOIN $branch_table br ON br.id = v.branch_id

    //         $join_cfs
    //         WHERE b.deleted=0 $where $where_cfs";

    //     return $this->db->query($sql);
    // }

    public function get_details($options = array())
    {
        $bills_table    = $this->db->prefixTable('vendor_bills');          // rise_vendor_bills
        $payments_table = $this->db->prefixTable('vendor_bill_payments');  // rise_vendor_bill_payments
        $vendor_table   = $this->db->prefixTable('vendor');                // rise_vendor
        $projects_table = $this->db->prefixTable('projects');              // rise_projects
        // Branch functionality removed - not needed in vendors plugin
        // $branch_table   = $this->db->prefixTable('branch');                // rise_branch

        // --- filters we’ll assemble safely ---
        $whereParts = [];
        $whereParts[] = "b.deleted = 0";

        $id = $this->_get_clean_value($options, "id");
        if ($id) {
            $whereParts[] = "b.id = " . $this->db->escape($id);
        }

        // status: support UI statuses via computed logic; fall back to column for known direct statuses
        $status = trim((string)($this->_get_clean_value($options, "status") ?? ''));
        if ($status !== '') {
            switch ($status) {
                case 'fully_paid':
                    $whereParts[] = "COALESCE(pay.payment_received,0) >= COALESCE(b.invoice_total,0)
                                    AND COALESCE(b.invoice_total,0) > 0";
                    break;

                case 'partially_paid':
                    $whereParts[] = "COALESCE(pay.payment_received,0) > 0
                                    AND COALESCE(pay.payment_received,0) < COALESCE(b.invoice_total,0)";
                    break;

                case 'not_paid':
                    $whereParts[] = "COALESCE(pay.payment_received,0) = 0
                                    AND COALESCE(b.invoice_total,0) > 0
                                    AND b.status NOT IN ('cancelled','void')";
                    break;

                case 'overdue':
                    $whereParts[] = "b.due_date IS NOT NULL
                                    AND b.due_date < CURDATE()
                                    AND COALESCE(pay.payment_received,0) < COALESCE(b.invoice_total,0)
                                    AND b.status NOT IN ('cancelled','void','draft')";
                    break;

                // direct, stored statuses
                case 'draft':
                case 'cancelled':
                case 'void':
                case 'credited':
                case 'paid':           // in your DB sample, “paid” is stored
                case 'not_paid':       // if you also store this literally, the computed branch above already handles most cases
                case 'partially_paid': // ditto
                    $whereParts[] = "b.status = " . $this->db->escape($status);
                    break;

                default:
                    // unknown status; treat as exact match to be safe
                    $whereParts[] = "b.status = " . $this->db->escape($status);
            }
        }

        $vendor_id = $this->_get_clean_value($options, "vendor_id");
        if ($vendor_id) {
            $whereParts[] = "b.vendor_id = " . $this->db->escape($vendor_id);
        }

        // Projects removed - not needed in vendors plugin
        // $project_id = $this->_get_clean_value($options, "project_id");
        // if ($project_id) {
        //     $whereParts[] = "b.project_id = " . $this->db->escape($project_id);
        // }

        // date range (bill_date)
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date   = $this->_get_clean_value($options, "end_date");
        if ($start_date && $end_date) {
            $whereParts[] = "(b.bill_date BETWEEN " . $this->db->escape($start_date) . " AND " . $this->db->escape($end_date) . ")";
        }

        // custom fields for entity: vendor_bills
        $custom_fields       = get_array_value($options, "custom_fields");
        $custom_field_filter = get_array_value($options, "custom_field_filter");

        // IMPORTANT: pass the alias "b" so CF conditions don’t reference the base table name after we’ve aliased it.
        $cf_info   = $this->prepare_custom_field_query_string("vendor_bills", $custom_fields, "b", $custom_field_filter);
        $select_cfs = get_array_value($cf_info, "select_string");
        $join_cfs   = get_array_value($cf_info, "join_string");
        $where_cfs  = trim((string)get_array_value($cf_info, "where_string"));

        if ($where_cfs !== "") {
            // $where_cfs already starts with AND in most implementations; be defensive:
            if (stripos($where_cfs, 'and ') === 0) {
                $whereParts[] = substr($where_cfs, 4);
            } else {
                $whereParts[] = $where_cfs;
            }
        }

        // keyword search (display id, numeric id, vendor, project, note)
        $search = trim((string)($this->_get_clean_value($options, "search") ?? ''));
        if ($search !== '') {
            $kw = $this->db->escapeLikeString($search);

            $or = [];
            $or[] = "b.display_id LIKE '%{$kw}%'";
            $or[] = "CAST(b.id AS CHAR) LIKE '%{$kw}%'";
            $or[] = "v.vendor_name LIKE '%{$kw}%'";
            $or[] = "p.title LIKE '%{$kw}%'";
            $or[] = "b.note LIKE '%{$kw}%'";

            // optional: numeric match on totals when user types a number
            if (is_numeric($search)) {
                // exact amount match at 2 decimals (tweak if you want fuzzy)
                $num = (float)$search;
                $or[] = "ROUND(b.invoice_total, 2) = " . $this->db->escape($num);
                $or[] = "ROUND(COALESCE(pay.payment_received,0), 2) = " . $this->db->escape($num);
                $or[] = "ROUND(COALESCE(b.invoice_total,0) - COALESCE(pay.payment_received,0), 2) = " . $this->db->escape($num);
            }

            $whereParts[] = '(' . implode(' OR ', $or) . ')';
        }


        // payments aggregation (per bill)
        $payments_sql = "SELECT vendor_bill_id, SUM(amount) AS payment_received
                        FROM $payments_table
                        WHERE deleted = 0
                        GROUP BY vendor_bill_id";

        // stub labels (until you add labels join)
        $labels_select = ", '' AS labels_list";

        $whereSql = implode(' AND ', $whereParts);
        if ($whereSql !== '') {
            $whereSql = "WHERE " . $whereSql;
        }

        $sql = "SELECT 
                    b.*,
                    b.invoice_total AS bill_value,
                    v.id   AS vendor_id,
                    v.vendor_name,
                    IFNULL(pay.payment_received, 0) AS payment_received,
                    (COALESCE(b.invoice_total,0) - COALESCE(pay.payment_received,0)) AS due_amount
                    $labels_select
                    $select_cfs
                FROM $bills_table b
                LEFT JOIN ($payments_sql) AS pay ON pay.vendor_bill_id = b.id
                LEFT JOIN $vendor_table   v  ON v.id = b.vendor_id
                $join_cfs
                $whereSql";

        return $this->db->query($sql);
    }





    function get_vendor_bills_total_summary($invoice_id)
    {
        $invoice_payments_table = $this->db->prefixTable('vendor_bill_payments');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('vendor_bills');

        $invoice_id = $this->_get_clean_value($invoice_id);

        $result = $this->get_vendor_bills_total_meta($invoice_id);

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=(SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$invoice_id LIMIT 1)";
        $client = $this->db->query($client_sql)->getRow();
        // print_r($client);die;
        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");


        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.vendor_bill_id=$invoice_id";
        $payment = $this->db->query($payment_sql)->getRow();

        $result->total_paid = is_null($payment->total_paid) ? 0 : $payment->total_paid;
        $result->balance_due = number_format($result->invoice_total, 2, ".", "") - number_format($result->total_paid, 2, ".", "");

        return $result;
    }

    function get_vendor_bills_total_summary_supplier($invoice_id)
    {
        $invoice_payments_table = $this->db->prefixTable('invoice_supplier_payments');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');

        $invoice_id = $this->_get_clean_value($invoice_id);

        $result = $this->get_invoice_total_meta_supplier($invoice_id);

        $client_sql = "SELECT $clients_table.currency_symbol, $clients_table.currency FROM $clients_table WHERE $clients_table.id=(SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$invoice_id LIMIT 1)";
        $client = $this->db->query($client_sql)->getRow();
        // print_r($client);die;
        $result->currency_symbol = $client->currency_symbol ? $client->currency_symbol : get_setting("currency_symbol");


        $result->currency = $client->currency ? $client->currency : get_setting("default_currency");

        $payment_sql = "SELECT SUM($invoice_payments_table.amount) AS total_paid
        FROM $invoice_payments_table
        WHERE $invoice_payments_table.deleted=0 AND $invoice_payments_table.invoice_id=$invoice_id";
        $payment = $this->db->query($payment_sql)->getRow();

        $result->total_paid = is_null($payment->total_paid) ? 0 : $payment->total_paid;
        $result->balance_due = number_format($result->invoice_total, 2, ".", "") - number_format($result->total_paid, 2, ".", "");

        return $result;
    }
    function invoice_statistics($options = array())
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $invoice_payments_table = $this->db->prefixTable('invoice_payments');
        $clients_table = $this->db->prefixTable('clients');

        $info = new \stdClass();
        $year = get_my_local_time("Y");

        $where = "";
        $payments_where = "";
        $invoices_where = "";
        $invoice_date_where = "";

        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");

        $generate_reports_based_on = "$invoices_table.due_date";
        if (get_setting("generate_reports_based_on") == "bill_date") {
            $generate_reports_based_on = "$invoices_table.bill_date";
        }

        if ($start_date && $end_date) {
            $invoice_date_where .= " AND ($generate_reports_based_on BETWEEN '$start_date' AND '$end_date')";
        } else {
            $invoice_date_where .= " AND YEAR($generate_reports_based_on)=$year";
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        } else {
            $invoices_where = $this->_get_clients_of_currency_query($this->_get_clean_value($options, "currency"), $invoices_table, $clients_table);

            $payments_where = " AND $invoice_payments_table.invoice_id IN(SELECT $invoices_table.id FROM $invoices_table WHERE $invoices_table.deleted=0 $invoices_where)";
        }

        $payments = $this->_get_clean_value($options, "payments");
        if ($payments) {
            $payments = "SELECT SUM($invoice_payments_table.amount) AS total, MONTH($invoice_payments_table.payment_date) AS month
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND YEAR($invoice_payments_table.payment_date)=$year AND $invoices_table.deleted=0 $where $payments_where
            GROUP BY MONTH($invoice_payments_table.payment_date)";

            $info->payments = $this->db->query($payments)->getResult();
        }


        $invoices = "SELECT SUM($invoices_table.invoice_total) AS total, MONTH(bill_date) AS month 
            FROM $invoices_table  
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' $where $invoice_date_where $invoices_where
            GROUP BY MONTH(bill_date)";

        $info->invoices = $this->db->query($invoices)->getResult();
        $info->currencies = $this->get_used_currencies_of_client()->getResult();

        return $info;
    }

    function get_used_currencies_of_client()
    {
        $clients_table = $this->db->prefixTable('clients');
        $default_currency = get_setting("default_currency");

        $sql = "SELECT $clients_table.currency, $clients_table.currency_symbol
            FROM $clients_table
            WHERE $clients_table.deleted=0 AND $clients_table.currency!='' AND $clients_table.currency!='$default_currency'
            GROUP BY $clients_table.currency";

        return $this->db->query($sql);
    }

    function get_invoices_total_and_paymnts($options = array())
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $invoice_payments_table = $this->db->prefixTable('invoice_payments');
        $clients_table = $this->db->prefixTable('clients');

        $info = new \stdClass();

        $tolarance = get_paid_status_tolarance();

        $where = "";

        $return_only = get_array_value($options, "return_only");

        $currency = $this->_get_clean_value($options, "currency");
        if ($currency) {
            $where .= $this->_get_clients_of_currency_query($currency, $invoices_table, $clients_table);
        }

        $client_id = $this->_get_clean_value($options, "client_id");
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }

        $payments = "SELECT SUM($invoice_payments_table.amount) AS total,
            (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=(
                SELECT $invoices_table.client_id FROM $invoices_table WHERE $invoices_table.id=$invoice_payments_table.invoice_id
                )
            ) AS currency
            FROM $invoice_payments_table
            LEFT JOIN $invoices_table ON $invoices_table.id=$invoice_payments_table.invoice_id    
            WHERE $invoice_payments_table.deleted=0 AND $invoices_table.deleted=0 $where
            GROUP BY currency";

        $now = get_my_local_time("Y-m-d");

        $invoices = "SELECT SUM($invoices_table.invoice_total) AS total, SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' $where
            GROUP BY currency";

        $draft = "SELECT SUM($invoices_table.invoice_total) AS total, SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='draft' $where
            GROUP BY currency";

        $fully_paid = "SELECT SUM($invoices_table.invoice_total) AS total, SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table
            LEFT JOIN (SELECT invoice_id, SUM($invoice_payments_table.amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
            WHERE  $invoices_table.deleted=0 AND $invoices_table.status='not_paid' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)>=($invoices_table.invoice_total-$tolarance) $where
            GROUP BY currency";

        $partially_paid = "SELECT SUM($invoices_table.invoice_total) AS total, SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table
            LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' AND IFNULL(payments_table.payment_received,0)>0 && TRUNCATE(IFNULL(payments_table.payment_received,0),2) < $invoices_table.invoice_total-$tolarance $where
            GROUP BY currency";

        $not_paid = "SELECT SUM($invoices_table.invoice_total) AS total, SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table            
            LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0 AND $invoices_table.status='not_paid' AND IFNULL(payments_table.payment_received,0)<=0 $where
            GROUP BY currency";

        $overdue = "SELECT SUM($invoices_table.invoice_total - IFNULL(payments_table.payment_received,0)) AS total , SUM(1) AS count, (SELECT $clients_table.currency FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id) AS currency
            FROM $invoices_table
            LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id 
            WHERE $invoices_table.deleted=0  AND $invoices_table.status='not_paid' AND $invoices_table.due_date<'$now' AND TRUNCATE(IFNULL(payments_table.payment_received,0),2)<$invoices_table.invoice_total-$tolarance $where
            GROUP BY currency";

        $payments_total = 0;

        if (!$return_only || $return_only == "payments" || $return_only == "due") {
            $payments_result = $this->db->query($payments)->getResult();
            foreach ($payments_result as $payment) {
                if ($currency) {
                    $payments_total += $payment->total ? $payment->total : 0;  //no need to convert since user will see currency wise total. 
                } else {
                    $payments_total += get_converted_amount($payment->currency, $payment->total);
                }
            }
        }

        $invoices_total = 0;
        $invoices_count = 0;

        if (!$return_only || $return_only == "invoices" || $return_only == "due") {
            $invoices_result = $this->db->query($invoices)->getResult();
            foreach ($invoices_result as $invoice) {
                $invoices_count += $invoice->count;
                if ($currency) {
                    $invoices_total += $invoice->total ? $invoice->total : 0; //no need to convert since user will see currency wise total. 
                } else {
                    $invoices_total += get_converted_amount($invoice->currency, $invoice->total);
                }
            }
        }

        $draft_total = 0;
        $draft_count = 0;
        if (!$return_only || $return_only == "draft") {
            $drafts_result = $this->db->query($draft)->getResult();
            foreach ($drafts_result as $draft) {
                $draft_count += $draft->count;
                if ($currency) {
                    $draft_total += $draft->total ? $draft->total : 0;
                } else {
                    $draft_total += get_converted_amount($draft->currency, $draft->total);
                }
            }
        }

        $fully_paid_total = 0;
        $fully_paid_count = 0;
        if (!$return_only || $return_only == "fully_paid") {
            $fully_paid_result = $this->db->query($fully_paid)->getResult();
            foreach ($fully_paid_result as $fully_paid) {
                $fully_paid_count += $fully_paid->count;
                if ($currency) {
                    $fully_paid_total += $fully_paid->total ? $fully_paid->total : 0;
                } else {
                    $fully_paid_total += get_converted_amount($fully_paid->currency, $fully_paid->total);
                }
            }
        }

        $partially_paid_total = 0;
        $partially_paid_count = 0;
        if (!$return_only || $return_only == "partially_paid") {
            $partially_paid_result = $this->db->query($partially_paid)->getResult();
            foreach ($partially_paid_result as $partially_paid) {
                $partially_paid_count += $partially_paid->count;
                if ($currency) {
                    $partially_paid_total += $partially_paid->total ? $partially_paid->total : 0;
                } else {
                    $partially_paid_total += get_converted_amount($partially_paid->currency, $partially_paid->total);
                }
            }
        }

        $not_paid_total = 0;
        $not_paid_count = 0;
        if (!$return_only || $return_only == "not_paid") {
            $not_paid_result = $this->db->query($not_paid)->getResult();
            foreach ($not_paid_result as $not_paid) {
                $not_paid_count += $not_paid->count;
                if ($currency) {
                    $not_paid_total += $not_paid->total ? $not_paid->total : 0;
                } else {
                    $not_paid_total += get_converted_amount($not_paid->currency, $not_paid->total);
                }
            }
        }

        $overdue_total = 0;
        $overdue_count = 0;
        if (!$return_only || $return_only == "overdue") {
            $overdue_result = $this->db->query($overdue)->getResult();
            foreach ($overdue_result as $overdue) {
                $overdue_count += $overdue->count;

                if ($currency) {
                    $overdue_total += $overdue->total ? $overdue->total : 0;
                } else {
                    $overdue_total += get_converted_amount($overdue->currency, $overdue->total);
                }
            }
        }

        $info->payments_total = $payments_total;

        $info->invoices_total = $invoices_total;
        $info->invoices_count = $invoices_count;

        $info->draft_total = $draft_total;
        $info->draft_count = $draft_count;

        $info->fully_paid_total = $fully_paid_total;
        $info->fully_paid_count = $fully_paid_count;

        $info->partially_paid_total = $partially_paid_total;
        $info->partially_paid_count = $partially_paid_count;

        $info->not_paid = $not_paid_total;
        $info->not_paid_count = $not_paid_count;

        $info->overdue = $overdue_total;
        $info->overdue_count = $overdue_count;

        $info->due = ignor_minor_value($invoices_total - $payments_total);

        return $info;
    }

    //update invoice status
    function update_invoice_status($invoice_id = 0, $status = "not_paid")
    {
        $status = $this->_get_clean_value(array("status" => $status), "status");
        $status_data = array("status" => $status);
        return $this->ci_save($status_data, $invoice_id);
    }

    //get the recurring invoices which are ready to renew as on a given date
    function get_renewable_invoices($date)
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $date = $this->_get_clean_value($date);

        $sql = "SELECT * FROM $invoices_table
                        WHERE $invoices_table.deleted=0 AND $invoices_table.recurring=1
                        AND $invoices_table.next_recurring_date IS NOT NULL AND $invoices_table.next_recurring_date<='$date'
                        AND ($invoices_table.no_of_cycles < 1 OR ($invoices_table.no_of_cycles_completed < $invoices_table.no_of_cycles ))";

        return $this->db->query($sql);
    }

    //get invoices dropdown list
    // Branch functionality removed - not needed in vendors plugin
    function get_invoices_dropdown_list($client_id = 0, $branch_id = 0)
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $invoice_payments_table = $this->db->prefixTable('invoice_payments');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');
        $users_table = $this->db->prefixTable('users');
        // Branch functionality removed - not needed in vendors plugin
        // $branch_table = $this->db->prefixTable('branch');

        $where = "";
        $client_id = $this->_get_clean_value($client_id);
        if ($client_id) {
            $where .= " AND $invoices_table.client_id=$client_id";
        }
        // Branch functionality removed - not needed in vendors plugin
        // $branch_id = $this->_get_clean_value($branch_id);
        // if ($branch_id) {
        //     if (is_array($branch_id)) {
        //         $escaped_ids = array_map(function ($id) {
        //             return db_connect()->escape($id);
        //         }, $branch_id);
        //         $where .= " AND $clients_table.branch_id IN (" . implode(",", $escaped_ids) . ")";
        //     } elseif (is_numeric($branch_id)) {
        //         $where .= " AND $clients_table.branch_id=" . db_connect()->escape($branch_id);
        //     }
        // }

        $sql = "SELECT $invoices_table.id, $invoices_table.display_id, (IFNULL($invoices_table.invoice_total, 0) - IFNULL(payments_table.payment_received, 0)) AS invoice_due,
                    (SELECT $clients_table.currency_symbol FROM $clients_table WHERE $clients_table.id=$invoices_table.client_id limit 1) AS currency_symbol
                FROM $invoices_table
                        LEFT JOIN $clients_table ON $clients_table.id= $invoices_table.client_id
        -- Branch functionality removed: LEFT JOIN branch_table ON clients_table.branch_id = branch_table.id 
                LEFT JOIN (SELECT invoice_id, SUM(amount) AS payment_received FROM $invoice_payments_table WHERE deleted=0 GROUP BY invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id
                WHERE $invoices_table.deleted=0 AND $invoices_table.type = 'invoice' AND $invoices_table.status NOT IN ('credited', 'cancelled') AND IFNULL($invoices_table.invoice_total, 0) > IFNULL(payments_table.payment_received, 0) $where
                ORDER BY $invoices_table.id DESC";

        return $this->db->query($sql);
    }

    //get label suggestions
    function get_label_suggestions()
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $sql = "SELECT GROUP_CONCAT(labels) as label_groups
        FROM $invoices_table
        WHERE $invoices_table.deleted=0";
        return $this->db->query($sql)->getRow()->label_groups;
    }


    //save initial number of invoice
    function save_initial_number_of_invoice($value)
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $value = $this->_get_clean_value($value);

        $sql = "ALTER TABLE $invoices_table AUTO_INCREMENT=$value;";

        return $this->db->query($sql);
    }

    function get_vendor_bills_total_meta($invoice_id)
    {
        $id = $this->_get_clean_value($invoice_id);

        $invoices_table = $this->db->prefixTable('vendor_bills');
        $invoice_items_table = $this->db->prefixTable('vendor_bill_items');
        $info = $this->get_sales_total_meta($id, $invoices_table, $invoice_items_table);
        $advance_row = $this->db->table($invoices_table)
            ->select('Advance')
            ->where('id', $id)
            ->get()
            ->getRow();

        $info->Advance = $advance_row ? $advance_row->Advance : 0;

        return $info;
    }
    function get_invoice_total_meta_supplier($invoice_id)
    {
        $id = $this->_get_clean_value($invoice_id);

        $invoices_table = $this->db->prefixTable('invoices');
        $invoice_items_table = $this->db->prefixTable('invoice_items');

        $info = $this->get_sales_total_meta_supplier($id, $invoices_table, $invoice_items_table);
        return $info;
    }

    // protected function get_sales_total_meta_supplier($id, $main_table, $items_table)
    // {

    //     //$main_table like as invoices table
    //     //$items_table like as invoice_items_table
    //     $taxes_table = $this->db->prefixTable('taxes');

    //     $invoice_sql = "SELECT $main_table.id, $main_table.discount_amount, $main_table.discount_amount_type, $main_table.discount_type,
    //             tax_table.percentage AS tax_percentage, tax_table2.percentage AS tax_percentage2, tax_table3.percentage AS tax_percentage3,
    //             tax_table.title AS tax_name, tax_table2.title AS tax_name2, tax_table3.title AS tax_name3,
    //             taxable_item.total_taxable, non_taxable_item.total_non_taxable
    //             FROM $main_table
    //             LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage, $taxes_table.title FROM $taxes_table) AS tax_table ON tax_table.id = $main_table.tax_id
    //             LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage, $taxes_table.title FROM $taxes_table) AS tax_table2 ON tax_table2.id = $main_table.tax_id2
    //             LEFT JOIN (SELECT $taxes_table.id, $taxes_table.percentage, $taxes_table.title FROM $taxes_table) AS tax_table3 ON tax_table3.id = $main_table.tax_id3
    //             LEFT JOIN (SELECT SUM($items_table.supplier_price) AS total_taxable, $items_table.invoice_id FROM $items_table WHERE $items_table.deleted=0 AND $items_table.taxable = 1 AND $items_table.supplier = 1 GROUP BY $items_table.invoice_id) AS taxable_item ON taxable_item.invoice_id = $main_table.id
    //             LEFT JOIN (SELECT SUM($items_table.supplier_price) AS total_non_taxable, $items_table.invoice_id  FROM $items_table WHERE $items_table.deleted=0 AND $items_table.taxable = 0 AND $items_table.supplier = 1 GROUP BY $items_table.invoice_id) AS non_taxable_item ON non_taxable_item.invoice_id = $main_table.id
    //             WHERE $main_table.deleted=0 AND $main_table.id = $id";

    //     $invoice_info = $this->db->query($invoice_sql)->getRow();

    //     if (!$invoice_info->id) {
    //         return null;
    //     }

    //     $total_taxable = $invoice_info->total_taxable ? $invoice_info->total_taxable : 0;
    //     $total_non_taxable = $invoice_info->total_non_taxable ? $invoice_info->total_non_taxable : 0;
    //     $sub_total = $total_taxable + $total_non_taxable;
    //     $discount_total = 0;
    //     $invoice_total = 0;

    //     if ($invoice_info->discount_amount_type == "percentage") {

    //         $non_taxable_discount_value = $total_non_taxable * ($invoice_info->discount_amount / 100);

    //         if ($invoice_info->discount_type == "before_tax") {
    //             $taxable_discount_value = $total_taxable * ($invoice_info->discount_amount / 100);
    //             $total_taxable = $total_taxable - $taxable_discount_value; //apply discount before tax
    //         }

    //         $tax1 = $total_taxable * ($invoice_info->tax_percentage / 100);
    //         $tax2 = $total_taxable * ($invoice_info->tax_percentage2 / 100);
    //         $tax3 = $total_taxable * ($invoice_info->tax_percentage3 / 100);
    //         $total_taxable = $total_taxable + $tax1 + $tax2 - $tax3;

    //         $invoice_total = $total_taxable + $total_non_taxable - $non_taxable_discount_value; //deduct only non-taxable discount since the taxable discount already deducted 

    //         if ($invoice_info->discount_type == "after_tax") {
    //             $taxable_discount_value = $total_taxable * ($invoice_info->discount_amount / 100);
    //             $invoice_total = $total_taxable + $total_non_taxable - $taxable_discount_value - $non_taxable_discount_value;
    //         }

    //         $discount_total = $taxable_discount_value + $non_taxable_discount_value;
    //     } else {
    //         //discount_amount_type is fixed_amount

    //         $discount_total = $invoice_info->discount_amount; //fixed amount 
    //         //fixed amount discount. fixed amount can't be applied before tax when there are both taxable and non-taxable items.
    //         //calculate all togather 

    //         if ($invoice_info->discount_type == "before_tax" && $total_taxable > 0) {
    //             $total_taxable = $total_taxable - $discount_total;
    //         } else if ($invoice_info->discount_type == "before_tax" && $total_taxable == 0) {
    //             $total_non_taxable = $total_non_taxable - $discount_total;
    //         }


    //         $tax1 = $total_taxable * ($invoice_info->tax_percentage / 100);
    //         $tax2 = $total_taxable * ($invoice_info->tax_percentage2 / 100);
    //         $tax3 = $total_taxable * ($invoice_info->tax_percentage3 / 100);
    //         $invoice_total = $total_taxable + $total_non_taxable + $tax1 + $tax2 - $tax3; //discount before tax

    //         if ($invoice_info->discount_type == "after_tax") {
    //             $invoice_total = $total_taxable + $total_non_taxable + $tax1 + $tax2 - $tax3 - $discount_total;
    //         }
    //     }

    //     $info = new \stdClass();
    //     $info->invoice_total = number_format($invoice_total, 2, ".", "") * 1;
    //     $info->invoice_subtotal = number_format($sub_total, 2, ".", "") * 1;
    //     $info->discount_total = number_format($discount_total, 2, ".", "") * 1;

    //     $info->tax_percentage = $invoice_info->tax_percentage;
    //     $info->tax_percentage2 = $invoice_info->tax_percentage2;
    //     $info->tax_percentage3 = $invoice_info->tax_percentage3;
    //     $info->tax_name = $invoice_info->tax_name;
    //     $info->tax_name2 = $invoice_info->tax_name2;
    //     $info->tax_name3 = $invoice_info->tax_name3;

    //     $info->tax = number_format($tax1, 2, ".", "") * 1;
    //     $info->tax2 = number_format($tax2, 2, ".", "") * 1;
    //     $info->tax3 = number_format($tax3, 2, ".", "") * 1;

    //     $info->discount_type = $invoice_info->discount_type;
    //     return $info;
    // }

    protected function get_sales_total_meta_supplier($id, $main_table, $items_table)
    {
        $taxes_table = $this->db->prefixTable('taxes');

        $invoice_sql = "SELECT $main_table.id, $main_table.discount_amount, $main_table.discount_amount_type, $main_table.discount_type,
                taxable_item.total_taxable, non_taxable_item.total_non_taxable
                FROM $main_table
                LEFT JOIN (SELECT SUM($items_table.supplier_price) AS total_taxable, $items_table.invoice_id 
                           FROM $items_table 
                           WHERE $items_table.deleted=0 AND $items_table.taxable = 1 AND $items_table.supplier = 1 
                           GROUP BY $items_table.invoice_id) AS taxable_item ON taxable_item.invoice_id = $main_table.id
                LEFT JOIN (SELECT SUM($items_table.supplier_price) AS total_non_taxable, $items_table.invoice_id  
                           FROM $items_table 
                           WHERE $items_table.deleted=0 AND $items_table.taxable = 0 AND $items_table.supplier = 1 
                           GROUP BY $items_table.invoice_id) AS non_taxable_item ON non_taxable_item.invoice_id = $main_table.id
                WHERE $main_table.deleted=0 AND $main_table.id = $id";

        $invoice_info = $this->db->query($invoice_sql)->getRow();

        if (!$invoice_info || !$invoice_info->id) {
            return null;
        }

        $total_taxable     = $invoice_info->total_taxable ? $invoice_info->total_taxable : 0;
        $total_non_taxable = $invoice_info->total_non_taxable ? $invoice_info->total_non_taxable : 0;
        $sub_total         = $total_taxable + $total_non_taxable;
        $discount_total    = 0;
        $invoice_total     = 0;

        if ($invoice_info->discount_amount_type == "percentage") {
            $non_taxable_discount_value = $total_non_taxable * ($invoice_info->discount_amount / 100);

            if ($invoice_info->discount_type == "before_tax") {
                $taxable_discount_value = $total_taxable * ($invoice_info->discount_amount / 100);
                $total_taxable -= $taxable_discount_value; // apply discount before tax
            }

            $invoice_total = $total_taxable + $total_non_taxable - $non_taxable_discount_value;

            if ($invoice_info->discount_type == "after_tax") {
                $taxable_discount_value = $total_taxable * ($invoice_info->discount_amount / 100);
                $invoice_total = $total_taxable + $total_non_taxable - $taxable_discount_value - $non_taxable_discount_value;
            }

            $discount_total = ($taxable_discount_value ?? 0) + $non_taxable_discount_value;
        } else {
            // fixed discount
            $discount_total = $invoice_info->discount_amount;

            if ($invoice_info->discount_type == "before_tax" && $total_taxable > 0) {
                $total_taxable -= $discount_total;
            } elseif ($invoice_info->discount_type == "before_tax" && $total_taxable == 0) {
                $total_non_taxable -= $discount_total;
            }

            $invoice_total = $total_taxable + $total_non_taxable;

            if ($invoice_info->discount_type == "after_tax") {
                $invoice_total = $total_taxable + $total_non_taxable - $discount_total;
            }
        }

        $info = new \stdClass();
        $info->invoice_total     = number_format($invoice_total, 2, ".", "") * 1;
        $info->invoice_subtotal  = number_format($sub_total, 2, ".", "") * 1;
        $info->discount_total    = number_format($discount_total, 2, ".", "") * 1;

        // Force “no tax” values
        $info->tax_percentage  = 0;
        $info->tax_percentage2 = 0;
        $info->tax_percentage3 = 0;
        $info->tax_name        = 'No Tax';
        $info->tax_name2       = '';
        $info->tax_name3       = '';
        $info->tax             = 0;
        $info->tax2            = 0;
        $info->tax3            = 0;

        $info->discount_type = $invoice_info->discount_type;

        return $info;
    }


    function update_invoice_total_meta($invoice_id)
    {
        $info = $this->get_invoice_total_meta($invoice_id);

        $data = array(
            "invoice_total" => $info->invoice_total,
            "invoice_subtotal" => $info->invoice_subtotal,
            "discount_total" => $info->discount_total,
            "tax" => $info->tax,
            "tax2" => $info->tax2,
            "tax3" => $info->tax3
        );

        return $this->ci_save($data, $invoice_id);
    }

    function save_invoice_and_update_total($data, $id = 0)
    {
        // Fetch the existing invoice if updating
        $data_before = $id ? (array)$this->get_one($id) : [];

        // Save the invoice
        $save_id = $this->ci_save($data, $id);

        // Determine if total/meta update is needed
        $update_total = false;
        $total_updateable_fields = [
            "tax_id",
            "tax_id2",
            "tax_id3",
            "discount_amount",
            "discount_amount_type",
            "discount_type"
        ];
        foreach ($total_updateable_fields as $field) {
            if (array_key_exists($field, $data)) {
                $update_total = true;
            }
        }

        if ($update_total) {
            $this->update_invoice_total_meta($save_id);
        }

        // Fetch saved invoice after update
        $saved_invoice = (array)$this->get_one($save_id);

        // Compute changes only if updating
        $fields_changed = [];
        if ($id) {
            foreach ($saved_invoice as $field => $new_value) {
                if (isset($data_before[$field]) && $data_before[$field] != $new_value) {
                    $from = $data_before[$field];
                    $to   = $new_value;

                    // 🔄 Replace client_id with client name
                    if ($field === "client_id") {
                        $clients_model = model("Vendors\Models\Clients_model");
                        $from = $from ? $clients_model->get_one($from)->company_name : "N/A";
                        $to   = $to ? $clients_model->get_one($to)->company_name : "N/A";
                    }

                    // 🔄 Replace tax_id with tax title
                    if (in_array($field, ["tax_id", "tax_id2", "tax_id3"])) {
                        $taxes_model = model("App\Models\Taxes_model");
                        $from = $from ? $taxes_model->get_one($from)->title : "N/A";
                        $to   = $to ? $taxes_model->get_one($to)->title : "N/A";
                    }

                    // 🔄 Replace project_id with project title
                    // Projects_model removed - not needed in vendors plugin
                    if ($field === "project_id") {
                        // $projects_model = model("Vendors\Models\Projects_model");
                        // $from = $from ? $projects_model->get_one($from)->title : "N/A";
                        // $to   = $to ? $projects_model->get_one($to)->title : "N/A";
                        $from = $from ? "N/A" : "N/A";
                        $to   = $to ? "N/A" : "N/A";
                    }

                    // 🔄 Strip the "_id" part from the field name for the log key
                    $pretty_key = preg_replace('/_id\d*$/', '', $field);

                    $fields_changed[$pretty_key] = [
                        "from" => $from,
                        "to"   => $to,
                    ];

                    // // Fetch new totals
                    $summary = $this->get_invoice_total_summary($save_id); // implement this summary method

                    $fields_changed['discount_total'] = [
                        "from" => $data_before['discount_total'] ?? null,
                        "to"   => "$" . number_format($summary->discount_total, 2, ".", "")
                    ];
                    $fields_changed['balance'] = [
                        "from" => $data_before['total'] ?? null,
                        "to"   => "$" . number_format($summary->invoice_total, 2, ".", "")
                    ];
                }
            }
        }

        // Set the log action
        $log_action = $id ? "updated" : "created";

        // Prepare log data
        $log_data = [
            "created_at"     => date('Y-m-d H:i:s'),
            "created_by"     => session()->get('user_id'),
            "action"         => $log_action,
            "log_type"       => "invoice",
            "log_for"        => "invoice",
            "log_for_id"     => $save_id,
            "log_type_title" => isset($saved_invoice['invoice_no']) && $saved_invoice['invoice_no']
                ? '#' . $saved_invoice['invoice_no']
                : '#' . $saved_invoice['id'],

            "changes"        => $id ? serialize($fields_changed) : "", // empty on create
        ];

        // Save to activity_logs
        $this->db->table($this->db->prefixTable('activity_logs'))->insert($log_data);

        return $save_id;
    }



    function get_invoices_summary($options = array())
    {
        $invoice_payments_table = $this->db->prefixTable('invoice_payments');
        $clients_table = $this->db->prefixTable('clients');
        $invoices_table = $this->db->prefixTable('invoices');

        $where = "";
        $start_date = $this->_get_clean_value($options, "start_date");
        $end_date = $this->_get_clean_value($options, "end_date");

        $generate_reports_based_on = "$invoices_table.due_date";
        if (get_setting("generate_reports_based_on") == "bill_date") {
            $generate_reports_based_on = "$invoices_table.bill_date";
        }

        if ($start_date && $end_date) {
            $where .= " AND ($generate_reports_based_on BETWEEN '$start_date' AND '$end_date') ";
        }

        $show_own_client_invoice_user_id = get_array_value($options, "show_own_client_invoice_user_id");
        if ($show_own_client_invoice_user_id) {
            $where .= " AND $clients_table.owner_id = $show_own_client_invoice_user_id";
        }

        $show_own_invoices_only_user_id = get_array_value($options, "show_own_invoices_only_user_id");
        if ($show_own_invoices_only_user_id) {
            $where .= " AND $invoices_table.created_by = $show_own_invoices_only_user_id";
        }

        $selected_currency = get_array_value($options, "currency");
        $default_currency = get_setting("default_currency");
        $currency = $selected_currency ? $selected_currency : get_setting("default_currency");
        $currency = $this->_get_clean_value(array("currency" => $currency), "currency");

        $where .= ($currency == $default_currency) ? " AND ($clients_table.currency='$default_currency' OR $clients_table.currency='' OR $clients_table.currency IS NULL)" : " AND $clients_table.currency='$currency'";

        $sql = "SELECT COUNT($invoices_table.id) AS invoice_count, SUM($invoices_table.invoice_total) AS invoice_total, SUM($invoices_table.discount_total) AS discount_total, SUM($invoices_table.tax) AS tax_total, SUM($invoices_table.tax2) AS tax2_total, SUM($invoices_table.tax3) AS tax3_total,
                $invoices_table.client_id, $clients_table.company_name AS client_name, $clients_table.currency, $clients_table.currency_symbol,
                SUM(payments_table.payment_received) AS payment_received
            FROM $invoices_table
            LEFT JOIN $clients_table ON $clients_table.id = $invoices_table.client_id             
            LEFT JOIN (SELECT SUM($invoice_payments_table.amount) AS payment_received, $invoice_payments_table.invoice_id FROM $invoice_payments_table WHERE $invoice_payments_table.deleted=0 GROUP BY $invoice_payments_table.invoice_id) AS payments_table ON payments_table.invoice_id = $invoices_table.id
            WHERE $invoices_table.deleted=0 AND $invoices_table.status = 'not_paid' $where
            GROUP BY $invoices_table.client_id";
        $result = $this->db->query($sql);

        return $result;
    }

    //get the last sequence number for a given year
    function get_last_invoice_sequence($year = 0)
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $year = $this->_get_clean_value($year);

        $where = "";
        if ($year) {
            $where =  " AND $invoices_table.number_year=$year ";
        }

        $sql = "SELECT MAX($invoices_table.number_sequence) AS last_sequence
               FROM $invoices_table
               WHERE $invoices_table.deleted=0 $where";

        $result = $this->db->query($sql)->getRow()->last_sequence;

        return $result ? $result : 0;
    }

    function delete_permanently_with_sub_items($id)
    {
        if ($this->delete_permanently($id)) {
            $invoice_items_table = $this->db->prefixTable('invoice_items');
            $this->db->query("DELETE FROM $invoice_items_table WHERE $invoice_items_table.invoice_id=$id");
            return true;
        }
    }

    function get_invoice_basic_info($invoice_id)
    {
        $invoices_table = $this->db->prefixTable('invoices');
        $clients_table = $this->db->prefixTable('clients');

        $sql = "SELECT $invoices_table.id, $invoices_table.created_by, $clients_table.owner_id AS client_owner_id
                FROM $invoices_table
                LEFT JOIN $clients_table ON $clients_table.id = $invoices_table.client_id
                WHERE $invoices_table.id=$invoice_id";

        return $this->db->query($sql)->getRow();
    }
    // public function get_supplier_total_due($supplier_id)
    // {
    //     $db = \Config\Database::connect();

    //     // Get total invoiced amount from the correct table, using the new formula
    //     $builder_invoiced_items = $db->table('rise_invoice_items');
    //     $builder_invoiced_items->select('SUM(supplier_quantity * quantity * days) AS total_invoiced');
    //     $builder_invoiced_items->where('deleted', 0);
    //     $builder_invoiced_items->where('supplier_id', $supplier_id);
    //     $invoice_query = $builder_invoiced_items->get();
    //     $total_invoiced = $invoice_query->getRow()->total_invoiced ?? 0;

    //     // Get total payments made
    //     $builder_payments = $db->table('rise_invoice_supplier_payments');
    //     $builder_payments->select('SUM(amount) AS total_payments');
    //     $builder_payments->where('deleted', 0);
    //     $builder_payments->where('supplier_id', $supplier_id);
    //     $payment_query = $builder_payments->get();
    //     $total_payments = $payment_query->getRow()->total_payments ?? 0;

    //     $balance_due = $total_invoiced - $total_payments;

    //     return (object) [
    //         "total_invoiced" => to_decimal_format($total_invoiced),
    //         "total_payments" => to_decimal_format($total_payments),
    //         "balance_due" => to_decimal_format($balance_due)
    //     ];
    // }

    public function get_supplier_total_due($supplier_id)
    {
        $db = \Config\Database::connect();

        $builder_invoiced_items = $db->table('rise_invoice_items');
        // make NULL-safe to avoid NULL SUM
        $builder_invoiced_items->select('SUM(COALESCE(supplier_quantity,0) * COALESCE(quantity,0) * COALESCE(days,0)) AS total_invoiced');
        $builder_invoiced_items->where('deleted', 0);
        $builder_invoiced_items->where('supplier_id', $supplier_id);
        $invoice_query   = $builder_invoiced_items->get();
        $total_invoiced  = (float) ($invoice_query->getRow()->total_invoiced ?? 0);

        $builder_payments = $db->table('rise_invoice_supplier_payments');
        $builder_payments->select('SUM(COALESCE(amount,0)) AS total_payments');
        $builder_payments->where('deleted', 0);
        $builder_payments->where('supplier_id', $supplier_id);
        $payment_query   = $builder_payments->get();
        $total_payments  = (float) ($payment_query->getRow()->total_payments ?? 0);

        $balance_due = $total_invoiced - $total_payments;

        return (object) [
            // raw
            "total_invoiced_raw" => $total_invoiced,
            "total_payments_raw" => $total_payments,
            "balance_due_raw"    => $balance_due,

            // formatted (for views only)
            "total_invoiced" => to_decimal_format($total_invoiced),
            "total_payments" => to_decimal_format($total_payments),
            "balance_due"    => to_decimal_format($balance_due),
        ];
    }


    /**
     * Save header and (re)compute totals – mirrors Invoices_model::save_invoice_and_update_total
     */
    public function save_vendor_bill_and_update_total(array $data, $id = 0)
    {
        $result_id = $this->ci_save($data, $id);
        if ($result_id) {
            $this->update_vendor_bill_total_meta($result_id);
        }
        return $result_id;
    }

    /**
     * Sum items and write into header – mirrors Invoices_model::update_invoice_total_meta
     * (keeps cloned column names invoice_subtotal/invoice_total for compatibility)
     */
    public function update_vendor_bill_total_meta($vendor_bill_id)
    {
        $bill_items = $this->db->prefixTable('vendor_bill_items');
        $bills      = $this->db->prefixTable('vendor_bills');

        $sumRow = $this->db->query("
            SELECT IFNULL(SUM(total),0) AS subtotal
            FROM $bill_items
            WHERE deleted=0 AND vendor_bill_id = ?
        ", [$vendor_bill_id])->getRow();

        $subtotal = (float)($sumRow->subtotal ?? 0);
        $total    = $subtotal; // extend with tax/discount as needed

        $this->db->table($bills)
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



    // Vendors/Models/Vendor_bills_model.php
    public function get_bill_total_summary(int $bill_id)
    {
        if ($bill_id <= 0) return null;

        $bills = $this->db->prefixTable('vendor_bills');
        $pays  = $this->db->prefixTable('vendor_bill_payments');

        $bill = $this->db->table($bills)
            ->select('id, invoice_total, invoice_subtotal, deleted')
            ->where('id', $bill_id)->where('deleted', 0)
            ->get()->getRow();

        if (!$bill) return null;

        $paidRow = $this->db->table($pays)
            ->select('SUM(amount) AS total_paid')
            ->where('deleted', 0)
            ->where('vendor_bill_id', $bill_id)
            ->get()->getRow();

        $total_paid    = (float)($paidRow->total_paid ?? 0);
        $invoice_total = (float)($bill->invoice_total ?? 0);
        $balance_due   = round($invoice_total - $total_paid, 2);

        return (object)[
            'invoice_total'   => $invoice_total,
            'total_paid'      => $total_paid,
            'balance_due'     => $balance_due,
            // fall back to app defaults; vendor bills typically don’t carry client currency
            'currency_symbol' => get_setting('currency_symbol'),
            'currency'        => get_setting('default_currency'),
        ];
    }


    // Vendor_bills_model

    public function get_list_for_table($filters = [])
    {
        $vt = $this->db->prefixTable('vendor_bills');
        $pt = $this->db->prefixTable('vendor_bill_payments');
        $st = $this->db->prefixTable('vendors');

        $b = $this->db->table("$vt vb");
        $b->select("vb.id, vb.bill_date, vb.total AS bill_value, vb.status,
                v.vendor_name,
                IFNULL(SUM(p.amount),0) AS payment_received");
        $b->join("$st v", "v.id = vb.vendor_id", "left");
        $b->join("$pt p", "p.bill_id = vb.id AND p.deleted=0", "left");
        $b->where("vb.deleted", 0);
        $b->groupBy("vb.id");

        // only filter when column exists
        if ($this->db->fieldExists('converted_to_account', $vt) && !empty($filters['not_converted'])) {
            $b->where("vb.converted_to_account", 0);
        }

        if (!empty($filters['from_date'])) $b->where("vb.bill_date >=", $filters['from_date']);
        if (!empty($filters['to_date']))   $b->where("vb.bill_date <=", $filters['to_date']);

        $rows = $b->get()->getResult();
        $data = [];
        foreach ($rows as $r) {
            $due = (float)$r->bill_value - (float)$r->payment_received;
            $data[] = [
                $r->bill_date,
                $r->vendor_name ?: "-",
                to_currency($r->bill_value),
                to_currency($r->payment_received),
                to_currency($due),
                $r->status,
                modal_anchor(
                    get_uri("vendor_bills/view/" . $r->id),
                    "<i class='fa fa-eye'></i>",
                    ["class" => "btn btn-sm btn-info"]
                )
            ];
        }
        return ["data" => $data];
    }

    public function get_payments_for_table($filters = [])
    {
        $pt = $this->db->prefixTable('vendor_bill_payments');
        $vt = $this->db->prefixTable('vendor_bills');
        $st = $this->db->prefixTable('vendors');
        $mt = $this->db->prefixTable('payment_methods');

        $b = $this->db->table("$pt p");
        $b->select("p.id, p.payment_date, p.amount,
                pm.title AS payment_method,
                vb.id AS bill_id, vb.bill_date, vb.total AS bill_total,
                v.vendor_name");
        $b->join("$vt vb", "vb.id = p.bill_id", "left");
        $b->join("$st v", "v.id = vb.vendor_id", "left");
        $b->join("$mt pm", "pm.id = p.payment_method_id", "left");
        $b->where("p.deleted", 0);

        if ($this->db->fieldExists('converted_to_account', $pt) && !empty($filters['not_converted'])) {
            $b->where("p.converted_to_account", 0);
        }

        if (!empty($filters['from_date'])) $b->where("p.payment_date >=", $filters['from_date']);
        if (!empty($filters['to_date']))   $b->where("p.payment_date <=", $filters['to_date']);

        $rows = $b->get()->getResult();
        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                $r->payment_date,
                to_currency($r->amount),
                $r->payment_method ?: "-",
                "BILL-{$r->bill_id}",
                app_lang('completed'),
                modal_anchor(
                    get_uri("vendor_bills/view_payment/" . $r->id),
                    "<i class='fa fa-eye'></i>",
                    ["class" => "btn btn-sm btn-info"]
                )
            ];
        }
        return ["data" => $data];
    }
}
