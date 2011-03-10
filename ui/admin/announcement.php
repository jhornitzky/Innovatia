<?
require_once("thinConnector.php");
import("note.service");
import("user.service");
?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<?
if (isset($_POST['action']) && $_POST['action'] == "Send") {
	renderServiceResponse(createAnnouncement($_SESSION['innoworks.ID'], $_POST['text']));
}
?>
<h2>Announcements</h2>
<div class="greenbox" style="padding:0.5em; margin-top:10px; -moz-border-radius: 4px 4px 4px 4px; background: -moz-linear-gradient(center top , #B4E391 0%, #61C419 50%, #B4E391 100%) repeat scroll 0 0 #02BF0F; width:95%;">
<form id="announcement" action="announcement.php" method="post">
Send announcement<br/>
<!-- <input type="text" name="text" style="min-width:35em"/> -->
<textarea rows="2" cols="60" name="text"></textarea><br/>
<input name="action" type="submit" value="Send"/>
<span style="font-size:12px;font-style:italic;">Note: be careful about what you announce as you can't unsend your announcements!</span>
</form>
</div>
<? 
$announces = getAnnouncements("LIMIT 200");
if ($announces && dbNumRows($announces)) {
	while($announce = dbFetchObject($announces)) {?>
		<p>
		<?= $announce->text ?><br/>
		<span style="font-size:0.85em;"><?= getDisplayUsername($announce->userId) . " " . $announce->date ?></span>
		</p>
		<hr/>
	<?}
}
?>
</body>
</html>