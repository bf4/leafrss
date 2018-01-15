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
// global settings editor

// access check
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}

if ($action=="settingsupdate"){
	$msg = "Settings Updated.";
	
	// password change detection
	if (getSafePostVar("newpass1")<>""){
		// check old password
		if (md5(getSafePostVar("curpass"))<>getSetting("password")){
			$msg = "ERROR: You must enter the correct current administrative password to change the password.";
		} elseif (getSafePostVar("newpass1")<>getSafePostVar("newpass2")){
				$msg = "ERROR: New passwords don't match.";
		} else {
			updateSetting("password",md5(getSafePostVar("newpass1")));
		}
	}
	// update interval
	updateSetting("updateInterval",(getSafePostVar("updateInterval")*3600));
	// expiration interval
	updateSetting("expirationInterval",(getSafePostVar("expirationInterval")*3600));
	// number of columns
	updateSetting("numColumns",getSafePostVar("numColumns"));
	// site name
	updateSetting("siteName",getSafePostVar("siteName"));
	// template
	updateSetting("templateName",getSafePostVar("templateName"));
	// clear Smarty Template cache so that if a new template is chosen, all templates
	// will recompile
	$smarty->template_dir = $ROOT_DIR."template/".getSafePostVar("templateName");
	$smarty->assign("templateName",getSafePostVar("templateName"));
	$smarty->clear_compiled_tpl();
	// keyword threshold
	updateSetting("bayesThreshold",getSafePostVar("bayesThreshold"));
	// admin method
	updateSetting("authMethod",getSafePostVar("authMethod"));
	// admin username
	updateSetting("username",getSafePostVar("username"));
	// use cron
	updateSetting("useCron",getSafePostVar("useCron"));
	// maximum number of articles
	updateSetting("maxArticles",getSafePostVar("maxArticles"));
	// maximum number of admin articles
	updateSetting("admMaxArticles",getSafePostVar("admMaxArticles"));
	// time zone offset
	updateSetting("timeZone",getSafePostVar("timeZone"));
	// date display format
	updateSetting("dateDisplay",getSafePostVar("dateDisplay"));
	// allow RSS
	updateSetting("allowRSS",getSafePostVar("allowRSS"));
	// copyright
	updateSetting("copyright",getSafePostVar("copyright"),1);
	// show admin link
	updateSetting("showAdminLink",getSafePostVar("showAdminLink"));
	// is site embedded in a web page?
	updateSetting("isEmbedded",getSafePostVar("isEmbedded"));
	// automatically ignore blank articles?
	updateSetting("autoIgnoreBlank",getSafePostVar("autoIgnoreBlank"));
	// automatically purge expired articles on update?
	updateSetting("autoExpireOnUpdate",getSafePostVar("autoExpireOnUpdate"));
	// purge whitelisted articles on expire purge?
	updateSetting("purgeWLArticles",getSafePostVar("purgeWLArticles"));
	// purge blacklisted articles on expire purge?
	updateSetting("purgeBLArticles",getSafePostVar("purgeBLArticles"));
	// purge ignored articles on expire purge?
	updateSetting("purgeIgnored",getSafePostVar("purgeIgnored"));
	// purge low ranking articles on expire purge?
	updateSetting("purgeLowScore",getSafePostVar("purgeLowScore"));
	// low ranking threshold level
	updateSetting("purgeLSThres",getSafePostVar("purgeLSThres"));
	// set low ranking threshold to bayes threshold?
	updateSetting("purgeUseBayes",getSafePostVar("purgeUseBayes"));
	// check for duplicates?
	updateSetting("duplicateCheck",getSafePostVar("duplicateCheck"));
	// automatically update Bayes scores when whitelisting/blacklisting?
	updateSetting("autoBayesScore",getSafePostVar("autoBayesScore"));
	$smarty->assign("msg",$msg);
}
// use cron for updates
$smarty->assign("useCron",getSetting("useCron"));
// update interval
$smarty->assign("updateInterval",(getSetting("updateInterval")/3600));
// expiration interval
$smarty->assign("expirationInterval",(getSetting("expirationInterval")/3600));
// number of columns
$smarty->assign("numColumns",getSetting("numColumns"));
// maximum number of articles
$smarty->assign("maxArticles",getSetting("maxArticles"));
// maximum number of articles on admin
$smarty->assign("admMaxArticles",getSetting("admMaxArticles"));
// template selection
$dir = $ROOT_DIR."template/";
$d = dir($dir);
$selectTemplate = array();
while (false !== ($entry = $d->read())) {
  if (($entry != ".")&&($entry != "..")&&(is_dir($dir.$entry))){
   	$selectTemplate[] = $entry;
  }
}
$d->close();
$smarty->assign("selectTemplate",$selectTemplate);
// time zone
$smarty->assign("timeZone",getSetting("timeZone"));
// RSS output
$smarty->assign("NoYes",array("No","Yes"));
$smarty->assign("allowRSS",getSetting("allowRSS"));
// RSS copyright info
$smarty->assign("copyright",getSetting("copyright"));
// show admin link
$smarty->assign("showAdminLink",getSetting("showAdminLink"));
// bayes threshold
$smarty->assign("bayesThreshold",getSetting("bayesThreshold"));
// administrative authentication method
$smarty->assign("authValues",array("session","www"));
$smarty->assign("authDisplay",array("Session Based","Browser Based"));
// admin username
$smarty->assign("username",getSetting("username"));
$smarty->assign("siteName",getSetting("siteName"));
$smarty->assign("authMethod",getSetting("authMethod"));
$smarty->assign("dateDisplay",getSetting("dateDisplay"));
$smarty->assign("isEmbedded",getSetting("isEmbedded"));
$smarty->assign("autoIgnoreBlank",getSetting("autoIgnoreBlank"));
$smarty->assign("autoExpireOnUpdate",getSetting("autoExpireOnUpdate"));
$smarty->assign("purgeWLArticles",getSetting("purgeWLArticles"));
$smarty->assign("purgeBLArticles",getSetting("purgeBLArticles"));
$smarty->assign("duplicateCheck",getSetting("duplicateCheck"));
$smarty->assign("autoBayesScore",getSetting("autoBayesScore"));
$smarty->assign("purgeIgnored",getSetting("purgeIgnored"));
$smarty->assign("purgeLowScore",getSetting("purgeLowScore"));
$smarty->assign("purgeLSThres",getSetting("purgeLSThres"));
$smarty->assign("purgeUseBayes",getSetting("purgeUseBayes"));
$smarty->display("settings.tpl");
?>