<?php
/**
 * Credit Card - Checkout form.
 *
 */
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="zoop_switch_field">
    <div class="zoop_switch_title">
        <?= _e('Select your payment form:', 'cv_wc_Zoop');?>
    </div>
    <input type="radio" id="zoop_option_card" name="zoop_option" value="card" checked="checked">
    <label for="zoop_option_card" style="min-height: 80px"><?php esc_html_e('Credit card', 'cv_wc_Zoop'); ?></label>
    <input type="radio" id="zoop_option_bankslip" name="zoop_option" value="bankslip">
    <label for="zoop_option_bankslip" style="min-height: 80px"> <?php esc_html_e('Bank slip', 'cv_wc_Zoop'); ?></label>
</div>



<div id="zoop_card">
    <?php
    if ($cards) {
        ?>
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" id="cards">
            <thead>
                <tr>
                    <?php foreach ($columns as $column_id => $column_name) : ?>
                        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($cards as $card) {
                    ?>
                    <tr class="woocommerce-orders-table__row order" id="<?php echo $card['id']; ?>">
                        <td class="woocommerce-orders-table__cell ">
                            <?php echo $card['name'] ?>
                        </td>
                        <td class="woocommerce-orders-table__cell ">
                            <?php echo $card['expires'] ?>
                        </td>
                        <td class="woocommerce-orders-table__cell ">
                            <?php echo $card['brand'] ?>
                        </td>
                        <td class="woocommerce-orders-table__cell ">
                            <?php echo $card['last4'] ?>
                        </td>
                        <td class="woocommerce-orders-table__cell ">
                            <input name="card_id" type="radio" id="card_id_<?php echo $card['id'] ?>" value="<?php echo $card['id'] ?>">
                            <label for="card_id_<?php echo $card['id'] ?>"><?php _e('Use this Card', 'cv_wc_Zoop'); ?></label>

                        </td>
                    </tr>
                <?php } ?>
                <tr class="woocommerce-orders-table__row order" id="">
                    <td class="woocommerce-orders-table__cell ">

                    </td>
                    <td class="woocommerce-orders-table__cell ">

                    </td>
                    <td class="woocommerce-orders-table__cell ">

                    </td>
                    <td class="woocommerce-orders-table__cell ">

                    </td>
                    <td class="woocommerce-orders-table__cell ">
                        <input name="card_id" type="radio" id="card_id" value="new">
                        <label for="card_id"><?php _e('New Card', 'cv_wc_Zoop'); ?></label>

                    </td>
                </tr>
            </tbody>
        </table>
    <?php }
    ?>
    <br/>
    <div id="card-wrapper" style="width: 100%; max-width: 100%"></div><br/>
    <p class="form-row form-row-wide">
        <label for="cc_number"><?php esc_html_e('Card number', 'cv_wc_Zoop'); ?> <span class="required">*</span></label>
        <input placeholder="<?php _e('Card number', 'cv_wc_Zoop'); ?> " style="width: 100%; max-width: 100%" type="tel" name = "cc_number"id="cc_number" class="cc required" size="20" >
    </p>
    <p class="form-row form-row-wide">
        <label for="cc_name"><?php esc_html_e('Full name', 'cv_wc_Zoop'); ?> <span class="required">*</span></label>
        <input placeholder="<?php _e('Full name', 'cv_wc_Zoop'); ?> " style="width: 100%; max-width: 100%" type="text" name = "cc_name" id="cc_name" class="cc required" size="20">
    </p>
    <p class="form-row form-row-wide">
        <label for="cc_expiry"><?php esc_html_e('Expires', 'cv_wc_Zoop'); ?> <span class="required">*</span></label>

        <input placeholder="<?php _e('MM/AAAA', 'cv_wc_Zoop'); ?> " style="width: 100%; max-width: 100%" type="tel" name = "cc_expiry" id="cc_expiry" class="cc required" size="10">

        <label for="cc_cvc"><?php esc_html_e('CVC', 'cv_wc_Zoop'); ?> <span class="required">*</span></label>

        <input placeholder="<?php _e('CVC', 'cv_wc_Zoop'); ?> " style="width: 100%; max-width: 100%" type="tel" name = "cc_cvc" id="cc_cvc" class="cc required" size="10">

        <input id="card_type" name="card_type" type="hidden">
        <input name="tot" type="hidden" value="<?php echo $cart_total; ?>">
    </p>
    <p class="form-row form-row-wide">
        <label for="installments"><?php esc_html_e('Installments', 'cv_wc_Zoop'); ?> <span class="required">*</span></label>
        <select name="installments" id="zoop-installments" class="cc required" style="width: 100%; max-width: 100%">
            <?php
            foreach ($installments as $number => $installment) :
                if ($number <= $freeInstallments):
                ?>
                <option value="<?php echo $number; ?>">
                    <?php printf(esc_html__('%1$dx de %2$s sem juros - R$ %3$s', 'cv_wc_Zoop'),
                        absint($number),
                        $installment,
                        number_format((float)$installment*$number, 2, ',', '.')); ?>
                </option>
            <?php
                else:
            ?>
                <option value="<?php echo $number; ?>">
                    <?php printf(esc_html__('%1$dx de %2$s com juros de %3$s%%  - R$ %4$s', 'cv_wc_Zoop'),
                        absint($number),
                        $installment,
                        $tax,
                        number_format((float)$installment * $number, 2,'.', ',')); ?></option>
                <?php
                endif;
            endforeach;
            ?>
        </select>
    </p>
