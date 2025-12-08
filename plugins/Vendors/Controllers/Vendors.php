<?php

namespace Vendors\Controllers;

use Vendors\Controllers\Security_Controller_Plugin_vendor;

class Vendors extends Security_Controller_Plugin_vendor
{
    function __construct()
    {
        parent::__construct();

        // Gate 1: Must have CRM permission
        if (!$this->can_manage_vendors()) {
            app_redirect("forbidden");
        }

        // Gate 2: (only after CRM is OK) must also have Supplier permission
        if (!$this->can_hide_vendors()) {
            app_redirect("forbidden");
        }
    }

    // Branch, Company, Country, Region functionality removed - not needed in vendors plugin
    // private function _get_branch_dropdown() removed

    function index()
    {
        $this->access_only_team_members();

        // Use can_hide_supplier() as the main view permission.
        if (!$this->can_hide_vendors()) {
            app_redirect("forbidden");
        }

        // Branch, Company, Country, Region functionality removed - not needed in vendors plugin
        // All branch/company/country/region dropdowns removed

        // Pass permission results to the view for conditdddional rendering of buttons
        $view_data['can_add_new_vendor'] = $this->can_add_new_vendor();

        return $this->template->rander("Vendors\Views/vendor/index", $view_data);
    }

    function modal_form()
    {
        $this->access_only_team_members();
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $id = $this->request->getPost('id');
        $project_id = $this->request->getPost('project_id');

        $model_info = $this->Vendor_model->get_one($id);


        $model_info->project_id = $model_info->project_id ? $model_info->project_id : $project_id;

        // if ($id) {
        //     // Check permission for updating an existing supplier.
        //     if (!$this->can_update_supplier()) {
        //         app_redirect("forbidden");
        //     }
        // } else {
        //     // Check permission for adding a new supplier.
        //     if (!$this->can_add_new_supplier()) {
        //         app_redirect("forbidden");
        //     }
        // }


        if (!isset($view_data['projects_dropdown'])) {
            $view_data['projects_dropdown'] = $this->get_projects_dropdown(true);
        }

        // Branch, Company, Country, Region functionality removed - not needed in vendors plugin
        // All related view data removed
        $view_data['finance_manager_id'] = array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name", "last_name"), "id",);
        $view_data['model_info'] = $this->Vendor_model->get_one($id);


        return $this->template->view('Vendors\Views/vendor/modal_form', $view_data);
    }


    function get_projects_dropdown($return_as_list_data = false)
    {
        // Projects_model removed - not needed in vendors plugin
        $options = array();

        // Dhammaan projects haddii aan client la xulin
        // $projects_dropdown = $this->Projects_model->get_id_and_text_dropdown(array("title"), $options, "-");
        $projects_dropdown = array();
        if ($return_as_list_data) {
            return $projects_dropdown;
        } else {
            echo json_encode($projects_dropdown);
        }
    }

    // Country and Region functionality removed - not needed in vendors plugin
    function get_regions_by_country()
    {
        echo json_encode([]);
    }

    function save()
    {
        $this->access_only_team_members();
        $id = $this->request->getPost('id');
        // $project_id = $this->request->getPost('vendor_project_id');

        // if ($id) {
        //     // Check permission for updating an existing supplier.
        //     if (!$this->can_update_supplier()) {
        //         app_redirect("forbidden");
        //     }
        // } else {
        //     // Check permission for adding a new supplier.
        //     if (!$this->can_add_new_supplier()) {
        //         app_redirect("forbidden");
        //     }
        // }

        $this->validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required",
            "name" => "required"
        ));

        $supplier_data = array(
            "vendor_name" => $this->request->getPost('name'),
            "address" => $this->request->getPost('address'),
            "phone" => $this->request->getPost('phone'),
            "email" => $this->request->getPost('email'),
            // "branch_id" => $this->request->getPost('branch_id'), // Branch functionality removed
            "deleted" => 0,
            // Country and Region removed - not needed in vendors plugin
            // "Country" => $this->request->getPost('country'),
            // "region" => $this->request->getPost('district'),
            "website" => $this->request->getPost('website'),
        );

        $save_id = $this->Vendor_model->ci_save($supplier_data, $id);

        if ($save_id) {
            $options = array("id" => $save_id);
            $item_info = $this->Vendor_model->get_details($options)->getRow();

            echo json_encode([
                "success" => true,
                "id" => $save_id,
                "data" => $this->_make_row($item_info),
                "message" => app_lang("record_saved")
            ]);
        }
    }

    function delete()
    {
        $this->access_only_team_members();
        // Check permission to delete a supplier.
        // if (!$this->can_delete_supplier()) {
        //     app_redirect("forbidden");
        // }

        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->request->getPost('id');
        // Company functionality removed - not needed in vendors plugin
        // $company_info = $this->Vendor_model->get_one($id);
        $vendor_info = $this->Vendor_model->get_one($id);


        if ($this->request->getPost('undo')) {
            if ($this->Vendor_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Vendor_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }

    function list_data()
    {
        $this->access_only_team_members();
        // Check permission for viewing suppliers before fetching data.
        // if (!$this->can_hide_supplier()) {
        //     echo json_encode(array("data" => array()));
        //     return;
        // }

        $custom_fields = $this->Custom_fields_model->get_available_fields_for_table("clients", $this->login_user->is_admin, $this->login_user->user_type);
        // Branch functionality removed - not needed in vendors plugin
        $options = array(
            // "branch_id" => $this->get_user_branch_access_view(),
            // "branch_ids" => $this->request->getPost("branch_id"),
        );

        $all_options = append_server_side_filtering_commmon_params($options);
        $result = $this->Vendor_model->get_details($all_options);

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

    private function _row_data($id)
    {
        $options = array("id" => $id);
        $data = $this->Vendor_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data)
    {
        $edit = '';
        $delete = '';

        // Conditionally show edit button based on can_update_supplier() permission.
        if ($this->can_update_vendor()) {
            $edit = modal_anchor(get_uri("vendors/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_vendor'), "data-post-id" => $data->id));
        }

        // Conditionally show delete button based on can_delete_supplier() permission.
        if ($this->can_delete_vendor()) {
            $delete = js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("vendors/delete"), "data-action" => "delete"));
        }

        return array(
            $data->id,
            $data->vendor_name,
            // $data->company_name, // Company name removed - not needed in vendors plugin
            $data->phone,
            $data->email,
            // $data->country_name, // Country removed - not needed in vendors plugin
            // $data->region_name, // Region removed - not needed in vendors plugin
            $data->address,
            $data->website,
            $edit . $delete,
        );
    }

    private function make_access_permissions_view_data()
    {
        // This function is not used in this context. Permissions are checked directly.
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
