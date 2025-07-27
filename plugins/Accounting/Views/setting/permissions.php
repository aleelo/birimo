<div class="row">
    <div class="col-md-4">
        <div id="role-list-box" class="card">
            <div class="table-responsiv">
                <table id="role-table" class="display clickable no-thead b-b-only" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="role-details-section"> 
            <div id="empty-role" class="text-center p15 box card " style="min-height: 150px;">
                <div class="box-content" style="vertical-align: middle; height: 100%"> 
                    <div><?php echo app_lang("select_a_role"); ?></div>
                    <span data-feather="sliders" width="6rem" height="6rem" style="color:rgba(128, 128, 128, 0.1)"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'plugins/Accounting/assets/js/setting/permissions_js.php'; ?>
