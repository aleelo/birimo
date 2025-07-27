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
    border-spacing: 0; /* remove gaps */
    margin-top: 30px;
    font-size: 10px;
    background: white;
    table-layout: fixed; /* forces equal distribution */
}

        thead {
            background-color: #a65f00;
            color: #fff;
        }

        th {
             font-size: 10px;
            padding: 10px;
            text-align: left;
            width: 12%;
            color: white;
            font-weight: bold;

            text-align: left;

        }

        td {
            padding: 10px;
            text-align: left;
            width: 12%;
             font-size: 12px;

        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
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
tbody tr.row-odd {
  background-color: #fefefe; /* First row */
}
tbody tr.row-even {
  background-color: #f1f1f1; /* Second row */
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
            <td style="width: 22%;">
                <h4 class="company-name"><?php echo $company_info->name; ?></h4>
                <h4 class="company-address"><?php echo nl2br($company_info->address); ?></h4>
            </td>
        </tr>

        <tr>
            <td style="width: 100%; line-height:-33px;   border: none; border-top: 2px solid #ccc; margin: 10px 0 20px 0; line-height: 3;">

            </td>
        </tr>
    </table>
    <!-- <div style="display:flex; justify-content:flex-end; align-items:center; height:50px; margin:0 0 10px 0;">
    <h1 style="font-size:33px; font-weight:bold; margin:0; padding:0; text-align:right;">INVOICE</h1>
</div> -->




    <div>
        <h3 class="invoice-title" style=" color:<?php echo $color ?>"><strong>Invoice To:</strong></h3>
        <h6><strong><?php echo $client_info->company_name; ?></strong></h6>
         <h6><strong>Invoice:</strong> <?php echo $invoice_info->display_id; ?></h6>
        <h6><strong>Date:</strong> <?php echo $invoice_info->bill_date ?></h6>
        <?php if ($invoice_info->description) { ?>
            <h6><strong>Event:</strong> <?php echo $invoice_info->description ?></h6>
        <?php } ?>
    </div>


    <table>
        <thead>

            <tr style="background-color:<?php echo $color ?>;">
                <th style="width: <?php echo $show_taxable ? '55%' : '20%'; ?>;">Item</th>
                <th style="width:10%"> Days</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Service (%)</th>
                <th>Cost (%)</th>
                <th style="width: <?php echo $show_taxable ? '15%' : '10%'; ?>;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tbody>
    <?php 
    $row_index = 0;
    foreach ($invoice_items as $item) { 
        if ($item->is_section) { ?>
            <tr style="font-weight: bold; background-color: <?php echo $company_info->section_background; ?>;">
                <td style="width: <?php echo $show_taxable ? '10%' : '10%'; ?>;"><?php echo $item->title; ?></td>
                <td><?php echo $item->quantity; ?></td>
                <td><?php echo $item->quantity; ?></td>
                <td><?php echo $item->quantity; ?></td>
            </tr>
        <?php 
        } else { 
            $row_color = ($row_index % 2 == 0) ? '#f9f9f9' : '#f1f1f1'; // alternate row colors
            $row_index++;
        ?>
            <tr style="background-color: <?php echo $row_color; ?>;">
                <td style="width: <?php echo $show_taxable ? '45%' : '18%'; ?>;"><?php echo $item->title; ?></td>
                <td style="width:10%"><?php echo $item->days ? $item->days : ""; ?></td>
                <td><?php echo $item->quantity; ?></td>
                <td><?php echo $item->rate; ?></td>
                <td><?php echo to_currency($item->total, $item->currency_symbol); ?></td>
                <td><?php echo $item->services ? $item->services . '%' : '0%'; ?></td>

                 <td><?php echo to_currency($item->service_cost, $item->currency_symbol); ?></td>
                <td><?php echo to_currency($item->alltotal, $item->currency_symbol); ?></td>
            </tr>
        <?php } 
    } ?>
</tbody>

        </tbody>
        <tfoot>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
         <td style="  text-align:center; vertical-align:middle; padding:10px; font-weight:bold;">Total</td>
        <td style=" text-align:center; vertical-align:middle; padding:10px; font-weight:bold;"> <?php echo to_currency($invoice_total_summary->invoice_total, $invoice_total_summary->currency_symbol); ?>
        </td>
         
        
    </tr>
</tfoot>


          

    </table>
    <?php if($invoice_info->terms){?>
    <h6><strong>Payment terms:</strong> <?php echo $invoice_info->terms; ?></h6>
    <?php } ?>
    <?php if($invoice_info->display_id){?>
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
                    <h5 class="signature-name"style=" color:<?php echo $color ?>"><?php echo $users_info->first_name; ?></h5>
                    <h5 class="signature-title"><?php echo $finance_manager_info->job_title_en; ?></h5>
                </div>
            </td>
        </tr>
    </table>

    </div>
</body>

</html>