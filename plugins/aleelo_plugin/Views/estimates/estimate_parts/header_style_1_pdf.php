<table >
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

?> 
    <tr class="invoice-preview-header-row"> <!-- Adjust the height as needed -->
        <td style="width: 43%;  line-height: 0.5; vertical-align: top;;">
        <div style="min-height: 35px; overflow: hidden;">
        <?php  
        echo view('aleelo_plugin\Views/estimates/estimate_parts/company_logo'); ?>
    </div> </td> 
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
<td style="width: 15%;"></td>
<td class="invoice-info-container invoice-header-style-one" 
    style="width: 41%; vertical-align: top; padding: 5px;">
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
    
    <tr >
    <td colspan="4">
    <div role="navigation" style="
        background-color: <?php echo $color; ?>;
        color: white;
        font-size: 2.5em;
        width: 100%;
        padding: 0em 0;
        border-bottom: 3px solid black;
        text-align: left;
        margin-top: 100px; /* Move the div closer to the top */
    ">
       <strong> <?php echo app_lang("estimate") ?></strong>
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