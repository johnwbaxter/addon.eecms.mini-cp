<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Video Player Module
 *
 * @package			Video Player
 * @subpackage		Modules
 * @category		Modules
 * @author			Benjamin David
 * @link			http://dukt.fr/en/addons/video-player/ 
 */

class Minicp_mcp {

	/* constructor*/
	
	function Minicp_mcp()
	{
		/* Make a local reference to the ExpressionEngine super object */
		$this->EE =& get_instance();
	}
	
	
	/* module home */
	
	function index()
	{
		$this->EE->cp->set_variable('cp_page_title', "Minicp");		                              
		return "It lives on".BASE;
	}
	
}


/* END Class */

/* End of file mcp.videoplayer.php */
/* Location: ./system/expressionengine/third_party/videoplayer/mcp.videoplayer.php */