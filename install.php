<?php
/*************************************************

LeafRSS- the Learning Filtered RSS Aggregator
Author: Grant Electronics <leafrss@grantelectronics.com>
Copyright (c): 2008 Grant Electronics, all rights reserved

 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU  General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

You may contact the author of LeafRSS by e-mail at:
leafrss@grantelectronics.com

Or, write to:
Robert Wilson
P.O. Box 590
Seward, AK 99664

The latest version of LeafRSS can be obtained from:
http://leafrss.sourceforge.com
*************************************************/
// install.php
// Installer file
?>
<HTML>
<TITLE>LeafRSS installation</TITLE>
<HEAD>
<STYLE>
BODY {
	margin-top: 10px;
	margin-left: 10px;
	margin-right: 10px;
	background-color: #FFF9D0;
	font-size: 10pt;
	font-family: Tahoma;
}
TABLE {
	border: 2px outset;
	padding: 2px;
	background-color: #5E8017;
	color: FFF08C;
}
TD {
	background-color: #71991C;
}
.green {
	color: #8EC023;
	font-weight: bold;
}
.red {
	color: #BF2222;
	font-weight: bold;
}
</STYLE>
<?php
switch($_GET["action"]){
	case "step2":
		// if we are on step 2, then add javascript for form validation
?>
<SCRIPT>
var isValid = true;
var almsg = '';

function validateFrm(){
	frm = document.serverinfo;	
	isValid = true;
	isBlank(frm.ROOT_URL.value, 'root URL');
	isBlank(frm.ROOT_DIR.value, 'root directory');
	isBlank(frm.SMARTY_DIR.value, 'Smarty directory');
	isBlank(frm.DB_SERVER.value, 'MySQL server name');
	isBlank(frm.DB_USER.value, 'MySQL userame');
	isBlank(frm.DB_PASS.value, 'MySQL password');
	isBlank(frm.DB_NAME.value, 'MySQL database name');
	if (frm.DB_PASS.value != frm.MYSQL_PASS2.value){
		isValid = false;
		almsg = "Passwords don't match.\n";
	}	
	if (isValid){
		frm.submit();
	} else {
		alert(almsg);
	}
}

function isBlank(which, desc){
	if (which == ""){
		isValid = false;
		almsg = almsg + "You must enter a " + desc + ".\n";
	}
}
</SCRIPT>	
<?php
	break;
	case "step4":
	// step 4 form validation script
?>
<SCRIPT>
function validateFrm(){
	if (document.configinfo.newpass1.value == document.configinfo.newpass2.value){
		document.configinfo.submit();
	} else {
		alert("Passwords don't match.");
	}
}
</SCRIPT>
<?php
}
?>
<BODY>
<P ALIGN=CENTER><A HREF="http://www.leafrss.com/"><IMG SRC="http://www.leafrss.com/logo.jpg" BORDER=0></A></P>
<?php
// name of this script (used in determining default values)
$myName = "install.php";
$confName = "config.php";

// set error reporting to supress warning messages
error_reporting(E_ERROR | E_PARSE);

// default root directory
$d_ROOT_DIR = str_replace($myName,"",$_SERVER['PATH_TRANSLATED']);

echo "<H2 ALIGN=CENTER>Now installing LeafRSS 1.1</H2>";

