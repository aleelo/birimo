<?php demo_load_css(array("assets/css/demo_styles.css")); ?>
<div class="card border-top-110 rounded-top-110">
    <div class="table-responsive">

</div>
</div>

<div id="page-content" class="page-wrapper clearfix">
    <div class="clearfix grid-button">
        <ul id="client-tabs" data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="nav-item">
                <a class="nav-link" href="#users-tab" data-bs-toggle="tab"><?php echo app_lang('Screen_size'); ?></a>
            </li>
            <div class="tab-title clearfix no-border">
            <div class="display">
            <?php echo modal_anchor(get_uri("Screen_size/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_Screen_size'), array("class" => "btn btn-default", "title" => app_lang('add_Screen_size'))); ?>
            </div>
        </div>
        </ul>
        <div class="tab-content">
            <div id="users-tab" class="tab-pane fade">
               
                 
            </div>
        </div>
    </div>
    <div class="tab-title clearfix no-border">
        <div class="title-button-group">
           
            </div>
    </div>
    <div class="card border-top-0 rounded-top-0">
        <div class="table-responsive pb50">
            <table id="user-table" class="display" cellspacing="0" width="100%">
            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#user-table").appTable({
            source: '<?php echo_uri("Screen_size/list_data") ?>',
           
            columns: [
               // {targets: [0], visible: false},
            {title: '<?php echo app_lang("id") ?>', "class": "w50"},
           {title: '<?php echo app_lang("screen_size") ?>', "class": "all", order_by: "title"},
           
            {title: "<i data-feather='menu' class='icon-16'></i>", "class": "text-center option w100"}
            
            ]
        });
    });
</script>