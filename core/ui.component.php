<?
/*
 * UI Components are common, often db-related widgets. 
 * These common patterns are abstracted to try and reduce the amount of rendering code written.
 */

function renderGenericAddForm($tablename, $omitArray) {
	$rs = dbQuery("SELECT * FROM $tablename LIMIT 0"); //FIXME
	while ($meta = dbFetchField($rs)) {
		if (!$meta || in_array($meta->name, $omitArray)) {
			//logDebug("Omit field from generic add");
		} else {?>
<label><?= fromCamelCase($meta->name);?></label>
<input type="text"
	name="<?= $meta->name;?>" />
		<?}
	}
}

function renderGenericInfoForm($rs,$row,$omitArray) {
	echo "<table>";
	foreach($row as $key => $value) {
		echo "<tr>";
		if (!in_array($key, $omitArray)) {?>
<td><label><?=fromCamelCase($key)?></label></td>
<td>
<p><?=$value?></p>
</td>
		<?}
		echo "</tr>";
	}
	echo "</table>";
}

function renderGenericUpdateForm($rs,$row,$omitArray) {
	//print_r($row);
	echo "<table>";
	foreach($row as $key => $value) {
		echo "<tr>";
		if (!in_array($key, $omitArray)) {?>
<td style="width: 10%"><label><?=fromCamelCase($key)?></label></td>
<td
	style="width: 90%"><!-- <input type="text" name="<?=$key?>" value="<?=$value?>" />  -->
<textarea name="<?=$key?>" dojoType="dijit.form.Textarea"><?=$value?></textarea>
</td>
		<?}
		echo "</tr>";
	}
	echo "</table>";
}

