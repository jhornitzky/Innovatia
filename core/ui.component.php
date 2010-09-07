<?
function renderGenericAddForm($tablename, $omitArray) {
	$rs = dbQuery("SELECT * FROM $tablename LIMIT 0"); //FIXME
	while ($meta = dbFetchField($rs)) {
		if (!$meta || in_array($meta->name, $omitArray)) {
			//logDebug("Omit field from generic add");
		} else {?>
<label><?= $meta->name;?></label>
<input type="text"
	name="<?= $meta->name;?>" />
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
			echo "<th>". fromCamelCase($field->name). "</th>";
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

function renderGenericUpdateRowWithRefData($rs,$row,$omitArray,$tableName) {
	$refdata = getRefDataForTable($tableName);
	if ($refdata && dbNumRows($refdata) > 0)
	$refdata = dbFetchAll($refdata);

	foreach($row as $key => $value) {
		if (!in_array($key, $omitArray)) {
			$metaArray = findColumnMetadata($refdata, $key);
			
			if ($metaArray){
				echo "<td>";
				echo "<select name='$key' value='$value'>";
				echo "<option value='$value' selected='selected'>$value</option>";
				
				$metaValArray = getColumnValues($metaArray);
				if ($metaValArray) {
					foreach($metaValArray as $metaValue) {
						logDebug("Meta value is ".$metaValue);
						if ($metaValue != $value) //PREVENT DOUBLE RENDERING
						echo "<option value='$metaValue'>$metaValue</option>";
					}
				}
				
				echo "</select>";
				echo "</td>";
			}
			else {?>
				<td><input type="text" name="<?=$key?>" value="<?=$value?>" /></td>
			<?}
		}
	}
}

function findColumnMetadata($refDataArray, $needle) {
	if (!$refDataArray)
	return $refDataArray;

	$metaArray = false;
	foreach ($refDataArray as $key => $refDataValue) {
		//logDebug("Array Iter: ". $key . " " . $refDataValue["key2"]);
		if ($refDataValue["key2"] == $needle)
		$metaArray[$key] = $refDataValue;
	} 
	return $metaArray;
}

function getColumnValues($refDataArray) {
	if (!$refDataArray)
		return $refDataArray;

	$metaArray = false;
	$x=0;
	
	foreach ($refDataArray as $refDataValue) {
		logDebug("Column value grab: " . $refDataValue["value"] . " ; key3: " . $refDataValue["key3"]);
		if ($refDataValue["key3"] == "V") {
			logDebug("Add to metaArray". $refDataValue["key3"]);
			$metaArray[$x] = $refDataValue["value"];
			$x++;
		}
	}
	return $metaArray;
}

///////////////////////// Camel Case util functions courtesy of  //////////////////////////////
/////////http://www.paulferrett.com/2009/php-camel-case-functions///////////////////

function fromCamelCase($str)
{
	$str[0] = strtolower($str[0]);
	return ucfirst(preg_replace('/([A-Z])/e', "' ' . strtolower('\\1')", $str));
}

function toCamelCase($str, $capitaliseFirstChar = false)
{
	if ($capitaliseFirstChar) {
		$str[0] = strtoupper($str[0]);
	}

	return preg_replace('/_([a-z])/e', "strtoupper('\\1')", $str);
}
?>
