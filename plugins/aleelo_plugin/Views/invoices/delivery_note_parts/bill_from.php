<!-- <strong style="color: <?php echo $color; ?>; font-size: 1.5em;"><?php echo "Delivery to"; ?></strong>
<br/><br/>
<table style="background-color: #f4f4f4; width: 45%; border-collapse: collapse;">
<tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>
<tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>


    <?php if ($client_info->company_name) { ?>
        <tr>
    <td style="padding: 2px; width: 84px; white-space: nowrap;">
        <strong style="font-size: 0.8em; color: <?php echo $color; ?>;">To:</strong>
    </td>
    <td style="padding: 2px;font-size: 0.8em;">
        <?php echo $client_info->company_name; ?>
    </td>
</tr>

    <?php } ?>
    <tr>
        <td style="padding: -10; line-height: 0.3em;">
           <hr style="border-left: 1px solid #ddd; margin:4px 0;color: <?php echo $color; ?>; width: 39%;">
        </td>
    </tr>
    <tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>

    <?php if ($client_info->address) { ?>
    <tr>
        <td style="padding: 5px;font-size: 0.8em;">
            <strong style="color: <?php echo $color; ?>;">Address:</strong>
        </td>
        <td style="padding: 5px;font-size: 0.8em;">
            <?php echo $client_info->address; ?>
        </td>
    </tr>
    <?php } ?>
    <tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>

    <?php if ($client_info->phone) { ?>
    <tr>
        <td style="padding: 5px;font-size: 0.8em;">
            <strong style="color: <?php echo $color; ?>;">Phone:</strong>
        </td>
        <td style="padding: 5px;font-size: 0.8em;">
            <?php echo $client_info->phone; ?>
        </td>
    </tr>
    <tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>

    <?php } ?>

    <?php if ($client_info->email) { ?>
    <tr>
        <td style="padding: 5px;font-size: 0.8em;">
            <strong style="color: <?php echo $color; ?>;">Email:</strong>
        </td>
        <td style="padding: 5px;font-size: 0.8em;">
            <?php echo $client_info->email; ?>
        </td>
    </tr>
    <?php } ?>

    <tr><td colspan="2" style="padding: 0; line-height: 5px;"></td></tr>

</table> -->
