<?php

namespace Config;

$routes = Services::routes();

$routes->get('demo', 'Demo::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('demo/(:any)', 'Demo::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('demo_settings', 'Demo_settings::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('demo_settings/(:any)', 'Demo_settings::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('demo_settings/(:any)', 'Demo_settings::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('clients', 'Clients::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('clients/(:any)', 'Clients::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('clients/(:any)', 'Clients::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('quotation', 'Estimate::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('quotation/(:any)', 'Estimate::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('quotation/(:any)', 'Estimate::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('quotations', 'Estimates::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('quotations/(:any)', 'Estimates::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('quotations/(:any)', 'Estimates::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('quotation_requests', 'Estimate_requests::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('quotation_requests/(:any)', 'Estimate_requests::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('quotation_requests/(:any)', 'Estimate_requests::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('tasks', 'Tasks::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('tasks/(:any)', 'Tasks::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('tasks/(:any)', 'Tasks::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('branch', 'Branch::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('branch/(:any)', 'Branch::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('branch/(:any)', 'Branch::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('signing', 'Signing::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('signing/(:any)', 'Signing::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('signing/(:any)', 'Signing::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('projects', 'Projects::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('projects/(:any)', 'Projects::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('projects/(:any)', 'Projects::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('proposals', 'Proposals::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('proposals/(:any)', 'Proposals::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('proposals/(:any)', 'Proposals::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('proposal_templates', 'Proposal_templates::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('proposal_templates/(:any)', 'Proposal_templates::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('proposal_templates/(:any)', 'Proposal_templates::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('/', 'Dashboard::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('dashboard', 'Dashboard::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('dashboard/(:any)', 'Dashboard::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('dashboard/(:any)', 'Dashboard::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('items', 'Items::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('items/(:any)', 'Items::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('items/(:any)', 'Items::$1', ['namespace' => 'Sales_and_crm\Controllers']); 

$routes->get('item_categories', 'item_categories::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('item_categories/(:any)', 'item_categories::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('item_categories/(:any)', 'item_categories::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('invoices', 'invoices::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('invoices/(:any)', 'invoices::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('invoices/(:any)', 'invoices::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('invoice_payments', 'Invoice_payments::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('invoice_payments/(:any)', 'Invoice_payments::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('invoice_payments/(:any)', 'Invoice_payments::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('invoice_supplier_payments', 'Invoice_supplier_payments::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('invoice_supplier_payments/(:any)', 'Invoice_supplier_payments::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('invoice_supplier_payments/(:any)', 'Invoice_supplier_payments::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('team_members', 'Team_members::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('team_members/(:any)', 'Team_members::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('team_members/(:any)', 'Team_members::$1', ['namespace' => 'Sales_and_crm\Controllers']);

$routes->get('supplier', 'Supplier::index', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->get('supplier/(:any)', 'Supplier::$1', ['namespace' => 'Sales_and_crm\Controllers']);
$routes->post('supplier/(:any)', 'Supplier::$1', ['namespace' => 'Sales_and_crm\Controllers']);

// $routes->get('projects/all_projects', 'Projects::index', ['namespace' => 'Sales_and_crm\Controllers']);
// $routes->get('projects/all_projects/(:any)', 'Projects::$1', ['namespace' => 'Sales_and_crm\Controllers']);
// $routes->post('projects/all_projects/(:any)', 'Projects::$1', ['namespace' => 'Sales_and_crm\Controllers']);

// $routes->get('contracts', 'Contracts::index', ['namespace' => 'Sales_and_crm\Controllers']);
// $routes->get('contracts/(:any)', 'Contracts::$1', ['namespace' => 'Sales_and_crm\Controllers']);
// $routes->post('contracts/(:any)', 'Contracts::$1', ['namespace' => 'Sales_and_crm\Controllers']);
