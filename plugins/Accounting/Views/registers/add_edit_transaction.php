<div id="page-content" class="page-wrapper clearfix">
    <?php echo acc_form_hidden('site_url', get_uri()); ?>
    <div class="card">
        <div class="page-title clearfix">
            <h1><?php echo _l('acc_transactions'); ?></h1>
        </div>
        <div class="card-body">

			<?php echo form_open_multipart(admin_url('accounting/register_add_edit_transaction'), array('id' => 'add_update_transaction','autocomplete'=>'off', "class" => "general-form", "role" => "form")); ?>

				<div class="row">
					<div class="col-md-3 ">
						<?php echo render_date_input('from_date_filter','from_date', _d($from_date)); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo render_date_input('to_date_filter','to_date', _d($to_date)); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo render_input('number_filter','number',''); ?>
					</div>
					<div class="col-md-3 ">
						<?php echo render_select('payee_filter[]', $customers,array('id','label'),'payee', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
					</div>

					<div class="col-md-3 hide">
						<?php echo render_input('from_credit_filter',_l('from_payment_label'),'', 'number'); ?>
					</div>
					<div class="col-md-3 hide">
						<?php echo render_input('to_credit_filter',_l('to_payment_label'),'', 'number'); ?>
					</div>
					
					<div class="col-md-3 hide">
						<?php echo render_input('from_debit_filter',_l('from_deposit_label'),'', 'number'); ?>
					</div>
					<div class="col-md-3 hide">
						<?php echo render_input('to_debit_filter',_l('to_deposit_label'),'', 'number'); ?>
					</div>


					<div class="col-md-3 ">
						<?php echo render_select('account_filter[]',$accounts,array('id','name'),'account', '', array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
					</div>

					<div class="col-md-3 ">
						<div class="form-group mtop25">
							<label> </label>
						<button type="button" class="btn btn-info reset_filter text-white"><?php echo _l('reset_filter'); ?></button>
						</div>
					</div>


				</div>

						
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped">

							<tr>
								<td width="30%"><?php echo '<strong>'._l('company').'</strong>' ?></td>
								<td width="70%"><?php echo html_entity_decode($company_name); ?></td>
							</tr>
							<tr>
								<td width="30%"><?php echo '<strong>'._l('acc_account').'</strong>' ?></td>
								<td width="70%"><?php echo html_entity_decode($account_name) ; ?></td>
							</tr>
						</table>
					</div>
				</div>
				<?php echo acc_form_hidden('account', $account); ?>


				<div class="row">
					<p class="font-italic text-danger">* <?php echo _l('handsontable_right_click_note'); ?></p>
					<div class="form"> 
						<div id="product_tab_hs">
						</div>
						<?php echo acc_form_hidden('product_tab_hs'); ?>
					</div>

				</div>

				<div class="row">
					<div class="col-md-7"></div>
					<div class="col-md-5">
						<table class="table text-right">
							<tbody>
								<tr>
									<td><span class="bold"><?php echo _l('ending_balance'); ?></span>
									</td>
									<td class="ending_balance">
										<?php echo to_currency($ending_balance, ''); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="row">
					<div class="modal-footer">
						<a href="<?php echo admin_url('accounting/registers'); ?>"  class="btn btn-default mr-2 "><?php echo _l('close'); ?></a>
						<?php if(acc_has_permission('acc_can_create_register') || acc_has_permission('acc_can_edit_register')){ ?>
							<button type="button" class="btn btn-info pull-right add_user_register text-white"><?php echo _l('submit'); ?></button>
						<?php } ?>
					</div>
				</div>


			<?php echo form_close(); ?>
		</div>
	</div>
</div>
	
<?php require 'plugins/Accounting/assets/js/registers/add_edit_transaction_js.php'; ?>
