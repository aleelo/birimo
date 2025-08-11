<?php

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */

use App\Libraries\Template;
use app\Controllers\App_Controller;
use aleelo_plugin\Controllers\Security_Controller_Plugin;
use aleelo_plugin\Libraries\Pdf; // Adjust the namespace based on your project structure

if (!function_exists('get_demo_setting')) {

    function get_demo_setting($key = "")
    {
        $config = new aleelo_plugin\Config\Demo();

        $setting_value = get_array_value($config->app_settings_array, $key);
        if ($setting_value !== NULL) {
            return $setting_value;
        } else {
            return "";
        }
    }
}
if (!function_exists('get_company_icon')) {

    function get_company_icon($company_id, $type = "", $return_html = false) {
        $Company_model = model('App\Models\Company_model');
        $company_info = $Company_model->get_one($company_id);
        $only_file_path = get_setting('only_file_path');

        if (isset($company_info->company_icon) && $company_info->company_icon) {
            $file = unserialize($company_info->company_icon);
            if (is_array($file)) {
                $file = get_array_value($file, 0);

                if ($return_html) {
                    return "<img class='max-logo-size' src='" . get_source_url_of_file($file, get_setting('system_file_path'), 'thumbnail', $only_file_path, $only_file_path) . "' alt='...' />";
                } else {
?>
                    <img class="max-logo-size" src="<?php echo get_source_url_of_file($file, get_setting("system_file_path"), "thumbnail", $only_file_path, $only_file_path); ?>" alt="..." />
                <?php
                }
            }
        } else {
            $logo = $type . "_logo";
            if (!get_setting($logo)) {
                $logo = "invoice_logo";
            }

            if ($return_html) {
                return "<img class='max-logo-size' src='" . get_file_from_setting($logo, $only_file_path) . "' alt='...' />";
            } else {
                ?>
                <img class="max-logo-size" src="<?php echo get_file_from_setting($logo, $only_file_path); ?>" alt="..." />
<?php
            }
        }
    }
}
if (!function_exists('get_expense_status_label')) {

    function get_expense_status_label($expense_info, $return_html = true, $extra_classes = "")
    {
        $expense_status_class = "bg-secondary";
        $status = "unpaid";

        $tolarance = get_paid_status_tolarance(); // Use same function as invoices
        $expense_value = floor(($expense_info->amount + $expense_info->tax_id + $expense_info->tax_id2) * 100) / 100;
        $paid_amount = $expense_info->expense_paid ?? 0;

        if ($paid_amount <= 0) {
            $expense_status_class = "bg-warning";
            $status = "unpaid";
        } else if ($paid_amount >= $expense_value - $tolarance) {
            $expense_status_class = "bg-success";
            $status = "fully_paid";
        } else {
            $expense_status_class = "bg-primary";
            $status = "partially_paid";
        }

        $label = "<span class='mt0 badge $expense_status_class $extra_classes'>" . app_lang($status) . "</span>";

        return $return_html ? $label : $status;
    }
}

if (!function_exists('income_vs_expenses_widget')) {

    function income_vs_expenses_widget($custom_class = "")
{
    $Expenses_model = model("App\Models\Expenses_model");
    $ci = new Security_Controller_Plugin(false);

    $info = $ci->Expenses_model->get_income_expenses_info();

    $today = explode('-', get_today_date());
    $current_year = get_array_value($today, 0);
    $previous_year = $current_year - 1;

    $view_data["current_year_info"] = $ci->Expenses_model->get_income_expenses_info(["year" => $current_year]);
    $view_data["previous_year_info"] = $ci->Expenses_model->get_income_expenses_info(["year" => $previous_year]);

    $view_data["income"] = $info->income ?? 0;
    $view_data["expenses"] = $info->expenses ?? 0;
    $view_data["custom_class"] = $custom_class;

    return view("expenses/income_expenses_widget", $view_data);
}

}

