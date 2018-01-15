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
// std_func_lib.php

// access check
Include("access.php");
	
function updateFeeds($forceUpdate=0,$ID=0,$verbose=0){
	global $DB_TABLE_PREFIX, $LASTRSS_CACHE_DIR;
	$updateInterval = getSetting("updateInterval");
	$lastUpdate = getSetting("lastUpdate");
	if (($lastUpdate > (time() - $updateInterval))&&(!$forceUpdate)){
		return false;
	} else {
		// automatic purge of expired articles
		if (getSetting("autoExpireOnUpdate")==1){
			purgeExpired();
		}
		// automatic purge of low ranking articles
		if (getSetting("purgeLowScore")==1){
			purgeLowScore();
		}
		// turn on notification
		updateSetting("isUpdating","1");
		$querywhere = " WHERE isEnabled=1";
		if ($ID <> 0){  
			// only pull an individual feed
			$querywhere .= " AND feedID=".$ID;
		}	
		$result = mysql_query("SELECT * FROM ".$DB_TABLE_PREFIX."rssfeeds".$querywhere);
		// gather required settings
		$autoIgnoreBlank = getSetting("autoIgnoreBlank");	
		$duplicateCheck = getSetting("duplicateCheck");
		if ($verbose){
			echo "Fetching articles from feeds...\n";
		}
		while ($row = mysql_fetch_object($result)){
			$rss = new lastRSS;
			$rss->cache_dir = $LASTRSS_CACHE_DIR;
			$rss->cache_time = 3600;
			$rss->CDATA = "content";
			
			if ($verbose){
				echo "Fetching ".$row->URL."...\n";
				$artcnt = 0;
			}
			if ($rs = $rss->get( $row->URL )){
				// parse and put into database
				foreach ($rs['items'] as $item) {
					$href = $item['link'];
					$title = mysql_real_escape_string($item['title']);
					$author = mysql_real_escape_string($item['author']);
					// if guid exists, snag it. If not, use the link as guid
					if (array_key_exists("guid",$item)){
						$guid = $item['guid'];
					} else {
						$guid = $href;
					}
					// if valid pubDate, then pull it. If not, set to null
					$pubDate = (array_key_exists("pubDate",$item)) ? strtotime($item['pubDate']) : -1;
					if ((!$pubDate)||($pubDate == -1)){
						$pubDate = "NULL";
					}
					if (strlen($desc)>65535){
						$desc = substr($desc,0,65532);
						$lastsp = strrpos($desc," ");
						$desc = substr($desc,0,$lastsp)."...";
					}
					//lastRSS doesn't automatically decode HTML entities, so put it here
					$desc = html_entity_decode($desc, ENT_QUOTES);
					$desc = mysql_real_escape_string($item['description']);
					// auto ignore blank feeds
					if (($desc == "")&& ($autoIgnoreBlank == "1")){
						$isIgnored = 1;
					} else {
						$isIgnored = 0;
					}
					// duplicate check
					$isDuplicate = 0;
					if ($duplicateCheck == "1"){
						$dupquery = "SELECT itemID FROM ".$DB_TABLE_PREFIX."feeddata WHERE title='".$title."' AND description='".$desc."\"";
						if ($dupcheck = mysql_query($dupquery)){
							$isDuplicate = mysql_num_rows($dupcheck);
							mysql_free_result($dupcheck);
						}
					}
					if ($isDuplicate > 0){
						$isIgnored = 1;
					}
					if ($row->isAutoWL == "1"){
						$bayesStatus = "W";
					} else {
						$bayesStatus = "U";
					}
					$query = "INSERT INTO ".$DB_TABLE_PREFIX."feeddata (link, title, author, 
						description, guid, pubDate, feedID, timestamp, isIgnored, bayesStatus) VALUES ('".$href."','".$title."','"
						.$author."','".$desc."','".$guid."',".$pubDate.",".$row->feedID.", UNIX_TIMESTAMP(), ".$isIgnored.",'".$bayesStatus."')";
					if ($linkresult = mysql_query("SELECT link FROM ".$DB_TABLE_PREFIX."feeddata WHERE link='".substr($href,0,255)."'")){
						if (mysql_num_rows($linkresult)==0){
							$insertresult = mysql_query($query);
							if (mysql_error()){
								echo mysql_error()."<BR>".$query."<BR>";
							} else {
								if ($verbose){
									$artcnt++;
								}
							}
						}
					} else {
						$insertresult = mysql_query($query);
					}
				}
			} else {
				if ($verbose){
					echo "Feed #".$row->feedID." (".$row->Description."): URL Not Found\n";
				}
			}
			if ($verbose){
				echo $artcnt." articles added successfully.\n";
			}
		}
		mysql_free_result($result);
		updateBayesFeed(1,$verbose);
		if ($ID == 0){
			$result = mysql_query("UPDATE ".$DB_TABLE_PREFIX."globalsettings SET settingValue=UNIX_TIMESTAMP() WHERE settingName='lastUpdate'");
		}
		// turn off notification
		updateSetting("isUpdating","0");
	}
}