</div>
<script>
    jQuery(document).ready(function ($) {
        if (jQuery('form[name="checkout"]').length) {
            var card = new Card({
                form: 'form[name="checkout"]',
                container: '#card-wrapper',
                formSelectors: {
                    numberInput: '#cc_number',
                    expiryInput: '#cc_expiry',
                    cvcInput: '#cc_cvc',
                    nameInput: '#cc_name'
                }
            });
        } else {
            var card = new Card({
                form: 'form',
                container: '#card-wrapper',
                formSelectors: {
                    numberInput: '#cc_number',
                    expiryInput: '#cc_expiry',
                    cvcInput: '#cc_cvc',
                    nameInput: '#cc_name'
                }
            });
        }
        jQuery('#cc_number').validateCreditCard(function (result) {
            if (result && result.hasOwnProperty('card_type') && result.card_type && result.card_type.hasOwnProperty('name')) {
                $('#card_type').val(result.card_type.name);
            } else {
                $('#card_type').val('');
            }
        });
        jQuery('input[type=radio]').click(function(){
            if( $(this).val() == 'bankslip' ){
                jQuery('#zoop_card').hide();
            }else{
                jQuery('#zoop_card').show();
            }
        });
    });
</script>
<style>
    .zoop_switch_field {
        font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
        padding: 5px;
        overflow: hidden;
    }

    .zoop_switch_title {
        margin-bottom: 6px;
    }

    .zoop_switch_field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .zoop_switch_field label {
        float: left;
    }

    .zoop_switch_field label {
        margin: 0px !important;
        display: inline-block;
        width: 45%;
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        font-weight: normal;
        text-align: center;
        text-shadow: none;
        padding: 6px 14px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        -webkit-transition: all 0.1s ease-in-out;
        -moz-transition:    all 0.1s ease-in-out;
        -ms-transition:     all 0.1s ease-in-out;
        -o-transition:      all 0.1s ease-in-out;
        transition:         all 0.1s ease-in-out;
    }

    .zoop_switch_field label:hover {
        cursor: pointer;
    }

    .zoop_switch_field input:checked + label {
        background-color: #A5DC86;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .zoop_switch_field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }

    .zoop_switch_field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }
    .jp-card{
        min-width: 200px !important;

    }
    .jp-card .jp-card-front .jp-card-lower .jp-card-name{
        font-size: 14px !important;
    }
    .jp-card .jp-card-front .jp-card-lower .jp-card-number{
        font-size: 14px !important;
    }
    .jp-card .jp-card-front .jp-card-lower .jp-card-expiry{
        font-size: 14px !important;
    }
    .jp-card .jp-card-front .jp-card-lower .jp-card-cvc{
        font-size: 14px !important;
    }


</style>