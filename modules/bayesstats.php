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
// bayes statistics module


// access check
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}
// recommended bayes update
if (isset($_POST["bayesThres"])){
	updateSetting("bayesThreshold",getSafePostVar("bayesThres"));
	$smarty->assign("msg","Bayes Threshold Updated.");
}
// get keyword count
if($result = mysql_query("SELECT COUNT(*) AS cnt FROM ".$DB_TABLE_PREFIX."bayes_keys")){
	$listKeys = mysql_result($result,0,"cnt");
	mysql_free_result($result);
} else {
	$listKeys = 0;
}
$smarty->assign("listKeys",$listKeys);
// if there are no keys, then display message and don't load bayes statistics
if ($listKeys == 0){
	$smarty->assign("msg","There is no Bayes data in the database. Please check back after some articles have been added to the database and analyzed.");
} else {
	// average bayes score for whitelisted and blacklisted items
	if ($result = mysql_query("SELECT AVG(".$DB_TABLE_PREFIX."feeddata.bayesValue) AS avgBayes,
												MIN(".$DB_TABLE_PREFIX."feeddata.bayesValue) AS minBayes,
												MAX(".$DB_TABLE_PREFIX."feeddata.bayesValue) AS maxBayes,
												".$DB_TABLE_PREFIX."feeddata.bayesStatus			 
												FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds 
												WHERE ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
												AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
												AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
												AND ".$DB_TABLE_PREFIX."feeddata.bayesValue > 0
												GROUP BY ".$DB_TABLE_PREFIX."feeddata.bayesStatus 
												ORDER BY ".$DB_TABLE_PREFIX."feeddata.bayesStatus DESC")){
		$avgWLBayes = mysql_result($result,2,"avgBayes");
		$avgBLBayes = mysql_result($result,1,"avgBayes");
		$minWLBayes = mysql_result($result,2,"minBayes");
		$minBLBayes = mysql_result($result,1,"minBayes");
		$maxWLBayes = mysql_result($result,2,"maxBayes");
		$maxBLBayes = mysql_result($result,1,"maxBayes");
	} else {
		$avgWLBayes = "Unknown";
		$avgBLBayes = "Unknown";
		$minWLBayes = "Unknown";
		$minBLBayes = "Unknown";
		$maxWLBayes = "Unknown";
		$maxBLBayes = "Unknown";
	}
	$smarty->assign("avgWLBayes",$avgWLBayes);
	$smarty->assign("avgBLBayes",$avgBLBayes);
	$smarty->assign("minWLBayes",$minWLBayes);
	$smarty->assign("minBLBayes",$minBLBayes);
	$smarty->assign("maxWLBayes",$maxWLBayes);
	$smarty->assign("maxBLBayes",$maxBLBayes);
	
	// strongest bayes keywords
	$result = mysql_query("SELECT keyword, 
													CASE WHEN blCount > 0 THEN (wlcount/blCount) ELSE wlCount END AS bayesValue 
													FROM ".$DB_TABLE_PREFIX."bayes_keys 
													ORDER BY bayesValue DESC 
													LIMIT 0,5");
	while ($row = mysql_fetch_object($result)){
		$top5kw[] = $row->keyword;
	}
	mysql_free_result($result);
	$result = mysql_query("SELECT keyword, 
													CASE WHEN wlCount > 0 THEN (blcount/wlCount) ELSE blCount END AS bayesValue 
													FROM ".$DB_TABLE_PREFIX."bayes_keys 
													ORDER BY bayesValue DESC 
													LIMIT 0,5");
	while ($row = mysql_fetch_object($result)){
		$bot5kw[] = $row->keyword;
	}
	
	
	$smarty->assign("top5kw",implode(",&nbsp;",$top5kw));
	$smarty->assign("bot5kw",implode(",&nbsp;",$bot5kw));
	mysql_free_result($result);
	// recommended Bayes threshold
	$recBayes = ($avgWLBayes + $avgBLBayes)/2;
	$smarty->assign("recBayes",$recBayes);
	$smarty->assign("curBayes",getSetting("bayesThreshold"));
}
$smarty->display("bayesstats.tpl");
?>