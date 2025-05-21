<?php
// echo company_widget($invoice_info->company_id, "estimate");
?>
<?php if (get_setting("invoice_style") != "style_3") { ?>
<?php } ?>
<?php if ($invoice_info->bill_date) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo app_lang ("invoice"); ?>#:</strong> <?php echo $invoice_info->id; }?>

        <span class="invoice-meta text-default">
    <?php if ($invoice_info->bill_date) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("DATE"); ?>:</strong> <?php echo $invoice_info->bill_date;
        } ?>
    
          <?php if ($invoice_info->project_title) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("Project"); ?>:</strong>                 <?php echo $invoice_info->project_title; ?>


 
    <?php } ?>
</span>
