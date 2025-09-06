<?php

namespace aleelo_plugin\Controllers;

class Expense_categories extends Security_Controller_Plugin
{

    function __construct()
    {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    //load expense categories list view
    function index()
    {
        return $this->template->rander("aleelo_plugin\Views/expense_categories/index");
    }

    //load expense category add/edit modal form
    function modal_form()
    {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Expense_categories_model->get_one($this->request->getPost('id'));
        return $this->template->view('aleelo_plugin\Views/expense_categories/modal_form', $view_data);
    }

    //save expense category
    // function save() {

    //     $this->validate_submitted_data(array(
    //         "id" => "numeric",
    //         "title" => "required"
    //     ));

    //     $id = $this->request->getPost('id');
    //     $data = array(
    //         "title" => $this->request->getPost('title')
    //     );
    //     $save_id = $this->Expense_categories_model->ci_save($data, $id);
    //     if ($save_id) {
    //         echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
    //     } else {
    //         echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
    //     }
    // }


    function save()
    {
        $this->validate_submitted_data([
            "id"    => "numeric",
            "title" => "required"
        ]);

        $id = (int)$this->request->getPost('id');
        $data = ["title" => $this->request->getPost('title')];

        $save_id = $this->Expense_categories_model->ci_save($data, $id);

        if ($save_id) {
            // ensure ONE expense account for this category (type=13, detail=15)
            $title            = trim((string)$data['title']);
            $expenseAccountId = $this->_ensure_single_expense_account($save_id, $title, 14, 105);

            // ❌ no write to expense_categories.account_id anymore
            // $this->db->table(get_db_prefix().'expense_categories')
            //          ->where('id', $save_id)
            //          ->update(['account_id' => $expenseAccountId]);

            // choose a default bank/cash (or 0 if none); still no preferred field
            $paymentAccountId = 87;

            // upsert mapping in rise_acc_expense_category_mappings
            $this->_upsert_expense_category_mapping($save_id, $paymentAccountId, $expenseAccountId);

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





    private function _to_key_name(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/i', '_', $slug);
        return 'acc_' . trim($slug, '_');
    }

    /** ONE account per expense category (type=13, detail=15), scoped by expense_id */
    private function _ensure_single_expense_account(int $categoryId, string $title, int $typeId = 14, int $detailId = 105): int
    {
        $desiredName = trim($title);
        $tbl = $this->db->table(get_db_prefix() . 'acc_accounts');

        $scoped = $tbl->select('id,name')
            ->where('expense_id', $categoryId)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->get()->getRow();

        if ($scoped && isset($scoped->id)) {
            if ($scoped->name !== $desiredName) {
                $tbl->where('id', $scoped->id)->update([
                    'name' => $desiredName,
                    'key_name' => $this->_to_key_name($desiredName),
                    'active' => 1
                ]);
            } else {
                $tbl->where('id', $scoped->id)->update(['active' => 1]);
            }
            return (int)$scoped->id;
        }

        $legacy = $tbl->select('id')
            ->where('name', $desiredName)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->where('expense_id', null)
            ->get()->getRow();

        if ($legacy && isset($legacy->id)) {
            $tbl->where('id', $legacy->id)->update(['expense_id' => $categoryId, 'active' => 1]);
            return (int)$legacy->id;
        }

        $tbl->insert([
            'name' => $desiredName,
            'key_name' => $this->_to_key_name($desiredName),
            'account_type_id' => $typeId,
            'account_detail_type_id' => $detailId,
            'expense_id' => $categoryId,
            'active' => 1,
        ]);
        return (int)$this->db->insertID();
    }

    /** Upsert ONE row into rise_acc_expense_category_mappings (no preferred!) */
    private function _upsert_expense_category_mapping(int $categoryId, int $paymentAccountId, int $expenseAccountId): void
    {
        $t = $this->db->table(get_db_prefix() . 'acc_expense_category_mappings');
        $payload = [
            'category_id'    => $categoryId,
            'payment_account' => $paymentAccountId,
            'deposit_to'     => $expenseAccountId,
            // DO NOT set preferred_payment_method
        ];
        $exists = $t->where('category_id', $categoryId)->countAllResults();
        if ($exists) {
            $t->where('category_id', $categoryId)->update($payload);
        } else {
            $t->insert($payload);
        }
    }

    /** Pick a default bank/cash account for expenses; DO NOT set preferred */
    private function _get_default_payment_account(): int
    {
        // try the "Cash" payment method first
        $pm = $this->db->table(get_db_prefix() . 'payment_methods')
            ->select('id')->where('deleted', 0)->like('title', 'Cash', 'none')->get()->getRow();

        if ($pm) {
            $m = $this->db->table(get_db_prefix() . 'acc_payment_mode_mappings')
                ->select('expense_payment_account, payment_account')
                ->where('payment_mode_id', (int)$pm->id)->get()->getRow();
            if ($m) {
                $acct = (int)($m->expense_payment_account ?? 0);
                if ($acct <= 0) $acct = (int)($m->payment_account ?? 0);
                if ($acct > 0) return $acct;
            }
        }

        // fallback: any mapping with an account
        $m = $this->db->table(get_db_prefix() . 'acc_payment_mode_mappings')
            ->select('expense_payment_account, payment_account')
            ->orderBy('payment_mode_id', 'asc')->get()->getRow();
        if ($m) {
            $acct = (int)($m->expense_payment_account ?? 0);
            if ($acct <= 0) $acct = (int)($m->payment_account ?? 0);
            if ($acct > 0) return $acct;
        }
        return 0; // none found; UI can update later
    }




    //delete/undo an expense category
    // function delete()
    // {
    //     $this->validate_submitted_data(array(
    //         "id" => "required|numeric"
    //     ));

    //     $id = $this->request->getPost('id');
    //     if ($this->request->getPost('undo')) {
    //         if ($this->Expense_categories_model->delete($id, true)) {
    //             echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
    //         } else {
    //             echo json_encode(array("success" => false, app_lang('error_occurred')));
    //         }
    //     } else {
    //         if ($this->Expense_categories_model->delete($id)) {
    //             echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
    //         } else {
    //             echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
    //         }
    //     }
    // }

    function delete()
    {
        $this->validate_submitted_data([
            "id" => "required|numeric"
        ]);

        $id = (int)$this->request->getPost('id');

        // Undo path (unchanged)
        if ($this->request->getPost('undo')) {
            if ($this->Expense_categories_model->delete($id, true)) {
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

        // RULE: if any owned account has transactions → NO deletion
        if ($this->_expense_category_has_history($id)) {
            echo json_encode([
                "success" => false,
                "message" => app_lang('cannot_delete_transaction_already_exists')
            ]);
            return;
        }

        // No history → delete category, then cleanup mappings + accounts
        if ($this->Expense_categories_model->delete($id)) {
            $this->_cleanup_expense_category_accounts_and_mappings($id);

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


    //get data for expenses category list
    function list_data()
    {
        $list_data = $this->Expense_categories_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Expense_categories_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    //prepare an expense category list row
    private function _make_row($data)
    {
        return array(
            $data->title,
            modal_anchor(get_uri("expense_categories/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_expenses_category'), "data-post-id" => $data->id))
                . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_expenses_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("expense_categories/delete"), "data-action" => "delete"))
        );
    }

    /**
     * True if ANY account owned by this expense category has history.
     * Checks acc_account_history.account ONLY (no split).
     */
    private function _expense_category_has_history(int $categoryId): bool
    {
        $prefix = get_db_prefix();

        // accounts owned by this category
        $accRows = $this->db->table($prefix . 'acc_accounts')
            ->select('id')
            ->where('expense_id', $categoryId)
            ->get()->getResultArray();

        if (!$accRows) {
            // no owned accounts => nothing to block on
            return false;
        }

        $ids = array_map(static fn($r) => (int)$r['id'], $accRows);

        // only check 'account' column, as requested
        $hist = $this->db->table($prefix . 'acc_account_history')
            ->whereIn('account', $ids)
            ->countAllResults();

        return $hist > 0;
    }

    /** Remove mappings + delete accounts owned by this category (safe ONLY after history check). */
    private function _cleanup_expense_category_accounts_and_mappings(int $categoryId): void
    {
        $prefix = get_db_prefix();

        // delete mapping rows for this category
        $this->db->table($prefix . 'acc_expense_category_mappings')
            ->where('category_id', $categoryId)
            ->delete();

        // delete accounts owned by this category
        if (!isset($this->Accounting_model)) {
            // ensure model is loaded
            $this->Items_model = model('Accounting_model'); // CI4; use $this->load->model('Accounting_model') for CI3
        }

        $accRows = $this->db->table($prefix . 'acc_accounts')
            ->select('id')
            ->where('expense_id', $categoryId)
            ->get()->getResultArray();

        foreach ($accRows as $r) {
            $this->Items_model->delete_account((int)$r['id']); // will succeed because we pre-checked no history
        }
    }
}

/* End of file expense_categories.php */
/* Location: ./app/controllers/expense_categories.php */