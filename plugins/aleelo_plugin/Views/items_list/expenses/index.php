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
                {title: '<?php echo app_lang("category") ?>'},
                {title: '<?php echo app_lang("title") ?>'},
                {title: '<?php echo app_lang("description") ?>'},
                {title: '<?php echo app_lang("files") ?>'},
                {title: '<?php echo app_lang("amount") ?>', "class": "text-right"},
                {title: '<?php echo app_lang("tax") ?>', "class": "text-right"},
                {title: '<?php echo app_lang("second_tax") ?>', "class": "text-right"},
                {title: '<?php echo app_lang("total") ?>', "class": "text-right"}
                <?php echo $custom_field_headers; ?>
            ],
            summation: [{column: 6, dataType: 'currency'}, {column: 7, dataType: 'currency'}, {column: 8, dataType: 'currency'}, {column: 9, dataType: 'currency'}]
        });
        $(".hidden-filter").hide(); // 

    });
</script>