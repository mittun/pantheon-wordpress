<table class="form-table">
  <tbody>
    <!--Start of campaign section-->

    <tr>
      <th scope="row"><?php _e( 'Select Campaign', 'mittun_classy' ); ?></th>
      <td>
        <select name="_classy_campaign_id" id="_classy_campaign_id">
          <option><?php _e('Select', 'mittun_classy'); ?></option>

          <?php if(!empty($campaigns)) : ?>
            <?php foreach($campaigns as $campaign) : ?>
              <option value="<?php echo $campaign->id;  ?>" <?php selected($campaign->id, $classy_campaign_id, true); ?>><?php echo $campaign->name;  ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>

      </td>
    </tr>

    <tr valign="top" id="classypress_checkout_url"<?php echo(!empty($campaigns) && !empty($classy_campaign_id) && empty($campaign_url) ? ' style="display: none;"' : ''); ?>>
      <th scope="row" class="indent"><?php _e('Checkout Url', 'mittun_classy'); ?></th>
      <td>
        <input type="text" name="_classy_campaign_url" value="<?php echo $campaign_url; ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row" class="indent"><?php _e('Display Form In','mittun_classy'); ?></th>
      <td>
        <input type="radio" class="classy-donation-template" name="_classy_campaign_display_form_type" value="inline"  <?php checked($display_form_type, 'inline', true); ?>/><?php _e('Inline (embeded)', 'mittun_classy');?>
        &nbsp;
        <input type="radio" class="classy-donation-template" name="_classy_campaign_display_form_type" value="popup"  <?php checked($display_form_type, 'popup', true); ?>/><?php _e('Popup (lightbox)', 'mittun_classy');?>
      </td>
    </tr>

    <!-- Inline Top/Bottom Text -->
    <tr valign="top" class="classy-inline-custom-field"<?php echo ($display_form_type == 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Inline Top Custom Text', 'mittun_classy'); ?></th>
      <td>
        <textarea name="_classy_campaign_inline_top_text" class="regular-text "><?php echo $inline_top_text; ?></textarea>
      </td>
    </tr>
    <tr valign="top" class="classy-inline-custom-field"<?php echo ($display_form_type == 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Inline Bottom Custom Text','mittun_classy'); ?></th>
      <td>
        <textarea name="_classy_campaign_inline_bottom_text" class="regular-text "><?php echo $inline_bottom_text; ?></textarea>
      </td>
    </tr>

    <!--Start of primary submit button section under Display Form In popup-->
    <tr valign="top" class="classy-popup-custom-field"<?php echo ($display_form_type != 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Popup Button Text','mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_primary_btn_text" type="text" class="regular-text "  value="<?php echo $primary_btn_text; ?>" />
      </td>
    </tr>
    <tr valign="top" class="classy-popup-custom-field"<?php echo ($display_form_type != 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Popup Button Text Color','mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_primary_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_text_color; ?>" />
      </td>
    </tr>
    <tr valign="top" class="classy-popup-custom-field"<?php echo ($display_form_type != 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Popup Button Background','mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_primary_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_bg_color; ?>" />
      </td>
    </tr>
    <tr valign="top" class="classy-popup-custom-field"<?php echo ($display_form_type != 'popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Popup Top Custom Text', 'mittun_classy'); ?></th>
      <td>
        <textarea name="_classy_campaign_popup_top_text" class="regular-text "><?php echo $popup_top_text; ?></textarea>
      </td>
    </tr>
    <tr valign="top" class="classy-popup-custom-field"<?php echo ($display_form_type!='popup') ? ' style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Popup Bottom Custom Text','mittun_classy'); ?></th>
      <td>
        <textarea name="_classy_campaign_popup_bottom_text" class="regular-text "><?php echo $popup_bottom_text; ?></textarea>
      </td>
    </tr>
    <!--End of primary submit button section under Display Form In popup-->

    <!--Start of Form Type-->
    <tr valign="top" class="primary-btn-style-end" >
      <th scope="row" class="indent"><?php _e('Form Type','mittun_classy'); ?>
        <div>
          <i>
            <?php _e('Short form: User submits by clicking once or monthly buttons', 'mittun_classy'); ?><br/>
            <?php _e('Long form: User selects once or monthly as an option, and submits by clicking the final submit button after completing the form', 'mittun_classy'); ?>
          </i>
        </div>
      </th>
      <td>
        <input type="radio" name="_classy_campaign_form_type" value="short"  <?php checked($form_type, 'short', true); ?>/><?php _e('Short (Once/Recurring buttons only)', 'mittun_classy');?>
        &nbsp;
        <input type="radio" name="_classy_campaign_form_type" value="long"  <?php checked($form_type, 'long', true); ?>/><?php _e('Long (all field options)', 'mittun_classy');?>

      </td>

    </tr>

    <tr valign="top" class="both">
      <th scope="row"><?php _e('Enable Set Donation Amount', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_set_donation_amt" id="_classy_campaign_set_donation_amt" type="checkbox"   value="true" <?php checked(!empty($set_donation_amt), true, true) ?>/>
      </td>
    </tr>
    <tr valign="top" class="both amnt-input"<?php echo(empty($set_donation_amt) ? ' style="display: none;"' : ''); ?>>
      <th scope="row" class="indent"><?php _e('Donation Amounts', 'mittun_classy'); ?></th>
      <td>
        <div id="mittun-classy-amount">
          <div id="mittun-classy-amount-wrapper">
            <p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text" value="<?php echo (!empty($donation_amt[0])?  $donation_amt[0]:''); ?>" /></p>
            <?php
            if(!empty($donation_amt)) :
              for($i=1; $i<count($donation_amt); $i++) :
                if(!empty($donation_amt[$i])) :
            ?>
                  <p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text"  value="<?php echo (!empty($donation_amt[$i]) ? $donation_amt[$i] : ''); ?>" />&nbsp;<a href="javascript:void(0)" class="mittun-classy-amt-remove">X</a></p>
                  <?php
                endif;
              endfor;
            endif;
            ?>
          </div>
          <p>
            <input type="button" value="Add New" class="mittun-classy-amt-more button-primary" data-field="_classy_campaign_donation_amt[]" data-container="mittun-classy-amount-wrapper"/>
          </p>
        </div>
      </td>
    </tr>
    <tr valign="top" class="both other-amnt" <?php echo (empty($set_donation_amt)) ? 'style="display:none"' : ''; ?>>
      <th scope="row"><?php _e('Show Other Amount Button', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_display_custom_amount_btn" id="_classy_campaign_display_custom_amount_btn" type="checkbox"   value="true" <?php checked(!empty($display_custom_amount_btn),true,true) ?>/>
      </td>
    </tr>
    <tr valign="top" class="both other-amnt other-amnt-input" <?php echo (empty($set_donation_amt) || empty($display_custom_amount_btn)) ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Other Amount Button Custom Text', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_amount_btn_text" type="text" class="regular-text "  value="<?php echo $amount_btn_text; ?>" />
      </td>
    </tr>
    <tr valign="top" class="custom-amount-btn-style-end both" <?php echo ($donation_type!='form' || empty($set_donation_amt)) ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Amount Buttons Text Color', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_amount_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_text_color; ?>" />
      </td>
    </tr>
    <tr valign="top" class="both amnt-input" <?php echo (empty($set_donation_amt)) ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Amount Buttons Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_bg_color; ?>" />
      </td>
    </tr>
    <tr valign="top" class="both amnt-input" <?php echo (empty($set_donation_amt)) ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Active Amount Buttons Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_active_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $active_amount_btn_bg_color; ?>" />
      </td>
    </tr>

    <!--Start of long form-->
    <tr valign="top" class="set-amt-style-end" <?php echo ($form_type=='short' || empty($set_donation_amt)) ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Payment Type Buttons Text Color(Once/Monthly)', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_text_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type=='short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Payment Type Buttons Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_bg_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type=='short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Payment Type Active Button Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_active_btn_bg_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type=='short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Form Submit Button Text Color', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_submit_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_text_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type == 'short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Form Submit Button Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_submit_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_bg_color; ?>" />
      </td>
    </tr>

    <tr valign="top" <?php echo ($form_type == 'short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Fields To Display', 'mittun_classy'); ?></th>
      <td>
        <?php
        foreach($this->form_fields_arr as $key=>$field) {
          echo '<input type="checkbox" name="_classy_campaign_fields_to_display[]" ';
          if(empty($fields_to_display)) {
            echo 'checked="checked"';
          } elseif(!empty($fields_to_display) && in_array($key, $fields_to_display)) {
            echo 'checked="checked"';
          }
          echo 'value="' . $key . '" />' . $field . '&nbsp;&nbsp;';
        }
        ?>
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type == 'short') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Submit Button Text', 'mittun_classy'); ?></th>
      <td>
        <input type="text" name="_classy_campaign_submit_btn_label" value="<?php echo $submit_btn_label; ?>" />
      </td>
    </tr>
    <!--End of long form-->

    <!--Start of short form-->
    <tr class="display-form-long-style-end" valign="top" <?php echo ($form_type == 'long') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Once/Monthly Button Text Color','mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_sf_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_text_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type == 'long') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Once/Monthly Button Background','mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_sf_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_bg_color; ?>" />
      </td>
    </tr>
    <tr valign="top" <?php echo ($form_type == 'long') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Once/Monthly Active Button Background', 'mittun_classy'); ?></th>
      <td>
        <input name="_classy_campaign_sf_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_active_btn_bg_color; ?>" />
      </td>
    </tr>

    <tr valign="top" <?php echo ($form_type == 'long') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Monthly Button Text', 'mittun_classy'); ?></th>
      <td>
        <input type="text" name="_classy_campaign_monthly_btn_text" value="<?php echo $monthly_btn_text; ?>" />
      </td>
    </tr>

    <tr valign="top" <?php echo ($form_type == 'long') ? 'style="display:none"' : ''; ?>>
      <th scope="row" class="indent"><?php _e('Once Button Text', 'mittun_classy'); ?></th>
      <td>
        <input type="text" name="_classy_campaign_once_btn_text" value="<?php echo $once_btn_text; ?>" />
      </td>
    </tr>
    <!--End of short form-->

    <!--End of Form Type-->
    <tr valign="top" class="display-form-short-style-end">
      <th scope="row" class="indent"><?php _e('Default Donation Amount', 'mittun_classy'); ?></th>
      <td>
        <input type="text" name="_classy_campaign_default_donation_amt" value="<?php echo $default_donation_amt; ?>" />
      </td>
    </tr>
    <!--End of display form section-->

    <!-- Custom CSS Area -->
    <tr valign="top" class="form-custom-css both">
      <th scope="row" class="indent"><?php _e('Custom CSS', 'mittun_classy'); ?></th>
      <td>
        <textarea name="_classy_campaign_custom_css" rows="15" cols="60"><?php echo $custom_css; ?></textarea>
      </td>
    </tr>
    <!--End of custom CSS area-->


    <tr valign="top" class="submit-area both">
      <th scope="row">&nbsp;</th>
      <td  style="text-decoration:e;">
        <span>
          <?php if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) : ?>
            <input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />
          <?php else: ?>
            <input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
          <?php endif; ?>
        </span>
        <span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:e;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
      </td>
    </tr>
  </tbody>
</table>
