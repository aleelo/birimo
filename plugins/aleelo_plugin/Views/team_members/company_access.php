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





                        <div id="some_company" class="<?php echo $user_info->company_access == "all" ? "hide" : ""; ?>">
                            <div class="row align-items-center mt-3">
                                <div class="col-md-3">
                                    <label class="mr-2" for="company_ids"><?php echo app_lang('select_company'); ?></label>
                                </div>
                                <div class="col-md-5" id="department-select-container">
                                    <?php
                                    echo form_dropdown("company_ids[]", $company, explode(',', $user_info->company_access), "class='select2 col-md-7 p0' multiple='multiple' id='company_ids'");
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-md-3">
                                <?php
                                echo form_checkbox("can_access_all_company", "all", ($user_info->company_access === "all") ? true : false, "id='can_access_all_company' class='form-check-input'");
                                ?>
                                <label for="can_access_all_company" class="ml-2"><?php echo ('can access all company'); ?></label>
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
    $(document).ready(function() {
        $("#general-info-form .select2").select2();

        $("#can_access_all_company").click(function() {
            if ($(this).is(":checked")) {
                $("#some_company").addClass("hide");
            } else {
                $("#some_company").removeClass("hide");
            }
        });

        $("#general-info-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                appAlert.success(result.message, {
                    duration: 10000
                });
                setTimeout(function() {
                    window.location.href = "<?php echo get_uri("team_member/view/" . $user_info->id); ?>" + "/company_access";
                }, 500);
            }
        });
    });
</script>