<?php
/**
 * Pagarme-split views Functions
 *
 * PHP version 5
 *
 * @category Views
 * @package  Pagarme-split/views
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

add_action( 'show_user_profile', 'my_show_bank_profile_fields' );
add_action( 'edit_user_profile', 'my_show_bank_profile_fields' );
/**
 * Fields to show on bank user profile.
 *
 * @param WP_User $user User object to build form fields.
 */
function my_show_bank_profile_fields( $user ) {
	$user_id = $user->ID;
	$user_receiver = get_the_author_meta( 'user_recebedor', $user_id );
?>
<fieldset style="border: 1px dashed red; padding: 5px; margin: 5px;">
	<legend>Pagar.me-split / Informações Bancárias e Recipientes</legend>
	
	<h3>Informações Bancarias</h3>
	<table class="form-table">
		<tr>
			<th><label for="bank_id">Identificação do Banco</label></th>
			<td>
				<input type="text" name="bank_id" class="regular-text" readonly="readonly" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_id', $user_id ) ) ?>">
				<br />
				<span class="description">Id do banco no Pagar.me.</span>
			</td>
		</tr>
		<tr>
			<th><label for="bank_cod">Código do Banco</label></th>
			<td>
				<input type="text" name="bank_cod" class="regular-text" maxlength="3" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_cod', $user_id ) ) ?>">
				<br />
				<span class="description">
					Código do banco
					<br />
					<strong>OBS</strong>: Deve conter 3 caracteres, apenas números
				</span>
			</td>
		</tr>
		<tr>
			<th><label for="bank_agency">Agência bancária</label></th>
			<td>
				<input type="text" name="bank_agency" class="regular-text" maxlength="5" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_agency', $user_id ) ) ?>">
				<br />
				<span class="description">
					Agência onde conta foi criada.
					<br />
					<strong>OBS</strong>: Limite de 5 caracteres, apenas números
				</span>
			</td>
			<th><label for="bank_agency_digit">Dígito agência bancária</label></th>
			<td>
				<input type="text" name="bank_agency_digit" class="regular-text" maxlength="1" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_agency_digit', $user_id ) ) ?>">
				<br />
				<span class="description">
					Dígito verificador da agência (se houver).
					<br />
					<strong>OBS</strong>: Deve conter 1 dígito, apenas números
				</span>
			</td>
		</tr>
		<tr>
			<th><label for="bank_account">Conta bancária</label></th>
			<td>
				<input type="text" name="bank_account" class="regular-text" maxlength="13" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_account', $user_id ) ) ?>">
				<br />
				<span class="description">
					Número da conta bancária.
					<br />
					<strong>OBS</strong>: Limite de 13 caracteres, apenas números
				</span>
			</td>
			<th><label for="bank_account_digit">Dígito conta bancária</label></th>
			<td>
				<input type="text" name="bank_account_digit" class="regular-text" maxlength="2" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_account_digit', $user_id ) )?>">
				<br />
				<span class="description">
					Dígito verificador da conta.
					<br />
					<strong>OBS</strong>: Limite de 2 caracteres, apenas alfanuméricos
				</span>
			</td>
		</tr>
		<tr>
			<th><label for="bank_account_name">Nome completo ou Razão Social</label></th>
			<td>
				<input type="text" name="bank_account_name" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_account_name', $user_id ) )?>">
				<br />
				<span class="description">
					Nome completo (se pessoa física) ou razão social (se pessoa jurídica)
				</span>
			</td>
		</tr>
		<tr>
			<th><label for="bank_account_document">Documento (CPF/CNPJ)</label></th>
			<td>
				<input type="text" name="bank_account_document" class="regular-text" maxlength="14" value="<?php echo esc_attr( get_the_author_meta( 'pgm_bank_account_document', $user_id ) )?>">
				<br />
				<span class="description">
					Documento identificador do titular da conta (cpf ou cnpj)
					<br />
					<strong>Ex</strong>.: 35146484252
					O documento precisa estar valido!
				</span>
			</td>
		</tr>
	</table>
	<h3>Recipient section</h3>
	<table class="form-table">
		<tr>
			<th><label for="recipient_id">Recipient identification</label></th>
			<td>
				<input type="text" name="recipient_id" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'pgm_recipient_id', $user_id ) )?>">
				<br />
				<span class="description">
					Identificação do Recipient.
					<br />
					Somente alterar ou incluir valor em caso de migração ou se souber os efeitos da mudança.
					<br />
					<strong>OBS</strong>: Você precisa deixar esse campo em branco se precisar alterar os dados bancários desse usuário.
					<br />
					<strong>OBS</strong>: Verifique antes se esse não é o " recipient_master ", você vai precisar atualizar o id no gateway logo depois de mudar aqui.
				</span>
			</td>
		</tr>
		
		<tr>
			<th><label for="recipient_transfer_enabled">Automatic Transfer</label></th>
			<td>
				<?php $recipient_transfer = get_the_author_meta( 'pgm_recipient_transfer_enabled', $user_id ) ?>
				<input type="radio" name="recipient_transfer_enabled" value="enabled" <?php echo esc_attr( checked_value( $recipient_transfer, 'enabled' ) )?>>Enable
				<br />
				<input type="radio" name="recipient_transfer_enabled" value="disabled" <?php echo esc_attr( checked_value( $recipient_transfer, 'disabled' ) )?> <?php if ( 'enabled' !== $recipient_transfer ) { echo 'checked="checked"';} ?>>Disable
				<br />
				<span class="description">Enable automatic tranfer.</span>
			</td>
		</tr>
		<tr>
			<th><label for="recipient_transfer_interval">Frequência</label></th>
			<td>
				<?php $recipient_transfer_interval = get_the_author_meta( 'pgm_recipient_transfer_interval', $user_id ) ?>
				<select name="recipient_transfer_interval">
					<option value="daily" <?php echo esc_attr( selected_value( $recipient_transfer_interval, 'daily' ) )?>>Diário</option>
					<option value="weekly" <?php echo esc_attr( selected_value( $recipient_transfer_interval, 'weekly' ) )?>>Semanal</option>
					<option value="monthly" <?php echo esc_attr( selected_value( $recipient_transfer_interval, 'monthly' ) )?>>Mensal</option>
				</select>
				<br />
				<span class="description">Frequência na qual o recebedor irá ser pago. .</span>
			</td>
		</tr>
		<tr>
			<th><label for="recipient_transfer_day">Dia transferência</label></th>
			<td>
				<?php $recipient_transfer_day = get_the_author_meta( 'pgm_recipient_transfer_day', $user_id ) ?>
				<select name="recipient_transfer_day">
					<?php for ( $i = 0; $i <= 31; $i++ ) : ?>
						<option value="<?php echo $i ?>" <?php echo esc_attr( selected_value( (int) $recipient_transfer_day, $i ) )?>><?php echo $i ?></option>
					<?php endfor?>
				</select>
				<br />
				<span class="description">Dia no qual o recebedor vai ser pago.<br />
				diário: somente 0, use o valor 0 somente para intervalo diário.<br />
				semanal: 1 a 5, onde 1 é segunda-feira e 5 é sexta-feira<br />
				mensal: 1 a 31</span>
			</td>
		</tr>
		<tr>
			<th><label for="recipient_master">Recipient Master</label></th>
			<td>
				<?php $recipient_master = get_the_author_meta( 'pgm_recipient_master', $user_id );
				write_log( "recipient_master: {$recipient_master}" );
				?>
				<input type="radio" name="recipient_master" value="enabled" <?php echo esc_attr( checked_value( $recipient_master, 'enabled' ) )?>>Enable<br />
				<input type="radio" name="recipient_master" value="disabled" <?php echo esc_attr( checked_value( $recipient_master, 'disabled' ) )?> <?php if ( 'enabled' !== $recipient_master ) { echo 'checked="checked"';} ?>>Disable
				<br />
				<span class="description">Esse é recipiente master?. <br />
				 Marque essa opção se usuário for o lojista master<br />
				 Ultimo usuário marcado essa opção passa ser lojista master (Enviar <strong>Recipient identification</strong> para recorrente.net)</span>
			</td>
		</tr>
	</table>
