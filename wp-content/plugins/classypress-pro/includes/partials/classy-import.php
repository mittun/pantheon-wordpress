<div class="wrap">
<h2><?php _e('Import ClassyPress Campaign Elements','mittun_classy'); ?></h2>

<table class="wp-list-table widefat plugins">
  <thead>
    <tr>
      <th scope="col" ><?php _e('Template Name','mittun_classy'); ?></th>
      <th scope="col" ><?php _e('Element Type','mittun_classy'); ?></th>
      <th scope="col"><?php _e('Preview','mittun_classy'); ?></th>
      <th scope="col"><?php _e('Import','mittun_classy'); ?></th>
    </tr>
  </thead>
  <tbody id="the-list">
    <?php
    while (false !== ($entry = $dir->read())) {
      if ($entry != '.' && $entry != '..') {
        if (is_dir($path . '/' .$entry)) {
          ?>
          <tr class="inactive">
            <td style="vertical-align:middle">
              <?php echo $entry; ?>
            </td>
            <td style="vertical-align:middle">
              <?php
              if(file_exists($path . '/' .$entry.'/element.txt'))
              {
                echo file_get_contents($path . '/' .$entry.'/element.txt');
              }
              ?>
            </td>
            <td>
              <?php
              if(file_exists($path . '/' .$entry.'/screenshot.png'))
              {
                ?>
                <a href="<?php echo MITTUN_CLASSY_URL . '/themes/' .$entry.'/screenshot-large.png'; ?>" class="mittun-theme-popup"><img src="<?php echo MITTUN_CLASSY_URL . '/themes/' .$entry.'/screenshot.png'; ?>" height="150" width="150"></a>
                <?php
              }
              ?>
            </td>
            <td style="vertical-align:middle">
              <input  class="button button-primary button-large import-campaign" data-dir="<?php echo $entry; ?>" value="<?php _e('Import Now','mittun_classy'); ?>" type="button"><img src="<?php echo MITTUN_CLASSY_URL; ?>/img/loader.gif" style="display:none;"/>
              <br/>
              <?php
              $imported_count=get_option($entry.'_imported_count');
              if(!empty($imported_count)){
                echo sprintf( _n( 'Imported (%s) time', 'Imported (%s) times', $imported_count, 'mittun_classy' ), $imported_count );
              }
              ?>
            </td>

          </tr>
          <?php
        }
      }
    }
    ?>

  </table>
</div>
