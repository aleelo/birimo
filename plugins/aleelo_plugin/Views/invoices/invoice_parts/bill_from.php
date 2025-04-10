<?php

//echo company_widget($invoice_info->company_id, "invoice");
?>

<?php if (get_setting("invoice_style") != "style_3") { ?>
<?php } ?>
<?php if ($client_info->company_name) { ?>

    <br /> <?php echo ("INVOICE:TO"); ?>:<br  /> <strong style="font-size: 1.2em;"><?php echo $client_info->company_name;} ?></strong>
    <span class="invoice-meta text-default">
    <?php if ($client_info->phone) { ?>
        <br /> <strong style="font-size: 1.1em; color: <?php echo $color; ?>;"><?php echo ("PHONE"); ?>:</strong> <?php echo $client_info->phone; ?>

        <?php if ($client_info->address) { ?>
            <br /> <strong style="color: <?php echo $color; ?>;"><?php echo ("ADDRESS"); ?>:</strong> <?php echo $client_info->address; ?>
        <?php } ?>
      
     
        <?php if ($client_info->country) { ?>
            <br /><strong style="color: <?php echo $color; ?>;"><?php echo ("COUNTRY"); ?>:</strong> <?php echo $client_info->country; ?>
        <?php } ?>
 
    <?php } ?>
    <?php if ($client_info->email) { ?>
            <br /><strong style="color: <?php echo $color; ?>;"><?php echo ("EMAIL"); ?>:</strong> <?php echo $client_info->email; ?>
        <?php } ?>
</span>
