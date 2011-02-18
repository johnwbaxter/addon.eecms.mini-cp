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
 
class Minicp_upd {

	var $version = '1.0b1';
	
	/* constructor */
	
	function Minicp_upd()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Module Installer
	 *
	 * @access	public
	 * @return	bool
	 */
	 
	function install()
	{
		$this->EE->load->dbforge();

		$data = array(
			'module_name' => 'Minicp',
			'module_version' => $this->version,
			'has_cp_backend' => 'n',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);
		
		/* Action : Ajax Search  */
		
		$data = array(
			'class'		=> 'Minicp' ,
			'method'	=> 'search'
		);
		
		$this->EE->db->insert('actions', $data);

		return TRUE;

	}
	
	
	// --------------------------------------------------------------------

	/**
	 * Module Uninstaller
	 *
	 * @access	public
	 * @return	bool
	 */
	 
	function uninstall()
	{
		$this->EE->load->dbforge();

		$this->EE->db->select('module_id');
		$query = $this->EE->db->get_where('modules', array('module_name' => 'Minicp'));

		$this->EE->db->where('module_id', $query->row('module_id'));
		$this->EE->db->delete('module_member_groups');

		$this->EE->db->where('module_name', 'Minicp');
		$this->EE->db->delete('modules');
		

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */	
	
	function update($current='')
	{
		return TRUE;
	}
	
}
/* END Class */

/* End of file upd.videoplayer.php */
/* Location: ./system/expressionengine/third_party/videoplayer/upd.videoplayer.php */