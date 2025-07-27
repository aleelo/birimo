<script type="text/javascript">
    $(document).ready(function () {
        "use strict";
        $("#role-table").appTable({
            source: '<?php echo_uri("accounting/role_list_data") ?>',
            columns: [
                {title: '<?php echo app_lang("name"); ?>'},
                {title: '', class: 'text-center option w125'}
            ],
            hideTools: true,
            onInitComplete: function () {
                var $role_list = $("#role-list-box"),
                        $empty_role = $("#empty-role");
                if ($empty_role.length && $role_list.length) {
                    $empty_role.height($role_list.height() - 30);
                }
            },
            displayLength: 1000
        });

        /*load a message details*/
        $("body").on("click", "tr", function () {
            //don't load this message if already has selected.
            if (!$(this).hasClass("active")) {
                var role_id = $(this).find(".role-row").attr("data-id");
                if (role_id) {
                    appLoader.show();
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $.ajax({
                        url: "<?php echo get_uri("accounting/permissions"); ?>/" + role_id,
                        success: function (result) {
                            appLoader.hide();
                            $("#role-details-section").html(result);
                        }
                    });
                }
            }
        });
    });
</script>