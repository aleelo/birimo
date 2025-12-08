<?php

defined('PLUGINPATH') or exit('No direct script access allowed');

use App\Controllers\Security_Controller;
use Vendors\Controllers\Security_Controller_Plugin_vendor;

/*
  Plugin Name: Vendors
  Description: Vendor bills, items, and payments module.
  Version: 1.0
  Requires at least: 3.0
  Author: Author Name
  Author URL: aziiza
*/

// ========== Left menu ==========
app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {
    $ci = new Security_Controller(false);
    $c2 = new Security_Controller_Plugin_vendor(false);

    // Vendor list (master section)
    if ($c2->can_manage_vendors()) {
        $sidebar_menu["vendor_list"] = [
            "name"     => "vendor_list",
            "class"    => "circle",
            "position" => 3,
            "icon"     => "fa fa-shopping-cart",
        ];
    }

    // Top-level Vendors entry (hidden per request)
    // if ($c2->can_manage_vendors() && $c2->can_hide_vendors()) {
    //     $sidebar_menu["vendors"] = [
    //         "name"     => "vendors",
    //         "class"    => "circle",
    //         "position" => 3,
    //         "url"      => "vendors",
    //         "icon"     => "fa fa-shopping-cart",
    //     ];
    // }

    // Vendor Bills
    if ($c2->can_manage_vendors() && $c2->can_hide_vendor_bills()) {
        $sidebar_menu["vendor_bills"] = [
            "name"     => "vendor_bills",
            "class"    => "circle",
            "position" => 3,
            "url"      => "vendor_bills",
            "icon"     => "fa fa-file-invoice-dollar",
        ];
    }

    // Vendor Items (hidden per request)
    // if ($c2->can_manage_vendors() && $c2->can_hide_vendor_items()) {
    //     $sidebar_menu["vendor_items"] = [
    //         "name"     => "vendor_items",
    //         "class"    => "circle",
    //         "position" => 3,
    //         "url"      => "vendor_items",
    //         "icon"     => "fa fa-box",
    //     ];
    // }

    // Vendor Bill Payments
    if ($c2->can_manage_vendors() && $c2->can_hide_vendor_bill_payments()) {
        $sidebar_menu["vendor_bill_payment"] = [
            "name"     => "vendor_bill_payment",
            "class"    => "circle",
            "position" => 3,
            "url"      => "vendor_bill_payment",
            "icon"     => "fa fa-cash-register",
        ];
    }

    return $sidebar_menu;
});


// ========== Routes ==========
$routes = service('routes');

$routes->group('', ['namespace' => 'Vendors\Controllers'], function ($routes) {
    // Vendors
    $routes->get('vendors', 'Vendors::index', ['namespace' => 'Vendors\Controllers']);
    $routes->get('vendors/(:any)', 'Vendors::$1', ['namespace' => 'Vendors\Controllers']);
    $routes->post('vendors/(:any)', 'Vendors::$1', ['namespace' => 'Vendors\Controllers']);

    // Vendor Items
    $routes->get('vendor_items', 'Vendor_items::index', ['namespace' => 'Vendors\Controllers']);
    $routes->get('vendor_items/(:any)', 'Vendor_items::$1', ['namespace' => 'Vendors\Controllers']);
    $routes->post('vendor_items/(:any)', 'Vendor_items::$1', ['namespace' => 'Vendors\Controllers']);

    // Vendor Bills
    $routes->get('vendor_bills', 'Vendor_bills::index', ['namespace' => 'Vendors\Controllers']);
    $routes->get('vendor_bills/(:any)', 'Vendor_bills::$1', ['namespace' => 'Vendors\Controllers']);
    $routes->post('vendor_bills/(:any)', 'Vendor_bills::$1', ['namespace' => 'Vendors\Controllers']);

    // Vendor Bill Payments
    $routes->get('vendor_bill_payment', 'Vendor_bill_payment::index', ['namespace' => 'Vendors\Controllers']);
    $routes->get('vendor_bill_payment/(:any)', 'Vendor_bill_payment::$1', ['namespace' => 'Vendors\Controllers']);
    $routes->post('vendor_bill_payment/(:any)', 'Vendor_bill_payment::$1', ['namespace' => 'Vendors\Controllers']);
});


// ========== Admin settings (optional) ==========
app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {
    // Reuse your DESCON bucket if that’s your convention
    $settings_menu["descon"] = array(
        array("name" => "company",            "url" => "company"),
        array("name" => "branch",             "url" => "branch"),
        array("name" => "item_categories",    "url" => "item_categories"),
        array("name" => "payment_methods",    "url" => "payment_methods"),
        array("name" => "taxes",              "url" => "taxes"),
        array("name" => "expense_categories", "url" => "expense_categories"),
    );

    usort($settings_menu["descon"], function ($a, $b) {
        return strcmp($a["name"], $b["name"]);
    });

    return $settings_menu;
});

// ========== Role permissions extension (safe) ==========
// If you don't have a Vendors\Security_Controller_Plugin_vendor yet, DO NOT reference it.
function vendors_get_role_permissions($role_id)
{
    $db = db_connect('default');
    $role = $db->table('roles')->where('id', $role_id)->get()->getRow();
    if ($role && isset($role->permissions)) {
        $permissions = @unserialize($role->permissions);
        if ($permissions === false) {
            $permissions = json_decode($role->permissions, true);
        }
        return is_array($permissions) ? $permissions : [];
    }
    return [];
}

app_hooks()->add_action('app_hook_role_permissions_extension', function ($role_id) {
    $role_permissions = vendors_get_role_permissions($role_id);
    $order = $role_permissions['order'] ?? "";
?>
    <li>
        <span data-feather="key" class="icon-14 ml-20"></span>
        <h5><?php echo app_lang("can_access_orders"); ?></h5>
        <div>
            <?php
            echo form_radio([
                "id"    => "order_no",
                "name"  => "order_permission",
                "value" => "",
                "class" => "form-check-input",
            ], $order, ($order === ""));
            ?>
            <label for="order_no"><?php echo app_lang("no"); ?></label>
        </div>
        <div>
            <?php
            echo form_radio([
                "id"    => "order_yes",
                "name"  => "order_permission",
                "value" => "all",
                "class" => "form-check-input",
            ], $order, ($order === "all"));
            ?>
            <label for="order_yes"><?php echo app_lang("yes"); ?></label>
        </div>
    </li>
<?php
});

app_hooks()->add_filter('app_filter_role_permissions_save_data', function ($permissions) {
    $request = \Config\Services::request();
    $permissions["order"] = $request->getPost('order_permission');
    return $permissions;
});

// ========== Install / Update / Uninstall ==========
register_installation_hook("Vendors", function ($item_purchase_code) {
    // Verify purchase code if needed …

    $db = db_connect('default');
    $dbprefix = get_db_prefix();

    // Only create tables specific to Vendors here.
    // (Leaving your branch table sample here commented to avoid duplicates.)
    /*
    $sql_query = "CREATE TABLE IF NOT EXISTS `{$dbprefix}vendor_extra` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `note` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->query($sql_query);
    */
});

app_hooks()->add_filter('app_filter_action_links_of_Vendors', function () {
    return [
        anchor(get_uri("vendor_bills"), "Open Vendors"),
    ];
});

register_update_hook("Vendors", function () {
    echo "Update steps for Vendors…";
});

register_uninstallation_hook("Vendors", function () {
    // Drop Vendors-specific tables if you created any.
    // $db = db_connect('default'); $dbprefix = get_db_prefix();
    // $db->query("DROP TABLE IF EXISTS `{$dbprefix}vendor_extra`;");
});
