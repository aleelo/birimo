<?php
// echo company_widget($estimate_info->company_id, "estimate");
?>
<?php if (get_setting("invoice_style") != "style_3") { ?>
<?php } ?>
<?php if ($estimate_info->estimate_date) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("Quotation#"); ?>:</strong> <?php echo $estimate_info->id; }?>

        <span class="invoice-meta text-default">
    <?php if ($estimate_info->estimate_date) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("DATE"); ?>:</strong> <?php echo format_to_date($estimate_info->estimate_date); ?>

       
 
    <?php } ?>
    <?php if ($estimate_info->valid_until) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("VALID DATE"); ?>:</strong> <?php echo format_to_date($estimate_info->valid_until); ?>

       
 
    <?php } ?>
    
</span>
