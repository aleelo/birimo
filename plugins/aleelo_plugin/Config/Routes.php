<?php

namespace Config;

$routes = Services::routes();

$routes->get('demo', 'Demo::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('demo/(:any)', 'Demo::$1', ['namespace' => 'aleelo_plugin\Controllers']);

$routes->get('demo_settings', 'Demo_settings::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('demo_settings/(:any)', 'Demo_settings::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('demo_settings/(:any)', 'Demo_settings::$1', ['namespace' => 'aleelo_plugin\Controllers']);

$routes->get('assigning_items', 'Assigning_items::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('assigning_items/(:any)', 'Assigning_items::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('assigning_items/(:any)', 'Assigning_items::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('expense_payments/modal_form/(:num)', 'Expense_payments::modal_form/$1');
$routes->get('expense_payments/modal_form', 'Expense_payments::modal_form/$1');
$routes->post('expense_payments/modal_form/(:num)', 'Expense_payments::modal_form/$1'); // optional, badanaa GET ayaa fura modals
$routes->post('expense_payments/save', 'Expense_payments::save');
$routes->get('expense_payments/index/(:num)', 'Expense_payments::index/$1');
$routes->match(['get', 'post'], 'expense_payments/modal_form/(:num)', 'Expense_payments::modal_form/$1');
$routes->post('expense_payments/save', 'Expense_payments::save', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->match(['get', 'post'], 'expense_payments/modal_form', 'Expense_payments::modal_form', ['namespace' => 'aleelo_plugin\Controllers']);


$routes->get('items_list', 'Items_list::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('items_list/(:any)', 'Items_list::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('items_list/(:any)', 'Items_list::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->group('', ['namespace' => 'aleelo_plugin\Controllers'], function($routes) {
    $routes->get('expense_payments', 'Expense_payments::index');
    $routes->get('expense_payments/index/(:num)', 'Expense_payments::index/$1');
    $routes->get('expense_payments/datatable/(:num)', 'Expense_payments::datatable/$1');
    $routes->post('expense_payments/save', 'Expense_payments::save');
    $routes->get('expense_payments/edit/(:num)', 'Expense_payments::edit/$1');
    $routes->post('expense_payments/delete/(:num)', 'Expense_payments::delete/$1');
        $routes->post('expense_payments/get_invoice_payment_amount_suggestion/(:num)', 'Expense_payments::get_invoice_payment_amount_suggestion/$1');

});
$routes->post('expense_payments/save', 'Expense_payments::save', ['namespace' => 'aleelo_plugin\Controllers']);


$routes->get('expense', 'Expense::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('expense/(:any)', 'Expense::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('expense/(:any)', 'Expense::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('expenses', 'Expense::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('expenses/(:any)', 'Expense::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('expenses/(:any)', 'Expense::$1', ['namespace' => 'aleelo_plugin\Controllers']);



$routes->get('project', 'Project::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('project/(:any)', 'Project::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('project/(:any)', 'Project::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('projects', 'Project::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('projects/(:any)', 'Project::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('projects/(:any)', 'Project::$1', ['namespace' => 'aleelo_plugin\Controllers']);


$routes->get('team_member', 'Team_member::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('team_member/(:any)', 'Team_member::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('team_member/(:any)', 'Team_member::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('team_members', 'Team_member::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('team_members/(:any)', 'Team_member::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('team_members/(:any)', 'Team_member::$1', ['namespace' => 'aleelo_plugin\Controllers']);


$routes->get('client', 'Client::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('client/(:any)', 'Client::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('client/(:any)', 'Client::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('clients', 'Client::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('clients/(:any)', 'Client::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('clients/(:any)', 'Client::$1', ['namespace' => 'aleelo_plugin\Controllers']);



$routes->get('Screen_size', 'Screen_size::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('Screen_size/(:any)', 'Screen_size::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('Screen_size/(:any)', 'Screen_size::$1', ['namespace' => 'aleelo_plugin\Controllers']);

$routes->get('estimates', 'estimates::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('estimates/(:any)', 'estimates::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('estimates/(:any)', 'estimates::$1', ['namespace' => 'aleelo_plugin\Controllers']);

$routes->get('tasks', 'Tasks::index', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->get('tasks/(:any)', 'Tasks::$1', ['namespace' => 'aleelo_plugin\Controllers']);
$routes->post('tasks/(:any)', 'Tasks::$1', ['namespace' => 'aleelo_plugin\Controllers']);
