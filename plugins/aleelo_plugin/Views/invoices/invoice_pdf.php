<!DOCTYPE html>
<html lang="en">
<?php $color = $company_info->invoice_color ?: "#2AA384";
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
    if ($invoice_info->tax_id) {
        $show_taxable = true;
        $colspan = 4;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Invoice INV/2025/000104</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: url('c3bece10-74f0-47fe-a29b-a3cf3641e1cc.png') no-repeat center top;
            background-size: cover;
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #ffffffdd;
            padding: 40px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.08);
        }

        header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #d8d8d8;
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 14px;
            color: #444;
        }

        * {
            outline: 1px dashed red;
        }

        .company-name {
            font-weight: bold;
            font-size: 16px;
            color: <?php echo $color ?>;
            margin: 0 0 5px 0;
            line-height: 0;
        }

        .company-address {
            font-size: 14px;
            color: #333;
        }

        .header-divider {
            border: none;
            border-top: 2px solid #ccc;
            margin: 10px 0 20px 0;
            line-height: 11;
        }


        .logo {
            height: 60px;
        }

        .invoice-section {
            margin-top: 30px;
        }

        .invoice-section h3 {
            color: #a65f00;
            margin-bottom: 33px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
        }

        thead {
            background-color: #a65f00;
            color: #fff;
        }

        th {
            width: 12%;
            color: white;
            font-weight: bold;

            text-align: left;

        }

        td {
            text-align: left;
            width: 12%;

        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
            color: #222;
        }

        .even-row {
            background-color: <?php echo $company_info->invoice_item_list_background ?>;
            color: black;
        }

        .odd-row {
            background-color: red;
            color: black;
        }

        tfoot td {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #ccc;
            color: #222;
        }

        .note {
            margin-top: 20px;
            font-size: 14px;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 40px;
            gap: 20px;
        }

        .payment-details {
            flex: 1;
        }

        h1,
        h3,
        h6 {
            margin: 0;
            padding: 0;
            line-height: 0;
            font-size: 14px;
            font-weight: normal !important;
            box-sizing: border-box;
            outline: none !important;
            margin-bottom: 6px;
        }

        h2 {
            margin: 0;
            padding: 0;
            line-height: -2;
            font-size: 14px;
            font-weight: normal !important;
            box-sizing: border-box;
            outline: none !important;
            margin-bottom: 6px;
        }

        h4 {
            margin: 0;
            padding: 0;
            line-height: -2;
            font-size: 14px;
            font-weight: normal !important;
            box-sizing: border-box;
            outline: none !important;
            margin-bottom: 6px;
        }

        h5 {
            margin: 0;
            padding: 0;
            font-size: 20px;
            font-weight: normal !important;
            box-sizing: border-box;
            outline: none !important;
            margin-bottom: 6px;
        }

        .invoice-title {
            line-height: -1;


        }

        hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #ccc;
            height: 1px;
            line-height: -13;

        }

        .signature-block {
            text-align: center;
            flex: 1;
        }

        .signature-block img {
            max-width: 200px;
        }

        .signature-name {
            margin-top: 10px;
            font-weight: bold;
            color: #a65f00;
            font-size: 20px;
            line-height: 0;

        }

        .signature-title {
            font-size: 13px;
            color: #444;
            line-height: 0;

        }

        .custom-f {
            display: block;
            font-size: 14px;
            padding: 5px 0;
            color: #333;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 40px;
        }

        @page {
            size: A4;
            margin: 20mm;
        }

        @media print {
            body {
                background: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .container {
                box-shadow: none;
                background: #fff;

            }


            .footer {
                page-break-after: avoid;
            }

            .details {
                color: #444;
                line-height: 14px;
            }

            .total {
                line-height: 0.2;

            }

        }
    </style>
</head>
<?php
$invoice_style = get_setting("invoice_style");
$data = array(
    "client_info" => $client_info,
    "color" => $company_info->invoice_color,
    "invoice_info" => $invoice_info,
    "company_info" => $company_info,
    "users_info" => $users_info,
);
?>

<body>
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 12%;">
                <?php

                echo get_company_icon($client_info->company_id, "");
                ?> </td>
            <td style="width: 50%;">
                <h4 class="company-name"><?php echo $company_info->name; ?></h4>
                <h4 class="company-address"><?php echo nl2br($company_info->address); ?></h4>
            </td>
        </tr>

        <tr>
            <td style="width: 100%; line-height:-33px;   border: none; border-top: 2px solid #ccc; margin: 10px 0 20px 0; line-height: 3;">

            </td>
        </tr>
    </table>




    <h3 class="invoice-title" style=" color:<?php echo $color ?>"><strong>Invoice To:</strong></h3>
    <h6><strong><?php echo $client_info->company_name; ?></strong></h6>
    <h2><?php echo $client_info->address; ?></h2>
    <h6><strong>Invoice:</strong> <?php echo $invoice_info->display_id; ?></h6>
    <h6><strong>Date:</strong> <?php echo $invoice_info->bill_date ?></h6>
    <?php if ($invoice_info->description) { ?>
        <h6><strong>Event:</strong> <?php echo $invoice_info->description ?></h6>
    <?php } ?>
    </div>


    <table style="width:100%; border-collapse:collapse; font-size:10px; table-layout:fixed;">
        <thead>
            <tr style="background-color:<?php echo $color ?>; color:#fff;">
                <th style="width:31%; text-align:left; padding:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Item</th>
                <th style="width:7%; text-align:center; padding:10px; white-space:nowrap;">Days</th>
                <th style="width:6%; text-align:center; padding:10px; white-space:nowrap;">Unit</th>
                <th style="width:8%; text-align:right; padding:10px; white-space:nowrap;">Price</th>
                <th style="width:14%; text-align:right; padding:10px; white-space:nowrap;">Cost</th>
                <th style="width:7%; text-align:center; padding:10px; white-space:nowrap;">Svc %</th>
                <th style="width:11%; text-align:center; padding:10px; white-space:nowrap;">Svc % Amt</th>
                <th style="width:16%; text-align:right; padding:10px; white-space:nowrap;">Tot Cost+Svc % </th>
            </tr>
                
        </thead>
        <tbody>
            <?php
            $current_section = null;
            $section_total = 0;
            $counter = 0;

            foreach ($invoice_items as $item) {

                if ($item->is_section) {
                    if ($current_section !== null) {
                        $row_class = ($counter % 2 === 0) ? '#f9f9f9' : '#f1f1f1';
                        $counter++;            ?>
                        <tr style="background-color: <?php echo $row_class; ?>">
                            <td style="width: <?php echo $show_taxable ? '88%' : '88%'; ?>"></td>
                            <td style=" width:12% text-align: left;"><?php echo to_currency($section_total, $invoice_total_summary->currency_symbol); ?></td>
                        </tr>
                    <?php
                        $section_total = 0; // Reset for next section
                    }

                    // Display the new section title
                    $current_section = $item->title;
                    ?>
                    <tr style="font-weight: bold; background-color: <?php echo $company_info->section_background; ?>;">
                        <td style=" width:100% text-align: right;"><?php echo $item->title; ?></td>
                    </tr>
                <?php
                } else {
                    $row_color = ($counter % 2 == 0) ? '#f9f9f9' : '#f1f1f1'; // alternate row colors
                    $counter++;
                    // Determine row styling


                    // Add to section total
                    $section_total += $item->alltotal;
                ?>
                    <tr style="background-color:<?php echo $row_color; ?>;">
                        <td style="width:31%; text-align:left; padding:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?php echo $item->title; ?><br />
                            <span style="color:#888; font-size:90%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:inline-block; max-width:95%;">
                                <?php echo custom_nl2br($item->description ?? ""); ?>
                            </span>
                        </td>
                        <td style="width:7%; text-align:center; padding:10px; white-space:nowrap;"><?php echo $item->days ?: ""; ?></td>
                        <td style="width:6%; text-align:center; padding:10px; white-space:nowrap;"><?php echo $item->quantity; ?></td>
                        <td style="width:8%; text-align:right; padding:10px; white-space:nowrap;"><?php echo $item->rate; ?></td>
                        <td style="width:14%; text-align:right; padding:10px; white-space:nowrap;"><?php echo to_currency($item->total, $item->currency_symbol); ?></td>
                        <td style="width:7%; text-align:center; padding:10px; white-space:nowrap;"><?php echo $item->services ? $item->services . '%' : '0%'; ?></td>
                        <td style="width:11%; text-align:center; padding:10px; white-space:nowrap;"><?php echo $item->service_cost ?: '0'; ?></td>
                        <td style="width:16%; text-align:right; padding:10px; white-space:nowrap;"><?php echo to_currency($item->alltotal, $item->currency_symbol); ?></td>
                                
                    </tr>
                <?php
                }
            }

            // Final section subtotal at the end
            if ($current_section !== null) {
                $row_color = ($counter % 2 == 0) ? '#f9f9f9' : '#f1f1f1'; // alternate row colors
                $counter++;           ?>
                <tr style="background-color:<?php echo $row_color; ?>;">
                    <td style="width: 84%;"></td>
                    <td style="width: 16%;font-weight: bold; text-align: right;"><?php echo to_currency($section_total, $invoice_total_summary->currency_symbol); ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>
        <tfoot>
            <tr class="total">
                <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                <?php if ($show_taxable) { ?>
                    <td></td>
                <?php } ?>
                <td style="width: 15%;text-align: left;"><?php echo app_lang("sub_total"); ?></td>
                <td style="text-align: left; width: 20%; border: 1px solid #fff;"><?php echo to_currency($invoice_total_summary->invoice_subtotal, $invoice_total_summary->currency_symbol); ?></td>
            </tr> <?php
                    if ($invoice_total_summary->discount_total && $invoice_total_summary->discount_type == "before_tax") { ?>
                <tr class="total">
                    <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                    <?php if ($show_taxable) { ?>
                        <td></td>
                    <?php } ?>
                    <td style="width: 15%;">Discount</td>
                    <td style="text-align: left; width: 20%; border: 1px solid #fff; "><?php echo to_currency($invoice_total_summary->discount_total, $invoice_total_summary->currency_symbol) ?> </td>
                </tr>

            <?php  }
            ?>
            <?php if ($show_taxable) { ?>

                <!-- <tr class="total">
                    <td style="width: <?php echo $show_taxable ? '72%' : '86%'; ?>;"></td>
                </tr> -->
            <?php } ?>
            <?php if ($invoice_total_summary->tax) { ?>

                <tr class="total">
                    <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                    <?php if ($show_taxable) { ?>
                        <td></td>
                    <?php } ?>
                    <td style="width: 15%;"><?php echo $invoice_total_summary->tax_name; ?></td>
                    <td style="text-align: left; width: 20%;"><?php echo to_currency($invoice_total_summary->tax, $invoice_total_summary->currency_symbol); ?></td>
                </tr>
            <?php } ?>

            <tr class="total">
                <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                <?php if ($show_taxable) { ?>
                    <td></td>
                <?php } ?>
                <td style="width: 15%; text-align: left;"><strong>Total</strong></td>
                <td style="text-align: left; width: 20%;"><strong><?php echo to_currency($invoice_total_summary->invoice_total, $invoice_total_summary->currency_symbol); ?></strong></td>
            </tr>

            <!-- <?php if ($invoice_total_summary->total_paid) { ?>
                <tr class="total">
                <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                    <?php if ($show_taxable) { ?><td></td><?php } ?>
                    <td style="width: 15%; text-align: left;">Paid</td>
                    <td style="text-align: left; width: 20%;"><?php echo to_currency($invoice_total_summary->total_paid, $invoice_total_summary->currency_symbol); ?>
                    </td>
                </tr>
                <tr class="total">
                <td style="width: <?php echo $show_taxable ? '60%' : '71%'; ?>;"></td>
                    <?php if ($show_taxable) { ?><td></td><?php } ?>
                    <td style="width: 15%; text-align: left;">Balance Due</td>
                    <td style="text-align: left; width: 17%;"><?php echo to_currency($invoice_total_summary->balance_due, $invoice_total_summary->currency_symbol); ?>
                    </td>
                </tr>
            <?php } ?> -->

        </tfoot>
    </table>
    <?php if ($invoice_info->terms) { ?>
        <h6><strong>Payment terms:</strong> <?php echo $invoice_info->terms; ?></h6>
    <?php } ?>
    <?php if ($invoice_info->display_id) { ?>
        <h6><strong>Payment Communication:</strong> <?php echo $invoice_info->display_id; ?></h6>
    <?php } ?>
    <table>
        <tr>
            <td style="width:60%;">
                <h6> <?php echo $company_info->invoice_footer; ?> </h6>

            </td>
            <td style="width:30%; ">

                <?php echo $signature;
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
                } ?>

                <div class="signature-block">
                    <?php if ($users_info->first_name) { ?>
                        <h5 class="signature-name" style=" color:<?php echo $color ?>"><?php echo $users_info->first_name . " " . $users_info->last_name; ?></h5>
                    <?php }
                    if (!empty($finance_manager_info->job_title_en)) { ?>
                        <h5 class="signature-title"><?php echo $finance_manager_info->job_title_en; ?></h5>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>

    </div>
</body>

</html>