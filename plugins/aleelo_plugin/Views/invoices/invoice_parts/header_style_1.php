<table class="header-style" style="font-size: 13.5px;">
    <tr class="invoice-preview-header-row"> <!-- Adjust the height as needed -->
        <td style="width: 60%; vertical-align: top;">
        <?php echo view('aleelo_plugin\Views/invoices/invoice_parts/company_logo'); ?>
        <?php
if (isset($client_info->company_id)) {
    if ($client_info->company_id == 1) {
        $color = get_setting("estimate_color_pixel");
    } elseif ($client_info->company_id == 2) {
        $color = get_setting("estimate_color_solution");
    } else {
        $color = get_setting("estimate_color");
    }
} else {
    $color = get_setting("estimate_color");
}

if (!$color) {
    $color = get_setting("invoice_color") ? get_setting("invoice_color") : "#2AA384";
}

?>  </td> 
      <td style="width: 1%; vertical-align: top;">
    <div style="
        background-image: linear-gradient(to left,  <?php echo $color; ?>, orange); 
        color:  <?php echo $color; ?>;
        padding: 0; 
         text-align: left;
        font-size: 0.5em;
        font-weight:12;
        height: 10%; /* Make the height dynamic */
        line-height: 10em; /* Adjust line height for better spacing */
        margin-top: 34px; /* Adjust margin as needed */

    ">&nbsp; </div>
</td>
<td class="invoice-info-container invoice-header-style-one" 
    style="width: 35%; vertical-align: top; padding: 5px;">
    <?php
    $data = array(
        "client_info" => $client_info,
        "invoice_info" => $invoice_info,
        "company_info" => $company_info,
        "color" => $color,
    );
    echo view('aleelo_plugin\Views/invoices/invoice_parts/invoice_info', $data);
    ?>
</td>


    </tr>
    
    <tr>
    <td colspan="3">
        <div role="navigation" style="
            background-color: <?php echo $color; ?>;
            color: white;
            font-size: 1.5em;
            width: 100%;
            padding: 0.5em 0;
            border-bottom: 2px solid black;
            margin-top: 8px;
            text-align: left;
        ">
            <?php echo app_lang("estimate")?>
        </div>
    </td>
</tr>

    <tr>

  
    <td style="width: 50%; vertical-align: top;">
        <?php 
            echo view('aleelo_plugin\Views/invoices/invoice_parts/bill_from', $data);
            ?>
    </td>
  <td style="width: 0.1%;"></td>
    <td style="width: 49.9%; vertical-align: top; text-align: right;">
        <?php 
            echo view('aleelo_plugin\Views/invoices/invoice_parts/bill_to', $data);
            ?>
    </td>
</tr>

    
</table>