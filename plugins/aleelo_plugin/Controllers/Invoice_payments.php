<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\Security_Controller_Plugin;

use App\Libraries\Paytm;
use App\Libraries\Stripe;
use App\Libraries\Paypal;

class Invoice_payments extends Security_Controller_Plugin {

    function __construct() {
        parent::__construct();
        $this->init_permission_checker("invoice");
    }

    /* load invoice list view */

    function index() {
        if(!$this->can_view_payment())
    {
        app_redirect("forbidden");
}

        if (            $invoice_permission = get_array_value($this->login_user->permissions, "payment")
        )
     {
    
            if ($this->login_user->is_admin || $invoice_permission === "all" || $invoice_permission === "own_company" || $invoice_permission === "read_only") {
                $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
                $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
                $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_expenses("payments");
                $view_data["conversion_rate"] = $this->get_conversion_rate_with_currency_symbol();
                $view_data['company'] = $this->_get_company();
                $view_data['can_edit_payment'] = $this->can_edit_payment();
                $view_data['can_add_payment'] = $this->can_add_payment();
    
                return $this->template->rander("aleelo_plugin\Views/invoices/payment_received", $view_data);
            } else {
                app_redirect("forbidden");
            }
        } else {
            if (!($this->can_client_access("invoice") && $this->can_client_access("payment", false))) {
                app_redirect("forbidden");
            }
    
            $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
            $view_data['client_id'] = $this->login_user->client_id;
            $view_data['page_type'] = "full";
            return $this->template->rander("aleelo_plugin\Views/clients/payments/index", $view_data);
        }
    }
    function supplier_payments($invoice_id ,$supplier_id=0) {
        if (!$this->can_view_invoice()) {
            app_redirect("forbidden");
        }

        if ($invoice_id) {
            validate_numeric_value($invoice_id);
            $view_data["invoice_id"] = $invoice_id;
            $view_data["supplier_id"] = $supplier_id;
            $view_data["can_edit_invoices"] = $this->can_edit_invoice();

            return $this->template->view("aleelo_plugin\Views/invoices/payments/supplier_payments", $view_data);
        } else {
            show_404();
        }
    }
    function get_payment_method_dropdown() {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }
        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->getResult();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . app_lang("payment_method") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return json_encode($payment_method_dropdown);
    }

    /* load payment modal */

    function payment_modal_form() {
        if (!$this->can_add_payment()  && !$this->can_edit_payment()) {
            app_redirect("forbidden");
        }
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "numeric"
        ));

        $view_data['model_info'] = $this->Invoice_payments_model->get_one($this->request->getPost('id'));

        $invoice_id = $this->request->getPost('invoice_id') ? $this->request->getPost('invoice_id') : $view_data['model_info']->invoice_id;

        if (!$invoice_id) {
            //prepare invoices dropdown
            $invoices = $this->Invoices_model->get_invoices_dropdown_list()->getResult();
            $invoices_dropdown = array();

            foreach ($invoices as $invoice) {
                $invoices_dropdown[$invoice->id] = $invoice->display_id;
            }

            $view_data['invoices_dropdown'] = array("" => "-") + $invoices_dropdown;
        }

        $amount = $view_data['model_info']->amount ? to_decimal_format($view_data['model_info']->amount) : "";
        if (!$view_data['model_info']->amount && $invoice_id) {
            $amount = to_decimal_format($this->Invoices_model->get_invoice_total_summary($invoice_id)->balance_due);
        }

        $view_data["amount"] = $amount;

        $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
        $view_data['invoice_id'] = $invoice_id;

        return $this->template->view('aleelo_plugin\Views/invoices/payment_modal_form', $view_data);
    }
  /* load payment modal */

  function supplier_payment_modal_form() {
    if (!$this->can_add_payment()  && !$this->can_edit_payment()) {
        app_redirect("forbidden");
    }
    $this->validate_submitted_data(array(
        "id" => "numeric",
        "invoice_id" => "numeric"
    ));

    $view_data['model_info'] = $this->Invoice_payments_model->get_one($this->request->getPost('id'));

    $invoice_id = $this->request->getPost('invoice_id') ? $this->request->getPost('invoice_id') : $view_data['model_info']->invoice_id;
    $supplier_id = $this->request->getPost('supplier_id'); // Read supplier_id from the dropdown

    if (!$invoice_id) {
        //prepare invoices dropdown
        $invoices = $this->Invoices_model->get_invoices_dropdown_list()->getResult();
        $invoices_dropdown = array();

        foreach ($invoices as $invoice) {
            $invoices_dropdown[$invoice->id] = $invoice->display_id;
        }

        $view_data['invoices_dropdown'] = array("" => "-") + $invoices_dropdown;
    }
    $view_data['suppliers_dropdown'] = array("" => "-") + $this->Supplier_model->get_dropdown_list(array("supplier_name"), "id");
    // $amount = $view_data['model_info']->amount ? to_decimal_format($view_data['model_info']->amount) : "";
    if (!$view_data['model_info']->amount && $invoice_id) {
        $amount = to_decimal_format($this->Invoices_model->get_invoice_total_summaryp($invoice_id, $supplier_id)->supplier_due);
    }

    $view_data["amount"] = $amount;

    $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_dropdown_list(array("title"), "id", array("online_payable" => 0, "deleted" => 0));
    $view_data['invoice_id'] = $invoice_id;

    return $this->template->view('aleelo_plugin\Views/invoices/supplier_payment_modal_form', $view_data);
}
    /* add or edit a payment */

    function save_payment() {
        // $this->access_only_allowed_members();

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "invoice_id" => "required|numeric",
            "invoice_payment_method_id" => "required|numeric",
            "invoice_payment_date" => "required",
            "invoice_payment_amount" => "required"
        ));

        $id = $this->request->getPost('id');
        $invoice_id = $this->request->getPost('invoice_id');
        $payment_method_id = $this->request->getPost('invoice_payment_method_id');
        $payment_method = $this->Payment_methods_model->get_one($payment_method_id);
        $account = $payment_method ? $payment_method->account_id : 0;
        $invoice_payment_data = array(
            "invoice_id" => $invoice_id,
            "payment_date" => $this->request->getPost('invoice_payment_date'),
            "note" => $this->request->getPost('invoice_payment_note'),
            "payment_method_id" => $payment_method_id,
            "account_id" => $account,
            "amount" => unformat_currency($this->request->getPost('invoice_payment_amount')),
            "created_at" => get_current_utc_time(),
            "created_by" => $this->login_user->id,
        );

        $invoice_payment_id = $this->Invoice_payments_model->ci_save($invoice_payment_data, $id);
        if ($invoice_payment_id) {

            //As receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
            $this->Invoices_model->update_invoice_status($invoice_id);

            if (!$id) {
                //show payment confirmation and payment received notification for new payments only
                log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
                log_notification("invoice_manual_payment_added", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), $this->login_user->id);
            }
            //get payment data
            $options = array("id" => $invoice_payment_id);
            $item_info = $this->Invoice_payments_model->get_details($options)->getRow();
            echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_payment_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }
  /* add or edit a payment */

  function save_payment_supplier() {
    // $this->access_only_allowed_members();

    $this->validate_submitted_data(array(
        "id" => "numeric",
        "invoice_id" => "required|numeric",
        "invoice_payment_method_id" => "required|numeric",
        "invoice_payment_date" => "required",
        "invoice_payment_amount" => "required"
    ));

    $id = $this->request->getPost('id');
    $invoice_id = $this->request->getPost('invoice_id');
    $payment_method_id = $this->request->getPost('invoice_payment_method_id');
    $payment_method = $this->Payment_methods_model->get_one($payment_method_id);
    $account = $payment_method ? $payment_method->account_id : 0;
    $invoice_payment_data = array(
        "invoice_id" => $invoice_id,
        "payment_date" => $this->request->getPost('invoice_payment_date'),
        "note" => $this->request->getPost('invoice_payment_note'),
        "payment_method_id" => $payment_method_id,
        "amount" => unformat_currency($this->request->getPost('invoice_payment_amount')),
        "created_at" => get_current_utc_time(),
        "created_by" => $this->login_user->id,
        "supplier_id" => $this->request->getPost('supplier_id'),
        "account_id" => $account,
        "supplier"=>1,
    );

    $invoice_payment_id = $this->Invoice_payments_model->ci_save($invoice_payment_data, $id);
    if ($invoice_payment_id) {

        //As receiving payment for the invoice, we'll remove the 'draft' status from the invoice 
        $this->Invoices_model->update_invoice_status($invoice_id);

        if (!$id) {
            //show payment confirmation and payment received notification for new payments only
            log_notification("invoice_payment_confirmation", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), "0");
            log_notification("invoice_manual_payment_added", array("invoice_payment_id" => $invoice_payment_id, "invoice_id" => $invoice_id), $this->login_user->id);
        }
        //get payment data
        $options = array("id" => $invoice_payment_id);
        $item_info = $this->Invoice_payments_model->get_details($options)->getRow();
        echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'id' => $invoice_payment_id, 'message' => app_lang('record_saved')));
    } else {
        echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
    }
}
    /* delete or undo a payment */

    function delete_payment() {
        if (!$this->can_delete_payment()) {
            app_redirect("forbidden");
        }
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Invoice_payments_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Invoice_payments_model->get_details($options)->getRow();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Invoice_payments_model->delete($id)) {
                $item_info = $this->Invoice_payments_model->get_one($id);
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "invoice_total_view" => $this->_get_invoice_total_view($item_info->invoice_id), 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data($invoice_id = 0) {
        if (!$this->can_view_payment()) {
            app_redirect("forbidden");
        }

        validate_numeric_value($invoice_id);
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $payment_method_id = $this->request->getPost('payment_method_id');
        $ss= $this->request->getPost("can_view_all_invoice");
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "invoice_id" => $invoice_id,
            "payment_method_id" => $payment_method_id,
            "currency" => $this->request->getPost("currency"),
            "project_id" => $this->request->getPost("project_id"),
            "company_id" => $this->can_view_own_company_payment(),
            "can_view_all_invoice" =>$ss,
            "supplier"=>"2",
            "supplier_type" => "client",  // or: "no_supplier"

        );

        $list_data = $this->Invoice_payments_model->get_details($options)->getResult();
        
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        
        echo json_encode(array("data" => $result));
    }
    function payment_supplier_data($invoice_id = 0 ,$supplier_id = 0) {
        if (!$this->can_view_payment()) {
            app_redirect("forbidden");
        }

        validate_numeric_value($invoice_id);
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $payment_method_id = $this->request->getPost('payment_method_id');
        $ss= $this->request->getPost("can_view_all_invoice");

        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "invoice_id" => $invoice_id,
            "payment_method_id" => $payment_method_id,
            "currency" => $this->request->getPost("currency"),
            "project_id" => $this->request->getPost("project_id"),
            "company_id" => $this->can_view_own_company_payment(),
            "can_view_all_invoice" =>$ss,
"supplier_type" => "supplier",
"supplier_id" => 13,        );

        $list_data = $this->Invoice_payments_model->get_details($options)->getResult();
        
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_supplier_payment_row($data);
        }
        
        echo json_encode(array("data" => $result));
    }
    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_client($client_id = 0) {
        if (!$this->can_view_invoices($client_id)) {
            app_redirect("forbidden");
        }

        validate_numeric_value($client_id);
        $options = array("client_id" => $client_id);
        $list_data = $this->Invoice_payments_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_project($project_id = 0) {
        validate_numeric_value($project_id);
        $options = array("project_id" => $project_id);

        $list_data = $this->Invoice_payments_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    /* prepare a row of invoice payment list table */

    private function _make_payment_row($data) {
        $invoice_url = "";
        if (!$this->can_view_invoices($data->client_id)) {
            app_redirect("forbidden");
        }

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), $data->display_id);
        } else {
            $invoice_url = anchor(get_uri(uri: "invoices/preview/" . $data->invoice_id), $data->display_id);
        }
        $edit= '';
        $delete= '';
        if ($this->can_edit_payment()) {
            $edit= modal_anchor(get_uri("invoice_payments/payment_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_payment'), "data-post-id" => $data->id, "data-post-invoice_id" => $data->invoice_id,));
        }
        if ($this->can_delete_payment()) {
        $delete= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoice_payments/delete_payment"), "data-action" => "delete"));
        }
        return array(
            $invoice_url,
            $data->payment_date,
            format_to_date($data->payment_date, false),
            $data->payment_method_title,
            $data->note,
            to_currency($data->amount, $data->currency_symbol),
            $edit . " " . $delete,
            );
    }
    private function _make_supplier_payment_row($data) {
        $invoice_url = "";
        // if (!$this->can_view_invoices($data->client_id)) {
        //     app_redirect("forbidden");
        // }

        if ($this->login_user->user_type == "staff") {
            $invoice_url = anchor(get_uri("invoices/view/" . $data->invoice_id), $data->display_id);
        } else {
            $invoice_url = anchor(get_uri("invoices/preview/" . $data->invoice_id), $data->display_id);
        }
        $edit= '';
        $delete= '';
        if ($this->can_edit_payment()) {
            $edit= modal_anchor(get_uri(uri: "invoice_payments/supplier_payment_modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_payment'), "data-post-id" => $data->id, "data-post-invoice_id" => $data->invoice_id,));
        }
        if ($this->can_delete_payment()) {
        $delete= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("invoice_payments/delete_payment"), "data-action" => "delete"));
        }
        return array(
            
            $data->payment_date,
            format_to_date($data->payment_date, false),
            format_to_date($data->payment_date, false),
            $data->supplier_name,
            $data->payment_method_title,
            $data->note,
            to_currency($data->amount, $data->currency_symbol),
            $edit . " " . $delete,
            );
    }

    /* invoice total section */

    private function _get_invoice_total_view($invoice_id = 0) {
        $view_data["invoice_total_summary"] = $this->Invoices_model->get_invoice_total_summary($invoice_id);
        $view_data["invoice_id"] = $invoice_id;
        $can_edit_invoices = false;
        if ($this->can_edit_invoices() && $this->is_invoice_editable($invoice_id)) {
            $can_edit_invoices = true;
        }
        $view_data["can_edit_invoices"] = $can_edit_invoices;
        return $this->template->view('aleelo_plugin\Views/invoices/invoice_total_section', $view_data);
    }

    //load the expenses yearly chart view
    function yearly_chart() {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        return $this->template->view("aleelo_plugin\Views/invoices/yearly_payments_chart", $view_data);
    }

    function yearly_chart_data() {

        $months = array("january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");

        $year = $this->request->getPost("year");
        if ($year) {
            $currency = $this->request->getPost("currency");
            $payments = $this->Invoice_payments_model->get_yearly_payments_chart($year, $currency);
            $values = array();
            foreach ($payments as $value) {
                $converted_rate = get_converted_amount($value->currency, $value->total);
                $values[$value->month - 1] = isset($values[$value->month - 1]) ? ($values[$value->month - 1] + $converted_rate) : $converted_rate; //in array the month january(1) = index(0)
            }

            foreach ($months as $key => $month) {
                $value = get_array_value($values, $key);
                $short_months[] = app_lang("short_" . $month);
                $data[] = $value ? $value : 0;
            }

            echo json_encode(array("months" => $short_months, "data" => $data, "currency_symbol" => $currency));
        }
    }

    function get_paytm_checksum_hash() {
        $paytm = new Paytm();
        $payment_data = $paytm->get_paytm_checksum_hash($this->request->getPost("input_data"), $this->request->getPost("verification_data"));

        if ($payment_data) {
            echo json_encode(array("success" => true, "checksum_hash" => get_array_value($payment_data, "checksum_hash"), "payment_verification_code" => get_array_value($payment_data, "payment_verification_code")));
        } else {
            echo json_encode(array("success" => false, "message" => app_lang("paytm_checksum_hash_error_message")));
        }
    }

    function get_stripe_checkout_session() {
        $this->access_only_clients();
        $stripe = new Stripe();
        try {
            $session = $stripe->get_stripe_checkout_session($this->request->getPost("input_data"), $this->login_user->id);
            if ($session->id) {
                echo json_encode(array("success" => true, "checkout_url" => $session->url));
            } else {
                echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
            }
        } catch (\Exception $ex) {
            echo json_encode(array("success" => false, "message" => $ex->getMessage()));
        }
    }

    function get_paypal_checkout_url() {
        $this->access_only_clients();
        $paypal = new Paypal();
        try {
            $checkout_url = $paypal->get_paypal_checkout_url($this->request->getPost("input_data"), $this->login_user->id);
            if ($checkout_url) {
                echo json_encode(array("success" => true, "checkout_url" => $checkout_url));
            } else {
                echo json_encode(array('success' => false, 'message' => app_lang('error_occurred')));
            }
        } catch (\Exception $ex) {
            echo json_encode(array("success" => false, "message" => $ex->getMessage()));
        }
    }

    function payments_summary() {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        $view_data['can_access_clients'] = $this->can_access_clients();
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown(false);
        $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
        return $this->template->rander("aleelo_plugin\Views/invoices/reports/yearly_payments_summary", $view_data);
    }

    function yearly_payment_summary_list_data() {
        if (!$this->can_view_invoices()) {
            app_redirect("forbidden");
        }

        //get the month name
        $month_array = array(" ", "january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december");

        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->request->getPost("currency"),
            "payment_method_id" => $this->request->getPost('payment_method_id')
        );

        $list_data = $this->Invoice_payments_model->get_yearly_summary_details($options)->getResult();

        $default_currency_symbol = get_setting("currency_symbol");

        $result = array();
        foreach ($list_data as $data) {
            $currency_symbol = $data->currency_symbol ? $data->currency_symbol : $default_currency_symbol;
            $month = get_array_value($month_array, $data->month);

            $result[] = array(
                app_lang($month),
                $data->payment_count,
                to_currency($data->amount, $currency_symbol)
            );
        }

        echo json_encode(array("data" => $result));
    }

    function clients_payment_summary() {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown(false);
        $view_data['payment_method_dropdown'] = $this->get_payment_method_dropdown();
        return $this->template->view("aleelo_plugin\Views/invoices/reports/clients_payment_summary", $view_data);
    }

    function clients_payment_summary_list_data() {
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $options = array(
            "start_date" => $start_date,
            "end_date" => $end_date,
            "currency" => $this->request->getPost("currency"),
            "payment_method_id" => $this->request->getPost('payment_method_id')
        );

        $list_data = $this->Invoice_payments_model->get_clients_summary_details($options)->getResult();

        $default_currency_symbol = get_setting("currency_symbol");

        $result = array();
        foreach ($list_data as $data) {
            $currency_symbol = $data->currency_symbol ? $data->currency_symbol : $default_currency_symbol;

            $result[] = array(
                anchor(get_uri("clients/view/" . $data->client_id), $data->client_name),
                $data->payment_count,
                to_currency($data->amount, $currency_symbol)
            );
        }

        echo json_encode(array("data" => $result));
    }

    function get_invoice_payment_amount_suggestion($invoice_id, $supplier_id ) {
        validate_numeric_value($invoice_id);
        validate_numeric_value($supplier_id);
        log_message('debug', 'Received Invoice ID: ' . $invoice_id);
        log_message('debug', 'Received Supplier ID: ' . $supplier_id);
    
        $invoice_total_summary = $this->Invoices_model->get_invoice_total_summaryp($invoice_id, $supplier_id);
        if ($invoice_total_summary) {
            $invoice_total_summary->supplier_due = $invoice_total_summary->supplier_due ? to_decimal_format($invoice_total_summary->supplier_due) : "";
            
            echo json_encode(array("success" => true, "invoice_total_summary" => $invoice_total_summary));
        } else {
            echo json_encode(array("success" => false));
        }
    }

    /* list of invoice payments, prepared for datatable  */

    function payment_list_data_of_order($order_id, $client_id = 0) {
        if (!$this->can_view_invoices($client_id)) {
            app_redirect("forbidden");
        }

        validate_numeric_value($order_id);
        validate_numeric_value($client_id);

        $options = array("order_id" => $order_id,);
        $list_data = $this->Invoice_payments_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_payment_row($data);
        }
        echo json_encode(array("data" => $result));
    }
}

/* End of file payments.php */
/* Location: ./app/controllers/payments.php */