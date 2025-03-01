<div class="card clearfix rounded-0 <?php
if (isset($page_type) && $page_type === "full") {
    echo "m20";
}
?>">
    <ul id="team-member-leave-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15">
                <?php
                if ($login_user->id === $applicant_id) {
                    echo app_lang("my_leave");
                } else {
                    echo app_lang("leaves");
                }
                ?>
            </h4>
        </li>
        </li>
        <li><a id="monthly-leaves-button" role="presentation" data-bs-toggle="tab" href="javascript:;" data-bs-target="#team_member-monthly-leaves"><?php echo app_lang("monthly"); ?></a></li>
        <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("team_members/yearly_leaves/"); ?>" data-bs-target="#team_member-yearly-leaves"><?php echo app_lang('yearly'); ?></a></li>
        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri('leaves/apply_leave_modal_form'), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('apply_leave'), array("class" => "btn btn-default", "title" => app_lang('apply_leave'))); ?>
            </div>
        </div>    
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="team_member-monthly-leaves">
            <div class="table-responsive">
                <table id="monthly-leaves-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
            <script type="text/javascript">
                loadMembersLeavesTable = function (selector, dateRange) {
                    $(selector).appTable({
                        source: '<?php echo_uri("team_members/list_data") ?>',
                        dateRangeType: dateRange,
                        filterParams: {deparment_id: "<?php echo $deparment_id; ?>"},
                        columns: [
                            {title: '', "class": "w50 text-center all"},
                            {title: "<?php echo app_lang("employee_id") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("name") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("job_title") ?>", "class": "w15p"},
                            {title: "<?php echo app_lang("section_name_so") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("department_name_so") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("shot_name_so") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("department_name_en") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("shot_name_en") ?>", "class": "w200 all"},
                            {title: "<?php echo app_lang("job_location") ?>", "class": "w200 all"},
                            {visible: visibleContact, title: "<?php echo app_lang("email") ?>", "class": "w20p"},
                            {visible: visibleContact, title: "<?php echo app_lang("phone") ?>", "class": "w15p"}
                            <?php echo $custom_field_headers; ?>,
                            {visible: visibleDelete, title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
                        ],
                        printColumns: [1, 2, 3, 4],
                        xlsColumns: [1, 2, 3, 4]
                    });
                };

                $(document).ready(function () {
                    loadMembersLeavesTable("#monthly-leaves-table", "monthly");
                });
            </script>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="team_member-yearly-leaves"></div>
    </div>
</div>