function getSetting($settingName){
	global $DB_TABLE_PREFIX;
	$result = mysql_query("SELECT settingValue FROM ".$DB_TABLE_PREFIX."globalsettings WHERE settingName='".$settingName."'");
	$settingValue = mysql_result($result,0,"settingValue");
	mysql_free_result($result);
	return $settingValue;
}

function updateSetting($settingName,$settingValue,$allowBlank=0){
	global $DB_TABLE_PREFIX;
	$curValue = getSetting($settingName);
	if (($curValue <> $settingValue)&&(($settingValue<>"")||($allowBlank))){
		$query = "UPDATE ".$DB_TABLE_PREFIX."globalsettings SET settingValue='".$settingValue."' WHERE settingName='".$settingName."'";
		mysql_query($query);
	}
}

function getKeywords($text){
	global $DB_TABLE_PREFIX;
	// make all lowercase
	$text = strtolower($text);
	// trim away any whitespace
	$text = trim($text);
	// replace closing tags that would normally display a new line with whitespace
	$text = str_replace(array("</p>","<br>","<br/>","</div>","</td>")," ",$text);
	// strip out any remaining HTML tags
	$text = strip_tags($text);
	// convert html special characters to normal
	$text = html_entity_decode($text);
	// replace char 160 with whitespace
	$text = str_replace(chr(160)," ",$text);
	// convert all html &# character codes
	$text = decode_entities($text);
	// remove "'s" from words
	$text = str_replace("'s","",$text); 
	// remove all punctuation and numbers
	$text = ereg_replace("[0-9,.;:'\?!\"\(\)]","",$text); 
	// convert to array
	$artext = explode(" ", $text);
	// remove duplicates
	$artext = array_unique($artext);
	// remove any item from the array that is 3 characters or less
	foreach($artext as $key => $value){
		if (strlen($value)<4){
			unset($artext[$key]);
		}
	}
	return $artext;
}

