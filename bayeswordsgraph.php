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


// configuration variables not stored in database
require_once("config.php");
if (!$isReady){
	die ("ERROR: You need to set up your config.inc file before LeafRSS will work.<BR>Please check the README included with this software for more details.");
}
// turn off time limit for script duration
if (!ini_get("safe_mode")){
	set_time_limit(0);
}
// standard functions
require_once($ROOT_DIR."include/std_func_lib.php");
// database connection
require_once($ROOT_DIR."include/db_inc.php");

// global settings from database
require_once($ROOT_DIR."include/db_global.php");

// PHP graphing functions
require_once($ROOT_DIR."phplot/phplot.php");
error_reporting(E_ERROR | E_PARSE);
// display graph
//Define the object
$graph =& new PHPlot(600,400);
$graph->SetPrintImage(0);
$graph->SetPlotAreaPixels(40,20,590,360);


$result = mysql_query("SELECT title, description, bayesValue FROM ".$DB_TABLE_PREFIX."feeddata WHERE bayesValue <> 0 ORDER BY bayesValue ASC;");
$max = 0;
while ($row = mysql_fetch_object($result)){
	$wordcount[] = count(getKeywords($row->title." ".$row->description));
	$bayesValue[] = $row->bayesValue*100;
}
array_multisort($wordcount,$bayesValue);
$max = $wordcount[0];
$pointcount = 1;
$sumavg = 0;
foreach ($wordcount as $key=>$value){
	if ($value <> $max){
		$data[] = array('',$value,($sumavg/$pointcount));
		$max = $value;
		$pointcount = 1;
		$sumavg = 0;
	} else {
		$pointcount++;
		$sumavg = $sumavg + $bayesValue[$key];
	}	
}
mysql_free_result($result);

$graph->SetDataType("data-data");
$graph->SetTitle('Average Bayes Values by # of Keywords');
$graph->SetDrawXDataLabels(false);
$graph->SetPlotType("lines");
$graph->SetXLabelAngle(90);
$graph->SetXTitle('# of words in article');
$graph->SetYTitle('Avg Bayes Value (%)');
$graph->SetNumHorizTicks(0);
$graph->SetNumVertTicks(25);
$graph->SetLineWidth(1);
$graph->SetPlotAreaWorld(0,0,$max+5,100);
$graph->SetDataValues($data);
$graph->SetDataColors(array(array(0,128,0),array(128,0,0),"black","red"));

//Draw it
$graph->DrawGraph();

//Print the image
$graph->PrintImage(); 
?>