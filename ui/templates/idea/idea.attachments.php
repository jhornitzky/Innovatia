<form method="post" enctype="multipart/form-data"
	onsubmit="addIdeaAttachment(this);return false;">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000"> <input
		name="userfile" type="file" id="userfile"> <input type="hidden"
		name="action" value="addAttachment" /> <input type="submit"
		value=" + " title="Add attachment" />
</form>
<?
if ($attachs && dbNumRows($attachs)) {
	while ($attach = dbFetchObject) {
		echo $attach->title;
	}
} else {
	echo "<p>No attachments</p>";
}?>