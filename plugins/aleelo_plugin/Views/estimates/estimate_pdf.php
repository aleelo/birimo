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
        "estimate_info" => $estimate_info,
        "company_info" => $company_info,
    );

    if ($style === "style_3") {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_3.php', $data);
    } else if ($style === "style_2") {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_2.php', $data);
    } else {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_1_pdf.php', $data);
    }

    $discount_row = '<tr>
                        <td colspan="3" style="text-align: right;">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
                                    <td colspan="3" style="text-align: right;">' . app_lang("total_after_discount") . '</td>
                                    <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">' . to_currency($estimate_total_summary->estimate_subtotal - $estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . '</td>
                                </tr>';
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%;">

    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 45%; border-right: 1px solid #eee;"> <?php echo app_lang("item"); ?> </th>
        <th style="text-align: center;  width: 15%; border-right: 1px solid #eee;"> <?php echo app_lang("quantity"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #eee;"> <?php echo app_lang("rate"); ?></th>
        <th style="text-align: right;  width: 20%; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($estimate_items as $item) {
    ?>
        <tr style="background-color: #f4f4f4; ">
            <td style="width: 45%; border: 1px solid #fff; padding: 10px; hyphens: auto;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo custom_nl2br($item->description ? process_images_from_content($item->description) : ""); ?></span>
            </td>
            <td style="text-align: center; width: 15%; border: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
            <?php echo to_currency($estimate_total_summary->estimate_subtotal, $estimate_total_summary->currency_symbol); ?>
        </td>
    </tr>
    <?php
    if ($estimate_total_summary->discount_total && $estimate_total_summary->discount_type == "before_tax") {
        echo $discount_row . $total_after_discount_row;
    }
    ?>
    <?php if ($estimate_total_summary->tax) { ?>
        <tr>
            <td colspan="3" style="text-align: right;"><?php echo $estimate_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($estimate_total_summary->tax, $estimate_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($estimate_total_summary->tax2) { ?>
        <tr>
            <td colspan="3" style="text-align: right;"><?php echo $estimate_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border: 1px solid #fff; background-color: #f4f4f4;">
                <?php echo to_currency($estimate_total_summary->tax2, $estimate_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($estimate_total_summary->discount_total && $estimate_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    }
    ?>
    <tr>
        <td colspan="3" style="text-align: right;"><?php echo app_lang("total"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($estimate_total_summary->estimate_total, $estimate_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php if ($estimate_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 1px solid #f2f4f6; color:#444; padding:0 0 20px 0;"><br /><?php echo custom_nl2br(process_images_from_content($estimate_info->note)); ?></div>
<?php } else { ?><!-- use table to avoid extra spaces -->
    <br /><br />
<?php } ?>

<span style="color:#444; line-height: 14px;">
<table >

<tr>
    <td style="width: 50%; vertical-align: top; padding: 0px;">
    <br /><br /> <strong style="color: <?php echo $color; ?>;"><?php echo app_lang("Payment"); ?></strong> 
    <br /><br /><?php echo app_lang("company"); ?>: <?php echo $company_info->name; ?>

    <?php if ($company_info->bank_name) { ?>
        <br /><br /> <?php echo app_lang("bank_name"); ?>: <?php echo $company_info->bank_name; ?>
    <?php } ?>
  
 
    <?php if ($company_info->account_no) { ?>
        <br /><br /><?php echo app_lang("account_no"); ?>: <?php echo $company_info->account_no; ?>
    <?php } ?>
<br/>
<br/>



    <?php if ($company_info->we_accept) { ?>
        <br /><br /><strong style="color: <?php echo $color; ?>;"><?php echo app_lang("we_accept"); ?>:</strong> <br/>
        <br/>
        <?php echo $company_info->we_accept; ?>
    <?php } ?>
    <br/>
    

    <?php if ($company_info->Condition_company) { ?>
        <br /><br /><strong style="color: <?php echo $color; ?>;"><?php echo app_lang("Condition_company"); ?>:</strong> <br/>
        <br/>
        <?php echo $company_info->Condition_company; ?>
    <?php } ?>
   </td>
    <td style="width: 25%;"></td>
    <td style="width: 25%; vertical-align: top; text-align: left; padding: 0px;">
    <?php if ($company_info->finance_manager_id) { ?>
        <br /><br />
        <br /><br />
        <br /><br />
        <br /><br />

        <?php echo $company_info->finance_manager_id; ?>
        <br /><br />        <br /><br />

        <strong style="color: <?php echo $color; ?>;"><?php echo app_lang("finance_manager"); ?></strong> <br/>
        <br/>
    <?php } ?>
    </td>
</tr>

</table>

    <?php echo get_setting("estimate_footer"); ?>
</span>