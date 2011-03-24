<?php
require_once('phpgraphlib/phpgraphlib.php');
require_once('thinConnector.php');

//Get the data
$data = array();
$raw = array();

$curYear = $_REQUEST['year'];

switch ($_REQUEST['action']) {
	case 'ideas':
		$items = dbQuery('SELECT COUNT(*) AS count, WEEK(createdTime) AS week FROM Ideas WHERE YEAR(createdTime) = ' . $curYear . ' GROUP BY YEARWEEK(createdTime)');
		break;
	case 'views':
		$items = dbQuery('SELECT COUNT(*) AS count, WEEK(dateTime) AS week FROM Views WHERE YEAR(dateTime) = ' . $curYear . ' GROUP BY YEARWEEK(dateTime)');
		break;
	case 'groups':
		$items = dbQuery('SELECT COUNT(*) AS count, WEEK(createdTime) AS week FROM Groups WHERE YEAR(createdTime) = ' . $curYear . ' GROUP BY YEARWEEK(createdTime)');
		break;
	case 'users':
		$items = dbQuery('SELECT COUNT(*) AS count, WEEK(createdTime) AS week FROM Users WHERE YEAR(createdTime) = ' . $curYear . ' GROUP BY YEARWEEK(createdTime)');
		break;
}

//Run thru each entry
while ($item = dbFetchObject($items)) {
	array_push($raw, $item);
	$data[$item->week] = $item->count;
}

//Now convert into year week & format the data
for($i = 1; $i < 53; $i++) {
	$j = $i;
	if ($j < 10) {
		$j = '0' + $j;
	}
	
	$j = strval($j);
	
	if (!isset($data[$j])) {
		$data[$j] = 0;	
	} 
}
ksort($data);

$graph = new PHPGraphLib(1000,350);
$graph->addData($data);
$graph->setDataValues(true);
$graph->setGradient('blue', 'blue');
$graph->createGraph();
?>