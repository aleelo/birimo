<?php echo view("includes/cropbox"); ?>
<div id="page-content" class="page-wrapper clearfix">
    <div class="bg-primary card mb0 rounded-bottom-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row p20">
                        <?php echo view("users/profile_image_section"); ?>
                    </div>
                </div>

                <div class="col-md-6 text-center cover-widget">
                    <div class="row p20">
                        <?php
                        if ($show_projects_count) {
                            echo count_project_status_widget($user_info->id);
                        }

                        echo count_total_time_widget($user_info->id);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <ul id="team-member-view-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs scrollable-tabs rounded-0 border-top-0" role="tablist">

        <?php if ($show_timeline) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#tab-timeline"> <?php echo app_lang('timeline'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/general_info/" . $user_info->id); ?>" data-bs-target="#tab-general-info"> <?php echo app_lang('general_info'); ?></a></li>
        <?php } ?>
        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/education_info/" . $user_info->id); ?>" data-bs-target="#tab-education-info"> <?php echo app_lang('education_info'); ?></a></li>
        <?php } ?>
      

        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/social_links/" . $user_info->id); ?>" data-bs-target="#tab-social-links"> <?php echo app_lang('social_links'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
          
          <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/job_info/" . $user_info->id); ?>" data-bs-target="#tab-job-info"> <?php echo app_lang('job_info'); ?></a></li>
              <?php
              load_css(array(
                  "assets/css/invoice.css",
              ));

              load_js(array(
                  "assets/js/signature/signature_pad.min.js",
              ));
           
          ?>
              <?php } ?>
            <?php if ($user_info->user_type === "staff" && ($login_user->is_admin || (!$user_info->is_admin && get_array_value($login_user->permissions, "can_manage_user_role_and_permissions") && $login_user->id !== $user_info->id))) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/company_access/" . $user_info->id); ?>" data-bs-target="#tab-company-access"> <?php echo app_lang('company_access'); ?></a></li>
        <?php } ?>
        <?php if ($show_account_settings) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/account_settings/" . $user_info->id); ?>" data-bs-target="#tab-account-settings"> <?php echo app_lang('account_settings'); ?></a></li>
        <?php } ?>

        <?php if ($login_user->id == $user_info->id) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/my_preferences/" . $user_info->id); ?>" data-bs-target="#tab-my-preferences"> <?php echo app_lang('my_preferences'); ?></a></li>
        <?php } ?>
        <?php if ($login_user->id == $user_info->id) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("left_menus/index/user"); ?>" data-bs-target="#tab-user-left-menu"> <?php echo app_lang('left_menu'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/files/" . $user_info->id); ?>" data-bs-target="#tab-files"> <?php echo app_lang('files'); ?></a></li>
        <?php } ?>

        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/notes/" . $user_info->id); ?>" data-bs-target="#tab-notes"> <?php echo app_lang('notes'); ?></a></li>
        <?php } ?>

        <?php if ($show_projects) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/projects_info/" . $user_info->id); ?>" data-bs-target="#tab-projects-info"><?php echo app_lang('projects'); ?></a></li>
        <?php } ?>

        <?php if ($show_timesheets) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("project/all_timesheets/" . $user_info->id); ?>" data-bs-target="#tab-timesheets"> <?php echo app_lang('timesheets'); ?></a></li>
        <?php } ?>

        <?php if ($show_attendance) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/attendance_info/" . $user_info->id); ?>" data-bs-target="#tab-attendance-info"> <?php echo app_lang('attendance'); ?></a></li>
        <?php } ?>

        <?php if ($show_leave) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/leave_info/" . $user_info->id); ?>" data-bs-target="#tab-leave-info"><?php echo app_lang('leaves'); ?></a></li>
        <?php } ?>
        <?php if ($show_general_info) { ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_member/expense_info/" . $user_info->id); ?>" data-bs-target="#tab-expense-info"><?php echo app_lang('expenses'); ?></a></li>
        <?php } ?>



     
        <?php
        $hook_tabs = array();
        $hook_tabs = app_hooks()->apply_filters('app_filter_staff_profile_ajax_tab', $hook_tabs, $user_info->id);
        $hook_tabs = is_array($hook_tabs) ? $hook_tabs : array();
        foreach ($hook_tabs as $hook_tab) {
        ?>
            <li><a role="presentation" data-bs-toggle="tab" href="<?php echo get_array_value($hook_tab, 'url') ?>" data-bs-target="#<?php echo get_array_value($hook_tab, 'target') ?>"><?php echo get_array_value($hook_tab, 'title') ?></a></li>
        <?php
        }
        ?>

    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade mb15" id="tab-timeline">
            <?php echo timeline_widget(array("limit" => 20, "offset" => 0, "is_first_load" => true, "user_id" => $user_info->id)); ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab-general-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-education-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-company-access"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-files"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-social-links"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-job-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-account-settings"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-my-preferences"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-user-left-menu"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-projects-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-attendance-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-leave-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-expense-info"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-notes"></div>
        <div role="tabpanel" class="tab-pane fade" id="tab-timesheets"></div>

        <?php
        foreach ($hook_tabs as $hook_tab) {
        ?>
            <div role="tabpanel" class="tab-pane fade" id="<?php echo get_array_value($hook_tab, 'target') ?>"></div>
        <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".upload").change(function() {
            if (typeof FileReader == 'function' && !$(this).hasClass("hidden-input-file")) {
                showCropBox(this);
            } else {
                $("#profile-image-form").submit();
            }
        });
        $("#profile_image").change(function() {
            $("#profile-image-form").submit();
        });


        $("#profile-image-form").appForm({
            isModal: false,
            beforeAjaxSubmit: function(data) {
                $.each(data, function(index, obj) {
                    if (obj.name === "profile_image") {
                        var profile_image = replaceAll(":", "~", data[index]["value"]);
                        data[index]["value"] = profile_image;
                    }
                });
            },
            onSuccess: function(result) {
                if (typeof FileReader == 'function' && !result.reload_page) {
                    appAlert.success(result.message, {
                        duration: 10000
                    });
                } else {
                    location.reload();
                }
            }
        });

        setTimeout(function() {
            var tab = "<?php echo $tab; ?>";
            if (tab === "general") {
                $("[data-bs-target='#tab-general-info']").trigger("click");
            } 
              else if (tab === "account") {
                $("[data-bs-target='#tab-account-settings']").trigger("click");
            } else if (tab === "social") {
                $("[data-bs-target='#tab-social-links']").trigger("click");
            } else if (tab === "job_info") {
                $("[data-bs-target='#tab-job-info']").trigger("click");
            } else if (tab === "my_preferences") {
                $("[data-bs-target='#tab-my-preferences']").trigger("click");
            } else if (tab === "left_menu") {
                $("[data-bs-target='#tab-user-left-menu']").trigger("click");
            }else if (tab === "company_access") {
                $("[data-bs-target='#tab-company-access']").trigger("click");
            }
        }, 210);

    });
</script>