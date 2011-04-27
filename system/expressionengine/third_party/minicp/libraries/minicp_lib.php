<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Minicp_lib {

	function cp_backlink($url = false)
	{
		$CI =& get_instance();

		$base = $CI->config->item('cp_url');
		if(strpos($base, ".php") === false) {
			$base .= "index.php";
		}
		$base .= QUERY_MARKER."S=".$CI->session->userdata('session_id');

		/* Multi Site Enabled, return URL straight away */
		if($CI->config->item('multiple_sites_enabled') != "y") {
			return $base.AMP.$url;
		}
		
		
		/* Multi Site Disabled, rebuild URL with base64 sauce */
		if (!$url)
		{
			$go_to_c = (count($_POST) > 0);
			$page = '';

			foreach($_GET as $key => $val)
			{
				if ($key == 'S' OR $key == 'D' OR ($go_to_c && $key != 'C'))
				{
					continue;
				}

				$page .= $key.'='.$val.AMP;
			}

			if (strlen($page) > 4 && substr($page, -5) == AMP)
			{
				$page = substr($page, 0, -5);
			}
			
			$url = $page;
		}
		
		if ($url)
		{
			$url = implode('|', explode(AMP, $url));
			$url = AMP."page=".strtr(base64_encode($url), '+=', '-_');
		}
		
		
		return $base.AMP."D=cp".AMP."C=sites".AMP."site_id=".$CI->config->item('site_id').$url;
	}
	
	function check_access() {
		$CI =& get_instance();

		
		/* super admin is always ok */
		if($CI->session->userdata['group_id'] == 1) {
			return true;
		}


		/* check if member module is enabled */
		$CI->db->where('module_name', "Member");
		$CI->db->from('modules');
		
		if($CI->db->count_all_results() != 1) {
			return false;
		}
		
		/* get mini cp module id */
		$CI->db->where('module_name', "Minicp");
		$query = $CI->db->get('modules');
		
		if ($query->num_rows() != 1)
		{
			return false;
		} else {
		   $minicp_mod = $query->row(); 
		}
		
		$query->free_result();
		

		/* check that the the user has access to the control panel */
		if($CI->session->userdata['can_access_cp'] === "y" && isset($minicp_mod)) {
		
			/* check that the member group this user belongs to has access to this module */
			$CI->db->where('group_id', $CI->session->userdata['group_id']);
			$CI->db->where('module_id', $minicp_mod->module_id);
			$CI->db->from('module_member_groups');
			
			if($CI->db->count_all_results() != 1) {
				return false;
			}

		} else {
			return false;
		}
		
		return true;
	}
}