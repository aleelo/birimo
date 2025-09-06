<?php

namespace aleelo_plugin\Controllers;
use aleelo_plugin\Controllers\Security_Controller_Plugin;
use Accounting\Models\Accounting_model;

class Payment_methods extends Security_Controller_Plugin
{

    function __construct()
    {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    //load payment methods list
    function index()
    {
        return $this->template->rander("aleelo_plugin\Views/payment_methods/index");
    }

    //load payment method add/edit form
    function modal_form()
    {

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Payment_methods_model->get_one_with_settings($this->request->getPost('id'));

        //get seetings associtated with this payment type
        $view_data['settings'] = $this->Payment_methods_model->get_settings($view_data['model_info']->type);

        return $this->template->view('aleelo_plugin\Views/payment_methods/modal_form', $view_data);
    }

    //save a payment method
    // function save()
    // {

    //     $this->validate_submitted_data(array(
    //         "id" => "numeric"
    //     ));

    //     $available_on_invoice = $this->request->getPost('available_on_invoice');
    //     if ($available_on_invoice) {
    //         $available_on_invoice = 1;
    //     } else {
    //         $available_on_invoice = "";
    //     }

    //     $id = $this->request->getPost('id');
    //     $data = array(
    //         "title" => $this->request->getPost('title'),
    //         "description" => $this->request->getPost('description'),
    //         "available_on_invoice" => $available_on_invoice,
    //         "minimum_payment_amount" => unformat_currency($this->request->getPost('minimum_payment_amount'))
    //     );

    //     //get seetings associtated with this payment type
    //     $model_info = $this->Payment_methods_model->get_one($id);

    //     $settings = $this->Payment_methods_model->get_settings($model_info->type);
    //     $settings_data = array();
    //     foreach ($settings as $setting) {
    //         $field_type = get_array_value($setting, "type");
    //         $settings_name = get_array_value($setting, "name");
    //         $value = $this->request->getPost($settings_name);

    //         if ($field_type == "boolean" && $value != "1") {
    //             $value = "0";
    //         }

    //         if ($field_type != "readonly") {
    //             $settings_data[$settings_name] = $value;
    //         }
    //     }

    //     $data["settings"] = serialize($settings_data);


    //     $save_id = $this->Payment_methods_model->ci_save($data, $id);
    //     if ($save_id) {
    //         echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
    //     } else {
    //         echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
    //     }
    // }

    // === your existing save() for payment methods ===============================

    function save()
    {
        $this->validate_submitted_data(["id" => "numeric"]);

        $available_on_invoice = $this->request->getPost('available_on_invoice') ? 1 : "";
        $id = (int)$this->request->getPost('id');

        $data = [
            "title"                  => $this->request->getPost('title'),
            "description"            => $this->request->getPost('description'),
            "available_on_invoice"   => $available_on_invoice,
            "minimum_payment_amount" => unformat_currency($this->request->getPost('minimum_payment_amount'))
        ];

        // settings...
        $model_info    = $this->Payment_methods_model->get_one($id);
        $settings      = $this->Payment_methods_model->get_settings($model_info->type);
        $settings_data = [];
        foreach ($settings as $setting) {
            $field_type   = get_array_value($setting, "type");
            $settings_name = get_array_value($setting, "name");
            $value        = $this->request->getPost($settings_name);
            if ($field_type == "boolean" && $value != "1") $value = "0";
            if ($field_type != "readonly") $settings_data[$settings_name] = $value;
        }
        $data["settings"] = serialize($settings_data);

        $save_id = $this->Payment_methods_model->ci_save($data, $id);

        if ($save_id) {

            // --- NEW: ensure 2 accounts per payment method (type 13, detail 15) ---
            $title = trim((string)$data['title']);

            $TYPE_ID   = 3;  // as requested
            $DETAIL_ID = 15;  // as requested

            $receiptName = $title . ' - receipts'; // clearing
            $bankName    = $title . '';     // where money lands

            $receiptAccId = $this->_ensure_payment_account($save_id, $receiptName, $TYPE_ID, $DETAIL_ID);
            $bankAccId    = $this->_ensure_payment_account($save_id, $bankName,    $TYPE_ID, $DETAIL_ID);

            // Map both sales & expense sides:
            $this->_upsert_payment_mode_mapping([
                'payment_mode_id'         => $save_id,
                'payment_account'         => 1, // sales clearing
                'deposit_to'              => $bankAccId,    // bank/cash
                'expense_payment_account' => $bankAccId,    // pay expenses from bank
                'expense_deposit_to'      => 87, // expense clearing
            ]);
            // --- /NEW -------------------------------------------------------------

            echo json_encode([
                "success" => true,
                "data"    => $this->_row_data($save_id),
                "id"      => $save_id,
                "message" => app_lang('record_saved')
            ]);
        } else {
            echo json_encode(["success" => false, 'message' => app_lang('error_occurred')]);
        }
    }


    // === helpers (inside your Payment_methods controller) =======================

    private function _to_key_name(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/i', '_', $slug);
        $slug = trim($slug, '_');
        return 'acc_' . $slug;
    }

    /**
     * Ensure/attach an account for a payment method.
     * Scopes by payment_id + (type, detail). Renames if title changes.
     */
    private function _ensure_payment_account(
        int $paymentModeId,
        string $desiredName,
        int $typeId = 3,
        int $detailId = 15
    ): int {
        $tbl = $this->db->table(get_db_prefix() . 'acc_accounts');

        // 1) Prefer an account already scoped to this payment
        $scoped = $tbl->select('id,name')
            ->where('payment_id', $paymentModeId)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->get()->getRow();

        if ($scoped && isset($scoped->id)) {
            if ($scoped->name !== $desiredName) {
                $tbl->where('id', $scoped->id)->update([
                    'name'     => $desiredName,
                    'key_name' => $this->_to_key_name($desiredName),
                    'active'   => 1
                ]);
            } else {
                $tbl->where('id', $scoped->id)->update(['active' => 1]);
            }
            return (int)$scoped->id;
        }

        // 2) Adopt a legacy row (same name/type/detail) by binding payment_id
        $legacy = $tbl->select('id,payment_id')
            ->where('name', $desiredName)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->get()->getRow();

        if ($legacy && isset($legacy->id)) {
            $tbl->where('id', $legacy->id)->update([
                'payment_id' => $paymentModeId,
                'active'     => 1
            ]);
            return (int)$legacy->id;
        }

        // 3) Create fresh
        $tbl->insert([
            'name'                   => $desiredName,
            'key_name'               => $this->_to_key_name($desiredName),
            'account_type_id'        => $typeId,
            'account_detail_type_id' => $detailId,
            'payment_id'             => $paymentModeId,
            'active'                 => 1,
        ]);

        return (int)$this->db->insertID();
    }

    /** Upsert mapping row in rise_acc_payment_mode_mappings */
    private function _upsert_payment_mode_mapping(array $m): void
    {
        $t = $this->db->table(get_db_prefix() . 'acc_payment_mode_mappings');

        $exists = $t->where('payment_mode_id', (int)$m['payment_mode_id'])->countAllResults();
        if ($exists) {
            $t->where('payment_mode_id', (int)$m['payment_mode_id'])->update([
                'payment_account'         => (int)$m['payment_account'],
                'deposit_to'              => (int)$m['deposit_to'],
                'expense_payment_account' => (int)$m['expense_payment_account'],
                'expense_deposit_to'      => (int)$m['expense_deposit_to'],
            ]);
        } else {
            $t->insert([
                'payment_mode_id'         => (int)$m['payment_mode_id'],
                'payment_account'         => (int)$m['payment_account'],
                'deposit_to'              => (int)$m['deposit_to'],
                'expense_payment_account' => (int)$m['expense_payment_account'],
                'expense_deposit_to'      => (int)$m['expense_deposit_to'],
            ]);
        }
    }


    //delete/undo a payment method
    // function delete()
    // {

    //     $this->validate_submitted_data(array(
    //         "id" => "numeric"
    //     ));

    //     $id = $this->request->getPost('id');
    //     if ($this->request->getPost('undo')) {
    //         if ($this->Payment_methods_model->delete($id, true)) {
    //             echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
    //         } else {
    //             echo json_encode(array("success" => false, app_lang('error_occurred')));
    //         }
    //     } else {
    //         if ($this->Payment_methods_model->delete($id)) {
    //             echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
    //         } else {
    //             echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
    //         }
    //     }
    // }

    // --- Controller action (replace your current delete()) ---

    // delete/undo a payment method (hard-block if any history exists)
    function delete()
    {
        $this->validate_submitted_data(["id" => "numeric"]);
        $id = (int)$this->request->getPost('id');

        // Undo path stays as is
        if ($this->request->getPost('undo')) {
            if ($this->Payment_methods_model->delete($id, true)) {
                echo json_encode([
                    "success" => true,
                    "data"    => $this->_row_data($id),
                    "message" => app_lang('record_undone')
                ]);
            } else {
                echo json_encode(["success" => false, app_lang('error_occurred')]);
            }
            return;
        }

        // RULE: if any transaction exists → NO deletion
        if ($this->_payment_method_has_history($id)) {
            echo json_encode([
                "success" => false,
                "message" => app_lang('cannot_delete_transaction_already_exists')
            ]);
            return;
        }

        // No history → delete method, then cleanup mappings + accounts
        if ($this->Payment_methods_model->delete($id)) {
            $this->_cleanup_payment_method_accounts_and_mappings($id);

            echo json_encode([
                "success" => true,
                "message" => app_lang('record_deleted')
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => app_lang('record_cannot_be_deleted')
            ]);
        }
    }


    //prepare payment method list data for datatable.
    function list_data()
    {
        $list_data = $this->Payment_methods_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get a payment method list row
    private function _row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Payment_methods_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    //prepare payment method list row
    private function _make_row($data)
    {
        $title = "<div class='item-row' data-id='$data->id'><div class='float-start move-icon'><i data-feather='menu' class='icon-16'></i></div><div class='float-start'> $data->title</div></div>";
        $options = modal_anchor(get_uri("payment_methods/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_payment_method'), "data-post-id" => $data->id));

        if (!$data->online_payable && $data->type !== "client_wallet") {
            $options .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_payment_method'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("payment_methods/delete"), "data-action" => "delete"));
        }

        return array(
            $data->sort,
            $title,
            $data->description,
            $data->online_payable ? ($data->available_on_invoice ? app_lang("yes") : app_lang("no")) : "-",
            $data->minimum_payment_amount ? to_decimal_format($data->minimum_payment_amount) : "-",
            $options
        );
    }

    //update the sort value for payment method
    function update_payment_method_sort_values($id = 0)
    {
        $sort_values = $this->request->getPost("sort_values");
        if ($sort_values) {
            //extract the values from the comma separated string
            $sort_array = explode(",", $sort_values);

            //update the value in db
            foreach ($sort_array as $value) {
                $sort_item = explode("-", $value); //extract id and sort value

                $id = get_array_value($sort_item, 0);
                $sort = get_array_value($sort_item, 1);

                $data = array("sort" => $sort);
                $this->Payment_methods_model->ci_save($data, $id);
            }
        }
    }

    // --- Helpers (put inside your Payment methods controller) ---

    /**
     * Return true if ANY account tied to this payment method has history.
     * Checks acc_account_history.account ONLY (no split).
     */
   private function _payment_method_has_history(int $paymentMethodId): bool
{
    $prefix = get_db_prefix();

    // ONLY accounts directly owned by this payment method
    $accRows = $this->db->table($prefix . 'acc_accounts')
        ->select('id')
        ->where('payment_id', $paymentMethodId)
        ->get()->getResultArray();

    if (!$accRows) {
        // No owned accounts => nothing to block on
        return false;
    }

    $ids = array_map(static fn($r) => (int)$r['id'], $accRows);

    // Check ONLY the 'account' column (not split), per your rule
    $hist = $this->db->table($prefix . 'acc_account_history')
        ->whereIn('account', $ids)
        ->countAllResults();

    return $hist > 0;
}


    /** Remove mappings + delete accounts tied via payment_id (safe ONLY after history check). */
    private function _cleanup_payment_method_accounts_and_mappings(int $paymentMethodId): void
    {
        $prefix = get_db_prefix();

        // 1) delete mappings
        $this->db->table($prefix . 'acc_payment_mode_mappings')
            ->where('payment_mode_id', $paymentMethodId)
            ->delete();

        // 2) delete accounts linked by payment_id (no history exists at this point)
        if (!isset($this->Accounting_model)) {
            $this->Accounting_model = model('Accounting_model'); // CI4; use load->model for CI3
        }

        $accRows = $this->db->table($prefix . 'acc_accounts')
            ->select('id')
            ->where('payment_id', $paymentMethodId)
            ->get()->getResultArray();

        foreach ($accRows as $r) {
            $this->Accounting_model->delete_account((int)$r['id']); // will return true (no history)
        }
    }
}

/* End of file payment_methods.php */
/* Location: ./app/controllers/payment_methods.php */