switch ($_GET["action"]){
	case "step5":
		$nextStep = true;
		$errmsg = "";
		// Create tables and enter in configuration data
		echo "<H3 ALIGN=CENTER>Step 5 of 5: Final Setup</H3>\n";
		// Test new configuration file
		echo "<P ALIGN=CENTER>Loading configuration file...&nbsp;";
		if (is_file($confName)){
			Include($confName);
  		if (isset($DB_SERVER)){
  			testSuccess();
  		} else {
  			testFail("Unable to parse data from configuration file.");
  		}
  	} else {
  		testFail("Unable to find configuration file '".$confName."'");
		}
		echo "</P>\n";
		// if config loaded ok, load db wrapper
		if ($nextStep){
			Include($ROOT_DIR."include/db_inc.php");
		}
		// check for previous table creation
		echo "<P ALIGN=CENTER>Checking for previous LeafRSS tables...&nbsp;";
		if (isInstalled()==true){
			testFail("It looks like leafRSS was previously installed with these settings.<BR>Please check your database information and make sure the table prefix is unique.");
		} else {
			testSuccess();
		}
		echo "</P>\n";
		// update interval
  	$updateInterval = checkPostData($_POST["updateInterval"],1)*3600;
  	// expiration interval
  	$expirationInterval = checkPostData($_POST["expirationInterval"],24)*3600;
  	// number of columns
  	$numColumns = checkPostData($_POST["numColumns"],1);
  	// site name
  	$siteName = checkPostData($_POST["siteName"],"News");
  	// template
  	$templateName = checkPostData($_POST["templateName"],"default");
  	// keyword threshold
  	$kwThreshold = checkPostData($_POST["kwThreshold"],0.5);
  	// authentication method
  	$authMethod = checkPostData($_POST["authMethod"],"session");
  	// admin username
  	$username = checkPostData($_POST["username"],"admin");
  	// use cron
  	$useCron = checkPostData($_POST["useCron"],0);
  	// max articles
  	$maxArticles = checkPostData($_POST["maxArticles"],10);
  	// max admin articles
  	$admMaxArticles = checkPostData($_POST["admMaxArticles"],25);
  	// time zone offset
  	$timeZone = checkPostData($_POST["timeZone"],0);
  	// date Display format
  	$dateDisplay = checkPostData($_POST["dateDisplay"],"l, F jS, Y");
  	// allow RSS feed
  	$allowRSS = checkPostData($_POST["allowRSS"],1);
  	// copyright info for RSS feed
  	$copyright = checkPostData($_POST["copyright"],"");
  	// admin link on front page
  	$showAdminLink = checkPostData($_POST["showAdminLink"],1);
  	// is site embedded?
  	$isEmbedded = checkPostData($_POST["isEmbedded"],0);
  	// automatically ignore blank articles
  	$autoIgnoreBlank = checkPostData($_POST["autoIgnoreBlank"],0);
  	// automatically ignore duplicate articles
  	$duplicateCheck = checkPostData($_POST["duplicateCheck"],0);
  	// automatically purge expired feeds on update
  	$autoExpireOnUpdate = checkPostData($_POST["autoExpireOnUpdate"],0);
  	// purge whitelisted articles
  	$purgeWLArticles = checkPostData($_POST["purgeWLArticles"],0);
  	// purge blacklisted articles
  	$purgeBLArticles = checkPostData($_POST["purgeBLArticles"],0);
  	// update Bayes Score when whitelisting/blacklisting articles
  	$autoBayesScore = checkPostData($_POST["autoBayesScore"],1);
  	// purge ignored articles
  	$purgeIgnored = checkPostData($_POST["purgeIgnored"],0);
  	// purge low arnk articles
  	$purgeLowScore = checkPostData($_POST["purgeLowScore"],0);
  	// low arnk purge threshold
  	$purgeLSThres = checkPostData($_POST["purgeLSThres"],0);
  	// use Bayes threshold for low rank
  	$purgeUseBayes = checkPostData($_POST["purgeUseBayes"],0);
  	// Bayes threshold
  	$bayesThreshold = checkPostData($_POST["bayesThreshold"],"0.25");
  	// create tables and fill globalsettings table with default values
  	if ($nextStep){
      echo "<P ALIGN=CENTER>Creating table ".$DB_TABLE_PREFIX."feeddata...";
      $query = "CREATE TABLE `".$DB_TABLE_PREFIX."feeddata` (
        `itemID` int(11) NOT NULL auto_increment,
        `link` varchar(255) NOT NULL default '',
        `title` varchar(255) NOT NULL default '',
        `author` varchar(255) default NULL,
        `description` text NOT NULL,
        `guid` varchar(255) NOT NULL,
        `pubDate` int(14) unsigned default NULL,
        `feedID` int(11) NOT NULL default '0',
        `timestamp` int(14) unsigned zerofill NOT NULL default '00000000000000',
        `imageURL` varchar(128) NOT NULL default '',
        `weightedValue` double(6,4) NOT NULL default '0.0000',
        `isIgnored` tinyint(1) NOT NULL default '0',
        `bayesValue` DOUBLE(14,13) NOT NULL DEFAULT '0.0000000000000',
  			`bayesStatus` ENUM('W','B','U') NOT NULL DEFAULT 'U',
        PRIMARY KEY  (`itemID`),
        KEY `timestamp` (`timestamp`),
				KEY `bayesValue` (`bayesValue`),
				KEY `bayesStatus` (`bayesStatus`),
				KEY `isIgnored` (`isIgnored`),
				KEY `admList` (`bayesStatus`,`bayesValue`,`timestamp`,`feedID`,`isIgnored`),
				KEY `feedUpdateDuplicateCheck` (`title`,`description`(1)),
				KEY `link` (`link`)
      ) TYPE=MyISAM;";
      doquery($query);
    }
    if ($nextStep){
      echo "<P ALIGN=CENTER>Creating table ".$DB_TABLE_PREFIX."globalsettings...";
      $query = "CREATE TABLE `".$DB_TABLE_PREFIX."globalsettings` (
        `settingName` varchar(128) NOT NULL default '',
        `settingValue` varchar(255) NOT NULL default '0',
        `Description` varchar(32) NOT NULL default '',
        `isEditable` enum('Y','N') default 'Y',
        PRIMARY KEY  (`settingName`)
      ) TYPE=MyISAM;";
      doquery($query);
    }
    if ($nextStep){
  		echo "<P ALIGN=CENTER>Creating table ".$DB_TABLE_PREFIX."rssfeeds...";
      $query = "CREATE TABLE `".$DB_TABLE_PREFIX."rssfeeds` (
        `feedID` int(11) NOT NULL auto_increment,
        `URL` varchar(128) NOT NULL default '',
        `Description` varchar(128) NOT NULL default '',
        `homePageURL` varchar(128) default NULL,
        `notes` text default '',
        `isEnabled` tinyint(1) NOT NULL default '1',
        `isAutoWL` tinyint(1) NOT NULL default '0',
        `isAutoIgnored` tinyint(1) NOT NULL default '0',
        PRIMARY KEY  (`feedID`),
        KEY `isAutoIgnored` (`isAutoIgnored`),
				KEY `isAutoWL` (`isAutoWL`),
				KEY `feedUpdate1` (`isEnabled`)
      ) TYPE=MyISAM;";
      doquery($query);
    }
		if ($nextStep){
			echo "<P ALIGN=CENTER>Creating table ".$DB_TABLE_PREFIX."bayes_keys...";
			$query = "CREATE TABLE `".$DB_TABLE_PREFIX."bayes_keys` (
  			`keyword` VARCHAR(64) NOT NULL DEFAULT '',
  			`wlCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0',
  			`blCount` INTEGER(11) UNSIGNED NOT NULL DEFAULT '0',
  			PRIMARY KEY (`keyword`)
				) TYPE=MyISAM;";
      doquery($query);
		}
    if ($nextStep){
      echo "<P ALIGN=CENTER>Updating global settings...";
      $query = "INSERT INTO `".$DB_TABLE_PREFIX."globalsettings` (`settingName`, `settingValue`, `Description`, `isEditable`) VALUES
        ('lastUpdate','','Last time feeds were updated','N'),
        ('updateInterval','".$updateInterval."','Update interval (in seconds)','Y'),
        ('expirationInterval','".$expirationInterval."','Expiration interval (in seconds)','Y'),
        ('numColumns','".$numColumns."','Number of columns to display','Y'),
        ('kwThreshold','".$kwThreshold."','Keyword Threshold','Y'),
        ('siteName','".$siteName."','Title of site','Y'),
        ('templateName','".$templateName."','Template name','Y'),
        ('authMethod','".$authMethod."','Authentication Method','Y'),
  			('username','".$username."','Administration User','Y'),
  			('password','".MD5($_POST['newpass1'])."','Administration Password','Y'),
  			('useCron','".$useCron."','Use cron for updating?','Y'),
  			('maxArticles','".$maxArticles."','Maximum number of articles to display','Y'),
  			('admMaxArticles','".$admMaxArticles."','Maximum articles on admin lists','Y'),
  			('version','1.1','Current version of LeafRSS','N'),
  			('timeZone','".$timeZone."','Local time zone for system','Y'),
  			('dateDisplay','".$dateDisplay."','Date display formatting','Y'),
  			('allowRSS','".$allowRSS."','Parse results into an RSS feed?','Y'),
  			('copyright','".$copyright."','Copyright info for RSS feed','Y'),
  			('isEmbedded','".$isEmbedded."','Is LeafRSS embedded?','Y'),
  			('showAdminLink','".$showAdminLink."','Show Admin link on front page?','Y'),
  			('autoIgnoreBlank','".$autoIgnoreBlank."','Ignore blank articles?','Y'),
  			('isUpdating','0','Is the feed updater running?','N'),
  			('autoExpireOnUpdate','".$autoExpireOnUpdate."','Purge expired feeds on update?','Y'),
				('wlCount','0','Total number of whitelisted','Y'),
				('blCount','0','Total number of whitelisted','Y'),
				('bayesThreshold','".$bayesThreshold."','Bayes Display Threshold','Y'),
				('duplicateCheck','".$duplicateCheck."','Check for duplicate articles?','Y'),
				('purgeWLArticles','".$purgeWLArticles."','Purge whitelisted articles?','Y'),
				('purgeBLArticles','".$purgeBLArticles."','Purge blacklisted articles?','Y'),
				('autoBayesScore','".$autoBayesUpdate."','Recalculate Bayes score?','Y'),
				('lastBayesUpdate','','Last time Bayes scores updated','N'),
				('purgeIgnored','".$purgeIgnored."','Purge Ignored articles?','Y'),
				('purgeLowScore','".$purgeLowScore."','Purge low scoring articles?','Y'),
				('purgeLSThres','".$purgeLSThres."','Low score purge threshold','Y'),
				('purgeUseBayes','".$purgeUseBayes."','Use Bayes for low score?','Y');";
			doquery($query);
		}
    if ($nextStep){
    	// finalize installation
    	echo "<P ALIGN=CENTER>Finalizing installation...&nbsp;";
    	// open configuration file for append
			if($confFile = fopen($ROOT_DIR.$confName,"a")){
  			$confData = "\$isReady = true;\nInclude(\$ROOT_DIR.\"include/access.php\");\n?>";
    		if(fwrite($confFile,$confData)){
    			testSuccess();
    		} else {
    			testFail("Unable to add data to configuration file. Please make sure that write access is enabled for ".$ROOT_DIR.".");
    		}
  		} else {
  			testFail("Unable to open to configuration file. Please make sure that write access is enabled for '".$ROOT_DIR."'.");
  		}
  		echo "</P>\n";
    }
    if ($nextStep){
    	echo "<P ALIGN=CENTER>Install is successful! <A HREF=\"index.php\">Go to LeafRSS</A></P>\n";
    } else {
			echo "<P ALIGN=CENTER>".$errmsg."</P>\n";
		}
  	break;
	case "step4":
		echo "<H3 ALIGN=CENTER>Step 4 of 5: LeafRSS Configuration Information</H3>\n";
    echo "<FORM ACTION=\"".$_SERVER["SCRIPT_NAME"]."?action=step5\" METHOD=POST NAME=\"configinfo\">\n";
    echo "<TABLE CELLPADDING=2 CELLSPACING=0 WIDTH=80% ALIGN=CENTER>\n";
    echo "<TR><TH COLSPAN=2>Feed updating and expiration</TH></TR>\n";
    // use cron for updates
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Use cron to update feeds?</TD><TD><SELECT NAME=\"useCron\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD>\n</TR>\n";
    // update interval
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Feed Update Interval:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"updateInterval\" VALUE=\"1\" SIZE=5 MAXLENGTH=5> hour(s)</TD>\n</TR>\n";
    // expiration interval
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Article Expiration Interval:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"expirationInterval\" VALUE=\"24\" SIZE=5 MAXLENGTH=5> hour(s)</TD>\n</TR>\n";
    // automatic purging of expired feeds
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Automatically purge expired articles on update?</TD><TD WIDTH=50%><SELECT NAME=\"autoExpireOnUpdate\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// automatic purge of whitelisted articles
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Remove expired whitelisted articles on purge?</TD><TD WIDTH=50%><SELECT NAME=\"purgeWLArticles\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// automatic purge of blacklisted articles
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Remove expired blacklisted articles on purge?</TD><TD WIDTH=50%><SELECT NAME=\"purgeBLArticles\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// automatic purge of ignored articles
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Remove expired articles articles on purge?</TD><TD WIDTH=50%><SELECT NAME=\"purgeIgnoed\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// automatic purge of low ranking articles
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Remove low ranking articles on purge?</TD><TD WIDTH=50%><SELECT NAME=\"purgeLowScore\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// use Bayes threshold for low ranking threshold
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Use Bayes threshold for low rank purge?</TD><TD WIDTH=50%><SELECT NAME=\"purgeUseBayes\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// Low rank purge threshold
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Low Rank Purge Threshold:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"purgeLSThres\" VALUE=\"0\" SIZE=10 MAXLENGTH=10></TD>\n</TR>\n";
		// automatic ignoring of blank articles
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Automatically ignore articles without a description?</TD><TD WIDTH=50%><SELECT NAME=\"autoIgnoreBlank\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// automatic ignoring duplicate articles
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Automatically ignore duplicate articles?</TD><TD WIDTH=50%><SELECT NAME=\"duplicateCheck\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD></TR>\n";
		// Update Bayes on whitelist/blacklist
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Update Bayes score when whitelisting/blacklisting articles?</TD><TD WIDTH=50%><SELECT NAME=\"autoBayesScore\"><OPTION VALUE=\"0\">No</OPTION><OPTION VALUE=\"1\" SELECTED>Yes</OPTION></SELECT></TD></TR>\n";
		echo "<TR><TH COLSPAN=2>Display Settings</TH></TR>\n";
    // number of columns
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Number of columns:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"numColumns\" VALUE=\"1\" SIZE=5 MAXLENGTH=5></TD>\n</TR>\n";
    // maximum number of articles
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Maximum number of articles to display:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"maxArticles\" VALUE=\"10\" SIZE=5 MAXLENGTH=5></TD>\n</TR>\n";
		// maximum number of articles on admin
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Maximum number of articles to display on admin tools:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"admMaxArticles\" VALUE=\"25\" SIZE=5 MAXLENGTH=5></TD>\n</TR>\n";
		// site name
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Site Title:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"siteName\" VALUE=\"News\" SIZE=32 MAXLENGTH=64></TD>\n</TR>\n";
    // template selection
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Site Template:</TD><TD WIDTH=50%>".selectTemplate("default")."</TD>\n</TR>\n";
    // is site embedded
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Is site embedded in another page?</TD><TD WIDTH=50%><SELECT NAME=\"isEmbedded\"><OPTION VALUE=\"0\" SELECTED>No</OPTION><OPTION VALUE=\"1\">Yes</OPTION></SELECT></TD>\n</TR>\n";
		// time zone
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Time Zone Offset:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"timeZone\" VALUE=\"0\" SIZE=5 MAXLENGTH=5></TD>\n</TR>\n";
		echo "<TR>\n<TD ALIGN=CENTER COLSPAN=2 CLASS=\"author\">NOTE: Offset is from GMT</TD></TR>\n";echo "<TR><TH COLSPAN=2>Filter Settings</TH></TR>\n";
    // date display
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Date Display Format:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"dateDisplay\" VALUE=\"l, F jS, Y\" SIZE=32 MAXLENGTH=64></TD>\n</TR>\n";
		echo "<TR>\n<TD ALIGN=CENTER COLSPAN=2 CLASS=\"author\">NOTE: Date formatting is for the PHP <A HREF=\"http://us.php.net/manual/en/function.date.php\" TARGET=blank>date()</A>) command</TD></TR>\n";
		// show admin link
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Show Admin link on front page?</TD><TD WIDTH=50%><SELECT NAME=\"showAdminLink\"><OPTION VALUE=\"0\">No</OPTION><OPTION VALUE=\"1\" SELECTED>Yes</OPTION></SELECT></TD>\n</TR>\n";
		echo "<TR>\n<TH COLSPAN=2>RSS Output</TH></TR>\n";
		// allow RSS
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Allow RSS feed output?</TD><TD WIDTH=50%><SELECT NAME=\"allowRSS\"><OPTION VALUE=\"0\">No</OPTION><OPTION VALUE=\"1\" SELECTED>Yes</OPTION></SELECT></TD>\n</TR>\n";
		// copyright info for RSS feed
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>RSS Copyright Information:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"copyright\" VALUE=\"\" SIZE=64 MAXLENGTH=255></TD>\n</TR>\n";
		echo "<TR>\n<TH COLSPAN=2>Filter Settings</TH></TR>\n";
		// Bayes threshold
		echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Bayes threshold:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"bayesThreshold\" VALUE=\"0.25\" SIZE=10 MAXLENGTH=10></TD>\n</TR>\n";
		echo "<TR><TH COLSPAN=2>Administrative Authentication</TH></TR>\n";
    // administrative authentication method
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Authentication Method:</TD><TD WIDTH=50%><SELECT NAME=\"authMethod\">";
    echo "<OPTION VALUE=\"session\" SELECTED>Session based</OPTION>\n";
    echo "<OPTION VALUE=\"www\">Browser based</OPTION>\n";
    echo "</SELECT></TD>\n</TR>\n";
    // admin username
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Administrative user:</TD><TD WIDTH=50%><INPUT TYPE=TEXT NAME=\"username\" VALUE=\"admin\" SIZE=10 MAXLENGTH=10></TD>\n</TR>\n";
    // new admin password
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>New password:</TD><TD WIDTH=50%><INPUT TYPE=PASSWORD NAME=\"newpass1\" VALUE=\"\" SIZE=10 MAXLENGTH=10></TD>\n</TR>\n";
    // confirm new admin password
    echo "<TR>\n<TD ALIGN=RIGHT WIDTH=50%>Confirm new password:</TD><TD WIDTH=50%><INPUT TYPE=PASSWORD NAME=\"newpass2\" VALUE=\"\" SIZE=10 MAXLENGTH=10></TD>\n</TR>\n";
    echo "</TABLE></FORM>\n";
    echo "<P ALIGN=CENTER><INPUT TYPE=BUTTON VALUE=\"Next >\" onClick=\"javascript: validateFrm();\"></P>\n";
    break;
	case "step3":
		// Step 3. Create config file.
		$nextStep = true;
		$errmsg = "";
		echo "<H3 ALIGN=CENTER>Step 3 of 5: Create Configuration File</H3>\n";
		// confirm Smarty is installed in given location
		echo "<P ALIGN=CENTER>Confirming Smarty location...&nbsp;";
		if (is_file($_POST["SMARTY_DIR"]."Smarty.class.php")){
			testSuccess();
		} else {
			testFail("Smarty.class.php was not found in '".$_POST["SMARTY_DIR"]."'. Please double-check the Smarty path.");
		}
		echo "</P>\n";
		// confirm that cache, config, and compile directories are writable
		echo "<P ALIGN=CENTER>Testing cache directory...&nbsp;";
		$SMARTY_CACHE_DIR = $_POST["ROOT_DIR"]."cache";
		if (!is_dir($SMARTY_CACHE_DIR)){
    	if (!mkdir($SMARTY_CACHE_DIR, 0755)){
    		testFail("Cannot create Smarty cache directory. Please check the folder permissions.");
    	} else {
    		testSuccess();
    	}
    } else if(touch($SMARTY_CACHE_DIR."pertest")){
			testSuccess();
			unlink($SMARTY_CACHE_DIR."pertest");
		} else {
			testFail("The directory '".$SMARTY_CACHE_DIR."' must be writable by the web server. Please check the folder permissions.");
		}
		echo "</P>\n";
		echo "<P ALIGN=CENTER>Testing Smarty config directory...&nbsp;";
		$SMARTY_CONFIG_DIR = $_POST["ROOT_DIR"]."config";
		if (!is_dir($SMARTY_CONFIG_DIR)){
    	if (!mkdir($SMARTY_CONFIG_DIR, 0755)){
    		testFail("Cannot create Smarty cache directory. Please check the folder permissions.");
    	} else {
    		testSuccess();
    	}
    } else {
    	testSuccess();
    }
    echo "</P>\n";
    echo "<P ALIGN=CENTER>Testing Smarty compile directory...&nbsp;";
		$SMARTY_COMPILE_DIR = $_POST["ROOT_DIR"]."smarty_c";
		if (!is_dir($SMARTY_COMPILE_DIR)){
    	if (!mkdir($SMARTY_COMPILE_DIR, 0755)){
    		testFail("Cannot create Smarty compile directory. Please check the folder permissions.");
    	} else {
    		testSuccess();
    	}
    } else if(touch($SMARTY_COMPILE_DIR."pertest")){
			testSuccess();
			unlink($SMARTY_COMPILE_DIR."pertest");
		} else {
			testFail("The directory '".$SMARTY_COMPILE_DIR."' must be writable by the web server. Please check the folder permissions.");
		}
		echo "</P>\n";
		// test MySQL connectivity
		echo "<P ALIGN=CENTER>Testing MySQL connection...&nbsp;";
		if (mysql_connect($_POST["DB_SERVER"],$_POST["DB_USER"],$_POST["DB_PASS"])){
			testSuccess();
			$testDB = true;
		} else {
			testFail("Unable to find MySQL server, or bad MySQL username and password.");
			$testDB = false;
		}
		// test for existence of specifed database
		if ($testDB){
			echo "<P ALIGN=CENTER>Finding MySQL database...&nbsp;";
  		if (mysql_select_DB($_POST["DB_NAME"])){
  			testSuccess();
  		} else {
  			testFail("Unable to find MySQL database on the server.");
  		}
  	}
  	if ($nextStep){
  		// attempt to create configuration file and open it for writing
  		echo "<P ALIGN=CENTER>Creating ".$confName."...&nbsp;";
  		$ROOT_DIR = $_POST["ROOT_DIR"];
			if($confFile = fopen($ROOT_DIR.$confName,"w")){
  			testSuccess();
  		} else {
  			testFail("Unable to write configuration file. Please make sure that write access is enabled for '".$ROOT_DIR."'.");
  		}
  		echo "</P>\n";
  		if ($nextStep){
    		// attempt to write to configuration file
    		$confData = "<?php\n"
    			."// ".$confName." - LeafRSS configuration file automatically generated by install script\n"
    			."\$ROOT_URL = \"".$_POST["ROOT_URL"]."\";\n"
    			."\$ROOT_DIR = \"".$_POST["ROOT_DIR"]."\";\n"
    			."\$SMARTY_DIR = \"".$_POST["SMARTY_DIR"]."\";\n"
    			."\$SMARTY_CACHE_DIR = \"".$SMARTY_CACHE_DIR."\";\n"
    			."\$SMARTY_CONFIG_DIR = \"".$SMARTY_CONFIG_DIR."\";\n"
    			."\$SMARTY_COMPILE_DIR = \"".$SMARTY_COMPILE_DIR."\";\n"
    			."\$LASTRSS_CACHE_DIR = \"".$SMARTY_CACHE_DIR."\";\n"
    			."\$DB_USER = \"".$_POST["DB_USER"]."\";\n"
  				."\$DB_PASS = \"".$_POST["DB_PASS"]."\";\n"
  				."\$DB_SERVER = \"".$_POST["DB_SERVER"]."\";\n"
  				."\$DB_NAME = \"".$_POST["DB_NAME"]."\";\n"
  				."\$DB_TABLE_PREFIX = \"".$_POST["DB_TABLE_PREFIX"]."\";\n";
    		echo "<P ALIGN=CENTER>Writing configuration data...&nbsp;";
    		if(fwrite($confFile,$confData)){
    			testSuccess();
    		} else {
    			testFail("Unable to add data to configuration file. Please make sure that write access is enabled for ".$ROOT_DIR.".");
    		}
    		echo "</P>\n";
  			fclose($confFile);
  		}
  	}
		if ($nextStep){
			echo "<P ALIGN=CENTER><INPUT TYPE=BUTTON VALUE=\"Next >\" onClick=\"javascript: location.href='".$_SERVER["SCRIPT_NAME"]."?action=step4'\"></P>\n";
		} else {
			echo "<P ALIGN=CENTER>".$errmsg."</P>\n";
		}
		break;	
	case "step2":
  	// Step 2: Ask for basic server information
  	echo "<H3 ALIGN=CENTER>Step 2 of 5: Server Information</H3>\n";
  	echo "<P ALIGN=CENTER>Please enter in the following information:</P>\n";
  	echo "<FORM METHOD=POST NAME=\"serverinfo\" ACTION=\"".$_SERVER["SCRIPT_NAME"]."?action=step3\">\n";
  	echo "<TABLE ALIGN=CENTER WIDTH=\"50%\" BORDER=\"1\" CELLSPACING=\"0\" CELLPADDING=\"2\">\n";
  	echo "<TR><TH COLSPAN=2>Path Information</TH></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>Root URL:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=48 NAME=\"ROOT_URL\" VALUE=\"http://".$_SERVER['SERVER_NAME'].str_replace($myName,"",$_SERVER['SCRIPT_NAME'])."\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT>Root Directory of LeafRSS install:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=48 NAME=\"ROOT_DIR\" VALUE=\"".$d_ROOT_DIR."\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT>Full path to Smarty Directory:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=48 NAME=\"SMARTY_DIR\" VALUE=\"".$d_ROOT_DIR."Smarty/libs/\"></TD></TR>\n";
  	echo "</TABLE>\n";
  	echo "<TABLE ALIGN=CENTER WIDTH=\"50%\" BORDER=\"1\" CELLSPACING=\"0\" CELLPADDING=\"2\">\n";
  	echo "<TR><TH COLSPAN=2>Database Information</TH></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>MySQL Server Name:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=32 NAME=\"DB_SERVER\" VALUE=\"localhost\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>MySQL Username:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=32 NAME=\"DB_USER\" VALUE=\"\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>MySQL Password:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=PASSWORD SIZE=32 NAME=\"DB_PASS\" VALUE=\"\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>Confirm MySQL Password:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=PASSWORD SIZE=32 NAME=\"MYSQL_PASS2\" VALUE=\"\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>Database name:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=32 NAME=\"DB_NAME\" VALUE=\"\"></TD></TR>\n";
  	echo "<TR><TD ALIGN=RIGHT WIDTH=50%>Database table prefix:</TD>";
  	echo "<TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=32 NAME=\"DB_TABLE_PREFIX\" VALUE=\"leafrss\"></TD></TR>\n";
  	echo "</TABLE></FORM>\n";
  	echo "<P ALIGN=CENTER><INPUT TYPE=BUTTON VALUE=\"Next >\" onClick=\"javascript: validateFrm();\"></P>\n";
  	break;
	default:
		// initial checks
		$nextStep = true;
		$errmsg = "";
		echo "<H3 ALIGN=CENTER>Step 1 of 5: System Check</H3>\n";
		// check to see if MySQL is installed
		echo "<P ALIGN=CENTER>Checking for MySQL...&nbsp;";
		if (function_exists("mysql_connect")){
			testSuccess();
		} else {
			testFail("You must have the PHP module for MySQL installed. Please make sure the PHP module is installed in your current configuration<BR>");
		}
		echo "</P>\n";
		// check to see if web server has write access to install directory
		echo "<P ALIGN=CENTER>Checking permissions on install directory...&nbsp;";
		if (touch($d_ROOT_DIR."pertest")){
			testSuccess();
			unlink($d_ROOT_DIR."pertest");
		} else {
			testFail("The directory '".$d_ROOT_DIR."' where you extracted LeafRSS must be writable by the web server. Please check the folder permissions.");
		}
		echo "</P>\n";
		// check to see if configuration file already exists and if this script has been run before
		echo "<P ALIGN=CENTER>Checking for previous installation...&nbsp;";
		if (is_file($d_ROOT_DIR.$confName)){
			$isReady = false;
			// test for isReady
			$arrConf = file($d_ROOT_DIR.$confName);
			foreach($arrConf as $line){
				if (strpos($line,"$isReady = true;")){
					$isReady = true;
				}
			}
			if ($isReady){
  			testFail("It looks like LeafRSS is already installed, or you are upgrading from a previous version. If you are upgrading, please read the PATCH file included with this install for instructions on upgrading LeafRSS.");
  		} else {
  			testSuccess();
  		}
		} else {
			testSuccess();
		}
		if ($nextStep){
			echo "<P ALIGN=CENTER><INPUT TYPE=BUTTON VALUE=\"Next >\" onClick=\"javascript: location.href='".$_SERVER["SCRIPT_NAME"]."?action=step2'\"></P>\n";
		} else {
			echo "<P ALIGN=CENTER>".$errmsg."</P>\n";
		}
}

function testFail($msg){
	global $errmsg, $nextStep;
	$nextStep = false;
	$errmsg .= $msg;
	echo "<SPAN CLASS=\"red\">Failed!</SPAN>";
}

function testSuccess(){
	echo "<SPAN CLASS=\"green\">Success</SPAN>";
}

function doquery($query){
	if (mysql_query($query)){
		testSuccess();
	} else {
		testFail(mysql_error());
	}
	echo "</P>\n";
}

function selectTemplate($templateName){
	global $ROOT_DIR;
	$dir = $ROOT_DIR."template/";
	$output = "<SELECT NAME=\"templateName\">\n";
  $d = dir($dir);
  while (false !== ($entry = $d->read())) {
    if (($entry != ".")&&($entry != "..")&&(is_dir($dir.$entry))){
     	$output .= "<OPTION VALUE=\"".$entry."\"";
     	$output .= ($entry == $templateName) ? " SELECTED" : "";
     	$output .= ">".$entry."</OPTION>\n";
    }
  }
  $d->close();
  $output .= "</SELECT>\n";
  return $output;
}

function checkPostData($value,$default){
	$output = ($value == "") ? $default : $value;
	return $output;
}

function isInstalled(){
	global $DB_NAME;
	global $DB_TABLE_PREFIX;
	$tables = mysql_list_tables($DB_NAME); 
	$num_tables = @mysql_numrows($tables); 
	$i = 0; 
	$exist = false; 
  while($i < $num_tables) 
  { 
  	$tablename = mysql_tablename($tables, $i); 
  	if ($tablename==($DB_TABLE_PREFIX.'globalsettings')) $exist=true; 
  	$i++; 
  }
  return $exist;
}	
?>
</BODY></HTML>