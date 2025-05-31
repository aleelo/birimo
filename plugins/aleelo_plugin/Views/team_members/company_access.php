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

            <div class="row align-items-center mt-3">
            <div class="col-md-3">
            <?php
            echo form_checkbox("can_access_all_company", "all",  ($user_info->company_access === "all") ? true : false, "id='can_access_all_company' class='form-check-input'");
            ?>
            <label for="can_access_all_company" class="ml-2"><?php echo app_lang('can_access_some_company'); ?></label>
            </div>
            </div>            
               <div id ="one_company" class="<?php echo $user_info->company_access === "all" ? "hide" : ""; ?>" >

                <div class="row align-items-center mt-3">
                    <label for="user_id" class="col-md-3 mr-2"><?php echo ($add_user_type == "client_contacts") ? app_lang('contact') : app_lang('company_access'); ?></label>
                    <div class="col-md-5">
                        <div class="select-member-field" id="user-dropdown-container">
                            <div class="select-member-form clearfix pb10">
                                <?php echo form_dropdown("user_id[]", $company, $user_info->department, "class='select2 col-md-7 p0' id='user_id'"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
             

        <div id ="some_company" class="<?php echo $user_info->company_access === "all" ? "" : "hide"; ?>" >
        <div class="row align-items-center mt-3">
            <div class="col-md-3">
            <label class="mr-2" for="department_ids"><?php echo app_lang('select_company'); ?></label>
            </div>
            <div class="col-md-5" id="department-select-container">
            <?php
                echo form_dropdown("department_ids[]", $company, explode(',', $user_info->department_id), "class='select2 col-md-7 p0' multiple='multiple' id='department_ids'");
            ?>
            </div>
        </div>
        </div>
</br>
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
    $("#general-info-form .select2").select2();

    // function toggleDepartmentDropdown() {
    //     if ($("#can_access_all_company").is(":checked")) {
    //         $("#department-select-container").show();
    //     } else {
    //         $("#department-select-container").sho();
    //     }
    // }
 $("#can_access_all_company").click(function() {
            if ($(this).is(":checked")) {
                $("#some_company").removeClass("hide");
            } else {
                $("#some_company").addClass("hide");
            }
        });
 $("#can_access_all_company").click(function() {
            if ($(this).is(":checked")) {
                $("#one_company").addClass("hide");
            } else {
                $("#one_company").removeClass("hide");
            }
        });

    $("#general-info-form").appForm({
        isModal: false,
        onSuccess: function (result) {
            appAlert.success(result.message, { duration: 10000 });
            setTimeout(function () {
                window.location.href = "<?php echo get_uri("team_member/view/" . $user_info->id); ?>" + "/company_access";
            }, 500);
        }
    });
});
</script>
