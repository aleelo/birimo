<?php

/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */

use aleelo_plugin\Controllers\Security_Controller_Plugin;
use App\Libraries\Pdf; // Adjust the namespace based on your project structure

if (!function_exists('get_demo_setting')) {

    function get_demo_setting($key = "") {
        $config = new aleelo_plugin\Config\Demo();

        $setting_value = get_array_value($config->app_settings_array, $key);
        if ($setting_value !== NULL) {
            return $setting_value;
        } else {
            return "";
        }
    }

}
if (!function_exists('get_team_member_profile_link')) {

    function get_team_member_profile_link($id = 0, $name = "", $attributes = array()) {
        $ci = new Security_Controller_Plugin(false);
        if ($ci->login_user->user_type === "staff") {
            return anchor("team_member/view/" . $id, $name ? $name : "", $attributes);
        } else {
            return js_anchor($name, $attributes);
        }
    }
}
/**
 * link the css files 
 * 
 * @param array $array
 * @return print css links
 */
if (!function_exists('demo_load_css')) {

    function demo_load_css(array $array) {
        $version = get_setting("app_version");

        foreach ($array as $uri) {
            echo "<link rel='stylesheet' type='text/css' href='" . base_url(PLUGIN_URL_PATH . "Demo/$uri") . "?v=$version' />";
        }
    }

}
if (!function_exists('prepare_estimate_pdf')) {

    function prepare_estimate_pdf($estimate_data, $mode = "download") {
        $pdf = new Pdf("invoice");
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(false);
        }
        $pdf->setPrintFooter(false);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
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

    function prepare_estimate_pdff($estimate_data, $mode = "download") {
        $pdf = new Pdf("invoice");
        if (!get_setting("enable_background_image_for_invoice_pdf")) {
            $pdf->setPrintHeader(false);
        }
        $pdf->setPrintFooter(false);
        $pdf->SetCellPadding(1.5);
        $pdf->setImageScale(1.42);
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

    function get_estimate_making_data($estimate_id) {
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

    function demo_get_source_url($demo_file = "") {
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