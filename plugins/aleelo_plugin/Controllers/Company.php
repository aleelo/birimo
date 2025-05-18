<?php

namespace aleelo_plugin\Controllers;

use aleelo_plugin\Controllers\Security_Controller_Plugin;

class Company extends Security_Controller_Plugin {

    public $Company_model;

    function __construct() {
        parent::__construct();
        $this->access_only_admin_or_settings_admin();
        $this->Company_model = model('App\Models\Company_model');
    }

    function index() {
        return $this->template->rander("aleelo_plugin\Views/company/index");
    }

    function modal_form() {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['finance_manager_id']=array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", );
        $view_data['model_info'] = $this->Company_model->get_one($this->request->getPost('id'));
        return $this->template->view('aleelo_plugin\Views/company/modal_form', $view_data);
    }

      function view($company_id) {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['finance_manager_id']=array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", );
        $view_data['model_info'] = $this->Company_model->get_one($company_id);
        return $this->template->view('aleelo_plugin\Views/company/view', $view_data);
    }
      function estimate_company($role_id) {
        $this->validate_submitted_data(array(
            "id" => "numeric"
        ));

        $view_data['finance_manager_id']=array("" => "-") + $this->Users_model->get_dropdown_list(array("first_name","last_name"), "id", );
        $view_data['model_info'] = $this->Company_model->get_one($role_id);
        return $this->template->view('aleelo_plugin\Views/company/estimate_company', $view_data);
    }
    
    function save() {
        $this->validate_submitted_data(array(
            "id" => "numeric",
            "name" => "required"
        ));

        $is_default = $this->request->getPost('is_default');
        $data = array(
            "name" => $this->request->getPost('name'),
            "address" => $this->request->getPost('address'),
            "phone" => $this->request->getPost('phone'),
            "email" => $this->request->getPost('email'),
            "website" => $this->request->getPost('website'),
            "vat_number" => $this->request->getPost('vat_number'),
            "is_default" => $is_default ? $is_default : 0,
            "gst_number" => $this->request->getPost('gst_number'),
            "we_accept" => $this->request->getPost('we_accept'),
            "account_no" => $this->request->getPost('account_no'),
            "bank_name" => $this->request->getPost('bank_name'),
            "Condition_company" => $this->request->getPost('Condition_company'),
            "finance_manager_id" => $this->request->getPost('finance_manager_id'),
            "Director_id"=> $this->request->getPost('finance_manager_id'),
        );

        $id = $this->request->getPost('id');
        $company_info = $this->Company_model->get_one($id);

        $save_id = $this->Company_model->ci_save($data, $id);

        if ($save_id) {
            if ($is_default) {
                //remove if there has any other default company
                $this->Company_model->remove_other_default_company($save_id);
            }

            $target_path = get_setting("system_file_path");
            $files_data = move_files_from_temp_dir_to_permanent_dir($target_path, "company_$save_id");
            $logo = unserialize($files_data);

            if ($logo) {
                //delete old file
                if ($company_info->logo) {
                    $files = unserialize($company_info->logo);
                    foreach ($files as $file) {
                        delete_app_files(get_setting("system_file_path"), array($file));
                    }
                }

                $data["logo"] = serialize($logo);

                $this->Company_model->ci_save($data, $save_id);
            }

            echo json_encode(array("success" => true, "data" => $this->_row_data($save_id), 'id' => $save_id, 'message' => app_lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => app_lang('error_occurred')));
        }
    }

