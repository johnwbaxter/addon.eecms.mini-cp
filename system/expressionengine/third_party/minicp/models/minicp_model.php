<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Minicp_model extends CI_Model {
	
	function __construct()
	{
		parent::__construct();

	}
	
	
	/* Toolbars */
	
	function set_toolbar($enabled = false, $left = "", $right="") {
		
		$user_id = $this->session->userdata['member_id'] - 1 +1;
		
		if($user_id && $user_id > 0) {
			$this->db->where('user_id', $user_id);
			$this->db->delete('minicp_toolbars');
			
			$data = array(
				"user_id" => $user_id,
				"enabled" => $enabled,
				"left_links" => $left,
				"right_links" => $right
			);
			
			return $this->db->insert('minicp_toolbars', $data);
		}
		return false;

	
	}
	
	function get_toolbar() {
		$user_id = $this->session->userdata['member_id'] - 1 +1;

		$this->db->where('user_id', $user_id);
		$query = $this->db->get('minicp_toolbars');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
		   return $row;
		} elseif($user_id > 0) {
			/* toolbar doesn't exist yet, let's create it from default configuration */
			$this->set_toolbar("1", "0", "1");
			return $this->get_toolbar();
			
		}
		
		return false;
	
	}

	/* Preferences */
	
	function set_preference($key, $value) {
		$this->db->where('pref_key', $key);
		$this->db->delete('minicp_preferences');
		$query = $this->db->query("INSERT INTO `".$this->db->dbprefix('minicp_preferences')."` (`key`, `value`) VALUES ('".$key."', '".$value."');");
		return $query;
	}
	
	function get_preference($key) {
		$this->db->where('pref_key', $key);
		$query = $this->db->get('minicp_preferences');
		
		if ($query->num_rows() > 0)
		{
		   $row = $query->row(); 
		
		   return $row->pref_value;

		}
		
		return false;
	}
	
}