<?php

namespace Vendors\Controllers;

use App\Controllers;
use App\Controllers\Security_Controller;

class Security_Controller_Plugin_vendor extends Security_Controller
{

    public $db;

    public $Vendor_model;
    public $Vendor_bills_model;
    public $Vendor_items_model;
    public $Vendor_bill_payments_model;
    public $Vendor_bill_items_model;
    public $Payment_methods_model;
    public $Supplier_model;

    // public $Branch_model;
    // public $Company_model;
    // public $Country_model;
    // public $Regions_model;
    // public $Projects_model;






    public function __construct($redirect = true)
    {
        parent::__construct();
        $this->db = \Config\Database::connect(); // Initialize the database connection

        //Vendors_model
        $this->Vendor_model = new \Vendors\Models\Vendor_model();
        $this->Vendor_bills_model = new \Vendors\Models\Vendor_bills_model();
        $this->Vendor_items_model = new \Vendors\Models\Vendor_items_model();
        $this->Vendor_bill_payments_model = new \Vendors\Models\Vendor_bill_payments_model();
        $this->Vendor_bill_items_model = new \Vendors\Models\Vendor_bill_items_model();
        $this->Supplier_model = new \aleelo_plugin\Models\Supplier_model();

        // Initialize Payment_methods_model - try App\Models first, fallback to aleelo_plugin
        if (class_exists('\App\Models\Payment_methods_model')) {
            $this->Payment_methods_model = new \App\Models\Payment_methods_model();
        } elseif (class_exists('\aleelo_plugin\Models\Payment_methods_model')) {
            $this->Payment_methods_model = new \aleelo_plugin\Models\Payment_methods_model();
        } else {
            // Try using model helper as last resort
            $this->Payment_methods_model = model("Payment_methods_model");
        }

        //Branch Model

        //branch
        // $this->Branch_model = new \Sales_and_crm\Models\Branch_model();
        // $this->Company_model = new \Sales_and_crm\Models\Company_model();

        // //Country Model
        // $this->Country_model = new \Sales_and_crm\Models\Country_model();

        // //Region
        // $this->Regions_model = new \Sales_and_crm\Models\Regions_model();

        // //Projects
        // $this->Projects_model = new \Sales_and_crm\Models\Projects_model();
    }


    //get currencies dropdown
    protected function _get_currencies_dropdown($support_empty_value = true, $selected_currency = "")
    {
        $used_currencies = $this->Vendor_bills_model->get_used_currencies_of_client()->getResult();
        $default_currency = get_setting("default_currency");

        $currencies_dropdown = array();
        if ($support_empty_value) {
            $currencies_dropdown[] = array("id" => "", "text" => "- " . app_lang("currency") . " -");
        }

        $currencies_dropdown[] = array("id" => $default_currency, "text" => $default_currency); // add default currency

        //if there is any specific currency selected, select only the currency.
        $selected_status = false;
        if ($used_currencies) {
            foreach ($used_currencies as $currency) {
                if (isset($selected_currency) && $selected_currency) {
                    if ($currency->currency == $selected_currency) {
                        $selected_status = true;
                    } else {
                        $selected_status = false;
                    }
                }

                $currencies_dropdown[] = array("id" => $currency->currency, "text" => $currency->currency, "isSelected" => $selected_status);
            }
        }
        return json_encode($currencies_dropdown);
    }


    // Branch functionality removed - not needed in vendors plugin
    // public function get_user_branch_access()
    // {
    //     $users_model = model("App\Models\Users_model", false);
    //     $user_id = $users_model->login_user_id();
    //
    //     $db = db_connect('default');
    //     $company = $db->table(get_db_prefix() . 'users')
    //         ->select('branch_access')
    //         ->where('id', $user_id)
    //         ->where('deleted', 0)
    //         ->get()
    //         ->getRow();
    //
    //     if (!$company) {
    //         return null;
    //     }
    //
    //     $branch = $company->branch_access;
    //
    //     if ($branch === 'all') {
    //         return session()->get('selected_branch_id');
    //     } else {
    //         return $branch;
    //     }
    // }





