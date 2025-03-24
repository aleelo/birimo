<div id="page-content" class="page-wrapper clearfix grid-button">
<div class="card clearfix">

    <div class="tab-title clearfix">
        <h4><?php echo app_lang('expenses'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("expenses/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i>" . app_lang('add_expense'), array("class" => "btn btn-default", "data-post-client_id" => $user, "title" => app_lang('add_expense'))); ?>
        </div>
    </div>
    <div class="table-responsive">
        <table id="expense-table" class="display" width="100%">
        </table>
    </div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        
        var optionVisibility = false;
        if ("<?php echo $can_edit_expense ?>") {
            optionVisibility = true;
        }
        $("#expense-table").appTable({
            source: '<?php echo_uri("expenses/expense_list_data_of_client/" . $user) ?>',
            order: [[0, "desc"]],
            filterDropdown: [<?php echo $custom_field_filters; ?>
            <?php if (get_array_value($login_user->permissions, "expense") == "own_company") 
 { ?>
            {name: "company_id_company", class: "w200 hidden-filter ", options: <?php echo $company; ?>},

            <?php  } elseif(get_array_value($login_user->permissions, "expense") == "own_expenses"){ ?>
                {name: "created_by_user", class: "w200 hidden-filter ", options: <?php echo $company; ?>, value: "<?php echo $login_user->company_id; ?>"},

          <?php  } ?>
            ],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo app_lang("date") ?>', "iDataSort": 0},
                {title: '<?php echo app_lang("company") ?>'},
                {title: '<?php echo ("Created by") ?>'},
                {title: '<?php echo app_lang("title") ?>'},
                {title: '<?php echo app_lang("description") ?>'},
                {title: '<?php echo app_lang("category") ?>', "class": "text-right"},
                {title: '<?php echo app_lang("amount") ?>', "class": "text-right"},
                {title: '<?php echo app_lang("status") ?>', "class": "text-right"}

                <?php echo $custom_field_headers; ?>,

            ],
            summation: [{column: 7, dataType: 'currency'}]
        });
        $(".hidden-filter").hide(); // 

    });
</script>