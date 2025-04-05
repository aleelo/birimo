<table class="header-style" style="font-size: 13.5px;">
    <tr class="invoice-preview-header-row"> <!-- Adjust the height as needed -->
        <td style="width: 65%; vertical-align: top;">
            <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/company_logo'); ?>
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
        <td style="width: 0.3%; vertical-align: top;" ><div style=" 
            background-color: <?php echo $color; ?>;
            color: white;
            padding: 0.1px 0; /* Reduce padding */
            border-right: 2px solid purple; 
           
            border-left: 15px solid <?php echo $color; ?>;
            text-align: left;
            font-size: 1.5em;
            font-weight: bold;
            line-height: 18.5em;

"></div>
        </td> 
        <td class="invoice-info-container invoice-header-style-one" 
            style="width: 35%; vertical-align: top; padding: 5px;"> <!-- Reduce padding -->
            <?php
            $data = array(
                "client_info" => $client_info,
                "estimate_info" => $estimate_info,
                "company_info" => $company_info,
                "color" => $color,
            );
            echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_info', $data);
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
            ESTIMATE
        </div>
    </td>
</tr>

    <tr>

  
    <td style="width: 50%; vertical-align: top;">
        <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_from', $data); ?>
    </td>
  <td style="width: 0.1%;"></td>
    <td style="width: 49.9%; vertical-align: top; text-align: right;">
        <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_to', $data); ?>
    </td>
</tr>

    
</table>