    // Branch functionality removed - not needed in vendors plugin
    // public function get_allowed_branch_ids(): array
    // {
    //     $user_id            = (int) ($this->login_user->id ?? 0);
    //     $selected_branch_id = session()->get('selected_branch_id'); // could be int, array, or "all"
    //
    //     // Read user's branch_access: "all" or CSV like "1,2,5"
    //     $row = $this->db->query(
    //         "SELECT branch_access FROM rise_users WHERE id = ? AND deleted = 0",
    //         [$user_id]
    //     )->getRow();
    //
    //     $branch_access = $row ? trim((string) $row->branch_access) : '';
    //
    //     // Helper: turn CSV into a clean int array
    //     $csvToIds = static function (?string $csv): array {
    //         if (!$csv) return [];
    //         $parts = array_map('trim', explode(',', $csv));
    //         $ids   = array_map('intval', $parts);
    //         return array_values(array_unique(array_filter($ids, fn($n) => $n > 0)));
    //     };
    //
    //     // 1) If user selected a specific branch (or an array of them), honor that.
    //     if (!empty($selected_branch_id) && $selected_branch_id !== 'all') {
    //         return array_values(array_unique(array_map('intval', (array) $selected_branch_id)));
    //     }
    //
    //     // 2) If the UI selection is "all"
    //     if ($selected_branch_id === 'all') {
    //         // If the user truly has access to all branches, return every branch id from DB.
    //         if (strtolower($branch_access) === 'all') {
    //             return $this->getAllBranchIds();
    //         }
    //         // Otherwise, restrict to the user's allowed list.
    //         return $csvToIds($branch_access);
    //     }
    //
    //     // 3) No UI selection. Fall back to the user's access.
    //     if (strtolower($branch_access) === 'all') {
    //         return $this->getAllBranchIds();
    //     }
    //
    //     return $csvToIds($branch_access);
    // }
    //
    // /**
    //  * Fetch every branch id you consider "active".
    //  * Adjust table name/conditions to match your schema (e.g., rise_branches).
    //  */
    // private function getAllBranchIds(): array
    // {
    //     $rows = $this->db->query("SELECT id FROM rise_branch WHERE deleted = 0")->getResultArray();
    //     return array_map('intval', array_column($rows, 'id'));
    // }


    // Branch functionality removed - not needed in vendors plugin
    public function get_user_branch_access_view()
    {
        // Return null to indicate no branch filtering
        return null;
    }

    // Branch functionality removed - not needed in vendors plugin
    function _get_branch()
    {
        return json_encode(array(array("id" => "", "text" => "- " . app_lang("branch") . " -")));
    }

    // Branch functionality removed - not needed in vendors plugin
    function _get_branch_map(): array
    {
        return [];
    }


    // Company functionality removed - not needed in vendors plugin
    protected function _get_companies_dropdown()
    {
        return array();
    }


    // Branch functionality removed - not needed in vendors plugin
    function _get_branch_form()
    {
        return array("" => "- " . app_lang("branch") . " -");
    }


    function _map_from_table(string $table, string $idCol, string $textCol, array $where = [], string $orderBy = null): array
    {
        $t = $this->db->prefixTable($table);
        $b = $this->db->table($t)->select("$idCol, $textCol");

        // common flags
        if (! array_key_exists('deleted', $where)) {
            $b->where('deleted', 0);
        }
        foreach ($where as $k => $v) {
            $b->where($k, $v);
        }

        $b->orderBy($orderBy ?: $textCol, 'ASC');

        $out = [];
        foreach ($b->get()->getResult() as $r) {
            $out[(string)$r->$idCol] = (string)$r->$textCol;
        }
        return $out;
    }

    // Vendors (branch filtering removed - not needed in vendors plugin)
    public function _vendors_map(?int $branch_id = null): array
    {
        // Use suppliers instead of rise_vendor for vendor bill workflows
        $suppliers = $this->Supplier_model->get_dropdown_list(["supplier_name"], "id", ["deleted" => 0]);
        return is_array($suppliers) ? $suppliers : [];
    }

    // Projects - removed Projects_model dependency
    public function _projects_map(): array
    {
        // Projects_model removed - not needed in vendors plugin
        // table: rise_projects (id, title, deleted)
        // return $this->_map_from_table('projects', 'id', 'title');
        return [];
    }

    // Vendor Items
    public function _vendor_items_map(): array
    {
        // table: rise_vendor_items (id, title, deleted)
        return $this->_map_from_table('vendor_items', 'id', 'title');
    }

    // Phases (if your table is named differently, change here)
    public function _phases_map(): array
    {
        // common names: project_phases OR phases
        // choose the one you actually have:
        if ($this->db->tableExists($this->db->prefixTable('project_phases'))) {
            return $this->_map_from_table('project_phases', 'id', 'title');
        }
        return $this->_map_from_table('milestones', 'id', 'title');
    }

    // Invoices dropdown - replaced project dropdown
    public function _invoices_map(): array
    {
        // Get invoices from invoices table
        if (!$this->db->tableExists($this->db->prefixTable('invoices'))) {
            return [];
        }

        $invoices = $this->db->table($this->db->prefixTable('invoices'))
            ->select('id, display_id, invoice_total')
            ->where('deleted', 0)
            ->orderBy('id', 'DESC')
            ->limit(1000) // Limit to prevent performance issues
            ->get()
            ->getResult();

        $map = [];
        foreach ($invoices as $inv) {
            // Use display_id if available, otherwise use ID
            $display = !empty($inv->display_id) ? $inv->display_id : ('INV-' . $inv->id);

            // Add invoice total to display
            $total = isset($inv->invoice_total) && $inv->invoice_total > 0
                ? number_format((float)$inv->invoice_total, 2, '.', '')
                : '0.00';

            // Format: "BRM/2025/000144 - 720.00"
            $display_with_total = $display . ' - ' . $total;

            $map[(string)$inv->id] = $display_with_total;
        }

        return $map;
    }

