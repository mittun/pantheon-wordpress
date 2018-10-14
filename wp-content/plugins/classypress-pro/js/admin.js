jQuery(document).ready(function($){

	$('#classypress-delete-cache').on('click', function(e) {
		e.preventDefault();

		var data = {
			'action': 'classypress_clear_cache'
		}

		$.post(
			ajaxurl,
			data,
			function(resp) {
				if(resp === 'success') {
					alert("ClassyPress cache has been cleared!");
				} else {
					alert("There was an error clearing the ClassyPress cache. Please try again.");
				}
			}
		);
	});

	jQuery('.classy-color-picker').wpColorPicker();
	jQuery( ".classy-button-set" ).buttonset();
	jQuery('#close-welcome-notification').click(function(){
		Cookies.set('close-welcome-notification', 'yes');
		$('.notice-success.notice').hide();
	});
	jQuery(".chosen-select").chosen({width: "95%"});
	jQuery(document).on('click','#show_credentials',function(){
		if($(this).is(":checked"))
			$('input[name="mittun_classy\[client_id\]"],input[name="mittun_classy\[client_secret\]"]').attr('type','text');
		else
			$('input[name="mittun_classy\[client_id\]"],input[name="mittun_classy\[client_secret\]"]').attr('type','password');
	});
	jQuery(document).on('click','.mittun-classy-copy',function(){
		var val=jQuery.trim(jQuery('#mittun_classy_shortcode').text());
		var hiddenClipboard = jQuery('#_hiddenClipboard_');
		if(!hiddenClipboard.length){
			jQuery('body').append('<textarea style="position:fixed;top: 0px;left:0px;width:1px;height:1px;" id="_hiddenClipboard_"></textarea>');
			hiddenClipboard = jQuery('#_hiddenClipboard_');
		}
		hiddenClipboard.html(val);
		hiddenClipboard.select();
		document.execCommand('copy');
		document.getSelection().removeAllRanges();
	});

	$(document).on('click', '.mittun-classy-upload', function(e){
		 e.preventDefault();
			var elem=$(this);
            var button = $(this),
            custom_uploader = wp.media({
            title: 'Insert image',
            library : {
                type : 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
			}).on('select', function() { // it also has "open" and "close" events
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				//$(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
				/* if you sen multiple to true, here is some code for getting the image IDs
				var attachments = frame.state().get('selection'),
					attachment_ids = new Array(),
					i = 0;
				attachments.each(function(attachment) {
					attachment_ids[i] = attachment['id'];
					console.log( attachment );
					i++;
				});
				*/
				elem.prev('input[type="text"]').val(attachment.url);
				var snap_html='<img src="'+attachment.url+'" style="max-width:50px;max-height:50px;">';
				elem.siblings('.mittun-uploaded-snap').html(snap_html);
			})
			.open();
		});

	jQuery(document).on('click','input[name*="checkout_url_type"]',function(){
		if($(this).val()=='custom')
		$(this).closest('tr').next('tr').show();
		else
		$(this).closest('tr').next('tr').hide();

	});

	jQuery(document).on('click','input[name="_classy_campaign_skin"]',function(){
		var campaign_skin=$(this).val();
		if(campaign_skin=='skin_3'){
		$(this).closest('tr').nextUntil('tr.sliding-style-end').not().show();
		}
		else{
		$(this).closest('tr').nextUntil('tr.sliding-style-end').hide();
		}

	});

	var progress_bar_style=$('input[name*=progress_bar_style]:checked').val();
	display_progress_bar_style_sanp(progress_bar_style);
	$(document).on('change','input[name*=progress_bar_style]',function(){
		var progress_bar_style=$(this).val();
		display_progress_bar_style_sanp(progress_bar_style);
	});
	function display_progress_bar_style_sanp(progress_bar_style)
	{
		$('img[data-rel]').hide();
		$('img[data-rel="'+progress_bar_style+'"]').show();
	}

	jQuery(document).on('click','input[name="_classy_campaign_display_campaign_title"],input[name="_classy_combined_campaign_display_campaign_title"],input[name="_classy_leaderboard_display_title"],input[name="_classy_leaderboard_title_link"],input[name="_classy_event_display_title"]',function(){
		if($(this).is(":checked"))
		$(this).closest('tr').nextUntil('tr.display-campaign-title-end').not().show();
		else
		$(this).closest('tr').nextUntil('tr.display-campaign-title-end').hide();

	});

	jQuery(document).on('click','input[name="_classy_campaign_display_progress_bar"],input[name="_classy_combined_campaign_display_progress_bar"],input[name="_classy_leaderboard_display_progress_bar"],input[name="_classy_event_display_progress_bar"]',function(){

		if($(this).is(":checked"))

		$(this).closest('tr').nextUntil('tr.progress-bar-style-end').not().show();

		else

		$(this).closest('tr').nextUntil('tr.progress-bar-style-end').hide();

	});

	jQuery(document).on('click','input[name="_classy_leaderboard_display_intro_text"],input[name="_classy_event_display_intro_text"]',function(){

		if($(this).is(":checked"))

		$(this).closest('tr').nextUntil('tr.display-intro-text-end').not().show();

		else

		$(this).closest('tr').nextUntil('tr.display-intro-text-end').hide();

	});

	jQuery(document).on('click','input[name="_classy_campaign_display_goal_amount"],input[name="_classy_combined_campaign_display_goal_amount"],input[name="_classy_leaderboard_display_goal_amount"],input[name="_classy_event_display_goal_amount"]',function(){

		if($(this).is(":checked"))

		$(this).closest('tr').nextUntil('tr.display-goal-amount-end').not().show();

		else

		$(this).closest('tr').nextUntil('tr.display-goal-amount-end').hide();

	});



	jQuery(document).on('click','input[name="_classy_campaign_display_amount_raised"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').not().show();
			if($('input[name="_classy_campaign_display_amount_raised_heading"]').is(":checked"))
			$('input[name="_classy_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').not().show();
			else
			$('input[name="_classy_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}
		else{
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').hide();
		$('input[name="_classy_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}

	});

	jQuery(document).on('click','input[name="_classy_combined_campaign_display_amount_raised"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').not().show();
			if($('input[name="_classy_combined_campaign_display_amount_raised_heading"]').is(":checked"))
			$('input[name="_classy_combined_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').not().show();
			else
			$('input[name="_classy_combined_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}
		else{
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').hide();
		$('input[name="_classy_combined_campaign_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}

	});

	jQuery(document).on('click','input[name="_classy_leaderboard_display_primary_btn"],input[name="_classy_event_display_primary_btn"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.primary-btn-style-end').not().show();
		}
		else{
		$(this).closest('tr').nextUntil('tr.primary-btn-style-end').hide();
		}

	});

	jQuery(document).on('click','input[name="_classy_campaign_display_form_type"]',function(){
		var display_type = $(this).val();

		if($(this).hasClass('classy-donation-template')) {
			if(display_type == 'popup') {
				$('tr.classy-popup-custom-field').show();
				$('tr.classy-inline-custom-field').hide();
			} else {
				$('tr.classy-popup-custom-field').hide();
				$('tr.classy-inline-custom-field').show();
			}
		} else {
			if(display_type=='popup'){
				$(this).closest('tr').nextUntil('tr.primary-btn-style-end').not().show();
			} else {
				$(this).closest('tr').nextUntil('tr.primary-btn-style-end').hide();
			}
		}

	});

	jQuery(document).on('change','input[name="_classy_campaign_display_custom_checkout_url"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').next('tr').show();
		}
		else{
		$(this).closest('tr').next('tr').hide();
		}
	});


	jQuery(document).on('click','input[name="_classy_campaign_display_account_activity"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.display-activity-style-end').not().show();
		$('input[name="_classy_campaign_display_activity_title"]').trigger('click');
		}
		else{
		$(this).closest('tr').nextUntil('tr.display-activity-style-end').hide();
		}

	});
	jQuery(document).on('click','input[name="_classy_campaign_display_donation"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.display-donation-style-end').not().show();
		$('input[name="_classy_campaign_display_donation_title"]').trigger('click');
		}
		else{
		$(this).closest('tr').nextUntil('tr.display-donation-style-end').hide();
		}

	});
	jQuery(document).on('click','input[name="_classy_campaign_display_activity_title"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.display-activity-title-style-end').not().show();
		}
		else{
		$(this).closest('tr').nextUntil('tr.display-activity-title-style-end').hide();
		}

	});
	jQuery(document).on('click','input[name="_classy_campaign_display_donation_title"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.display-donation-title-style-end').not().show();
		}
		else{
		$(this).closest('tr').nextUntil('tr.display-donation-title-style-end').hide();
		}

	});



	jQuery(document).on('click','input[name="_classy_leaderboard_display_amount_raised"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').not().show();
			if($('input[name="_classy_leaderboard_display_amount_raised_heading"]').is(":checked"))
			$('input[name="_classy_leaderboard_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').not().show();
			else
			$('input[name="_classy_leaderboard_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}
		else{
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').hide();
		$('input[name="_classy_leaderboard_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}

	});

	jQuery(document).on('click','input[name="_classy_event_display_amount_raised"]',function(){
		if($(this).is(":checked")){
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').not().show();
			if($('input[name="_classy_event_display_amount_raised_heading"]').is(":checked"))
			$('input[name="_classy_event_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').not().show();
			else
			$('input[name="_classy_event_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}
		else{
		$(this).closest('tr').nextUntil('tr.amount-raised-style-end').hide();
		$('input[name="_classy_event_display_amount_raised_heading"]').closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();
		}

	});

	jQuery(document).on('click','input[name="_classy_campaign_display_amount_raised_heading"],input[name="_classy_combined_campaign_display_amount_raised_heading"],input[name="_classy_leaderboard_display_amount_raised_heading"],input[name="_classy_event_display_amount_raised_heading"]',function(){
		if($(this).is(":checked"))
		$(this).closest('tr').nextUntil('tr.amount-raised-heading-style-end').not().show();
		else
		$(this).closest('tr').nextUntil('tr.amount-raised-heading-style-end').hide();

	});


	jQuery(document).on('click','input[name="_classy_campaign_donation_type"]',function(){

		var donation_type=$(this).filter(':checked').val();
		if(donation_type=='form')
		{
			$(this).closest('tr').nextUntil('tr.donation-type-style-end').hide();
			$(this).closest('tr').nextUntil('tr.display-form-style-end').show();
			$('input[name="_classy_campaign_display_form_type"]:checked').trigger('click');
			$('input[name="_classy_campaign_form_type"]:checked').trigger('click');
		}
		else if(donation_type=='fundraise')
		{
			$(this).closest('tr').nextUntil('tr.donation-type-style-end').show();
			$(this).closest('tr').nextUntil('tr.display-form-style-end').hide();
		}
		else if(donation_type=='none')
			$(this).closest('tr').nextUntil('tr.donation-type-style-end').hide();

	});

	jQuery(document).on('click','input[name="_classy_campaign_form_type"]',function(){
		var val=$(this).val();
		if(val=='short'){
		$(this).closest('tr').nextUntil('tr.display-form-style-end').show();
		$(this).closest('tr').nextUntil('tr.display-form-long-style-end').hide();

			/**
			 * Long/Short form Pre-set donation amount fields
			 */
			if($('input[name="_classy_campaign_set_donation_amt"]').is(':checked')) {

				$('.both:not(.other-amnt-input)').show();

				if($('input[name="_classy_campaign_display_custom_amount_btn"]').is(':checked')) {
					$('.both.other-amnt-input').show();
				}

				$('.both.amnt-input').show();
			} else {
				if($('input#_classy_campaign_display_custom_amount_btn').is(':checked')) {
					$('input#_classy_campaign_display_custom_amount_btn').prop('checked', false);
				}

				$('input#_classy_campaign_display_custom_amount_btn').removeAttr('checked');
				$('.both:not(.amnt-input, .other-amnt)').show();
			}

		}
		else if(val=='long'){
		$(this).closest('tr').nextUntil('tr.display-form-style-end').hide();
		$(this).closest('tr').nextUntil('tr.display-form-long-style-end').show();
		$('tr.display-form-short-style-end,tr.display-checkout-url-override').show();
		$('input[name="_classy_campaign_set_donation_amt"]').trigger('change');

		}
		$('input[name="_classy_campaign_display_custom_checkout_url"]').trigger('change');
		$('.submit-area.both, .form-custom-css.both').show();
	});

	jQuery(document).on('change','input[name="_classy_campaign_set_donation_amt"]',function(){
		if($(this).is(":checked")){
			 $(this).closest('tr').nextUntil('tr.set-amt-style-end').show();
			$('input[name="_classy_campaign_display_custom_amount_btn"]').trigger('change');
		}
		else{
			$(this).closest('tr').nextUntil('tr.set-amt-style-end').hide();
		}
	});
	jQuery(document).on('change','input[name="_classy_campaign_display_custom_amount_btn"]',function(){
		if($(this).is(":checked")){
			 $(this).closest('tr').nextUntil('tr.custom-amount-btn-style-end').show();
		}
		else{
			$(this).closest('tr').nextUntil('tr.custom-amount-btn-style-end').hide();
		}
	});

	jQuery('.mittun-classy-amt-more').click(function(){

		var field_name=jQuery(this).data('field');

		var container=jQuery(this).data('container');

		jQuery('#'+container).append('<p><input name="'+field_name+'" type="text" class="regular-text" value="" />&nbsp;<a href="javascript:void(0)" class="mittun-classy-amt-remove">X</a></p>');

	});

	/**
	 * Hide 'Checkout URL' if Campaign is Selected
	 * @return {[type]} [description]
	 */
	jQuery('#_classy_campaign_id').change(function() {
		var $this = jQuery(this);

		if($this.val() !== '' && $this.val() !== 'Select') {
			jQuery('#classypress_checkout_url').hide();
		} else {
			jQuery('#classypress_checkout_url').show();
		}
	});

	jQuery(document).on('click',".metabox_submit",function(e){
		e.preventDefault();
		jQuery('#publish').click();
	});



	jQuery(document).on('click','.mittun-classy-amt-remove',function(){

		jQuery(this).closest('p').remove();

	});


	//Admin menu in new tab
	$( "ul#adminmenu #toplevel_page_mittun-classy .wp-submenu li a" ).each(function(){

	var href=$(this).attr('href');
	if(href.indexOf("/mittun.com/")!=-1)
		$(this).attr('target','_blank');
	});

	$('.mittun-theme-popup').magnificPopup({
	  type: 'image',
	  image: {
			verticalFit: false
		},
	});

	$(document).on('click','.import-campaign',function(){

		var elem=$(this);
		var dir=elem.data('dir');
		elem.next('img').show();
		var data = {
			'action': 'import_campaign',
			'dir': dir
		};

		jQuery.post(ajaxurl, data, function(response) {
			elem.next('img').hide();
			elem.val(response.msg);
			if(response.error==false && response.redirect!='')
			{
				setTimeout(function(){ window.location.href=response.redirect }, 1000);
			}
		},'json');

	});

});
