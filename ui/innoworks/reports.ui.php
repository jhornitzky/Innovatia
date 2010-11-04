<? 
require_once("thinConnector.php");  

function renderDetails() {
$noOfIdeas = countQuery("SELECT COUNT(*) FROM Ideas WHERE userId='".$_SESSION['innoworks.ID']."'");
$noOfSelectedIdeas = countQuery("SELECT COUNT(*) FROM Selections, Ideas WHERE Ideas.userId='".$_SESSION['innoworks.ID']."' and Ideas.ideaId = Selections.ideaId");
?>
<h3>Stats on yourself</h3>
<p>Number of ideas : <?= $noOfIdeas ?></p>
<p>Number of selected ideas : <?= $noOfSelectedIdeas ?></p>
<p>Number of groups created : <? echo countQuery("SELECT COUNT(*) FROM Groups WHERE userId='".$_SESSION['innoworks.ID']."'"); ?></p>
<p>Selected idea ratio : <?= $noOfSelectedIdeas/$noOfIdeas ?></p>
<p>Number of groups part of : <? echo countQuery("SELECT COUNT(*) FROM GroupUsers WHERE userId='".$_SESSION['innoworks.ID']."'"); ?></p>
<br/>
<h3>Stats on everybody</h3>
<p>Number of ideas : <? echo countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of selected ideas : <? echo countQuery("SELECT COUNT(*) FROM Selections"); ?></p>
<p>Selected idea ratio : <? echo countQuery("SELECT COUNT(*) FROM Selections")/countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of groups : <? echo countQuery("SELECT COUNT(*) FROM Groups"); ?></p>
<p>Number of innovators : <? echo countQuery("SELECT COUNT(*) FROM Users"); ?></p>
<?}

function renderGraphs() {?>
<!-- 
<h3>Number of your ideas</h3>
<div id="simplechart2" style="width: 500px; height: 250px;"></div>

<script type="text/javascript">
//Include the required dojo libraries/namespaces
dojo.require("dojox.charting.Chart2D"); 

function chartThis() {
    var chart2 = new dojox.charting.Chart2D("simplechart2");
    chart2.addPlot("default", {type: "Bars", gap:5});
    chart2.addAxis("x");
    chart2.addAxis("y", {vertical: true});
    chart2.addSeries("UserA", [0,1,5,4]);
    chart2.addSeries("UserB", [0,3,0,0]);
	chart2.render();
};

chartThis();
</script>
 -->
<?}?>