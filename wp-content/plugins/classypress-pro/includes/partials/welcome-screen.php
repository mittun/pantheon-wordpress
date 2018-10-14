<div class="wrap">

  <h1 style="color:#388E3C;font-weight:500"><i class="dashicons dashicons-yes"></i>Yippee! ClassyPress is now installed on your website.</h1>
  <?php if(empty($_COOKIE['close-welcome-notification']) || (!empty($_COOKIE['close-welcome-notification']) && $_COOKIE['close-welcome-notification']!='yes')) : ?>
    <div class="notice-success notice  is-dismissible" style="display: table; position: relative;height: 70px;padding: 0;border: 0;overflow: hidden;margin-bottom: 10px;">
      <div style="padding: 15px 15px 10px 15px; display: inline-block;">
        <img style="width:40px;display: inline;height: 40px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDEyOCAxMjgiIGhlaWdodD0iMTI4cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAxMjggMTI4IiB3aWR0aD0iMTI4cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik02NCwxMy41Yy0yOC43LDAtNTIsMjMuMy01Miw1MnMyMy4zLDUyLDUyLDUyczUyLTIzLjMsNTItNTJTOTIuNywxMy41LDY0LDEzLjV6IE02NCwxMTMuNSAgYy0yNi41LDAtNDgtMjEuNS00OC00OHMyMS41LTQ4LDQ4LTQ4czQ4LDIxLjUsNDgsNDhTOTAuNSwxMTMuNSw2NCwxMTMuNXoiIGZpbGw9IiMzQjk3RDMiLz48cGF0aCBkPSJNNjQsMzYuNWMzLjMsMCw2LDIuNyw2LDZWNjBoMThjMy4zLDAsNiwyLjcsNiw2cy0yLjcsNi02LDZINzB2MTguNWMwLDMuMy0yLjcsNi02LDZzLTYtMi43LTYtNlY3Mkg0MCAgYy0zLjMsMC02LTIuNy02LTZzMi43LTYsNi02aDE4VjQyLjVDNTgsMzkuMSw2MC43LDM2LjUsNjQsMzYuNSBNNjQsMzQuNWMtNC40LDAtOCwzLjYtOCw4VjU4SDQwYy00LjQsMC04LDMuNi04LDhzMy42LDgsOCw4aDE2ICB2MTYuNWMwLDQuNCwzLjYsOCw4LDhzOC0zLjYsOC04Vjc0aDE2YzQuNCwwLDgtMy42LDgtOHMtMy42LTgtOC04SDcyVjQyLjVDNzIsMzgsNjguNCwzNC41LDY0LDM0LjVMNjQsMzQuNXoiIGZpbGw9IiMyQzNFNTAiLz48L3N2Zz4=" width="128" height="128">
        <div style="display: inline;position: relative;margin-left: 5px;font-weight: 300;
        top: -14px;font-size: 20px;"> Introducing the all-new ClassyPress: <?php echo $plugin_version; ?></div>
        <a href="https://www.mittun.com/classypress/#changelog" target="_blank" style="position: relative;top: -17px; background: #ECEFF1;text-decoration: none;color: #111;font-size: 10px;padding: 4px 10px 5px 10px;border-radius: 4px;margin-left: 5px;text-transform: uppercase;border: 1px solid rgba(207, 216, 220, 0.9);">What's New?</a>
      </div>
      <div style="display:inline-block;float:right;height: 70px;background: #333;width: 50px;text-align: center;"><a title="Close this Notification" id="close-welcome-notification" style="color: #fff;text-decoration: none;top: 26px;position: relative;padding: 24px 18px;font-size: 17px;font-weight: 300;background: #333;
      z-index: 100;" href="#">X</a>
    </div>

    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
  </div>
<?php endif; ?>