    function delete() {
        $this->validate_submitted_data(array(
            "id" => "numeric|required"
        ));

        $id = $this->request->getPost('id');
        $company_info = $this->Company_model->get_one($id);
        if ($company_info->is_default) {
            //default company can't be deleted
            show_404();
        }

        if ($this->request->getPost('undo')) {
            if ($this->Company_model->delete($id, true)) {
                echo json_encode(array("success" => true, "data" => $this->_row_data($id), "message" => app_lang('record_undone')));
            } else {
                echo json_encode(array("success" => false, app_lang('error_occurred')));
            }
        } else {
            if ($this->Company_model->delete($id)) {
                echo json_encode(array("success" => true, 'message' => app_lang('record_deleted')));
            } else {
                echo json_encode(array("success" => false, 'message' => app_lang('record_cannot_be_deleted')));
            }
        }
    }
function save_invoice_settings() {
    // Validate submitted data
    $this->validate_submitted_data(array(
        // "id" => "required|numeric",
        // "site_logo_file" => "required",
        // "invoice_color" => "required",
        // "invoice_item_list_background" => "required",
        // "invoice_footer" => "required"
    ));

    $id =  $this->request->getPost('id');
    $invoice_pdf_background_image = $this->request->getPost('existing_invoice_pdf_background_image'); // Default to the existing value
    $file = $this->request->getFile('invoice_pdf_background_image');

    if ($file && $file->isValid()) {
        $target_path = WRITEPATH . 'uploads/company/';
        if (!is_dir($target_path)) {
            mkdir($target_path, 0755, true); // Create directory if it doesn't exist
        }

        $new_name = $file->getRandomName(); // Generate a random file name
        $file->move($target_path, $new_name); // Move the file to the target directory
        $invoice_pdf_background_image = 'uploads/company/' . $new_name; // Save the relative path
    }
    // Prepare data for saving
    $company_data = array(
        "invoice_color" => $this->request->getPost('invoice_color'),
        "invoice_item_list_background" => $this->request->getPost('invoice_item_list_background'),
        "invoice_footer" => $this->request->getPost('invoice_footer'),
        "invoice_pdf_background_image"=>$invoice_pdf_background_image,
        "enable_background_image_for_invoice_pdf"=> $this->request->getPost('enable_background_image_for_invoice_pdf'),
        "site_logo_file"=> $this->request->getPost('site_logo_file'),

    );

    // Save data to the Company table
    $save_id = $this->Company_model->ci_save($company_data, $id);

    if ($save_id) {
        $options = array("id" => $save_id);
        $company_info = $this->Company_model->get_details($options)->getRow();

        echo json_encode([
            "success" => true,
            "id" => $save_id,
            "data" => $this->_make_row($company_info),
            "message" => app_lang("record_saved")
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => app_lang("error_occurred")
        ]);
    }
}
    function list_data() {
        $list_data = $this->Company_model->get_details()->getResult();
        $result = array();
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _row_data($id) {
        $options = array("id" => $id);
        $data = $this->Company_model->get_details($options)->getRow();
        return $this->_make_row($data);
    }

    private function _make_row($data) {
        $default_company = "";
        $delete = js_anchor("<i data-feather='x' class='icon-16'></i>", array('title' => app_lang('delete_company'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("company/delete"), "data-action" => "delete"));
        if ($data->is_default) {
            $default_company = " <span class='bg-info badge text-white'>" . app_lang('default_company') . "</span>";
            $delete = "";
        }

        $company_logo = get_company_logo($data->id, '', true);

        $company_info = "<div class='mb10 strong'>" . $data->name . $default_company . "</div>" . "<div>" . nl2br($data->address) . "</div>" . "<div>" . $data->phone . "</div>" . "<div>" . $data->email . "</div>" . "<div>" . $data->website . "</div>" . "<div>" . $data->vat_number . "</div>" . "<div>" . $data->gst_number . "</div>";

        return array(
            // $company_logo,
            // $data->name,
              "<a href='#' data-id='$data->id' class='role-row link'>" . $data->name . "</a>",
            "<a class='edit'><i data-feather='sliders' class='icon-16'></i></a>" . modal_anchor(get_uri("company/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "", "title" => app_lang('edit_role'), "data-post-id" => $data->id)).
              
            // modal_anchor(get_uri("company/modal_form"), "<i data-feather='edit' class='icon-16'></i>", array("class" => "edit", "title" => app_lang('edit_company'), "data-post-id" => $data->id)).
             $delete
        );
    }

}

/* End of file company.php */
/* Location: ./app/controllers/company.php */