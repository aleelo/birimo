<?php
require_once __DIR__ .'/gtsslib.php';
if(!isset($item_purchase_code) || $item_purchase_code == ''){
    echo json_encode(array("success" => false, "message" => 'Please enter a valid purchase code.'));
    exit();
}
$license_code = strip_tags(trim($item_purchase_code));
$client_name = 'risecrm';
$lic_accounting = new AccountingLic();
$activate_response = $lic_accounting->activate_license($license_code, $client_name);
$msg = '';
$status = false;
if(empty($activate_response)){
  $msg = 'Server is unavailable.';
}else{
  $msg = $activate_response['message'];
  $status = $activate_response['status'];
}
if (!$status) {
    echo json_encode(array("success" => false, "message" => $msg));
    exit();
}