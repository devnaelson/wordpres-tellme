<?php
/**
 * Created by PhpStorm.
 * User: cassiovidal
 * Date: 09/07/18
 * Time: 14:39
 */
?>

<fieldset style="border: 1px dashed orange; padding: 5px; margin: 5px;">
    <legend><?php  _e("Zoop - split / Bank and recipient information", "cv_wc_Zoop");?></legend>

    <h2><?php  _e("Business data", "cv_wc_Zoop");?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="cpf"><?php  _e("CPF or CNPJ", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="cpf" onblur="verify_vendor()" id="cpf"/>
				<input type="hidden" name="zoop_vendor_id" id="zoop_vendor_id"/>
            </th>
            <th>
                <label for="type"><?php  _e("Type", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <select name="type" class="regular-text" id="type">
                    <option value="individual" <?php if ( isset($data['zoop_type'])) { echo selected($data['zoop_type'] == 'individual'); } ; ?> > <?php _e("Individual", "cv_wc_Zoop");?> </option>
                    <option value="business" <?php if ( isset($data['zoop_type'])) { echo selected($data['zoop_type'] == 'business'); } ; ?> > <?php _e("Business", "cv_wc_Zoop");?> </option>
                </select>
            </th>
        </tr>
        <tr>
            <th>
                <label for="business_name"><?php  _e("Business name", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_name" id="business_name"
                    <?php
                    if (isset($data['zoop_business_name'])){
                        echo 'value="' . $data['zoop_business_name'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="mcc"><?php  _e("Business category", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <select class="regular-text" name="mcc">
                    <?php
                    foreach ($mcc as $key => $name){
                        echo "<<optgroup label=\"$key\">";
                        foreach ($name as $cat){
                            if (isset($data['zoop_mcc'])){
                                echo "<option " . selected($data['zoop_mcc'] == $cat['code']) . " value= '"
                                    . $cat['code'] ."'>" . $cat['description'] ."</option>";
                            }else{
                                echo "<option value= '". $cat['code'] ."'>" . $cat['description'] ."</option>";
                            }

                        }
                        echo " </optgroup> ";
                    }
                    ?>
                </select>
            </th>
        </tr>
        <tr>
            <th>
                <label for="business_description"><?php  _e("business description", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_description" id="business_description"
                    <?php
                    if (isset($data['zoop_business_description'])){
                        echo 'value="' . $data['zoop_business_description'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="business_phone"><?php  _e("Business phone", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_phone" id="business_phone"
                    <?php
                    if (isset($data['zoop_business_phone'])){
                        echo 'value="' . $data['zoop_business_phone'] . '"';
                    }
                    ?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="business_email"><?php  _e("business email", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_email" id="business_email"
                    <?php
                    if (isset($data['zoop_business_email'])){
                        echo 'value="' . $data['zoop_business_email'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="business_opening_date"><?php  _e("Business opening date", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_opening_date" id="business_opening_date"
                    <?php
                    if (isset($data['zoop_business_opening_date'])){
                        echo 'value="' . $data['zoop_business_opening_date'] . '"';
                    }?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="statement_descriptor"><?php  _e("Statement descriptor", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="statement_descriptor" id="statement_descriptor"
                    <?php
                    if (isset($data['zoop_statement_descriptor'])){
                        echo 'value="' . $data['zoop_statement_descriptor'] . '"';
                    }
                    ?>
                />
            </th>
        </tr>
    </table>
    <h3><?php  _e("Business address", "cv_wc_Zoop");?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="business_address_postal_code"><?php  _e("Postal Code", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_postal_code" id="business_address_postal_code"
                    <?php
                    if (isset($data['zoop_business_address_postal_code'])){
                        echo 'value="' . $data['zoop_business_address_postal_code'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="business_address_line1"><?php  _e("Address", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_line1" id="business_address_line1"
                    <?php
                    if (isset($data['zoop_business_address_line1'])){
                        echo 'value="' . $data['zoop_business_address_line1'] . '"';
                    }
                    ?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="business_address_line2"><?php  _e("Adjunct", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_line2" id="business_address_line2"
                    <?php
                    if (isset($data['zoop_business_address_line2'])){
                        echo 'value="' . $data['zoop_business_address_line2'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="business_address_neighborhood"><?php  _e("Neighborrhood", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_neighborhood" id="business_address_neighborhood"
                    <?php
                    if (isset($data['zoop_business_address_neighborhood'])){
                        echo 'value="' . $data['zoop_business_address_neighborhood'] . '"';
                    }?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="business_address_city"><?php  _e("City", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_city" id="business_address_city"
                    <?php
                    if (isset($data['zoop_business_address_city'])){
                        echo 'value="' . $data['zoop_business_address_city'] . '"';
                    }
                    ?>
                />
            </th>
            <th>
                <label for="business_address_state"><?php  _e("State", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="business_address_state" id="business_address_state"
                    <?php
                    if (isset($data['zoop_business_address_state'])){
                        echo 'value="' . $data['zoop_business_address_state'] . '"';
                    }
                    ?>
                />
            </th>
        </tr>
    </table>
    <h2><?php  _e("Owner data", "cv_wc_Zoop");?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="name"><?php  _e("Name", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="name" id="business_owner_name"
                <?php
                if (isset($data['zoop_owner_first_name']) && $data['zoop_owner_first_name'] !=""){
                    echo 'value="' . $data['zoop_owner_first_name'] . '"';
                } else if (isset($data['zoop_first_name'])){
                    echo 'value="' . $data['zoop_first_name'] . '"';
                }?> />
            </th>
            <th>
                <label for="owner_email"><?php  _e("Email", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="owner_email" id="business_owner_email"
                    <?php
                    if (isset($data['zoop_owner_email']) && $data['zoop_owner_email'] !=""){
                        echo 'value="' . $data['zoop_owner_email'] . '"';
                    } else if (isset($data['zoop_email'])){
                        echo 'value="' . $data['zoop_email'] . '"';
                    }?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="phone"><?php  _e("Phone", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="phone" id="business_owner_phone"
                    <?php
                    if (isset($data['zoop_owner_phone_number']) && $data['zoop_owner_phone_number'] !=""){
                        echo 'value="' . $data['zoop_owner_phone_number'] . '"';
                    } else if (isset($data['zoop_phone_number'])){
                        echo 'value="' . $data['zoop_phone_number'] . '"';
                    }?>
                />
            </th>
            <th>
                <label for="birthdate"><?php  _e("Birthdate", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="birthdate" id="business_owner_birthdate"
                    <?php
                    if (isset($data['zoop_owner_birthdate']) && $data['zoop_owner_birthdate'] !=""){
                        echo 'value="' . $data['zoop_owner_birthdate'] . '"';
                    } else if (isset($data['zoop_birthdate'])){
                        echo 'value="' . $data['zoop_birthdate'] . '"';
                    }?>
                />
            </th>
        </tr>
    </table>
    <h3><?php  _e("Owner address", "cv_wc_Zoop");?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="postal_code"><?php  _e("Postal Code", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="postal_code" id="business_owner_postal_code"
                    <?php
                    if (isset($data['zoop_owner_address_postal_code']) && $data['zoop_owner_address_postal_code'] !=""){
                        echo 'value="' . $data['zoop_owner_address_postal_code'] . '"';
                    } else if (isset($data['zoop_address_postal_code'])){
                        echo 'value="' . $data['zoop_address_postal_code'] . '"';
                    }?>
                />
            </th>
            <th>
                <label for="line1"><?php  _e("Address", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="line1" id="business_owner_line1"
                    <?php
                    if (isset($data['zoop_owner_address_line1']) && $data['zoop_owner_address_line1'] !=""){
                        echo 'value="' . $data['zoop_owner_address_line1'] . '"';
                    } else if (isset($data['zoop_address_line1'])){
                        echo 'value="' . $data['zoop_address_line1'] . '"';
                    }?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="line2"><?php  _e("Adjunct", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="line2" id="business_owner_line2"
                    <?php
                    if (isset($data['zoop_owner_address_line2']) && $data['zoop_owner_address_line2'] !=""){
                        echo 'value="' . $data['zoop_owner_address_line2'] . '"';
                    } else if (isset($data['zoop_address_line2'])){
                        echo 'value="' . $data['zoop_address_line2'] . '"';
                    }?>
                />
            </th>
            <th>
                <label for="neighborhood"><?php  _e("Neighborhood", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="neighborhood" id="business_owner_neighborhood"
                    <?php
                    if (isset($data['zoop_owner_address_neighborhood']) && $data['zoop_owner_address_neighborhood'] !=""){
                        echo 'value="' . $data['zoop_owner_address_neighborhood'] . '"';
                    } else if (isset($data['zoop_address_neighborhood'])){
                        echo 'value="' . $data['zoop_address_neighborhood'] . '"';
                    }?>
                />
            </th>
        </tr>
        <tr>
            <th>
                <label for="city"><?php  _e("City", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="city" id="business_owner_city"
                    <?php
                    if (isset($data['zoop_owner_address_city']) && $data['zoop_owner_address_city'] !=""){
                        echo 'value="' . $data['zoop_owner_address_city'] . '"';
                    } else if (isset($data['zoop_address_city'])){
                        echo 'value="' . $data['zoop_address_city'] . '"';
                    }?>
                />
            </th>
            <th>
                <label for="state"><?php  _e("State", "cv_wc_Zoop");?></label>
            </th>
            <th>
                <input type="text" class="regular-text" name="state" id="business_owner_state"
                    <?php
                    if (isset($data['zoop_owner_address_state']) && $data['zoop_owner_address_state'] !=""){
                        echo 'value="' . $data['zoop_owner_address_state'] . '"';
                    } else if (isset($data['zoop_address_state'])){
                        echo 'value="' . $data['zoop_address_state'] . '"';
                    }?>
                />
            </th>
        </tr>
    </table>
    <h2><?php  _e("Bank data", "cv_wc_Zoop");?></h2>
    <table class="form-table">
        <tr>
            <th>
                <label for="bank_cod"><?php  _e("Bank cod", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" name="bank_cod" class="regular-text" maxlength="3"
                    <?php
                    if (isset($data['zoop_bank_bank_code'])){
                        echo 'value="' . $data['zoop_bank_bank_code'] . '"';
                    }?>
                />
                <br />
                <span class="description">
					<strong>OBS</strong>: <?php  _e("Only numbers", "cv_wc_Zoop");?>
				</span>
            </td>
            <th>
                <label for="holder_name"><?php  _e("Holder name", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" name="holder_name" class="regular-text"
                    <?php
                    if (isset($data['zoop_bank_holder_name'])){
                        echo 'value="' . $data['zoop_bank_holder_name'] . '"';
                    }?>
                />
                <br />
                <span class="description">
                    <strong>OBS</strong>:<?php  _e("Bank account holder", "cv_wc_Zoop");?>
                </span>
            </td>
        </tr>
        <tr>
            <th>
                <label for="bank_agency"><?php  _e("Bank agency", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" name="bank_agency" class="regular-text" maxlength="5"
                    <?php
                    if (isset($data['zoop_bank_routing_number'])){
                        echo 'value="' . $data['zoop_bank_routing_number'] . '"';
                    }?>
                />
                <br />
                <span class="description">
					<strong>OBS</strong>: <?php  _e("4 numbers no digit", "cv_wc_Zoop");?>
                </span>
            </td>
            <th>
                <label for="bank_account"><?php  _e("Bank account", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" name="bank_account" class="regular-text" maxlength="13"
                    <?php
                    if (isset($data['zoop_bank_account_number'])){
                        echo 'value="' . $data['zoop_bank_account_number'] . '"';
                    }?>
                />
                <br />
                <span class="description">
                    <strong>OBS</strong>:<?php  _e("Only numbers with digit", "cv_wc_Zoop");?>
                </span>
            </td>
        </tr>
        <tr>
            <th>
                <label for="bank_taxpayer_id"><?php _e("Bank CPF/CNPJ", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" class="regular-text" name="bank_taxpayer_id"
                    <?php
                    if (isset($data['zoop_bank_taxpayer_id'])){
                        echo 'value="' . $data['zoop_bank_taxpayer_id'] . '"';
                    }?>
                />
            </td>
        </tr>
    </table>
    <h2><?php  _e("Recipient data", "cv_wc_Zoop");?></h2>
    <table class="form-table" style="vertical-align: top">
        <tr>
            <th><label for="recipient_transfer_enabled"><?php  _e("Automatic Transfer", "cv_wc_Zoop");?></label></th>
            <td>
                <input type="radio" name="recipient_transfer_enabled"
                    <?php if ( isset($data['zoop_transfer_enabled']) && $data['zoop_transfer_enabled'] == 'enabled') {
                        echo 'checked="checked"';
                    } ?> value="enabled" ><?php  _e("Enable", "cv_wc_Zoop");?>
                <br />
                <input type="radio" name="recipient_transfer_enabled"
                    <?php if ( isset($data['zoop_transfer_enabled']) && $data['zoop_transfer_enabled'] == 'disabled') {
                        echo 'checked="checked"';
                    }?> value="disabled"><?php  _e("Disable", "cv_wc_Zoop");?>
                <br />
                <span class="description"><?php  _e("Enable automatic tranfer", "cv_wc_Zoop");?></span>
            </td>
            <th><label for="recipient_transfer_interval"><?php  _e("Frequency", "cv_wc_Zoop");?></label></th>
            <td  style="vertical-align: top">
                <select name="recipient_transfer_interval">
                    <option value="daily"
                        <?php if ( isset($data['zoop_transfer_interval'])) {
                            echo selected($data['zoop_transfer_interval'] == 'daily');
                        } ?> > <?php  _e("Daily", "cv_wc_Zoop");?></option>
                    <option value="weekly"
                        <?php if ( isset($data['zoop_transfer_interval'])) {
                            echo selected($data['zoop_transfer_interval'] == 'weekly');
                        } ?> > <?php  _e("Weekly", "cv_wc_Zoop");?></option>
                    <option value="monthly"
                        <?php if ( isset($data['zoop_transfer_interval'])) {
                            echo selected($data['zoop_transfer_interval'] == 'monthly');
                        } ?> ><?php  _e("Monthly", "cv_wc_Zoop");?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="recipient_transfer_day"><?php  _e("Transfer day", "cv_wc_Zoop");?></label></th>
            <td>
                <select name="recipient_transfer_day">
                    <?php for ( $i = 0; $i <= 31; $i++ ) : ?>
                        <option
                            <?php if ( isset($data['zoop_transfer_interval'])) {
                                echo selected($data['zoop_transfer_day'] == $i );
                            }?> value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor?>
                </select>
                <br />
                <span class="description">
                    <?php  _e("Daily: use only 0", "cv_wc_Zoop");?><br />
                    <?php  _e("Weekly: use 1 to 5, where 1 is monday and 5 is friday", "cv_wc_Zoop");?><br />
                    <?php  _e("Monthly: use 1 to 31", "cv_wc_Zoop");?>
                </span>
            </td>
            <th>
                <label for="minimum_transfer_value"><?php  _e("Minimum transfer value", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="text" name="minimum_transfer_value" class="regular-text" maxlength="5"
                    <?php
                    if (isset($data['zoop_transfer_value'])){
                        echo 'value="' . $data['zoop_transfer_value'] . '"';
                    }?>
                />
            </td>
        </tr>
    </table>
    <h3><?php _e("Documents", "cv_wc_Zoop") ;?> </h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="NF"><?php  _e("Invoice or business card", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="file" name="NF" value="" />
            </td>
            <th>
                <label for="address"><?php  _e("Proof of address", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="file" name="address" value="" />
            </td>
            <th>
                <label for="cnpj"><?php  _e("Document", "cv_wc_Zoop");?></label>
            </th>
            <td>
                <input type="file" name="cnpj" value="" />
            </td>
        </tr>

    </table>
</fieldset>

<script>
    jQuery(document).ready(function () {
        jQuery('#cpf').cpfcnpj({
            mask: true,
            ifValid: function (input) {
                input.parent(".form-group").removeClass("has-error");
                var $length = jQuery('#cpf').val().length;
                if ($length < 15){
                    jQuery('#type').val('individual');
                    jQuery('#business_name').prop('disabled', true);
                    jQuery('#business_description').prop('disabled', true);
                    jQuery('#business_phone').prop('disabled', true);
                    jQuery('#business_email').prop('disabled', true);
                    jQuery('#business_opening_date').prop('disabled', true);
                    jQuery('#business_address_postal_code').prop('disabled', true);
                    jQuery('#business_address_line1').prop('disabled', true);
                    jQuery('#business_address_line2').prop('disabled', true);
                    jQuery('#business_address_neighborhood').prop('disabled', true);
                    jQuery('#business_address_city').prop('disabled', true);
                    jQuery('#business_address_state').prop('disabled', true);
                }else{
                    jQuery('#type').val('business');
                    jQuery('#business_name').prop('disabled', false);
                    jQuery('#business_description').prop('disabled', false);
                    jQuery('#business_phone').prop('disabled', false);
                    jQuery('#business_email').prop('disabled', false);
                    jQuery('#business_opening_date').prop('disabled', false);
                    jQuery('#business_opening_date').prop('disabled', false);
                    jQuery('#business_address_postal_code').prop('disabled', false);
                    jQuery('#business_address_line1').prop('disabled', false);
                    jQuery('#business_address_line2').prop('disabled', false);
                    jQuery('#business_address_neighborhood').prop('disabled', false);
                    jQuery('#business_address_city').prop('disabled', false);
                    jQuery('#business_address_state').prop('disabled', false);
                }
            },
            ifInvalid: function (input) {
                var length = input.val().length;
                if (length == 14 || length == 18){
                    input.parent(".form-group").addClass("has-error");
                }else{
                    input.val('');
                    input.parent(".form-group").removeClass("has-error");
                }

            }
        });
        <?php
            if ( isset( $data['zoop_taxpayer_id'] ) ){ ?>
        jQuery('#cpf').val("<?= $data['zoop_taxpayer_id'] ; ?>");
        jQuery('#business_name').prop('disabled', true);
        jQuery('#business_description').prop('disabled', true);
        jQuery('#business_phone').prop('disabled', true);
        jQuery('#business_email').prop('disabled', true);
        jQuery('#business_opening_date').prop('disabled', true);
        jQuery('#business_address_postal_code').prop('disabled', true);
        jQuery('#business_address_line1').prop('disabled', true);
        jQuery('#business_address_line2').prop('disabled', true);
        jQuery('#business_address_neighborhood').prop('disabled', true);
        jQuery('#business_address_city').prop('disabled', true);
        jQuery('#business_address_state').prop('disabled', true);
        <?php
            } elseif ( isset( $data['zoop_ein'] ) ){ ?>
        jQuery('#cpf').val("<?= $data['zoop_ein'] ; ?>");
        jQuery('#business_name').prop('disabled', false);
        jQuery('#business_description').prop('disabled', false);
        jQuery('#business_phone').prop('disabled', false);
        jQuery('#business_email').prop('disabled', false);
        jQuery('#business_opening_date').prop('disabled', false);
        jQuery('#business_opening_date').prop('disabled', false);
        jQuery('#business_address_postal_code').prop('disabled', false);
        jQuery('#business_address_line1').prop('disabled', false);
        jQuery('#business_address_line2').prop('disabled', false);
        jQuery('#business_address_neighborhood').prop('disabled', false);
        jQuery('#business_address_city').prop('disabled', false);
        jQuery('#business_address_state').prop('disabled', false);
        <?php
            }
        ?>

    });
	function verify_vendor(){
		var $cpf = jQuery( "#cpf" ).val().replace(/\D/g,'');
		if ( $cpf.length < 12){
			var $data = {
				'action': 'zoop_get_vendor',
				'taxpayer_id': $cpf
			}
		} else {
			var $data = {
				'action': 'zoop_get_vendor',
				'ein': $cpf
			}
		}
		
		jQuery.ajax({
            url: 'admin-ajax.php',
            type: "POST",
            data: $data,
            dataType: "json",
            success: function (response) {
                jQuery(".progressbar").hide();
                if (response.code == '200') {
					jQuery('#type').val(response.message["type"]);
					jQuery('#business_name').val(response.message[""]);
					jQuery('#mcc').val(response.message["mcc"]); // verificar
					if (response.message["type"] === 'business'){
						jQuery('#business_name').val(response.message["business_name"]);
						jQuery('#business_description').val(response.message["business_description"]);
						jQuery('#business_phone').val(response.message["business_phone"]);
						jQuery('#business_email').val(response.message["business_email"]);
						jQuery('#business_opening_date').val(response.message["business_opening_date"]);
						jQuery('#statement_descriptor').val(response.message["business_opening_date"]);
						jQuery('#business_address_postal_code').val(response.message["business_address"]["postal_code"]);
						jQuery('#business_address_line1').val(response.message["business_address"]["line1"]);
						jQuery('#business_address_line2').val(response.message["business_address"]["line2"]);
						jQuery('#business_address_neighborhood').val(response.message["business_address"]["neighborhood"]);
						jQuery('#business_address_city').val(response.message["business_address"]["city"]);
						jQuery('#business_address_state').val(response.message["business_address"]["state"]);
						
						jQuery('#business_owner_name').val(response.message["owner"]["first_name"]);
						jQuery('#business_owner_email').val(response.message["owner"]["email"]);
						jQuery('#business_owner_phone').val(response.message["owner"]["phone_number"]);
						jQuery('#business_owner_birthdate').val(response.message["owner"]["birthdate"]);
						jQuery('#business_owner_postal_code').val(response.message["owner"]["address"]["postal_code"]);
						jQuery('#business_owner_line1').val(response.message["owner"]["address"]["line1"]);
						jQuery('#business_owner_line2').val(response.message["owner"]["address"]["line2"]);
						jQuery('#business_owner_neighborhood').val(response.message["owner"]["address"]["neighborhood"]);
						jQuery('#business_owner_city').val(response.message["owner"]["address"]["city"]);
						jQuery('#business_owner_state').val(response.message["owner"]["address"]["state"]);
					} else if (response.message["type"] === 'individual'){
						jQuery('#business_owner_name').val(response.message["first_name"]);
						jQuery('#business_owner_email').val(response.message["email"]);
						jQuery('#business_owner_phone').val(response.message["phone_number"]);
						jQuery('#business_owner_birthdate').val(response.message["birthdate"]);
						jQuery('#business_owner_postal_code').val(response.message["address"]["postal_code"]);
						jQuery('#business_owner_line1').val(response.message["address"]["line1"]);
						jQuery('#business_owner_line2').val(response.message["address"]["line2"]);
						jQuery('#business_owner_neighborhood').val(response.message["address"]["neighborhood"]);
						jQuery('#business_owner_city').val(response.message["address"]["city"]);
						jQuery('#business_owner_state').val(response.message["address"]["state"]);
						/*jQuery('#business_bank_cod').val();//API com erro.
						jQuery('#business_bank_holder').val();//verificar
						jQuery('#business_bank_agency').val();//verificar
						jQuery('#business_bank_account').val();//verificar 41813402000192
						jQuery('#business_bank_cpf').val(); // verificar*/
					} else if (response.message.error.message) {
					    alert (response.message.error.message);
					}
					jQuery('#zoop_vendor_id').val(response.message["id"]);
					
                } else {
                    // error
                }
            },
            error: function () {
                jQuery(".progressbar").hide();
                jQuery('#success_msg').addClass('hide');
                jQuery('#error_msg').removeClass('hide').html("<?php _e('An error has occurred in request. Try Again', 'cv_wc_Zoop'); ?>");
            }
        });
	}
</script>