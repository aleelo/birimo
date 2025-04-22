
<?php if ($invoice_info->bill_date ) { ?>
    <table style="width: 30%; ">
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("invoice#"); ?>
            </td>
            <td>
                <?php echo $invoice_info->id; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("DATE"); ?>
            </td>
            <td>
                <?php echo format_to_date($invoice_info->bill_date); ?>
            </td>
        </tr>
        <?php if ($invoice_info->due_date) { ?>
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("DUE DATE"); ?>
            </td>
            <td>
                <?php echo format_to_date($invoice_info->due_date); ?>
            </td>
        </tr>   
        <?php } ?>
       
    </table>
<?php } ?>