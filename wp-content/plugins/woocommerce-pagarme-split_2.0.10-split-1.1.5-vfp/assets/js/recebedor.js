/**
 * recebedor.js
 */
jQuery(document).ready(function() {
    jQuery('input[name=save2').click(function( event ) {
        var tipo_post = jQuery('#post_type').val();
        if ( tipo_post == 'vm50_recebedor' ) {
            event.preventDefault();
            var post      = jQuery('#post_ID').val();
            var box_nonce = jQuery('#_wpnonce').val();
            var recebe_id = jQuery('#vm50_recebedor_id').val();
            var c_banc_id = jQuery('#vm50_recebedor_conta_bancaria_id').val();
            var email     = jQuery('#vm50_recebedor_email').val();
            var banco_cod = jQuery('#vm50_recebedor_conta_bancaria_banco').val();
            var agenc_num = jQuery('#vm50_recebedor_conta_bancaria_agencia').val();
            var agenc_dig = jQuery('#vm50_recebedor_conta_bancaria_agencia_digito').val();
            var cc_num    = jQuery('#vm50_recebedor_conta_bancaria_ccorrente').val();
            var cc_dig    = jQuery('#vm50_recebedor_conta_bancaria_ccorrente_digito').val();
            var cc_nome   = jQuery('#vm50_recebedor_conta_bancaria_nome').val();
            var doc_tipo  = jQuery('#vm50_recebedor_conta_bancaria_documento_tipo').val();
            var doc_num   = jQuery('#vm50_recebedor_conta_bancaria_documento_numero').val();
            var trans_aut = jQuery('input[name=vm50_recebedor_transferencia_automatica]:checked').val();
            var trans_per = jQuery('#vm50_recebedor_transferencia_automatica_periodicidade option:selected').val();
            var trans_sem = jQuery('#vm50_recebedor_transferencia_automatica_periodicidade_semanal option:selected').val();
            var trans_men = jQuery('#vm50_recebedor_transferencia_automatica_periodicidade_mensal option:selected').val();

            banco_cod = banco_cod.replace(/[^0-9\.]/g,'');
            agenc_num = agenc_num.replace(/[^0-9\.]/g,'');
            agenc_dig = agenc_dig.replace(/[^0-9\.]/g,'');
            cc_num    = cc_num.replace(/[^0-9\.]/g,'');
            cc_dig    = cc_dig.replace(/[^0-9\.]/g,'');
            doc_num   = doc_num.replace(/[^0-9\.]/g,'');
            jQuery('#vm50_recebedor_conta_bancaria_banco').val(banco_cod);
            jQuery('#vm50_recebedor_conta_bancaria_agencia').val(agenc_num);
            jQuery('#vm50_recebedor_conta_bancaria_agencia_digito').val(agenc_dig);
            jQuery('#vm50_recebedor_conta_bancaria_ccorrente').val(cc_num);
            jQuery('#vm50_recebedor_conta_bancaria_ccorrente_digito').val(cc_dig);
            jQuery('#vm50_recebedor_conta_bancaria_documento_numero').val(doc_num);

            var chk_email = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var email_chk = chk_email.test(email);

            var erro = '';
            if ( !email_chk ) {
                erro += 'Email inválido.<br />';
            }
            if ( banco_cod == '' ) {
                erro += 'Número do banco inválido.<br />';
            }
            if ( agenc_num == '' ) {
                erro += 'Agência inválida.<br />';
            }
            if ( agenc_dig == '' ) {
                erro += 'Dígito da agência inválido.<br />';
            }
            if ( cc_num == '' ) {
                erro += 'Conta corrente inválida.<br />';
            }
            if ( cc_dig == '' ) {
                erro += 'Dígito da conta corrente inválido.<br />';
            }
            if ( doc_num == '' ) {
                erro += 'Número de documento inválido.<br />';
            }
            if ( erro != '' ) {
                erro = '<p style="color:#cc0000;padding:8px 5px;background-color:#FFFF00;">' + erro + '</p>';
                jQuery('#vm50_recebedor_mensagem').html(erro);
            } else {
                var data = {
                    'action'               : 'vm50_recebedor_salva',
                    'post_id'              : post,
                    'vm50_recebedor_campos_meta_box_nonce' : box_nonce,
                    'vm50_recebedor_email' : email,
                    'vm50_recebedor_id'    : recebe_id,
                    'vm50_recebedor_conta_bancaria_id'    : c_banc_id,
                    'vm50_recebedor_conta_bancaria_banco' : banco_cod,
                    'vm50_recebedor_conta_bancaria_agencia'          : agenc_num,
                    'vm50_recebedor_conta_bancaria_agencia_digito'   : agenc_dig,
                    'vm50_recebedor_conta_bancaria_ccorrente'        : cc_num,
                    'vm50_recebedor_conta_bancaria_ccorrente_digito' : cc_dig,
                    'vm50_recebedor_conta_bancaria_nome'             : cc_nome,
                    'vm50_recebedor_conta_bancaria_documento_tipo'   : doc_tipo,
                    'vm50_recebedor_conta_bancaria_documento_numero' : doc_num,
                    'vm50_recebedor_transferencia_automatica'        : trans_aut,
                    'vm50_recebedor_transferencia_automatica_periodicidade'         : trans_per,
                    'vm50_recebedor_transferencia_automatica_periodicidade_semanal' : trans_sem,
                    'vm50_recebedor_transferencia_automatica_periodicidade_mensal'  : trans_men
                };
                jQuery.post(referenciaPagarme.pagarmeadminAjaxUrl, data, function(response) {
                    jQuery('#vm50_recebedor_mensagem').html(response);
//                    jQuery('form').submit();
                });
            }
        }
    });
});


function vm50_recebedor_periodicidade() {
    if ( jQuery('#vm50_recebedor_transferencia_automatica_s').is(':checked') ) {
        jQuery('#vm50_tranferencia_automatica').show();
    } else {
        jQuery('#vm50_tranferencia_automatica').hide();
    }
}


function vm50_recebedor_automatica() {
    var tipo = jQuery('#vm50_recebedor_transferencia_automatica_periodicidade').val();
    if ( tipo == 'daily' ) {
        jQuery('#vm50_tranferencia_automatica_semanal').hide();
        jQuery('#vm50_tranferencia_automatica_mensal').hide();
    } else if ( tipo == 'weekly' ) {
        jQuery('#vm50_tranferencia_automatica_semanal').show();
        jQuery('#vm50_tranferencia_automatica_mensal').hide();
    } else {
        jQuery('#vm50_tranferencia_automatica_semanal').hide();
        jQuery('#vm50_tranferencia_automatica_mensal').show();
    }
}
