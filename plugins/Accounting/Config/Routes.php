<?php

namespace Config;

$routes = Services::routes();

$routes->get('accounting', 'Accounting::index', ['namespace' => 'Accounting\Controllers']);
$routes->get('accounting/(:any)', 'Accounting::$1', ['namespace' => 'Accounting\Controllers']);
$routes->post('expense_payments/save', '\aleelo_plugin\Controllers\Expense_payments::save');

$routes->post('accounting/(:any)', 'Accounting::$1', ['namespace' => 'Accounting\Controllers']);
