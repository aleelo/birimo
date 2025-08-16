<?php
defined('PLUGINPATH') or exit('No direct script access allowed');

/*
  Plugin Name: Accounting and Bookkeeping
  Description: This plugin offers the ability to automate many processes which will not only save time but will also ensure accuracy and efficiency with financial reports.
  Version: 1.0.0
  Requires at least: 3.0
  Author: Aleelo Solutions
  Author URI: aleelo solution
*/
use App\Controllers\Security_Controller;

if(!defined('ACCOUNTING_REVISION')){
    define('ACCOUNTING_REVISION', 10010);    
}
if(!defined('ACCOUTING_EXPORT_XLSX')){
    define('ACCOUTING_EXPORT_XLSX', 'plugins/Accounting/uploads/export_xlsx/');    
}
if(!defined('ACCOUTING_IMPORT_ITEM_ERROR')){
    define('ACCOUTING_IMPORT_ITEM_ERROR', 'plugins/Accounting/uploads/import_item_error/');    
}
if(!defined('TEMP_FOLDER')){
    define('TEMP_FOLDER', ROOTPATH . 'files/temp' . '/');    
}

//add menu item to left menu
app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {

    $accounting_submenu = array();
    $ci = new Security_Controller(false);
    $permissions = $ci->login_user->permissions;

    if ($ci->login_user->is_admin || acc_has_permission('acc_can_view_dashboard') || acc_has_permission('acc_can_view_banking') || acc_has_permission('acc_can_view_transaction') || acc_has_permission('acc_can_view_register') || acc_has_permission('acc_can_view_journal_entry') || acc_has_permission('acc_can_view_transfer') || acc_has_permission('acc_can_view_account') || acc_has_permission('acc_can_view_reconcile') || acc_has_permission('acc_can_view_budget') || acc_has_permission('acc_can_view_report') || acc_has_permission('acc_can_view_setting')) {

        if(acc_has_permission('acc_can_view_dashboard')){
            $accounting_submenu["accounting_dashboard"] = array(
                "name" => "acc_dashboard", 
                "url" => "accounting/dashboard", 
                "class" => "home"
            );
        }

        // if(acc_has_permission('acc_can_view_banking')){
        //     $accounting_submenu["accounting_banking"] = array(
        //         "name" => "acc_banking", 
        //         "url" => "accounting/banking?group=bank_accounts", 
        //         "class" => "repeat"
        //     );
        // }
        
        if(acc_has_permission('acc_can_view_transaction')){
            $accounting_submenu["accounting_transaction"] = array(
                "name" => "acc_transactions", 
                "url" => "accounting/transaction?group=sales", 
                "class" => "repeat"
            );
        }
        
        // if(acc_has_permission('acc_can_view_register')){
        //     $accounting_submenu["accounting_registers"] = array(
        //         "name" => "acc_registers", 
        //         "url" => "accounting/registers", 
        //         "class" => "list"
        //     );
        // }
        
        // if(acc_has_permission('acc_can_view_journal_entry')){
        //     $accounting_submenu["accounting_journal_entry"] = array(
        //         "name" => "acc_journal_entry", 
        //         "url" => "accounting/journal_entry", 
        //         "class" => "repeat"
        //     );
        // }
        
        if(acc_has_permission('acc_can_view_transfer')){
            $accounting_submenu["accounting_transfer"] = array(
                "name" => "acc_transfer", 
                "url" => "accounting/transfer", 
                "class" => "home"
            );
        }
        
        if(acc_has_permission('acc_can_view_account')){
            $accounting_submenu["accounting_chart_of_accounts"] = array(
                "name" => "acc_chart_of_accounts", 
                "url" => "accounting/chart_of_accounts", 
                "class" => "home"
            );
        }
        
        // if(acc_has_permission('acc_can_view_reconcile')){
        //     $accounting_submenu["accounting_reconcile"] = array(
        //         "name" => "acc_reconcile", 
        //         "url" => "accounting/reconcile", 
        //         "class" => "home"
        //     );
        // }
        
        // if(acc_has_permission('acc_can_view_budget')){
        //     $accounting_submenu["accounting_budget"] = array(
        //         "name" => "acc_budget", 
        //         "url" => "accounting/budget", 
        //         "class" => "home"
        //     );
        // }
        
        if(acc_has_permission('acc_can_view_report')){
            $accounting_submenu["accounting_reports"] = array(
                "name" => "acc_reports", 
                "url" => "accounting/report", 
                "class" => "home"
            );
        }
        
        if(acc_has_permission('acc_can_view_setting')){
            $accounting_submenu["accounting_setting"] = array(
                "name" => "acc_setting", 
                "url" => "accounting/setting?group=general", 
                "class" => "home"
            );
        }

        $sidebar_menu["accounting"] = array(
            "name" => "als_accounting",
            "url" => "accounting/dashboard",
            "class" => "book",
            "submenu" => $accounting_submenu,
            "position" => 3,
        );
    }


    return $sidebar_menu;
});


