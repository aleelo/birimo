<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\App_Controller_Plugin;
use App\Controllers;
use App\Controllers\Security_Controller;

class Security_Controller_Plugin extends Security_Controller
{

    public $Assigning_items_model;

    public $Screen_size_model;
    public $Items_model;
    public $Items_list_model;
    public $db;
    public $Expenses_model;
    public $Company_model;
    public $Projects_model;
    public $Project_status_model;
    public $Clients_model;
    public $Expense_payments_model;
    public $Expense_payments_emp_model;
    public $Expenses_emp_model;
    public $Country_model;
    public $Regions_model;

    public $University_names_model;
    // use App_Controller;
    public $Field_of_study_model;
    public $Users_models;
    public $Users_model;

    public $Tasks_model;

    public $Estimates_model;
    public $Supplier_model;
    public $Invoice_items_model;
    public $Invoice_payments_model;
    public $Invoices_model;
    public $Expense_categories_model;

    public $Estimate_items_model;
    public $Payment_methods_model;


    public function __construct($redirect = true)
    {
        parent::__construct();
        $this->db = \Config\Database::connect(); // Initialize the database connection

        $this->Assigning_items_model = new \aleelo_plugin\Models\Assigning_items_model();
        $this->Screen_size_model = new \aleelo_plugin\Models\Screen_size_model();
        $this->Items_list_model = new \aleelo_plugin\Models\Items_list_model();
        $this->Expenses_model = new \aleelo_plugin\Models\Expenses_model();
        $this->Company_model = new \App\Models\Company_model();
        $this->Project_status_model = new \aleelo_plugin\Models\Project_status_model();
        $this->Projects_model = new \aleelo_plugin\Models\Projects_model();
        $this->Clients_model = new \aleelo_plugin\Models\Clients_model();
        $this->University_names_model = new \aleelo_plugin\Models\University_names_model();
        $this->Field_of_study_model = new \aleelo_plugin\Models\Field_of_study_model();
        $this->Users_models = new \aleelo_plugin\Models\Users_models();
        $this->Tasks_model = new \aleelo_plugin\Models\Tasks_model();
        $this->Estimates_model = new \aleelo_plugin\Models\Estimates_model();
        $this->Users_model = new \aleelo_plugin\Models\Users_models();
        $this->Country_model = new \aleelo_plugin\Models\Country_model();
        $this->Regions_model = new \aleelo_plugin\Models\Regions_model();
        $this->Items_model = new \aleelo_plugin\Models\Items_model();
        $this->Supplier_model = new \aleelo_plugin\Models\Supplier_model();
        $this->Invoice_items_model = new \aleelo_plugin\Models\Invoice_items_model();
        $this->Invoice_payments_model = new \aleelo_plugin\Models\Invoice_payments_model();
        $this->Invoices_model = new \aleelo_plugin\Models\Invoices_model();
        $this->Expense_categories_model = new \aleelo_plugin\Models\Expense_categories_model();
        $this->Estimate_items_model = new \aleelo_plugin\Models\Estimate_items_model();
        $this->Expense_payments_emp_model = new \aleelo_plugin\Models\Expense_payments_emp_model();
        $this->Expenses_emp_model = new \aleelo_plugin\Models\Expenses_emp_model();
        $this->Payment_methods_model = new \aleelo_plugin\Models\Payment_methods_model();






        // if (!$login_user_id && $redirect) {
        //     $uri_string = uri_string();

        //     if (!$uri_string || $uri_string === "signin" || $uri_string === "/signin" || $uri_string === "/") {
        //         app_redirect('signin');
        //     } else {
        //         app_redirect('signin?redirect=' . get_uri($uri_string));
        //     }
        // }

        // app_hooks()->do_action('app_hook_before_app_access', array(
        //     "login_user_id" => $login_user_id,
        //     "redirect" => $redirect
        // ));

        // //initialize login users required information
        // $this->login_user = $this->Users_models->get_access_info($login_user_id);

        // //initialize login users access permissions
        // if ($this->login_user && $this->login_user->permissions) {
        //     $permissions = unserialize($this->login_user->permissions);
        //     $this->login_user->permissions = is_array($permissions) ? $permissions : array();
        // } else {
        //     if (!$this->login_user) {
        //         $this->login_user = new \stdClass();
        //     }
        //     $this->login_user->permissions = array();
        // }

    }

