<?php if ($estimate_info->estimate_date || $estimate_info->valid_until) { ?>
    <table style="width: 100%; ">
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("Quotation#"); ?>
            </td>
            <td>
                <?php echo ":";echo $estimate_info->id; ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("DATE"); ?>
            </td>
            <td>
                <?php echo format_to_date($estimate_info->estimate_date); ?>
            </td>
        </tr>
        <?php if ($estimate_info->valid_until) { ?>
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("VALID DATE"); ?>
            </td>
            <td>
                <?php echo format_to_date($estimate_info->valid_until); ?>
            </td>
        </tr>
        <tr>
            <td style="font-size: 1.1em; color: <?php echo $color; ?>; font-weight: bold;">
                <?php echo ("Event name"); ?>
            </td>
            <td>
                <?php echo $estimate_info->project_title; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } ?>