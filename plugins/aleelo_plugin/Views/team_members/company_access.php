<div class="tab-content">
    <?php echo form_open(get_uri("team_member/save_company_access/" . $user_info->id), array("id" => "general-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>
    <div class="card border-top-0 rounded-top-0">
        <div class=" card-header">
            <h4> <?php echo app_lang('company_access'); ?></h4>
        </div>
        <div class="card-body">

<div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
          
            <div class="form-group" style="min-height: 50px">
                <div class="row">
                    <label for="user_id" class="col-md-3"><?php echo ($add_user_type == "client_contacts") ? app_lang('contact') : app_lang('company_access'); ?></label>
                    <div class="col-md-9">
                        <div class="select-member-field" id="user-dropdown-container">
                            <div class="select-member-form clearfix pb10">
                                <?php echo form_dropdown("user_id[]", $company, array($model_info->id), "class='select2 col-md-7 p0' id='user_id'"); ?>
                                <?php //echo js_anchor("<i data-feather='x' class='icon-16'></i> ", array("class" => "remove-member delete ml20")); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
                echo form_checkbox("user_id", "all", false, "id='can_access_all_company' class='form-check-input'");
                ?>
                <label for="can_access_all_company"><?php echo app_lang('can_accsess_all_company'); ?></label>
            </div>
        </div>
    </div>
    </div>
        <div class="card-footer rounded-0">
            <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>


    <script>


$(document).ready(function () {
    // Initialize Select2 on page load
    $("#general-info-form .select2").select2();

    $("#can_access_all_company").change(function () {
        if ($(this).is(":checked")) {
            $("#user-dropdown-container").hide(); // Hide the dropdown
        } else {
            $("#user-dropdown-container").show(); // Show the dropdown
        }
    });
    $("#general-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                setTimeout(function () {
                    window.location.href = "<?php echo get_uri("team_member/view/" . $user_info->id); ?>" + "/company_access";
                }, 500);
            }
        });
});
    </script>