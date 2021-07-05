<?php
/**
 * Receipt page error template
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul class="woocommerce-error">
    <?php foreach ( $result['Status'] as $key => $message ) : ?>
		<li><?php echo $key .": ".$message; ?></li>
	<?php endforeach; ?>
	<?php foreach ( $result['errors'] as $key => $message ) : ?>
		<li><?php echo $key .": ".$message; ?></li>
	<?php endforeach; ?>
</ul>