//install dependencies
register_installation_hook("Accounting", function ($item_purchase_code) {    
    // include PLUGINPATH . "Accounting/lib/gtsverify.php";
    require_once __DIR__ . '/install.php';
});

//activation
register_activation_hook("Accounting", function () {    
    require_once __DIR__ . '/install.php';
});


//update plugin
register_update_hook("Accounting", function () {
    require_once __DIR__ . '/install.php';
});

//uninstallation: remove data from database
// register_uninstallation_hook("Accounting", function () {    
//     require_once __DIR__ . '/uninstall.php';
// });
// app_hooks()->add_action('app_hook_accounting_init', function (){
//     require_once __DIR__ .'/lib/gtsslib.php';
//     $lic_accounting = new AccountingLic();
//     $accounting_gtssres = $lic_accounting->verify_license(true);    
//     if(!$accounting_gtssres || ($accounting_gtssres && isset($accounting_gtssres['status']) && !$accounting_gtssres['status'])){
//         echo '<strong>YOUR ACCOUNTING & BOOKKEEPING PLUGIN FAILED ITS VERIFICATION. PLEASE <a href="/index.php/Plugins">REINSTALL</a> OR CONTACT SUPPORT</strong>';
//         exit();
//     } 
// });
// app_hooks()->add_action('app_hook_uninstall_plugin_Accounting', function (){
//     require_once __DIR__ .'/lib/gtsslib.php';
//     $lic_accounting = new AccountingLic();
//     $lic_accounting->deactivate_license();    
// });

/**
 * init add head component
 */
