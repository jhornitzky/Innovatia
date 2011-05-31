<div style="width: 54%; position: relative; float: left; margin-right: 2%;">
	<form id="profileDetailsForm"
		onsubmit="updateProfile('profileDetailsForm'); return false;"
		style="border: 1px solid #DDD;">
		<table style="width: 100%">
			<tr>
				<td>Public<br /> <span style="font-size: 0.85em">Share your profile
						with everyone</span></td>
				<td><input type="checkbox" onclick="togglePublicProfile(this)"
					<? if ($userDetails->isPublic == 1) echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td>Send Emails<br /> <span style="font-size: 0.85em">Allow
						innoworks to send you updates and notes via email</span></td>
				<td><input type="checkbox" onclick="toggleSendEmail(this)"
					<? if ($userDetails->sendEmail == 1) echo "checked"; ?> />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="background-color: #EEE;">Flags <br /> <span
					style="font-size: 0.85em"><? if($userDetails->isAdmin == 1) echo "admin"; ?>
					<? if($userDetails->isExternal == 1) echo "external"; ?>
				</span></td>
			</tr>
		</table>
		<? renderGenericUpdateForm(null ,$userDetails, array("ideaId", "title","userId", "createdTime", "username", "password", "isAdmin", "lastUpdateTime", "isExternal", "isPublic", "sendEmail")); ?>
		<input type="hidden" name="action" value="updateProfile" />
	</form>
</div>
<div
	style="width: 40%; position: relative; float: left; border-top: 1px solid #DDD;">
	<p>
		<b>Attachments</b>
	</p>
	<iframe
		style="width: 100%; height: 20em; border: none; background: #EEE;"
		src="attachment.php"></iframe>
</div>
