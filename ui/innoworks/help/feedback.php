<?php require_once("../thinConnector.php");?>
Send feedback to innoworks admin staff
<form onsubmit="return sendFeedback(this)">
<textarea style="width:100%; height: 12em" name="noteText"></textarea>
<input type="submit" value="Send"/> or write direct <a href="mailto:<?= $serverAdminEmail ?>">email</a>
<input type="hidden" name="action" value="sendFeedback"/>
</form>