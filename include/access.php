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
// access.php
// Security Access Check

// script security check
$allowedScripts = array("index.php",
												"update.php",
												"admin.php",
												"install.php",
												"patch0506.php",
												"patch0607.php",
												"patch0708.php",
												"patch0809.php",
												"patch09095.php",
												"patch09510.php",
												"patch1011.php",
												"bayesgraph.php",
												"bayeswordsgraph.php",
												"rss.php");
$accessViolation = true;
foreach ($allowedScripts as $index){
	if ($_SERVER['SCRIPT_FILENAME'] == ($ROOT_DIR.$index)){
		$accessViolation = false;
	}
}
if ($accessViolation){
	die("<B>ERROR:</B> Script access violation.");
}

?>