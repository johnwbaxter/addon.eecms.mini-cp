$(document).ready(function() {

	/* Drag & Drop */

	$( "#minicp-cp ul" ).disableSelection();
	
	$( "#minicp-cp ul" ).sortable({
		connectWith: "ul",
		dropOnEmpty: true,
		update:function(e,ui) {
			minicp_save_toolbar();
		}
	});


	var minicp_enable = $($('#minicp-enable').find('input')[0]);
	var minicp_left = $($('#minicp-cp .minicp-left')[0]);
	var minicp_right = $($('#minicp-cp .minicp-right')[0]);
	
	minicp_enable.change(function() {
		minicp_save_toolbar();
	});
	
	$( "#minicp-cp a" ).click(function() {
		return false;
	});
	
	function minicp_save_toolbar() {
		//console.log(minicp_enable.attr('checked'));
		var left_links = "";
		var right_links = "";

		minicp_left.find('li').each(function(i,el) {
			left_links += $(el).attr('rel')+",";
		});
		
		
		minicp_right.find('li').each(function(i,el) {
			right_links += $(el).attr('rel')+",";
		});
		
		//console.log('save toolbar', minicp_enable.attr('checked'), left_links, right_links);
		
		//console.log(left_links, right_links);

		var enabled_value = 0;
		if(minicp_enable.attr('checked')) {
			enabled_value = 1;
		}
		
		$.ajax({
			url: $('#minicp-cp').attr('rel'),
			data: {
				enabled:enabled_value,
				left:left_links,
				right:right_links
			},
			type:'get'
		});


	}
	
});

