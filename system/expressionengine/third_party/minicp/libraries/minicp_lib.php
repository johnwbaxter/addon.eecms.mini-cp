<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Dropee - by Duktee
 *
 * @package		Dropee
 * @version		Version 1.0b1
 * @author		Benjamin David
 * @copyright	Copyright (c) 2011 - Duktee
 * @license		http://duktee.com/addons/dropee/license
 * @link		http://duktee.com/addons/dropee/
 *
 */

class Minicp_lib {

	
	/**
	 * Control Panel Backlink
	 *
	 * @access	private
	 * @return	void
	 */
	function cp_backlink($url = false)
	{
		$CI =& get_instance();

		$base = $CI->config->item('cp_url');
		
		if(strpos($base, ".php") === false)
		{
			$base .= "index.php";
		}
		
		$base .= QUERY_MARKER."S=".$CI->session->userdata('session_id');

		return $base.AMP.$url;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check Access
	 *
	 * @access	private
	 * @return	void
	 */	
	function check_access()
	{
		$CI =& get_instance();

		
		// super admin is always ok
		
		if($CI->session->userdata['group_id'] == 1)
		{
			return true;
		}


		// check if member module is enabled
		
		$CI->db->where('module_name', "Member");
		$CI->db->from('modules');
		
		if($CI->db->count_all_results() != 1)
		{
			return false;
		}
		
		
		// get mini cp module id
		
		$CI->db->where('module_name', "Minicp");
		
		$query = $CI->db->get('modules');
		
		if($query->num_rows() != 1)
		{
			return false;
		}
		else
		{
		   $minicp_mod = $query->row(); 
		}
		
		$query->free_result();
		

		// check that the the user has access to the control panel 
		
		if($CI->session->userdata['can_access_cp'] === "y" && isset($minicp_mod))
		{
		
			// check that the member group this user belongs to has access to this module
			
			$CI->db->where('group_id', $CI->session->userdata['group_id']);
			$CI->db->where('module_id', $minicp_mod->module_id);
			$CI->db->from('module_member_groups');
			
			if($CI->db->count_all_results() != 1) {
				return false;
			}

		}
		else
		{
			return false;
		}
		
		return true;
	}
}