<div class="minicp">
	<ul class="left">
		<?
		$r .= '<li><a';
		if($entry_id) {
			$r .= ' href="'.$base.AMP.'&D=cp&C=content_publish&M=entry_form&channel_id='.$channel_id.'&entry_id='.$entry_id.'"';
		} else {
			$r .= ' href="#" class="disabled"';
		}
		$r .= '>Edit Page</a></li>';

		?>
		<li class="more">
			<a href="#">New Page<span></span></a>
			<div class="channels">
				<ul>
					<?
					foreach($channels as $c) {
						$r .= '<li><a href="'.$base.AMP.'D=cp&C=content_publish&M=entry_form&channel_id='.$c->channel_id.'">'.$c->channel_title.'</a></li>';
					}
					?>
				</ul>
				<div class="box-arrow"></div>
			</div>
		
		</li>
		
		<li class="search ui-widget more">
			<div class="search-middle">
				<div class="search-left">
					<div class="search-right">
						<input type="text" id="minicp-jquery" value="" alt="Search entries..." />
					</div>
				</div>
			</div>
			
			<div id="minicp-search-results">
				<div class="box-arrow"></div>

			</div>
		</li>
	</ul>
	<ul class="right">
		<li><a href="<?=$base?>&D=cp&C=addons_modules&M=show_module_cp&module=comment&status=p">Comments
		<?
		if($nb_comments > 0) {
			$r .= ' <strong>'.$nb_comments.'</strong>';
		}
		?></a></li>
		<li><a href="<?=$base?>&D=cp&C=homepage">Control Panel</a></li>
		<li class="more">
			<a href="#">Benjamin David <span></span></a>
			<div class="account">
				<ul>
					<li><a href="<?=$base?>&D=cp&C=myaccount">My Account</a></li>
					<li><a href="?ACT='.$logout_action_id.'">Logout</a></li>
				</ul>
				<div class="box-arrow"></div>
			
			</div>
		</li>
	</ul>
</div>