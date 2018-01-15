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

// whitelist arrays
$result = mysql_query("SELECT bayesValue FROM ".$DB_TABLE_PREFIX."feeddata WHERE bayesStatus='W' AND bayesValue > 0");
while($row = mysql_fetch_object($result)){
	$bayes = (int)($row->bayesValue * 100);
	$wldata[] = $bayes;
}
mysql_free_result($result);
$freqdata = array_count_values($wldata);
ksort($freqdata);
$max = 0;
foreach($freqdata as $key => $value){
	if ($value > $max){
		$max = $value;
	}
	$wlPos[] = $key;
	$wlVal[] = $value;
}

//blacklist arrays
$result = mysql_query("SELECT bayesValue FROM ".$DB_TABLE_PREFIX."feeddata WHERE bayesStatus='B' AND bayesValue > 0");
while($row = mysql_fetch_object($result)){
	$bayes = (int)($row->bayesValue * 100);
	$bldata[] = $bayes;
}
mysql_free_result($result);
$freqdata = array_count_values($bldata);
ksort($freqdata);
foreach($freqdata as $key => $value){
	$data[] = array('',$key,$value);
	if ($value > $max){
		$max = $value;
	}
	$blPos[] = $key;
	$blVal[] = $value;
}

// whitelist
$data = array();
$minval = $wlPos[0];
$maxval = $wlPos[count($wlPos)-1];

// Cubic Interpolation
for ($i=$minval;$i<=$maxval;$i++){
	$val = 0;
	$lastCtlPos = 0;
	if (in_array($i,$wlPos)){
		$lastCtlPos = array_search($i,$wlPos);
		$val = $wlVal[$lastCtlPos];
		$label = "";
	} else {
		if (($lastCtlPos>0)&&($lastCtlPos<count($wlPos)+1)){
			$y0 = $wlPos[$lastCtlPos-1];
			$y1 = $wlPos[$lastCtlPos];
			$y2 = $wlPos[$lastCtlPos+1];
			$y3 = $wlPos[$lastCtlPos+2];
			$mu = ($i-$y1)/($y2-$y1);
			$val = CubicInterpolate($y0, $y1, $y2, $y3, $mu);
		} else {
			$y0 = $wlPos[$lastCtlPos];
			$y1 = $wlPos[$lastCtlPos+1];
			$mu = ($i-$y0)/($y1-$y0);
			$mu2 = (1-cos($mu*PI))/2;
   		$val = (y0*(1-$mu2)+y1*$mu2);
   	}
   	
  }
  $data[] = array($label,$i,$val);
}
$graph->SetDataType("data-data");
$graph->SetTitle('Distribution of Bayes Values');
$graph->SetDrawXDataLabels(false);
$graph->SetPlotType("lines");
$graph->SetXLabelAngle(90);
$graph->SetXTitle('Bayes Value (%)');
$graph->SetYTitle('# of articles');
$graph->SetNumHorizTicks(0);
$graph->SetNumVertTicks(10);
$graph->SetLineWidth(1);
$graph->SetPlotAreaWorld(0,0,100,$max+5);
$graph->SetDataValues($data);
$graph->SetDataColors(array(array(0,128,0),array(128,0,0),"black","red"));

//Draw it
$graph->DrawGraph();

$data = array();
$minval = $blPos[0];
$maxval = $blPos[count($blPos)-1];
// Cubic Interpolation
for ($i=$minval;$i<=$maxval;$i++){
	$val = 0;
	$lastCtlPos = 0;
	if (in_array($i,$blPos)){
		$lastCtlPos = array_search($i,$blPos);
		$val = $blVal[$lastCtlPos];
		$label = "";
	} else {
		if (($lastCtlPos>0)&&($lastCtlPos<count($blPos)+1)){
			$y0 = $blPos[$lastCtlPos-1];
			$y1 = $blPos[$lastCtlPos];
			$y2 = $blPos[$lastCtlPos+1];
			$y3 = $blPos[$lastCtlPos+2];
			$mu = ($i-$y1)/($y2-$y1);
			$val = CubicInterpolate($y0, $y1, $y2, $y3, $mu);
		} else {
			$y0 = $blPos[$lastCtlPos];
			$y1 = $blPos[$lastCtlPos+1];
			$mu = ($i-$y0)/($y1-$y0);
			$mu2 = (1-cos($mu*PI))/2;
   		$val = (y0*(1-$mu2)+y1*$mu2);
   	}
   	
  }
  $data[] = array($label,$i,$val);
}
$graph->SetDataType("data-data");
$graph->SetTitle('');
$graph->SetDrawXDataLabels(false);
$graph->SetPlotType("lines");
$graph->SetXLabelAngle(90);
$graph->SetXTitle('');
$graph->SetYTitle('');
$graph->SetNumHorizTicks(0);
$graph->SetNumVertTicks(10);
$graph->SetLineWidth(1);
$graph->SetPlotAreaWorld(0,0,100,$max+5);
$graph->SetDataValues($data);
$graph->SetDataColors(array(array(128,0,0),array(0,128,0),"black","red"));
$graph->SetLegend(array("Blacklisted","Whitelisted"));
$graph->SetLegendPixels(500,0);
//Draw it
$graph->DrawGraph();

function CubicInterpolate($y0, $y1, $y2, $y3, $mu)
{
   $mu2 = $mu*$mu;
   $a0 = $y3 - $y2 - $y0 + $y1;
   $a1 = $y0 - $y1 - $a0;
   $a2 = $y2 - $y0;
   $a3 = $y1;

   return($a0*$mu*$mu2+$a1*$mu2+$a2*$mu+$a3);
}

//Print the image
$graph->PrintImage(); 
?>