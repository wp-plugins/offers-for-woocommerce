<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_New_Counter_Offer_Email' ) ) :

    /**
     * A custom New Counter Offer WooCommerce Email class
     *
     * @since 0.1.0
     * @extends WC_Email
     */
    class WC_New_Counter_Offer_Email extends WC_Email {
        /**
         * Set email defaults
         *
         * @since 0.1.0
         */
        public function __construct() {
            /**
             * Call $plugin_slug from public plugin class
             * @since	0.1.0
             */
            $plugin = Angelleye_Offers_For_Woocommerce::get_instance();
            $this->plugin_slug = $plugin->get_plugin_slug();

            // set ID, this simply needs to be a unique name
            $this->id = 'wc_new_counter_offer';

            // this is the title in WooCommerce Email settings
            $this->title = 'New counter offer';

            // this is the description in WooCommerce email settings
            $this->description = 'New Counter Offer Notification emails are sent to the admin when customer submits a counter offer';

            // these are the default heading and subject lines that can be overridden using the settings
            $this->heading = 'New Counter Offer';
            $this->subject = 'New Counter Offer';

            // Call parent constructor to load any other defaults not explicity defined here
            parent::__construct();

            // Set the recipient
            $this->recipient = $this->get_option( 'admin_email' );
        }

        /**
         * Determine if the email should actually be sent and setup email merge variables
         *
         * @since 0.1.0
         * @param int $order_id
         */
        public function trigger( $offer_args ) {

            $this->offer_args = $offer_args;
            $this->recipient = $this->get_option( 'recipient' );

            if ( ! $this->is_enabled() )
            {
                return;
            }

            // woohoo, send the email!
            $this->send( $this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        }

        /**
         * get_content_html function.
         *
         * @since 0.1.0
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
         * @since 0.1.0
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
         * @since 0.1.0
         */
        public function init_form_fields() {

            $this->form_fields = array(
                'enabled'    => array(
                    'title'   => 'Enable/Disable',
                    'type'    => 'checkbox',
                    'label'   => 'Enable this email notification',
                    'default' => 'yes'
                ),
                'recipient'    => array(
                    'title'       => 'Recipient(s)',
                    'type'        => 'text',
                    'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option('admin_email') ) ),
                    'placeholder' => '',
                    'default'     => ''
                ),
                'subject'    => array(
                    'title'       => 'Subject',
                    'type'        => 'text',
                    'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
                    'placeholder' => '',
                    'default'     => ''
                ),
                'heading'    => array(
                    'title'       => 'Email Heading',
                    'type'        => 'text',
                    'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
                    'placeholder' => '',
                    'default'     => ''
                ),
                'email_type' => array(
                    'title'       => 'Email type',
                    'type'        => 'select',
                    'description' => 'Choose which format of email to send.',
                    'default'     => 'html',
                    'class'       => 'email_type',
                    'options'     => array(
                        'plain'     => 'Plain text',
                        'html'      => 'HTML',
                        'multipart' => 'Multipart',
                    )
                )
            );
        }
    } // end \WC_New_Counter_Offer_Email class

endif;