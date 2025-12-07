<?php

namespace Vendors\Controllers;

use App\Libraries\Excel_import;

class Vendor_items extends Security_Controller_Plugin_vendor
{

    use Excel_import;

    private $categories_id_by_title = array();

    function __construct()
    {
        parent::__construct();



        // Gate 1: Must have CRM permission
        if (!$this->can_manage_vendors()) {
            app_redirect("forbidden");
        }

        // Gate 2: (only after CRM is OK) must also have items permission
        if (!$this->can_hide_vendor_items()) {
            app_redirect("forbidden");
        }
    }

    /**
     * Replaced the old validate_access_to_items() with the provided permission checks.
     * This function is now removed.
     *
     * The `can_hide_items()` permission is used as a proxy for basic view access,
     * since a user who can manage the visibility of items should also be able to see them.
     */

    // load items list view
    function index()
    {
        $this->access_only_team_members();
        // Check if the user has permission to view/hide items.
        // If not, they are redirected to the forbidden page.
        if (!$this->can_hide_vendor_items()) {
            app_redirect("forbidden");
        }

        // $view_data['categories_dropdown'] = $this->_get_categories_dropdown();

        $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("items", $this->login_user->is_admin, $this->login_user->user_type);
        $view_data["custom_field_filters"] = $this->Custom_fields_model->get_custom_field_filters("items", $this->login_user->is_admin, $this->login_user->user_type);

        // Pass the permission check result to the view
        $view_data['can_add_new_items'] = $this->can_add_new_vendor_item();

        return $this->template->rander("Vendors\Views/vendor_items/index", $view_data);
    }