function admListArticle($bayesStatus,$which = ">"){
	global $expirationInterval, $DB_TABLE_PREFIX, $ROOT_URL, $action, $smarty;
	// previous/net position
	$start = getPKID("start");
	$querywhich = $DB_TABLE_PREFIX."feeddata.bayesStatus = '".$bayesStatus."'";
	if ($bayesStatus == 'U'){
		$querywhich .= " AND ".$DB_TABLE_PREFIX."feeddata.bayesValue ".$which." ".getSetting("bayesThreshold");
	}
	$admMaxArticles = getSetting("admMaxArticles");
	// get total number of articles
	$cntresult = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS cnt 
														FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds
														WHERE ".$querywhich
				 										." AND ".$DB_TABLE_PREFIX."feeddata.timestamp > (UNIX_TIMESTAMP() - ".$expirationInterval.")
                    				AND ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
                    				AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
                    				AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 0
														AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0");
	$cnt = mysql_result($cntresult,0,"cnt");
  mysql_free_result($cntresult);
	// get articles
	$query = "SELECT ".$DB_TABLE_PREFIX."feeddata.itemID AS itemID, 
						".$DB_TABLE_PREFIX."feeddata.title AS title,
						".$DB_TABLE_PREFIX."feeddata.link,
            ".$DB_TABLE_PREFIX."feeddata.author,
            ".$DB_TABLE_PREFIX."feeddata.description,
            ".$DB_TABLE_PREFIX."feeddata.imageURL,
            ".$DB_TABLE_PREFIX."feeddata.pubDate,
            ".$DB_TABLE_PREFIX."rssfeeds.Description AS feedDescription,
            ".$DB_TABLE_PREFIX."rssfeeds.homePageURL AS feedURL,
				 		".$DB_TABLE_PREFIX."feeddata.bayesValue AS bayesValue,
				 		IF(ISNULL(".$DB_TABLE_PREFIX."feeddata.pubDate),".$DB_TABLE_PREFIX."feeddata.timestamp,".$DB_TABLE_PREFIX."feeddata.pubDate) AS displayOrder
				 FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds WHERE ".$querywhich
				 ." AND ".$DB_TABLE_PREFIX."feeddata.timestamp > (UNIX_TIMESTAMP() - ".$expirationInterval.")
				 AND ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
				 AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 0
				 AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
				 AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL = 0
				 ORDER BY ".$DB_TABLE_PREFIX."feeddata.bayesValue DESC
				 LIMIT ".$start.",".$admMaxArticles;
	$result = mysql_query($query);
	$isBWlist = ($bayesStatus == "U");
	$smarty->assign("admMaxArticles",$admMaxArticles);
	$smarty->assign("isBWlist",$isBWlist);
	// previous/next buttons
	if ($start > 0){
		$startPrev = ($admMaxArticles > $start) ? 0 : ($start - $admMaxArticles);
	} else {
		$startPrev = -1;
	}
	$smarty->assign("startPrev",$startPrev);
	if (($start + $admMaxArticles) < $cnt){
		$startNext = $start + $admMaxArticles;
	} else {
		$startNext = -1;
	}
	$smarty->assign("startNext",$startNext);
	$smarty->assign("cnt",$cnt);
	$isBlack = ($bayesStatus == "B");
	$isWhite = ($bayesStatus == "W");
	$smarty->assign("isBlack",$isBlack);
	$smarty->assign("isWhite",$isWhite);
	$articles = array();
	while ($row = mysql_fetch_assoc($result)){
		if ($row['pubDate'] <> ""){
			$row['pubDate'] = dateDisplay($row['pubDate']);
		}
		$articles[] = $row;
	}
	$smarty->assign("articles",$articles);
	mysql_free_result($result);
	$smarty->display("adminlist.tpl");
}	

