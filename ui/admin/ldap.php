<?php
require_once("thinConnector.php");
$queryObj;
if (isset($_POST['query'])) {
	echo '<p>' . htmlspecialchars($_POST['query']) . '</p>';
	//global $ldapUser, $ldapPass, $ldapPort, $ldapFullUrl;	
	$connection = ldap_connect($ldapFullUrl);
	//ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3); //OPTIONAL DEPENDING ON VERSION
	logDebug("Trying LDAP BIND");
	if (ldap_bind ($connection, $ldapUser, $ldapPass)) {
		logDebug("LDAP Searching for query");
		/* Search for the user */
		$search_result = ldap_search ($connection, "o=uts", $_POST['query']); //(utsIdNumber=$user)

		/* Check the result and the number of entries returned */
		if ($search_result && (ldap_count_entries ($connection, $search_result) > 0))
		{
			print_r($search_result);
			echo "<br/>";
			$entries = ldap_get_entries($connection,$search_result);
			foreach ($entries as $entry) {
				echo $entry['utsmail'][0] . " " . $entry['utsidnumber'][0] . " " . $entry['utsaccountstatus'][0] . "<br/>";
				print_r($entry);	
			}
		} else {
			echo "LDAP Found nothing";
		}
	}
	@ldap_unbind($connection);
	@ldap_close($connection);
}
?>
<html>
<head>
<? require_once("head.php"); ?>
</head>
<body>
<h2>LDAP search</h2>
<p style="font-style:italic;">LDAP query example: (&(utsaccountstatus=ACTIVE)(utsidnumber=00000000))<p>
<form method="post" action="./ldap.php">
<input name="query" value="<?= htmlspecialchars($_POST['query'])?>" style="width:100%"/>
</form>
<hr/>
</body>
</html>