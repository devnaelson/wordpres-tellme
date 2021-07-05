<div id="success_msg" class="success_msg hide"></div>
<div id="error_msg" class="error_msg hide"></div>
<input type="button" class="woocommerce-Button button" name="cancel_payment" id="cancel_payment"
       onclick="deltransaction(<?php  echo "'$zoop_transaction_id' ,'$order_id', '$total'"; ?>)"
           value=" <?php _e('Cancel payment', 'cv_wc_Zoop'); ?>">
<div class="progressbar"></div>
<script>
    function deltransaction($id, $order_id, $total) {
        jQuery(".progressbar").show();
        $id_class = '#' + $id;
        jQuery.ajax({
            url: 'admin-ajax.php',
            type: "POST",
            data: {
                'action': 'zoopcancelregularpayment',
                'transaction_id': $id,
                'order_id': $order_id,
                'total' : $total
            },
            dataType: "json",
            success: function (response) {
                jQuery(".progressbar").hide();
                if (response.code == '200') {
                    jQuery('#error_msg').addClass('hide');
                    jQuery('#success_msg').removeClass('hide').html(response.message);
                    jQuery('#cancel_payment').addClass('hide');
                } else {
                    jQuery('#success_msg').addClass('hide');
                    jQuery('#error_msg').removeClass('hide').html(response.message);
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

