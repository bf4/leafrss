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
// smarty_func_lib.php

// access check
Include("access.php");

// check to see if compile directory exists
// if not, try to create it
if (!is_dir($SMARTY_COMPILE_DIR)){
	if (!mkdir($SMARTY_COMPILE_DIR, 0755)){
		echo "ERROR: Cannot create smarty compile directory. Please create the directory manually.";
		exit;
	}
}
require($SMARTY_DIR."Smarty.class.php");
$smarty = new Smarty();
$smarty->template_dir = $SMARTY_TEMPLATE_DIR;
$smarty->compile_dir = $SMARTY_COMPILE_DIR;
$smarty->cache_dir = $SMARTY_CACHE_DIR;
$smarty->config_dir = $SMARTY_CONFIG_DIR;
$smarty->assign("siteName",getSetting("siteName"));
$smarty->assign("ROOT_URL",$ROOT_URL);
$smarty->assign("action",$action);
$smarty->assign("authMethod",getSetting("authMethod"));
$smarty->assign("dateDisplay",getSetting("dateDisplay"));
$smarty->assign("templateName",getSetting("templateName"));
$smarty->assign("LEAF_RSS_VERSION",$LEAF_RSS_VERSION);
$smarty->assign("allowRSS",getSetting("allowRSS"));
$smarty->assign("showAdminLink",getSetting("showAdminLink"));
$smarty->register_function("inputButton","inputButton");

function inputButton($params, &$smarty){
	$output = "<INPUT TYPE=BUTTON STYLE=\"width: 150px\" VALUE=\"".$params['name']."\" onClick=\"";
	$output .= ($params['confirm'] <> "") ? "if (confirm('".$params['confirm']."')){" : "";
	$output .= "location.href='index.php?action=".$params['action']."';";
	$output .= ($params['confirm'] <> "") ? "}" : "";
	$output .= "\">";
	return $output;
}
?>