app_hooks()->add_action('app_hook_head_extension', function (){
    $viewuri = $_SERVER['REQUEST_URI'];

  if (!(strpos($viewuri, 'accounting') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/css/custom.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
  }

  if (!(strpos($viewuri, 'accounting/new_journal_entry') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . base_url('plugins/Accounting/assets/plugins/handsontable/chosen.css') . '"  rel="stylesheet" type="text/css" />';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
  }

  if (!(strpos($viewuri, 'accounting/rp_') === false) || !(strpos($viewuri, 'accounting/report') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/css/report.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . base_url('plugins/Accounting/assets/plugins/treegrid/css/jquery.treegrid.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . base_url('plugins/Accounting/assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
  }

  if (!(strpos($viewuri, 'accounting/reconcile_account') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/css/reconcile_account.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
  }

  if (!(strpos($viewuri, 'accounting/dashboard') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . base_url('plugins/Accounting/assets/css/dashboard.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
  }

  if (!(strpos($viewuri, 'accounting/setting') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/css/setting.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
  }

  if (!(strpos($viewuri, 'accounting/budget') === false) || !(strpos($viewuri, 'accounting/user_register_view') === false)) {
    echo '<link href="' . base_url('plugins/Accounting/assets/plugins/handsontable/handsontable.full.min.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
    echo '<link href="' . base_url('plugins/Accounting/assets/plugins/handsontable/chosen.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/handsontable/handsontable.full.min.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
    echo '<link href="' . base_url('plugins/Accounting/assets/css/box_loading.css') . '?v=' . ACCOUNTING_REVISION . '"  rel="stylesheet" type="text/css" />';

  }
});

/**
 * init add footer component
 */
app_hooks()->add_action('app_hook_head_extension', function(){
    $viewuri = $_SERVER['REQUEST_URI'];

  if (!(strpos($viewuri, 'accounting') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/js/accounting_main.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
  }

  if (!(strpos($viewuri, 'accounting/setting?group=general') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/js/setting/general.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
  }

  if (!(strpos($viewuri, 'accounting/new_rule') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/js/setting/new_rule.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
  }

  if (!(strpos($viewuri, 'accounting/banking?group=plaid_new_transaction') === false)) {
        echo '<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>';
    }
    
  if (!(strpos($viewuri, 'accounting/new_journal_entry') === false) || !(strpos($viewuri, 'accounting/user_register_view') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/handsontable/chosen.jquery.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/handsontable/handsontable-chosen-editor.js') . '"></script>';
  }

  if (!(strpos($viewuri, 'accounting/reconcile') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/js/reconcile/reconcile.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
  }

  if(!(strpos($viewuri,'accounting/rp_') === false)){
        echo '<script src="'. base_url('plugins/Accounting/assets/plugins/treegrid/js/jquery.treegrid.min.js').'?v=' . ACCOUNTING_REVISION.'"></script>';
        // echo '<script src="' . base_url('plugins/Accounting/assets/plugins/jspdf/jspdf.umd.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        
        echo '<script src="' . base_url('plugins/Accounting/assets/plugins/html2pdf/html2pdf.bundle.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        echo '<script src="' . base_url('plugins/Accounting/assets/plugins/tableHTMLExport/tableHTMLExport.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
        echo '<script src="' . base_url('plugins/Accounting/assets/js/report/main.js') . '?v=' . ACCOUNTING_REVISION . '"></script>';
    }

  if (!(strpos($viewuri, '/accounting/dashboard') === false)) {
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/highcharts.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/modules/export-data.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/modules/exporting.js') . '"></script>';
    echo '<script src="' . base_url('plugins/Accounting/assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
  }
});

app_hooks()->add_action("app_hook_data_update", function($data){
    $Accounting_model = model("Accounting\Models\Accounting_model");
    switch ($data['table']) {
        case get_db_prefix().'invoice_items':
                if (get_setting('acc_invoice_automatic_conversion') == 1) {
                    $Accounting_model->automatic_invoice_conversion('', $data['id']);
                }
            break;
        case get_db_prefix().'invoices':
                if (get_setting('acc_invoice_automatic_conversion') == 1) {
                    $Accounting_model->automatic_invoice_conversion($data['id'], '');
                }
            break;
        case get_db_prefix().'invoice_payments':
                if (get_setting('acc_payment_automatic_conversion') == 1) {
                    $Accounting_model->automatic_payment_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expenses':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    
                    $Accounting_model->automatic_expense_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expense_payments':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    
                    $Accounting_model->automatic_expense_payment_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expenses_emp':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    
                    $Accounting_model->automatic_emp_expense_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expense_payments_emp':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    
                    $Accounting_model->automatic_emp_expense_payment_conversion($data['id']);
                }
            break;
        default:
            // code...
            break;
    }

    return $data;
});

app_hooks()->add_action("app_hook_data_insert", function($data){
    $Accounting_model = model("Accounting\Models\Accounting_model");
    switch ($data['table']) {
        case get_db_prefix().'invoice_items':
                if (get_setting('acc_invoice_automatic_conversion') == 1) {
                    $Accounting_model->automatic_invoice_conversion('', $data['id']);
                }
            break;
        case get_db_prefix().'invoice_payments':
                if (get_setting('acc_payment_automatic_conversion') == 1) {
                    $Accounting_model->automatic_payment_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expenses':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    $Accounting_model->automatic_expense_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expense_payments':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    $Accounting_model->automatic_expense_payment_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expenses_emp':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    $Accounting_model->automatic_emp_expense_conversion($data['id']);
                }
            break;
        case get_db_prefix().'expense_payments_emp':
                if (get_setting('acc_expense_automatic_conversion') == 1) {
                    $Accounting_model->automatic_emp_expense_payment_conversion($data['id']);
                }
            break;
        default:
            // code...
            break;
    }

    return $data;
});

app_hooks()->add_action("app_hook_data_delete", function($data){
    $Accounting_model = model("Accounting\Models\Accounting_model");
    switch ($data['table']) {
        case get_db_prefix().'invoices':
                $Accounting_model->delete_invoice_convert($data['id']);
            break;
        case get_db_prefix().'invoice_items':
                if (get_setting('acc_invoice_automatic_conversion') == 1) {
                    $Accounting_model->automatic_invoice_conversion('', $data['id']);
                }
            break;
        case get_db_prefix().'invoice_payments':
                $Accounting_model->delete_convert($data['id'], 'payment');
            break;
        case get_db_prefix().'expenses':
                $Accounting_model->delete_convert($data['id'], 'expense');
            break;
        case get_db_prefix().'expense_payments':
                $Accounting_model->delete_convert($data['id'], 'vendor_expense_payment');
            break;
        case get_db_prefix().'expenses_emp':
                $Accounting_model->delete_convert($data['id'], 'expense_emp');
            break;
        case get_db_prefix().'expense_payments_emp':
                $Accounting_model->delete_convert($data['id'], 'emp_expense_payment');
            break;
        case get_db_prefix().'items':
                $Accounting_model->delete_convert($data['id'], 'opening_stock');
            break;
        default:
            // code...
            break;
    }

    return $data;
});

/**
 * { after add purchase order action }
 */
app_hooks()->add_action('after_purchase_order_add', function($id){

    if ($id) {
        if (get_setting('acc_pur_order_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_order_conversion($id);
        }

    }
    return $id;
});

/**
 * { after update purchase order action }
 */
app_hooks()->add_action('after_pur_order_updated', function($id){
    if ($id) {
        if (get_setting('acc_pur_order_automatic_conversion') == 1) {            
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_order_conversion($id);
        }

    }

    return $id;
});


/**
 * { after update purchase order action }
 */
app_hooks()->add_action('after_purchase_order_approve', function($id){
    if ($id) {
        if (get_setting('acc_pur_order_automatic_conversion') == 1) {            
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_order_conversion($id);
        }

    }

    return $id;
});

/**
 * { before delete PO action }
 */
app_hooks()->add_action('before_pur_order_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");

        $Accounting_model->delete_convert($id, 'purchase_order');
    }
    return $id;
});

/**
 * { after payment purchase invoice }
 */
app_hooks()->add_action('after_payment_pur_invoice_added', function($id){ 
    if ($id) {
        if (get_setting('acc_pur_payment_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_payment_conversion($id);
        }

    }
    return $id;
});

/**
 * { after payment purchase invoice }
 */
app_hooks()->add_action('after_purchase_payment_approve', function($id){ 
    if ($id) {
        if (get_setting('acc_pur_payment_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_payment_conversion($id);
        }

    }
    return $id;
});


/**
 * { after delete payment of purchase invoice }
 */
app_hooks()->add_action('after_payment_pur_invoice_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");

        $Accounting_model->delete_convert($id, 'purchase_payment');
    }
    return $id;
});

/**
 * { after purchase invoice added action }
 */
app_hooks()->add_action('after_pur_invoice_added', function($id){ 
    if ($id) {
        if (get_setting('acc_pur_invoice_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_invoice_conversion($id);
        }

    }
    return $id;
});

/**
 * { after purchase invoice updated action }
 */
app_hooks()->add_action('after_pur_invoice_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_pur_invoice_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_purchase_invoice_conversion($id);
        }

    }
    return $id;
});

/**
 * { after delete payment of purchase invoice }
 */
app_hooks()->add_action('after_pur_invoice_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");

        $Accounting_model->delete_convert($id, 'purchase_invoice');
    }
    return $id;
});

// inventory
/**
 * { after goods receipt added action }
 */
app_hooks()->add_action('after_wh_goods_receipt_added', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_import_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_import_conversion($id);
        }

    }
    return $id;
});

/**
 * { after goods receipt added action }
 */
app_hooks()->add_action('after_wh_goods_receipt_approve', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_import_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_import_conversion($id);
        }

    }
    return $id;
});


/**
 * { after goods receipt updated action }
 */
app_hooks()->add_action('after_wh_goods_receipt_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_import_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_import_conversion($id);
        }

    }
    return $id;
});

/**
 * { after goods receipt delete action }
 */
app_hooks()->add_action('before_goods_receipt_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'stock_import');

    }
    return $id;
});

/**
 * { after goods receipt added action }
 */
app_hooks()->add_action('after_wh_goods_delivery_approve', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_export_conversion($id);
        }

    }
    return $id;
});

