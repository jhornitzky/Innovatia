<div style="height: 120px; border:1px solid #AAA; position:relative">
	<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>');opacity:0.25; background-position:center center;background-color:#EEE;"></div>
	<div style="position:absolute;width:100%;height:120px; background-image:url('retrieveImage.php?action=groupImg&actionId=<?=$group->groupId?>'); background-repeat:no-repeat; background-position:center center;">
		<h1><?= $group->title ?></h1>
		Group ideas
	</div>
</div>