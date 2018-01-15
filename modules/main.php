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
if (isset($ROOT_DIR)){
	Include($ROOT_DIR."include/access.php");
} else {
	die("<B>ERROR:</B> Script access violation.");
}

// main display page
$result = mysql_query("SELECT ".$DB_TABLE_PREFIX."feeddata.title,
       ".$DB_TABLE_PREFIX."feeddata.link,
       ".$DB_TABLE_PREFIX."feeddata.author,
       ".$DB_TABLE_PREFIX."feeddata.description,
       ".$DB_TABLE_PREFIX."feeddata.imageURL,
       ".$DB_TABLE_PREFIX."feeddata.pubDate,
       ".$DB_TABLE_PREFIX."rssfeeds.Description AS feedDescription,
       ".$DB_TABLE_PREFIX."rssfeeds.homePageURL AS feedURL,
       ".$DB_TABLE_PREFIX."rssfeeds.URL AS sourceURL,
       ".$DB_TABLE_PREFIX."feeddata.guid AS guid,
       ".$DB_TABLE_PREFIX."feeddata.bayesValue AS bayesValue,
       ".$DB_TABLE_PREFIX."feeddata.bayesStatus AS bayesStatus,
       IF(ISNULL(".$DB_TABLE_PREFIX."feeddata.pubDate),".$DB_TABLE_PREFIX."feeddata.timestamp,".$DB_TABLE_PREFIX."feeddata.pubDate) AS displayOrder
FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds
WHERE ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
      AND ".$DB_TABLE_PREFIX."feeddata.timestamp > (UNIX_TIMESTAMP() - ".getSetting("expirationInterval").")
      AND (".$DB_TABLE_PREFIX."feeddata.bayesValue > ".getSetting("bayesThreshold")."
      		OR ".$DB_TABLE_PREFIX."feeddata.bayesStatus = 'W')
      AND ".$DB_TABLE_PREFIX."feeddata.bayesStatus <> 'B'
      AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 0
      AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
ORDER BY displayOrder DESC
LIMIT ".getSetting("maxArticles"));
if ($result){
	if (mysql_num_rows($result)==0){
		$smarty->assign("err_msg","There are currently no articles to display.");
	}
	$numColumns = getSetting("numColumns");
	$smarty->assign("colWidth",(int)(100/$numColumns));
	$columns = array();
	$picAlign = array("LEFT","RIGHT");
	$curAlign = 1;
	$count = 0;
	while ($count < $numColumns){
		$columns[] = array();
		$count++;
	}
	$count = 0;
	if (!isset($output)){
		$output = "";
	}
	while ($row = mysql_fetch_assoc($result)){
		if ($row['imageURL'] <> ""){
			$row['imageAlign'] = $picAlign[$curAlign];
			$curAlign = 1 - $curAlign;
		} else {
			$row['imageAlign'] = "";
		}
		if ($row['pubDate'] <> ""){
			$row['pubDate'] = dateDisplay($row['pubDate']);
		}
		if (($output == "rss")&&(getSetting("allowRSS")==1)){
			$row['description'] = htmlspecialchars($row['description']);
			$row['sourceURL'] = htmlspecialchars($row['sourceURL']);
			$row['link'] = htmlspecialchars($row['link']);
			$row['guid'] = htmlspecialchars($row['guid']);
			if (substr($row['guid'],0,7)<>"http://"){
				$row['isPermalink'] = " isPermaLink=\"false\"";
			} else {
				$row['isPermalink'] = "";
			}
		}
		$columns[$count][] = $row;
		$count++;
		if ($count >= $numColumns){$count = 0;}
	}
	$smarty->assign("columns",$columns);
} else {
	$smarty->assign("err_msg","No articles available. Please check to make sure that you have at least one RSS feed entered into the aggregator.");
}
// display template

// if output is set to rss, display rss template instead of standard
if (($output == "rss")&&(getSetting("allowRSS")==1)){
	$smarty->assign("copyright",getSetting("copyright"));
	header('Content-type: application/rss+xml');
	$smarty->display("rss.tpl");
} elseif (getSetting("isEmbedded")==1) {
	$smarty->display("embedded.tpl");
} else {
	$smarty->display("main.tpl");
}
?>