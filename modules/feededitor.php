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
// RSS feed editor

// access check
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}
$msg = "";

switch ($action){
	case "feedinsert":
  	if (($_POST["URL"] == "")||($_POST["Description"] == "")){
  		$msg = "ERROR: You must enter in both a URL and a description.";
  		break;
  	}
		// check to see if feed URL is already in database
		$result = mysql_query("SELECT feedID FROM ".$DB_TABLE_PREFIX."rssfeeds WHERE URL='".getSafePostVar("URL")."'");
		if (mysql_num_rows($result) <> 0){
  		$msg = "ERROR: Duplicate feed exists.";
  		break;
  	}
  		if (mysql_query("INSERT INTO ".$DB_TABLE_PREFIX."rssfeeds (URL, Description, homePageURL,notes,isAutoWL,isAutoIgnored) VALUES
									('".getSafePostVar("URL")."','"
									.getSafePostVar("Description")."','"
									.getSafePostVar("homePageURL")."','"
									.getSafePostVar("notes")."','"
									.checkBoxToBin($_POST["isAutoWL"])."','"
									.checkBoxToBin($_POST["isAutoIgnored"])."')")){
			$msg = "New feed added successfully.";
		} else {
			$msg = "ERROR: ".mysql_error();
		}
  	break;
	case "feedupdate":
  	if (($_POST["URL"] == "")||($_POST["Description"] == "")){
  		$msg = "ERROR: You must enter in both a URL and a description.";
  		$action = "feededit";
  	} else {
  		// TODO: do we really need these checks?
  		if (!isset($_POST["isAutoWL"])){
  			$_POST["isAutoWL"] = "";
  		}
  		if (!isset($_POST["isAutoIgnored"])){
  			$_POST["isAutoIgnored"] = "";
  		}
  		if (mysql_query("UPDATE ".$DB_TABLE_PREFIX."rssfeeds SET URL='".getSafePostVar("URL")."',
  										Description='".getSafePostVar("Description")."', 
											homePageURL='".getSafePostVar("homePageURL")."', 
											notes ='".getSafePostVar("notes")."',
  										isAutoWL=".checkBoxToBin($_POST["isAutoWL"]).",
											isAutoIgnored=".checkBoxToBin($_POST["isAutoIgnored"])." 
											WHERE feedID=".getPKID("feedID"))){
  			$msg = "Feed updated successfully.";
  		} else {
  			$msg = "ERROR: ".mysql_error();
  		}
  	}
  	break;
	case "feedremove":
  	// remove all articles from the given feed
  	if ((mysql_query("DELETE FROM ".$DB_TABLE_PREFIX."feeddata WHERE feedID=".getPKID("feedID")))&&
  		(mysql_query("DELETE FROM ".$DB_TABLE_PREFIX."rssfeeds WHERE feedID=".getPKID("feedID")))){
  		$msg = "Feed removed successfully.";
  	} else {
  		$msg = "ERROR: ".mysql_error();
  	}
  	break;
  case "feeddisable":
  	if (mysql_query("UPDATE ".$DB_TABLE_PREFIX."rssfeeds SET isEnabled=0 WHERE feedID=".getPKID("feedID"))){
  		$msg = "Feed disabled.";
  	} else {
  		$msg = "ERROR: ".mysql_error();
  	}
  	break;
  case "feedenable":
  	if (mysql_query("UPDATE ".$DB_TABLE_PREFIX."rssfeeds SET isEnabled=1 WHERE feedID=".getPKID("feedID"))){
  		$msg = "Feed enabled.";
  	} else {
  		$msg = "ERROR: ".mysql_error();
  	}
}
// list feeds

// set display order
if (isset($_GET["sortby"])){
	$sortby = $_GET["sortby"];
} else {
	$sortby = "descasc";
}
// default to Description ASC
$descorder = "desc";
$wlorder = "desc";
$blorder = "desc";
$orderby = "Description ASC";
switch ($sortby){
	case "wlasc":
		$orderby = "wlCount ASC";
		$descorder = "asc";
		break;
	case "wldesc":
		$orderby = "wlCount DESC";
		$descorder = "asc";
		$wlorder = "asc";
		break;
	case "blasc":
		$orderby = "blCount ASC";
		$descorder = "asc";
		break;
	case "bldesc":
		$orderby = "blCount DESC";
		$descorder = "asc";
		$blorder = "asc";
		break;
	case "descdesc":
		$orderby = "Description DESC";
		$descorder = "asc";
}
$smarty->assign("wlorder",$wlorder);
$smarty->assign("blorder",$blorder);
$smarty->assign("descorder",$descorder);
$smarty->assign("sortby",$sortby);
$result = mysql_query("SELECT * FROM ".$DB_TABLE_PREFIX."rssfeeds ORDER BY ".$orderby);
if ($result){
	$feeds = array();
	while ($row = mysql_fetch_assoc($result)){
		$row['rowcolor'] = ($row['isEnabled'] == 1) ? "enabledfeed" : "disabledfeed";
		$row['togglefeed'] = ($row['isEnabled'] == 1) ? "Disable" : "Enable";
		$feeds[] = $row;
	}
	mysql_free_result($result);
	$smarty->assign("feeds",$feeds);
} else {
	$msg = "No RSS feeds have been entered.";
}

// default to new feed form
$feedID = "";
$URL = "";
$Description = "";
$homePageURL = "";
$notes = "";
$feedaction = "feedinsert";
$formTitle = "Add New Feed";
$buttonName = "Add";
$isAutoWL = "";
$isAutoIgnored = " DISABLED";

if ($action == "feededit"){
	// edit feed
	$result = mysql_query("SELECT * FROM ".$DB_TABLE_PREFIX."rssfeeds WHERE feedID=".getPKID("feedID"));
	if (mysql_num_rows($result) > 0){
		$feedaction = "feedupdate";
		$feedID = mysql_result($result,0,"feedID");
		$URL = mysql_result($result,0,"URL");
		$Description = mysql_result($result,0,"Description");
		$homePageURL = mysql_result($result,0,"homePageURL");
		$notes = mysql_result($result,0,"notes");
		$formTitle = "Edit Feed";
		$buttonName = "Update";
		$isAutoWL = (mysql_result($result,0,"isAutoWL")==1) ? " CHECKED": "";
		$isAutoIgnored = (mysql_result($result,0,"isAutoIgnored")==1) ? " CHECKED": "";
		$isAutoIgnored .= (mysql_result($result,0,"isAutoWL")==0) ? " DISABLED": "";
		mysql_free_result($result);
	} else {
		$msg = "ERROR: feed not found.";
	}		
}
$smarty->assign("feedaction",$feedaction);
$smarty->assign("feedID",$feedID);
$smarty->assign("URL",$URL);
$smarty->assign("Description",$Description);
$smarty->assign("homePageURL",$homePageURL);
$smarty->assign("notes",$notes);
$smarty->assign("formTitle",$formTitle);
$smarty->assign("buttonName",$buttonName);
$smarty->assign("isAutoWL",$isAutoWL);
$smarty->assign("isAutoIgnored",$isAutoIgnored);
$smarty->assign("msg",$msg);
$smarty->display('feededit.tpl');

function checkBoxToBin($box){
	if ($box == "on"){
		$output = 1;
	} else {
		$output = 0;
	}
	return $output;
}
?>