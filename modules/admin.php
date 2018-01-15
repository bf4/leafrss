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
// admin module

// access check
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}

// Administrative menu
$smarty->assign("lastUpdate",strftime("%a, %b %e, %G %I:%M %p",(int)getSetting("lastUpdate")));
$smarty->assign("lastBayesUpdate",strftime("%a, %b %e, %G %I:%M %p",(int)getSetting("lastBayesUpdate")));
$result = mysql_query("SELECT COUNT(*) AS cnt FROM ".$DB_TABLE_PREFIX."rssfeeds");
$smarty->assign("feedCount",mysql_result($result,0,"cnt"));
mysql_free_result($result);
$smarty->assign("listAcc",getBayesCount("U",">="));
$smarty->assign("listDen",getBayesCount("U","<"));
$smarty->assign("listWhite",getBayesCount("W"));
$smarty->assign("listTotWhite",getSetting("wlCount"));
$smarty->assign("listBlack",getBayesCount("B"));
$smarty->assign("listTotBlack",getSetting("blCount"));
if (getSetting("isUpdating")==1){
	if ($msg <> ""){
		$msg .= "<BR>";
	}
	$msg .= "WARNING: Feed data is currently updating";
	$smarty->assign("msg",$msg);
}

// ignored articles
if ($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."globalsettings,".$DB_TABLE_PREFIX."rssfeeds
     WHERE ".$DB_TABLE_PREFIX."feeddata.isIgnored = 1
     AND ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
     AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
     AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
     AND ".$DB_TABLE_PREFIX."globalsettings.settingName = 'expirationInterval'
     AND timestamp >= UNIX_TIMESTAMP() - ".$DB_TABLE_PREFIX."globalsettings.settingValue")){
  $listIgnore = mysql_result($result,0,"itemcount");
  mysql_free_result($result);
} else {
 	$listIgnore = 0;
}
$smarty->assign("listIgnore",$listIgnore);

