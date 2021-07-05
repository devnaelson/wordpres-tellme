<?php
/**
 * Pagarme-split Functions to legacy code
 *
 * PHP version 5
 *
 * @category Legacy
 * @package  Pagarme-split/legacy
 * @author   Barradev Consulting <contato@barradev.com>
 * @license  Attribution-ShareAlike https://creativecommons.org/licenses/by-sa/4.0/
 * @version  GIT: $id$
 * @link     https://bitbucket.org/barradev_isquare/woocommerce-pagarme-split
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Like old select function to return all recebedores.
 *
 * @param string $selected Selected value to select.
 * @param string $select_id Name for select html.
 * @deprecated
 */
function new_recebedores_select_option( $selected = '', $select_id = 'recebedor_nome' ) {
	global $post;

	$args = array(
		'post_type'  => 'vm50_recebedor',
		'orderby'    => 'title',
		'order'      => 'ASC',
	);
	$the_query_interno = new WP_Query( $args );
	$saida  = '<select id="' . $select_id . '" name="' . $select_id . '" class="' . $select_id . ' regular-text">';
	$saida .= '<option value=""';
	'' === $selected && $saida .= ' selected';
	$saida .= '>&nbsp;</option>';
	while ( $the_query_interno->have_posts() ) {
		$the_query_interno->the_post();
		$identif = $the_query_interno->post->ID;
		$unidade = get_the_title();
		$saida  .= '<option value="' . $identif . '"';
		$selected === $identif && $saida .= ' selected';
		$saida  .= '>' . $unidade . '</option>';
	}
	$saida .= '</select>';
	wp_reset_postdata();

	return $saida;
}

/**
 * Find the lojista master (old function)
 *
 * @deprecated
 */
function encontra_lojista() {
	$args = array(
		'post_type'  => 'vm50_recebedor',
		'meta_key'   => 'vm50_recebedor_lojista',
		'meta_value' => 'S',
	);
	$the_query = new WP_Query( $args );
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$identif = $the_query->post->ID;
		$dp_id   = get_post_meta( $identif, 'vm50_recebedor_id', true );
	}

	wp_reset_postdata();
	return $dp_id;
}