function renderGenericUpdateFormWithRefData($rs,$row,$omitArray,$tableName) {
	$refdata = getRefDataForTable($tableName);

	if ($refdata && dbNumRows($refdata) > 0)
	$refdata = dbFetchAll($refdata);

	echo "<table>";
	foreach($row as $key => $value) {
		echo "<tr>";
		if (!in_array($key, $omitArray)) {
			$metaArray = findColumnMetadata($refdata, $key);
			if ($metaArray){
				$metaHelp = getColumnDescription($metaArray);
				if ($metaHelp) {?>
					<td style="width: 10%"><span class="helper" title="<?= $metaHelp?>"><?= fromCamelCase($key) ?></span></td>
				<?} else {?>
					<td style="width: 10%"><label><?=fromCamelCase($key)?></label></td>
				<?}
			}
			
			$metaArray = findColumnMetadata($refdata, $key);
			//If metadata then render appropriate input dialog
			if ($metaArray){
				//Get column type, if column type, then render appropriate input
				$metaType = getColumnType($refdata);
				if ($metaType) {
					renderMetaType($metaType, $key, $value);
				} else {?>
					<td style="width: 10%"><label><?=fromCamelCase($key)?></label></td>
					<td style="width: 90%"><!-- <input type="text" name="<?=$key?>" value="<?=$value?>" />  -->
						<textarea name="<?=$key?>" dojoType="dijit.form.Textarea"><?=$value?></textarea>
					</td>
				<?}
			} else {?>
					<td style="width: 10%"><label><?=fromCamelCase($key)?></label></td>
					<td style="width: 90%"><!-- <input type="text" name="<?=$key?>" value="<?=$value?>" />  -->
						<textarea name="<?=$key?>" dojoType="dijit.form.Textarea"><?=$value?></textarea>
					</td>
			<?}
		}
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

function renderGenericHeaderWithRefData($rs, $omitArray, $tableName) {
	$refdata = getRefDataForTable($tableName);

	if ($refdata && dbNumRows($refdata) > 0)
	$refdata = dbFetchAll($refdata);

	echo "<tr>";
	while ($field = dbFetchField($rs)) {
		if (!in_array($field->name, $omitArray)) {
			//logDebug("Field name: ".$field->name);
			$metaArray = findColumnMetadata($refdata, $field->name);
			//logDebug("Col Head meta array: ".$metaArray);
				
			echo "<th>";
			//If metadata then render help lnk
			if ($metaArray){
				$metaHelp = getColumnDescription($metaArray);
				if ($metaHelp) {?>
					<span class="helper" title="<?= $metaHelp?>"><?= fromCamelCase($field->name) ?></span>
				<?}
			} else {
				echo fromCamelCase($field->name);
			}
			echo "</th>";
				
		}
	}
	echo "<th></th>"; //EXTRA For actions FIXME
	echo "</tr>";
}

function renderGenericUpdateRow($rs,$row,$omitArray) {
	foreach($row as $key => $value) {
		if (!in_array($key, $omitArray)) {?>
<td><!-- <input type="text" name="<?=$key?>" value="<?=$value?>" />  -->
<textarea name="<?=$key?>" dojoType="dijit.form.Textarea"><?=$value?></textarea>
</td>
		<?}
	}
}

function renderGenericUpdateRowWithRefData($rs,$row,$omitArray,$tableName, $renderCallback) {
	$refdata = getRefDataForTable($tableName);

	if ($refdata && dbNumRows($refdata) > 0)
		$refdata = dbFetchAll($refdata);

	foreach($row as $key => $value) {
		if ($renderCallback != null) 
			eval('$rendered='.$renderCallback.'($key, $value);');
			
		if (!in_array($key, $omitArray) && !$rendered) {
			$metaArray = findColumnMetadata($refdata, $key);
			echo "<td>";
			//If metadata then render appropriate input dialog
			if ($metaArray){
				//Get column type, if column type, then render appropriate input
				$metaType = getColumnType($refdata);
				if ($metaType) {
					renderMetaType($metaType, $key, $value);
				} else {
					echo "<select name='$key' value='$value'>";
					echo "<option value='$value' selected='selected'>$value</option>";
					$metaValArray = getColumnValues($metaArray);
					if ($metaValArray) {
						foreach($metaValArray as $metaValue) {
							if ($metaValue != $value) //PREVENT DOUBLE RENDERING
							echo "<option value='$metaValue'>$metaValue</option>";
						}
					}
					echo "</select>";
				}

			} else {?>
				<input type="text" name="<?=$key?>" value="<?=$value?>" />
			<?}
			echo "</td>";
		}
	}
}

////////////// COLUMN METADATA FUNCTIONS FROM REFERENCE DATA //////////////////

function renderMetaType($metaType, $key, $value) {
	switch ($metaType) {
		case "likert7":
			echo "<select name='$key' value='$value'>";
			echo "<option value='$value' selected='selected'>$value</option>";
			for ($i = 1; $i <= 7; $i++ ) {
				if ($i != $value) //PREVENT DOUBLE RENDERING
				echo "<option value='$i'>$i</option>";
			}
			echo "</select>";
			break;
	}
}

function findColumnMetadata($refDataArray, $needle) {
	//logDebug("Find column metadata");
	if (!$refDataArray) {
		logDebug("Empty column meta refDataArray");
		return $refDataArray;
	}
	$metaArray = false;
	foreach ($refDataArray as $key => $refDataValue) {
		//logDebug("Array Iter: ". $key . " " . $refDataValue["key2"]);
		if ($refDataValue["key2"] == $needle) {
			//logDebug("Found metadata for column; ". $needle);
			$metaArray[$key] = $refDataValue;
		}
	}
	return $metaArray;
}

function getColumnValues($refDataArray) {
	if (!$refDataArray)
	return $refDataArray;

	$metaArray = false;
	$x=0;

	foreach ($refDataArray as $refDataValue) {
		//logDebug("Column value grab: " . $refDataValue["value"] . " ; key3: " . $refDataValue["key3"]);
		if ($refDataValue["key3"] == "V") {
			//logDebug("Add to metaArray". $refDataValue["key3"]);
			$metaArray[$x] = $refDataValue["value"];
			$x++;
		}
	}
	return $metaArray;
}

function getColumnType($refDataArray) {
	if (!$refDataArray)
	return $refDataArray;

	foreach ($refDataArray as $refDataValue) {
		if ($refDataValue["key3"] == "T") {
			return $refDataValue["value"];
		}
	}
	return false;
}

function getColumnDescription($refDataArray) {
	//logDebug("In get col description");
	if (!$refDataArray)
	return $refDataArray;

	foreach ($refDataArray as $refDataValue) {
		if ($refDataValue["key3"] == "H") {
			//logDebug("return refdata value");
			return $refDataValue["value"];
		}
	}
	return false;
}

function renderServiceResponse($resp) {
	if ($resp) {
		return "<span style='color:green;font-weight:bold;'>Success</span> ";	
	} else {
		return getCommonErrorString("Unknown");
	}
	
}

function getCommonErrorString($cause) {
	return "<span style='color:red;font-weight:bold;'>Error occurred:</span> " . $cause . "<br/> Please review your input in light of error. If problem exists please notify support thru feedback.";
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