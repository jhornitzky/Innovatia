<?
require_once("innoworks.config.php");

//////////////// GENERIC CRUD FUNCTIONS ////////////

function genericCreate($tablename, $opts) {
	$comma_keys = implode(",", array_keys($opts));
	$comma_vals = dbValImplode(array_values($opts));

	$sql = "INSERT INTO $tablename (".$comma_keys.") VALUES (".$comma_vals.")";

	logDebug("SQL TO CREATE KEYS: ".$sql);

	return dbQuery($sql);
}

function genericUpdate($tablename, $opts, $where) {
	$keys = array_keys($opts);
	$vals = array_values($opts);

	$setString;
	$whereString;
	for ($i = 0; $i < count($keys); $i++) {
		if (in_array($keys[$i], $where)) {
			if (!isset($whereString))
				$whereString = $keys[$i]." = '".$vals[$i]."'";
			else
				$whereString = $whereString." AND ".$keys[$i]." = '".$vals[$i]."'";
		} else {
			if (!isset($setString))
				$setString = $keys[$i]." = '".$vals[$i]."'";
			else
				$setString = $setString.", ".$keys[$i]." = '".$vals[$i]."'";
		}
	}

	$sql = "UPDATE $tablename SET ".$setString." WHERE ".$whereString;

	logDebug("SQL TO CREATE KEYS: ".$sql);

	return dbQuery($sql);

}

function genericDelete($tablename, $opts) {
	$keys = array_keys($opts);
	$vals = array_values($opts);

	$whereString;
	for ($i = 0; $i < count($keys); $i++) {
		if ($i == (count($keys) - 1))
		$whereString = $whereString.$keys[$i]." = '".$vals[$i]."'";
		else
		$whereString = $whereString.$keys[$i]." = '".$vals[$i]."' AND ";
	}

	$sql = "DELETE FROM ". $tablename . " WHERE " . $whereString;
	logDebug("SQL TO DELETE KEYS: ".$sql);

	return dbQuery($sql);
}

////////////// DBDRIVER FUNCTIONS ///////////////

function dbConnect()
{
	global $dbURL, $dbUser, $dbPass, $dbSchema;
	$upLink = mysqli_connect($dbURL, $dbUser, $dbPass, $dbSchema);
	/*
		if(!dbAlive($upLink)){return false;}
		$dbOpen = mysqli_select_db($dbSchema, $upLink);
		if(!$dbOpen){return false;}
		*/
	return $upLink;
}

function dbAlive($upLink)
{
	if (is_resource($upLink) && $upLink)
	{ return true; }
	return false;
}

function dbQuery($sqlQuery)
{
	logDebug($sqlQuery);
	$argv = func_get_args();
	$argc = func_num_args();
	switch ($argc)
	{
		/*
			1 Argument
			Autonomous MySQL Query {Automatically: Connects, Cleans + Closes}
			Creates Own Connection
			*/
		case 1:
			/*
				if (!is_string($argv[0]))
				{
				die("Argument 0: Not A MySQL String");
				}
				*/
			return dbQuery_Auto($argv[0]);
			/*
			 2 Arguments
			 Manual MySQL Query {Must Manually: Connect, Cleanup + Close}
			 Uses Existing Connection
			 */
		case 2:
			/*
				if (!dbAlive($argv[0]))
				{
				die("Argument 0: Not A Valid MySQL Connection");
				}
				if (!is_string($argv[1]))
				{
				die("Argument 1: Not A MySQL String");
				}
				*/
			return dbQuery_Manual($argv[0], $argv[1]);
		default:
			return false;
	}

}

function dbQuery_Auto($sqlQuery)
{
	$dbUplink = dbConnect();
	$queryResult = mysqli_query($dbUplink, $sqlQuery);
	dbClose($dbUplink);
	return $queryResult;

	/*
	 if ($queryResult)
	 {
		if (is_object($queryResult))
		{
		$dataSet = array();
		for ($Iter = 0; $dataRow = mysqli_fetch_object($queryResult); $Iter++)
		{
		$dataSet[$Iter] = $dataRow;
		}
		dbRelease($queryResult);
		dbClose($dbUplink);
		return $dataSet;
		} else {
		dbClose($dbUplink);
		return $queryResult;
		}
		}
		*/
}

function dbQuery_Manual($dbUplink, $sqlQuery)
{
	return mysqli_query($dbUplink, $sqlQuery);
	/*
	 if ($queryResult)
	 {
		if (is_object($queryResult))
		{
		$dataSet = array();
		for ($Iter = 0; $dataRow = mysqli_fetch_object($queryResult); $Iter++)
		{
		$dataSet[$Iter] = $dataRow;
		}
		return $dataSet;
		}
		}
		return false;
		*/
}

function countQuery($sql) {
	$rs = dbQuery($sql);
	$result = dbFetchArray($rs);
	return $result[0];
}

function getColumnNames($tablename) {
	return dbQuery("SELECT * FROM $tablename LIMIT 0");
}

function dbClose($link)
{
	mysqli_commit($link);
	mysqli_close($link);
}

//////////////////////////////////////////////

function dbAffectedId($result) {
	return mysqli_insert_id($result); //FIXME
}

function dbNumAffectedRows($result) {
	return mysqli_affected_rows($result);
}

function dbNumRows($result) {
	return mysqli_num_rows($result);
}

function dbNumFields($result) {
	return mysqli_num_fields($result);
}

function dbFetchObject($result) {
	return mysqli_fetch_object($result);
}

function dbFetchField($result) {
	return mysqli_fetch_field($result);
}

function dbFetchArray($results) {
	return mysqli_fetch_array($results);
}

function dbRelease($result)
{
	@mysqli_free_result($result);
}

function execQuery($link, $sqlQuery) {
	return mysqli_query($link, $sqlQuery);
}

function cleanseString($link, $str) {
	return mysqli_real_escape_string($link, $str);
}

function dbCloseRollback($link) {
	mysqli_rollback($link);
	mysqli_close($link);
}

function dbInsertedId($link) {
	return mysqli_insert_id($link);
}
?>