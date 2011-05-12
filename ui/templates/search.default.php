<form id="searchForm" onsubmit="showSearch(); return false;"
	style="font-size: 1.5em; clear: both;">
	<div class="dijitTextBox" style="border:1px solid 1px solid #B3B3B3; position: relative; float: left; clear: right; margin:0; padding:0;">
		<input id="searchTerms" type="text" name="searchTerms"
			value="<?= $searchTerms ?>" placeholder=" . . . "
			style="font-size: 1.2em; width:80%; border: none" />
		<img src="<?= $uiRoot."style/glass.png"?>" onclick="showSearch()"
					style="width: 30px; height: 24px; margin: 2px; cursor: pointer; right:2px; position:absolute; top:2px;" />
		<input id="searchBtn" type="submit" value="Search"
			style="display: none;" />
	</div>
	<div id="searchHider"
		style="clear: left; padding: 0.25em; border: 1px solid #DDD; background-color: #EEE; float: left;">
		<a href="javascript:logAction()" onclick="toggleSearchOptions()"
			style="font-size: 0.6em; margin: 0;">More &gt;&gt;</a>
	</div>
	<div id="searchOptions"
		style="display: none; clear: left; font-size: 0.7em; padding: 0.25em; border: 1px solid #DDD; background-color: #EEE; float: left">
		<a href="javascript:logAction()" onclick="toggleSearchOptions()"
			style="margin: 0; margin-bottom: 0.5em;">&lt;&lt;Less</a><br /> Date
		from <input type="text" name="dateFrom"
			dojoType="dijit.form.DateTextBox" value="<?= $dateFrom ?>" /> Date to
		<input type="text" name="dateTo" dojoType="dijit.form.DateTextBox"
			value="<?= $dateTo ?>" />
	</div>
</form>

<div id="searchui" class="threeColumnContainer" style="clear: both">
	<div class="threecol">
		<div class="searchResults">
		<?renderSearchIdeas($userId, $limit);?>
		</div>
	</div>

	<div class="threecol">
		<div class="searchResults">
		<?renderSearchProfiles($userId, $limit);?>
		</div>
	</div>

	<div class="threecol">
		<div class="searchResults">
			<?renderSearchGroups($userId, $limit);?>
		</div>
	</div>
</div>
