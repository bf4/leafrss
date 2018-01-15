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
	admin.tpl
	Administrative Menu template
*}
{include file="header.tpl"}
<H2 ALIGN=CENTER>Article Administration</H2>
{if $msg}
<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
<TABLE WIDTH=85% ALIGN=CENTER CELLPADDING=2 CELLSPACING=0 BORDER=0>
	<TR>
		<TD CLASS="grey1">Settings Editor</TD>
		<TD CLASS="grey1" WIDTH=150>{inputButton name="Edit Settings" action="settings"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2">Last update was on {$lastUpdate}</TD>
		<TD CLASS="grey2">{inputButton name="Force Update" confirm="Update may take several minutes. Are your sure you want to do this?" action="forceupdate"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey1">You are currently pulling articles from {$feedCount} feeds.</TD>
		<TD CLASS="grey1">{inputButton name="List Feeds" action="feedlist"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2">There are {$listAcc} articles that are to be shown but not on the whitelist.</TD>
		<TD CLASS="grey2">{inputButton name="List These Articles" action="admlistacc"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey1">There are {$listDen} articles that are not to be shown but not on the blacklist.</TD>
		<TD CLASS="grey1">{inputButton name="List These Articles" action="admlistden"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2">There are {$listWhite} articles that are whitelisted. ({$listTotWhite} total)</TD>
		<TD CLASS="grey2">{inputButton name="List These Articles" action="admlistwhite"}</TD>
	<TR>
		<TD CLASS="grey1">There are {$listBlack} articles that are blacklisted. ({$listTotBlack} total)</TD>
		<TD CLASS="grey1">{inputButton name="List These Articles" action="admlistblack"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2">There are {$listIgnore} articles that are ignored.</TD>
		<TD CLASS="grey2">{inputButton name="List These Articles" action="admlistign"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey1"><SPAN CLASS="gen">There are {$listExp} expired articles in the database. ({$listExpW} whitelisted, {$listExpB} blacklisted, {$listExpU} unknown)</SPAN></TD>
		<TD CLASS="grey1">{inputButton name="Purge Expired Articles" action="admpurgeexp" confirm="Are you sure you want to do this? This action cannot be undone!"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2">There are {$listLowrank} low ranking articles in the database.</TD>
		<TD CLASS="grey2">{inputButton name="Purge Low Rank Articles" action="admpurgelow" confirm="Are you sure you want to purge low ranking articles? This action cannot be undone!"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey1"><SPAN CLASS="gen">Last Bayes score update was on {$lastBayesUpdate}</SPAN></TD>
		<TD CLASS="grey1">{inputButton name="Update Bayes Score" action="forcebayesupdate" confirm="Update may take several minutes. Are your sure you want to do this?"}</TD>
	</TR>
	<TR>
		<TD CLASS="grey2"><SPAN CLASS="gen">There are {$listDis} articles from disabled feeds in the database.</SPAN></TD>
		<TD CLASS="grey2">&nbsp;</TD>
	</TR>
	<TR>
		<TD CLASS="grey1"><SPAN CLASS="gen">There are {$listAuto} articles from automatically whitelisted feeds in the database.</SPAN></TD>
		<TD CLASS="grey1">&nbsp;</TD>
	</TR>
	<TR>
		<TD CLASS="grey2"><SPAN CLASS="gen">There are {$listTotal} articles in the database.</SPAN></TD>
		<TD CLASS="grey2">&nbsp;</TD>
	</TR>
	<TR>
		<TD CLASS="grey1" COLSPAN=2 ALIGN=CENTER>{inputButton name="Bayes Statistics" action="bayesstats"}</TD>
	</TR>
</TABLE>
{include file="footer.tpl"}