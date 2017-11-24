<!-- ****** Module Configuration Template ****** -->

<!-- Display a confirmation message when the module configuration is saved -->
{if isset($confirmation)}
<div class='alert alert-success'>
	Configuration saved
</div>
{/if}

<form method='post' action='' class='defaultForm form-horizontal'>

	<div class='panel'>
		<div class='panel-heading'>
			<i class='icon-cogs'></i>
	Module Configuration
		</div>

		<div class='form-wrapper'>
			<div class='form-group'>
				<label class='control-label col-lg-3'>Activate email notifications :</label>
				<div class='col-lg-9'>

					<img src='../img/admin/enabled.gif' alt=''>
					<input type='radio' id='enable_emails_1' name='enable_emails' value='1' {if $enable_emails == true}checked{/if} />
					<label class='t' for='enable_emails_1'>Yes</label>

					<img src='../img/admin/disabled.gif' alt='' />
					<input type='radio' id='enable_emails_0' name='enable_emails' value='0' {if $enable_emails == false}checked{/if} />
					<label class='t' for='enable_emails_0'>No</label>

				</div>
			</div>

			<div class='panel-footer'>
				<button class='btn btn-default pull-right' name='submit_emailmystock_form' type='submit'>
					<i class='process-icon-save'></i> Save
				</button>
			</div>
		</div>
	</div>
	
</form>