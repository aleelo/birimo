<strong style="color: <?php echo $color; ?>; font-size: 1.5em;"><?php echo "Delivery to"; ?></strong>
<br/><br/>

<table style="background-color: #f4f4f4; width: 40%; border-collapse: collapse;">
    <!-- TO -->
    <tr>
        <td style="padding: 2px 0;">
        <?php if ($client_info->company_name) { ?>
           <strong style="color: <?php echo $color; ?>; font-size:     0.8em;"><?php echo "To"; ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $client_info->company_name; ?>
        <?php } ?>
        </td>
    </tr>
    
    <!-- Horizontal Line -->
    <tr>
        <td style="padding: -10;">
           <hr style="border-left: 1px solid #ddd; margin:4px 0;color: <?php echo $color; ?>; width: 38%;">
        </td>
    </tr>
    
    <!-- ADDRESS -->
    <tr>
        <td style="padding: 2px 0;">
        <?php if ($client_info->address) { ?>
           <strong style="color: <?php echo $color; ?>; font-size:     0.8em;"><?php echo "Address"; ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $client_info->address; ?>
        <?php } ?>
        </td>
    </tr>
    
  
    <!-- PHONE -->
    <tr>
        <td style="padding: 2px 0;">
        <?php if ($client_info->phone) { ?>
           <strong style="color: <?php echo $color; ?>; font-size:     0.8em;"><?php echo "Phone"; ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $client_info->phone; ?>
        <?php } ?>
        </td>
    </tr>
    
   
    <!-- EMAIL -->
    <tr>
        <td style="padding: 2px 0;">
        <?php if ($client_info->email) { ?>
           <strong style="color: <?php echo $color; ?>; font-size:     0.8em;"><?php echo "Email"; ?>:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $client_info->email; ?>
        <?php } ?>
        </td>
    </tr>
</table>