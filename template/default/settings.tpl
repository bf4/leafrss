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
	adminlist.tpl
	Global Settings template
*}
{include file="header.tpl"}
<H2 ALIGN=CENTER>Global Settings Editor</H2>
{if $msg}
	<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
<FORM ACTION="index.php?action=settingsupdate" METHOD=POST>
<TABLE CELLPADDING=2 CELLSPACING=0 ALIGN=CENTER>
	<TR>
		<TH COLSPAN=2 class="grey1">Feed updating and expiration</TH>
	</TR>
	<TR>
		<TD ALIGN=RIGHT WIDTH=50% class="grey2">Use cron to update feeds?</TD>
		<TD WIDTH=50% class="grey2">{html_options name="useCron" options=$NoYes selected=$useCron}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Feed Update Interval:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="updateInterval" VALUE="{$updateInterval}" SIZE=5 MAXLENGTH=5> hour(s)</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Article Expiration Interval:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="expirationInterval" VALUE="{$expirationInterval}" SIZE=5 MAXLENGTH=5> hour(s)</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Automatically purge expired article on update?</TD>
		<TD class="grey2">{html_options name="autoExpireOnUpdate" options=$NoYes selected=$autoExpireOnUpdate}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Remove expired whitelisted articles on purge?</SPAN></TD>
		<TD class="grey2">{html_options name="purgeWLArticles" options=$NoYes selected=$purgeWLArticles}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Remove expired blacklisted articles on purge?</SPAN></TD>
		<TD class="grey2">{html_options name="purgeBLArticles" options=$NoYes selected=$purgeBLArticles}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Remove expired ignored articles on purge?</SPAN></TD>
		<TD class="grey2">{html_options name="purgeIgnored" options=$NoYes selected=$purgeIgnored}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Remove low ranking articles on purge?</SPAN></TD>
		<TD class="grey2">{html_options name="purgeLowScore" options=$NoYes selected=$purgeLowScore}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Use Bayes threshold for low rank purge?</SPAN></TD>
		<TD class="grey2">{html_options name="purgeUseBayes" options=$NoYes selected=$purgeUseBAyes}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Low Rank Purge Threshold:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="purgeLSThres" VALUE="{$purgeLSThres}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Automatically ignore articles without a description?</SPAN></TD>
		<TD class="grey2">{html_options name="autoIgnoreBlank" options=$NoYes selected=$autoIgnoreBlank}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Automatically ignore duplicate articles?</SPAN></TD>
		<TD class="grey2">{html_options name="duplicateCheck" options=$NoYes selected=$duplicateCheck}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Update Bayes score when whitelisting/blacklisting articles?</SPAN></TD>
		<TD class="grey2">{html_options name="autoBayesScore" options=$NoYes selected=$autoBayesScore}</TD>
	</TR>
	<TR>
		<TH COLSPAN=2 class="grey1">Display Settings</TH>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Number of columns:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="numColumns" VALUE="{$numColumns}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Maximum number of articles to display:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="maxArticles" VALUE="{$maxArticles}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Maximum number of articles to display on admin tools:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="admMaxArticles" VALUE="{$admMaxArticles}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Site Title:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="siteName" VALUE="{$siteName}" SIZE=32 MAXLENGTH=64></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Site Template:</TD>
		<TD class="grey2">{html_options name=templateName output=$selectTemplate values=$selectTemplate selected=$templateName}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Is site embedded in another page?</TD>
		<TD class="grey2">{html_options name="isEmbedded" options=$NoYes selected=$isEmbedded}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Time Zone Offset:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="timeZone" VALUE="{$timeZone}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="grey2"><SPAN CLASS="author">NOTE: Offset is from GMT</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Date Display Format:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="dateDisplay" VALUE="{$dateDisplay}" SIZE=32 MAXLENGTH=64></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="grey2"><SPAN CLASS="author">NOTE: Date formatting is for the PHP <A HREF="http://us.php.net/manual/en/function.date.php">date()</A> command</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Show Admin link on front page?</TD>
		<TD class="grey2">{html_options name="showAdminLink" options=$NoYes selected=$showAdminLink}</TD>
	</TR>
	<TR>
		<TH COLSPAN=2 class="grey1">RSS Output</TH>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Allow RSS feed output?</TD>
		<TD class="grey2">{html_options name="allowRSS" options=$NoYes selected=$allowRSS}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">RSS copyright information:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="copyright" VALUE="{$copyright}" SIZE=64 MAXLENGTH=255></TD>
	</TR>
	<TR>
		<TH COLSPAN=2 class="grey1">Filter Settings</TH>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2"><SPAN CLASS="gen">Bayes threshold:</SPAN></TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="bayesThreshold" VALUE="{$bayesThreshold}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TH COLSPAN=2 class="grey1">Administrative Authentication</TH>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Authentication Method:</TD>
		<TD class="grey2">{html_options name=authMethod output=$authDisplay values=$authValues selected=$authMethod}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Administrative user:</TD>
		<TD class="grey2"><INPUT TYPE=TEXT NAME="username" VALUE="{$username}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Current password:</TD>
		<TD class="grey2"><INPUT TYPE=PASSWORD NAME="curpass" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">New password:</TD>
		<TD class="grey2"><INPUT TYPE=PASSWORD NAME="newpass1" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="grey2">Confirm new password:</TD>
		<TD class="grey2"><INPUT TYPE=PASSWORD NAME="newpass2" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="grey2"><INPUT TYPE=SUBMIT VALUE="Update Settings"></TD>
	</TR>
</TABLE>
</FORM>
{include file="footer.tpl"}