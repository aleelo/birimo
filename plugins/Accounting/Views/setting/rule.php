<div id="page-content" class="page-wrapper clearfix">
    <?php echo form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo html_entity_decode($title); ?></h1>
        </div>
        <div class="card-body">
    <?php $setting = []; 
      $url = get_uri('accounting/new_rule');
    if (isset($rule)) {
      $url = get_uri('accounting/new_rule/'.$rule->id);
    }
    ?>
    <?php echo form_open($url,array('id'=>'rule-form', 'class' => 'general-form')); ?>
    <div class="row">
      <div class="col-md-12">
        <?php $value = (isset($rule)) ? $rule->name : ''; ?>
        <?php echo render_input('name','name',$value,'text'); ?>
      </div>
      <div class="col-md-6">
        <?php $transactions = [ 
          1 => ['id' => 'money_out', 'name' => _l('money_out')],
          2 => ['id' => 'money_in', 'name' => _l('money_in')],
        ]; 
        $value = (isset($rule)) ? $rule->transaction : '';
        ?>
        <?php echo render_select('transaction',$transactions,array('id','name'),'apply_this_to_transactions_that_are',$value,array(),array(),'','',false); ?>
      </div>
      <div class="col-md-6">
        <?php $following = [ 
          1 => ['id' => 'any', 'name' => _l('any')],
          2 => ['id' => 'all', 'name' => _l('all')],
        ]; 
        $value = (isset($rule)) ? $rule->following : '';
        ?>
        <?php echo render_select('following',$following,array('id','name'),'and_include_the_following',$value,array(),array(),'','',false); ?>
      </div>
    </div>
    <div class="row">
      <?php $follow_1 = [ 
          1 => ['id' => 'description', 'name' => _l('description')],
          2 => ['id' => 'amount', 'name' => _l('acc_amount')],
        ]; ?>
      <?php $follow_2 = [ 
          1 => ['id' => 'contains', 'name' => _l('contains')],
          2 => ['id' => 'does_not_contain', 'name' => _l('does_not_contain')],
          3 => ['id' => 'is_exactly', 'name' => _l('is_exactly')],
        ]; ?>
        <?php $follow_3 = [ 
          1 => ['id' => 'does_not_equal', 'name' => _l('does_not_equal')],
          2 => ['id' => 'equals', 'name' => _l('equals')],
          3 => ['id' => 'is_greater_than', 'name' => _l('is_greater_than')],
          4 => ['id' => 'is_less_than', 'name' => _l('is_less_than')],
        ]; ?>
        <div class="list_approve mleft15 mtop15">
      <?php if(!isset($rule)) { ?>
        <div id="item_approve">
          <div class="row">
            <div class="col-md-3">
              <?php echo render_select('type[0]',$follow_1,array('id','name'),'','',array('data-index' => 0),array(),'','',false); ?>
           </div>
           <div class="col-md-3 hide" id="div_subtype_amount_0"> 
              <?php echo render_select('subtype_amount[0]',$follow_3,array('id','name'),'','',array(),array(),'','',false); ?>
           </div>
           <div class="col-md-3" id="div_subtype_0">    
              <?php echo render_select('subtype[0]',$follow_2,array('id','name'),'','',array(),array(),'','',false); ?>
           </div>
           <div class="col-md-3">
              <?php echo render_input('text[0]','','','text'); ?>
           </div>
           <div class="col-md-1">
              <button name="add" class="btn new_vendor_requests btn-success" data-ticket="true" type="button"><i data-feather="plus" class=""></i></button>
          </div>
        </div>
      </div>
    <?php }else{ 
      ?>
      <?php foreach ($rule->details as $key => $value) { ?>
          <div id="item_approve">                            
            <div class="row">                              
              <div class="col-md-3">                      
                <?php echo render_select('type['.$key.']',$follow_1,array('id','name'),'',$value['type'],array('data-index' => $key),array(),'','',false); ?>
             </div>
             <div class="col-md-3 <?php if($value['type'] != 'amount'){echo 'hide';}; ?>" id="div_subtype_amount_<?php echo html_entity_decode($key); ?>">
                <?php echo render_select('subtype_amount['.$key.']',$follow_3,array('id','name'),'',$value['subtype_amount'],array(),array(),'','',false); ?>
             </div>
             <div class="col-md-3 <?php if($value['type'] == 'amount'){echo 'hide';}; ?>" id="div_subtype_<?php echo html_entity_decode($key); ?>">          
                <?php echo render_select('subtype['.$key.']',$follow_2,array('id','name'),'',$value['subtype'],array(),array(),'','',false); ?>
             </div>
             <div class="col-md-3">
              <?php echo render_input('text['.$key.']','',$value['text'],'text'); ?>
           </div>
             <div class="col-md-1">
                <?php if($key != 0){ ?>
                  <button name="add" class="btn remove_vendor_requests btn-danger mtop20" data-ticket="true" type="button"><i data-feather="minus" class=""></i></button>
                <?php }else{ ?>
                  <button name="add" class="btn new_vendor_requests btn-success mtop20" data-ticket="true" type="button"><i data-feather="plus" class=""></i></button>
                <?php } ?>
            </div>
          </div>

        </div>
    <?php }
  } ?>
</div>
</div>
  <?php $then = [ 
    1 => ['id' => 'assign', 'name' => _l('assign')],
    2 => ['id' => 'exclude', 'name' => _l('exclude')],
  ]; 
  $value = (isset($rule)) ? $rule->then : '';
  ?>
  <?php echo render_select('then',$then,array('id','name'),'then',$value,array(),array(),'','',false); ?>
<div id="then_assign" class="<?php if($value == 'exclude'){echo 'hide';} ?>">
  <div class="row">
    <div class="col-md-6">
      <?php $value = (isset($rule)) ? $rule->payment_account : ''; ?>
      <?php echo render_select('payment_account',$accounts,array('id','name', 'account_type_name'),'payment_account',$value ,array(),array(),'','',false); ?>
    </div>
    <div class="col-md-6">
      <?php $value = (isset($rule)) ? $rule->deposit_to : ''; ?>
      <?php echo render_select('deposit_to',$accounts,array('id','name', 'account_type_name'),'deposit_to',$value,array(),array(),'','',false); ?>
    </div>
  </div>
  <?php $value = (isset($rule)) ? $rule->auto_add : ''; ?>
  <div class="col-md-6">
      <h5 class="title mbot5"><?php echo _l('automatically_confirm_transactions_this_rule_applies_to') ?></h5>
      <div class="row">
          <div class="col-md-6 mtop10 border-right">
            <span><?php echo _l('auto_add'); ?> </span>
          </div>
          <div class="col-md-6 mtop10">
              <div class="onoffswitch">
                  <input type="checkbox" id="auto_add" data-perm-id="3" class="onoffswitch-checkbox form-check-input" <?php if($value == '1'){echo 'checked';} ?>  value="1" name="auto_add">
                  <label class="onoffswitch-label" for="auto_add"></label>
              </div>
          </div>
      </div>
    </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-info text-white"><i data-feather="check-circle" class="icon-16"></i> <?php echo _l('submit'); ?></button>
</div>
</div>
<?php echo form_close(); ?>
</div>
</div>
</div>

