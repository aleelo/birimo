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
        <td style="width: 55%;  line-height: -0; vertical-align: top;;">
        <div style="min-height: 35px; overflow: hidden;">
        <?php  
        echo view('aleelo_plugin\Views/estimates/estimate_parts/company_logo'); ?>
    </div> </td> 
    <td style="width: 2.6%;"></td>
    
    <td style="width: 1%; vertical-align: top;">
    <table style="width: 100%; height: 300px; border-collapse: collapse;">
    <tr>
        <td style="background-color: <?php echo $color; ?>; width: 25%; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 20); ?>; width: 25%; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 40); ?>; width: 25%; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 60); ?>; width: 25%; height: 90px;"></td>
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
        "estimate_info" => $estimate_info,
        "company_info" => $company_info,
        "color" => $color,
    );
    echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_info', $data);
    ?>
</td>


    </tr>
    
    <tr >
    <td colspan="6">
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
  <td style="width: 20%;"></td>
    <td style="width: 30%; vertical-align: top; text-align: left;">
        <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_to', $data); ?>
    </td>
</tr>

    
</table>