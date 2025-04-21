
<?php echo form_open(get_uri("supplier/save"), array("id" => "supplier-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
        <div class="form-group">
            <div class="row">
                <label for="company_id" class="col-md-3"><?php echo 'Company'; ?></label>
                <div class="col-md-9">
                    <?php 
                    echo form_dropdown(array( 
                        'id'=> "company_id",
                        'name'=> "company_id",
                        'class' => "form-control select2",
                        "value" => $model_info->company,
                        'autocomplete'=> "off",
                        'data-rule-required' => true,
                        'data-msg-required' => app_lang('field_required')
                    ), $company, [$model_info->company]); 
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="name" class="col-md-3"><?php echo app_lang('supplier_name'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "name",
                        "name" => "name",
                        "value" => $model_info->supplier_name,  
                        "class" => "form-control",
                        "placeholder" => app_lang('supplier_name'),
                        "data-rule-required" => true,
                        "data-msg-required" => app_lang("field_required")
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
             <div class="row">

                    <label for="country" class="col-md-3"><?php echo app_lang('country'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("country", $countries_dropdown, $model_info->Country, "class='select2 form-control validate-hidden' id='country' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                   
                         ?>
                </div>
             </div>
            </div>
            <div class="form-group">
             <div class="row">

                    <label for="district" class="col-md-3"><?php echo app_lang('district'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("district", $Regions_dropdown, $model_info->region, "class='select2 form-control validate-hidden' id='district' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                   
                         ?>
                </div>
             </div>
            </div>
        <div class="form-group">
            <div class="row">
                <label for="address" class="col-md-3"><?php echo app_lang('address'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_textarea(array(
                        "id" => "address",
                        "name" => "address",
                        "value" => $model_info->address,
                        "class" => "form-control",
                        "placeholder" => app_lang('address'),
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="phone" class="col-md-3"><?php echo app_lang('phone'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "phone",
                        "name" => "phone",
                        "value" => $model_info->phone,
                        "class" => "form-control",
                        "placeholder" => app_lang('phone')
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label for="email" class="col-md-3"><?php echo app_lang('email'); ?></label>
                <div class="col-md-9">
                    <?php
                    echo form_input(array(
                        "id" => "email",
                        "name" => "email",
                        "value" => $model_info->email,
                        "class" => "form-control",
                        "placeholder" => app_lang('email')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
    <div class="row">
        <label for="website" class="col-md-3"><?php echo app_lang('website'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "website",
                "name" => "website",
                "value" => $model_info->Website,
                "class" => "form-control",
                "placeholder" => app_lang('website')
            ));
            ?>
        </div>
    </div>
</div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal">
        <span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?>
    </button>
    <button type="submit" class="btn btn-primary">
        <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
    </button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(function () {
        if (typeof $.fn.select2 === "undefined") {
            console.error("❌ Select2 is not loaded properly!");
            return;
        }

        $("#supplier-form .select2").select2();

        $("#supplier-form").appForm({
            onSuccess: function (result) {
                if (typeof $SUPPLIER_TABLE !== 'undefined') {
                    $SUPPLIER_TABLE.appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                } else {
                    location.reload(); // fallback
                }
            }
        });

        setTimeout(function () {
            $("#name").focus();
        }, 200);
    });
</script>
