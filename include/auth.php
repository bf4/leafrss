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
// access check
Include("access.php");

// if page is not embedded, start session
if ((getSetting("isEmbedded")==0)||($action<>"")){
	session_start();
}

// user authentication
if (($action<>"")&&($action<>"login")&&($action<>"login2")){
	$_SESSION['return_action']=$action;
	switch (getSetting("authMethod")) {
		case "www":
			if (!isset($_SERVER['PHP_AUTH_USER'])) {
       header('WWW-Authenticate: Basic realm="LeafRSS Administration Panel"');
       header('HTTP/1.0 401 Unauthorized');
       echo 'Access Denied. You must enter a username and password to access this page.';
       exit;
    	} else {
    		if (($_SERVER['PHP_AUTH_USER'] <> getSetting("username"))||(MD5($_SERVER['PHP_AUTH_PW']) <> getSetting("password"))){
    			header('HTTP/1.0 401 Unauthorized');
    			echo 'Access Denied. Invalid username or password.';
    			exit;
    		}
    	}
    	break;
    default:
    	if (!isset($_SESSION['AUTH_USER'])) {
    		$_SESSION['login_attempts'] = 0;
    		$_SESSION['is_logged_in'] = false;
    		header('Location: '.$ROOT_URL.'index.php?action=login');
    	} else {
    		if (($_SESSION['AUTH_USER'] <> getSetting("username"))||($_SESSION['AUTH_PW'] <> getSetting("password"))){
    			$_SESSION['login_attempts']++;
    			$_SESSION['login_msg']="Invalid username or password.";
    			$_SESSION['is_logged_in'] = false;
    			header('Location: '.$ROOT_URL.'index.php?action=login');
    		} else {
    			$_SESSION['is_logged_in'] = true;
    		}
    	}
  }
}

?>