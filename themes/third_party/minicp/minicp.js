var minicp_autocomplete;

$(document).ready(function() {
	
	$('.minicp li.search').css('position', 'static');
	
	$('.minicp-left li.li1.active, .minicp-right li.li1.active').css('background', 'blue !important');
	
	$('body').css('padding-top', 47);
	
	$('.minicp a.disabled').css('opacity', 0.4);

	$('.minicp .more > a').click(
		function()
		{
			var el = $(this).parent();
			if(el.hasClass('active')) {
				el.removeClass('active');
			} else {
				$('.minicp .active').removeClass('active');
				el.addClass('active');
				
			}
			return false;
		}
	);
	
	$(document).click(
		function()
		{
			$('.minicp .active').removeClass('active');
		}
	);
	
	$('.minicp a.disabled').click(
		function()
		{
			return false;
		}
	);
	
	$('.minicp .search input').click(
		function()
		{
			return false;
		}
	);

	$('.minicp .search input').focus(
		function()
		{
			var el = $(this);
			$('.minicp .active').removeClass('active');
			$( "#minicp-jquery" ).autocomplete("search");
			
			el.animate(
				{
			    	width: ['260', 'easeOutCubic']
			  	},
			  	400,
			  	function()
			  	{
				    // Animation complete.
				    if($($("#minicp-search-results ul")[0]).css('display') == "block")
				    {
				    	$('.search').addClass('active');
				    }
			  	}
			);
			  
			el.parent().parent().animate(
				{
			    	opacity: 1
			  	},
			  	400,
			  	function()
			  	{
			    	// Animation complete.
			  	}
			);
			
			if(el.attr('alt') == el.attr('value'))
			{
				el.attr('value', "");
			}
		}
	);
	
	
	// search
	
	if(typeof($('.minicp .search input').attr('value'))   == "undefined")
	{
		$('.minicp .search input').attr('value', $('.minicp .search input').attr('alt'));
	}
	
	$('.minicp .search').css('opacity', 0.6);
	
	$('.minicp .search input').blur(
		function()
		{
			var el = $(this);
			el.animate(
				{
			    	width: ['100', 'easeOutCubic']
			  	},
			  	400,
			  	function()
			  	{
			    	// Animation complete.
			  	}
			);
			  
			el.parent().parent().animate(
				{
			    	opacity: 0.6
			  	},
			  	400,
			  	function()
			  	{
			    	// Animation complete.
			  	}
			);
			
			if(typeof(el.attr('value'))   == "undefined")
			{
				el.attr('value', el.attr('alt'));
			}
		}
	);
	

	// auto complete

	$( "#minicp-jquery").autocomplete("destroy");
	
	$( "#minicp-jquery").autocomplete(
		{
			source: $( "#minicp-jquery" ).attr('rel'),
			appendTo:"#minicp-search-results",
			open: function()
			{
				$(this).parent().parent().addClass('active');
			},
			close: function()
			{
				$(this).parent().parent().removeClass('active');
			},
			select:function(event, ui)
			{
				var url = ui.item.cp_link;
				
				location.href = url;
			}
		}
	).data("autocomplete")._renderItem = function(ul, item)
	{
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><em>" + item.channel_title + "</em>" + item.entry_title + "</a>" )
				.appendTo( ul );
	};


});
