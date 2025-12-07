
<?php if (get_setting("invoice_style") != "style_3") { ?>
<?php } ?>
<?php if ($client_info->company_name) { ?>

    <br /> <?php echo ("INVOICE:TO"); ?>:<br  /> <strong style="font-size: 1.2em;"><?php echo $client_info->company_name;} ?></strong>
    <span class="invoice-meta text-default">

               <br /> <strong style="color: <?php echo $color; ?>;"><?php echo $client_info->address; ?>-<?php echo $client_info->country; ?></strong> 

 
 
</span>
