<?php
/*************************************************

LeafRSS- the Learning Filtered RSS Aggregator
Author: Grant Electronics <leafrss@grantelectronics.com>
Copyright (c): 2007 Grant Electronics, all rights reserved

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
// cron-based update script

// this is the script cron needs to run if update schedule is set through cron


// buffer output of script
ob_start();

// configuration variables not stored in database
require_once("config.php");
if (!$isReady){
	die ("ERROR: You need to set up your config.inc file before LeafRSS will work.<BR>Please check the README included with this software for more details.");
}

// include lastRSS
require_once("lastRSS/lastRSS.php");
// standard functions
require_once($ROOT_DIR."include/std_func_lib.php");
// database connection
require_once($ROOT_DIR."include/db_inc.php");

// global settings from database
require_once($ROOT_DIR."include/db_global.php");

// turn off time limit for script duration
if (!ini_get("safe_mode")){
	set_time_limit(0);
}
// update feed data
updateFeeds(1,0,1);

ob_end_flush();
?>
