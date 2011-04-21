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
<img src="<?= $serverUrl . $serverRoot ?>ui/style/kubus.png" style="cursor:pointer; border:none;"/><br/>
</td>
<td>
<p class="subheading" style="font-size:50px;padding-top:12px;">
update
</p>
</td>
</tr>
</table>
</a>
<? 
if (isset($msgTemplate)) {
	echo renderTemplate($msgTemplate);
} else if (isset($msg)) {
	echo $msg;
} 
//FIXME probably should throw exception here
?>
</body>
</html>
