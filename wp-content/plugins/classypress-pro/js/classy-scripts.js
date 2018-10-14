jQuery(document).ready(function($){

	
	$(document).on('click','.mittun-classy-sliding-open',function(){
		$(".mittun-classy-sidenav").css('padding-left','20px').css('padding-right','20px').css('width','450px');
	});
	$(document).on('click','.mittun-classy-sidenav .closebtn',function(){
		$(".mittun-classy-sidenav").css('width','0px').css('padding-left','0px').css('padding-right','0px');
	});
	

	$(document).on('click','.mittun-classy-donate',function(){

	var elem=$(this);

	var src=elem.attr('data-mfp-src');
	
	if($(src).length){

	

		$('#eid').val(elem.attr('data-campaign-id'));	
		
		
			$.magnificPopup.open({

			  items: {

				src: src, // can be a HTML string, jQuery object, or CSS selector

				type: 'inline'

			  },

			  callbacks: {

				open: function() {
					
					 $(src).find("input:button").removeClass('active');					 

				},
				

				},

			});
		

		

		return false;

	}

	

	});
	
	
	
	
	$(document).on('keyup','input[name="amount"]',function(){  
		var valid = /^\d+(\.\d{0,2})?$/.test(this.value),
        val = $(this).val();
    
		if(!valid){
			console.log("Invalid input!");
			$(this).val(val.substring(0, val.length - 1));
		}
	});

	$(document).on('click','.classy-amount input:button',function(){

		

		var parent=$(this).closest('p');

		var parentForm=parent.closest('form');

		

		parent.find("input:button").removeClass('active');

		$(this).addClass('active');

		

		var amt=$(this).attr('data-amount');

		

		if(typeof amt==='undefined'){

			parentForm.find("input[name='amount']").val('').focus();

		}

		else

		{

			parentForm.find("input[name='amount']").val(amt);

		}

		

		

	});

					

	$(document).on('click','.classy-donation-form.short input[name="recurring"]',function(){
		$(this).closest('form').submit();																					   
	});
	
	$(document).on('click','.mittun-classy-activity-more input[type="button"]',function(){
		var elem=$(this);
		var current_page=elem.data('current');
		var last_page=elem.data('last');
		var basic=elem.data('basic');
		var id=elem.data('id');
		
		elem.hide();
		elem.next('img').show();
				
		var data = {
		'action': 'mittun_classy_more_activity',
		'current_page': current_page,
		'last_page': last_page,
		'basic': basic,
		'id': id,
		};
		
		jQuery.post(mittunClassy.ajax_url, data, function(response) {
			elem.next('img').hide();
			elem.show();
			if(response.error!=true)
			{
				elem.closest('.mittun-classy-activity-more').before(response.loop_data);
				elem.data('current',response.current_page);
				
				if(response.current_page==last_page)
					elem.remove();
			}
		},'json');
		
	});
	
	$(document).on('click','.mittun-classy-donation-more input[type="button"]',function(){
		var elem=$(this);
		var current_page=elem.data('current');
		var last_page=elem.data('last');
		var basic=elem.data('basic');
		var id=elem.data('id');
		
		elem.hide();
		elem.next('img').show();
				
		var data = {
		'action': 'mittun_classy_more_donation',
		'current_page': current_page,
		'last_page': last_page,
		'basic': basic,
		'id': id,
		};
		
		jQuery.post(mittunClassy.ajax_url, data, function(response) {
			elem.next('img').hide();
			elem.show();
			if(response.error!=true)
			{
				elem.closest('.mittun-classy-donation-more').before(response.loop_data);
				elem.data('current',response.current_page);
				
				if(response.current_page==last_page)
					elem.remove();
			}
		},'json');
		
	});
	
	$(document).on('load', function()
	{
		
		var event_col_2_max=event_col_3_max=leaderboard_col_2_max=leaderboard_col_3_max=0;

		
		
		if($('.events-container-inner').length)
		{			
			$('.events-container-inner').each(function(){
				//For event two coloumn
				if($(this).find('.event-col-2').length){
					$(this).find('.event-col-2').each(function(){
						if($(this).height() > event_col_2_max)
						event_col_2_max=$(this).height();
					});
					$(this).find('.event-col-2').height(event_col_2_max);
				}
				
				//For event three coloumn
				if($(this).find('.event-col-3').length){
					$(this).find('.event-col-3').each(function(){
						if($(this).height() > event_col_3_max)
						event_col_3_max=$(this).height();
					});
					$(this).find('.event-col-3').height(event_col_3_max);
				}
				
				
			});
		}
		
		if($('.leaderboard-container-inner').length)
		{			
			$('.leaderboard-container-inner').each(function(){
				//For leaderboard two coloumn
				if($(this).find('.leaderboard-col-2').length){
					$(this).find('.leaderboard-col-2').each(function(){
						if($(this).height() > leaderboard_col_2_max)
						leaderboard_col_2_max=$(this).height();
					});
					$(this).find('.leaderboard-col-2').height(leaderboard_col_2_max);
				}
				
				//For leaderboard three coloumn
				if($(this).find('.leaderboard-col-3').length){
					$(this).find('.leaderboard-col-3').each(function(){
						if($(this).height() > leaderboard_col_3_max)
						leaderboard_col_3_max=$(this).height();
					});
					$(this).find('.leaderboard-col-3').height(leaderboard_col_3_max);
				}
				
				
			});
		}
		
	});

});