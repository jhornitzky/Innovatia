<?require_once("thinConnector.php");?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<?
if (isset($_POST['action']) && $_POST['action'] == "Query")
	renderServiceResponse(dbQuery($_POST['queryText'])); 

$tableName;
if (isset($_GET['tablename']))
	$tableName=$_GET['tablename'];
else if (isset($_POST['tablename'])) 
	$tableName=$_POST['tablename'];
 
if (isset($tableName)) {
	logDebug("Retrieving tablename data");
	$datars = dbQuery("SELECT * FROM $tableName");
	if (isset($_POST['updateBtn'])) {
		$info = $_POST['updateBtn'];
		$sql = "UPDATE $tableName SET";
		foreach($_POST AS $key => $value) {
			if ( $key != "insertBtn" && $key != "tablename")
			$sql = $sql + " $value,";
		}
		$sql = $sql + "WHERE id=$info";
		echo $sql;
		dbQuery($sql);
	} else if (isset($_POST['insertBtn'])) {
		$sql  = "INSERT INTO $tableName VALUES";
		foreach($_POST AS $key => $value) {
			if ( $key != "insertBtn" && $key != "tablename")
			$sql = $sql + " $value,";
		}
		echo $sql;
		dbQuery($sql);
	} else if (isset($_POST['deleteBtn'])) {
		$sql  = "DELETE FROM $tableName WHERE id=$info";
		echo $sql;
		dbQuery($sql);
	} else if (isset($_POST['createDataBtn'])) {
		$sql = file_get_contents("dbcreate.sql");
		dbQuery($sql);
	}
}
?>
<p><b>SQL</b></p>
<form action="table.php" method="post">
<textarea name="queryText" style="width:100%;"
title="Change tables through SQL; 
INSERT INTO tablename (field1,field2,...) VALUES ('value1','value2',...);
DELETE FROM tablename WHERE field1 = 'X';
UPDATE tablename SET field1='value1', field2='value2' WHERE field3='value3'"></textarea><br/>
<input type="submit" name="action" value="Query"/>
<input type="hidden" name="tablename" value="<?= $tableName ?>"/>
</form>
<hr/>
<p><b>Tables</b></p>
<div id="tableList" style="width: 100%; font-size:0.9em;">
<? 
$tablers = dbQuery("SHOW TABLES");

while($table = dbFetchNumArray($tablers)) {?> | 
	<a href="?tablename=<?= $table[0];?>"><?= $table[0];?></a> | 
<?}
dbRelease($tablers);?>
</div>

<p><b><?= $tableName ?></b></p>
<div id="contentContainer" style="border: 1px solid #000000; padding: 3px; font-size:0.9em;">
<table>
<?
renderGenericHeader($datars,array(), false);
if (isset($datars)) {
if ($datars && dbNumRows($datars) > 0) {
	echo "<tr>";
	while ($row = dbFetchObject($datars)) {
	renderGenericInfoRow($datars, $row, array());?>
	<td><!-- <form><input type="submit" name="deleteBtn" value="Delete"/> </form> --></td>
	<?
	echo "</tr>"; 
	}
}
}
dbRelease($datars);
?>
</table>
</div>
</body>
</html>