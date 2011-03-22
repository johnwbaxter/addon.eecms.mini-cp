<!--

<?=$init_enabled?><br />
<?=$init_left?><br />
<?=$init_right?><br />
-->


<?
$items = array(
	0 => '	<li rel="0"><a href="#">Edit Entry</a></li> ',
	1 => '	<li rel="1" class="more"><a href="#">New Entry<span></span></a></li>',
	2 => '
			<li rel="2" class="search ui-widget more"> 
				<div class="search-middle"> 
				<div class="search-left"> 
				<div class="search-right"> 
					<div class="input"></div> 
				</div> 
				</div> 
				</div> 
				
				<div id="minicp-search-results"> 
					<div class="box-arrow"></div> 
	
				</div> 
			</li> 
	',
	3 => '<li rel="3"><a href="#">Comments <strong>1</strong></a></li> ',
	4 => '<li rel="4"><a href="#">Control Panel</a></li> ',
	5 => '<li rel="5" class="more"><a href="#">'.$this->session->userdata['screen_name'].' <span></span></a></li>',
);


$items_used = array();
$items_unused = array();
$items_left = "";
$items_right = "";

foreach($items as $k => $v) {
	if(strpos("$init_left", "$k") !== false) {
		$items_left .= $v;
		array_push($items_used, $k);
		
	} elseif(strpos("$init_right", "$k") !== false) {
		$items_right .= $v;
		array_push($items_used, $k);
	
	}
}

foreach($items as $k => $v) {
	$used = false;
	foreach($items_used as $item_key) {
		if($k == $item_key) {
			$used = true;
		}
	}
	
	if(!$used) {
		array_push($items_unused, $k);
	}
}



?>

<div id="minicp-cp" rel="<?=$save_toolbar_action?>">
	<h2 id="minicp-enable"><label><input type="checkbox" <?
	if($init_enabled == 1) {
		echo ' checked="checked"';
	}
	?> /> Enable your Mini CP Toolbar</label></h2>
	
	<div id="minicp-toolbar" class="minicp"> 
		
		<ul class="minicp-left">
			<?
			echo $items_left;
			?>
		</ul> 
		<ul class="minicp-right"> 
			<?
			echo $items_right;
			?>
		</ul>
		<div class="clear"></div>
	</div> 

	
	<div class="minicp-clearleft"></div>
	
	
	<div id="minicp-nativelinks" class="minicp">
		
		<div class="minicp-dropzone">
			<ul>
				<?
				foreach($items_unused as $item) {
					echo $items[$item];
				}
				?>
			</ul>
			<div class="minicp-clearleft"></div>
		</div>
	</div>	
	
	<div class="minicp-clearleft"></div>

	<h3 class="center">Drag &amp; Drop your favorite items to your Mini CP Toolbar</h3>
</div>

<!--

<li><input type="checkbox" /> <strong>Navee</strong>		Navee compatibility will make navigations appear in Mini CP</li>
<li><input type="checkbox" /> <strong>Brilliant Retail</strong>		The Quick Tabs you defined will appear in Mini CP</li>
<li><input type="checkbox" /> <strong>Quick Tabs</strong>		The Quick Tabs you defined will appear in Mini CP</li>

-->





<!--

<ul>
<?
foreach($quick_tabs as $qt) {
	echo '<li>'.$qt['title'].'</li>';
}
?>
</ul>
-->