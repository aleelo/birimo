<?php
function adjust_brightness($hex, $steps) {
    // Remove the hash (#) if it exists
    $hex = str_replace('#', '', $hex);

    // Convert hex to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
               . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
               . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);

}
?>
<table >
<?php  

?> 
    <tr class="invoice-preview-header-row">
    <td style="width: 55%;  line-height: -0; vertical-align: top;">
        <div style="min-height: 22px; overflow: hidden;">
<?php   echo view('Sales_and_crm\Views/invoices/invoice_parts/company_logo'); ?>
 </div></td>    
     <td style="width: 2.6%;"></td>

    <td style="width: 1%; line-height: 0.5;  vertical-align: top;">
    <table style="width: 0.5%; height: 3px; border-collapse: collapse;">
    <tr>
        <td style="background-color: <?php echo $color; ?>; width: 2px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 20); ?>; width: 2px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 40); ?>; width: 1px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 60); ?>; width: 1px; height: 90px;"></td>
    </tr>
</table>  
</td>
       <td style="width: 0.4%; vertical-align: top;">
</td>
<td class="invoice-info-container invoice-header-style-one" 
    style="width: 41%; vertical-align: top; padding: 5px;">
            <?php   
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "invoice_info" => $invoice_info
            );
            echo view('Sales_and_crm\Views/invoices/invoice_parts/invoice_info', $data);
            ?>
        </td>
    </tr>  
    
   <tr>

   <td colspan="5">
  
        <strong style="color: <?php echo $color; ?>;"><?php echo ("DESCON HQ: "); ?>:</strong> <?php echo $company_info->address; ?>

   </td>
</tr>
   <tr>
<td colspan="5" style="background-color: <?php echo $color; ?>;font-weight: bold;font-size: 2.2em; height: 50px; padding: 0; padding-left: 20px; text-align: left;">
<div style="color: #fff;">
INVOICE</div>

    </td>
    </tr>
    <tr>
        <td colspan="5" style="background-color: #0A78BF; height: 1px; line-height: -15px;"></td>
    </tr>

  
 
<tr>


    <td style="width: 33%; vertical-align: top;">
            <?php echo view('Sales_and_crm\Views/invoices/invoice_parts/bill_from', $data);?>
        </td>
       <td style="width: 40%;"></td>
    <td style="width: 30%; vertical-align: top; text-align: left;">
        <?php echo view('Sales_and_crm\Views/invoices/invoice_parts/bill_to', $data);?>
        </td>
    </tr>

</table>