function admListIgnore(){
	global $expirationInterval, $DB_TABLE_PREFIX, $smarty;
	// previous/net position
	$start = getPKID("start");
	$admMaxArticles = getSetting("admMaxArticles");
	// get total number of articles
	$cntresult = mysql_query("SELECT COUNT(".$DB_TABLE_PREFIX."feeddata.itemID) AS cnt 
														FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds
														WHERE ".$DB_TABLE_PREFIX."feeddata.timestamp > (UNIX_TIMESTAMP() - ".$expirationInterval.")
                    				AND ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
                    				AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
                    				AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 1");
	$cnt = mysql_result($cntresult,0,"cnt");
	$query = "SELECT ".$DB_TABLE_PREFIX."feeddata.itemID AS itemID, 
						".$DB_TABLE_PREFIX."feeddata.title AS title,
						".$DB_TABLE_PREFIX."feeddata.link,
            ".$DB_TABLE_PREFIX."feeddata.author,
            ".$DB_TABLE_PREFIX."feeddata.description,
            ".$DB_TABLE_PREFIX."feeddata.imageURL,
            ".$DB_TABLE_PREFIX."feeddata.pubDate,
            ".$DB_TABLE_PREFIX."rssfeeds.Description AS feedDescription,
            ".$DB_TABLE_PREFIX."rssfeeds.homePageURL AS feedURL,
				 		IF(ISNULL(".$DB_TABLE_PREFIX."feeddata.pubDate),".$DB_TABLE_PREFIX."feeddata.timestamp,".$DB_TABLE_PREFIX."feeddata.pubDate) AS displayOrder
				 FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds 
				 WHERE ".$DB_TABLE_PREFIX."feeddata.timestamp >= (UNIX_TIMESTAMP() - ".$expirationInterval.")
				 AND ".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
				 AND ".$DB_TABLE_PREFIX."rssfeeds.isEnabled = 1
				 AND ".$DB_TABLE_PREFIX."feeddata.isIgnored = 1
				 ORDER BY displayOrder DESC
				 LIMIT ".$start.",".$admMaxArticles;
	$result = mysql_query($query);
	$smarty->assign("admMaxArticles",$admMaxArticles);
	// previous/next buttons
	if ($start > 0){
		$startPrev = ($admMaxArticles > $start) ? 0 : ($start - $admMaxArticles);
	} else {
		$startPrev = -1;
	}
	$smarty->assign("startPrev",$startPrev);
	if (($start + $admMaxArticles) < $cnt){
		$startNext = $start + $admMaxArticles;
	} else {
		$startNext = -1;
	}
	$smarty->assign("startNext",$startNext);
	$smarty->assign("cnt",$cnt);
	$articles = array();
	while ($row = mysql_fetch_assoc($result)){
		if ($row['pubDate'] <> ""){
			// time zone offset
			$row['pubDate'] = dateDisplay($row['pubDate']);
		}
		$articles[] = $row;
	}
	$smarty->assign("articles",$articles);
	mysql_free_result($result);
	$smarty->display("ignorelist.tpl");
}

function decode_entities($text) {
   $text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1");
   $text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text);
   $text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);
   return $text;
}

function dateDisplay($tstamp){
	$output = date(getSetting("dateDisplay"),($tstamp + (getSetting("timeZone")*3600)));
	return $output;
}

// purge expired articles
function purgeExpired(){
		global $expirationInterval, $DB_TABLE_PREFIX;
		// purge expired articles
		$querywhere = "WHERE timestamp <= UNIX_TIMESTAMP() - ".$expirationInterval;
		if (getSetting("purgeWLArticles")==0){
			$querywhere .= " AND bayesStatus <> 'W'";
		}
		if (getSetting("purgeBLArticles")==0){
			$querywhere .= " AND bayesStatus <> 'B'";
		}
		if (getSetting("purgeIgnored")==0){
			$querywhere .= " and isIgnored = 0";
		}
		$delete = mysql_query("DELETE FROM ".$DB_TABLE_PREFIX."feeddata ".$querywhere);
}

// purge low ranking articles
function purgeLowScore(){
		global $DB_TABLE_PREFIX;
		if (getSetting("purgeUseBayes")==1){
			$thres = getSetting("bayesThreshold");
		} else {
			$thres = getSetting("purgeLSThres");
		}
		if (getSetting("purgeWLArticles")==0){
			$querywhere .= " AND bayesStatus <> 'W'";
		}
		if (getSetting("purgeBLArticles")==0){
			$querywhere .= " AND bayesStatus <> 'B'";
		}
		if (getSetting("purgeIgnored")==0){
			$querywhere .= " and isIgnored = 0";
		}
		$delete = mysql_query("DELETE FROM ".$DB_TABLE_PREFIX."feeddata WHERE bayesValue > 0 AND bayesValue < ".$thres." ".$querywhere);
}

