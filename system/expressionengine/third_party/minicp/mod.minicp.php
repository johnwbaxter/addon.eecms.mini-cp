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

class Minicp {

	var $return_data = '';

	/* constructor */
	
	function Minicp()
	{
	
			
		$this->EE =& get_instance();
		
		$this->EE->load->library('minicp_lib');
		
		$r = "";
		$r .= $this->javascripts();
		$r .= $this->styles();
		
		
		
		$this->return_data = $r;
	}
	
	function search() {	
		
		$terms = $this->EE->input->get('term');
		
		$this->EE->db->like('title', $terms); 
		$this->EE->db->limit(20);
		$query = $this->EE->db->get('channel_titles');
		
		$r = array();
		
		foreach ($query->result() as $row)
		{
			$this->EE->db->where('channel_id', $row->channel_id);
			$channel_title = $this->EE->db->get('channels')->row('channel_title');
			
			
			array_push($r, array(
					'id' => $row->entry_id,
					'channel_id' => $row->channel_id,
					'channel_title' => $channel_title,
					'entry_title' => $row->title,
					'label' => $row->title,
					'value' => $row->title,
					'cp_link' => str_replace('&amp;', '&', $this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$row->channel_id.AMP.'entry_id='.$row->entry_id))
				)
			);
		}
		
		$r = json_encode($r);
		echo $r;

		die();
	
	}
	
	function widget() {
		
		if($this->EE->session->userdata['can_access_cp'] === "y" || $this->EE->session->userdata['group_id'] === "1") {

		} else {
			return "";
		}
		
		
		$entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$site_id = $this->EE->config->item('site_id');
		$base = $this->EE->config->item('cp_url');
		if(strpos($base, ".php") === false) {
			$base .= "index.php";
		}
		$base .= "?S=".$this->EE->session->userdata('session_id');
		
		$logout_action_id = $this->EE->db->where(array('class' => 'Member', 'method' => 'member_logout'))->get('actions')->row('action_id');
		$nb_comments = $this->EE->db->where(array('status' => 'p', 'site_id' => $site_id))->get('comments')->num_rows();
		
		$this->EE->db->where('site_id', $site_id);
		$this->EE->db->order_by('channel_name', "asc");
		$channels = $this->EE->db->get('channels')->result();
		
		
		$this->EE->db->where('entry_id', $entry_id);
		$channel_id = $this->EE->db->get('channel_titles')->row('channel_id');
		
		$site_id = $this->EE->db->where('entry_id', $entry_id)->get('channel_titles')->row('site_id');
		
		$search_action_id = $this->EE->db->where(array('class' => 'Minicp', 'method' => 'search'))->get('actions')->row('action_id'); 
		
		$r = '
		
		<div class="minicp">
			<ul class="left">';
			
				$r .= '<li><a';
				if($entry_id) {
					$r .= ' href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$channel_id.AMP.'entry_id='.$entry_id).'"';
				} else {
					$r .= ' href="#" class="disabled"';
				}
				$r .= '>Edit Page</a></li>';

				$r .= '
				<li class="more">
					<a href="#">New Page<span></span></a>
					<div class="channels">
						<ul>';
							foreach($channels as $c) {
								$r .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$c->channel_id).'">'.$c->channel_title.'</a></li>';
							}
							$r .= '
						</ul>
						<div class="box-arrow"></div>
					</div>
				
				</li>
				
				<li class="search ui-widget more">
					<div class="search-middle">
					<div class="search-left">
					<div class="search-right">
						<input type="text" id="minicp-jquery" rel="/?ACT='.$search_action_id.'" value="" alt="Search entries..." />
					</div>
					</div>
					</div>
					
					<div id="minicp-search-results" rel="'.$base.AMP.'D=cp&C=content_publish&M=entry_form">
						<div class="box-arrow"></div>

					</div>
				</li>
			</ul>
			<ul class="right">
				';
				
				if($this->EE->session->userdata['can_moderate_comments'] == "y") {
					$r .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp&C=addons_modules&M=show_module_cp&module=comment&status=p').'">Comments';
					if($nb_comments > 0) {
						$r .= ' <strong>'.$nb_comments.'</strong>';
					}
					$r .= '</a></li>';
				}
				
				$r .= '
				
				<li><a href="'.$base.AMP.'D=cp&C=homepage">Control Panel</a></li>
				<li class="more">
					<a href="#">'.$this->EE->session->userdata['screen_name'].' <span></span></a>
					<div class="account">
						<ul>
							<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp&C=myaccount').'">My Account</a></li>
							<li><a href="?ACT='.$logout_action_id.'">Logout</a></li>
						</ul>
						<div class="box-arrow"></div>
					
					</div>
				</li>
			</ul>
		</div>
		
		
		';
		
		return $r;
	}
	
	
	function javascripts() {
	
		if($this->EE->session->userdata['can_access_cp'] === "y" || $this->EE->session->userdata['group_id'] === "1") {

		} else {
			return "";
		}
	
	
		if(isset($this->EE->TMPL)) {
			$jquery = $this->EE->TMPL->fetch_param('jquery');
			$jqueryui = $this->EE->TMPL->fetch_param('jqueryui');
			$r = "";
			if($jquery == "yes") {
				$r .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>';
			}
			
			if($jqueryui == "yes") {
				$r .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>';
				
			}
			$r .= '<script type="text/javascript" src="'.$this->theme_url().'minicp.js"></script>';
			return $r;
		}
	}
	
	function styles() {
		if($this->EE->session->userdata['can_access_cp'] === "y" || $this->EE->session->userdata['group_id'] === "1") {

		} else {
			return "";
		}
	

	
		return '<link rel="stylesheet" href="'.$this->theme_url().'minicp.css" type="text/css" />';
	}
	
	
	private function theme_url()
	{
		$url = $this->EE->config->item('theme_folder_url')."third_party/minicp/";
		return $url;
	}

	
}
/* END Class */

/* End of file mod.minicp.php */
/* Location: ./system/expressionengine/third_party/minicp/mod.minicp.php */