    // get categories dropdown
    public function _get_categories_dropdown()
    {
        $categories = $this->Item_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->getResult();

        $categories_dropdown = array(array("id" => "", "text" => "- " . app_lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    /* load item modal */
    function modal_form()
    {
        $this->access_only_team_members();
        $id = $this->request->getPost('id');
        // Check if the user is creating a new item or updating an existing one.
        // if ($id) {
        //     // Check if the user has permission to update an item.
        //     if (!$this->can_update_items()) {
        //         app_redirect("forbidden");
        //     }
        // } else {
        //     // Check if the user has permission to add a new item.
        //     if (!$this->can_add_new_items()) {
        //         app_redirect("forbidden");
        //     }
        // }

        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['model_info'] = $this->Vendor_items_model->get_one($id);
        // $view_data['categories_dropdown'] = $this->Item_categories_model->get_dropdown_list(array("title"));

        $view_data["custom_fields"] = $this->Custom_fields_model->get_combined_details("items", $view_data['model_info']->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        return $this->template->view('Vendors\Views/vendor_items/modal_form', $view_data);
    }

    // /* add or edit an item */

    /* add or edit an item */
    function save()
    {
        $this->access_only_team_members();
        $id = $this->request->getPost('id');

        // if ($id) {
        //     if (!$this->can_update_items()) app_redirect("forbidden");
        // } else {
        //     if (!$this->can_add_new_items()) app_redirect("forbidden");
        // }

        $this->validate_submitted_data([
            "id" => "numeric",
            // "category_id" => "required",
        ]);

        $item_data = [
            "title" => $this->request->getPost('title'),
            "description" => $this->request->getPost('description'),
            "category_id" => $this->request->getPost('category_id'),
            "unit_type" => $this->request->getPost('unit_type'),
            "rate" => unformat_currency($this->request->getPost('item_rate')),
            "show_in_client_portal" => $this->request->getPost('show_in_client_portal') ? $this->request->getPost('show_in_client_portal') : "",
            "taxable" => ""
        ];

        // files (unchanged)
        $target_path = get_setting("timeline_file_path");
        $new_files = unserialize(move_files_from_temp_dir_to_permanent_dir($target_path, "item"));
        if ($id) {
            $item_info = $this->Vendor_items_model->get_one($id);
            $new_files = update_saved_files($target_path, $item_info->files, $new_files);
        }
        $item_data["files"] = serialize($new_files);

        $item_id = $this->Vendor_items_model->ci_save($item_data, $id);

        if ($item_id) {
            // ====== /NEW ======

            save_custom_fields("items", $item_id, $this->login_user->is_admin, $this->login_user->user_type);

            echo json_encode(["success" => true, "id" => $item_id, "data" => $this->_item_row_data($item_id), 'message' => app_lang('record_saved')]);
        } else {
            echo json_encode(["success" => false, 'message' => app_lang('error_occurred')]);
        }
    }

    // inside class Items extends Security_Controller { ... }

    private function _to_key_name(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9]+/i', '_', $slug);
        $slug = trim($slug, '_');
        return 'acc_' . $slug;
    }

    private function _ensure_chart_account(int $itemId, string $desiredName, int $typeId, int $detailId): int
    {
        $tbl = $this->db->table(get_db_prefix() . 'acc_accounts');

        // 1) Prefer exact match by item scope (prevents duplicates on edits)
        $scoped = $tbl->select('id, name')
            ->where('item_id', $itemId)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->get()->getRow();

        if ($scoped && isset($scoped->id)) {
            // rename if title changed → keep one row only
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

        // 2) If a legacy row exists by (name,type,detail) but without item_id,
        //    adopt it by attaching item_id (avoids creating another row).
        $legacy = $tbl->select('id, item_id')
            ->where('name', $desiredName)
            ->where('account_type_id', $typeId)
            ->where('account_detail_type_id', $detailId)
            ->get()->getRow();

        if ($legacy && isset($legacy->id)) {
            $tbl->where('id', $legacy->id)->update([
                'item_id'  => $itemId,
                'active'   => 1
            ]);
            return (int)$legacy->id;
        }

        // 3) Otherwise, create the scoped account (unique index guarantees no dup)
        $tbl->insert([
            'name'                   => $desiredName,
            'key_name'               => $this->_to_key_name($desiredName),
            'account_type_id'        => $typeId,
            'account_detail_type_id' => $detailId,
            'item_id'                => $itemId,
            'active'                 => 1,
        ]);

        return (int)$this->db->insertID();
    }



    private function _cleanup_item_accounts_and_mapping(int $itemId): void
    {
        $prefix = get_db_prefix();
        $accTbl = $this->db->table($prefix . 'acc_accounts');
        $mapTbl = $this->db->table($prefix . 'acc_item_automatics');

        // 1) fetch accounts linked to this item
        $accounts = $accTbl->select('id')
            ->where('item_id', $itemId)
            ->get()->getResultArray();

        // 2) attempt to delete each account via Accounting_model->delete_account
        foreach ($accounts as $row) {
            $accId = (int)$row['id'];
            // delete_account respects transactions; returns 'have_transaction' when history exists
            $result = $this->Vendor_items_model->delete_account($accId);

            if ($result === 'have_transaction') {
                // can’t delete: deactivate instead (keeps the ledger clean)
                $accTbl->where('id', $accId)->update(['active' => 0]);
            }
        }

        // 3) remove item mapping rows (safe to hard delete)
        $mapTbl->where('item_id', $itemId)->delete();
    }

    /**
     * On undo: reactivate any accounts we previously deactivated for this item,
     * and re-ensure item mapping if your workflow expects it back.
     */
    private function _reactivate_item_accounts_and_mapping(int $itemId): void
    {
        $prefix = get_db_prefix();
        $accTbl = $this->db->table($prefix . 'acc_accounts');
        $mapTbl = $this->db->table($prefix . 'acc_item_automatics');

        // reactivate all accounts tied to this item
        $accTbl->where('item_id', $itemId)->update(['active' => 1]);

        // if you want to restore income mapping automatically, you can re-ensure it here.
        // optional: noop if you prefer to rely on your save() ensure-logic when edited next.
        // Example (uncomment if you want auto-restore):
        /*
    $exists = $mapTbl->where('item_id', $itemId)->countAllResults();
    if (!$exists) {
        // You may want to resolve the expected income account again:
        // $incomeAccountId = ... look up by (item_id, account_type_id=11, detail=83) ...
        // $mapTbl->insert(['item_id' => $itemId, 'income_account' => $incomeAccountId]);
    }
    */
    }

    /* delete or undo an item */
    function delete()
    {
        $this->access_only_team_members();
        // if (!$this->can_delete_items()) app_redirect("forbidden");

        $this->validate_submitted_data([
            "id" => "required|numeric"
        ]);

        $id = (int)$this->request->getPost('id');

        // get the account tied to this item
        $prefix = get_db_prefix();
        $accRow = $this->db->table($prefix . 'acc_accounts')
            ->select('id')
            ->where('item_id', $id)
            ->get()
            ->getRow();

        if ($accRow) {
            $accountId = (int)$accRow->id;

            // check account history table for this account id
            $hist = $this->db->table($prefix . 'acc_account_history')
                ->where('account', $accountId)
                ->countAllResults();

            if ($hist > 0) {
                echo json_encode([
                    "success" => false,
                    "message" => app_lang('cannot_delete_transaction_already_exists')
                ]);
                return;
            }
        }

        // no history → safe to delete
        if ($this->Vendor_items_model->delete($id)) {
            $this->_cleanup_item_accounts_and_mapping($id);

            echo json_encode([
                "success" => true,
                "id"      => $id,
                "message" => app_lang('record_deleted')
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => app_lang('record_cannot_be_deleted')
            ]);
        }
    }



    /* list of items, prepared for datatable */
    function list_data()
    {
        $this->access_only_team_members();
        // Check if the user has permission to view items.
        if (!$this->can_hide_vendor_items()) {
            echo json_encode(array("data" => array()));
            return;
        }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("items", $this->login_user->is_admin, $this->login_user->user_type);

        $category_id = $this->request->getPost('category_id');
        $options = array(
            "category_id" => $category_id,
            "custom_fields" => $custom_fields,
            "custom_field_filter" => $this->prepare_custom_field_filter_values("items", $this->login_user->is_admin, $this->login_user->user_type)
        );

        $list_data = $this->Vendor_items_model->get_details($options)->getResult();

        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_item_row($data, $custom_fields);
        }
        echo json_encode(array("data" => $result));
    }

    /* return a row of item list table */
    private function _item_row_data($id)
    {
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("items", $this->login_user->is_admin, $this->login_user->user_type);

        $options = array("id" => $id, "custom_fields" => $custom_fields);
        $data = $this->Vendor_items_model->get_details($options)->getRow();
        return $this->_make_item_row($data, $custom_fields);
    }

    /* prepare a row of item list table */
    private function _make_item_row($data, $custom_fields)
    {
        $type = $data->unit_type ? $data->unit_type : "";

        $show_in_client_portal_icon = "";
        if ($data->show_in_client_portal && get_setting("module_order")) {
            $show_in_client_portal_icon = "<span title='" . app_lang("showing_in_client_portal") . "'><i data-feather='shopping-bag' class='icon-16'></i></span> ";
        }

        $row_data = array(
            modal_anchor(get_uri("vendor_items/view"), $show_in_client_portal_icon . $data->title, array("title" => app_lang("item_details"), "data-post-id" => $data->id)),
            custom_nl2br($data->description ? $data->description : ""),
            // $data->category_title ? $data->category_title : "-",
            $type,
            to_decimal_format($data->rate)
        );

        foreach ($custom_fields as $field) {
            $cf_id = "cfv_" . $field->id;
            $row_data[] = $this->template->view("custom_fields/output_" . $field->field_type, array("value" => $data->$cf_id));
        }

        $row_actions = "";
        // Conditionally show the edit button based on permissions.
        if ($this->can_update_vendor_item()) {
            $row_actions .= modal_anchor(get_uri("vendor_items/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_item'), "data-post-id" => $data->id));
        }
        // Conditionally show the delete button based on permissions.
        if ($this->can_delete_vendor_item()) {
            $row_actions .= js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendor_items/delete"), "data-action" => "delete"));
        }
        $row_data[] = $row_actions;

        return $row_data;
    }

    function view()
    {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        // Check if the user has permission to view items.
        // if (!$this->can_hide_items()) {
        //     app_redirect("forbidden");
        // }

        $model_info = $this->Vendor_items_model->get_details(array("id" => $this->request->getPost('id'), "login_user_id" => $this->login_user->id))->getRow();

        $view_data['model_info'] = $model_info;
        $view_data["client_info"] = $this->Clients_model->get_one($this->login_user->client_id);
        $view_data['custom_fields_list'] = $this->Custom_fields_model->get_combined_details("items", $model_info->id, $this->login_user->is_admin, $this->login_user->user_type)->getResult();

        $view_data['can_update_items'] = true;


        return $this->template->view('Vendors\Views/vendor_items/view', $view_data);
    }

    function save_files_sort()
    {
        $this->access_only_allowed_members();
        // Check if the user has permission to update items (which includes file sorting).
        // if (!$this->can_update_items()) {
        //     app_redirect("forbidden");
        // }

        $id = $this->request->getPost("id");
        $sort_values = $this->request->getPost("sort_values");
        if ($id && $sort_values) {
            //extract the values from the :,: separated string
            $sort_array = explode(":,:", $sort_values);

            $item_info = $this->Vendor_items_model->get_one($id);
            if ($item_info->id) {
                $updated_file_indexes = update_file_indexes($item_info->files, $sort_array);
                $item_data = array(
                    "files" => serialize($updated_file_indexes)
                );

                $this->Vendor_items_model->ci_save($item_data, $id);
            }
        }
    }

    private function _validate_excel_import_access()
    {
        // A user can import if they can add new items.
        return ($this->access_only_team_members());
    }

    private function _get_controller_slag()
    {
        return "items";
    }

    private function _get_custom_field_context()
    {
        return "items";
    }

    private function _get_headers_for_import()
    {
        return array(
            array("name" => "title", "required" => true, "required_message" => sprintf(app_lang("import_error_field_required"), app_lang("title"))),
            array("name" => "description"),
            array("name" => "category", "required" => true, "required_message" => sprintf(app_lang("import_error_field_required"), app_lang("category"))),
            array("name" => "unit_type"),
            array("name" => "rate", "required" => true, "required_message" => sprintf(app_lang("import_error_field_required"), app_lang("rate"))),
            array("name" => "show_in_client_portal")
        );
    }

    function download_sample_excel_file()
    {
        $this->access_only_team_members();
        // A user can download the sample if they have import access.
        if (!$this->_validate_excel_import_access()) {
            app_redirect("forbidden");
        }
        return $this->download_app_files(get_setting("system_file_path"), serialize(array(array("file_name" => "import-items-sample.xlsx"))));
    }

    private function _init_required_data_before_starting_import()
    {
        // $categories = $this->Item_categories_model->get_details()->getResult();
        $categories_id_by_title = array();
        foreach ($categories as $category) {
            $categories_id_by_title[$category->title] = $category->id;
        }

        $this->categories_id_by_title = $categories_id_by_title;
    }

    private function _save_a_row_of_excel_data($row_data)
    {
        $item_data_array = $this->_prepare_item_data($row_data);
        $item_data = get_array_value($item_data_array, "item_data");

        //couldn't prepare valid data
        if (!($item_data && count($item_data) > 1)) {
            return false;
        }

        //save item data
        $saved_id = $this->Vendor_items_model->ci_save($item_data);
        if (!$saved_id) {
            return false;
        }
    }

    private function _prepare_item_data($row_data)
    {

        $item_data = array();

        foreach ($row_data as $column_index => $value) {
            if (!$value) {
                continue;
            }

            $column_name = $this->_get_column_name($column_index);
            if ($column_name == "category") {
                $category_id = get_array_value($this->categories_id_by_title, $value);
                if ($category_id) {
                    $item_data["category_id"] = $category_id;
                } else {
                    $category_data = array("title" => $value);
                    $saved_category_id = $this->Item_categories_model->ci_save($category_data);
                    $item_data["category_id"] = $saved_category_id;
                    $this->categories_id_by_title[$value] = $saved_category_id;
                }
            } else if ($column_name == "rate") {
                $item_data["rate"] = unformat_currency($value);
            } else if ($column_name == "show_in_client_portal") {
                $item_data["show_in_client_portal"] = ($value === "Yes" ? 1 : "");
            } else {
                $item_data[$column_name] = $value;
            }
        }

        return array(
            "item_data" => $item_data
        );
    }
}

/* End of file items.php */
/* Location: ./Vendors/controllers/items.php */