if (!function_exists('update_custom_fields_changes')) {

    function update_custom_fields_changes($related_to_type, $related_to_id, $changes, $activity_log_id = 0)
    {
        // if ($changes && count($changes)) {
        if (is_array($changes) && count($changes)) {
            $ci = new App_Controller();

            $related_to_data = new \stdClass();

            $log_type = "";
            $log_for = "";
            $log_type_title = "";
            $log_for_id = "";

            if ($related_to_type == "tasks") {
                $related_to_data = $ci->Tasks_model->get_one($related_to_id);
                $log_type = "task";
                $log_for = "project";
                $log_type_title = $related_to_data->title;
                $log_for_id = $related_to_data->project_id;
            }

            $log_data = array(
                "action" => "updated",
                "log_type" => $log_type,
                "log_type_title" => $log_type_title,
                "log_type_id" => $related_to_id,
                "log_for" => $log_for,
                "log_for_id" => $log_for_id
            );

            if ($activity_log_id) {
                $before_changes = array();

                //we have to combine with the existing changes of activity logs
                $activity_log = $ci->Activity_logs_model->get_one($activity_log_id);
                $activity_logs_changes = unserialize($activity_log->changes ? $activity_log->changes : "");
                if (is_array($activity_logs_changes)) {
                    foreach ($activity_logs_changes as $key => $value) {
                        $before_changes[$key] = array("from" => get_array_value($value, "from"), "to" => get_array_value($value, "to"));
                    }
                }

                $log_data["changes"] = serialize(array_merge($before_changes, $changes));

                if ($activity_log->action != "created") {
                    $ci->Activity_logs_model->update_where($log_data, array("id" => $activity_log_id));
                }
            } else {
                $log_data["changes"] = serialize($changes);
                return $ci->Activity_logs_model->ci_save($log_data);
            }
        }
    }
}

