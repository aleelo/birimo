<div>
    <?php $color = $company_info->invoice_color ?: "#2AA384";


    $style = get_setting("invoice_style");
    ?>
    <?php
    $data = array(
        "client_info" => $client_info,
        "color" => $company_info->invoice_color,
        "estimate_info" => $estimate_info,
        "company_info" => $company_info,
        "users_info" => $users_info,
    );

    if ($style === "style_3") {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_3.php', $data);
    } else if ($style === "style_2") {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_2.php', $data);
    } else {
        echo view('aleelo_plugin\Views/estimates/estimate_parts/header_style_1_pdf.php', $data);
    }
    $item_background = $company_info->invoice_item_list_background;

    $discount_row = '<tr>
    <td style=" width: 60%;"></td>
                        <td colspan="3" style="text-align: right;  border-top: 1px solid #fff; border-left: 1px solid #9B9997;border-right: 1px solid #9B9997;background-color: ' . $item_background . ';">' . app_lang("discount") . '</td>
                        <td style="text-align: right; width: 20%; border: 1px solid #fff; border-right: 1px solid #9B9997; background-color: ' . $item_background . ';">' . to_currency($estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . '</td>
                    </tr>';

    $total_after_discount_row = '<tr>
    <td style=" width: 60%;"></td>
        <td colspan="3" style="text-align: right;border-top: 1px solid #fff; border-left: 1px solid #9B9997; border-right: 1px solid #9B9997;background-color: ' . $item_background . ';">' . app_lang("total_after_discount") . '</td>
        <td style="text-align: right; width: 20%; border: 1px solid #fff;  border-right: 1px solid #9B9997;background-color: ' . $item_background . ';">' . to_currency($estimate_total_summary->estimate_subtotal - $estimate_total_summary->discount_total, $estimate_total_summary->currency_symbol) . '</td>
    </tr>';
    ?>
</div>

<br />

<table class="table-responsive" style="width: 100%;">

    <tr style="font-weight: bold; background-color: <?php echo $color; ?>; color: #fff;  ">
        <th style="width: 39%; border-right: 1px solid #9B9997;"> <?php echo app_lang("item"); ?> </th>
        <th style="width: 9%; border-right: 1px solid #9B9997;"> <?php echo app_lang("days"); ?> </th>

        <th style="text-align: center;  width: 12%; border-right: 1px solid #9B9997;"> <?php echo app_lang("quantity"); ?></th>
        <th style="text-align: right;  width: 20%; border-right: 1px solid #9B9997;"> <?php echo app_lang("rate"); ?></th>
        <th style="text-align: right;  width: 20%; "> <?php echo app_lang("total"); ?></th>
    </tr>
    <?php
    foreach ($estimate_items as $item) {
    ?>
        <?php if ($item->is_section) { ?>
            <tr style="background-color: <?php echo $color; ?>; color: #fff; font-weight: bold;">
                <td colspan="5" style="padding: 6px;"><?php echo $item->title; ?></td>
            </tr>
        <?php } else { ?>
            <tr style="background-color:<?php echo $item_background; ?>; ">
                <td style="width: 39%; border-right: 1px solid #9B9997;border-top: 1px solid #fff; padding: 10px; hyphens: auto;"><?php echo $item->title; ?>
                    <br />
                    <span style="color: #888; font-size: 90%;"><?php echo custom_nl2br($item->description ? process_images_from_content($item->description) : ""); ?></span>
                </td>
                <td style="width: 9%;text-align: center; border-right: 1px solid #9B9997;border-top: 1px solid #fff; padding: 10px; hyphens: auto;"><?php echo $item->days; ?>
                </td>

                <td style="text-align: center; width: 12%;border-right: 1px solid #9B9997;border-top: 1px solid #fff;"> <?php echo $item->quantity . " " . $item->unit_type; ?></td>
                <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997;border-top: 1px solid #fff;"> <?php echo to_currency($item->rate, $item->currency_symbol); ?></td>
                <td style="text-align: right; width: 20%; border-right: 1px solid #9B9997;border-top: 1px solid #fff;"> <?php echo to_currency($item->total, $item->currency_symbol); ?></td>
            </tr>
        <?php } ?>

    <?php } ?>
    <tr>
        <td style=" width: 60%;"></td>
        <td colspan="3" style="text-align: right; border-right: 1px solid #9B9997;border-left: 1px solid #9B9997;border-top: 1px solid #fff;#9B9997;border: 1px solid #fff; width: 20%;background-color: <?php echo $item_background; ?>"><?php echo app_lang("sub_total"); ?></td>
        <td style="text-align: right; width: 20%;  border-right: 1px solid #9B9997;border-top: 1px solid #fff; background-color: <?php echo $item_background; ?>">
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
            <td style=" width: 60%;"></td>

            <td colspan="3" style="text-align: right;border-top: 1px solid #fff; border-left: 1px solid #9B9997;background-color:<?php echo $item_background; ?>"><?php echo $estimate_total_summary->tax_name; ?></td>
            <td style="text-align: right; width: 20%;border-left: 1px solid #9B9997;  border-right: 1px solid #9B9997;border-top: 1px solid #fff; background-color: <?php echo $item_background; ?>;">
                <?php echo to_currency($estimate_total_summary->tax, $estimate_total_summary->currency_symbol); ?>
            </td>
        </tr>
    <?php } ?>
    <?php if ($estimate_total_summary->tax2) { ?>
        <tr>
            <td style=" width: 60%;"></td>

            <td colspan="3" style="text-align: right;background-color:<?php echo $item_background; ?>"><?php echo $estimate_total_summary->tax_name2; ?></td>
            <td style="text-align: right; width: 20%;  border-right: 1px solid #9B9997;border-top: 1px solid #fff; background-color:<?php echo $item_background; ?>;">
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
        <td style=" width: 60%;"> </td>
        <td colspan="3" style="text-align: right; color: white;  width: 20%; border-right: 1px solid #9B9997; background-color: <?php echo $color; ?>;"><?php echo app_lang("total"); ?></td>
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
    <table>

        <tr>
            <td style="width: 50%; vertical-align: top; padding: 0px;">
                <?php
                echo $company_info->invoice_footer;

                ?>
            </td>
            <td style="width: 20%;"></td>
            <td style="width: 30%; vertical-align: top; text-align: left; padding: 0px;">










                <?php if ($company_info->finance_manager_id) { ?>
                    <br /><br />
                    <br /><br />


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
                                echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 150px; height: auto;">';
                            } else {
                                // File not found, try the second method
                                $signature_data = @unserialize($finance_manager_info->signature);

                                if (!empty($signature_data['file_name'])) {
                                    $signature_file_name = $signature_data['file_name'];

                                    $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

                                    if (file_exists($signature_path)) {
                                        echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 150px; height: auto;">';
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
                    <strong style="font-size:150%; color: <?php echo $color; ?>;"><?php echo $users_info->first_name, " ", $users_info->last_name ?></strong> <br />
                    <br />
                    <?php if (!empty($users_info->job_title_en)) { ?>
                        <?php echo $users_info->job_title_en; ?>
                    <?php } ?>
                    <br />

            </td>
        <?php
                }
        ?>





        </tr>

    </table>

</span>