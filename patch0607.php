<?
// LeafRSS 0.6 -> 0.7 database patch

// To patch the LeafRSS database from 0.6 to 0.7, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

// check to see if patch has already run
$query = "SELECT * FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='authMethod'";
if ($result = mysql_query($query)){
		if (mysql_num_rows($result)>0){
			echo "<P>ERROR: Patch has already been run!</P>";
			exit;
		}
} else {
	echo "<P>ERROR: ".mysql_error()."</P>";
	exit;
}
// patch routine

// Authentication method
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('authMethod','session','Authentication method','Y');";
doquery($query);

// Alter feeddata
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata ADD `isIgnored` tinyint(1) NOT NULL default '0' AFTER weightedValue";
doquery($query);

function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}
?>