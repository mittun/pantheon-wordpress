/**
 * jQuery to trigger image upload via WP
 */

jQuery(function($) {
	/*
	* Select/Upload image(s) event
	*/
	$('body').on('click', '.classypress_upload_image_button', function(e) {
		e.preventDefault();

		var button = $(this);
		var custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				type : 'image'
			},
			button: {
				text: 'Set Background Image' // button label text
			},
			multiple: false
		}).on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();

			$(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
		}).open();
	});

	/*
	* Remove image event
	*/
	$('body').on('click', '.classypress_remove_image_button', function() {
		$(this).hide().prev().val('').prev().addClass('button').html('Upload image');
		return false;
	});

	// Initialize color picker
	$('.bg-color').wpColorPicker();
});
