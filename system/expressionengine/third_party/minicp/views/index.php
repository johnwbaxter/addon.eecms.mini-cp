<?php
$items = array(
	0 => '	<li class="li1" rel="0"><a class="a1" href="#">'.$this->lang->line('edit_entry').'</a></li> ',
	1 => '	<li class="li1 more" rel="1"><a class="a1" href="#">'.$this->lang->line('new_entry').'<span></span></a></li>',
	2 => '
			<li rel="2" class="li1 search ui-widget more"> 
				<div class="search-input"> 
					<div class="input"></div> 
				</div>
			</li> 
	',
	3 => '<li class="li1" rel="3"><a class="a1" href="#">'.$this->lang->line('comments').' <strong>1</strong></a></li> ',
	4 => '<li class="li1" rel="4"><a class="a1" href="#">'.$this->lang->line('control_panel').'</a></li> ',
	5 => '<li class="li1 more" rel="5"><a class="a1" href="#">'.$this->session->userdata['screen_name'].' <span></span></a></li>',
);


$items_used = array();
$items_unused = array();
$items_left = "";
$items_right = "";

foreach($items as $k => $v)
{
	if(strpos("$init_left", "$k") !== false)
	{
		$items_left .= $v;
		array_push($items_used, $k);
	}
	elseif(strpos("$init_right", "$k") !== false)
	{
		$items_right .= $v;
		array_push($items_used, $k);
	}
}

foreach($items as $k => $v)
{
	$used = false;
	foreach($items_used as $item_key)
	{
		if($k == $item_key)
		{
			$used = true;
		}
	}
	
	if(!$used)
	{
		array_push($items_unused, $k);
	}
}



?>

<div id="minicp-cp" rel="<?php echo $save_toolbar_action?>">
	<h2 id="minicp-enable"><label><input type="checkbox" <?php
	if($init_enabled == 1)
	{
		echo ' checked="checked"';
	}
	?> /> <?php echo $this->lang->line('enable_minicp')?></label></h2>
	
	
	<div id="minicp-toolbar" class="minicp">
		<div class="minicp-wrap">
			<div class="minicp-pad">
				<ul class="ul1 minicp-left">
					<?php
					echo $items_left;
					?>
				</ul> 
				<ul class="ul1 minicp-right"> 
					<?php
					echo $items_right;
					?>
				</ul>
				
				<div class="minicp-clear"></div>
			</div>
		</div>
		<div class="minicp-clearleft"></div>
	</div> 
	
	
	<div id="minicp-dropzone" class="minicp">
		
		<div class="zone">
			<ul>
				<?php
				foreach($items_unused as $item)
				{
					echo $items[$item];
				}
				?>
			</ul>
			<div class="minicp-clearleft"></div>
		</div>
	</div>
	<div class="minicp-clearleft"></div>

	<h3 class="center"><?php echo $this->lang->line('dragdrop_favorites')?></h3>
</div>