<p style=" font-family: georgia; font-size: 20px; font-style: italic; margin-bottom: 3px; line-height: 1.5; color: #666;">Thank you for chosing the industry-leading plugin for connecting Classy with WordPress. </p>
<p style="max-width:60%; font-family: georgia;font-size: 20px;margin-top: 4px;font-style: italic;line-height: 1.2;color: #666;"><strong>Speed Improvements:</strong><br />
  <ol>
    <li>Update: Major logic rewrite to securely cache Access Token</li>
    <li>Update: Removed unneeded stats widget that was in beta</li>
    <li>Update: Removed transactions database</li>
    <li>Update: Removed notification features in advanced settings</li>
    <li>View the complete list of changes by going to the <a title="Click Here to view the ClassyPress Changelog" href="https://www.mittun.com/classypress/#changelog" target="_blank">ClassyPress Changelog</a></li>
  </p>

  <div style="max-width: 550px;background: #fff;border: 1px solid #ddd;padding: 25px 27px 25px 26px;border-radius: 2px; margin-top: 19px; overflow: hidden;">

    <div style="float: left;">
      <p style="margin-top:0;margin-bottom:0;font-size: 15px;line-height: 1;"><b>Login to your Classy Account Dashboard</b></p>
      <p style="margin-top: 8px;margin-bottom:0px;"><b>1.)</b> Create an app in Classy<br />
        <b>2.)</b> Retrieve your API credentials and Organization ID<br />
        <b>3.)</b> <a target="_blank" title="Add your Classy API credentials to ClassyPress" href="<?php echo admin_url('admin.php?page=mittun-classy'); ?>">Add your Classy Credentials to ClassyPress advanced settings</a> <br />
        <b>4.)</b> Import a demo campaign to get you started <br />
        <b>5.)</b> Assign the demo campaign to a campaign in your Classy account<br />
        <b>6.)</b> Publish the ClassyPress campaign and copy your shortcode <br />
        <b>7.)</b> Paste the shortcode anywhere on your WordPress website!</p>
        <b>8.)</b> That's it! Now go enjoy yourself :)</p>
      </div>

      <div style="float: right;">
        <a target="_blank" title="Add your Classy API credentials to ClassyPress" href="<?php echo admin_url('admin.php?page=mittun-classy'); ?>">Quick Link: ClassyPress Advanced Settings</a>
      </div>

    </div>


    <h1 style="color: #303F9F;font-weight: 500;margin-top: 30px;"><i class="dashicons dashicons-editor-help" style="font-size: 36px; margin-right: 20px; margin-top: -1px;"></i>Need Help?</h1>

    <p style="font-family: georgia;font-size: 20px;font-style: italic;margin-bottom: 3px;line-height: 1.5;margin-top: 11px;color: #666;">We're a bunch of passionate people that love life and are dedicated to helping nonprofits like yours thrive online. Please forward all inquiries to support@mittun.com and we will be more than happy to help.</p>

    <div style="display: inline-block;margin: 15px 0px 5px 0px; overflow: hidden;">
      <div style="float: left;margin-right: 15px;background: #fff;border: 1px solid #ddd;
      padding: 5px 25px 10px 23px;    border-radius: 2px;">
        <p><b>Getting Started</b></p>
        <ul style="margin-top: 6px;">
          <li><a href="http://help.mittun.com/article/23-what-if-i-dont-see-api-tab" target="_blank">What if i don't see the API tab?</a></li>
          <li><a href="http://help.mittun.com/article/9-how-to-add-settings-for-the-first-time" target="_blank">Settings &amp; Options</a></li>
          <li><a href="http://help.mittun.com/article/19-create-app-in-classy" target="_blank">Create app in classy</a></li>
          <li><a href="http://help.mittun.com/article/18-how-to-obtain-organization-id" target="_blank">How to obtain organization ID</a></li>
        </ul>
      </div>
      <div style="float: left;margin-right: 15px;background: #fff;border: 1px solid #ddd;
      padding: 5px 25px 10px 23px;border-radius: 2px;">
        <p><b>Useful Links</b></p>
        <ul style="margin-top: 6px;">
          <li><a href="http://help.mittun.com/article/13-how-to-set-up-a-campaign-short-form" target="_blank">How to set up a campaign short form</a></li>
          <li><a href="http://help.mittun.com/article/12-how-to-set-up-campaign-with-pop-up" target="_blank">How to set up campaign with pop up</a></li>
          <li><a href="http://help.mittun.com/article/10-how-to-add-custom-css" target="_blank">How to add custom CSS</a></li>
          <li><a href="http://help.mittun.com/article/15-how-to-set-up-individual-leaderboards" target="_blank">How to set up individual leaderboards</a></li>
        </ul>
      </div>
    </div>
</div>
