/**
 * painel_recebedor.js
 */


function vm50_acessar_recebedor() {
    var rcb_email = jQuery('#vm50-painel-login-email').val();
    var rcb_docum = jQuery('#vm50-painel-login-docum').val();
    rcb_docum     = rcb_docum.replace(/[^0-9\.]/g,'');
    var chk_email = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var email_chk = chk_email.test(rcb_email);
    var erro = '';
    if ( !email_chk ) {
        erro += 'Email inválido.<br />';
    }
alert(rcb_docum.length);
    if ( ( rcb_docum.length != 11 ) && ( rcb_docum.length != 14 ) ) {
        erro += 'Número de documento inválido. ';
    }
    if ( erro != '' ) {
        jQuery('#vm50-painel-resumo').html('<p class="vm50-painel-resumo-erro">'+erro+'</p>');
        return;
    }
    jQuery('#vm50-painel-resumo').html('Aguarde...');
    var data = {
        'action'    : 'vm50_painel_recebedor_acessa',
        'email'     : rcb_email,
        'documento' : rcb_docum,
    };
    jQuery.post(referenciaPainel.paineladminAjaxUrl, data, function(response) {
        jQuery('#vm50-painel-resumo').html(response);
    });
}

