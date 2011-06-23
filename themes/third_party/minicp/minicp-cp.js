$(document).ready(function() {

	/**
	 * Drag & Drop
	 *
	 * @access	public
	 * @return	bool
	 */	
	$("#minicp-cp ul").disableSelection();
	
	$("#minicp-cp ul").sortable({
		connectWith: "ul",
		dropOnEmpty: true,
		update:function(e,ui) {
			minicp_save_toolbar();
		}
	});

	// --------------------------------------------------------------------

	/**
	 * Init Toolbar
	 *
	 * @access	public
	 * @return	bool
	 */

	var minicp_enable 	= $($('#minicp-enable').find('input')[0]);
	var minicp_left 	= $($('#minicp-cp .minicp-left')[0]);
	var minicp_right 	= $($('#minicp-cp .minicp-right')[0]);
	
	minicp_enable.change(function(){
		minicp_save_toolbar();
	});
	
	$("#minicp-cp a").click(function() {
		return false;
	});
	
	// --------------------------------------------------------------------

	/**
	 * Save toolbar
	 *
	 * @access	public
	 * @return	bool
	 */	
	function minicp_save_toolbar()
	{
		var left_links 	= "";
		var right_links = "";

		minicp_left.find('li').each(function(i,el) {
			left_links += $(el).attr('rel')+",";
		});
		
		
		minicp_right.find('li').each(function(i,el) {
			right_links += $(el).attr('rel')+",";
		});
		
		var enabled_value = 0;
		if(minicp_enable.attr('checked')) {
			enabled_value = 1;
		}
		
		$.ajax({
			url: $('#minicp-cp').attr('rel'),
			data: {
				enabled	:	enabled_value,
				left	:	left_links,
				right	:	right_links
			},
			type:'get'
		});
	}

	// --------------------------------------------------------------------

	
});

