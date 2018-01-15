<?
// LeafRSS 0.9.5 -> 1.0 database patch

// To patch the LeafRSS database from 0.9.5 to 1.0, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

// check to see if patch has already run
$query = "SELECT settingValue FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='version'";
if ($result = mysql_query($query)){
		if (mysql_result($result,0,"settingValue")=="1.0"){
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
$query = "UPDATE `".$DB_TABLE_PREFIX."globalsettings` SET settingValue='1.0' WHERE settingName='version';";
doquery($query);

// add global setting for automatic ignoring of blank feeds
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('autoIgnoreBlank','0','Ignore blank articles?','Y');";
doquery($query);

// add global setting for feed update notification
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('isUpdating','0','Is the feed updater running?','N');";
doquery($query);

// add global setting for automatic purging of expired feeds
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('autoExpireOnUpdate','0','Purge expired feeds on update??','Y');";
doquery($query);


// patch changes to the config.php file
echo "<P>Patching config.php...";
if ($oldConf = file_get_contents("config.php")){
	$confData = "\n// Full path to LastRSS cache directory\n\$LASTRSS_CACHE_DIR = '".$SMARTY_CACHE_DIR."';\n?>";
	$newConf = str_replace("?>",$confData,$oldConf);
	if ($confFile = fopen("config.php","w")){
		fwrite($confFile,$newConf);
		fclose($confFile);
		echo "Done!</P>";
	} else {
		echo "ERROR: Unable to write to config.php. Please check your directory permissions.";
	}
} else {
	echo "ERROR: Unable to read from config.php. Please check your directory permissions.";
}


function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}


?>