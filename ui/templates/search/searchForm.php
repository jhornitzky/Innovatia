<form id="searchForm" onsubmit="doSearch('<?= $_REQUEST['']; ?>'); return false;" style="font-size: 1.5em; clear: both;">
<div style="border: 1px solid #444444; position: relative; float: left; clear:right">
<table cellpadding="0" cellspacing="0">
<tr>
<td><input id="searchTerms" type="text"  name="searchTerms"
	value="<?= $searchTerms ?>"; placeholder=" . . . " style="font-size:1.2em; width:15.5em; border: none" /></td>
<td><img src="<?= $serverRoot."ui/style/glass.png"?>" onclick="showSearch()" style="width:30px; height:24px; margin:2px;cursor:pointer"/>
</td>
</tr>
</table>