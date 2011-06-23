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

	/**
	 * Constructor
	 *
	 */
	function Minicp_mcp()
	{
		$this->EE =& get_instance();
		
		$this->EE->cp->set_variable('cp_page_title', "Mini CP");	
	}

	// --------------------------------------------------------------------
	
	/**
	 * Index Page
	 *
	 * @access	public
	 * @return	void
	 */
	function index()
	{
		$this->EE->load->model('minicp_model');

		$this->data['save_toolbar_action'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=minicp'.AMP.'method=save_toolbar';
		
		$toolbar = $this->EE->minicp_model->get_toolbar();
		$this->data['init_enabled'] = $toolbar->enabled;
		$this->data['init_left'] = $toolbar->left_links;
		$this->data['init_right'] = $toolbar->right_links;
		
		$this->_styles();
		$this->_javascripts();
		
		return $this->EE->load->view('index', $this->data, true);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Save toolbar
	 *
	 * @access	public
	 * @return	void
	 */
	function save_toolbar()
	{	
		$this->EE->load->model('minicp_model');
		
		$member_id = $this->EE->session->userdata['member_id'];
		
		if($member_id > 0)
		{
			$enabled = $this->EE->input->get('enabled');

			if($enabled)
			{
				$enabled = 1;
			}
			else
			{
				$enabled = 0;
			}
			
			$left = $this->EE->input->get('left');
			$right = $this->EE->input->get('right');
			
			$left = trim($left, ",");
			$right = trim($right, ",");
			
			$this->EE->minicp_model->set_toolbar($enabled, $left, $right);
		}

		exit();	
	}

	// --------------------------------------------------------------------
	
	/**
	 * Theme URL
	 *
	 * @access	private
	 * @return	void
	 */
	private function _theme_url()
	{
		$url = $this->EE->config->item('theme_folder_url')."third_party/minicp/";
		return $url;
	}		

	// --------------------------------------------------------------------
	
	/**
	 * Add styles to head
	 *
	 * @access	private
	 * @return	void
	 */
	private function _styles()
	{
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$this->_theme_url().'minicp.css?v1.4" />');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Add javascript to head
	 *
	 * @access	private
	 * @return	void
	 */
	private function _javascripts()
	{
		$this->EE->cp->add_to_head('<script type="text/javascript" src="'.$this->_theme_url().'minicp-cp.js?v1.4"></script>');
	}
}


/* END Class */

/* End of file mcp.minicp.php */
/* Location: ./system/expressionengine/third_party/minicp/mcp.minicp.php */