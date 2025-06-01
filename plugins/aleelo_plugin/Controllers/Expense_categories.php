<?php

namespace aleelo_plugin\Controllers;
use aleelo_plugin\Controllers\Security_Controller_Plugin;
use Accounting\Models\Accounting_model;

class Expense_categories extends Security_Controller_Plugin {

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
    }

    //load expense categories list view
    function index() {
        return $this->template->rander("aleelo_plugin\Views/expense_categories/index");
    }

    //load expense category add/edit modal form
    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));
        $view_data['has_all_permission'] =
        ($this->login_user->user_type === "staff" &&  $this->login_user->company_access ==="all" && $this->login_user->department ==0);
            $view_data['companies_dropdown'] =  array("0" => "choose company") +$this->Company_model->get_dropdown_list(array("name"));

        $view_data['model_info'] = $this->Expense_categories_model->get_one($this->request->getPost('id'));
        return $this->template->view('aleelo_plugin\Views/expense_categories/modal_form', $view_data);
    }

        function get_account_suggestion() {
        $key = $this->request->getPost("c");
        if (class_exists('\Accounting\Models\Accounting_model')) {
            $accounting_model = new Accounting_model();
            $accounts = $accounting_model->get_accounts("", array("account_type_id" => 14), $key);
    
            foreach ($accounts as $account) {
                $suggestion[] = array("id" => $account['id'], "text" => $account['name']);
            }
        
        
            echo json_encode($suggestion);
        } else {
            log_message('error', 'Accounting plugin is not available.');
        }
        
       
    }
    //save expense category
    function save() {

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "title" => "required"
        ));

        $id = $this->request->getPost('id');
        $data = array(
            "title" => $this->request->getPost('title'),
            "expense_type" => $this->request->getPost('expense_type'),
            "account_id" => $this->request->getPost('account_id') ? $this->request->getPost('account_id') : null,
            "company_id" => $this->request->getPost('company_id') ? $this->request->getPost('company_id') : 0,
        );
        $save_id = $this->Expense_categories_model->ci_save($data, $id);
        if ($save_id) {
            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    //delete/undo an expense category
    function delete() {
        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');
        if ($this->request->getPost('undo')) {
            if ($this->Expense_categories_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Expense_categories_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    //get data for expenses category list
    function list_data() {
        $list_data = $this->Expense_categories_model->get_details()->getResult();
        $options = array(           
             "department" =>$this->login_user->department,
);
$list_data = $this->Expense_categories_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    //get an expnese category list row
    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Expense_categories_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    //prepare an expense category list row
    private function _make_row($data) {
        return array(
            $data->title,
            $data->expense_type,
            $data->account_id ? $data->account_name : app_lang('none'),
            $data->company_id ? $data->company_name : app_lang('none'),

            modal_anchor(get_uri("expense_categories/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_expenses_category'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_expenses_category'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("expense_categories/delete"), "data-action" => "delete"))
        );
    }

}

/* End of file expense_categories.php */
/* Location: ./app/controllers/expense_categories.php */