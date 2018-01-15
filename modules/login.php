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
// login.php
// User login script

// access check
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}

switch ($action){
  case "login2":
  	if ((getSafePostVar("username") == getSetting("username"))&&(MD5(getSafePostVar("password")) == getSetting("password"))){
  		$_SESSION['AUTH_USER'] = getSafePostVar("username");
  		$_SESSION['AUTH_PW'] = MD5(getSafePostVar("password"));
  		$_SESSION['is_logged_in'] = true;
  		$_SESSION['login_attempts'] = 0;
  		unset($_SESSION['login_msg']);
  		if (isset($_SESSION['return_action'])){
  			$return_action = "?action=".$_SESSION['return_action'];
  			unset($_SESSION['return_action']);
  		}
  		header("Location: ".$ROOT_URL."index.php".$return_action);
  	} else {
  		$_SESSION['login_attempts']++;
  		// TODO: if maximum login attempts reached, log IP address and notify of hack attempt
  		//if ($_SESSION['login_attempts']>$MAX_LOGIN_ATTEMPTS){
  		//	header('Location: '.$ROOT_URL.'unauth.php');
  		//}
  		$_SESSION['login_msg']="Invalid username or password.";
  		$_SESSION['is_logged_in'] = false;
  		header('Location: '.$ROOT_URL.'index.php?action=login');
  	}
    break;	
  case "login":
  	// display template
  	$smarty->display("login.tpl");
  	break;
  case "logout":
  	unset($_SESSION['AUTH_USER']);
  	unset($_SESSION['AUTH_PW']);
  	$_SESSION['is_logged_in'] = false;
  	$_SESSION['login_attempts'] = 0;
  	unset($_SESSION['login_msg']);
  	unset($_SESSION['return_action']);
  	header("Location: ".$ROOT_URL."index.php");
  	break;
}
?>
