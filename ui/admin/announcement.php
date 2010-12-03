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
<form id="announcement" action="announcement.php" method="post">
Send announcement<br/>
<input type="text" name="text" />
<input name="action" type="submit" value="Send"/>
</form>

<? 
$announces = getAnnouncements();
if ($announces && dbNumRows($announces)) {
	while($announce = dbFetchObject($announces)) {?>
		<p>
		<?= $announce->text ?><br/>
		<?= getUserInfo($announce->userId)->username . " " . $announce->date ?>
		</p>
	<?}
}
?>
</body>
</html>