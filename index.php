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
// RSS news feeder project

// version
$LEAF_RSS_VERSION = "1.1";

// if configuration file is not present, LeafRSS has not been installed.
if (!is_file("config.php")){
	header("Location: install.php");
}
// buffer output of script
//ob_start();

// configuration variables not stored in database
require_once("config.php");
if (!$isReady){
	die ("ERROR: You need to finish running the installer before LeafRSS will work.<BR>Please check the README included with this software for more details.");
}
// include lastRSS
require_once($ROOT_DIR."lastRSS/lastRSS.php");
// standard functions
require_once($ROOT_DIR."include/std_func_lib.php");
// database connection
require_once($ROOT_DIR."include/db_inc.php");
// TODO: error checking to make sure all tables are in order (i.e. install.php has been run)

// global settings from database
require_once($ROOT_DIR."include/db_global.php");

// check to make sure version of script and of database match
if ($LEAF_RSS_VERSION <> getSetting("version")) {
	die ("ERROR: Script version and database version don't match! Please make sure that you have run the patches to bring the database up to the latest version.");
} 

// get action from GET array
if (isset($_GET["action"])){
	$action = $_GET["action"];
} else {
	$action="";
}
// authentication routine
Include($ROOT_DIR."include/auth.php");
// smarty setup
Include($ROOT_DIR."include/smarty_func_lib.php");

// if not set to use cron for updating, update feeds if update interval has been met.
if (getSetting("useCron")==0){
	updateFeeds();
}
// feed updating message
switch ($action) {
	case "admin":
		// administrative list
		Include("modules/admin.php");
		break;
	case "admlistacc":
		$smarty->assign("listTitle","Articles that are to be shown that are not on the whitelist");
		// list articles that were accepted by the system
		admListArticle("U");
		break;
	case "admlistden":
		$smarty->assign("listTitle","Articles that are to be denied that are not on the blacklist");
		// list articles that were denied by the system
		admListArticle("U","<=");
		break;
	case "admlistblack":
		$smarty->assign("listTitle","Blacklisted Articles");
		// list blacklisted articles
		admListArticle("B");
		break;
	case "admlistwhite":
		$smarty->assign("listTitle","Whitelisted Articles");
		// list whitelisted articles
		admListArticle("W");
		break;
	case "admlistign":
		// list ignored articles
		admListIgnore();
		break;
	case "admundoacc":
		// undo blacklisting
		undoBayesStatus(getPKID("itemID"),"B");
	case "admacc":
		// take article and whitelist it
		updateBayesStatus(getPKID("itemID"),"W");
		// update bayes value for all nonexpired feeds
		if ($autoBayesScore=="1"){
			updateBayesFeed();
		}
		// administrative list
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "admundoden":
		// undo whitelisting
		undoBayesStatus(getPKID("itemID"),"W");
	case "admden":
		// take article and blacklist it
		updateBayesStatus(getPKID("itemID"),"B");
		// update bayes value for all nonexpired feeds
		if ($autoBayesScore=="1"){
			updateBayesFeed();
		}
		// administrative list
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "admign":
		// take article and ignore it
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET isIgnored=1 WHERE itemID=".getPKID("itemID"));
		// administrative list
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "admignrem":
		// take article and remove from ignore list
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET isIgnored=0 WHERE itemID=".getPKID("itemID"));
		// administrative list
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "admmassupdate":
		// update all checkboxes
		if (!empty($_POST["wlist"])){
  		foreach($_POST["wlist"] as $key=>$value){
				updateBayesStatus($key,"W");
  		}
  	}
  	if (!empty($_POST["blist"])){
  		foreach($_POST["blist"] as $key=>$value){
  			updateBayesStatus($key,"B");
  		}
  	}
  	if (!empty($_POST["ignore"])){
  		foreach($_POST["ignore"] as $key=>$value){
  			$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET isIgnored=1 WHERE itemID=".$key);
  		}
  	}
  	if ($autoBayesScore=="1"){
			updateBayesFeed();
		}
		// administrative list
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "forceupdate":
		updateFeeds(1);
		$smarty->assign("msg","Feeds updated successfully.");
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "forcebayesupdate":
		updateBayesFeed();
		$smarty->assign("msg","Bayes scores updated successfully.");
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "feeddata":
		updateFeeds(1,getPKID("feedID"));
		$smarty->assign("msg","Feed updated successfully.");
	case "feedlist":
	case "feededit":
	case "feedinsert":
	case "feedupdate":
	case "feedremove":
	case "feeddisable":
	case "feedenable":
		// feed editor
		Include($ROOT_DIR."modules/feededitor.php");
		break;
	case "login":
	case "login2":
	case "logout":
		Include($ROOT_DIR."modules/login.php");
		break;
	case "settings":
	case "settingsupdate":
		// global settings editor
		Include($ROOT_DIR."modules/settings.php");
		break;
	case "admpurgeexp":
		purgeExpired();
		$smarty->assign("msg","Purge completed.");
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "admpurgelow":
		purgeLowScore();
		$smarty->assign("msg","Purge completed.");
		Include($ROOT_DIR."modules/admin.php");
		break;
	case "bayesstats":
		Include($ROOT_DIR."modules/bayesstats.php");
		break;
	default:
		// list news items
		Include($ROOT_DIR."modules/main.php");
}

// flush the output buffer and stop buffering
//ob_end_flush();
?>
