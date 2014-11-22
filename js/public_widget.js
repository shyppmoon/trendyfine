var SWJquery = jQuery.noConflict();

SWJquery(function($) {
	//alert('iam loaded on public blog');
	
	// CAROUSEL
	var widget_index = 1;
	SWJquery('.sheinside-core-widget .sheinside-widget-carousel').each(function(){
		var root = SWJquery(this);
		
		var totalLinks   = root.find('ul>li').size();
		var visibleLinks = root.find('ul').carouFredSel().triggerHandler("configuration", "items.visible");
		var widgetWidth  = root.width();
		
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-next').hide();
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-prev').hide();
		
		var dynamicLinks = Math.ceil((totalLinks/visibleLinks)) * visibleLinks;
		var genLinks 	 = dynamicLinks - totalLinks;
		var cloneLinks   = root.find('ul > li:lt('+ genLinks +')').clone();
		root.find('ul').carouFredSel().append(cloneLinks);
	
		root.attr('id', 'sheinside-widget-' + widget_index);
		root.find('ul').carouFredSel({
			prev : '#sheinside-widget-' + widget_index + ' .sheinside-widget-carousel-prev',
			next : '#sheinside-widget-' + widget_index + ' .sheinside-widget-carousel-next',
			pagination : '#sheinside-widget-' + widget_index + ' .sheinside-widget-carousel-pager',
			circular : false,
			infinite: true,
			auto: false,
			height : 128,
			width: widgetWidth,
			align: "center",
			scroll:
			{
				easing: 'swing'
			},

			debug: false
		});
		
		//Force showing the pager to keep the heights the same
		if(root.find('.sheinside-widget-carousel-pager').hasClass('hidden'))
		{
			root.find('.sheinside-widget-carousel-pager').removeClass('hidden').show();
		}
		
		widget_index++;
	});
	
	//Hides the 
	SWJquery('.sheinside-core-widget').mouseleave(function(){
		
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-next').hide();
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-prev').hide();
	});
	
	SWJquery('.sheinside-core-widget').mouseenter(function(){
		
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-next').fadeIn("slow").show();
		SWJquery('.sheinside-core-widget .sheinside-widget-carousel-prev').fadeIn("slow").show();
	});
	

	

	// SHOW DESCRIPTION FOR ITEMS
	SWJquery('.sheinside-core-widget .sheinside-widget-carousel li').mouseenter(function() {
		var self = $(this);
		self.closest('.sheinside-widget-carousel').find('li').removeClass('hovered').removeClass('not-hovered');
		self.closest('li').addClass('hovered').siblings().addClass('not-hovered');
		self.closest('.sheinside-widget-carousel').find('.sheinside-widget-carousel-info').html(
		self.find('div.sheinside-widget-carousel-description').html()).stop(true, true).delay(500).slideDown();
	});
	
	
	var box_keep_open = '.sheinside-core-widget .sheinside-widget-carousel li, .sheinside-core-widget .sheinside-widget-carousel-info';
	SWJquery(box_keep_open).mouseleave(function(e) {
		if(!SWJquery(e.relatedTarget).is(box_keep_open) && SWJquery(e.relatedTarget).closest(box_keep_open).length == 0) {
			$(this).closest('.sheinside-widget-carousel').find('.hovered').removeClass('hovered');
			$(this).closest('.sheinside-widget-carousel').find('.not-hovered').removeClass('not-hovered');
			$(this).closest('.sheinside-widget-carousel').find('.sheinside-widget-carousel-info').slideUp();
			$(this).closest('.sheinside-widget-carousel').find('.sheinside-widget-carousel-info').html(
			$(this).find('div.sheinside-widget-carousel-description').html()).stop(true, true).delay(500).queue(function() {
				$(this).hide();
			});
		}
	}); 
	
	SWJquery('.sheinside-widget-carousel-info').hide();

	// ACCORDION MENU
	SWJquery('.sheinside-core-widget').each(function(){
		var self = SWJquery(this);
		self.find('.sheinside-widget-panel').hide();
		self.find('.sheinside-widget-heading:first').addClass('selected').next('.sheinside-widget-panel').slideDown();
		self.find('.sheinside-widget-heading').click(function(e){
			e.preventDefault();
			if($(this).hasClass('selected')) return;
			$(this).closest('.sheinside-core-widget').find('.sheinside-widget-heading').removeClass('selected');
			$(this).closest('.sheinside-core-widget').find('.sheinside-widget-panel').slideUp();
			$(this).addClass('selected').next('.sheinside-widget-panel').slideDown();
		});
		
	});
	
});
