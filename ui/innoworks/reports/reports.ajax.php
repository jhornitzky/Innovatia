<?
require_once(dirname(__FILE__) . "/../thinConnector.php");
require_once(dirname(__FILE__) . "/reports.ui.php");
import("report.service");

if (isset($_GET['action'])) {
	switch ($_GET['action']) {
		case "getReportDetails":
			renderReportDetails();
			break;
	}
}
?>