<li>

	<span data-feather="key" class="icon-14 ml-20"></span>
	<h5><?php echo app_lang("can_access_accountings"); ?></h5>

	<div>
		<label for=""><strong><?php echo app_lang("dashboard"); ?></strong></label>
		<div class="ml15">
			<div>
				<?php
				echo form_checkbox("acc_can_view_dashboard", "1", $acc_can_view_dashboard ? true : false, "id='acc_can_view_dashboard' class='form-check-input'");
				?>
				<label for="acc_can_view_dashboard"><?php echo app_lang("view"); ?></label>
			</div>
		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("banking"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_banking", "1", $acc_can_view_banking ? true : false, "id='acc_can_view_banking' class='form-check-input'");
				?>
				<label for="acc_can_view_banking"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_banking", "1", $acc_can_create_banking ? true : false, "id='acc_can_create_banking' class='form-check-input'");
				?>
				<label for="acc_can_create_banking"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_banking", "1", $acc_can_edit_banking ? true : false, "id='acc_can_edit_banking' class='form-check-input'");
				?>
				<label for="acc_can_edit_banking"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_banking", "1", $acc_can_delete_banking ? true : false, "id='acc_can_delete_banking' class='form-check-input'");
				?>
				<label for="acc_can_delete_banking"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("transaction"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_transaction", "1", $acc_can_view_transaction ? true : false, "id='acc_can_view_transaction' class='form-check-input'");
				?>
				<label for="acc_can_view_transaction"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_transaction", "1", $acc_can_create_transaction ? true : false, "id='acc_can_create_transaction' class='form-check-input'");
				?>
				<label for="acc_can_create_transaction"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_transaction", "1", $acc_can_edit_transaction ? true : false, "id='acc_can_edit_transaction' class='form-check-input'");
				?>
				<label for="acc_can_edit_transaction"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_transaction", "1", $acc_can_delete_transaction ? true : false, "id='acc_can_delete_transaction' class='form-check-input'");
				?>
				<label for="acc_can_delete_transaction"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("registers"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_register", "1", $acc_can_view_register ? true : false, "id='acc_can_view_register' class='form-check-input'");
				?>
				<label for="acc_can_view_register"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_register", "1", $acc_can_create_register ? true : false, "id='acc_can_create_register' class='form-check-input'");
				?>
				<label for="acc_can_create_register"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_register", "1", $acc_can_edit_register ? true : false, "id='acc_can_edit_register' class='form-check-input'");
				?>
				<label for="acc_can_edit_register"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_register", "1", $acc_can_delete_register ? true : false, "id='acc_can_delete_register' class='form-check-input'");
				?>
				<label for="acc_can_delete_register"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("journal_entry"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_journal_entry", "1", $acc_can_view_journal_entry ? true : false, "id='acc_can_view_journal_entry' class='form-check-input'");
				?>
				<label for="acc_can_view_journal_entry"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_journal_entry", "1", $acc_can_create_journal_entry ? true : false, "id='acc_can_create_journal_entry' class='form-check-input'");
				?>
				<label for="acc_can_create_journal_entry"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_journal_entry", "1", $acc_can_edit_journal_entry ? true : false, "id='acc_can_edit_journal_entry' class='form-check-input'");
				?>
				<label for="acc_can_edit_journal_entry"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_journal_entry", "1", $acc_can_delete_journal_entry ? true : false, "id='acc_can_delete_journal_entry' class='form-check-input'");
				?>
				<label for="acc_can_delete_journal_entry"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("transfer"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_transfer", "1", $acc_can_view_transfer ? true : false, "id='acc_can_view_transfer' class='form-check-input'");
				?>
				<label for="acc_can_view_transfer"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_transfer", "1", $acc_can_create_transfer ? true : false, "id='acc_can_create_transfer' class='form-check-input'");
				?>
				<label for="acc_can_create_transfer"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_transfer", "1", $acc_can_edit_transfer ? true : false, "id='acc_can_edit_transfer' class='form-check-input'");
				?>
				<label for="acc_can_edit_transfer"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_transfer", "1", $acc_can_delete_transfer ? true : false, "id='acc_can_delete_transfer' class='form-check-input'");
				?>
				<label for="acc_can_delete_transfer"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("account"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_account", "1", $acc_can_view_account ? true : false, "id='acc_can_view_account' class='form-check-input'");
				?>
				<label for="acc_can_view_account"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_account", "1", $acc_can_create_account ? true : false, "id='acc_can_create_account' class='form-check-input'");
				?>
				<label for="acc_can_create_account"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_account", "1", $acc_can_edit_account ? true : false, "id='acc_can_edit_account' class='form-check-input'");
				?>
				<label for="acc_can_edit_account"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_account", "1", $acc_can_delete_account ? true : false, "id='acc_can_delete_account' class='form-check-input'");
				?>
				<label for="acc_can_delete_account"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("reconcile"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_reconcile", "1", $acc_can_view_reconcile ? true : false, "id='acc_can_view_reconcile' class='form-check-input'");
				?>
				<label for="acc_can_view_reconcile"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_reconcile", "1", $acc_can_create_reconcile ? true : false, "id='acc_can_create_reconcile' class='form-check-input'");
				?>
				<label for="acc_can_create_reconcile"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_reconcile", "1", $acc_can_edit_reconcile ? true : false, "id='acc_can_edit_reconcile' class='form-check-input'");
				?>
				<label for="acc_can_edit_reconcile"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_reconcile", "1", $acc_can_delete_reconcile ? true : false, "id='acc_can_delete_reconcile' class='form-check-input'");
				?>
				<label for="acc_can_delete_reconcile"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("budget"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_budget", "1", $acc_can_view_budget ? true : false, "id='acc_can_view_budget' class='form-check-input'");
				?>
				<label for="acc_can_view_budget"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_budget", "1", $acc_can_create_budget ? true : false, "id='acc_can_create_budget' class='form-check-input'");
				?>
				<label for="acc_can_create_budget"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_budget", "1", $acc_can_edit_budget ? true : false, "id='acc_can_edit_budget' class='form-check-input'");
				?>
				<label for="acc_can_edit_budget"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_budget", "1", $acc_can_delete_budget ? true : false, "id='acc_can_delete_budget' class='form-check-input'");
				?>
				<label for="acc_can_delete_budget"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("report"); ?></strong></label>
		<div class="ml15">
			<div>
				<?php
				echo form_checkbox("acc_can_view_report", "1", $acc_can_view_report ? true : false, "id='acc_can_view_report' class='form-check-input'");
				?>
				<label for="acc_can_view_report"><?php echo app_lang("view"); ?></label>
			</div>
		</div>
	</div>

	<div>
		<label for=""><strong><?php echo app_lang("setting"); ?></strong></label>
		<div class="ml15">
			
			<div>
				<?php
				echo form_checkbox("acc_can_view_setting", "1", $acc_can_view_setting ? true : false, "id='acc_can_view_setting' class='form-check-input'");
				?>
				<label for="acc_can_view_setting"><?php echo app_lang("view"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_create_setting", "1", $acc_can_create_setting ? true : false, "id='acc_can_create_setting' class='form-check-input'");
				?>
				<label for="acc_can_create_setting"><?php echo app_lang("acc_create"); ?></label>
			</div>
			
			<div>
				<?php
				echo form_checkbox("acc_can_edit_setting", "1", $acc_can_edit_setting ? true : false, "id='acc_can_edit_setting' class='form-check-input'");
				?>
				<label for="acc_can_edit_setting"><?php echo app_lang("acc_edit"); ?></label>
			</div>
			<div>
				<?php
				echo form_checkbox("acc_can_delete_setting", "1", $acc_can_delete_setting ? true : false, "id='acc_can_delete_setting' class='form-check-input'");
				?>
				<label for="acc_can_delete_setting"><?php echo app_lang("acc_delete"); ?></label>
			</div>

		</div>
	</div>

</li>