/**
 * { after goods receipt added action }
 */
app_hooks()->add_action('after_wh_goods_delivery_added', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_export_conversion($id);
        }

    }
    return $id;
});

/**
 * { after goods receipt updated action }
 */
app_hooks()->add_action('after_wh_goods_delivery_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_stock_export_conversion($id);
        }

    }
    return $id;
});

/**
 * { after goods receipt delete action }
 */
app_hooks()->add_action('before_goods_delivery_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'stock_export');
    }
    return $id;
});


/**
 * { after loss adjustment added action }
 */
app_hooks()->add_action('after_wh_loss_adjustment_approve', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_loss_adjustment_conversion($id);
        }

    }
    return $id;
});
/**
 * { after loss adjustment added action }
 */
app_hooks()->add_action('after_wh_loss_adjustment_added', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_loss_adjustment_conversion($id);
        }

    }
    return $id;
});

/**
 * { after loss adjustment updated action }
 */
app_hooks()->add_action('after_wh_loss_adjustment_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_wh_stock_export_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_loss_adjustment_conversion($id);
        }

    }
    return $id;
});

/**
 * { after loss adjustment delete action }
 */
app_hooks()->add_action('before_loss_adjustment_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'loss_adjustment');
    }
    return $id;
});

/**
 * { before item delete action }
 */