if (!function_exists('get_team_member_profile_link')) {

    function get_team_member_profile_link($id = 0, $name = "", $attributes = array())
    {
        $ci = new Security_Controller_Plugin(false);
        if ($ci->login_user->user_type === "staff") {
            return anchor("team_member/view/" . $id, $name ? $name : "", $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }
}


 function _get_total_paid_view($expense_id)
{
    $view_data["expense_id"] = $expense_id;
    $view_data["total_paid"] = $this->Expense_payments_model->get_total_paid($expense_id);
    $view_data["expense_info"] = $this->Expenses_model->get_one($expense_id);

    return $this->template->view("aleelo_plugin/Views/expenses/expense_payments/total_paid_section", $view_data, true);
}

/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('demo_load_css')) {

    function demo_load_css(array $array)
    {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url(PLUGIN_URL_PATH . "Demo/$uri") . "?v=$version' />";
        }
    }
}
if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download")
    {
        $pdf = new Pdf("invoice");
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(false);
        }
        $pdf->setPrintFooter(false);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
        $pdf->setInvoiceData($estimate_data); // 

        $pdf->AddPage();

        if ($estimate_data) {

            $estimate_data["mode"] = clean_data($mode);

            $html = view("aleelo_plugin\Views/estimates/estimate_pdf_view", $estimate_data);
            if ($mode != "html") {
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = app_lang("estimate") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $pdf->SetTitle($pdf_file_name);
                $pdf->Output($pdf_file_name, "I");
                exit;
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}
if (!function_exists('prepare_estimate_pdff')) {

    function prepare_estimate_pdff($estimate_data, $mode = "download")
    {
        $pdf = new Pdf("invoice_pdf");
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(false);
        }
        $pdf->setPrintFooter(true);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
        $pdf->setInvoiceData($estimate_data); // 

        $pdf->AddPage();

        if ($estimate_data) {

            $estimate_data["mode"] = clean_data($mode);

            $html = view("aleelo_plugin\Views/estimates/estimate_pdf", $estimate_data);
            if ($mode != "html") {
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $estimate_info = get_array_value($estimate_data, "estimate_info");
            $pdf_file_name = app_lang("estimate") . "-$estimate_info->id.pdf";

            if ($mode === "download") {
                $pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $pdf->SetTitle($pdf_file_name);
                $pdf->Output($pdf_file_name, "I");
                exit;
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}
if (!function_exists('get_estimate_making_data')) {

    function get_estimate_making_data($estimate_id)
    {
        validate_numeric_value($estimate_id);
        $ci = new Security_Controller_Plugin();

        $estimate_info = $ci->Estimates_model->get_details(array("id" => $estimate_id))->getRow();
        if ($estimate_info) {
            $data['estimate_info'] = $estimate_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['estimate_info']->client_id);
            $data['company_info'] = $ci->Company_model->get_one($data['client_info']->company_id);
            $data['users_info'] = $ci->Users_models->get_one($data['company_info']->finance_manager_id);

            $finance_manager_info = $ci->db->table('team_member_job_info')
                ->select('*') // Select user_id and job_title_en
                ->where('user_id', $data['company_info']->finance_manager_id)
                ->get()
                ->getRow();

            $data['finance_manager_info'] = $finance_manager_info;

            $data['estimate_items'] = $ci->Estimate_items_model->get_details(array("estimate_id" => $estimate_id))->getResult();
            $data["estimate_total_summary"] = $ci->Estimates_model->get_estimate_total_summary($estimate_id);
            $data['estimate_status_label'] = get_estimate_status_label($estimate_info);

            $data['estimate_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "estimates", "show_in_estimate" => true, "related_to_id" => $estimate_id))->getResult();
            return $data;
        }
    }
}


/**
 * get all data to make an invoice
 * 
 * @param Int $invoice_id
 * @return array
 */
if (!function_exists('get_invoice_making_data')) {

    function get_invoice_making_data($invoice_id)
    {
        $ci = new Security_Controller_Plugin();
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->getRow();
        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Invoice_items_model->get_details_with_sections(array("invoice_id" => $invoice_id))->getResult();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);
            $data['company_info'] = $ci->Company_model->get_one($data['client_info']->company_id);
            $data['users_info'] = $ci->Users_models->get_one($data['company_info']->finance_manager_id);
            $data['signature'] = get_signature_image_html($data['company_info']->finance_manager_id,"",true);
            $finance_manager_info = $ci->db->table('team_member_job_info')
                ->select('*') // Select user_id and job_title_en
                ->where('user_id', $data['company_info']->finance_manager_id)
                ->get()
                ->getRow();
            $data['finance_manager_info'] = $finance_manager_info;
            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->getResult();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->getResult();
            return $data;
        }
    }
}
if (!function_exists('get_signature_image_html')) {

    function get_signature_image_html($user_id, $style = "max-width: 150px;", $is_pdf = false)
    {
        $db = \Config\Database::connect();

        $finance_manager = $db->table('team_member_job_info')
            ->where('user_id', $user_id)
            ->get()
            ->getRow();

        if (!$finance_manager || empty($finance_manager->signature)) {
            return "m";
        }

        $raw_signature = $finance_manager->signature;
        $signature_file_name = null;

        $first = @unserialize($raw_signature);

        if (is_array($first)) {
            $signature_file_name = $first[0]['file_name'] ?? $first['file_name'] ?? null;
        } elseif (is_string($first)) {
            $second = @unserialize($first);
            if (is_array($second)) {
                $signature_file_name = $second[0]['file_name'] ?? $second['file_name'] ?? null;
            }
        }

        if (!$signature_file_name && is_string($raw_signature) && !str_contains($raw_signature, '{')) {
            $signature_file_name = $raw_signature;
        }
        if ($is_pdf) {
            $image_path = FCPATH . 'files/signature/' . $signature_file_name;
            if (file_exists($image_path)) {
                return '<img src="' . $image_path . '" alt="Company Logo" style="width:250;" />';
            }
        } else {

            $signature_url = base_url('files/signature/' . $signature_file_name);
            return "<img src='{$signature_url}' alt='Signature' style='{$style}'>";
        }

        return ',';
    }
}
if (!function_exists('get_invoice_making_data_delivery_note')) {

    function get_invoice_making_data_delivery_note($invoice_id)
    {
        $ci = new Security_Controller_Plugin();
        $invoice_info = $ci->Invoices_model->get_details(array("id" => $invoice_id))->getRow();
        if ($invoice_info) {
            $data['invoice_info'] = $invoice_info;
            $data['client_info'] = $ci->Clients_model->get_one($data['invoice_info']->client_id);
            $data['invoice_items'] = $ci->Invoice_items_model->get_details(array("invoice_id" => $invoice_id))->getResult();
            $data['invoice_status_label'] = get_invoice_status_label($invoice_info);
            $data["invoice_total_summary"] = $ci->Invoices_model->get_invoice_total_summary($invoice_id);
            $data['company_info'] = $ci->Company_model->get_one($data['client_info']->company_id);
            $data['users_info'] = $ci->Users_models->get_one($data['company_info']->Director_id);
            $finance_manager_info = $ci->db->table('team_member_job_info')
                ->select('*') // Select user_id and job_title_en
                ->where('user_id', $data['company_info']->Director_id)
                ->get()
                ->getRow();
            $project_id = $ci->db->table('projects')
                ->select('*') // Select user_id and job_title_en
                ->where('id', $invoice_info->project_id)
                ->get()
                ->getRow();

            $data['finance_manager_info'] = $finance_manager_info;
            $data['project'] = $project_id;
            $data['invoice_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "invoices", "show_in_invoice" => true, "related_to_id" => $invoice_id))->getResult();
            $data['client_info']->custom_fields = $ci->Custom_field_values_model->get_details(array("related_to_type" => "clients", "show_in_invoice" => true, "related_to_id" => $data['invoice_info']->client_id))->getResult();
            return $data;
        }
    }
}


if (!function_exists('prepare_invoice_pdf')) {

    function prepare_invoice_pdf($invoice_data, $mode = "download")
    {
        $pdf = new Pdf("invoice_pdf");

        //if setting is desable then don't show header
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(true);
        }

        $pdf->setPrintFooter(true);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
        $pdf->setInvoiceData($invoice_data);

        $pdf->AddPage();

        // Get the page width in user units (default is millimeters)
        $pageWidthInUserUnits = $pdf->getPageWidth();

        $pageWidthInPixels = ($pageWidthInUserUnits / 25.4) * 92;

        //show background image on first page
        if (get_setting("set_invoice_pdf_background_only_on_first_page")) {
            $pdf->setPrintHeader(false);
        }

        if ($invoice_data) {

            $invoice_data["mode"] = clean_data($mode);

            $html = view("aleelo_plugin\Views/invoices/invoice_pdf", $invoice_data);

            if ($mode != "html") {
                $html = rebuild_html($html, $pageWidthInPixels);
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $invoice_id = $invoice_info->display_id;
            $pdf_file_name = preg_replace('/[^A-Za-z0-9\-]/', '-', $invoice_id) . ".pdf";

            if ($mode === "download") {
                $pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $pdf->SetTitle($pdf_file_name);
                $pdf->Output($pdf_file_name, "I");
                exit;
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}

if (!function_exists('prepare_invoice_pdf_delivery_note')) {
    function prepare_invoice_pdf_delivery_note($invoice_data, $mode = "download")
    {
        $pdf = new Pdf("invoice");
        $ci = new Security_Controller_Plugin();


        // Force no header/footer margins
        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        $pdf->SetCellPadding(0.5);
        $pdf->setImageScale(1.42);

        // Set all margins to 0
        $pdf->SetMargins(0, 10, 0);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(150);

        $pdf->SetAutoPageBreak(true, 15); // Remove automatic page breaks
        $pdf->setInvoiceData($invoice_data); // 

        $pdf->AddPage();


        // Get page width calculations
        $pageWidthInUserUnits = $pdf->getPageWidth();
        $pageWidthInPixels = ($pageWidthInUserUnits / 25.4) * 92;

        if ($invoice_data) {
            $invoice_data["mode"] = clean_data($mode);

            // Modified delivery note view
            $html = view("aleelo_plugin\Views/invoices/delivery_note_pdf", $invoice_data);

            if ($mode != "html") {
                $html = rebuild_html($html, $pageWidthInPixels);

                // Start writing at the very top of the page
                $pdf->SetY(0);
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $project = $ci->db->table('projects')
                ->select('*')
                ->where('id', $invoice_info->project_id)
                ->get()
                ->getRow();

            $company_name = isset($invoice_info->company_name) ? $invoice_info->company_name : "Unknown Company";

            if ($project) {
                $invoice_id = " delivery_note " . $company_name . " " . $project->title;
            } else {
                $invoice_id =  "delivery_note " . $company_name;
            }
            $pdf_file_name = $invoice_id . ".pdf";

            if ($mode === "download") {
                $pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $pdf->SetTitle($pdf_file_name);
                $pdf->Output($pdf_file_name, "I");
                exit;
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}
if (!function_exists('prepare_invoice_pdf_view')) {

    function prepare_invoice_pdf_view($invoice_data, $mode = "download")
    {
        $pdf = new Pdf("invoice");

        //if setting is desable then don't show header
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(false);
        }

        $pdf->setPrintFooter(false);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
        $pdf->setInvoiceData($invoice_data); // 

        $pdf->AddPage();

        // Get the page width in user units (default is millimeters)
        $pageWidthInUserUnits = $pdf->getPageWidth();

        $pageWidthInPixels = ($pageWidthInUserUnits / 25.4) * 92;

        //show background image on first page
        if (get_setting("set_invoice_pdf_background_only_on_first_page")) {
            $pdf->setPrintHeader(false);
        }

        if ($invoice_data) {

            $invoice_data["mode"] = clean_data($mode);

            $html = view("aleelo_plugin\Views/invoices/invoice_pdf_view", $invoice_data);

            if ($mode != "html") {
                $html = rebuild_html($html, $pageWidthInPixels);
                $pdf->writeHTML($html, true, false, true, false, '');
            }

            $invoice_info = get_array_value($invoice_data, "invoice_info");
            $invoice_id = $invoice_info->display_id;
            $pdf_file_name = preg_replace('/[^A-Za-z0-9\-]/', '-', $invoice_id) . ".pdf";

            if ($mode === "download") {
                $pdf->Output($pdf_file_name, "D");
            } else if ($mode === "send_email") {
                $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
                $pdf->Output($temp_download_path, "F");
                return $temp_download_path;
            } else if ($mode === "view") {
                $pdf->SetTitle($pdf_file_name);
                $pdf->Output($pdf_file_name, "I");
                exit;
            } else if ($mode === "html") {
                return $html;
            }
        }
    }
}






if (!function_exists("get_logo_url_company")) {

    function get_logo_url_company($department_id)
    {
        return get_file_from_setting_company($department_id);
    }
}

if (!function_exists("get_file_from_setting")) {

    function get_file_from_setting_company($department_id = "", $only_file_path_with_slash = false, $file_path = "", $company_id = null)
    {
        if ($department_id) {
            // Load the Company_model
            $Company_model = model('App\Models\Company_model');
            $company_info = $Company_model->get_one($department_id);
            // Check if the company_id matches the department_id
            if ($company_info && $company_info->id == $department_id) {
                // Return the logo column if it exists
                if ($company_info->logo) {
                    $file = @unserialize($company_info->logo);
                    if (is_array($file)) {
                        return get_source_url_of_file($file, get_setting("system_file_path"), "thumbnail", $only_file_path_with_slash, $only_file_path_with_slash);
                    }
                }
            }
        }

        // if (!$department_id) {
        //     $setting_value = get_setting($department_id);
        //     if ($setting_value) {
        //         $file_path = $file_path ? $file_path : get_setting("system_file_path");

        //         $file = @unserialize($setting_value);
        //         if (is_array($file)) {

        //             // Show full-size thumbnail for signin page background
        //             $show_full_size_thumbnail = false;
        //             if ($department_id == "signin_page_background") {
        //                 $show_full_size_thumbnail = true;
        //             }

        //             return get_source_url_of_file($file, $file_path, "thumbnail", $only_file_path_with_slash, $only_file_path_with_slash, $show_full_size_thumbnail);
        //         } else {
        //             if ($only_file_path_with_slash) {
        //                 return "/" . ($file_path . $setting_value);
        //             } else {
        //                 return get_file_uri($file_path . $setting_value);

        //             }
        //         }
        //     }
        // }
    }
}
// if (!function_exists('prepare_estimate_pdf')) {

//     function prepare_estimate_pdf($estimate_data, $mode = "download") {
//         $pdf = new Pdf();
//         if (!get_setting("enable_background_image_for_invoice_pdf")) {
//             $pdf->setPrintHeader(false);
//         }

//         $pdf->setPrintHeader(false);
//         $pdf->setPrintFooter(false);
//         $pdf->SetCellPadding(1.5);
//         $pdf->setImageScale(1.42);
//         $pdf->AddPage();

//         if ($estimate_data) {

//             $estimate_data["mode"] = clean_data($mode);

//             $html = view("aleelo_plugin\Views/estimates/estimate_pdf", $estimate_data);
//             if ($mode != "html") {
//                 $pdf->writeHTML($html, true, false, true, false, '');
//             }

//             $estimate_info = get_array_value($estimate_data, "estimate_info");
//             $pdf_file_name = app_lang("estimate") . "-$estimate_info->id.pdf";

//             if ($mode === "download") {
//                 $pdf->Output($pdf_file_name, "D");
//             } else if ($mode === "send_email") {
//                 $temp_download_path = getcwd() . "/" . get_setting("temp_file_path") . $pdf_file_name;
//                 $pdf->Output($temp_download_path, "F");
//                 return $temp_download_path;
//             } else if ($mode === "view") {
//                 $pdf->SetTitle($pdf_file_name);
//                 $pdf->Output($pdf_file_name, "I");
//                 exit;
//             } else if ($mode === "html") {
//                 return $html;
//             }
//         }
//     }
// }


if (!function_exists('demo_get_source_url')) {

    function demo_get_source_url($demo_file = "")
    {
        if (!$demo_file) {
            return "";
        }

        try {
            $file = unserialize($demo_file);
            if (is_array($file)) {
                return get_source_url_of_file($file, get_demo_setting("demo_file_path"), "thumbnail", false, false, true);
            }
        } catch (\Exception $ex) {
        }
    }
}
