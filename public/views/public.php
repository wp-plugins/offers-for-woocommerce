<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   Angelleye_Offers_For_Woocommerce
 * @author    AngellEYE <andrew@angelleye.com>
 * @license   GPL-2.0+
 * @link      http://www.angelleye.com
 */
?>

<!-- This file is used to markup the public facing aspect of the plugin. -->
<div id="tab_custom_ofwc_offer_tab_alt_message" class="tab_custom_ofwc_offer_tab_inner_content">
    <ul class="woocommerce-error aeofwc-woocommerce-error">
        <li><strong><?php echo __('Selection Required:', $this->plugin_slug); ?>&nbsp;</strong><?php echo __('Select product options above before making new offer.', $this->plugin_slug); ?></li>
    </ul>        
</div>
<div id="tab_custom_ofwc_offer_tab_alt_message_success" class="tab_custom_ofwc_offer_tab_inner_content">
    <ul class="woocommerce-message">
        <li><strong><?php echo __('Offer Sent!', $this->plugin_slug); ?>&nbsp;</strong><?php echo __('Your offer has been received and will be processed as soon as possible.', $this->plugin_slug); ?></li>
    </ul>        
</div>
<div id="tab_custom_ofwc_offer_tab_alt_message_2" class="tab_custom_ofwc_offer_tab_inner_content">
    <ul class="woocommerce-error aeofwc-woocommerce-error">
        <li><strong><?php echo __('Error:', $this->plugin_slug);?>&nbsp;</strong><?php echo __('There was an error sending your offer, please try again. If this problem persists, please contact us.', $this->plugin_slug); ?></li>
    </ul>
</div>
<div id="tab_custom_ofwc_offer_tab_alt_message_custom" class="tab_custom_ofwc_offer_tab_inner_content">
    <ul class="woocommerce-error aeofwc-woocommerce-error">
        <li id="alt-message-custom"></li>
    </ul>
