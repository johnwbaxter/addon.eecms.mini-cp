<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mini CP - by Duktee
 *
 * @package		Mini CP
 * @version		Version 2.5
 * @author		Benjamin David
 * @copyright	Copyright (c) 2011 - Duktee
 * @license		http://duktee.com/addons/minicp/license
 * @link		http://duktee.com/addons/minicp/
 *
 */

class Minicp {

	var $return_data = '';
	var $allowed_channels = false;

	/**
	 * Constructor
	 *
	 */
	function Minicp()
	{
			
		$this->EE =& get_instance();
		
		$this->EE->load->library('minicp_lib');
		
		
		// Mini CP Head
		
		$r = "";
		
		
		// load javascripts
		
		$r .= $this->javascripts();
		
		
		// load styles
		
		$r .= $this->styles();

		$this->return_data = $r;
		
		
		// load allowed channels
		
		if(!$this->allowed_channels)
		{
			$this->allowed_channels = $this->EE->functions->fetch_assigned_channels();
		}
		
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Search
	 *
	 * @access	public
	 * @return	void
	 */
	function search()
	{	
		
		$terms = $this->EE->input->get('term');
		
		if(count($this->allowed_channels) == 0)
		{
			die();
		}
		
		$this->EE->db->like('title', $terms); 
		$this->EE->db->where_in('channel_id', $this->allowed_channels);
		$this->EE->db->limit(8);
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
	
	// --------------------------------------------------------------------	
	
	/**
	 * Widget
	 *
	 * @access	public
	 * @return	void
	 */
	
	function widget()
	{
	
		$this->EE->lang->loadfile('minicp');

		// are we allowed to display Mini CP ?
		
		if(!$this->EE->minicp_lib->check_access())
		{
			return "";
		}
		
		
		// retrieve site_id		
		
		$site_id = $this->EE->config->item('site_id');

		// site switch
		// we switch site session here since ExpressionEngine disabled the base64 redirect method
		// (for more infos, see system/expressionengine/controllers/cp/sites.php, _site_switch method)
		
		if($this->EE->config->item('multiple_sites_enabled') == "y")
		{
			$this->EE->config->site_prefs('', $site_id);
			$this->EE->functions->set_cookie('cp_last_site_id', $site_id, 0);
		}
		
		// fetch parameters
		
		$entry_id = $this->EE->TMPL->fetch_param('entry_id');
		
		
		// define base url
		
		$base = $this->EE->config->item('cp_url');

		if(strpos($base, ".php") === false)
		{
			$base .= "index.php";
		}
		
		$base .= QUERY_MARKER."S=".$this->EE->session->userdata('session_id');
		
		
		// retrieve action ids
		
		$site_index = $this->EE->functions->fetch_site_index(0, 1);
		$logout_action_id = $this->EE->db->where(array('class' => 'Member', 'method' => 'member_logout'))->get('actions')->row('action_id');
		$search_action_id = $this->EE->db->where(array('class' => 'Minicp', 'method' => 'search'))->get('actions')->row('action_id');
		$search_action_url = $site_index.QUERY_MARKER."ACT=".$search_action_id;
		$logout_action_url = $site_index.QUERY_MARKER."ACT=".$logout_action_id;
			
		
		
		/****************************
		* Defined toolbar quicklinks
		*****************************/
		
		$quick_links = array();
		
		
		// 0 : Edit Entry
		
		$quick_links[0] = '';
		if($entry_id)
		{
		
			// retrieve entry
			
			$this->EE->db->where('entry_id', $entry_id);
			$this->EE->db->limit(1);
			$entry = $this->EE->db->get('channel_titles');
			
			if ($entry->num_rows() > 0)
			{
				// is member allowed to edit this entry

				if($this->EE->session->userdata['can_access_edit'] == 'y' && ($entry->row('author_id') == $this->EE->session->userdata['member_id'] || $this->EE->session->userdata['can_edit_other_entries'] == 'y'))
				{
				
					// check that the entry channels is one that the member can use
					
					foreach($this->allowed_channels as $channel_id)
					{
						if($channel_id == $entry->row('channel_id'))
						{
							$quick_links[0] .= '<li class="li1"><a class="a1" href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$channel_id.AMP.'entry_id='.$entry_id).'">'.$this->EE->lang->line('edit_entry').'</a></li>';
						}
					}
					
				}
			   
			
			}
			
			
		}
		
		
		// 2 : New Entry
		
		$quick_links[1] = '';
		

		// get channel titles
		
		$allowed_channel_titles = $this->allowed_channel_titles();
		
		if($this->EE->session->userdata['group_id'] == 1 || ($this->EE->session->userdata['can_access_content'] == 'y' && $this->EE->session->userdata['can_access_publish'] == 'y'))
		{
			if(count($allowed_channel_titles) > 0)
			{
				$quick_links[1] .= '
					<li class="li1 more">
						<a class="a1" href="#">'.$this->EE->lang->line('new_entry').'<span></span></a><ul class="ul2">';
								foreach($allowed_channel_titles as $channel_id => $channel_title) {
									$quick_links[1] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$channel_id).'">'.$channel_title.'</a></li>';
								}
								$quick_links[1] .= '
							</ul>						
					</li>';
			}
		}
		
		
		// 2 : Search
		
		$quick_links[2] = '';
		
		if($this->EE->session->userdata['can_access_edit'] == 'y')
		{
			$quick_links[2] .= '
				<li class="li1 search ui-widget more">
					<div class="search-input">
						<input type="text" class="input" name="minicpsearch" id="minicp-jquery" rel="'.$search_action_url.'" value="" alt="'.$this->EE->lang->line('search_entries').'" />
					</div>
					
					<div class="box-arrow">
						<div id="minicp-search-results" rel="'.$base.AMP.'D=cp&C=content_publish&M=entry_form">
							
	
						</div>
					</div>
				</li>';
		}

				
		// 3 : Comments
		
		if($this->EE->session->userdata['group_id'] == 1 || $this->EE->session->userdata['can_moderate_comments'] == "y")
		{
		
			// count pending comments if comment module installed
			
			$nb_comments = 0;
			
			if ($this->EE->db->table_exists('comments'))
			{
				$nb_comments = $this->EE->db->where(array('status' => 'p', 'site_id' => $site_id))->get('comments')->num_rows();
			}
		
			$quick_links[3] = '<li class="li1"><a class="a1" href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=comment'.AMP.'status=p').'">'.$this->EE->lang->line('comments');
			
			if($nb_comments > 0)
			{
				$quick_links[3] .= ' <strong>'.$nb_comments.'</strong>';
			}
			
			$quick_links[3] .= '</a></li>';
		}


		// 4 : Control Panel

		$quick_links[4] = '
			<li class="li1 more">
				<a class="a1" href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=homepage').'">'.$this->EE->lang->line('control_panel').'<span></span></a>
				<ul class="ul2">
					<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=homepage').'">'.$this->EE->lang->line('cp_home').'</a></li>
					';
					
					
					if($this->EE->session->userdata['group_id'] == 1 || ($this->EE->session->userdata['can_access_admin'] == 'y' && $this->EE->session->userdata['can_access_content_prefs'] == 'y'))
					{
						
						// channels
						
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=admin_content'.AMP.'M=channel_management').'">'.$this->EE->lang->line('channels').'</a></li>';
						
						
						// categories
						
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=admin_content'.AMP.'M=category_management').'">'.$this->EE->lang->line('categories').'</a></li>';
						
						
						// custom fields
						
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=admin_content'.AMP.'M=field_group_management').'">'.$this->EE->lang->line('custom_fields').'</a></li>';
					}
					
					
					// Members
					
					if($this->EE->session->userdata['group_id'] == 1 || ($this->EE->session->userdata['can_access_admin'] == 'y' && $this->EE->session->userdata['can_access_members'] == 'y'))
					{
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=members').'">'.$this->EE->lang->line('members').'</a></li>';
					}
					
					
					// System Preferences
					
					if($this->EE->session->userdata['group_id'] == 1 || ($this->EE->session->userdata['can_access_admin'] == 'y' && $this->EE->session->userdata['can_access_sys_prefs'] == 'y'))
					{
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=admin_system'.AMP.'M=general_configuration').'">'.$this->EE->lang->line('general_config').'</a></li>';
					}
					
					
					// Templates
					
					if($this->EE->session->userdata['group_id'] == 1 ||$this->EE->session->userdata['can_access_design'] == 'y') {
						$quick_links[4] .= '<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=design'.AMP.'M=manager').'">'.$this->EE->lang->line('templates').'</a></li>';
					}
					
