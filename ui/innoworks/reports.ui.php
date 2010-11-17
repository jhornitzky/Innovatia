<? 
require_once("thinConnector.php");  

function renderDetails() {
$noOfIdeas = countQuery("SELECT COUNT(*) FROM Ideas WHERE userId='".$_SESSION['innoworks.ID']."'");
$noOfSelectedIdeas = countQuery("SELECT COUNT(*) FROM Selections, Ideas WHERE Ideas.userId='".$_SESSION['innoworks.ID']."' and Ideas.ideaId = Selections.ideaId");
?>
<div class="two-column">
<div class="blue" style="height:1.5em"></div>
<h3>Stats on yourself</h3>
<p>Number of ideas : <?= $noOfIdeas ?></p>
<p>Number of selected ideas : <?= $noOfSelectedIdeas ?></p>
<p>Selected idea ratio : <?= $noOfSelectedIdeas/$noOfIdeas ?></p>
<p>Number of groups created : <? echo countQuery("SELECT COUNT(*) FROM Groups WHERE userId='".$_SESSION['innoworks.ID']."'"); ?></p>
<p>Number of groups part of : <? echo countQuery("SELECT COUNT(*) FROM GroupUsers WHERE userId='".$_SESSION['innoworks.ID']."'"); ?></p>
</div>

<div class="two-column" style="margin-left:2%">
<div class="green" style="height:1.5em;"></div>
<h3>Stats on everybody</h3>
<p>Number of ideas : <? echo countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of selected ideas : <? echo countQuery("SELECT COUNT(*) FROM Selections"); ?></p>
<p>Selected idea ratio : <? echo countQuery("SELECT COUNT(*) FROM Selections")/countQuery("SELECT COUNT(*) FROM Ideas"); ?></p>
<p>Number of groups : <? echo countQuery("SELECT COUNT(*) FROM Groups"); ?></p>
<p>Number of innovators : <? echo countQuery("SELECT COUNT(*) FROM Users"); ?></p>
</div>
<?}?>