</div>
<?php if($parent_offer_error && $parent_offer_error_message) { ?>
<div id="tab_custom_ofwc_offer_tab_alt_message_3" class="tab_custom_ofwc_offer_tab_inner_content tab_custom_ofwc_offer_tab_alt_message_2">
    <ul class="woocommerce-error aeofwc-woocommerce-error">
        <li><strong><?php echo __('Error:', $this->plugin_slug); ?>&nbsp;</strong><?php echo $parent_offer_error_message;?></li>
    </ul>
</div>
<?php } ?>
<div id="tab_custom_ofwc_offer_tab_inner" class="tab_custom_ofwc_offer_tab_inner_content">
    <fieldset>
    	<div class="make-offer-form-intro">
            <h2>
                <?php $is_counter_offer = (isset($parent_offer_id) && $parent_offer_id != '') ? true : false; ?>
                <?php if($is_counter_offer)
                {
                    echo __('Make Counter Offer', $this->plugin_slug);
                }
                else
                {
                    if(isset($button_display_option['display_setting_custom_make_offer_btn_text']) && !empty($button_display_option['display_setting_custom_make_offer_btn_text']))
                    {
                        echo $button_display_options['display_setting_custom_make_offer_btn_text'];
                    }
                    else
                    {
                        echo __('Make Offer', $this->plugin_slug);
                    }
                }
                ?>
            </h2>
            <div class="make-offer-form-intro-text"><?php echo __('To make', $this->plugin_slug); ?>&nbsp;<?php echo ($is_counter_offer) ? __('a counter', $this->plugin_slug) . ' ' : __('an', $this->plugin_slug) . ' '; ?><?php echo __('offer please complete the form below:', $this->plugin_slug);?></div>
        </div>
        <form id="woocommerce-make-offer-form" name="woocommerce-make-offer-form" method="POST" autocomplete="off">
            <?php if($is_counter_offer) {?>
            <input type="hidden" name="parent_offer_id" id="parent_offer_id" value="<?php echo (isset($parent_offer_id) && $parent_offer_id != '') ? $parent_offer_id : ''; ?>">
            <input type="hidden" name="parent_offer_uid" id="parent_offer_uid" value="<?php echo (isset($parent_offer_uid) && $parent_offer_uid != '') ? $parent_offer_uid : ''; ?>">
            <?php } ?>
            <div class="woocommerce-make-offer-form-section">
                <?php if(isset($is_sold_individually) && $is_sold_individually ) { ?>
                    <input type="hidden" name="offer_quantity" id="woocommerce-make-offer-form-quantity" data-m-dec="0" data-l-zero="deny" data-a-form="false" required="required" value="1" />
                <?php } else { ?>
            	<div class="woocommerce-make-offer-form-part-left">
                    <label for="woocommerce-make-offer-form-quantity"><?php echo __('Quantity', $this->plugin_slug); ?></label>
                    <br /><input type="text" name="offer_quantity" id="woocommerce-make-offer-form-quantity" data-m-dec="0" data-l-zero="deny" data-a-form="false" <?php echo ($new_offer_quantity_limit) ? ' data-v-max="'.$new_offer_quantity_limit.'"' : '';?> required="required" />
                </div>
                <?php } ?>
                <div class="woocommerce-make-offer-form-part-left">
                	<label for="woocommerce-make-offer-form-price-each"><?php echo __('Price Each', $this->plugin_slug); ?></label>
                    <br />
                    <div class="angelleye-input-group">
                        <span class="angelleye-input-group-addon"><?php echo (isset($currency_symbol)) ? $currency_symbol : '$';?></span>
                        <input type="text" name="offer_price_each" id="woocommerce-make-offer-form-price-each" pattern="([0-9]|\$|,|.)+" data-a-sign="$" data-m-dec="2" data-w-empty="" data-l-zero="keep" data-a-form="false" required="required" />
                    </div>
                </div>
                <div class="woocommerce-make-offer-form-part-left">
                    <?php if(isset($is_sold_individually) && $is_sold_individually ) { ?>
                        <input type="hidden" name="offer_total" id="woocommerce-make-offer-form-total" class="form-control" data-currency-symbol="<?php echo (isset($currency_symbol)) ? $currency_symbol : '$';?>" disabled="disabled" />
                    <?php } else { ?>
                    <label for="woocommerce-make-offer-form-total"><?php echo __('Total Offer Amount', $this->plugin_slug); ?></label>
	                <br />
                    <div class="angelleye-input-group">
                        <span class="angelleye-input-group-addon"><?php echo (isset($currency_symbol)) ? $currency_symbol : '$';?></span>
                        <input type="text" name="offer_total" id="woocommerce-make-offer-form-total" class="form-control" data-currency-symbol="<?php echo (isset($currency_symbol)) ? $currency_symbol : '$';?>" disabled="disabled" />
                    </div>
                    <?php } ?>
                 </div>
            </div>
            <div class="woocommerce-make-offer-form-section">
                <label for="offer-name" class="woocommerce-make-offer-form-label"><?php echo __('Your Name', $this->plugin_slug); ?></label>
                <br /><input type="text" id="offer-name" name="offer_name" required="required" <?php echo ($is_counter_offer) ? ' disabled="disabled"' : '' ?> value="<?php echo (isset($offer_name)) ? $offer_name : ''; ?>" />
            </div>
            <div class="woocommerce-make-offer-form-section">
                <label for="offer-name" class="woocommerce-make-offer-form-label"><?php echo __('Company Name', $this->plugin_slug); ?></label>
                <br /><input type="text" id="offer-company-name" name="offer_company_name" <?php echo ($is_counter_offer) ? ' disabled="disabled"' : '' ?> value="<?php echo (isset($offer_company_name)) ? $offer_company_name: ''; ?>" />
            </div>
            <div class="woocommerce-make-offer-form-section">
                <label for="offer-name" class="woocommerce-make-offer-form-label"><?php echo __('Phone Number', $this->plugin_slug); ?></label>
                <br /><input type="text" id="offer-phone" name="offer_phone" <?php echo ($is_counter_offer) ? ' disabled="disabled"' : '' ?> value="<?php echo (isset($offer_phone)) ? $offer_phone: ''; ?>" />
            </div>
            <div class="woocommerce-make-offer-form-section">
                <label for="woocommerce-make-offer-form-email"><?php echo __('Your Email Address', $this->plugin_slug); ?></label>
                <br /><input type="email" name="offer_email" id="woocommerce-make-offer-form-email" required="required" <?php echo ($is_counter_offer) ? ' disabled="disabled"' : '' ?> value="<?php echo (isset($offer_email)) ? $offer_email: ''; ?>" />
            </div>
            <div class="woocommerce-make-offer-form-section">
                <label for="angelleye-offer-notes"><?php echo __('Offer Notes (optional)', $this->plugin_slug); ?></label>
                <br /><textarea name="offer_notes" id="angelleye-offer-notes" rows="4"></textarea>
            </div>
            <div class="woocommerce-make-offer-form-section woocommerce-make-offer-form-section-submit">
                <input type="submit" class="button" id="woocommerce-make-offer-form-submit-button" data-orig-val="<?php echo __('Submit', $this->plugin_slug); ?>&nbsp;<?php echo ($is_counter_offer) ? ' ' . __('Counter', $this->plugin_slug) . ' ' : ''; ?><?php echo __('Offer', $this->plugin_slug); ?>" value="<?php echo __('Submit', $this->plugin_slug); ?>&nbsp;<?php echo ($is_counter_offer) ? ' ' . __('Counter', $this->plugin_slug) . ' ' : ''; ?><?php echo __('Offer', $this->plugin_slug); ?>" />
                <div class="offer-submit-loader" id="offer-submit-loader"><?php echo __('Please wait...', $this->plugin_slug); ?></div>
            </div>
        </form>
    </fieldset>
</div>