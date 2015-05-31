<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Offer_On_Hold_Email' ) ) :

/**
 * A custom Offer On Hold WooCommerce Email class
 *
 * @since 1.0.1
 * @extends WC_Email
 */
class WC_Offer_On_Hold_Email extends WC_Email {
    /**
     * Set email defaults
     *
     * @since 1.0.1
     */
    public function __construct() {
        /**
         * Set plugin slug
         * @since	1.1.2
         */
        $this->plugin_slug = 'angelleye-offers-for-woocommerce';

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_offer_on_hold';

        // this is the title in WooCommerce Email settings
        $this->title = __('Offer On Hold', $this->plugin_slug);

        // this is the description in WooCommerce email settings
        $this->description = __('Offer On Hold Notification emails are sent when a customer offer is placed on hold by the store admin', $this->plugin_slug);

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = __('Offer On Hold', $this->plugin_slug);
        $this->subject = __('Offer On Hold', $this->plugin_slug);

        // Set email template paths
        $this->template_html 	= 'woocommerce-offer-on-hold.php';
        $this->template_plain 	= 'plain/woocommerce-offer-on-hold.php';

        // Call parent constructor to load any other defaults not explicitly defined here
        parent::__construct();

        // Set the recipient
        $this->recipient = $this->get_option( 'recipient' );

        // Other settings
        $this->template_base = OFWC_EMAIL_TEMPLATE_PATH;
    }

    /**
     * Determine if the email should actually be sent and setup email merge variables
     *
     * @since 1.0.1
     */
    public function trigger( $offer_args ) {

        $this->recipient = $offer_args['recipient'];
        $this->offer_args = $offer_args;

        if ( ! $this->is_enabled() || ! $this->recipient )
        {
            return;
        }

        // woohoo, send the email!
        $this->send( $this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    /**
     * get_content_html function.
     *
     * @since 1.0.1
     * @return string
     */
    public function get_content_html() {
        ob_start();
        wc_get_template( $this->template_html, array(
                'offer_args'         => $this->offer_args,
                'email_heading' => $this->get_heading(),
                'sent_to_admin'    => false,
                'plain_text'    => false
            ),
            '',
            $this->template_html_path
        );
        return ob_get_clean();
    }

    /**
     * get_content_plain function.
     *
     * @since 1.0.1
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        wc_get_template( $this->template_plain, array(
                'offer_args'         => $this->offer_args,
                'email_heading' => $this->get_heading(),
                'sent_to_admin'    => false,
                'plain_text'    => true
            ),
            '',
            $this->template_plain_path
        );
        return ob_get_clean();
    }

    /**
     * Initialize Settings Form Fields
     *
     * @since 1.0.1
     */
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => __('Enable/Disable', $this->plugin_slug),
                'type'    => 'checkbox',
                'label'   => __('Enable this email notification', $this->plugin_slug),
                'default' => 'yes'
            ),
            'subject'    => array(
                'title'       => __('Subject', $this->plugin_slug),
                'type'        => 'text',
                'description' => sprintf( __('This controls the email subject line. Leave blank to use the default subject:', $this->plugin_slug).' <code>%s</code>', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => __('Email Heading', $this->plugin_slug),
                'type'        => 'text',
                'description' => sprintf( __('This controls the main heading contained within the email notification. Leave blank to use the default heading:', $this->plugin_slug).' <code>%s</code>', $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => __('Email type', $this->plugin_slug),
                'type'        => 'select',
                'description' => __('Choose which format of email to send.', $this->plugin_slug),
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => __('Plain text', $this->plugin_slug),
                    'html'      => 'HTML',
                    'multipart' => 'Multipart',
                )
            )
        );
    }
} // end \WC_Offer_On_Hold Email class

endif;