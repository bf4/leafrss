<?
// LeafRSS 0.9 -> 0.9.5 database patch

// To patch the LeafRSS database from 0.8 to 0.9, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

// check to see if patch has already run
$query = "SELECT settingValue FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='version'";
if ($result = mysql_query($query)){
		if (mysql_result($result,0,"settingValue")=="0.9.5"){
			echo "<P>ERROR: Patch has already been run!</P>";
			exit;
		}
} else {
	echo "<P>ERROR: ".mysql_error()."</P>";
	exit;
}
// patch routine

// version control
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "UPDATE `".$DB_TABLE_PREFIX."globalsettings` SET settingValue='0.9.5' WHERE settingName='version';";
doquery($query);
function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}
?>