	$quick_links[4] .= '				
				</ul>
			</li>';
		
		
		// 5 : Member Account
		
		$quick_links[5] = '
				<li class="li1 more">
					<a class="a1" href="#">'.$this->EE->session->userdata['screen_name'].' <span></span></a>
						<ul class="ul2">
							<li><a href="'.$this->EE->minicp_lib->cp_backlink('D=cp'.AMP.'C=myaccount').'">'.$this->EE->lang->line('my_account').'</a></li>
							<li><a href="'.$logout_action_url.'">'.$this->EE->lang->line('logout').'</a></li>
						</ul>
				</li>';

		
		// Toolbar
		
		$this->EE->load->model('minicp_model');
		$toolbar = $this->EE->minicp_model->get_toolbar();
		
		$toolbar_left 	= explode(",", $toolbar->left_links);
		$toolbar_right 	= explode(",", $toolbar->right_links);
		
		if(!$toolbar->enabled)
		{
			return "";
		}
		
		
		// widget wrapper
		
		$r = '
		
		<div id="minicp-widget" class="minicp">
			<div class="minicp-wrap">
				<div id="minicp-widget-pad">
					<ul class="ul1 minicp-left">';
					
					
					// retrieve quicklinks to put on the left
					
					foreach($toolbar_left as $v)
					{
						if(isset($quick_links[$v]))
						{
							$r .= $quick_links[$v];
						}
					}
		
