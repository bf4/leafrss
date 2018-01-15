{*
/*************************************************

LeafRSS- the Learning Filtered RSS Aggregator
Author: Grant Electronics <leafrss@grantelectronics.com>
Copyright (c): 2008 Grant Electronics, all rights reserved
Version: 0.1

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
	subBlack template - made to look like subBlack for phpBB
	header.tpl
	page header
*}
<HTML>
	<HEAD>
		{if $allowRSS == 1}<link rel="alternate" type="application/rss+xml" title="{$siteName}" href="{$ROOT_URL}index.php?output=rss" />{/if}
		<TITLE>{$siteName}</TITLE>
		<STYLE>{literal}
			body {
      	background-color: #000000;
      	scrollbar-face-color: #212121;
      	scrollbar-highlight-color: #404040;
      	scrollbar-shadow-color: #000000;
      	scrollbar-3dlight-color: #616161;
      	scrollbar-arrow-color:  #A1A1A1;
      	scrollbar-track-color: #000000;
      	scrollbar-darkshadow-color: #000000;
			}
			font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
      a:link,a:active,a:visited { color : #FFFFCC; text-decoration: none; }
      a:hover		{ text-decoration: underline; color : #FFFFCC; }
			hr	{ height: 0px; border: solid #CCCCCC 0px; border-top-width: 1px;}
			.bodyline	{ background-color: #000000; border: 1px #212121 solid; }
			.maintitle,h1,h2	{
			font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
			text-decoration: none; line-height : 120%; color : #FFFFCC;
			}
      .forumlink		{ font-weight: bold; font-size: 12px; color : #CC9900; }
      a.forumlink 	{ text-decoration: none; color : #CC9900; }
      a.forumlink:visited { text-decoration: none; color : #CC9900; }
      a.forumlink:hover{ text-decoration: none; color : #FFFFCC; }
			.gen { font-size : 12px; }
      .genmed { font-size : 11px; }
      .gensmall { font-size : 10px; }
      .gen,.genmed,.gensmall { color : #FFFFCC; text-decoration: none; }
      a.gen,a.genmed,a.gensmall { color: #FFCC00; text-decoration: none; }
      a.gen:visited,a.genmed:visited,a.gensmall:visited	{ color: #FFCC00; text-decoration: none; }
      a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #FFFFCC; text-decoration: none; }
			textarea, select {
      	color : #FFFFCC;
      	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
      	border-color : #FFFFCC;
      }
      
      /* The text input fields background colour */
      input.post, textarea.post, select {
      	background-color : #000000;
      	color : #FFFFCC;
      }
      
      input { text-indent : 2px; 
      	color : #000000; }
      .copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFFCC; letter-spacing: -1px;}
      a.copyright		{ color: #FFCC00; text-decoration: none;}
      a.copyright:link   { color : #FFCC00; text-decoration: none;}
      a.copyright:visited { color : #FFCC00; text-decoration: none;}
      a.copyright:hover { color: #FFFFCC; text-decoration: underline;}
      th	{
      	color: #CC9900; font-size: 12px; font-weight : bold;
      	background-color: #000000; height: 25px;
      	background-image: url({/literal}{$ROOT_URL}{literal}template/subBlack/cellpic3.gif);
      }
      td.row1	{ background-color: #000000; }
			td.row2	{ background-color: #212121; }
			td.row3	{ background-color: #424242; }
			td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-color:#000000; border: #FFCC66; border-style: solid; height: 28px;
			background-image: url({/literal}{$ROOT_URL}{literal}template/subBlack/cellpic1.gif)
			}
      td.cat,td.catHead,td.catBottom {
      	height: 29px;
      	border-width: 0px 0px 0px 0px;
      }
      .cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #FFCC00}
      a.cattitle		{ text-decoration: none; color : #FFCC00; }
      a.cattitle:visited { text-decoration: none; color : #FFCC00; }
      a.cattitle:hover{  color: #FFFFCC; text-decoration: none; }
      .forumline	{ background-color: #000000; border: 2px #212121 solid; }
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
				<TD WIDTH=100% CLASS="main">