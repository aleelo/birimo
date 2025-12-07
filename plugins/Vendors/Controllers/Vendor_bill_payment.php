<?php

namespace Vendors\Controllers;

use App\Libraries\Paytm;
use App\Libraries\Stripe;
use App\Libraries\Paypal;

class Vendor_bill_payment extends Security_Controller_Plugin_vendor
{

    private $Client_wallet_model;

    function __construct()
    {
        parent::__construct();

        $this->Client_wallet_model = model("App\Models\Client_wallet_model");

        // Gate 1: Must have CRM permission
        if (!$this->can_manage_vendors()) {
            app_redirect("forbidden");
        }

        // Gate 2: (only after CRM is OK) must also have items permission
        if (!$this->can_hide_vendor_bill_payments()) {
            app_redirect("forbidden");
        }
    }

    /* load invoice list view */
    function index()
    {
        // Get payment methods dropdown
        if (isset($this->Payment_methods_model) && $this->Payment_methods_model !== null) {
            $view_data['payment_method_dropdown'] = $this->Payment_methods_model->get_payment_methods_dropdown();
        } else {
            $view_data['payment_method_dropdown'] = json_encode([["id" => "", "text" => "- " . app_lang("payment_method") . " -"]]);
        }
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        $view_data["projects_dropdown"] = $this->_get_projects_dropdown_for_income_and_expenses("payments");
        $view_data["conversion_rate"] = $this->get_conversion_rate_with_currency_symbol();

        // Branch functionality removed - not needed in vendors plugin
        // $hide_branch_dropdown = false;
        // $branch_id = $this->get_user_branch_access_view();
        // if (is_array($branch_id) || !$branch_id) {
        //     $hide_branch_dropdown = true;
        // } else {
        //     $hide_branch_dropdown = false;
        // }

        // Branch functionality removed - not needed in vendors plugin
        $view_data['hide_branch_dropdown'] = true;
        // $view_data['branch_dropdown'] = $this->_get_branch();
        $view_data['branch_dropdown'] = json_encode(array(array("id" => "", "text" => "-")));

        $view_data["can_add_new_vendor_bill_payment"] = $this->can_add_new_vendor_bill_payment();

        return $this->template->rander("Vendors\Views/vendor_bills/payment_received", $view_data);
    }



