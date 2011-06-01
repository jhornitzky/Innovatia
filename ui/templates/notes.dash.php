<? $count = 0;
if ($notes && dbNumRows($notes) > 0) {
	while ($note = dbFetchObject($notes)) {
		$count++;?>
<div class="itemHolder clearfix">
	<div class="lefter lefterImage">
		<img
			src="retrieveImage.php?action=userImg&actionId=<?= $note->fromUserId ?>"
			style="width: 2em; height: 2em;" />
	</div>
	<div class="lefter">
	<?= $note->noteText ?>
		<br /> <span> <a
			href="javascript:showProfileSummary('<?= $note->fromUserId ?>')"> <?= getDisplayUsername($note->fromUserId)?>
		</a> sent <span><?= $note->createdTime ?> </span> </span>
	</div>
</div>
	<?}?>
<a class="loadMore" href="javascript:log(this)" onclick="showProfile();">show
	and send more notes</a>
	<?}

	if ($count < 1) { ?>
<p style="margin-top: 0;">Welcome to innoWorks, the innovation
	management tool.</p>
<p>innoWorks helps you innovate by giving you a simple yet structured
	way to manage your ideas, compare them and select them. A good place to
	start is by hitting the green add button up the top left, or you can
	move throughout the system at your own pace.</p>
<p>If you get stuck you can click on the 'i' icon to the top right for
	help. Happy ideating!</p>
	<?}?>