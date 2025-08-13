<?php

namespace aleelo_plugin\Controllers;
 use Accounting\Models\Accounting_model;
use aleelo_plugin\Models\Expense_payments_emp_model;
 use aleelo_plugin\Models\Expenses_emp_model;

class Expense_payments_emp extends Security_Controller_Plugin
{
    function __construct()
    {
        parent::__construct();
        // $this->access_only_allowed_members();
        $this->Expense_payments_model = model("Expense_payments_emp_model");
        $this->Expenses_model = model("Expenses_emp_model");
    }

    // Show payments list for an expense
    public function index($expense_id = 0)
{
    validate_numeric_value($expense_id);

    $expense_model = new Expense_payments_emp_model(); 
    $expenses_model = new Expenses_emp_model();

    $accounting_model = new Accounting_model();

    $view_data["expense_id"] = $expense_id;
    $view_data["total_paid"] = $expense_model->get_total_paid($expense_id);
    $view_data["expense_info"] = $expenses_model->get_one($expense_id);
 
 return $this->template->rander("aleelo_plugin\Views/expenses_emp/expense_payments/index", $view_data);

}
public function save()
{
    $id = $this->request->getPost("id");
    $expense_id = $this->request->getPost("expense_id");

    validate_numeric_value($id);
    validate_numeric_value($expense_id);

    // Make sure expense exists
    $expense_info = $this->Expenses_model->get_one($expense_id);
    // if (!$expense_info || !$expense_info->id) {
    //     echo json_encode([
    //         "success" => false,
    //         "message" => app_lang("expense_not_found")
    //     ]);
    //     return;
    // }

    $data = [
        "expense_id" => $expense_id,
        "payment_date" => $this->request->getPost("payment_date"),
        "note" => $this->request->getPost("note"),
        "payment_method" => $this->request->getPost("payment_method"),
        "deposit_to" => $this->request->getPost("deposit_to"),
        "created_by" => $this->login_user->id,
        "amount_paid"=>$this->request->getPost("invoice_payment_amount"),
    ];

    // Only allow setting amount if not already paid
  

    $save_id = $this->Expense_payments_model->ci_save($data, $id);

    if ($save_id) {
        // Update expense summary (paid, due, status)
        // $this->Expense_payments_model->update_payment_summary($expense_id);

        echo json_encode([
            "success" => true,
            "data" => "",
            "id" => $save_id,
            "message" => app_lang("record_saved")
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => app_lang("error_occurred")
        ]);
    }
}
    function get_invoice_payment_amount_suggestion($invoice_id)
    {
        validate_numeric_value($invoice_id);

        $invoice_total_summary = $this->Expense_payments_model->get_expense_total($invoice_id);
        if ($invoice_total_summary) {
            $invoice_total_summary->balance_due = $invoice_total_summary->balance_due ? to_decimal_format($invoice_total_summary->balance_due) : "";
            echo json_encode(array("success" => true, "invoice_total_summary" => $invoice_total_summary));
        } else {
            echo "f";
        }
    }
 public function modal_form()
{
    $id =$this->request->getPost('id');
    $expense_id=$this->request->getPost('expense_id');

    // Validate both $id and $expense_id
    validate_numeric_value($id);
    validate_numeric_value($expense_id);
    $view_data['categories_dropdown'] = $this->_get_categories_dropdown();
    $view_data['vendors_dropdown'] = $this->_get_vendors_dropdown();

    // Get expense info
    $expense_info = $this->Expense_payments_model->get_one($id);
    // if (!$expense_info || !$expense_info->id) {
    //     return json_encode([
    //         "success" => false,
    //         "message" => app_lang("expense_not_found")
    //     ]);
    // }
        $accounting_model = new Accounting_model();
        $accounts = $accounting_model->get_accounts();
        $accounts_dropdown = [];
        foreach ($accounts as $account) {
            $accounts_dropdown[$account['id']] = $account['name'];
        }
    $view_data['accounts_dropdown'] = $accounts_dropdown;
    $view_data["model_info"] = $this->Expense_payments_model->get_one($this->request->getPost('id'));
    $view_data['expenses_dropdown'] = $this->_get_expenses_dropdown();
    $view_data["model_info"] = $expense_info;
    $view_data["expense_id"] = $expense_id;
     $view_data['payment_methods_dropdown'] = $this->get_payment_method_dropdown();
$amount = "";


$hide_expense_dropdown = false;

if (!empty($expense_id)) {
    $balance_due = $this->Expense_payments_model->get_expense_total($expense_id)->balance_due ?? 0;
    $amount = to_decimal_format($balance_due);
    $hide_expense_dropdown = true; 
}

$view_data["amount"] = $amount;
$view_data["hide_expense_dropdown"] = $hide_expense_dropdown;



if (!empty($view_data['model_info']) && $view_data['model_info']->amount_paid) {
    $amount = to_decimal_format($view_data['model_info']->amount_paid);
} elseif ($expense_id) {
    $balance_due = $this->Expense_payments_model->get_expense_total($expense_id)->balance_due ?? 0;
    $amount = to_decimal_format($balance_due);
}
$view_data["amount"] = $amount;

 return $this->template->view("aleelo_plugin\Views/expenses_emp/expense_payments/modal_form", $view_data);

    // return $this->template->view("aleelo_plugin/Views/expenses_emp/expense_payments/modal_form", $view_data);
}

public function getExpensesByVendorDropdown(){
    $vendor_id = $this->request->getPost('vendor_id');
    $category_id = $this->request->getPost('category_id');

    $result = $this->getExpensesByVendorJs($category_id, $vendor_id);


    echo json_encode([
        "success" => true,
        "data" => $result
    ]);
}
    // Datatable list
     