				$r.='</ul>';
				$r.='<ul class="ul1 minicp-right">';
				
				
					// retrieve quicklinks to put on the right
					
					foreach($toolbar_right as $v)
					{
						if(isset($quick_links[$v]))
						{
							$r .= $quick_links[$v];
						}
					}
				$r.='</ul>
					<div class="minicp-clear"></div>
				
				</div>
			</div>
		</div>
		';
		
		return $r;
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Load javascripts
	 *
	 * @access	public
	 * @return	void
	 */
	
	function javascripts()
	{
		$this->EE->load->model('minicp_model');
		
		$toolbar = $this->EE->minicp_model->get_toolbar();
	
		if(!$this->EE->minicp_lib->check_access() || !$toolbar->enabled)
		{
			return "";
		}
	
		if(isset($this->EE->TMPL))
		{
			$jquery = $this->EE->TMPL->fetch_param('jquery');
			
			$jqueryui = $this->EE->TMPL->fetch_param('jqueryui');
			
			$r = "";
			
			if($jquery == "yes")
			{
				$r .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>';
			}
			
			if($jqueryui == "yes")
			{
				$r .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>';	
			}
			
			$r .= '<script type="text/javascript" src="'.$this->theme_url().'minicp.js?v1.4"></script>';
			
			return $r;
		}
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Load styles
	 *
	 * @access	public
	 * @return	void
	 */
	function styles()
	{
		$this->EE->load->model('minicp_model');
		$toolbar = $this->EE->minicp_model->get_toolbar();
		
		if(!$this->EE->minicp_lib->check_access() || !$toolbar->enabled)
		{
			return "";
		}

  		$r = "";
  		$r .= '<link rel="stylesheet" href="'.$this->theme_url().'minicp.css?v1.4" type="text/css" />';
  		$r .= '<!--[if gte IE 7]><link rel="stylesheet" type="text/css" media="screen" href="'.$this->theme_url().'minicp-ie.css?v1.4"  /><![endif]-->';
  		
		return $r;
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Return theme URL
	 *
	 * @access	public
	 * @return	void
	 */
	private function theme_url()
	{
		$url = $this->EE->config->item('theme_folder_url')."third_party/minicp/";
		return $url;
	}
	
	// --------------------------------------------------------------------	
	
	/**
	 * Get channel titles
	 *
	 * @access	public
	 * @return	void
	 */
	private function allowed_channel_titles()
	{
		$channel_titles = array();
	 
		$this->EE->db->where_in('channel_id', $this->allowed_channels);
		$this->EE->db->order_by('channel_title', 'asc');
		$query = $this->EE->db->get('channels');
		
		
		// build assigned channels array
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$channel_titles[$row['channel_id']] = $row['channel_title'];
			}
		}
		
		return $channel_titles;
	}
}
/* END Class */

/* End of file mod.minicp.php */
/* Location: ./system/expressionengine/third_party/minicp/mod.minicp.php */