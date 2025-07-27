<div id="page-content" class="page-wrapper clearfix">
  <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="row">
      <div class="col-md-3">
          <div class="card-body">
            <ul class="nav nav-tabs vertical settings d-block" role="tablist">
              <div class="clearfix settings-anchor" data-bs-toggle="collapse" data-bs-target="#settings-tab-accouting">
                  <?php echo app_lang('accounting_settings'); ?>
              </div>

              <div id='settings-tab-accouting' class='collapse show'>
                  <ul class="list-group help-catagory">
                  <?php
                    foreach($tab as $key => $gr){
                      ?>
                      <a href="<?php echo get_uri('accounting/setting?group='.$gr); ?>" class="list-group-item <?php if($gr == $group){echo 'active ';} ?>"><?php echo app_lang($gr); ?></a>
                    <?php } ?>
                  </ul>
              </div>
            </ul>
          </div>
      </div>

      <div class="col-md-9">
        <div class="card">
          <div class="card-body">
              <div>
                 <div class="tab-content">
                    <?php echo view($tabs['view']); ?>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </div>
</div>
