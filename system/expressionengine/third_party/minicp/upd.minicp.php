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
 
class Minicp_upd {

	var $version = '1.4';
	
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
		/* Load DB Forge */
		$this->EE->load->dbforge();
		
		
		/* Insert Module */
		
		$data = array(
			'module_name' => 'Minicp',
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);
		
		
		
		/* Mini CP Table : Toolbars */
		
		$fields = array(
						'id'			=> array('type' 		 => 'int',
													'constraint'	 => '10',
													'unsigned'		 => TRUE,
													'auto_increment' => TRUE),
						'user_id'			=> array('type' 		 => 'int',
													'constraint'	 => '10'),
						'enabled'		=> array('type' => 'int', 'constraint' => '1'),
						'left_links'		=> array('type' => 'text'),
						'right_links'		=> array('type' => 'text'),
						);

		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('minicp_toolbars');
		
		
		
		/* Mini CP Table : Toolbars */
		
		$fields = array(
			'key'	=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'value'	=> array('type'	=> 'text')
		);
	
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('key', TRUE);
	
		$this->EE->dbforge->create_table('minicp_preferences');
		
		
		
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
		
		/* drop minicp tables */
		$this->EE->dbforge->drop_table('minicp_toolbars');
		$this->EE->dbforge->drop_table('minicp_preferences');
		
		/* uninstall module */
			
		$this->EE->db->where('class', 'Minicp');
		$this->EE->db->delete('actions');

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

		if ($current == $this->version)
		{
			return FALSE;
		}

		
		if ($current < '1.4')
		{
			$data = array(
				'module_name' => 'Minicp',
				'has_cp_backend' => 'y',
			);
			$this->EE->db->where('module_name', "Minicp");
			$this->EE->db->update('modules', $data);
		}

		
		return TRUE;
	}
	
}


/* END Class */

/* End of file upd.minicp.php */
/* Location: ./system/expressionengine/third_party/minicp/upd.minicp.php */