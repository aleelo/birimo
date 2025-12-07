<?php

use App\Controllers\Security_Controller;

use Sales_and_crm\Controllers\Security_Controller_Plugin;
// use Sales_and_crm\Controllers\Security_Controller;
use Sales_and_crm\Libraries\Pdf;
use App\Libraries\Clean_data;
use App\Libraries\Outlook_smtp;
use App\Controllers\Notification_processor;

use App\Controllers\App_Controller;

use App\Libraries\Template;


// use Sales_and_crm\Controllers\Tasks;
// use App\Controllers\Tasks;
// use App\Libraries\Template;


/**
 * get the defined config value by a key
 * @param string $key
 * @return config value
 */




if (!function_exists('get_demo_setting')) {

    function get_demo_setting($key = "")
    {
        $config = new Sales_and_crm\Config\Demo();

        $setting_value = get_array_value($config->app_settings_array, $key);
        if ($setting_value !== NULL) {
            return $setting_value;
        } else {
            return "";
        }
    }
}
// if (!function_exists('get_vendor_bill_status_label')) {
//     function get_vendor_bill_status_label($bill, $return_html = true)
//     {
//         $cls    = "bg-secondary";
//         $status = "draft";
//         $now    = get_my_local_time("Y-m-d");
//         $tol    = get_paid_status_tolarance();

//         // use bill_value, not invoice_value
//         $total   = floor(((float)$bill->bill_value) * 100) / 100; // 2-decimal compare
//         $paid    = (float)$bill->payment_received;
//         $dueDate = $bill->due_date;

//         if ($bill->status === "cancelled") {
//             $cls = "bg-danger";   $status = "cancelled";
//         } elseif ($bill->status === "credited") {
//             $cls = "bg-danger";   $status = "credited";
//         } elseif ($bill->status !== "draft" && $dueDate && $dueDate < $now && $paid < $total - $tol) {
//             $cls = "bg-danger";   $status = "overdue";
//         } elseif ($bill->status !== "draft" && $paid <= 0) {
//             $cls = "bg-warning";  $status = "not_paid";
//         } elseif ($paid >= $total - $tol) {
//             $cls = "bg-success";  $status = "fully_paid";
//         } elseif ($paid > 0 && $paid < $total - $tol) {
//             $cls = "bg-primary";  $status = "partially_paid";
//         } else { // draft
//             $cls = "bg-secondary"; $status = "draft";
//         }

//         $html = "<span class='mt0 badge {$cls} large'>" . app_lang($status) . "</span>";
//         return $return_html ? $html : $status;
//     }
// }


if (!function_exists('get_vendor_bill_status_label')) {
    function get_vendor_bill_status_label($bill, $return_html = true)
    {
        $cls    = "bg-secondary";
        $status = "draft";
        $now    = get_my_local_time("Y-m-d");
        $tol    = get_paid_status_tolarance();

        $total   = floor(((float)$bill->bill_value) * 100) / 100;
        $paid    = (float)$bill->payment_received;
        $dueDate = $bill->due_date;

        if ($bill->status === "cancelled") {
            $cls = "bg-danger";   $status = "cancelled";
        } elseif ($bill->status === "credited") {
            $cls = "bg-danger";   $status = "credited";
        } elseif ($bill->status !== "draft" && $dueDate && $dueDate < $now && $paid < $total - $tol) {
            $cls = "bg-danger";   $status = "overdue";
        } elseif ($bill->status !== "draft" && $paid <= 0) {
            $cls = "bg-warning";  $status = "not_paid";
        } elseif ($paid >= $total - $tol) {
            $cls = "bg-success";  $status = "fully_paid";
        } elseif ($paid > 0 && $paid < $total - $tol) {
            $cls = "bg-primary";  $status = "partially_paid";
        } else {
            $cls = "bg-secondary"; $status = "draft";
        }

        // smaller, rounded, smooth badge
        $html = "<span class='badge status-badge  {$cls}'>"
              . app_lang($status)
              . "</span>";

        return $return_html ? $html : $status;
    }
}