    /* load payment modal */
    public function payment_modal_form()
    {
        $this->access_only_team_members();

        $this->validate_submitted_data([
            "id"             => "numeric",
            "vendor_bill_id" => "numeric"
        ]);

        $id = $this->request->getPost('id');

        $view_data['model_info']   = $this->Vendor_bill_payments_model->get_one($id);
        $bill_id                   = $this->request->getPost('vendor_bill_id') ?: $view_data['model_info']->vendor_bill_id;
        $view_data['vendor_bill_id'] = $bill_id;

        // Build dropdown of ONLY unpaid/partially-paid bills
        if (!$bill_id) {
            $b = $this->db->prefixTable('vendor_bills');
            $p = $this->db->prefixTable('vendor_bill_payments');

            // Compute balance due per bill (total - sum(payments))
            $rows = $this->db->query("
            SELECT
                b.id,
                b.display_id,
                b.invoice_total,
                b.status,
                (b.invoice_total - COALESCE(
                    (SELECT SUM(p.amount)
                     FROM $p p
                     WHERE p.vendor_bill_id = b.id
                       AND p.deleted = 0), 0)
                ) AS due
            FROM $b b
            WHERE b.deleted = 0 AND (b.status IS NULL OR b.status <> 'cancelled')
            ORDER BY b.id DESC
        ")->getResult();

            $dd = ["" => "-"];
            foreach ($rows as $r) {
                // epsilon to avoid float noise
                if ((float)($r->due ?? 0) > 0.0001) {
                    $label = ($r->display_id ?: ("BILL-" . $r->id));
                    // optional: show remaining due in the label
                    // $label .= " (" . app_lang("due") . ": " . to_currency($r->due) . ")";
                    $dd[$r->id] = $label;
                }
            }
            $view_data['bills_dropdown'] = $dd;
        }

        // Prefill amount to bill’s due when creating
        $amount = $view_data['model_info']->amount ? to_decimal_format($view_data['model_info']->amount) : "";
        if (!$view_data['model_info']->amount && $bill_id) {
            $amount = to_decimal_format($this->Vendor_bill_payments_model->get_bill_balance_due($bill_id));
        }
        $view_data["amount"] = $amount;

        helper('cookie');
        $selected_payment_method = get_cookie("user_" . $this->login_user->id . "_payment_method");
        // Get payment methods dropdown
        if (isset($this->Payment_methods_model) && $this->Payment_methods_model !== null) {
            $view_data['payment_methods_dropdown'] = $this->Payment_methods_model->get_payment_methods_dropdown(true, $selected_payment_method);
        } else {
            $view_data['payment_methods_dropdown'] = json_encode([["id" => "", "text" => "- " . app_lang("payment_method") . " -"]]);
        }

        return $this->template->view('Vendors\Views/vendor_bills/payment_modal_form', $view_data);
    }


    /* add or edit a payment */
    public function save_payment()
    {
        $this->access_only_team_members();

        $id = (int) $this->request->getPost('id'); // 0=new, >0=edit

        $this->validate_submitted_data([
            "id"                        => "numeric",
            "vendor_bill_id"            => "required|numeric",
            "invoice_payment_method_id" => "required|numeric",
            "invoice_payment_date"      => "required",
            "invoice_payment_amount"    => "required"
        ]);

        $bill_id           = (int) $this->request->getPost('vendor_bill_id');
        $payment_method_id = (int) $this->request->getPost('invoice_payment_method_id');
        $amount            = (float) unformat_currency($this->request->getPost('invoice_payment_amount'));

        // --- Guard: amount must be > 0
        if ($amount <= 0) {
            echo json_encode(["success" => false, "message" => app_lang("invalid_value")]);
            return;
        }

        // --- Guard: don't exceed balance due
        // NOTE: When editing, add back THIS payment's current amount so user can keep/smaller it.
        $due = (float) $this->Vendor_bill_payments_model->get_bill_balance_due($bill_id);

        if ($id > 0) {
            $existing = $this->Vendor_bill_payments_model->get_one($id);
            if (!$existing || (int)($existing->vendor_bill_id ?? 0) !== $bill_id) {
                echo json_encode(["success" => false, "message" => app_lang("record_not_found")]);
                return;
            }
            $due += (float) ($existing->amount ?? 0);
        }

        $EPS = 0.00001; // float tolerance
        if ($amount > $due + $EPS) {
            echo json_encode([
                "success" => false,
                "message" => app_lang("payment_amount_exceeds_balance_due")
            ]);
            return;
        }

        // --- Build payload
        $data = [
            "vendor_bill_id"    => $bill_id,
            "payment_date"      => $this->request->getPost('invoice_payment_date'),
            "payment_method_id" => $payment_method_id,
            "note"              => $this->request->getPost('invoice_payment_note'),
            "amount"            => $amount,
            "created_at"        => get_current_utc_time(),
            "created_by"        => $this->login_user->id,
        ];

        // --- Save
        $save_id = $this->Vendor_bill_payments_model->save_payment_and_log_changes($data, $id);

        if ($save_id) {
            // Keep your existing status updater
            $this->_update_bill_status_from_payments($bill_id);

            echo json_encode([
                "success" => true,
                "id"      => $save_id,
                "message" => app_lang("record_saved")
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => app_lang("error_occurred")
            ]);
        }
    }


    /* delete or undo a payment */
    function delete_payment()
    {


        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Vendor_bill_payments_model->delete($id, true)) {
                $options = array("id" => $id);
                $item_info = $this->Invoice_payments_model->get_details($options)->getRow();
                echo json_encode(array("success" => true, "invoice_id" => $item_info->invoice_id, "data" => $this->_make_payment_row($item_info), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Vendor_bill_payments_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    private function _make_payment_row($data, $is_mobile = 0)
    {
        // Bill link (prefer display_id, fall back to BILL-#)
        $label = $data->display_id ? $data->display_id : ("BILL-" . $data->vendor_bill_id);
        $bill_url = anchor(get_uri("vendor_bills/edit/" . $data->vendor_bill_id), $label);

        $payment_date_raw = $data->payment_date;
        $payment_date     = format_to_date($data->payment_date, false);

        $title = "";
        if ($is_mobile) {
            $title = "<div>
            <span>{$data->payment_date}</span>
            <span class='float-end strong'>" . to_currency($data->amount, $data->currency_symbol) . "</span>
            <span class='float-end me-3'>{$data->payment_method_title}</span>
            <div class='text-wrap text-off mt5'>{$data->note}</div>
        </div>";
        }

        // Actions (edit / delete) – point to vendor_bill_payment endpoints

        $edit   = "";
        $delete = "";

        // Check permission before showing edit button
        if ($this->can_update_vendor_bill_payment()) {
            $edit = modal_anchor(
                get_uri("vendor_bill_payment/payment_modal_form"),
                "<i data-feather='edit' class='icon-16'></i>",
                [
                    "class" => "edit",
                    "title" => app_lang('edit_payment'),
                    "data-post-id" => $data->id,
                    "data-post-vendor_bill_id" => $data->vendor_bill_id,
                ]
            );
        }

        // Check permission before showing delete button
        if ($this->can_delete_vendor_bill_payment()) {
            $delete = js_anchor(
                "<i data-feather='x' class='icon-16'></i>",
                [
                    "class" => "delete",
                    "title" => app_lang('delete'),
                    "data-id" => $data->id,
                    "data-action-url" => get_uri("vendor_bill_payment/delete_payment"),
                    "data-action" => "delete-confirmation"
                ]
            );
        }

        return [
            $bill_url,                             // Bill #
            $payment_date_raw,                     // hidden sort
            $is_mobile ? $title : $payment_date,   // display date / title
            // $data->branch_name,                    // Branch
            $data->payment_method_title,           // Method
            $data->note,                           // Note
            to_currency($data->amount, $data->currency_symbol), // Amount
            '<div class="actions-inline">' . $edit . $delete . '</div>' // Options
        ];
    }


    /* list of invoice payments, prepared for datatable  */

    public function payment_list_data($vendor_bill_id = 0, $is_mobile = 0)
    {
        $payment_id = $this->request->getPost('id');

        validate_numeric_value($vendor_bill_id);
        validate_numeric_value($is_mobile);
        validate_numeric_value($payment_id);

        $start_date       = $this->request->getPost('start_date');
        $end_date         = $this->request->getPost('end_date');
        $payment_method_id = $this->request->getPost('payment_method_id');
        // Branch functionality removed - not needed in vendors plugin
        // $branch_id        = $this->request->getPost('branch_id');
        $project_id       = $this->request->getPost('project_id'); // optional

        $options = [
            "id"                 => $payment_id,
            "start_date"         => $start_date,
            "end_date"           => $end_date,
            "vendor_bill_id"     => $vendor_bill_id,
            "payment_method_id"  => $payment_method_id,
            // "branch_id"          => $branch_id, // Branch functionality removed
            "project_id"         => $project_id
        ];

        $list = $this->Vendor_bill_payments_model->get_details($options)->getResult();
        $rows = [];
        foreach ($list as $r) {
            $rows[] = $this->_make_payment_row($r, $is_mobile);
        }
        echo json_encode(["data" => $rows]);
    }



    //load the expenses yearly chart view
    function yearly_chart()
    {
        $view_data["currencies_dropdown"] = $this->_get_currencies_dropdown();
        return $this->template->view("Vendors\Views/vendor_bills/yearly_payments_chart", $view_data);
    }


    // private function _update_bill_status_from_payments(int $bill_id): void
    // {
    //     $bills    = $this->db->prefixTable('vendor_bills');
    //     $payments = $this->db->prefixTable('vendor_bill_payments');

    //     $bill = $this->db->table($bills)->where('id', $bill_id)->get()->getRow();
    //     if (!$bill) return;

    //     $total = (float) ($bill->invoice_total ?? 0);

    //     $paidRow = $this->db->table($payments)
    //         ->selectSum('amount', 'sum_paid')
    //         ->where('vendor_bill_id', $bill_id)
    //         ->where('deleted', 0)
    //         ->get()->getRow();

    //     $paid = (float) ($paidRow->sum_paid ?? 0);
    //     $eps  = 0.0001;

    //     // Decide new status
    //     $newStatus = $bill->status;
    //     if ($total > 0 && $paid >= $total - $eps) {
    //         $newStatus = 'paid';
    //     } elseif ($paid > $eps) {
    //         $newStatus = 'partially_paid';
    //     } else {
    //         // pick what fits your workflow best:
    //         $newStatus = 'unpaid'; // or 'draft'
    //     }

    //     if ($newStatus !== $bill->status) {
    //         // IMPORTANT: assign to a variable before calling ci_save()
    //         $payload = ["status" => $newStatus];
    //         $this->Vendor_bills_model->ci_save($payload, $bill_id);
    //     }
    // }

    private function _update_bill_status_from_payments(int $bill_id): void
    {
        $bills    = $this->db->prefixTable('vendor_bills');
        $payments = $this->db->prefixTable('vendor_bill_payments');

        $bill = $this->db->table($bills)->where('id', $bill_id)->get()->getRow();
        if (!$bill) return;

        $total = (float) ($bill->invoice_total ?? 0);

        $paidRow = $this->db->table($payments)
            ->selectSum('amount', 'sum_paid')
            ->where('vendor_bill_id', $bill_id)
            ->where('deleted', 0)
            ->get()->getRow();

        $paid = (float) ($paidRow->sum_paid ?? 0);
        $eps  = 0.0001;

        // ✅ Map to new ENUM statuses
        $newStatus = $bill->status;
        if ($total > 0 && $paid >= $total - $eps) {
            $newStatus = 'credited';  // fully paid
        } elseif ($paid > $eps) {
            $newStatus = 'partially_paid'; // partial
        } else {
            $newStatus = 'not_paid'; // no payment
        }

        if ($newStatus !== $bill->status) {
            $payload = ["status" => $newStatus];
            $this->Vendor_bills_model->ci_save($payload, $bill_id);
        }
    }



    public function get_bill_payment_amount_suggestion($vendor_bill_id = 0)
    {
        $this->access_only_team_members();

        $vendor_bill_id = (int)$vendor_bill_id;
        if ($vendor_bill_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid bill id']);
            return;
        }

        $sum = $this->Vendor_bills_model->get_bill_total_summary($vendor_bill_id);
        if (!$sum) {
            echo json_encode(['success' => false, 'message' => 'Bill not found']);
            return;
        }

        echo json_encode([
            'success'     => true,
            'balance_due' => to_decimal_format($sum->balance_due)
        ]);
    }
}
