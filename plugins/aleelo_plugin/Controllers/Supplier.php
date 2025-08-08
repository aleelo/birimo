<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\Security_Controller_Plugin;
use Accounting\Models\Accounting_model;
class Supplier extends Security_Controller_Plugin {


    function __construct() {
        parent::__construct();
    }

    function index() {
        if (!$this->can_view_supplier()) {
            app_redirect("forbidden");
        }
        $view_data['companies_dropdown'] =$this->_get_company();


        $view_data['see_company_dropdown'] = $this->login_user->user_type === "staff" && $this->login_user->company_access ==="all" && $this->login_user->department ==0;
        $view_data['can_add_supplier'] = $this->can_add_supplier();
        return $this->template->rander("aleelo_plugin\Views/supplier/index",$view_data);
    }

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));
        if (!$this->can_add_supplier()  && !$this->can_edit_supplier()) {
            app_redirect("forbidden");
        }       
        $accounting_model = new Accounting_model();
        $accounts = $accounting_model->get_accounts();
        $accounts_dropdown = [];
        foreach ($accounts as $account) {
            $accounts_dropdown[$account['id']] = $account['name'];
        }
        $view_data['accounts_dropdown'] = $accounts_dropdown;
        $view_data['companies_dropdown'] =  array("0" => "choose company") +$this->Company_model->get_dropdown_list(array("name"));
        $view_data['countries_dropdown'] = $this->Country_model->get_dropdown_list(array("country_name"));
        $view_data['Regions_dropdown'] = $this->Regions_model->get_dropdown_list(array("region"), "region");
        $view_data['has_all_permission'] =
        ($this->login_user->user_type === "staff" &&  $this->login_user->company_access ==="all" && $this->login_user->department ==0);
        $view_data['can_add_supplier'] = $this->can_add_supplier();
        $view_data['finance_manager_id']=array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", );
        $view_data['model_info'] = $this->Supplier_model->get_one($this->request->getPost('id'));
        return $this->template->view('aleelo_plugin\Views/supplier/modal_form', $view_data);
    }
    function get_regions_by_country() {
        $country_id = $this->request->getPost('country_id');
    
        if ($country_id) {
            $regions = $this->Regions_model->get_all_where(array("country_id" => $country_id))->getResult();
            
            $result = array();
            foreach ($regions as $region) {
                $result[$region->id] = $region->region;
            }
    
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
    }
    
    function save() {
        // $this->access_only_team_members();
    
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required"
        ));
    
        $id = $this->request->getPost('id');
    
        $supplier_data = array(
            "supplier_name" => $this->request->getPost('name'),
            "address" => $this->request->getPost('address'),
            "phone" => $this->request->getPost('phone'),
            "email" => $this->request->getPost('email'),
            "company" => $this->request->getPost('company_id'),
            "deleted" => 0,
            "Country" => $this->request->getPost('country'),
            "region"=> $this->request->getPost('district'),
            "website" => $this->request->getPost('website'),
            "Account_Payable" => $this->request->getPost('Account_Payable'),
        );
    
        // Optional file uploads (if needed in future)
        // $target_path = get_setting("timeline_file_path");
        // $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "supplier");
        // $new_files = unserialize($files_data);
    
        // if ($id) {
        //     $existing = $this->Supplier_model->get_one($id);
        //     $new_files = update_saved_files($target_path, $existing->files ?? "", $new_files);
        // }
    
        // $supplier_data["files"] = serialize($new_files);
    
        $save_id = $this->Supplier_model->ci_save($supplier_data, $id);
    
        if ($save_id) {
            $options = array("id" => $save_id);
            $item_info = $this->Supplier_model->get_details($options)->getRow();
    
            echo json_encode([
                "success" => true,
                "id" => $save_id,
                "data" => $this->_make_row($item_info), // full row like in expense
                "message" => app_lang("record_saved")
            ]);
        }}
    
    

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->request->getPost('id');
        $company_info = $this->Supplier_model->get_one($id);
      

        if ($this->request->getPost('undo')) {
            if ($this->Supplier_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Supplier_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data() {
        if (!$this->can_view_supplier()) {
            app_redirect("forbidden");
        }
        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
        $options = array(
        
            "can_view_own_company_client" => $this->can_view_own_company_client(),
            "company_id" => $this->request->getPost('company_id'),


        );

        $all_options = append_server_side_filtering_commmon_params($options);

        $result = $this->Supplier_model->get_details($all_options);

  if (get_array_value($all_options, "server_side")) {
            $list_data = get_array_value($result, "data");
        } else {
            $list_data = $result->getResult();
            $result = array();
        }
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Supplier_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }
       private function _make_row($data) {
        $edit='';
        $delete='';
        if ($this->can_edit_supplier()) {
            $edit = modal_anchor(get_uri("supplier/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_company'), "data-post-id" => $data->id));
        }
        if ($this->can_delete_supplier()) {
            $delete = 
             js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("supplier/delete"), "data-action" => "delete"));    }
        
        return array(
            $data->id,
            $data->supplier_name,
            $data->company_name,
            $data->phone,
            $data->email,
            $data->country_name,
            $data->region_name,
            $data->address,
            $data->Website,
            $edit.$delete,
        );
    }
    private function make_access_permissions_view_data() {

        $access_invoice = $this->get_access_info("invoice");
        $view_data["show_invoice_info"] = (get_setting("module_invoice") && $access_invoice->access_type == "all") ? true : false;

        $access_estimate = $this->get_access_info("estimate");
        $view_data["show_estimate_info"] = (get_setting("module_estimate") && $access_estimate->access_type == "all") ? true : false;

        $view_data["show_estimate_request_info"] = (get_setting("module_estimate_request") && $access_estimate->access_type == "all") ? true : false;

        $access_order = $this->get_access_info("order");
        $view_data["show_order_info"] = (get_setting("module_order") && $access_order->access_type == "all") ? true : false;

        $access_proposal = $this->get_access_info("proposal");
        $view_data["show_proposal_info"] = (get_setting("module_proposal") && $access_proposal->access_type == "all") ? true : false;

        $access_ticket = $this->get_access_info("ticket");
        $view_data["show_ticket_info"] = (get_setting("module_ticket") && $access_ticket->access_type == "all") ? true : false;

        $access_contract = $this->get_access_info("contract");
        $view_data["show_contract_info"] = (get_setting("module_contract") && $access_contract->access_type == "all") ? true : false;
        $view_data["show_project_info"] = !$this->has_all_projects_restricted_role();

        $access_subscription = $this->get_access_info("subscription");
        $view_data["show_subscription_info"] = (get_setting("module_subscription") && $access_subscription->access_type == "all") ? true : false;

        return $view_data;
    }
}