// function: get percentage that any message is whitelisted
function getWLPerc(){
	$wlCnt = (int)(getSetting("wlCount"));
	$blCnt = (int)(getSetting("blCount"));
	$tot = $wlCnt + $blCnt;
	if ($tot <> 0){
		$WLPerc = (double)($wlCnt/$tot);
	} else {
		$WLPerc = 0;
	}
	return $WLPerc;
}

// function: determine bayes value for a given word
function getBayesValue($word){
	$bayesValue = 0;
	global $PrWL, $DB_TABLE_PREFIX;
	$res = mysql_query("SELECT wlCount, blCount FROM ".$DB_TABLE_PREFIX."bayes_keys WHERE keyword='".$word."';");
	if (mysql_num_rows($res)>0){
		$PrWordsWL = (int)(mysql_result($res,0,"wlCount"));
		$PrWordsBL = (int)(mysql_result($res,0,"blCount"));
		$PrWords = $PrWordsWL + $PrWordsBL;
		$bayesValue = (double)(($PrWordsWL * $PrWL)/$PrWords);
	}
	return $bayesValue;
}

// function: determine bayes value for a given article
function getAvgBayesValue($words){
	// get bayes value for each keyword
	foreach($words as $word){
		$bayesWord[]=getBayesValue($word);
	}
	// get average bayes value for string
	if (count($bayesWord) > 0){
		$avgBayesValue = array_sum($bayesWord) / count($bayesWord);
	} else {
		$avgBayesValue = 0;
	}
	return $avgBayesValue;
}

// function: update Bayes score for all non-expired articles
function updateBayesFeed($new=0,$verbose=0){
	// turn off time limit for script duration
	if (!ini_get("safe_mode")){
		set_time_limit(0);
	}
	if ($verbose){
		echo "Updating Bayes score...\n";
		$btime = stepTimer();
	}
	global $DB_TABLE_PREFIX, $expirationInterval;
	// if new flag is set, only grab articles that have a bayes score of zero
	if ($new){
		$querywhere = " AND ".$DB_TABLE_PREFIX."feeddata.bayesValue = 0";
	}
	// grab all articles that are not expired or automatically ignored
	$result = mysql_query("SELECT ".$DB_TABLE_PREFIX."feeddata.itemID,
		 ".$DB_TABLE_PREFIX."feeddata.title, 
		 ".$DB_TABLE_PREFIX."feeddata.description,
		 ".$DB_TABLE_PREFIX."rssfeeds.isAutoWL
		  FROM ".$DB_TABLE_PREFIX."feeddata, ".$DB_TABLE_PREFIX."rssfeeds WHERE
		".$DB_TABLE_PREFIX."feeddata.feedID=".$DB_TABLE_PREFIX."rssfeeds.feedID
		AND ".$DB_TABLE_PREFIX."feeddata.timestamp > (UNIX_TIMESTAMP() - ".$expirationInterval.")
		AND ".$DB_TABLE_PREFIX."rssfeeds.isAutoIgnored = 0".$querywhere);
	// if there are no articles (usually from first time run), exit function
	if (!$result){return false;}
	// for each article gathered
	while ($row = mysql_fetch_object($result)){
		// reset the bayes value to 0
		$bayesValue = 0;
		// take the title and description
		$words = $row->title." ".$row->description;
  	// gather keywords from them
		$arKeywords = getKeywords($words);
  	// get bayes value based on keyword array
		$bayesValue = getAvgBayesValue($arKeywords);
  	// set the bayes value for the article
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET bayesValue=".$bayesValue." WHERE itemID=".$row->itemID);
  	// if the article is from an automatically whitelisted feed, whitelist it
		if ($row->isAutoWL == 1){
  		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET bayesStatus='W' WHERE itemID=".$row->itemID);
  	}
	}
	// free up database resources
	mysql_free_result($result);
	// update last Bayes update
	$result = mysql_query("UPDATE ".$DB_TABLE_PREFIX."globalsettings SET settingValue=UNIX_TIMESTAMP() WHERE settingName='lastBayesUpdate'");
	if ($verbose){
		$btime = stepTimer() - $btime;
		echo "Total time for Bayes update: ".$btime." seconds.\n";
	}
}

// whitelists or blacklists the article, updating all counts and keywords
function updateBayesStatus($itemID, $which){
		global $DB_TABLE_PREFIX;
		// set article status
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."feeddata SET bayesStatus='".$which."' WHERE itemID=".$itemID);
		// update total count
		$whichCount = strtolower($which)."lCount";
		$count = (int)getSetting($whichCount) + 1;
		updateSetting($whichCount,$count);
		// get title, description, and feedID from given article
		$result = mysql_query("SELECT title, description, feedID FROM ".$DB_TABLE_PREFIX."feeddata WHERE itemID=".$itemID);
		$row = mysql_fetch_object($result);
		// break result into keywords
		$artext = getKeywords($row->title." ".$row->description);
		// update bayes keywords table for the given keywords
		foreach($artext as $value){
			// check to see if keyword is already in database
			$result = mysql_query("SELECT wlCount,blCount FROM ".$DB_TABLE_PREFIX."bayes_keys WHERE keyword='".$value."'");
			if (mysql_num_rows($result)>0){
				// if it is, update the count
				$result = mysql_query("UPDATE ".$DB_TABLE_PREFIX."bayes_keys SET ".$whichCount."=".$whichCount."+1 WHERE keyword='".$value."'");
			} else {
				// otherwise, add a record for the new keyword
				$result = mysql_query("INSERT INTO ".$DB_TABLE_PREFIX."bayes_keys SET keyword='".$value."',".$whichCount."=1");
			}
		}	
		// update feed count
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."rssfeeds SET ".$whichCount."=".$whichCount."+1 WHERE feedID=".$row->feedID);
}