</fieldset>
<?php }

add_action( 'personal_options_update', 'my_save_bank_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_bank_profile_fields' );

function my_save_bank_profile_fields( $user_id ) {
	if ( ! current_user_can( 'administrator', $user_id ) ) {
		return false;
	}

	$bank = array();
	$bank['cod'] = $_POST['bank_cod'];
	$bank['agency'] = $_POST['bank_agency'];
	$bank['agency_digit'] = $_POST['bank_agency_digit'];
	$bank['account'] = $_POST['bank_account'];
	$bank['account_digit'] = $_POST['bank_account_digit'];
	$bank['account_name'] = $_POST['bank_account_name'];
	$bank['account_document'] = $_POST['bank_account_document'];

	$recipient_data['id'] = $_POST['recipient_id'];
	$recipient_data['transfer_enabled'] = $_POST['recipient_transfer_enabled'];
	$recipient_data['transfer_interval'] = $_POST['recipient_transfer_interval'];
	$recipient_data['transfer_day'] = $_POST['recipient_transfer_day'];
	$recipient_data['automatic_anticipation_enabled'] = false;
	$recipient_data['anticipatable_volume_percentage'] = 0;
	$recipient_data['master'] = $_POST['recipient_master'];

	if ( 'daily' !== $recipient_data['transfer_interval'] ) {
		if ( '0' === $recipient_data['transfer_day'] ) {
			$recipient_data['transfer_day'] = '1';
		}
		if ( 'weekly' === $recipient_data['transfer_interval'] ) {
			if ( $recipient_data['transfer_day'] > '7' ) {
				$recipient_data['transfer_day'] = '5';
			}
		}
	} else {
		$recipient_data['transfer_day'] = '0';
	}

	update_user_meta( $user_id, 'pgm_bank_cod', $bank['cod'] );
	update_user_meta( $user_id, 'pgm_bank_agency', $bank['agency'] );
	update_user_meta( $user_id, 'pgm_bank_agency_digit', $bank['agency_digit'] );
	update_user_meta( $user_id, 'pgm_bank_account', $bank['account'] );
	update_user_meta( $user_id, 'pgm_bank_account_digit', $bank['account_digit'] );
	update_user_meta( $user_id, 'pgm_bank_account_name', $bank['account_name'] );
	update_user_meta( $user_id, 'pgm_bank_account_document', $bank['account_document'] );

	$recipient = find_or_create_recipient( $bank, $recipient_data );
	if ( $recipient instanceof PagarMe\Sdk\Recipient\Recipient ) {
		$recipient_id = $recipient->getId();
		$bank_acc = $recipient->getBankAccount();
		$bank_id = $bank_acc->getId();
		write_log( "Updating remote before local -> recipient_id: {$recipient_id} bank_id: ${bank_id} user_id: ${user_id}" );
		update_remote_recipient( $recipient, $recipient_data, $bank );
		write_log( "Updating from remote recipient_id: {$recipient_id} bank_id: ${bank_id} user_id: ${user_id}" );
		update_user_meta( $user_id, 'pgm_bank_id', $bank_id );
		update_user_meta( $user_id, 'pgm_recipient_id', $recipient_id );
		if ( $recipient->getTransferEnabled() ) {
			update_user_meta( $user_id, 'pgm_recipient_transfer_enabled', 'enabled' );
		} else {
			update_user_meta( $user_id, 'pgm_recipient_transfer_enabled', 'disabled' );
		}
		update_user_meta( $user_id, 'pgm_recipient_transfer_interval', $recipient->getTransferInterval() );
		update_user_meta( $user_id, 'pgm_recipient_transfer_day', $recipient->getTransferDay() );
		if ( $bank_acc instanceof PagarMe\Sdk\BankAccount\BankAccount ) {
			update_user_meta( $user_id, 'pgm_bank_cod', $bank_acc->getBankCode() );
			update_user_meta( $user_id, 'pgm_bank_agency', $bank_acc->getAgencia() );
			if ( false === $bank_acc->getAgenciaDv() ) {
				$agency_digit = '';
			} else {
				$agency_digit = $bank_acc->getAgenciaDv();
			}
			update_user_meta( $user_id, 'pgm_bank_agency_digit', $agency_digit );
			update_user_meta( $user_id, 'pgm_bank_account', $bank_acc->getConta() );
			update_user_meta( $user_id, 'pgm_bank_account_digit', $bank_acc->getContaDv() );
			update_user_meta( $user_id, 'pgm_bank_account_name', $bank_acc->getLegalName() );
			update_user_meta( $user_id, 'pgm_bank_account_document', $bank_acc->getDocumentNumber() );
		}
		if ( 'enabled' === $recipient_data['master'] ) {
			update_master_user( $user_id, $recipient_data['master'] );
		}
	} else {
		delete_user_meta( $user_id, 'pgm_bank_id' );
		delete_user_meta( $user_id, 'pgm_recipient_id' );
	}

}

add_filter( 'manage_users_columns', 'recipient_n_master_user_table' );
/**
 * Change user table to show new columns 'Recipient ID' and 'Lojista Master'.
 *
 * @param Array $columns The name is passed to functions to identify the column. The label is shown as the column header.
 */
function recipient_n_master_user_table( $columns ) {
	$columns['master'] = __( 'Master Vendor' );
	$columns['recipient_id'] = __( 'Recipient ID' );
	return $columns;
}

add_filter( 'manage_users_custom_column', 'recipient_n_master_user_table_row', 10, 3 );
/**
 * Returns row value for new user table columns.
 *
 * @param String $output 	  Custom column output. Default empty.
 * @param String $column_name Column name.
 * @param Int    $user_id 	  ID of the currently-listed user.
 */
function recipient_n_master_user_table_row( $output, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'recipient_id' :
			return get_the_author_meta( 'pgm_recipient_id', $user_id );
			break;
		case 'master' :
			$master = get_the_author_meta( 'pgm_recipient_master', $user_id );
			return 'enabled' === $master ? __( 'yes' ) : '';
			break;
		default:
	}
	return $output;
}
