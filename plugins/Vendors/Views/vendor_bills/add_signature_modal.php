<?php echo form_open(get_uri("invoices/save_signature"), array("id" => "signature-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body">
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_info->id; ?>" />

    <?php
    // Branch functionality removed - not needed in vendors plugin
    $signature_type = $invoice_info->signature_type ? $invoice_info->signature_type : "own_signature";
    ?>
    <div class="form-group">
        <label for="signature_type"><?php echo ("Signature Type"); ?></label><br>

        <div class="form-check">
            <?php 
            echo form_radio("signature_type", "own_signature", $signature_type == "own", array("id" => "own_signature", "class" => "form-check-input"));
            ?>
            <label class="form-check-label" for="own_signature"><?php echo ("Own Signature"); ?></label>
        </div>

        <?php 
        // Branch functionality removed - not needed in vendors plugin
        // <div class="form-check">
        //     echo form_radio("signature_type", "branch_signature", $signature_type == "branch", array("id" => "branch_signature", "class" => "form-check-input"));
        //     echo '<label class="form-check-label" for="branch_signature">Branch Finance Signature</label>';
        // </div>
        ?>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><?php echo app_lang("save"); ?></button>
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo app_lang("close"); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#signature-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });
    });
</script>
