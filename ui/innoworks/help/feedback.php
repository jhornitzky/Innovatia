<?php require_once("../thinConnector.php");?>
<form class="addForm" onsubmit="return sendFeedback(this)">
send feedback
<textarea style="width:100%; height: 12em" name="noteText"></textarea>
<input type="submit" value="Send"/> or write <a href="mailto:<?= $serverAdminEmail ?>">email</a>
<input type="hidden" name="action" value="sendFeedback"/>
</form>