<?php
/**
 * Customer Offer Countered email (plain text)
 *
 * @since	0.1.0
 * @package admin/includes/emails/plain
 * @author  AngellEYE <andrew@angelleye.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

$link_insert = ( strpos( $offer_args['product_url'], '?') ) ? '&' : '?';
echo sprintf( __( 'We have provided you with a counter offer on', 'angelleye-offers-for-woocommerce' ) .' %s.', get_bloginfo( 'name' ) ) . "\n\n";
echo sprintf( __( 'To pay for this order please use the following link:', 'angelleye-offers-for-woocommerce' ) .' %s', $offer_args['product_url']. $link_insert .'__aewcoapi=1&woocommerce-offer-id=' . $offer_args['offer_id'].'&woocommerce-offer-uid=' .$offer_args['offer_uid'] );

if(isset($offer_args['offer_expiration_date']) && $offer_args['offer_expiration_date'])
{
    echo "\n\n" . sprintf( __( 'Offer expires on:', 'angelleye-offers-for-woocommerce' ).' %s</strong></p>', date("m-d-Y", strtotime($offer_args['offer_expiration_date'])) );
}

if(isset($offer_args['final_offer']) && $offer_args['final_offer'] == '1') {
    echo "\n\n" . __( 'This is a final offer.', 'angelleye-offers-for-woocommerce' );
} else {
    echo "\n\n" . sprintf( __('To make a counter offer use the following link:', 'angelleye-offers-for-woocommerce') .'%s', $offer_args['product_url'] . $link_insert . 'aewcobtn=1&offer-pid=' . $offer_args['offer_id'] . '&offer-uid=' . $offer_args['offer_uid']);
}

echo "\n\n****************************************************\n";

echo sprintf( __( 'Offer ID:', 'angelleye-offers-for-woocommerce') .' %s', $offer_args['offer_id'] ) . "\n";

echo "\n";

echo __( 'Product', 'woocommerce' ) . ': ' . stripslashes($offer_args['product_title_formatted']) . "\n";
echo __( 'Quantity', 'woocommerce' ) . ': ' . number_format( $offer_args['product_qty'], 0 ) . "\n";
echo __( 'Price', 'woocommerce' ) . ': ' . get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_price_per'], 2 ) . "\n";
echo __( 'Subtotal', 'woocommerce' ) . ': ' . get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_total'], 2 );

if(isset($offer_args['offer_notes']) && $offer_args['offer_notes'] != '')
{
    echo "\n\n" . __( 'Offer Notes:', 'angelleye-offers-for-woocommerce' ) . ' ' . $offer_args['offer_notes'];
}

echo "\n****************************************************\n\n";
echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );