<div style="width: 100%;">
	<div class="fixed-left">
		<div class="tiny">latest announcements...</div>
		<div class="treeMenu" style="margin-bottom:1em;">
			<div>
			<?if ($announces && dbNumRows($announces)) {
				while($announce = dbFetchObject($announces)) {?>
					<div class="itemHolder" style="font-size: 0.85em">
					<?= $announce->text ?>
						<br/> 
						<span><?= getDisplayUsername($announce->userId) . ' ' . $announce->date ?></span>
					</div>
				<?}
			}?>
			</div>
		</div>
		<div class="tiny">want to share more?</div>
		<? renderTemplate('shareBtns'); ?>
	</div>
	<div class="fixed-right">
		<div style="height:4.25em; border-bottom:1px solid #DDD">
			<div class="lefter lefterImage">
				<img src="<?=$uiRoot?>/style/public.png" /> 
			</div>
			<div class="lefter">
				<h1>public space</h1>
			</div>
		</div>
		<ul class="submenu">
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showPublicIdeate(this)">Ideate</a>
			</li>
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showPublicCompare(this)">Compare</a>
			</li>
			<li class="bluebox"><a href="javascript:logAction()"
				onclick="showPublicSelect(this)">Select</a>
			</li>
		</ul>
		<div class="publicInfo" style="margin-top: 2em"></div>
	</div>
</div>