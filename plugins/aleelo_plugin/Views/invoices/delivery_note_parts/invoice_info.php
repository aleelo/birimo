<span class="invoice-meta text-default"><?php 
if (isset($estimate_info->custom_fields) && $estimate_info->custom_fields) {
    foreach ($estimate_info->custom_fields as $field) {
        if ($field->value) {
            echo "<span>" . $field->custom_field_title . ": " . view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value)) . "</span><br />";
        }
    }
}
// Replace line breaks in the address with spaces
$address = str_replace(array("\n", "\r"), ' ', $company_info->address);
?><br /><?php
echo $address; ?><br /><?php 
echo $company_info->phone; ?><br /><?php 
echo $company_info->email; ?><br /><?php 
echo $company_info->website; ?>
</span>