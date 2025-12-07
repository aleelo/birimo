<span class="invoice-meta text-default"><?php 
if (isset($estimate_info->custom_fields) && $estimate_info->custom_fields) {
    foreach ($estimate_info->custom_fields as $field) {
        if ($field->value) {
            echo "<span>" . $field->custom_field_title . ": " . view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value)) . "</span><br />";
        }
    }
}
// Branch functionality removed - not needed in vendors plugin
// Replace line breaks in the address with spaces
// $address = str_replace(array("\n", "\r"), ' ', $branch_info->address);
?><br /><?php
// echo $address; ?><br /><?php 
// echo $branch_info->phone; ?><br /><?php 
// echo $branch_info->email; ?><br /><?php 
if (isset($company_info->website)) {
    echo $company_info->website; 
}
?>
</span>