<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\Security_Controller_Plugin;
use App\Controllers\Security_Controller;

class Screen_size extends Security_Controller_Plugin {

    function __construct() {
        parent::__construct();

    }

    function index() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
            
        ));
        return $this->template->rander('aleelo_plugin\Views\Screen_size\index');
 }


    function save(){
        $id = $this->request->getPost('id');

        $data = array(
            "screen_size" => $this->request->getPost('name'),
        );
     
        $save_id = $this->Screen_size_model->ci_save($data,$id);
        if ($save_id) {
            echo json_encode(array("success" => true, 'data' => $data, 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }
    function modal_form(){
       
        $this->validate_submitted_data(array(
            "id" => "numeric"
            
        ));
        $view_data['model_info'] = $this->Screen_size_model->get_one($this->request->getPost('id'));

        $view_data['label_column'] = "col-md-2";
        $view_data['field_column'] = "col-md-10";
        return $this->template->view('aleelo_plugin\Views\Screen_size\modal_form',$view_data);
    }


    function list_data($type = "", $id = 0) {
       

        validate_numeric_value($id);

        $this->validate_submitted_data(array(
            "category_id" => "numeric"
        ));

        $options = array(
            "category_id" => $this->request->getPost("category_id"),
           // "category_id" => $this->request->getPost("category_id"),


        );
        $options["created_by"] = $this->login_user->id;
        // if ($type == "project" && $id) {
        //     $options["created_by"] = $this->login_user->id;
            
        // } else if ($type == "client" && $id) {
        //     $options["client_id"] = $id;
        // } else if ($type == "user" && $id) {
        //     $options["user_id"] = $id;
        // } else {
        //    
        //     $options["my_notes"] = true;
        // }

        $list_data = $this->Screen_size_model->get_details($options)->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
       // error_log(" " . $data->id);

    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Screen_size_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
      
       

        //only creator and admin can edit/delete notes
        $actions = modal_anchor(get_uri("Screen_size/modal_form/" . $data->id), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('Screen_sizetment_details'), "data-modal-title" => app_lang("Screen_sizetment"), "data-post-id" => $data->id));
     


        return array(
            $data->id,
           $data->screen_size,
         
           
            $actions,
           // format_to_relative_time($data->created_date),

           
        );
        
    }
    function delete() {
     

        $this->validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->request->getPost('id');

        $Screen_size_info = $this->Screen_size_model->get_one($id);
       // $this->validate_access_to_note($Screen_size_info, true);

        if ($this->Screen_size_model->delete($id)) {
            //delete the files
            $file_path = get_setting("timeline_file_path");
            

            echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
        }
    }
}
