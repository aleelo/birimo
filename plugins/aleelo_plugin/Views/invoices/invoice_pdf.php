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


    $color = $company_info->invoice_color ?: "#2AA384";

    $invoice_style = get_setting("invoice_style");
    $data = array(
        "client_info" => $client_info,
        "color" => $company_info->invoice_color,
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

    $item_background = $company_info->invoice_item_list_background;

    $discount_row = '<tr>
        <td style=" width: 64%;"></td>

                        <td colspan="' . $colspan . '" style="text-align: right;border-top: 1px solid #fff; border-left: 1px solid #9B9997;border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 12%; border-right: 1px solid #9B9997;border-top: 1px solid #fff; border-left: 1px solid #9B9997;border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
        <td style=" width: 64%;"></td>

            <td colspan="' . $colspan . '" style="text-align: right;border-top: 1px solid #fff; border-left: 1px solid #9B9997;border-right: 1px solid #9B9997;background-color: ' . $item_background . ';">' . app_lang("total_after_discount") . '</td>
            <td style="text-align: right; width: 19%; border-right: 1px solid #9B9997;border-top: 1px solid #fff; border-left: 1px solid #9B9997;border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . to_currency($invoice_total_summary->invoice_subtotal - $invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) . '</td>
        </tr>';
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%; border-collapse: collapse;">
    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;">
        <th style="width: 50%; border-right: 1px solid #9B9997;"><?php echo app_lang("item"); ?></th>
        <th style="width: 12.5%; border-right: 1px solid #9B9997;"><?php echo app_lang("days"); ?></th>
        <th style="width: 12.5%; text-align: center; border-right: 1px solid #9B9997;"><?php echo app_lang("quantity"); ?></th>
        <th style="width: 12.5%; text-align: right; border-right: 1px solid #9B9997;"><?php echo app_lang("rate"); ?></th>
        <th style="width: 12.5%; text-align: right;"><?php echo app_lang("total"); ?></th>
    </tr>

    <?php foreach ($invoice_items as $item) { ?>
        <?php if ($item->is_section) { ?>
            <tr style="background-color: <?php echo $color; ?>; color: #fff; font-weight: bold;">
                <td colspan="5" style="padding: 6px;"><?php echo $item->title; ?></td>
            </tr>
        <?php } else { ?>
            <tr style="background-color: <?php echo $company_info->invoice_item_list_background; ?>;">
                <td style="padding: 6px; border-top: 1px solid #fff; border-bottom: 1px solid #fff; border-right: 1px solid #9B9997;">
                    <?php echo $item->title; ?>
                    <br />
                    <span style="color: #888; font-size: 90%;"><?php echo custom_nl2br($item->description ?: ""); ?></span>
                </td>
                <td style="text-align: center; padding: 6px; border-top: 1px solid #fff; border-bottom: 1px solid #fff; border-right: 1px solid #9B9997;"><?php echo $item->days; ?></td>
                <td style="text-align: center; padding: 6px; border-top: 1px solid #fff; border-bottom: 1px solid #fff; border-right: 1px solid #9B9997;"><?php echo $item->quantity . " " . $item->unit_type; ?></td>
                <td style="text-align: right; padding: 6px; border-top: 1px solid #fff; border-bottom: 1px solid #fff; border-right: 1px solid #9B9997;"><?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
                <td style="text-align: right; padding: 6px; border-top: 1px solid #fff; border-bottom: 1px solid #fff;"><?php echo to_currency($item->total, $item->currency_symbol); ?></td>
            </tr>
        <?php } ?>
    <?php } ?>

    <!-- Subtotal -->
    <tr>
        <td colspan="3"></td>
        <td style="text-align: right; padding: 6px; border-top: 1px solid #fff; background-color: <?php echo $item_background; ?>; border-right: 1px solid #9B9997;"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; padding: 6px; border-top: 1px solid #fff; background-color: <?php echo $item_background; ?>;"><?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?></td>
    </tr>

    <!-- Discounts Before Tax -->
    <?php if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax") {
        echo $discount_row . $total_after_discount_row;
    } ?>

    <!-- Tax 1 -->
    <?php if ($invoice_total_summary->tax) { ?>
        <tr>
            <td colspan="3"></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>; border-right: 1px solid #9B9997;"><?php echo $invoice_total_summary->tax_name; ?></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>;"><?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>
        </tr>
    <?php } ?>

    <!-- Tax 2 -->
    <?php if ($invoice_total_summary->tax2) { ?>
        <tr>
            <td colspan="3"></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>; border-right: 1px solid #9B9997;"><?php echo $invoice_total_summary->tax_name2; ?></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>;"><?php echo to_currency($invoice_total_summary->tax2, $invoice_total_summary->currency_symbol); ?></td>
        </tr>
    <?php } ?>

    <!-- Tax 3 -->
    <?php if ($invoice_total_summary->tax3) { ?>
        <tr>
            <td colspan="3"></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>; border-right: 1px solid #9B9997;"><?php echo $invoice_total_summary->tax_name3; ?></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>;"><?php echo to_currency($invoice_total_summary->tax3, $invoice_total_summary->currency_symbol); ?></td>
        </tr>
    <?php } ?>

    <!-- Discounts After Tax -->
    <?php if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "after_tax") {
        echo $discount_row;
    } ?>

    <!-- Paid -->
    <?php if ($invoice_total_summary->total_paid) { ?>
        <tr>
            <td colspan="3"></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>; border-top: 1px solid #fff; border-right: 1px solid #9B9997;"><?php echo app_lang("paid"); ?></td>
            <td style="text-align: right; padding: 6px; background-color: <?php echo $item_background; ?>; border-top: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?></td>
        </tr>
    <?php } ?>

    <!-- Balance Due -->
    <tr>
        <td colspan="3"></td>
        <td style="text-align: right; padding: 6px; background-color: <?php echo $color; ?>; color: white; border-right: 1px solid #9B9997;"><?php echo app_lang("balance_due"); ?></td>
        <td style="text-align: right; padding: 6px; background-color: <?php echo $color; ?>; color: white;"><?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?></td>
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

    <table>

        <tr>
            <td style="width: 50%;  vertical-align: top; padding: 0px;">
                <?php
                echo $company_info->invoice_footer;


                ?>
            </td>
            <td style="width: 15%;"></td>
            <td style="width: 40%; vertical-align: top; padding: 0px;">

                <?php if ($company_info->finance_manager_id) { ?>
                    <br />
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
                                echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style=" width:250px; height: auto; max-height: 150px;">';
                            } else {
                                // File not found, try the second method
                                $signature_data = @unserialize($finance_manager_info->signature);

                                if (!empty($signature_data['file_name'])) {
                                    $signature_file_name = $signature_data['file_name'];

                                    $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

                                    if (file_exists($signature_path)) {
                                        echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style=" width:250px; height: auto; max-height: 150px;">';
                                    } else {
                                        echo '<p>Signature file not found.</p>';
                                    }
                                } else {
                                    echo '<p>Signature file not found.</p>';
                                }
                            }
                        } else {
                            echo '<p>No signature available.</p>';
                        }
                    } ?>
                    <br />
                    <br />
                    <strong style="font-size:150%; color: <?php echo $color; ?>;"><?php echo $users_info->first_name, " ", $users_info->last_name ?></strong> <br />
                    <br />

                    <?php if (!empty($users_info->job_title_en)) { ?>
                        <?php echo $users_info->job_title_en; ?>
                    <?php } ?>
                    <br />
                <?php
                }
                ?>
            </td>






        </tr>

    </table>
    <?php //echo get_setting("invoice_footer"); 
    ?>
</span>