<?
require_once("thinConnector.php");
require_once("reports.ui.php");
import("report.service");

if (isset($_GET) && $_GET != '') {
	switch ($_GET['action']) {
		case "getReportDetails":
			renderDetails();
			break;
		case "getReportGraphs":
			renderGraphs();
			break;
	}
}
?>