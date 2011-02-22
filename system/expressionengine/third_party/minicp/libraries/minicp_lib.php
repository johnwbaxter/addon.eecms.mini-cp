<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Minicp_lib {

	function cp_backlink($url = false)
	{
		$CI =& get_instance();

		$base = $CI->config->item('cp_url');
		$base .= "?S=".$CI->session->userdata('session_id');

		if($CI->config->item('multiple_sites_enabled') != "y") {
			return $base.AMP.$url;
		}
		
		if ( ! $url)
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
}