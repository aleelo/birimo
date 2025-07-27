<div id="accordion">
  <div class="card">
    <table class="tree">
      <tbody>
        <tr>
          <td colspan="6" class="text-center">
              <h3 class="bold"><?php echo _l('customer_statement'); ?></h3>
              <p class="text-muted"><?php echo sprintf(_l('statement_from_to'),$data_report['from_date'],$data_report['to_date'], false); ?></p>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" width="50">
            <?php
            $company_address = nl2br($data_report['data']['company_info']->address);
            ?><div><b><?php echo html_entity_decode($data_report['data']['company_info']->name); ?></b></div>
              <?php
                if ($company_address) {
                    echo html_entity_decode($company_address);
                }
                ?>
                <?php if ($data_report['data']['company_info']->phone) { ?>
                    <br /><?php echo app_lang("phone") . ": " . $data_report['data']['company_info']->phone; ?>
                <?php } ?>
                <?php if ($data_report['data']['company_info']->email) { ?>
                    <br /><?php echo app_lang("email") . ": " . $data_report['data']['company_info']->email; ?>
                <?php } ?>
                <?php if ($data_report['data']['company_info']->vat_number) { ?>
                    <br /><?php echo app_lang("vat_number") . ": " . $data_report['data']['company_info']->vat_number; ?>
                <?php } ?>
          </td>
          <td colspan="3" class="text-right" width="50">
            <strong><?php echo html_entity_decode($data_report['data']['client']->company_name); ?> </strong>
              <?php if ($data_report['data']['client']->address || $data_report['data']['client']->vat_number || (isset($data_report['data']['client']->custom_fields) && $data_report['data']['client']->custom_fields)) { ?>
                  <div><?php echo nl2br($data_report['data']['client']->address); ?>
                      <?php if ($data_report['data']['client']->city) { ?>
                          <br /><?php echo html_entity_decode($data_report['data']['client']->city); ?>
                      <?php } ?>
                      <?php if ($data_report['data']['client']->state) { ?>
                          <br /><?php echo html_entity_decode($data_report['data']['client']->state); ?>
                      <?php } ?>
                      <?php if ($data_report['data']['client']->zip) { ?>
                          <br /><?php echo html_entity_decode($data_report['data']['client']->zip); ?>
                      <?php } ?>
                      <?php if ($data_report['data']['client']->country) { ?>
                          <br /><?php echo html_entity_decode($data_report['data']['client']->country); ?>
                      <?php } ?>
                      <?php if ($data_report['data']['client']->vat_number) { ?>
                          <br /><?php echo app_lang("vat_number") . ": " . $data_report['data']['client']->vat_number; ?>
                      <?php } ?>
                      <?php
                      if (isset($data_report['data']['client']->custom_fields) && $data_report['data']['client']->custom_fields) {
                          foreach ($data_report['data']['client']->custom_fields as $field) {
                              if ($field->value) {
                                  echo "<br />" . $field->custom_field_title . ": " . view("custom_fields/output_" . $field->custom_field_type, array("value" => $field->value));
                              }
                          }
                      }
                      ?>


                  </div>
              <?php } ?>
          </td>
          
          <td></td>
          <td></td>
          <td></td>
          <td></td>
      </tr>
      <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-left"><?php echo _l('statement_beginning_balance'); ?>:</td>
          <td colspan="2" class="text-right"><?php echo to_currency($data_report['data']['beginning_balance'], $data_report['data']['currency']); ?></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-left"><?php echo _l('invoiced_amount'); ?>:</td>
          <td colspan="2" class="text-right"><?php echo to_currency($data_report['data']['invoiced_amount'], $data_report['data']['currency']); ?></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-left"><?php echo _l('amount_paid'); ?>:</td>
          <td colspan="2" class="text-right"><?php echo to_currency($data_report['data']['amount_paid'], $data_report['data']['currency']); ?></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-left tr_total"><b><?php echo _l('balance_due'); ?></b>:</td>
          <td colspan="2" class="text-right tr_total"><?php echo to_currency($data_report['data']['balance_due'], $data_report['data']['currency']); ?></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="6" class="text-center bold padding-10"><?php echo sprintf(_l('customer_statement_info'),$data_report['from_date'],$data_report['to_date'], false); ?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr class="tr_header">
          <td class="text-bold"><b><?php echo _l('statement_heading_date'); ?></b></td>
          <td colspan="2" class="text-bold"><b><?php echo _l('statement_heading_details'); ?></b></td>
          <td class="text-bold text-right"><b><?php echo _l('statement_heading_amount'); ?></b></td>
          <td class="text-bold text-right"><b><?php echo _l('statement_heading_payments'); ?></b></td>
          <td class="text-bold text-right"><b><?php echo _l('statement_heading_balance'); ?></b></td>
          <td></td>
        </tr>
        <tr>
           <td><?php echo html_entity_decode($data_report['from_date']); ?></td>
           <td colspan="2"><?php echo _l('statement_beginning_balance'); ?></td>
           <td class="text-right"><?php echo to_currency($data_report['data']['beginning_balance'], $data_report['data']['currency'], true); ?></td>
           <td></td>
           <td class="text-right"><?php echo to_currency($data_report['data']['beginning_balance'], $data_report['data']['currency'], true); ?></td>
            <td></td>
         </tr>

         <?php
             $tmpBeginningBalance = $data_report['data']['beginning_balance'];
             foreach($data_report['data']['result'] as $data){ ?>
                <tr>
                  <td><?php echo _d($data['date']); ?></td>
                  <td colspan="2">
                    <?php
                    if(isset($data['invoice_id'])) {
                      echo sprintf(_l('statement_invoice_details'), '<a href="'.admin_url('invoices/view/'.$data['invoice_id']).'" target="_blank">'.get_invoice_id($data['invoice_id']).'</a>',_d($data['duedate']), false);
                    } else if(isset($data['payment_id'])){
                     echo sprintf(_l('statement_payment_details'),'<a href="'.admin_url('invoices/view/'.$data['payment_invoice_id']).'" target="_blank">'.'#'.$data['payment_id'].'</a>',get_invoice_id($data['payment_invoice_id']), false);
                   }
                  ?>
                </td>
                <td class="total_amount">
                  <?php
                  if(isset($data['invoice_id'])) {
                    echo to_currency($data['invoice_amount'], $data_report['data']['currency'], true);
                  }
                  ?>
                </td>
                <td class="total_amount">
                  <?php
                  if(isset($data['payment_id'])) {
                    echo to_currency($data['payment_total'], $data_report['data']['currency'], true);
                  }
                  ?>
                </td>
                <td class="total_amount">
                  <?php
                  if(isset($data['invoice_id'])) {
                    $tmpBeginningBalance = ($tmpBeginningBalance + $data['invoice_amount']);
                  } else if(isset($data['payment_id'])){
                    $tmpBeginningBalance = ($tmpBeginningBalance - $data['payment_total']);
                  }

                  echo to_currency($tmpBeginningBalance, $data_report['data']['currency'], true);
                  ?>
                </td>
                <td></td>
              </tr>
            <?php } ?>
              <tr class="tr_total">
              <td></td>
              <td></td>
               <td class="text-right">
                 <b><?php echo _l('balance_due'); ?></b>
               </td>
              <td></td>
               <td class="text-right">
                 <b><?php echo to_currency($data_report['data']['balance_due'], $data_report['data']['currency']); ?></b>
               </td>
              <td></td>
              </tr>
      </tbody>
    </table>
  </div>
</div>
