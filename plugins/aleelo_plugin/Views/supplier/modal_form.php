
<?php echo form_open(get_uri("supplier/save"), array("id" => "supplier-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        
    
        <?php if ($has_all_permission){ ?>

<div class="form-group">
<div class="row">

                    <label for="company_id" class="col-md-3"><?php echo app_lang('company'); ?></label>
                    <div class=" col-md-9">
                        <?php
                        echo form_dropdown("company_id", $companies_dropdown, $model_info->company, "class='select2 form-control validate-hidden' id='company_id' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");
                
                    ?>
                </div>
</div>
            </div>
            <?php } else{ ?>
                <input type="hidden" name="company_id" value="<?php echo $login_user->department; ?>">
                <?php } ?>
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

            <label for="Account_Payable" class="col-md-3"><?php echo ('Account Payable'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_dropdown("Account_Payable", $accounts_dropdown, $model_info->Account_Payable, "class='select2 form-control validate-hidden' id='Account_Payable' data-rule-required='true', data-msg-required='" . app_lang('field_required') . "'");

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
        $("#country").on("change", function () {
    var country_id = $(this).val();
    
    if (country_id) {
        $.ajax({
            url: "<?php echo get_uri('supplier/get_regions_by_country'); ?>",
            type: "POST",
            data: { country_id: country_id },
            success: function (response) {
                var districts = JSON.parse(response);
                var $district = $("#district");

                $district.empty(); 

                $.each(districts, function (id, name) {
                    $district.append(new Option(name, id));
                });

                $district.trigger("change");
            }
        });
    }
});

        setTimeout(function () {
            $("#name").focus();
        }, 200);
    });
</script>
