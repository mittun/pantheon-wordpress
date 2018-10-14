<?php

/**
 * Template Name: ClassyPress Donation Page
 *
 * Custom page template for auto donation page generation
 *
 * @package classypress-pro
 * @subpackage classypress-pro/templates
 */

get_header(); ?>

<?php
if(have_posts()) :
  while(have_posts()) :
    the_post();

    // Get background image
    $bg = ClassyPress_Templates::return_donation_background($post);

    // What type of form is this?
    $display_form_type = get_post_meta($post->ID, '_classy_campaign_display_form_type', true);

    $inline_top_text = false;
    $inline_bottom_text = false;

    if($display_form_type === 'inline') {
      $inline_top_text = get_post_meta($post->ID, '_classy_campaign_inline_top_text', true);
      $inline_bottom_text = get_post_meta($post->ID, '_classy_campaign_inline_bottom_text', true);
    }

    // Custom CSS
    $custom_css = get_post_meta($post->ID, '_classy_campaign_custom_css', true);
    echo '<style>' . ClassyPress_Templates::strip_custom_css_comments($custom_css) . '</style>';
  ?>

  <div id="classypress" class="mittun-classy"<?php echo $bg; ?>>

      <div class="mittun-classy-container">

        <h1 class="classy-form-title"><?php the_title(); ?></h1>

        <?php if($display_form_type === 'inline' && $inline_top_text) : ?>
          <div class="classy-form-description">
            <?php echo $inline_top_text; ?>
          </div>
        <?php endif; ?>

        <?php echo do_shortcode('[mittun_non_classy id="'. $post->ID . '"]'); ?>

        <?php if($display_form_type === 'inline' && $inline_bottom_text) : ?>
          <div class="classy-form-description">
            <?php echo $inline_bottom_text; ?>
          </div>
        <?php endif; ?>
      </div>
  </div>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