// low ranking articles
if (getSetting("purgeUseBayes")==1){
		$thres = getSetting("bayesThreshold");
} else {
		$thres = getSetting("purgeLSThres");
}
if (getSetting("purgeWLArticles")==0){
	$querywhere .= " AND bayesStatus <> 'W'";
}
if (getSetting("purgeBLArticles")==0){
	$querywhere .= " AND bayesStatus <> 'B'";
}
if (getSetting("purgeIgnored")==0){
	$querywhere .= " AND isIgnored = 0";
}
if($result = mysql_query("SELECT COUNT(itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata
     WHERE bayesValue < ".$thres.$querywhere)){
	$listLowrank = mysql_result($result,0,"itemcount");
	mysql_free_result($result);
} else {
	$listLowrank = 0;
}
$smarty->assign("listLowrank",$listLowrank);
// expired articles
if($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount, ".$DB_TABLE_PREFIX."feeddata.bayesStatus
		 FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."globalsettings,".$DB_TABLE_PREFIX."rssfeeds
     WHERE ".$DB_TABLE_PREFIX."globalsettings.settingName = 'expirationInterval'
     AND ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
     AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
     AND ".$DB_TABLE_PREFIX."feeddata.timestamp <= UNIX_TIMESTAMP() - ".$DB_TABLE_PREFIX."globalsettings.settingValue
		 GROUP BY ".$DB_TABLE_PREFIX."feeddata.bayesStatus DESC")){
	while ($row = mysql_fetch_object($result)){
		$listExp[$row->bayesStatus] = $row->itemcount;
	}
	mysql_free_result($result);
}
if (!isset($listExp['U'])){
	$listExp['U'] = 0;
}
$smarty->assign("listExpU",$listExp['U']);
if (!isset($listExp['W'])){
	$listExp['W'] = 0;
}
$smarty->assign("listExpW",$listExp['W']);
if (!isset($listExp['B'])){
	$listExp['B'] = 0;
}
$smarty->assign("listExpB",$listExp['B']);
$smarty->assign("listExp",$listExp['U']+$listExp['B']+$listExp['W']);

// articles from disabled feeds
if($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."rssfeeds
     WHERE ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
     AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 0")){
	$listDis = mysql_result($result,0,"itemcount");
	mysql_free_result($result);
} else {
	$listDis = 0;
}
$smarty->assign("listDis",$listDis);

// articles from automatically whitelisted feeds
if($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."globalsettings,".$DB_TABLE_PREFIX."rssfeeds
     WHERE ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
     AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 1
     AND ".$DB_TABLE_PREFIX."globalsettings.settingName = 'expirationInterval'
     AND timestamp >= UNIX_TIMESTAMP() - ".$DB_TABLE_PREFIX."globalsettings.settingValue")){
	$listAuto = mysql_result($result,0,"itemcount");
	mysql_free_result($result);
} else {
	$listAuto = 0;
}
$smarty->assign("listAuto",$listAuto);

// total articles 
if($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata")){
	$listTotal = mysql_result($result,0,"itemcount");
	mysql_free_result($result);
} else {
	$listTotal = 0;
}
$smarty->assign("listTotal",$listTotal);

// get keyword count
if($result = mysql_query("SELECT COUNT(*) AS cnt FROM ".$DB_TABLE_PREFIX."bayes_keys")){
	$listKeys = mysql_result($result,0,"cnt");
	mysql_free_result($result);
} else {
	$listKeys = 0;
}
$smarty->assign("listKeys",$listKeys);

// average bayes score for whitelisted and blacklisted items
if ($result = mysql_query("SELECT AVG(".$DB_TABLE_PREFIX."feeddata.bayesValue) AS avgBayes,".$DB_TABLE_PREFIX."feeddata.bayesStatus 
											FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds 
											WHERE ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
											AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
											AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
											GROUP BY ".$DB_TABLE_PREFIX."bayesStatus 
											ORDER BY ".$DB_TABLE_PREFIX."bayesStatus DESC")){
	$avgWLBayes = mysql_result($result,2,"avgBayes");
	$avgBLBayes = mysql_result($result,1,"avgBayes");
} else {
	$avgWLBayes = "Unknown";
	$avgBLBayes = "Unknown";
}
$smarty->assign("avgWLBayes",$avgWLBayes);
$smarty->assign("avgBLBayes",$avgBLBayes);

$smarty->display("admin.tpl");

function getThreshCount($low,$high){
	global $DB_TABLE_PREFIX;
  if ($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."globalsettings,".$DB_TABLE_PREFIX."rssfeeds
       WHERE ".$DB_TABLE_PREFIX."feeddata.weightedValue BETWEEN ".$low." AND ".$high."
       AND ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
       AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
       AND ".$DB_TABLE_PREFIX."globalsettings.settingName = 'expirationInterval'
       AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 0
       AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
       AND timestamp >= UNIX_TIMESTAMP() - ".$DB_TABLE_PREFIX."globalsettings.settingValue")){
  	return mysql_result($result,0,"itemcount");
  } else {
  	return 0;
  }
}

function getBayesCount($which,$modifier=">"){
	global $DB_TABLE_PREFIX;
	$querywhich = $DB_TABLE_PREFIX."feeddata.bayesStatus='".$which."'";
	if ($which == "U"){
		$querywhich .= " AND ".$DB_TABLE_PREFIX."feeddata.bayesValue ".$modifier." ".getSetting("bayesThreshold");
	}
	if ($result = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS itemcount FROM ".$DB_TABLE_PREFIX."feeddata,".$DB_TABLE_PREFIX."globalsettings,".$DB_TABLE_PREFIX."rssfeeds
       WHERE ".$querywhich."
       AND ".$DB_TABLE_PREFIX."feeddata.feedID = ".$DB_TABLE_PREFIX."rssfeeds.feedID
       AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
       AND ".$DB_TABLE_PREFIX."globalsettings.settingName = 'expirationInterval'
       AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 0
       AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
       AND timestamp >= UNIX_TIMESTAMP() - ".$DB_TABLE_PREFIX."globalsettings.settingValue")){
  	return mysql_result($result,0,"itemcount");
  } else {
  	return 0;
  }
}
?>