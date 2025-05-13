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

$style = get_setting("invoice_style");

?>


<?php
?>
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
    return '#'  . str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
                . str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
                . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}
?>

<div >
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

$style = get_setting("invoice_style");
?>
    <?php
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "invoice_info" => $invoice_info,
        "company_info" => $company_info,
    );
?>



<?php
   

    $discount_row = '<tr>
                        <td colspan="4" style="text-align: right;">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
                                    <td colspan="4" style="text-align: right;">' . app_lang("total_after_discount") . '</td>
                                    <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                                </tr>';
    ?>
</div>

<table >
<tr > 
<td style="width: 55%;  line-height: -0; vertical-align: top;">
        <div style="min-height: 35px; overflow: hidden;">
        <?php  
         echo view('aleelo_plugin\Views/invoices/delivery_note_parts/company_logo'); ?>
    </div> </td> 
    <td style="width: 2.6%;"></td>
    <td style="width: 1%; vertical-align: top;">
    <table style="width: 0.5%; height: 300px; border-collapse: collapse;">
    <tr>
        <td style="background-color: <?php echo $color; ?>; width: 2px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 20); ?>; width: 2px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 40); ?>; width: 1px; height: 90px;"></td>
        <td style="background-color: <?php echo adjust_brightness($color, 60); ?>; width: 1px; height: 90px;"></td>
    </tr>
</table>  
    
</td>

    <td 
    style="width: 41%; vertical-align: top; ">
    <?php
    $data = array(
        "client_info" => $client_info,
        "invoice_info" => $invoice_info,
        "company_info" => $company_info,
        "color" => $color,
    );
    echo view('aleelo_plugin\Views/invoices/delivery_note_parts/delivery_info', $data);
    ?>
</td>
    </tr>
</table>
<table style="margin: 0; padding: 0; border-spacing: 0; width: 150%;">
    <tr>
        <td>
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
       <strong> <?php echo (" DELIVERY NOTE") ?></strong>
            </div>
        </td>
    </tr>
</table>
<table >
    <tr style="padding: 33%;">

    <td style="width: 5%;"></td>

<td style="width: 50%; vertical-align: top; text-align: left;">
    <?php echo view('aleelo_plugin\Views/invoices/delivery_note_parts/bill_from', $data); ?>
</td>    <td style="width: 20%;"></td>

<td style="width: 34%; vertical-align: top; text-align: left;">
    <?php echo view('aleelo_plugin\Views/invoices/delivery_note_parts/bill_to', $data); ?>
</td>
</tr>
<tr>
    <td style="width: 5%;"></td>

        <td style="width: 750;">
        <?php
     echo $invoice_info->delivery_note; 
?>
        </td>
        <td style="width: 5%;"></td>

    </tr>
    
</table>

<table>
   
</table>

<table style="width: 50%; margin: 0 auto; border-collapse: collapse;">

    <tr style="font-weight: bold;  color: #fff;  ">
        <th style="width: 8%;"></th> 
        <th style="width: 40%; border-left: 1px solid #eee; border-right: 1px solid #eee;background-color: <?php echo $color; ?>;"> <?php echo app_lang("item"); ?> </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;background-color: <?php echo $color; ?>;"> <?php echo app_lang("quantity"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;background-color: <?php echo $color; ?>;"> <?php echo app_lang("rate"); ?></th>
        <th style="text-align: right;  width: 20%;background-color: <?php echo $color; ?>; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($invoice_items as $item) {
    ?>
        <tr >
        <td style="width: 8%;"></td>

            <td style="width: 40%; border: 1px solid #fff; background-color: #f4f4f4;padding: 10px; hyphens: auto;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888;background-color: #f4f4f4; font-size: 90%;"><?php echo custom_nl2br($item->description ? process_images_from_content($item->description) : ""); ?></span>
            </td>
            <td style="text-align: center; background-color: #f4f4f4; width: 15%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right;background-color: #f4f4f4; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <td style="text-align: right;background-color: #f4f4f4; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
        </tr>
    <?php } ?>
    <tr>
        
        <td colspan="4" style="text-align: right;"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax") {
        echo $discount_row . $total_after_discount_row;
    }
    ?>
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $invoice_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="4" style="text-align: right;"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    }
    ?>
    <tr>
        <td colspan="4" style="text-align: right;"><?php echo app_lang("total"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($invoice_total_summary->invoice_total, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php if ($invoice_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 1px solid #f2f4f6; color:#444; padding:0 0 20px 0;"><br /><?php echo custom_nl2br(process_images_from_content($invoice_info->note)); ?></div>
<?php } else { ?><!-- use table to avoid extra spaces -->
    <br /><br />
<?php } ?>

<span style="color:#444; line-height: 14px;">

</span>