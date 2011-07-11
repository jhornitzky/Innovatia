<? if (!isset($_REQUEST['searchTerms']) || empty($searchTerms)) { ?>
<h2 style="color:#AAA">find, compare and share ideas</h2>
<? } else { ?>
<h2 style="color:#AAA">found result(s) for search terms</h2> 
<? } ?>

<form id="searchForm" onsubmit="showSearch(); return false;" style="font-size: 1.5em; clear: both;">
	<div class="dijitTextBox" style="border: 1px solid #B3B3B3; position: relative; float: left; clear: right; margin: 0; padding: 0; background-color:#FFF; width:auto">
		<input id="searchTerms" type="text" name="searchTerms" value="<?= $searchTerms ?>" style="margin: 0; padding: 0; font-size: 1.2em; border:none; width:auto !important;" /> 
		<img src="<?= $uiRoot."style/glass.png"?>" onclick="showSearch()" style="width: 30px; height: 24px; margin: 2px; cursor: pointer;" />
		<input id="searchBtn" type="submit" value="Search" style="display: none;" />
		<div id="searchHider" style="padding: 0.25em; background-color: #EEE; height:1.15em; position:absolute; right: -75px; top:-1px; width:60px">
			<a href="javascript:logAction()" onclick="toggleSearchOptions()" style="font-size: 0.6em; margin: 0;">more &darr;</a>
		</div>
	</div>
	<div id="searchOptions" style="display: none; clear: left; font-size: 0.7em; padding: 0.25em; border: 1px solid #DDD; background-color: #EEE; float: left">
		<div style="margin-bottom:1em;"><b>more filters</b> <a href="javascript:logAction()" onclick="toggleSearchOptions()" style="margin: 0; margin-bottom: 0.5em; float:right">less &uarr;</a></div> 
		<div>Date from <input type="text" name="dateFrom" dojoType="dijit.form.DateTextBox" value="<?= $dateFrom ?>" /> to <input type="text" name="dateTo" dojoType="dijit.form.DateTextBox" value="<?= $dateTo ?>" /></div>
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

<? if (isset($_REQUEST['searchTerms'])) { 
	$searchTerms = urlencode($_REQUEST['searchTerms']);?>
	<div class="threeColumnContainer" style="clear:both; padding-top:2em;">
		<h2 style="color:#AAA">results on other networks</h2>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('http://www.iinspireus.com/search/index?q=<?= $searchTerms ?>')">
				<img src="http://www.iinspireus.com/img/favicon.ico"/>
				<span>iinspireus</span>
			</div>
			<span class="tiny">ideas and innovators</span>
		</div>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('http://www.creativitypool.com/creativitypool.php?searchtext=<?= $searchTerms ?>&find=any')">
				<img src="http://creativitypool.com/favicon.ico"/>
				<span>creativity pool</span>
			</div>
			<span class="tiny">open ideas</span>
		</div>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('https://www.innocentive.com/ar/challenge/browse?search=<?= $searchTerms ?>')">
				<img src="http://innocentive.com/favicon.ico"/>
				<span>innocentive</span>
			</div>
			<span class="tiny">ideas and challenges</span>
		</div>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('https://www.atizo.com/search/?q=<?= $searchTerms ?>')">
				<img src="http://www.atizo.com/site_media/images/favicon.ico"/>
				<span>atizo</span>
			</div>
			<span class="tiny">ideas and challenges</span>
		</div>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('http://openideas.org/search.php?search=<?= $searchTerms ?>')">
				<img src="http://openideas.org/favicon.ico"/>
				<span>open ideas</span>
			</div>
			<span class="tiny">more ideas</span>
		</div>
		<div class="sixCol">
			<div class="linkHolder" style="cursor:pointer" onclick="goTo('http://www.linkedin.com/search/fpsearch?type=people&keywords=<?= $searchTerms ?>')">
				<img src="http://linkedin.com/favicon.ico"/>
				<span>linkedIn</span>
			</div>
			<span class="tiny">professionals</span>
		</div>
	</div>
<? } ?>