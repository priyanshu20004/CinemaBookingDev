<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$plugin_menu = PJ_VIEWS_PATH . sprintf('pjLayouts/elements/menu_%s.php', $controller->getConst('PLUGIN_NAME'));
	if (is_file($plugin_menu))
	{
		include $plugin_menu;
	}
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	?>
	<style type="text/css">
	label[for="status_cancelled"].ui-state-active{
		background: #FEFEFE;
		color: black;
	}
	label[for="status_not_paid"].ui-state-active{
		background: red;
		color: #fff;
		text-shadow: 1px 1px 1px #444;
	}
	label[for="status_paid"].ui-state-active{
		background: green;
		color: white;
		text-shadow: 1px 1px 1px #333;
	}
	</style>
	<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjInvoice&amp;action=pjActionCreateInvoice" method="post" class="pj-form form" id="frmCreateInvoice">
		<input type="hidden" name="invoice_create" value="1" />
		<input type="hidden" name="tmp" value="<?php echo @$_REQUEST['tmp']; ?>" />
		<input type="hidden" name="order_id" value="<?php echo @$_REQUEST['order_id']; ?>" />
		
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php __('plugin_invoice_i_details');?></a></li>
				<li><a href="#tabs-2"><?php __('plugin_invoice_i_client');?></a></li>
			</ul>
			<div id="tabs-1">
				<?php pjUtil::printNotice(@$titles['PIN10'], @$bodies['PIN10']); ?>
		
				<fieldset class="fieldset light">
					<legend><?php __('plugin_invoice_general_info'); ?></legend>
					<div class="float_left w300">
						<p>
							<label class="title"><?php __('plugin_invoice_i_uuid'); ?></label>
							<span class="left h30"><input type="text" name="uuid" class="pj-form-field w100" value="<?php echo @$tpl['uuid']; ?>"/></span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_issue_date'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-after">
								<input type="text" name="issue_date" class="pj-form-field w80 datepick pointer" readonly="readonly" value="<?php echo pjUtil::formatDate(@$_REQUEST['issue_date'], "Y-m-d", $tpl['option_arr']['o_date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
								<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
							</span>
						</p>
					</div>
					<div class="float_right w400">
						<p>
							<label class="title"><?php __('plugin_invoice_i_order_id'); ?></label>
							<span class="left h30"><?php
							if (defined("PJ_INVOICE_PLUGIN"))
							{
							    ?><a href="<?php echo PJ_INSTALL_URL . str_replace('{ORDER_ID}', @$_REQUEST['order_id'], PJ_INVOICE_PLUGIN); ?>"><?php echo pjSanitize::html(@$_REQUEST['order_id']); ?></a><?php
							} else {
							    echo pjSanitize::html(@$_REQUEST['order_id']);
							}
							?></span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_due_date'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-after">
								<input type="text" name="due_date" class="pj-form-field w80 datepick pointer" readonly="readonly" value="<?php echo pjUtil::formatDate(@$_REQUEST['due_date'], "Y-m-d", $tpl['option_arr']['o_date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
								<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
							</span>
						</p>
					</div>
					<br class="clear_both" />
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('plugin_invoice_save'); ?>" class="pj-button" />
					</p>
				</fieldset>
				
				<fieldset class="fieldset white">
					<legend><?php __('plugin_invoice_items_info'); ?></legend>
					<div id="grid_items"></div>
					<input type="button" class="t5 pj-button plugin_invoice_add_item" value="<?php __('plugin_invoice_add'); ?>" />
				</fieldset>
				
				<fieldset class="fieldset sky">
					<legend><?php __('plugin_invoice_payment_info'); ?></legend>
					<div class="float_left w350">
						<p>
							<label class="title"><?php __('plugin_invoice_i_subtotal'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="subtotal" id="subtotal" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['subtotal']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_discount'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="discount" id="discount" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['discount']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_tax'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="tax" id="tax" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['tax']); ?>" />
							</span>
						</p>
						<?php if ((int) $tpl['config_arr']['si_include'] === 1 && (int) $tpl['config_arr']['si_shipping'] === 1) : ?>
						<p>
							<label class="title"><?php __('plugin_invoice_i_shipping'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="shipping" id="shipping" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['shipping']); ?>" />
							</span>
						</p>
						<?php endif; ?>
						<p>
							<label class="title"><?php __('plugin_invoice_i_total'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="total" id="total" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['total']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_amount_paid'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="paid_deposit" id="paid_deposit" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['paid_deposit']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_amount_due'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, !empty($_REQUEST['currency']) ? $_REQUEST['currency'] : $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
								<input type="text" name="amount_due" id="amount_due" class="pj-form-field number w80" value="<?php echo pjSanitize::html(@$_REQUEST['amount_due']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_currency'); ?></label>
							<span class="left">
								<select name="currency" id="currency" class="pj-form-field w100">
								<option value="">---</option>
								<?php
								foreach (__('currencies', true) as $currency)
								{
									?><option value="<?php echo $currency; ?>"<?php echo $currency == @$_REQUEST['currency'] ? ' selected="selected"' : NULL; ?>><?php echo $currency; ?></option><?php
								}
								?>
								</select>
							</span>
						</p>
						<p>
							<label class="title">&nbsp;</label>
							<input type="submit" value="<?php __('plugin_invoice_save'); ?>" class="pj-button" />
						</p>
					</div>
					<div class="float_right w350">
						<p class="tblError">
							<label class="title"><?php __('plugin_invoice_i_status'); ?></label><br/>
							<span class="left h30 block" id="boxStatus">
							<?php
							foreach (__('plugin_invoice_statuses', true) as $k => $v)
							{
								?><input type="radio" name="status" id="status_<?php echo $k; ?>" value="<?php echo $k; ?>"<?php echo @$_REQUEST['status'] == $k ? ' checked="checked"' : NULL; ?> /> <label for="status_<?php echo $k; ?>"><?php echo $v; ?></label><?php
							}
							?>
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_notes'); ?></label><br/>
							<span class="left">
								<textarea name="notes" id="notes" class="pj-form-field" style="width: 325px; height: 236px"><?php echo pjSanitize::html(@$_REQUEST['notes']); ?></textarea>
							</span>
						</p>
					</div>
					<br class="clear_both" />
				</fieldset>
			</div><!-- tabs-1 -->
			<div id="tabs-2">
				<?php pjUtil::printNotice(@$titles['PIN11'], @$bodies['PIN11']); ?>
		
				<fieldset class="fieldset white">
					<legend><?php __('plugin_invoice_billing_info'); ?></legend>
					<p>
						<label class="title"><?php __('plugin_invoice_i_billing_address'); ?></label>
						<span class="left">
							<input type="text" name="b_billing_address" id="b_billing_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_billing_address']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_company'); ?></label>
						<span class="left">
							<input type="text" name="b_company" id="b_company" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_company']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_name'); ?></label>
						<span class="left">
							<input type="text" name="b_name" id="b_name" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_name']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_address'); ?></label>
						<span class="left">
							<input type="text" name="b_address" id="b_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_address']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_street_address'); ?></label>
						<span class="left">
							<input type="text" name="b_street_address" id="b_street_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_street_address']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_city'); ?></label>
						<span class="left">
							<input type="text" name="b_city" id="b_city" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_city']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_state'); ?></label>
						<span class="left">
							<input type="text" name="b_state" id="b_state" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_state']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_zip'); ?></label>
						<span class="left">
							<input type="text" name="b_zip" id="b_zip" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['b_zip']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_phone'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
							<input type="text" name="b_phone" id="b_phone" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['b_phone']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_fax'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
							<input type="text" name="b_fax" id="b_fax" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['b_fax']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_email'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
							<input type="text" name="b_email" id="b_email" class="pj-form-field email" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['b_email']); ?>" />
						</span>
					</p>
					<p>
						<label class="title"><?php __('plugin_invoice_i_url'); ?></label>
						<span class="pj-form-field-custom pj-form-field-custom-before">
							<span class="pj-form-field-before"><abbr class="pj-form-field-icon-url"></abbr></span>
							<input type="text" name="b_url" id="b_url" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['b_url']); ?>" />
						</span>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="<?php __('plugin_invoice_save'); ?>" class="pj-button float_left align_middle" />
					</p>
				</fieldset>
				
				<?php
				if ((int) $tpl['config_arr']['si_include'] === 1)
				{
					?>
					<fieldset class="fieldset white">
						<legend><?php __('plugin_invoice_shipping_info'); ?></legend>
						<p>
							<label class="title"><?php __('plugin_invoice_i_is_shipped'); ?></label>
							<span class="left">
								<input type="checkbox" name="s_is_shipped" value="1"<?php echo (int) @$_REQUEST['s_is_shipped'] === 1 ? ' checked="checked"' : NULL; ?> />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_shipping_date'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-after">
								<input type="text" name="s_date" id="s_date" class="pj-form-field w80 datepick pointer" readonly="readonly" value="<?php echo pjUtil::formatDate(@$_REQUEST['s_date'], "Y-m-d", $tpl['option_arr']['o_date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
								<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_shipping_terms'); ?></label>
							<span class="left">
								<textarea name="s_terms" id="s_terms" class="pj-form-field" style="width: 500px; height: 150px"><?php echo pjSanitize::html(@$_REQUEST['s_terms']); ?></textarea>
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_shipping_address'); ?></label>
							<span class="left">
								<input type="text" name="s_shipping_address" id="s_shipping_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_shipping_address']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_company'); ?></label>
							<span class="left">
								<input type="text" name="s_company" id="s_company" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_company']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_name'); ?></label>
							<span class="left">
								<input type="text" name="s_name" id="s_name" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_name']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_address'); ?></label>
							<span class="left">
								<input type="text" name="s_address" id="s_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_address']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_street_address'); ?></label>
							<span class="left">
								<input type="text" name="s_street_address" id="s_street_address" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_street_address']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_city'); ?></label>
							<span class="left">
								<input type="text" name="s_city" id="s_city" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_city']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_state'); ?></label>
							<span class="left">
								<input type="text" name="s_state" id="s_state" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_state']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_zip'); ?></label>
							<span class="left">
								<input type="text" name="s_zip" id="s_zip" class="pj-form-field w500" value="<?php echo pjSanitize::html(@$_REQUEST['s_zip']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_phone'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
								<input type="text" name="s_phone" id="s_phone" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['s_phone']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_fax'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
								<input type="text" name="s_fax" id="s_fax" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['s_fax']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_email'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
								<input type="text" name="s_email" id="s_email" class="pj-form-field email" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['s_email']); ?>" />
							</span>
						</p>
						<p>
							<label class="title"><?php __('plugin_invoice_i_url'); ?></label>
							<span class="pj-form-field-custom pj-form-field-custom-before">
								<span class="pj-form-field-before"><abbr class="pj-form-field-icon-url"></abbr></span>
								<input type="text" name="s_url" id="s_url" class="pj-form-field" style="width: 473px" value="<?php echo pjSanitize::html(@$_REQUEST['s_url']); ?>" />
							</span>
						</p>
						
						<p>
							<label class="title">&nbsp;</label>
							<input type="submit" value="<?php __('plugin_invoice_save'); ?>" class="pj-button float_left align_middle" />
						</p>
						
					</fieldset>
					<?php
				}
				?>
				<?php
				if(!empty($tpl['config_arr']))
				{
					?>
					<input type="hidden" name="y_logo" value="<?php echo pjSanitize::html($tpl['config_arr']['y_logo']); ?>" />
					<input type="hidden" name="y_company" value="<?php echo pjSanitize::html($tpl['config_arr']['y_company']); ?>" />
					<input type="hidden" name="y_name" value="<?php echo pjSanitize::html($tpl['config_arr']['y_name']); ?>" />
					<input type="hidden" name="y_street_address" value="<?php echo pjSanitize::html($tpl['config_arr']['y_street_address']); ?>" />
					<input type="hidden" name="y_country" value="<?php echo pjSanitize::html($tpl['config_arr']['y_country']); ?>" />
					<input type="hidden" name="y_city" value="<?php echo pjSanitize::html($tpl['config_arr']['y_city']); ?>" />
					<input type="hidden" name="y_state" value="<?php echo pjSanitize::html($tpl['config_arr']['y_state']); ?>" />
					<input type="hidden" name="y_zip" value="<?php echo pjSanitize::html($tpl['config_arr']['y_zip']); ?>" />
					<input type="hidden" name="y_phone" value="<?php echo pjSanitize::html($tpl['config_arr']['y_phone']); ?>" />
					<input type="hidden" name="y_fax" value="<?php echo pjSanitize::html($tpl['config_arr']['y_fax']); ?>" />
					<input type="hidden" name="y_email" value="<?php echo pjSanitize::html($tpl['config_arr']['y_email']); ?>" />
					<input type="hidden" name="y_url" value="<?php echo pjSanitize::html($tpl['config_arr']['y_url']); ?>" />
					<?php
				} 
				?>
			</div><!-- tabs-2 -->
		</div>
		
		
		
		
		<br class="clear_both" />
	</form>
	
	<div id="dialogAddItem" style="display: none" title="<?php __('plugin_invoice_add_item_title'); ?>"></div>
	<div id="dialogEditItem" style="display: none" title="<?php __('plugin_invoice_edit_item_title'); ?>"></div>
	
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.qty_is_int = <?php echo (int) @$tpl['config_arr']['o_qty_is_int'] === 1 ? 'true' : 'false'; ?>;
	pjGrid.o_use_qty_unit_price = <?php echo (int) @$tpl['config_arr']['o_use_qty_unit_price'] === 1 ? 'true' : 'false'; ?>;
	var myLabel = myLabel || {};
	myLabel.btn_cancel = "<?php __('btnCancel'); ?>";
	myLabel.btn_save = "<?php __('plugin_invoice_save'); ?>";
	myLabel.btn_update = "<?php __('btnUpdate'); ?>";
	myLabel.btn_send = "<?php __('btnSend'); ?>";
	myLabel.i_item = "<?php __('plugin_invoice_i_item'); ?>";
	myLabel.i_qty = "<?php __('plugin_invoice_i_qty'); ?>";
	myLabel.i_unit = "<?php __('plugin_invoice_i_unit'); ?>";
	myLabel.i_amount = "<?php __('plugin_invoice_i_amount'); ?>";
	myLabel.uuid_exists = "<?php __('plugin_invoice_i_uuid_exists'); ?>";
	myLabel.status_required = <?php __encode('plugin_invoice_status_required'); ?>;
	</script>
	<?php
}
?>