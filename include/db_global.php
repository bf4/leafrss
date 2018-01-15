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

// db_global.php
// global settings stored in database;

// access check
Include("access.php");

// Name of template
// This is the directory name that contains the layout template
// All template directories should be in /template/
$templateName = getSetting("templateName");

// article expiration interval
$expirationInterval = getSetting("expirationInterval");

// Smarty Template directory
$SMARTY_TEMPLATE_DIR = $ROOT_DIR."template/".$templateName;

// automatic Bayes Updating
$autoBayesScore = getSetting("autoBayesScore");

// get bayes whitelist percentage;
$PrWL = getWLPerc();
?>