// removes an article from the whitelist or blacklist
function undoBayesStatus($itemID, $which){
	global $DB_TABLE_PREFIX;
	// update total count
	$whichCount = strtolower($which)."lCount";
	$count = (int)getSetting($whichCount) - 1;
	updateSetting($whichCount,$count);
	// get title, description, and feedID from given article
	$result = mysql_query("SELECT title, description, feedID FROM ".$DB_TABLE_PREFIX."feeddata WHERE itemID=".$itemID);
		$row = mysql_fetch_object($result);
		// break result into keywords
		$artext = getKeywords($row->title." ".$row->description);
		// update bayes keywords table for the given keywords
		foreach($artext as $value){
			// update the count
			$result = mysql_query("UPDATE ".$DB_TABLE_PREFIX."bayes_keys SET ".$whichCount."=".$whichCount."-1 WHERE keyword='".$value."'");
		}	
		// update feed count
		$update = mysql_query("UPDATE ".$DB_TABLE_PREFIX."rssfeeds SET ".$whichCount."=".$whichCount."-1 WHERE feedID=".$row->feedID);
}

// cleanup of form variables to prevent potential injection attacks
function getSafePostVar($which){
	// if post variable is set
	if (isset($_POST[$which])){
		// check if magic quotes gpc is on
		if (get_magic_quotes_gpc()){
			$output = stripslashes($_POST[$which]);
		} else {
			$output = $_POST[$which];
		}
		// make variable input safe for mysql queries
		$output = mysql_real_escape_string($output);
	} else {
		// set to blank
		$output = "";
	}
	return $output;
}

// cleanup of GET variables for primary keys (usually feedID or articleID)
// to prevent injection attacks
function getPKID($which){
	if (isset($_GET[$which])){
		$output = (int)$_GET[$which];
	} else {
		$output = 0;
	}
	return $output;
}

function stepTimer(){
	$mtime = microtime(); 
	$mtime = explode(" ", $mtime); 
	$mtime = $mtime[1] + $mtime[0];
	return $mtime;
} 		
?>