app_hooks()->add_action('delete_item_on_woocommerce', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'opening_stock');
    }
    return $id;
});

// fixed equipment

/**
 * { after asset added action }
 */
app_hooks()->add_action('after_fe_asset_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_asset_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_asset_conversion($id);
        }

    }
    return $id;
});

/**
 * { after asset updated action }
 */
app_hooks()->add_action('after_fe_asset_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_asset_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_asset_conversion($id);
        }

    }
    return $id;
});

/**
 * { after asset updated action }
 */
app_hooks()->add_action('after_fe_asset_updated_v2', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_asset_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_asset_conversion($id);
        }

    }
    return $id;
});


/**
 * { after asset delete action }
 */
app_hooks()->add_action('after_fe_asset_deleted', function($id){ 
    if ($id) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->delete_convert($id, 'fe_asset');
            $Accounting_model->delete_convert($id, 'fe_component');
            $Accounting_model->delete_convert($id, 'fe_consumable');

    }
    return $id;
});


/**
 * { after license added action }
 */
app_hooks()->add_action('after_fe_license_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_license_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_license_conversion($id);
        }

    }
    return $id;
});

/**
 * { after license updated action }
 */
app_hooks()->add_action('after_fe_license_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_license_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_license_conversion($id);
        }

    }
    return $id;
});

/**
 * { after license delete action }
 */
app_hooks()->add_action('after_fe_license_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'fe_license');
    }
    return $id;
});

/**
 * { after consumable added action }
 */
app_hooks()->add_action('after_fe_consumable_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_consumable_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_consumable_conversion($id);
        }

    }
    return $id;
});

/**
 * { after consumable updated action }
 */
app_hooks()->add_action('after_fe_consumable_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_consumable_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_consumable_conversion($id);
        }

    }
    return $id;
});

/**
 * { after component added action }
 */
app_hooks()->add_action('after_fe_component_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_component_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_component_conversion($id);
        }

    }
    return $id;
});

/**
 * { after component updated action }
 */
app_hooks()->add_action('after_fe_component_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_component_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_component_conversion($id);
        }

    }
    return $id;
});


/**
 * { after maintenance added action }
 */
app_hooks()->add_action('after_fe_maintenance_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_maintenance_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_maintenance_conversion($id);
        }

    }
    return $id;
});

/**
 * { after maintenance updated action }
 */
app_hooks()->add_action('after_fe_maintenance_updated', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_maintenance_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_maintenance_conversion($id);
        }

    }
    return $id;
});

/**
 * { after maintenance delete action }
 */
app_hooks()->add_action('after_fe_maintenance_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'fe_maintenance');
    }
    return $id;
});


// manufacturing

/**
 * { after manufacturing order status changed action }
 */
app_hooks()->add_action('manufacturing_order_status_changed', function($data){ 
    if(isset($data['data']['status'])){
        if ($data['data']['status'] == 'done') {
            if (get_setting('acc_mrp_manufacturing_order_automatic_conversion') == 1) {
                $Accounting_model = model("Accounting\Models\Accounting_model");

                $Accounting_model->automatic_manufacturing_order_conversion($data['id']);
            }
        }else{
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->delete_convert($data['id'], 'manufacturing_order');
        }
    }
    return $data;
});

/**
 * { after manufacturing order delete action }
 */
app_hooks()->add_action('after_manufacturing_order_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'manufacturing_order');
    }
    return $id;
});


// payslip

/**
 * { before payslip delete action }
 */
app_hooks()->add_action('before_payslip_deleted', function($id){ 
    if ($id) {
        $Accounting_model = model("Accounting\Models\Accounting_model");
        $Accounting_model->delete_convert($id, 'payslip');
    }
    return $id;
});

/**
 * { after depreciation added action }
 */
app_hooks()->add_action('after_fe_depreciation_added', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_depreciation_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_depreciation_conversion($id);
        }

    }
    return $id;
});

/**
 * { after depreciation added action }
 */
app_hooks()->add_action('after_fe_depreciation_added_v2', function($id){ 
    if ($id) {
        if (get_setting('acc_fe_depreciation_automatic_conversion') == 1) {
            $Accounting_model = model("Accounting\Models\Accounting_model");
            $Accounting_model->automatic_fe_depreciation_conversion($id);
        }

    }
    return $id;
});