    protected function can_edit_profile()
    {
        if ($this->login_user->user_type === "staff" && !$this->login_user->is_admin && get_array_value($this->login_user->permissions, "cant_edit_profile") == "1") {
            return true;
        }
    }
    protected function can_edit_team_member()
    {
        if ($this->login_user->user_type === "staff" && !$this->login_user->is_admin && get_array_value($this->login_user->permissions, "can_edit_team_member") == "1") {
            return true;
        }
    }
    public function get_bank_name_dropdown()
    {

        $bane_names = $this->db->query("SELECT id, bank_name FROM rise_bank_names WHERE deleted=0")->getResult();
        $temp_array = array('' => '---Choose Bank Name---');

        if (!$bane_names) {
            return null;
        }

        foreach ($bane_names as $b) {
            $temp_array[$b->id] = $b->bank_name;
        }

        return $temp_array;
    }
    public function get_clients_and_leads_dropdown($return_json = false)
    {
        $clients_dropdown = array("" => "-");
        $clients_json_dropdown = array(array("id" => "", "text" => "-"));
        if ($company_id = $this->login_user->department == 0) {
            $clients = $this->Clients_model->get_all_where(array("deleted" => 0), 0, 0, "is_lead")->getResult();
        } else if ($this->login_user->department == !0) {
            $company_id = $this->login_user->department;
            $clients = $this->Clients_model->get_all_where(array("deleted" => 0, "company_id" => $company_id), 0, 0, "is_lead")->getResult();
        }



        foreach ($clients as $client) {
            $company_name = $client->is_lead ? app_lang("lead") . ": " . $client->company_name : $client->company_name;

            $clients_dropdown[$client->id] = $company_name;
            $clients_json_dropdown[] = array("id" => $client->id, "text" => $company_name);
        }

        return $return_json ? $clients_json_dropdown : $clients_dropdown;
    }
    function get_payment_method_dropdown()
    {

        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0), 0, 0, "title")->getResult();

        $payment_method_dropdown =  array("" =>  "-- " . app_lang("payment_methods") . " --");
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[$value->id] = $value->title;
        }

        return $payment_method_dropdown;
    }
    //get categories dropdown
    public function _get_categories_dropdown()
    {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->getResult();

        $categories_dropdown = array("" =>  "--- " . app_lang("category") . " ---");
        foreach ($categories as $category) {
            $categories_dropdown[$category->id] = $category->title;
        }

        return $categories_dropdown;
    }
    public function _get_expenses_dropdown()
    {
        $expenses = $this->Expenses_model->get_all_where(array("deleted" => 0), 0, 0, "title")->getResult();

        $expenses_dropdown = array("" =>  "-- " . app_lang("expenses") . " --");
        foreach ($expenses as $expense) {
            $expenses_dropdown[$expense->id] = $expense->title;
        }

        return $expenses_dropdown;
    }
    function payment_method_dropdown()
    {
        if (!$this->can_view_invoice()) {
            app_redirect("forbidden");
        }
        $payment_methods = $this->Payment_methods_model->get_all_where(array("deleted" => 0))->getResult();

        $payment_method_dropdown = array(array("id" => "", "text" => "- " . app_lang("payment_method") . " -"));
        foreach ($payment_methods as $value) {
            $payment_method_dropdown[] = array("id" => $value->id, "text" => $value->title);
        }

        return $payment_method_dropdown;
    }
    public function _get_vendors_dropdown()
    {
        $venders = $this->Supplier_model->get_all_where(array("deleted" => 0), 0, 0, "supplier_name")->getResult();

        $venders_dropdown = array("" => "----Vendor----");
        foreach ($venders as $vender) {
            $venders_dropdown[$vender->id] = $vender->supplier_name;
        }

        return $venders_dropdown;
    }
    //get categories dropdown
    public function _get_categories_dropdown_js()
    {
        $categories = $this->Expense_categories_model->get_all_where(array("deleted" => 0), 0, 0, "title")->getResult();

        $categories_dropdown = array(array("id" => "", "text" => "- " . app_lang("category") . " -"));
        foreach ($categories as $category) {
            $categories_dropdown[] = array("id" => $category->id, "text" => $category->title);
        }

        return json_encode($categories_dropdown);
    }

    public function _get_vendors_dropdown_js()
    {
        $venders = $this->Supplier_model->get_all_where(array("deleted" => 0), 0, 0, "supplier_name")->getResult();

        $venders_dropdown = array(array("id" => "", "text" => "- " . "Vendor" . " -"));
        foreach ($venders as $vender) {
            $venders_dropdown[] = array("id" => $vender->id, "text" => $vender->supplier_name);
        }

        return json_encode($venders_dropdown);
    }

    public function _get_expenses_dropdown_js()
    {
        $expenses = $this->db->query("select e.*, s.supplier_name from rise_expenses e 
                    left join rise_supplier s on s.id = e.vendor_id 
                    where e.deleted = 0")->getResult();

        $venders_dropdown = array(array("id" => "", "text" => "- " . "Expense" . " -"));
        foreach ($expenses as $e) {
            $venders_dropdown[] = array("id" => $e->id, "text" => $e->supplier_name . " - " . $e->expense_date . " - " . $e->amount);
        }

        return json_encode($venders_dropdown);
    }


    public function getExpensesByVendorJs($category_id = 0, $vendor_id = 0)
    {

        if ($category_id && $vendor_id) {
            $expenses = $this->db->query("select e.*, s.supplier_name from rise_expenses e 
                    left join rise_supplier s on s.id = e.vendor_id 
                    where e.category_id = $category_id and e.vendor_id = $vendor_id and e.deleted = 0")->getResult();
        } else if ($category_id) {
            $expenses = $this->db->query("select e.*, s.supplier_name from rise_expenses e 
                    left join rise_supplier s on s.id = e.vendor_id 
                    where e.category_id = $category_id and e.deleted = 0")->getResult();
        } else if ($vendor_id) {
            $expenses = $this->db->query("select e.*, s.supplier_name from rise_expenses e 
                    left join rise_supplier s on s.id = e.vendor_id 
                    where e.vendor_id = $vendor_id and e.deleted = 0")->getResult();
        } else {

            $expenses = $this->db->query("select e.*, s.supplier_name from rise_expenses e 
                    left join rise_supplier s on s.id = e.vendor_id 
                    where e.deleted = 0")->getResult();
        }

        $venders_dropdown = array(array("id" => "", "text" => "- " . "Expense" . " -"));
        foreach ($expenses as $e) {
            $venders_dropdown[] = array("id" => $e->id, "text" => $e->supplier_name . " - " . $e->expense_date . " - " . $e->amount);
        }

        return json_encode($venders_dropdown);
    }

    protected function can_view_own_project()
    {
        if (

            $this->login_user->company_access === "all"
        ) {
            return $this->login_user->department;
        }
        return $this->login_user->department;
    }
    protected function can_view_own_company_project()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }
    // ----------------------------------------------------invoice-----------------------------------------------------
    protected function can_view_invoice()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "hide_invoice") !== "1" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }


    protected function can_edit_invoice()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_invoice") == "1")) {
            return true;
        }
        return null;
    }


    protected function can_add_invoice()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_invoice") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_invoice()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_invoice") == "1")) {
            return true;
        }
        return null;
    }
    protected function can_view_own_company_invoice()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }


    // ----------------------------------------------------estimate-----------------------------------------------------
    protected function can_view_estimate()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "hide_estimate") !== "1" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }


    protected function can_edit_estimate()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_estimate") == "1")) {
            return true;
        }
        return null;
    }


    protected function can_add_estimate()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_estimate") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_estimate()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_estimate") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_estimate()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }
    // ----------------------------------------------------payment-----------------------------------------------------
    protected function can_view_payment()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "hide_payment") !== "1" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }


    protected function can_edit_payment()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_payment") == "1")) {
            return true;
        }
        return false;
    }


    protected function can_add_payment()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_payment") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_payment()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_payment") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_payment()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }

    // ----------------------------------------------------client-----------------------------------------------------
    protected function can_view_client()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "hide_client") !== "1" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }


    protected function can_edit_client()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_client") == "1")) {
            return true;
        }
        return false;
    }


    protected function can_add_client()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_client") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_client()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_client") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_client()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }
    // ----------------------------------------------------supplier-----------------------------------------------------
    protected function can_view_supplier()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "hide_supplier") !== "1" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }


    protected function can_edit_supplier()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_supplier") == "1")) {
            return true;
        }
        return false;
    }


    protected function can_add_supplier()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_supplier") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_supplier()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_supplier") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_supplier()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }

    // ----------------------------------------------------expense-----------------------------------------------------hide_expense
    protected function can_view_expense()
    {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_expense") !== "1") {
            return true;
        }

        return false;
    }


    protected function can_edit_expense()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_update_expense") == "1")) {
            return true;
        }
        return false;
    }


    protected function can_add_expense()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_add_expense") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_expense()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_expense") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_expense()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }
    protected function can_view_own_expense()
    {
        if (
            get_array_value($this->login_user->permissions, "expense") === "own_expenses"
        ) {
            return null;
        } else {
            return  $department = $this->login_user->id;
        }
    }

    // ----------------------------------------------------task-----------------------------------------------------
    protected function can_view_task()
    {
        if ($this->login_user->user_type == "staff" && get_array_value($this->login_user->permissions, "hide_task") !== "1") {
            return true;
        }
        return false;
    }


    protected function can_edit_task()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_edit_tasks") == "1")) {
            return true;
        }
        return false;
    }


    protected function can_add_task()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_create_tasks") == "1")) {
            return true;
        }
        return false;
    }

    protected function can_delete_task()
    {
        if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin || get_array_value($this->login_user->permissions, "can_delete_tasks") == "1")) {
            return true;
        }
        return false;
    }
    protected function can_view_own_company_task()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }

    protected function can_view_own_company_tasks()
    {
        if (

            get_array_value($this->login_user->permissions, "task") === "all_tasks"
        ) {
            return $this->login_user->department;
        }
        return null;
    }
    protected function can_view_own_tasks()
    {
        if (
            ($this->login_user->user_type == "staff") &&
            get_array_value($this->login_user->permissions, "task") === "own_tasks"
        ) {
            return $this->login_user->id;
        }
        return null;
    }
    //-----------------------------------------------------items-----------------------------------------------------
    protected function can_view_items()
    {
        if ($this->login_user->is_admin) {
            return true;
        }
        if (
            $this->login_user->user_type == "staff" &&
            get_array_value($this->login_user->permissions, "items") !== "no" &&
            get_array_value($this->login_user->permissions, "show_sales") == "1"
        ) {
            return true;
        }

        return false;
    }

    // protected function can_view_items() {
    //     if ($this->login_user->user_type == "staff" && ($this->login_user->is_admin|| get_array_value($this->login_user->permissions, "items") == "all"))    {
    //         return true; 
    //     }
    //     return false;
    // }

    protected function can_view_own_department_client()
    {
        $department = $this->login_user->department;
        if ($department == 0) {
            return null;
        } else {
            return  $department = $this->login_user->department;
        }
    }




    public function get_merchant_types_dropdown()
    {

        $merchant_types = $this->db->query("SELECT mt.id, mt.merchant_type FROM rise_merchant_types mt WHERE mt.deleted=0")->getResult();
        $temp_array = array('' => '---  Choose Merchant Type ---');

        if (!$merchant_types) {
            return null;
        }

        foreach ($merchant_types as $m) {
            $temp_array[$m->id] = $m->merchant_type;
        }

        return $temp_array;
    }

    public function get_merchant_types_dropdown_js()
    {

        $merchant_types = $this->db->query("SELECT mt.id, mt.merchant_type FROM rise_merchant_types mt WHERE mt.deleted=0")->getResult();
        // $temp_array[] = array('id' =>'','text'=> '---  Choose Merchant Type ---');

        if (!$merchant_types) {
            return [];
        }

        foreach ($merchant_types as $m) {
            $temp_array[] = array('id' => $m->id, 'text' => $m->merchant_type);
        }

        return $temp_array;
    }


    protected function show_own_office_only_user_id()
    {
        if ($this->login_user->user_type === "staff") {
            // print_r(get_array_value($this->login_user->permissions, "somgas"));die;
            return get_array_value($this->login_user->permissions, "somgas") == "own_office" ? $this->login_user->id : false;
        }
    }


    public function can_view_team_members_contact_info()
    {
        if ($this->login_user->user_type == "staff") {
            if ($this->login_user->is_admin) {
                return true;
            } else if (get_array_value($this->login_user->permissions, "can_view_team_members_contact_info") == "1") {
                return true;
            }
        }
    }
    public function get_departments_for_table_emp()
    {
        $dept_id = $this->get_user_department_id();
        $role = $this->get_user_role();

        if ($role == 'admin' || $role == 'Administrator' || $role == 'HRM') {
            $dept_id = '%';
        }

        if (!$dept_id) {
            return json_encode([['id' => '', 'text' => 'All Companies']]);
        }

        $query = "SELECT id, name FROM rise_company WHERE id LIKE ?";
        $depts = $this->db->query($query, [$dept_id]);

        if (!$depts) {
            return json_encode([['id' => '', 'text' => 'All Companies']]);
        }

        $data[] = ['id' => '', 'text' => 'All Companies'];

        foreach ($depts->getResult() as $d) {
            $data[] = ['id' => $d->id, 'text' => $d->name];
        }

        return json_encode($data);
    }


    public function get_user_role()
    {
        $user = $this->login_user;

        if ($user->is_admin) {
            return 'admin';
        }

        $role = $this->Roles_model->get_one($user->role_id);
        return $role->title;
    }

    public function get_user_department_id()
    {
        $user_id = $this->login_user->department ?? null;

        if (!$user_id) {
            return null;
        }

        $query = "SELECT t.company_id FROM rise_expenses t 
                  LEFT JOIN rise_users u ON u.id = t.user_id 
                  WHERE t.company_id = ?";

        $job_info = $this->db->query($query, [$user_id])->getRow();

        return $job_info?->company_id;
    }






    //-----------------------------------------------------------logs------------------------------------------------------


    public function log_activity_only_with_changes_custom($type, $id, $data_before, $data_after, $action)
    {
        $model = null;
        $log_type_title_key = "title";

        switch ($type) {
            case "project":
                $model = model('Projects_model');
                break;
            case "task":
                $model = model('Tasks_model');
                break;
            case "client":
                $model = model('Clients_model');
                $log_type_title_key = "company_name";
                break;
            case "quotation":
                $model = model('Estimates_model');
                $log_type_title_key = "id";
                break;
            case "invoice":
                $model = model('Invoices_model');
                $log_type_title_key = "id";
                break;
            default:
                log_message('error', "Unknown log type: $type");
                return;
        }

        if (!$model) {
            log_message('error', "Model not found for type: $type");
            return;
        }

        if (!$data_before || !isset($data_before['id'])) {
            log_message('error', "Data before not found or missing id for log type: $type");
            return;
        }

        $fields_changed = [];

        foreach ($data_after as $field => $new_value) {
            $old_value = isset($data_before[$field]) ? $data_before[$field] : null;
            if ($old_value != $new_value) {
                $from = $old_value;
                $to = $new_value;

                if ($field === "client_id") {
                    $clients_model = model("aleelo_plugin\Models\Clients_model");
                    $from = $from ? $clients_model->get_one($from)->company_name : "N/A";
                    $to   = $to ? $clients_model->get_one($to)->company_name : "N/A";
                }

                if (in_array($field, ["tax_id", "tax_id2", "tax_id3"])) {
                    $taxes_model = model("App\Models\Taxes_model");
                    $from = $from ? $taxes_model->get_one($from)->title : "N/A";
                    $to   = $to ? $taxes_model->get_one($to)->title : "N/A";
                }

                if ($field === "project_id") {
                    $projects_model = model("aleelo_plugin\Models\Projects_model");
                    $from = $from ? $projects_model->get_one($from)->title : "N/A";
                    $to   = $to ? $projects_model->get_one($to)->title : "N/A";
                }

                $pretty_key = preg_replace('/_id\d*$/', '', $field);
                $fields_changed[$pretty_key] = ["from" => $from, "to" => $to];
            }
        }

        if (empty($fields_changed) && $action == "updated") {
            log_message('info', "No changes detected, skipping activity log insert.");
            return;
        }

        $log_type_title = isset($data_before[$log_type_title_key]) ? $data_before[$log_type_title_key] : "N/A";
        if ($type === "quotation") {
            $log_type_title = "#" . $log_type_title;
        }

        $log_data = [
            "created_at"     => date('Y-m-d H:i:s'),
            "created_by"     => session()->get('user_id') ?: 1,
            "action"         => $action,
            "log_type"       => $type,
            "log_type_title" => $log_type_title,
            "log_type_id"    => $id,
            "log_for"        => $type,
            "log_for_id"     => $id,
            "changes"        => serialize($fields_changed)
        ];

        $builder = $this->db->table($this->db->prefixTable('activity_logs'));
        $inserted = $builder->insert($log_data);

        if (!$inserted) {
            log_message('error', 'Failed to insert activity log: ' . print_r($builder->error(), true));
        } else {
            log_message('info', 'Activity log inserted successfully for ' . $type . ' ID ' . $id);
        }
    }


    public function getNextSort(int $invoice_id): int
    {
        $itemsTbl    = $this->db->prefixTable('invoice_items');
        $sectionsTbl = $this->db->prefixTable('items_section');

        $maxItems = $this->db->table($itemsTbl)
            ->selectMax('sort', 'max_sort')
            ->where('invoice_id', $invoice_id)
            ->where('deleted', 0)
            ->get()->getRow();

        $maxSections = $this->db->table($sectionsTbl)
            ->selectMax('sort', 'max_sort')
            ->where('invoice_id', $invoice_id)
            ->where('deleted', 0)
            ->get()->getRow();

        $m1 = $maxItems && isset($maxItems->max_sort) ? (int)$maxItems->max_sort : 0;
        $m2 = $maxSections && isset($maxSections->max_sort) ? (int)$maxSections->max_sort : 0;

        return max($m1, $m2) + 1;
    }
}
