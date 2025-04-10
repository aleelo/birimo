<div style=" margin: auto;">
    <?php
    $colspan = 3;
    $show_taxable = false;
    if (get_setting('taxable_column') == "always_show") {
        $show_taxable = true;
        $colspan = 4;
    } else if (get_setting('taxable_column') == "never_show") {
        $show_taxable = false;
    } else {
        $taxable_fields = array();
        foreach ($invoice_items as $item) {
            $taxable_fields[] = $item->taxable;
        }
        if (count(array_unique($taxable_fields)) == 2) {
            $show_taxable = true;
            $colspan = 4;
        }
    }


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
        $color = "#2AA384";
    }
    $invoice_style = get_setting("invoice_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $color,
        "invoice_info" => $invoice_info,
        "company_info" => $company_info,
        "users_info" => $users_info,
    );

    if ($invoice_style === "style_3") {
        echo view('aleelo_plugin\Views/invoices/invoice_parts/header_style_3.php', $data);
    } else if ($invoice_style === "style_2") {
        echo view('aleelo_plugin\Views/invoices/invoice_parts/header_style_2.php', $data);
    } else {
        echo view('aleelo_plugin\Views/invoices/invoice_parts/header_style_1_pdf.php', $data);
    }

    $item_background = get_setting("invoice_item_list_background");

    $discount_row = '<tr>
                        <td colspan="' . $colspan . '" style="text-align: right;">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
                                    <td colspan="' . $colspan . '" style="text-align: right;">' . app_lang("total_after_discount") . '</td>
                                    <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                                </tr>';
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%;">            
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 45%; border-right: 1px solid #9B9997;"> <?php echo app_lang("item"); ?> </th>
        <th style="text-align: center; border-right: 1px solid #9B9997; width: <?php echo $show_taxable ? '12%' : '15%'; ?>; border-right: 1px solid #eee;"> <?php echo app_lang("quantity"); ?></th>
        <th style="text-align: right;border-right: 1px solid #9B9997;  width:<?php echo $show_taxable ? '12%' : '20%'; ?>; border-right: 1px solid #eee;"> <?php echo app_lang("rate"); ?></th>
        <?php if ($show_taxable) { ?>
            <th style="text-align: center; width: 12%;  border-right: 1px solid #9B9997; "> <?php echo app_lang("taxable"); ?></th>
        <?php } ?>
        <th style="text-align: right;border-right: 1px solid #9B9997;  width: <?php echo $show_taxable ? '19%' : '20%'; ?>; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($invoice_items as $item) { ?>

        <tr style="background-color: <?php echo $item_background; ?>;">
            <td style="width: 45%; border-right: 1px solid #9B9997; border-botton: 1px solid #fff; border-top: 1px solid #fff; padding: 10px;"><?php echo $item->title; ?>
                <br />
                <span style="color: #888; font-size: 90%;"><?php echo custom_nl2br($item->description ? $item->description : ""); ?></span>
            </td>
            <td style="text-align: center; width: <?php echo $show_taxable ? '12%' : '15%'; ?>; border-right: 1px solid #9B9997; border-botton: 1px solid #fff; border-top: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
            <td style="text-align: right; width: <?php echo $show_taxable ? '12%' : '20%'; ?>; border-right: 1px solid #9B9997; border-botton: 1px solid #fff; border-top: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
            <?php if ($show_taxable) { ?>
                <td style="text-align: center; width: 12%; border-right: 1px solid #9B9997; border-botton: 1px solid #fff; border-top: 1px solid #fff; "> <?php echo $item->taxable ? app_lang("yes") : app_lang("no"); ?></td>
            <?php } ?>
            <td style="text-align: right; width: <?php echo $show_taxable ? '19%' : '20%'; ?>; border-right: 1px solid #9B9997; border-botton: 1px solid #fff; border-top: 1px solid #fff;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td style="width: 60%;"></td>
        <td colspan="<?php echo $colspan; ?>" style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%; border-botton: 1px solid #fff; border-top: 1px solid #fff; border-left: 1px solid #9B9997; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;">
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
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;">
                <?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;">
                <?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($invoice_total_summary->tax3) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;"><?php echo $invoice_total_summary->tax_name3; ?></td>
            <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;">
                <?php echo to_currency($invoice_total_summary->tax3, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php
    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    }
    ?> 
    <?php if ($invoice_total_summary->total_paid) { ?>     
        <tr>
        <td style="width: 60%;"></td>

            <td colspan="<?php echo $colspan; ?>" style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;"><?php echo app_lang("paid"); ?></td>
            <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $item_background; ?>;">
                <?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td style="width: 60%;"></td>
        <td colspan="<?php echo $colspan; ?>" style="text-align: right;width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $color; ?>; "><?php echo app_lang("balance_due"); ?></td>
        <td style="text-align: right; width: 20%; background-color: <?php echo $color; ?>; color: #fff;">
            <?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?>
        </td>
    </tr>
</table>
<?php if ($invoice_info->note) { ?>
    <br />
    <br />
    <div style="border-top: 1px solid #f2f4f6; color:#444; padding:0 0 20px 0;"><br /><?php echo custom_nl2br(process_images_from_content($invoice_info->note)); ?></div>
<?php } else { ?> <!-- use table to avoid extra spaces -->
    <br /><br />
    <br /><br />
    <?php } ?>
<span style="color:#444; line-height: 14px;">
    
<table >

<tr>
    <td style="width: 50%;  vertical-align: top; padding: 0px;">
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
    <td style="width: 15%;"></td>
    <td style="width: 35%; vertical-align: top; text-align: left; padding: 0px;">
  
  
  
  
    <?php if ($company_info->finance_manager_id) { ?>
        <br /><br />
        <br /><br />


        <br/>
        <?php if (!empty($finance_manager_info->job_title_en)) { ?>
            <?php 


// Try the first method
if (!empty($finance_manager_info->signature)) {
    $signature_data = @unserialize($finance_manager_info->signature);

    if ($signature_data !== false && !empty($signature_data[0]['file_name'])) {
        // Handle serialized signature data
        $signature_file_name = $signature_data[0]['file_name'];
    } else {
        // Handle direct file path
        $signature_file_name = $finance_manager_info->signature;
    }

    // Construct the full path to the signature file
    $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

    // Check if the file exists
    if (file_exists($signature_path)) {
        // Display the signature image
        echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 180px; height: 200px;">';
    } else {
        // File not found, try the second method
        $signature_data = @unserialize($finance_manager_info->signature);

        if (!empty($signature_data['file_name'])) {
            $signature_file_name = $signature_data['file_name'];

            $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

            if (file_exists($signature_path)) {
                echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 180px; height: 200px;">';
            } else {
                echo '<p>Signature file not found.</p>';
            }
        } else {
            echo '<p>Signature file not found.</p>';
        }
    }
} else {
    echo '<p>No signature available.</p>';
}}?>
<br/>
        <strong style="font-size:150%; color: <?php echo $color; ?>;"><?php echo $users_info->first_name, " ",$users_info->last_name ?></strong> <br/>
<br/>

        <?php if (!empty($users_info->job_title_en)) { ?>
            <?php echo $users_info->job_title_en; ?>
        <?php } ?>
        <br/>
        <?php     
}
?>
    </td>
 





</tr>

</table>
    <?php echo get_setting("invoice_footer"); ?>
</span>

