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
<table class="header-style" style="font-size: 13.5px;">
    <tr class="invoice-preview-header-row"> <!-- Adjust the height as needed -->
        <td style="width: 60%; vertical-align: top;">
            <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/company_logo'); ?>
            <?php
    $color = $company_info->invoice_color ? : "#2AA384";


?>  </td> 
      <td style="width: 1%; vertical-align: top;">
    <div style="
        background-image: linear-gradient(to left,  <?php echo $color; ?>,<?php echo adjust_brightness($color, 20); ?>,<?php echo adjust_brightness($color, 40); ?>,<?php echo adjust_brightness($color, 60); ?>); 
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
        padding: 1em 0; 
        border-bottom: 2px solid black;
        margin: 16px 0; 
        text-align: left; /* left-align the text */
    ">
        <span style="padding: 0 1em; display: inline-block;font-weight: bold;"> <!-- Added padding around the word -->
            <?php echo app_lang("estimate") ?>
        </span>
    </div>
</td>
</tr>

    <tr>

  
    <td style="width: 70%; vertical-align: top;">
        <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_from', $data); ?>
    </td>
  <td style="width: 0.1%;"></td>
    <td style="width: 49.9%; vertical-align: top; text-align: left;">
        <?php echo view('aleelo_plugin\Views/estimates/estimate_parts/estimate_to', $data); ?>
    </td>
</tr>

    
</table>