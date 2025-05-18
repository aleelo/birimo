<?php if ($invoice_info->bill_date || $invoice_info->bill_date) { ?>
    <table style="width: 34%; ">
    <br/><br/>
    <br/><br/>

        <tr>
            <td style="font-size: 0.8em; color: black; font-weight: bold;text-align: right;">
                <?php echo ("DATE:"); ?>
            </td>
            <td>
                <?php echo format_to_date($invoice_info->bill_date); ?>
            </td>
        </tr>
        <!-- <tr>
            <td style="font-size: 0.8em; color:black; font-weight: bold;text-align: right;">
                <?php echo (" Due Date:"); ?>
            </td>
            <td>
                <?php echo format_to_date($invoice_info->due_date); ?>
            </td>
        </tr> -->
       
    </table>
<?php } ?>