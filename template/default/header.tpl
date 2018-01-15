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
	default template
	header.tpl
	page header template
*}
<HTML>
	<HEAD>
		{if $allowRSS == 1}<link rel="alternate" type="application/rss+xml" title="{$siteName}" href="{$ROOT_URL}index.php?output=rss" />{/if}
		<TITLE>{$siteName}</TITLE>
		<STYLE>{literal}
			BODY
			{
				background-color: #EEEEEE;
				font-family: Times New Roman;
				font-size: 12pt;
				background-image: url({/literal}{$ROOT_URL}{literal}template/default/background.jpg)
			}
			TD
			{
				font-family: Times New Roman;
				font-size: 12pt
			}
			A
			{
				font-weight: bold;
				color: #345CC4;
				text-decoration: none
			}
			A:hover
			{
				text-decoration: underline
			}
			.author
			{
				font-size: 8pt;
				font-weight: bold;
				font-style: italic
			}
			TD.topheader
			{
				font-size: 36pt;
				font-variant: small-caps;
				text-align: center
			}
			TD.leftsidebar
			{
				border-right: 1px solid #222222;
				font-size: 10pt;
				font-family: Arial;
				padding: 5px
			}
			.source
			{
				font-size: 7pt;
				font-family: Arial;
				font-weight: bold;
			}
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
			TD.enabledfeed
			{
				color: black;
				font-weight: bold
			}
			TD.disabledfeed
			{
				color: #A0A0A0;
			}
			.grey1
			{
				background: #DDDDDD
			}
			.grey2
			{
				background: #EEEEEE
			}{/literal}
		</STYLE>
</HEAD>
<BODY LEFTMARGIN=5 TOPMARGIN=5>
<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
	<TR>
		<TD WIDTH=100% CLASS="topheader">{$siteName}<HR></TD>
	</TR>
	<TR>
		<TD WIDTH=100% CLASS="main">