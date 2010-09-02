<?
function renderGenericAddForm($tablename, $omitArray) {
	$rs = dbQuery("SELECT * FROM $tablename LIMIT 0"); //FIXME
	while ($meta = dbFetchField($rs)) {
		if (!$meta || in_array($meta->name, $omitArray)) {
			//logDebug("Omit field from generic add");
		} else {?>
		<label><?= $meta->name;?></label>
		<input type="text" name="<?= $meta->name;?>" />
		<?}
	}
}

function renderGenericUpdateForm($rs,$row,$omitArray) {
	echo "<table>";
	foreach($row as $key => $value) {
		echo "<tr>";
		if (!in_array($key, $omitArray)) {?>
			<td><label><?=$key?></label></td>
			<td><input type="text" name="<?=$key?>" value="<?=$value?>" /></td>
		<?}
		echo "</tr>";
	}
	echo "</table>";
}

function renderGenericHeader($rs, $omitArray) {
	echo "<tr>";
	while ($field = dbFetchField($rs)) {
		if (!in_array($field->name, $omitArray)) {
			echo "<th>". $field->name. "</th>";
		}
	}
	echo "<th></th>"; //EXTRA For actions
	echo "</tr>";
}

function renderGenericUpdateRow($rs,$row,$omitArray) {
	foreach($row as $key => $value) {
		if (!in_array($key, $omitArray)) {?>
			<td><input type="text" name="<?=$key?>" value="<?=$value?>" /></td>
		<?}
	}
}
?>
