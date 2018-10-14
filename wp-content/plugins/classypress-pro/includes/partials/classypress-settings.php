<div class="wrap">
  <?php if(!defined('IS_AUTHENTICATE') || !IS_AUTHENTICATE){ ?>
    <h2><?php _e('Classy Settings','mittun_classy');?></h2>
    <form method="post" action="options.php">
      <?php
      settings_errors('mittun-classy-key');
      settings_fields( 'mittun-classy-key' );
      do_settings_sections( 'mittun-classy-key' );
      ?>

      <table class="form-table">
        <?php
        if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
        else $tab = 'classy';
        ?>
        <tr valign="top">
          <th scope="row"><?php _e('License Key','mittun_classy'); ?></th>
          <td>
            <input type="text" name="mittun_classy_key"  size="50"/>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
    <?php
  }
  else{

    if ( isset ( $_GET['tab'] ) ) $this->mittun_classy_admin_menu_tabs($_GET['tab']); else $this->mittun_classy_admin_menu_tabs('classy');

    if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
    else $tab = 'classy';


    ?>
    <form method="post" action="options.php">

      <table class="form-table">
        <?php

        switch ( $tab ){
          case 'classy':
          settings_errors('mittun-classy-settings');
          settings_fields( 'mittun-classy-settings' );
          do_settings_sections( 'mittun-classy-settings' );
          ?>
          <tr valign="top">
            <th scope="row"><?php _e('Client ID (required)','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy[client_id]" type="password" class="regular-text" value="<?php echo mittun_classy_get_option('client_id','mittun_classy'); ?>"/>&nbsp;&nbsp;<a href="<?php echo esc_url('https://mittun.co/classy-support#client-id'); ?>" target="_blank"><?php _e('How to find your client ID','mittun_classy'); ?></a>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Client Secret (required)','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy[client_secret]" type="password" class="regular-text" value="<?php echo mittun_classy_get_option('client_secret','mittun_classy'); ?>"/>&nbsp;&nbsp;<a href="<?php echo esc_url('https://mittun.co/classy-support#client-secret'); ?>" target="_blank"><?php _e('How to find your client secret','mittun_classy'); ?></a>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Organization ID (required)','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy[organisation_id]" type="text" class="regular-text" value="<?php echo mittun_classy_get_option('organisation_id','mittun_classy'); ?>"/>&nbsp;&nbsp;<a href="<?php echo esc_url('https://mittun.co/classy-support#organization-id'); ?>" target="_blank"><?php _e('How to find your organization ID','mittun_classy'); ?></a>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><input type="checkbox" id="show_credentials"><?php _e('Show API Credentials','mittun_classy'); ?></th>
            <td>&nbsp;

            </td>
          </tr>
          <?php
          break;
          case 'color':
          settings_errors('mittun-classy-color-settings');
          settings_fields( 'mittun-classy-color-settings' );
          do_settings_sections( 'mittun-classy-color-settings' );
          $progress_bar_style=mittun_classy_get_option('progress_bar_style','mittun_classy_color');
          ?>

          <tr valign="top">
            <th scope="row"><?php _e('Heading Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[heading_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('heading_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Intro Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[intro_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('intro_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Layout Type','mittun_classy'); ?></th>

            <?php  $skin=mittun_classy_get_option('skin','mittun_classy_color');if(empty($skin))$skin='skin_1'; ?>

            <td  style="position:relative">

              <input type="radio" id="skin_1" name="mittun_classy_color[skin]" value="skin_1" <?php echo ($skin=='skin_1' || empty($skin)?'checked="checked"':''); ?>><?php _e('Original','mittun_classy'); ?>
              &nbsp;
              <input type="radio" id="skin_2"  name="mittun_classy_color[skin]" value="skin_2" <?php checked($skin,'skin_2',true); ?>><?php _e('Maverick','mittun_classy'); ?>

            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Progress Bar Style','mittun_classy'); ?></th>
            <td class="classy-button-set" style="position:relative">
              <input type="radio" id="style_1" name="mittun_classy_color[progress_bar_style]" value="style_1" <?php echo ($progress_bar_style=='style_1' || empty($progress_bar_style)?'checked="checked"':''); ?>>
              <label for="style_1"><?php _e('Style 1','mittun_classy'); ?></label>

              <input type="radio" id="style_2"  name="mittun_classy_color[progress_bar_style]" value="style_2" <?php checked($progress_bar_style,'style_2',true); ?>>
              <label for="style_2" <?php selected($progress_bar_style,'style_1',true); ?>><?php _e('Style 2','mittun_classy'); ?></label>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style1.png" data-rel="style_1" class="mittun-classy-style-sanp"/>
              <img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style2.png" data-rel="style_2" class="mittun-classy-style-sanp"/>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Progress Bar Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[progress_bar_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('progress_bar_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Progress Bar Marker Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[progress_bar_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('progress_bar_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Progress Bar Marker Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[progress_bar_marker_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('progress_bar_marker_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Primary Submit Button Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[primary_btn_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('primary_btn_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Primary Submit Button Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[primary_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Goal Amount Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[goal_amount_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('goal_amount_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Amount Raised Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[amount_raised_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('amount_raised_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Amount Buttons Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[amount_btn_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('amount_btn_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Amount Buttons Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[amount_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('amount_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Active Amount Buttons Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[active_amount_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('active_amount_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Payment Type Buttons Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[payment_btn_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('payment_btn_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Payment Type Buttons Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[payment_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Payment Type Active Button Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[payment_active_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('payment_active_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Form Submit Button Text Color','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[submit_btn_text_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('submit_btn_text_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Form Submit Button Background','mittun_classy'); ?></th>
            <td>
              <input name="mittun_classy_color[submit_btn_bg_color]" type="text" class="regular-text classy-color-picker"  value="<?php echo mittun_classy_get_option('submit_btn_bg_color','mittun_classy_color'); ?>" />
            </td>
          </tr>
          <?php
          break;
          case 'advanced':
          settings_errors('mittun-classy-advanced-settings');
          settings_fields( 'mittun-classy-advanced-settings' );
          do_settings_sections( 'mittun-classy-advanced-settings' );
          ?>

          <tr valign="top">
            <th scope="row"><?php _e('Checkout URL Type','mittun_classy'); ?></th>
            <td>
              <?php $checkout_url_type=mittun_classy_get_option('checkout_url_type','mittun_classy_advanced'); ?>

              <input type="radio" name="mittun_classy_advanced[checkout_url_type]" value="default"  <?php checked($checkout_url_type,'default',true); ?>/><?php _e('Default','mittun_classy'); ?>&nbsp;<input type="radio" name="mittun_classy_advanced[checkout_url_type]" value="custom" <?php checked($checkout_url_type,'custom',true); ?> /><?php _e('Custom','mittun_classy'); ?>
            </td>
          </tr>
          <tr valign="top" <?php if($checkout_url_type!='custom')echo 'style="display:none;"'; ?>>
            <th scope="row"><u><?php _e('Custom URL Override','mittun_classy'); ?></u><br/><i><?php _e('Use this to override campaign checkout URL','mittun_classy') ?></i></th>
            <td>
              <?php $custom_checkout_url=mittun_classy_get_option('custom_checkout_url','mittun_classy_advanced'); ?>

              <input type="text" name="mittun_classy_advanced[custom_checkout_url]" value="<?php echo $custom_checkout_url; ?>"  class="regular-text"/>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Clear Cache','mittun_classy'); ?></th>
            <td>
              <button id="classypress-delete-cache" type="button" class="button"><?php _e('Empty ClassyPress Cache', 'mittun_classy'); ?></button>
            </td>
          </tr>

          <tr valign="top">
            <th scope="row"><?php _e('Disable Plugin Autoupdate','mittun_classy'); ?></th>
            <td>
              <?php $disable_autoupdate=mittun_classy_get_option('disable_autoupdate','mittun_classy_advanced'); ?>

              <input type="checkbox" name="mittun_classy_advanced[disable_autoupdate]" value="true" <?php checked($disable_autoupdate,'true',true);?> />
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Custom CSS','mittun_classy'); ?></th>
            <td>
              <?php $custom_css=mittun_classy_get_option('custom_css','mittun_classy_advanced'); ?>

              <textarea name="mittun_classy_advanced[custom_css]" class="regular-text" cols="70" rows="10"><?php echo $custom_css; ?></textarea>
            </td>
          </tr>

          <?php
          break;
        }
        ?>
      </table>


      <?php submit_button(); ?>
    </form>
  <?php } ?>

</div>