    // Nicely format an account label from a DB row
    private function format_account_label(object $r): string
    {
        $name = trim((string) ($r->name ?? ''));

        // if name missing, derive from key_name like "acc_cash_and_cash_equivalents" -> "Cash And Cash Equivalents"
        if ($name === '' || strtolower($name) === 'null') {
            $key = trim((string) ($r->key_name ?? ''));
            if ($key !== '') {
                $pretty = preg_replace('/^acc_/', '', $key);
                $pretty = str_replace('_', ' ', $pretty);
                $name   = ucwords($pretty);
            }
        }

        // final safety net
        if ($name === '') {
            $name = 'Account #' . (int) $r->id;
        }

        $number = trim((string) ($r->number ?? ''));
        return $number !== '' ? ($number . ' — ' . $name) : $name;
    }



    // public function _accounts_map(): array
    // {
    //     $t = $this->db->prefixTable('acc_accounts');

    //     $b = $this->db->table($t)
    //         ->select('id, name, key_name, number');

    //     // filter deleted
    //     if ($this->db->fieldExists('deleted', $t)) {
    //         $b->where('deleted', 0);
    //     }

    //     // filter active (if column exists)
    //     if ($this->db->fieldExists('active', $t)) {
    //         $b->where('active', 1);
    //     }

    //     // exclude Accounts Payable (id=87)
    //     $b->where('id !=', 87);

    //     $rows = $b->get()->getResult();

    //     $out = [];
    //     foreach ($rows as $r) {
    //         $out[(string) $r->id] = $this->format_account_label($r);
    //     }

    //     // sort human-friendly
    //     natcasesort($out);
    //     return $out;
    // }

    public function _accounts_map(): array
    {
        $t = $this->db->prefixTable('acc_accounts');

        $b = $this->db->table($t)
            ->select('id, name, key_name, number, account_type_id');

        // filter deleted
        if ($this->db->fieldExists('deleted', $t)) {
            $b->where('deleted', 0);
        }

        // filter active
        if ($this->db->fieldExists('active', $t)) {
            $b->where('active', 1);
        }

        // Hide these account types for Vendor Bills
        $hidden_types = [1, 3, 6, 7, 8, 9, 10, 11, 12];
        $b->whereNotIn('account_type_id', $hidden_types);

        $rows = $b->get()->getResult();

        $out = [];
        foreach ($rows as $r) {
            $out[(string) $r->id] = $this->format_account_label($r);
        }

        // Sort human-friendly
        natcasesort($out);
        return $out;
    }



    public function _get_vendors_dropdown_json()
    {
        $Supplier_model = $this->Vendor_model;
        $vendors = $Supplier_model
            ->get_all_where(["deleted" => 0], 0, 0, "vendor_name")
            ->getResult();

        $opts = [];
        $opts[] = ["id" => "", "text" => "— " . (app_lang('vendor') ?: 'Vendor') . " —"];
        foreach ($vendors as $v) {
            $opts[] = ["id" => (string)$v->id, "text" => (string)$v->vendor_name];
        }
        return json_encode($opts);
    }


    //  Permissions

    public function can_manage_vendors()
    {
        // Staff can manage vendors if they are an admin or have the 'can_manage_vendors' permission
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_manage_vendors") == "1")
        ) {
            return true;
        }
        return false;
    }

    // ---------------- VENDORS ----------------
    public function can_hide_vendors()
    {
        // Staff can hide vendors if they are an admin or have the 'hide_vendors' permission.
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hide_vendors") !== "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_add_new_vendor()
    {
        // Staff can add vendors if they are an admin or have the 'can_add_new_vendor' permission.
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_new_vendor") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_update_vendor()
    {
        // Staff can update vendors if they are an admin or have the 'can_update_vendor' permission.
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_vendor") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_delete_vendor()
    {
        // Staff can delete vendors if they are an admin or have the 'can_delete_vendor' permission.
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_vendor") == "1")
        ) {
            return true;
        }
        return false;
    }


    // ---------------- VENDOR BILLS ----------------
    public function can_hide_vendor_bills()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hide_vendor_bills") !== "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_add_new_vendor_bill()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_new_vendor_bill") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_update_vendor_bill()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_vendor_bill") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_delete_vendor_bill()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_vendor_bill") == "1")
        ) {
            return true;
        }
        return false;
    }


    // ---------------- VENDOR BILL PAYMENTS ----------------
    public function can_hide_vendor_bill_payments()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hide_vendor_bill_payments") !== "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_add_new_vendor_bill_payment()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_new_vendor_bill_payment") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_update_vendor_bill_payment()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_vendor_bill_payment") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_delete_vendor_bill_payment()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_vendor_bill_payment") == "1")
        ) {
            return true;
        }
        return false;
    }

    // ---------------- VENDOR ITEMS ----------------
    public function can_hide_vendor_items()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "hide_vendor_items") !== "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_add_new_vendor_item()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_new_vendor_item") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_update_vendor_item()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_vendor_item") == "1")
        ) {
            return true;
        }
        return false;
    }

    protected function can_delete_vendor_item()
    {
        if (
            $this->login_user->user_type == "staff"
            && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_vendor_item") == "1")
        ) {
            return true;
        }
        return false;
    }
}
