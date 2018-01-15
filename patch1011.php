<?
// LeafRSS 1.0 -> 1.1 database patch

// To patch the LeafRSS database from 0.9.5 to 1.0, place this file in the root
// directory of your LeafRSS installation and run this patch via your browser.

// configuration variables not stored in database
require_once("config.php");

// database connection
require_once($ROOT_DIR."include/db_inc.php");

require_once($ROOT_DIR."include/std_func_lib.php");

// check to see if patch has already run
$query = "SELECT settingValue FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='version'";
if ($result = mysql_query($query)){
		if (mysql_result($result,0,"settingValue")=="1.1"){
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
$query = "UPDATE `".$DB_TABLE_PREFIX."globalsettings` SET settingValue='1.1' WHERE settingName='version';";
doquery($query);

// add global setting for bayes whitelist count
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('wlCount','0','Total number of whitelisted','Y');";
doquery($query);

// add global setting for bayes blacklist count
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('blCount','0','Total number of whitelisted','Y');";
doquery($query);

// add global setting for bayes threshold
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('bayesThreshold','0.25','Bayes Display Threshold','Y');";
doquery($query);

// add global setting for duplicate checking switch
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('duplicateCheck','1','Check for duplicate articles?','Y');";
doquery($query);

// add global setting for whitelist purge
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeWLArticles','0','Purge whitelisted articles?','Y');";
doquery($query);

// add global setting for blacklist purge
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeBLArticles','0','Purge blacklisted articles?','Y');";
doquery($query);

// add global setting for automatic Bayes update
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('autoBayesScore','1','Recaculate Bayes score?','Y');";
doquery($query);

// add global setting for last Bayes update
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('lastBayesUpdate','0','Last time Bayes scores updated','N');";
doquery($query);

// add global setting for ignored article purge
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeIgnored','0','Purge Ignored articles?','Y');";
doquery($query);

// add global setting for low score article purge
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeLowScore','0','Purge low scoring articles?','Y');";
doquery($query);

// add global setting for low score article purge threshold
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeLSThres','0','Low score purge threshold','Y');";
doquery($query);

// add global setting for low score article purge bayes
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` VALUES ('purgeUseBayes','0','Use Bayes for low score?','Y');";
doquery($query);


// remove global setting for keyword threshold
echo "<P>Patching ".$DB_TABLE_PREFIX."globalsettings...";
$query = "DELETE FROM `".$DB_TABLE_PREFIX."globalsettings` WHERE settingName='kwThreshold';";
doquery($query);

// add bayes_keys table
echo "<P>Adding bayes_keys table...";
$query = "CREATE TABLE `".$DB_TABLE_PREFIX."bayes_keys` (
  					`keyword` VARCHAR(64) NOT NULL DEFAULT '',
  					`wlCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0',
  					`blCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0',
  					PRIMARY KEY (`keyword`)
					) TYPE=MyISAM;";
doquery($query);

// add bayes columns to feeddata table
echo "<P>Adding bayes columns to ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata
						ADD `bayesValue` DOUBLE(14,13) NOT NULL DEFAULT '0.0000000000000' AFTER `isIgnored`,
  					ADD `bayesStatus` ENUM('W','B','U') NOT NULL DEFAULT 'U' AFTER `bayesValue`;";
doquery($query);

// update indexes on feeddata table
echo "<P>Updating indexes on ".$DB_TABLE_PREFIX."feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata 
						ADD KEY `timestamp` (`timestamp`),
						ADD KEY `bayesValue` (`bayesValue`),
						ADD KEY `bayesStatus` (`bayesStatus`),
						ADD KEY `isIgnored` (`isIgnored`),
						ADD KEY `admList` (`bayesStatus`,`bayesValue`,`timestamp`,`feedID`,`isIgnored`),
						ADD KEY `feedUpdateDuplicateCheck` (`title`,`description`(1)),
						ADD KEY `link` (`link`),
						DROP KEY `guid`;";
doquery($query);

// update indexes on rssfeeds table
echo "<P>Updating indexes on ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds
						ADD KEY `isAutoIgnored` (`isAutoIgnored`),
						ADD KEY `isAutoWL` (`isAutoWL`),
						ADD KEY `feedUpdate1` (`isEnabled`);";
doquery($query);

// add bayes counts to rssfeeds
echo "<P>Adding bayes counts to ".$DB_TABLE_PREFIX."rssfeeds...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."rssfeeds
						ADD `wlCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `isAutoIgnored`,
  					ADD `blCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `wlCount`;";
doquery($query);

// gather all whitelisted feeds in database and re-whitelist them using new system
echo "<P>Generating Bayesian data from whitelisted articles...";
$query = "SELECT itemID FROM ".$DB_TABLE_PREFIX."feeddata WHERE weightedValue=11";
$result = mysql_query($query);
if ($result){
	while ($row = mysql_fetch_object($result)){
		updateBayesStatus($row->itemID,"W");
	}
	echo "Done!</P>";
} else {
	echo "ERROR: ".mysql_error()."</P>";
}

// gather all blacklisted feeds in database and re-blacklist them using new system
echo "<P>Generating Bayesian data from blacklisted articles...";
$query = "SELECT itemID FROM ".$DB_TABLE_PREFIX."feeddata WHERE weightedValue=-11";
$result = mysql_query($query);
if ($result){
	while ($row = mysql_fetch_object($result)){
		updateBayesStatus($row->itemID,"B");
	}
	echo "Done!</P>";
} else {
	echo "ERROR: ".mysql_error()."</P>";
}

// remove weightedValue column from feeddata
echo "Removing old weighted value from feeddata...";
$query = "ALTER TABLE ".$DB_TABLE_PREFIX."feeddata DROP `weightedValue`;";
doquery($query);

// drop table keyweight
echo "Removing old keyweight table...";
$query = "DROP TABLE ".$DB_TABLE_PREFIX."keyweight;";
doquery($query);

// drop table keywords
echo "Removing old keywords table...";
$query = "DROP TABLE ".$DB_TABLE_PREFIX."keywords;";
doquery($query);

function doquery($query){
	if (mysql_query($query)){
		echo "Done!</P>";
	} else {
		echo "ERROR: ".mysql_error()."</P>";
	}
}


?>