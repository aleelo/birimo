<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\Security_Controller_Plugin;

class Supplier extends Security_Controller_Plugin {


    function __construct() {
        parent::__construct();
    }

    function index() {
        return $this->template->rander("aleelo_plugin\Views/supplier/index");
    }

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));
        $view_data['company'] =  array("0" => "choose company") +$this->Company_model->get_dropdown_list(array("name"));

        $view_data['finance_manager_id']=array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", );
        $view_data['model_info'] = $this->Supplier_model->get_one($this->request->getPost('id'));
        return $this->template->view('aleelo_plugin\Views/supplier/modal_form', $view_data);
    }

    function save() {
        $this->access_only_team_members();
    
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
            "deleted" => 0
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
        $list_data = $this->Supplier_model->get_details()->getResult();
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
      
        return array(
            $data->id,
            $data->supplier_name,
            $data->company_name,
            $data->phone,
            $data->email,
            modal_anchor(get_uri("supplier/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_company'), "data-post-id" => $data->id))
            . js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("supplier/delete"), "data-action" => "delete"))
        );
    }

}