    // Create or update a payment
  
function get_expense_info($id) {
    $expense = $this->Expenses_model->get_one($id);
    $category = $this->Expense_categories_model->get_one($expense->category_id);

    echo json_encode([
        "amount" => to_currency($expense->amount),
        "category" => $category->title
    ]);
}



    // Edit modal
    public function edit($id = 0)
    {
        $model_info = $this->Expense_payments_model->get_one($id);
        $view_data["model_info"] = $model_info;
        $view_data["accounts_dropdown"] = $this->_get_accounts_dropdown();
        return $this->template->view("expense_payments/modal_form", $view_data);
    }

    // Delete a payment
    public function delete($id = 0)
    {
        $info = $this->Expense_payments_model->get_one($id);

        if ($info && $info->id) {
            $this->Expense_payments_model->delete($id);
            $this->_update_expense_status($info->expense_id);
            echo json_encode(["success" => true, "message" => app_lang("record_deleted")]);
        } else {
            echo json_encode(["success" => false, "message" => app_lang("record_not_found")]);
        }
    }

    // Helpers
    public function datatable($expense_id = 0)
{
    validate_numeric_value($expense_id);

    $list_data = $this->Expense_payments_model->get_details(["expense_id" => $expense_id])->getResult();
    $result = [];

    foreach ($list_data as $data) {
        $result[] = $this->_make_row($data);
    }

    echo json_encode(["data" => $result]);
}

    private function _make_row($data)
{
    return [
        $data->id, // COLUMN 0: Waa muhiim!
        format_to_date($data->payment_date, true),
        $data->payment_method_title,
        $data->note,
        to_currency($data->amount_paid),
        modal_anchor(get_uri("expense_payments_emp/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_payment'), "data-post-id" => $data->id, "data-post-invoice_id" => $data->expense_id)).
        js_anchor("<i data-feather='x' class='icon-16'></i>", ["class" => "btn btn-danger btn-sm", "title" => app_lang("delete"), "data-id" => $data->id, "data-action-url" => get_uri("expense_payments_emp/delete/" . $data->id), "data-action" => "delete"])
    ];
}


    private function _update_expense_status($expense_id)
    {
        $total_paid = $this->Expense_payments_model->get_total_paid($expense_id);
        $expense = $this->Expenses_model->get_one($expense_id);

        if ($total_paid >= $expense->amount) {
            $paid=array("status" => "paid");
            $this->Expenses_model->ci_save($paid, $expense_id);
        } elseif ($total_paid > 0) {
            $partial=array("status" => "partially_paid");
            $this->Expenses_model->ci_save($partial, $expense_id);
        } else {
            $unpaid=array("status" => "unpaid");
            $this->Expenses_model->ci_save($unpaid, $expense_id);
        }
    }

    private function _get_accounts_dropdown()
    {
        $accounts = model("Accounting_model")->get_all_accounts(); 
        $dropdown = [];
        foreach ($accounts as $account) {
            $dropdown[$account->id] = $account->name;
        }
        return $dropdown;
    }
}
