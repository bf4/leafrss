<?
// LeafRSS 0.7 -> 0.8 database patch

// To patch the LeafRSS database from 0.6 to 0.7, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

// check to see if patch has already run
$query = "SELECT * FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='version'";
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
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('version','0.8','Current version of LeafRSS','N');";
doquery($query);

// admin display limit
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('admMaxArticles','25','Max Articles on admin lists','Y');";
doquery($query);

// time zone
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('timeZone','0','Local time zone for system','Y');";
doquery($query);

// date display
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
      ('dateDisplay','l, F jS, Y','Date display formatting','Y');";
doquery($query);

// Alter feeddata - change weighted value field
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata MODIFY `weightedValue` double(6,4) NOT NULL default '0'";
doquery($query);

// Alter feeddata - add guid
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata ADD `guid` varchar(255) NOT NULL AFTER description";
doquery($query);

// Update feeddata - set guid from link for each item
$result = mysql_query("SELECT itemID, link FROM ".$DB_TABLE_PREFIX."feeddata");
while ($row = mysql_fetch_object($result)){
	$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET guid='".$row->link."' WHERE itemID=".$row->itemID);
}

// Alter feeddata - add pubDate
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata ADD `pubDate` int(14) default NULL AFTER guid";
doquery($query);

// Alter feeddata - remove link index
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "DROP INDEX `link` ON `".$DB_TABLE_PREFIX."feeddata`";
doquery($query);

// Alter feeddata - create guid index
echo "<P>Patching ".$DB_TABLE_PREFIX."feeddata...";
$query = "CREATE INDEX `guid` ON `".$DB_TABLE_PREFIX."feeddata` (`guid`)";
doquery($query);

// Alter rssfeeds - add notes
echo "<P>Patching ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds ADD `notes` text default '' AFTER homePageURL";
doquery($query);

// Alter rssfeeds - add isEnabled
echo "<P>Patching ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds ADD `isEnabled` tinyint(1) NOT NULL default '1' AFTER notes";
doquery($query);

// Alter rssfeeds - add isAutoWL
echo "<P>Patching ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds ADD `isAutoWL` tinyint(1) NOT NULL default '0' AFTER isEnabled";
doquery($query);

// Alter rssfeeds - add isAutoIgnored
echo "<P>Patching ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds ADD `isAutoIgnored` tinyint(1) NOT NULL default '0' AFTER isAutoWL";
doquery($query);

function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}
?>