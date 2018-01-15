{*
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
	subSilver template - made to look like subSilver for phpBB
	header.tpl
	page header
*}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
	<HEAD>
		{if $allowRSS == 1}<link rel="alternate" type="application/rss+xml" title="{$siteName}" href="{$ROOT_URL}index.php?output=rss">{/if}
		<TITLE>{$siteName}</TITLE>
		<STYLE TYPE="text/css">{literal}
			body {
      	background-color: #E5E5E5;
      	scrollbar-face-color: #DEE3E7;
      	scrollbar-highlight-color: #FFFFFF;
      	scrollbar-shadow-color: #DEE3E7;
      	scrollbar-3dlight-color: #D1D7DC;
      	scrollbar-arrow-color:  #006699;
      	scrollbar-track-color: #EFEFEF;
      	scrollbar-darkshadow-color: #98AAB1;
			}
			font,th,td,p {
				font-family: Verdana, Arial, Helvetica, sans-serif
			}
			a:link,a:active,a:visited { 
				color : #006699;
			}
			a:hover { 
				text-decoration: underline;
				color : #DD6900; 
			}
			hr	{
				height: 0px; 
				border: solid #D1D7DC 0px; 
				border-top-width: 1px;
			}
			.bodyline	{ 
				background-color: #FFFFFF; 
				border: 1px #98AAB1 solid; 
			}
			.maintitle	{
      	font-weight: bold; 
				font-size: 22px; 
				font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
      	text-decoration: none; 
				line-height : 120%; 
				color : #000000;
      }
      .forumlink { 
				font-weight: bold; 
				font-size: 12px; 
				color : #006699;
			}
      a.forumlink {
				text-decoration: none; 
				color : #006699;
			}
      a.forumlink:hover { 
				text-decoration: underline; 
				color : #DD6900;
			}
			.gen { font-size : 12px; }
      .genmed { font-size : 11px; }
      .gensmall { font-size : 10px; }
      .gen,.genmed,.gensmall { color : #000000; }
      a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
      a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #DD6900; text-decoration: underline; }
			input,textarea, select {
      	color : #000000;
      	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
      	border-color : #000000;
      }
      .copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
      a.copyright		{ color: #444444; text-decoration: none;}
      a.copyright:hover { color: #000000; text-decoration: underline;}
      th	{
      	color: #FFA34F; font-size: 11px; font-weight : bold;
      	background-color: #006699; height: 25px;
      	background-image: url({/literal}{$ROOT_URL}{literal}template/subSilver/cellpic3.gif);
      }
      td.row1	{ background-color: #EFEFEF; }
			td.row2	{ background-color: #DEE3E7; }
			td.row3	{ background-color: #D1D7DC; }
			td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
      			background-image: url({/literal}{$ROOT_URL}{literal}template/subSilver/cellpic1.gif);
      			background-color:#D1D7DC; border: #FFFFFF; border-style: solid; height: 28px;
      }
      td.cat,td.catHead,td.catBottom {
      	height: 29px;
      	border-width: 0px 0px 0px 0px;
      }
      .cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #006699}
      a.cattitle		{ text-decoration: none; color : #006699; }
      a.cattitle:hover{ text-decoration: underline; }
      .forumline	{ background-color: #FFFFFF; border: 2px #006699 solid; }
			TD.wlist
			{
				background: #568E48
			}
			TD.blist
			{
				background: #8E0000
			}
			TD.ignore
			{
				background: #008E8E
			}
			.enabledfeed
			{
				color: black;
				font-weight: bold
			}
			.disabledfeed
			{
				color: #A0A0A0;
			}
			{/literal}
		</STYLE>
</HEAD>
<BODY BGCOLOR="#E5E5E5" TEXT="#000000" LINK="#006699" VLINK="#5493B4">
<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="10" BORDER="0" ALIGN="CENTER">
	<TR>
		<TD CLASS="bodyline">
			<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
			<TR>
				<TD ALIGN="CENTER" WIDTH="100%" VALIGN="MIDDLE"><SPAN CLASS="maintitle">{$siteName}</SPAN></TD>
			</TR>	
			<TR>
				<TD WIDTH="100%" CLASS="main">