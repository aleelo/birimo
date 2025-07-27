
      <ul class="nav nav-tabs bg-white title" role="tablist">
         <li role="presentation" class="<?php if($child_tab_2 == 'general_mapping_setup'){echo 'active';}; ?>">
            <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=general_mapping_setup'); ?>">
              <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('general'); ?>
            </a>
         </li>
        <?php if(accounting_get_status_modules('Purchase')){ ?>
            <li role="presentation" class="<?php if($child_tab_2 == 'purchase'){echo 'active';}; ?>">
                <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=purchase'); ?>">
                  <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('purchase'); ?>
                </a>
             </li>
        <?php } ?> 
        <?php if(accounting_get_status_modules('Warehouse')){ ?>
            <li role="presentation" class="<?php if($child_tab_2 == 'warehouse'){echo 'active';}; ?>">
                <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=warehouse'); ?>">
                  <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('warehouse'); ?>
                </a>
             </li>
        <?php } ?> 
        <?php if(accounting_get_status_modules('Manufacturing')){ ?>
            <li role="presentation" class="<?php if($child_tab_2 == 'manufacturing'){echo 'active';}; ?>">
                <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=manufacturing'); ?>">
                  <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('manufacturing'); ?>
                </a>
             </li>
        <?php } ?> 
        <?php if(accounting_get_status_modules('Fixed_equipment')){ ?>
            <li role="presentation" class="<?php if($child_tab_2 == 'fixed_equipment'){echo 'active';}; ?>">
                <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=fixed_equipment'); ?>">
                  <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('fixed_equipment'); ?>
                </a>
             </li>
        <?php } ?>
        <?php if(accounting_get_status_modules('Hr_payroll')){ ?>
            <li role="presentation" class="<?php if($child_tab_2 == 'payslip'){echo 'active';}; ?>">
                <a href="<?php echo get_uri('accounting/setting?group=mapping_setup&tab=payslip'); ?>">
                  <i class="fa fa-th"></i>&nbsp;<?php echo app_lang('payslip'); ?>
                </a>
             </li>
        <?php } ?>
      </ul>

  <?php echo view($tab_2); ?>
