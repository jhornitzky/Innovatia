<?php
require_once '../thinConnector.php';
import('note.service');
sendMail(array('to' => 'james.hornitzky@gmail.com', 'msg' => 'hello again', 'subject' => 'hello'));
?>
<p>Should have sent mail</p>