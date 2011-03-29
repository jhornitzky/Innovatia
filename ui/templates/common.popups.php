<div id="ideasPopup" dojoType="dijit.Dialog" title="Edit idea">
	<div id="ideaPopupResponses" class="responses"></div>
	<span class="ideaDetailsOptions"
		style="position: relative; float: right;"> <a
		href="javascript:printPopupIdea()">Print</a> </span>
	<table>
		<tr>
			<td style="vertical-align: top;"><img id="popupIdeaImgMain"
				style="height: 2em; width: 2em;" />
			</td>
			<td style="vertical-align: top;"><span id="ideaName"></span>
			</td>
		</tr>
	</table>

	<div id="ideasPopupTabContainer" dojoType="dijit.layout.TabContainer"
		style="width: 55em; height: 28em;">
		<div id="ideasPopupDetails" dojoType="dijit.layout.TabContainer"
			title="Details" nested="true"
			iconClass="dijitEditorIcon dijitEditorIconSelectAll">
			<div id="ideaMission" dojoType="dijit.layout.ContentPane"
				title="Mission"
				iconClass="dijitEditorIcon dijitEditorIconViewSource"></div>
			<div id="ideaFeatures" dojoType="dijit.layout.ContentPane"
				title="Features"
				iconClass="dijitEditorIcon dijitEditorIconViewSource"></div>
			<div id="ideaRoles" dojoType="dijit.layout.ContentPane" title="Roles"
				iconClass="dijitEditorIcon dijitEditorIconViewSource"></div>
			<div id="ideaAttachments" dojoType="dijit.layout.ContentPane"
				title="Attachments"
				iconClass="dijitEditorIcon dijitEditorIconInsertImage"></div>
		</div>
		<div id="ideasPopupReview" dojoType="dijit.layout.TabContainer"
			title="Review" nested="true"
			iconClass="dijitEditorIcon dijitEditorIconSelectAll">
			<div id="ideaComments" dojoType="dijit.layout.ContentPane"
				title="Comments" iconClass="dijitEditorIcon dijitEditorIconWikiword">
				<div id="addComment">
					<form id="addCommentForm" class="addForm ui-corner-all"
						onsubmit="addComment();return false;">
						New Comment <input type="submit" value=" + " />
						<textarea name="text" dojoType="dijit.form.Textarea"
							style="width: 100%;"></textarea>
						<input type="hidden" name="action" value="addComment" />
					</form>
				</div>
				<div id="commentList">No comments yet</div>
			</div>
			<div id="ideaFeatureEvaluationList"
				dojoType="dijit.layout.ContentPane" title="Feature Evaluation"
				iconClass="dijitEditorIcon dijitEditorIconInsertTable"></div>
			<div id="ideaRiskEval" dojoType="dijit.layout.ContentPane"
				title="Risk Evaluation"
				iconClass="dijitEditorIcon dijitEditorIconInsertTable"></div>
		</div>
		<div id="ideaSelect" dojoType="dijit.layout.ContentPane"
			title="Select" iconClass="dijitEditorIcon dijitEditorIconSelectAll"></div>
		<div id="ideaShare" dojoType="dijit.layout.ContentPane" title="Share"
			iconClass="dijitEditorIcon dijitEditorIconCopy"></div>
	</div>
</div>

<div id="commonPopup" dojoType="dijit.Dialog"
	style="width: 18em; height: 20em;">
	<div id="actionDetails"></div>
</div>