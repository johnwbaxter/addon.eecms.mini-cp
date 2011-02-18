<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Mini CP Module
 *
 * @package			Mini CP
 * @subpackage		Modules
 * @category		Modules
 * @author			Benjamin David
 * @link			http://duktee.com/addons/mini-cp/ 
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

/* End of file mcp.minicp.php */
/* Location: ./system/expressionengine/third_party/minicp/mcp.minicp.php */