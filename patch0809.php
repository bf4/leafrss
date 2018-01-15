<?
// LeafRSS 0.8 -> 0.9 database patch

// To patch the LeafRSS database from 0.8 to 0.9, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

// check to see if patch has already run
$query = "SELECT * FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='allowRSS'";
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

// version control
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "UPDATE `".$DB_TABLE_PREFIX."globalsettings` SET settingValue='0.9' WHERE settingName='version';";
doquery($query);

// RSS feed toggle
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('allowRSS','1','Parse results for an RSS feed?','Y');";
doquery($query);

// copyright for RSS
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('copyright','','Copyright info for RSS feed','Y');";
doquery($query);

// show admin link
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('showAdminLink','1','Show Admin link on front page?','Y');";
doquery($query);

// is site embedded
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('isEmbedded','0','Is LeafRSS embedded?','Y');";
doquery($query);
function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}
?>