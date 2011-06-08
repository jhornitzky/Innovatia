<? $count = 0;
if ($notes && dbNumRows($notes) > 0) {
	while ($note = dbFetchObject($notes)) {
		$count++;
		renderTemplate('noteItem', array('note' => $note));
	}?>
	<div class="loadMore" href="javascript:log(this)" onclick="showProfile();">show and send more notes &rarr;</div>
<?}

if ($count < 1) { ?>
	<p style="margin-top: 0;">Welcome to innoWorks, the innovation management tool.</p>
<?}?>