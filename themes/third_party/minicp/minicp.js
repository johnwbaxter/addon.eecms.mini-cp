var minicp_autocomplete;

$(document).ready(function() {

	$('.minicp a.disabled').css('opacity', 0.4);



	$('.minicp .more > a').click(function() {
		var el = $(this).parent();
		if(el.hasClass('active')) {
			el.removeClass('active');
		} else {
			$('.minicp .active').removeClass('active');
			el.addClass('active');
			
		}
		return false;
	});
	
	$(document).click(function() {
		$('.minicp .active').removeClass('active');
	});
	
	$('.minicp a.disabled').click(function() {
		return false;
	});
	
	$('.minicp .search input').click(function() {
	
		return false;
	});
	
	$('.minicp .search input').focus(function() {
		var el = $(this);
		$('.minicp .active').removeClass('active');
		$( "#minicp-jquery" ).autocomplete("search");
		el.animate({
		    width: ['200', 'easeOutCubic'],
		  }, 400, function() {
		    // Animation complete.
		    if($($("#minicp-search-results ul")[0]).css('display') == "block") {
		    	$('.search').addClass('active');
		    }
		  });
		el.parent().parent().parent().parent().animate({
		    opacity: 1,
		  }, 400, function() {
		    // Animation complete.
		  });
		if(el.attr('alt') == el.attr('value')) {
			el.attr('value', "");
		}
	});
	
	/* search */
	
	if(typeof($('.minicp .search input').attr('value'))   == "undefined") {
		$('.minicp .search input').attr('value', $('.minicp .search input').attr('alt'))
	}
	
	$('.minicp .search').css('opacity', 0.6);
	
	$('.minicp .search input').blur(function() {
		var el = $(this);
		el.animate({
		    width: ['100', 'easeOutCubic'],
		  }, 400, function() {
		    // Animation complete.
		  });
		  
		el.parent().parent().parent().parent().animate({
		    opacity: 0.6,
		  }, 400, function() {
		    // Animation complete.
		  });
		if(typeof(el.attr('value'))   == "undefined") {
			el.attr('value', el.attr('alt'));
		}
	});
	
	/* auto complete */


	$( "#minicp-jquery" ).autocomplete({
		source: $( "#minicp-jquery" ).attr('rel'),
		appendTo:"#minicp-search-results",
		open: function() {
			$(this).parent().parent().parent().parent().addClass('active');
		},
		close: function() {
			$(this).parent().parent().parent().parent().removeClass('active');
		
		},
		select:function(event, ui) {
			
			//console.log(ui.item.id);
			//console.log(ui.item.channel_id);
			var url = $("#minicp-search-results").attr('rel') + "&channel_id=" + ui.item.channel_id + "&entry_id=" + ui.item.id;
			
			location.href = url;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><em>" + item.channel_title + "</em>" + item.entry_title + "</a>" )
				.appendTo( ul );
		};
		
	//minicp_autocomplete.search();


});
