<?php
/**
 * New Counter Offer email (plain text)
 *
 * @since	0.1.0
 * @package public/includes/emails/plain
 * @author  AngellEYE <andrew@angelleye.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo sprintf( __('New Counter Offer submitted on', 'angelleye-offers-for-woocommerce') . ' %s. ' . __('To manage this counter offer please visit the following url:', 'angelleye-offers-for-woocommerce') . ' %s', get_bloginfo( 'name' ),  admin_url( 'post.php?post='. $offer_args['offer_id']  .'&action=edit' ) ) . "\n\n";

echo "****************************************************\n";

echo sprintf( __( 'Offer ID:', 'angelleye-offers-for-woocommerce') .' %s', $offer_args['offer_id'] ) . "\n";

echo "\n";

echo __( 'Product', 'woocommerce' ) . ': ' . stripslashes($offer_args['product_title_formatted']) . "\n";
echo __( 'Quantity', 'woocommerce' ) . ': ' . number_format( $offer_args['product_qty'], 0 ) . "\n";
echo __( 'Price', 'woocommerce' ) . ': ' . get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_price_per'], 2 ) . "\n";
echo __( 'Subtotal', 'woocommerce' ) . ': ' . get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_total'], 2 );
echo "\n\n";

echo __('Offer Contact Details:', 'angelleye-offers-for-woocommerce');
echo (isset($offer_args['offer_name']) && $offer_args['offer_name'] != '') ? "\n" . __('Name:', 'angelleye-offers-for-woocommerce') . " ".stripslashes($offer_args['offer_name']) : "";
echo (isset($offer_args['offer_company_name']) && $offer_args['offer_company_name'] != '') ? "\n" . __('Company Name:', 'angelleye-offers-for-woocommerce') . " ".stripslashes($offer_args['offer_company_name']) : "";
echo (isset($offer_args['offer_email']) && $offer_args['offer_email'] != '') ? "\n" . __('Email:', 'angelleye-offers-for-woocommerce') . " ".stripslashes($offer_args['offer_email']) : "";
echo (isset($offer_args['offer_phone']) && $offer_args['offer_phone'] != '') ? "\n" . __('Phone:', 'angelleye-offers-for-woocommerce') . " ".stripslashes($offer_args['offer_phone']) : "";

if(isset($offer_args['offer_notes']) && $offer_args['offer_notes'] != '')
{
    echo "\n\n" . __( 'Offer Notes:', 'angelleye-offers-for-woocommerce' ) . ' ' . $offer_args['offer_notes'];
}

echo "\n****************************************************\n\n";
echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );