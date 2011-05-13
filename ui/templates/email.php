<?php global $serverUrl, $serverRoot; ?>
<html>
<head>
<title><?= (isset($title)) ? $title : 'Innoworks'; ?></title>
</head>
<body>
<a style="text-decoration:none;" href="<?= $serverUrl . $serverRoot ?>">
<table>
<tr>
<td>
<img src="<?= $serverUrl . $serverRoot ?>ui/style/kubus.png" style="cursor:pointer; border:none;" alt="innoWorks"/><br/>
</td>
<td>
<p class="subheading" style="font-size:50px;padding-top:12px;">
update
</p>
</td>
</tr>
</table>
</a>
<div style="margin-bottom:20px; color:#444; font-size:1.2em">
<? 
if (isset($msgTemplate)) {
	echo renderTemplate($msgTemplate);
} else if (isset($msg)) {
	echo $msg;
} 
//FIXME probably should throw exception here
?>
</div>
<div style="border-top:1px solid #AAA; font-size:0.9em; color:#AAA">
<p>To find out more, head over to <a href="<?= $serverUrl . $serverRoot ?>">innoworks</a>.</p>
<p>To turn off email notifications, go to innoWorks > profile > details and uncheck the 'Send email' box.</p>
</div>
</body>
</html>
