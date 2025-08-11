<?php

 namespace aleelo_plugin\Models;

use App\Models\Crud_model;
use CodeIgniter\Model;

 class Expense_payments_model extends Crud_model
{
    function __construct() {
        $this->table = 'expense_payments'; // ✅ fixed
        parent::__construct($this->table);
    }

    protected $allowedFields = [
        'amount',
        'payment_date',
        'note',
        'expense_id',
        'payment_method',
        'deposit_to',
        'created_by',
        'created_at',
        'deleted'
    ];

    function get_details($options = []) {
        $expense_payments_table = $this->db->prefixTable('expense_payments');
        $expenses_table = $this->db->prefixTable('expenses');
        $payment_methods_table = $this->db->prefixTable('payment_methods');


        $where="";
        $sql = "SELECT $expense_payments_table.*, $expenses_table.amount AS expense_amount, $expenses_table.title AS expense_title,$payment_methods_table.title AS payment_method_title
        FROM $expense_payments_table
        LEFT JOIN $expenses_table ON $expenses_table.id= $expense_payments_table.expense_id
        LEFT JOIN $payment_methods_table ON $payment_methods_table.id = $expense_payments_table.payment_method

        WHERE $expenses_table.deleted=0 $where";
        return $this->db->query($sql);

    }

    public function get_total_paid($expense_id) {
        return $this->db->table($this->table)
            ->selectSum("amount_paid")
            ->where("expense_id", $expense_id)
            ->get()
            ->getRow()
            ->amount_paid;
    }

    function get_one($id = 0) {
        return $this->where('id', $id)->get()->getRow();
    }


    function get_expense_total($expense_id) {
        $expense_payments_table = $this->db->prefixTable('expense_payments');
        $expenses_table = $this->db->prefixTable('expenses');
        $supplier_table = $this->db->prefixTable('rise_supplier');

    $expense_id = $this->_get_clean_value($expense_id);

    // Use the updated meta calculation
    $result = $this->get_invoice_total_meta($expense_id);

    // Get client currency info
$supplier_sql = "SELECT $supplier_table.* FROM $supplier_table 
                 WHERE $supplier_table.id = (
                    SELECT $expenses_table.vendor_id 
                    FROM $expenses_table 
                    WHERE $expenses_table.id = $expense_id
                 )";

   
    // Get total payments
$payment_sql = "SELECT SUM($expense_payments_table.amount_paid) AS total_paid
                FROM $expense_payments_table
                WHERE $expense_payments_table.deleted=0 
                AND $expense_payments_table.expense_id=$expense_id";

    $payment = $this->db->query($payment_sql)->getRow();

    $result->total_paid = $payment && $payment->total_paid ? (float)$payment->total_paid :"" ;

    // Balance due
$result->balance_due = round((float)$result->amount - (float)$result->total_paid, 100);

    return $result;
}
function get_invoice_total_meta($expense_id) {
    $id = $this->_get_clean_value($expense_id);
    $expenses_table = $this->db->prefixTable('expenses');

    // Fetch cost + service% from items
$items_sql = "SELECT * FROM $expenses_table WHERE id = $expense_id AND deleted = 0";

$items = $this->db->query($items_sql)->getRow();
$total = ($items && isset($items->amount)) ? $items->amount : 44;


    $info = new \stdClass();
    $info->amount = number_format($total, 2, ".", "") * 1;

    return $info;
}
}

