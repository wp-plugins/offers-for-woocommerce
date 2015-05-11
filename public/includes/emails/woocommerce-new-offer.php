<?php
/**
 * New Offer email
 *
 * @since	0.1.0
 * @package public/includes/emails
 * @author  AngellEYE <andrew@angelleye.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php printf( '<p><strong>' . __('New offer submitted on', 'angelleye-offers-for-woocommerce') . ' %s.</strong><br />' . __('To manage this offer please use the following link:', 'angelleye-offers-for-woocommerce') . '</p> %s', get_bloginfo( 'name' ), '<a style="background:#EFEFEF; color:#161616; padding:8px 15px; margin:10px; border:1px solid #CCCCCC; text-decoration:none; " href="'. admin_url( 'post.php?post='. $offer_args['offer_id']  .'&action=edit' ) .'"><span style="border-bottom:1px dotted #666; ">' . __( 'Manage Offer', 'angelleye-offers-for-woocommerce') . '</span></a>' ); ?>

<h2><?php echo __( 'Offer ID:', 'angelleye-offers-for-woocommerce') . ' ' . $offer_args['offer_id']; ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', time() ), date_i18n( wc_date_format(), time() ) ); ?>)</h2>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
    <thead>
    <tr>
        <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', 'woocommerce' ); ?></th>
        <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
        <th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', 'woocommerce' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo stripslashes($offer_args['product_title_formatted']); ?></td>
        <td><?php echo number_format( $offer_args['product_qty'], 0 ); ?></td>
        <td><?php echo get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_price_per'], 2 ); ?></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th scope="row" colspan="2" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
        <td style="text-align:left; border: 1px solid #eee; border-top-width: 4px; "><?php echo get_woocommerce_currency_symbol() . ' ' . number_format( $offer_args['product_total'], 2 ); ?></td>
    </tr>
    </tfoot>
</table>

<h4><?php echo __('Offer Contact Details:', 'angelleye-offers-for-woocommerce'); ?></h4>
<?php echo (isset($offer_args['offer_name']) && $offer_args['offer_name'] != '') ? '<strong>' . __('Name:', 'angelleye-offers-for-woocommerce') . '&nbsp;</strong>'.stripslashes($offer_args['offer_name']) : ''; ?>
<?php echo (isset($offer_args['offer_company_name']) && $offer_args['offer_company_name'] != '') ? '<br /><strong>' . __('Company Name:', 'angelleye-offers-for-woocommerce') . '&nbsp;</strong>'.stripslashes($offer_args['offer_company_name']) : ''; ?>
<?php echo (isset($offer_args['offer_email']) && $offer_args['offer_email'] != '') ? '<br /><strong>' . __('Email:', 'angelleye-offers-for-woocommerce') . '&nbsp;</strong>'.stripslashes($offer_args['offer_email']) : ''; ?>
<?php echo (isset($offer_args['offer_phone']) && $offer_args['offer_phone'] != '') ? '<br /><strong>' . __('Phone:', 'angelleye-offers-for-woocommerce') . '&nbsp;</strong>'.stripslashes($offer_args['offer_phone']) : ''; ?>

<?php if(isset($offer_args['offer_notes']) && $offer_args['offer_notes'] != '') { echo '<h4>'. __( 'Offer Notes:', 'angelleye-offers-for-woocommerce' ) .'</h4>'. stripslashes($offer_args['offer_notes']); } ?>

<?php do_action( 'woocommerce_email_footer' ); ?>