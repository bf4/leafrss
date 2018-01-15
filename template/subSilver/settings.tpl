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
	adminlist.tpl
	Global Settings template
*}
{include file="header.tpl"}
{if $msg}
	<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
<P>&nbsp;</P>
<FORM ACTION="index.php?action=settingsupdate" METHOD=POST>
<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER WIDTH=85%>
	<TR>
		<TH COLSPAN=2>Global Settings Editor</TH>
	<TR>
		<TD CLASS="cat" COLSPAN=2 ALIGN=CENTER><SPAN CLASS="cattitle">Feed updating and expiration</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT WIDTH=50% class="row1"><SPAN CLASS="gen">Use cron to update feeds?</SPAN></TD>
		<TD WIDTH=50% class="row1">{html_options name="useCron" options=$NoYes selected=$useCron}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Feed Update Interval:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="updateInterval" VALUE="{$updateInterval}" SIZE=5 MAXLENGTH=5> hour(s)</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Article Expiration Interval:</TD></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="expirationInterval" VALUE="{$expirationInterval}" SIZE=5 MAXLENGTH=5> hour(s)</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Automatically purge expired articles on update?</SPAN></TD>
		<TD class="row1">{html_options name="autoExpireOnUpdate" options=$NoYes selected=$autoExpireOnUpdate}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Remove expired whitelisted articles on purge?</SPAN></TD>
		<TD class="row1">{html_options name="purgeWLArticles" options=$NoYes selected=$purgeWLArticles}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Remove expired blacklisted articles on purge?</SPAN></TD>
		<TD class="row1">{html_options name="purgeBLArticles" options=$NoYes selected=$purgeBLArticles}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Remove expired ignored articles on purge?</SPAN></TD>
		<TD class="row1">{html_options name="purgeIgnored" options=$NoYes selected=$purgeIgnored}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Remove low ranking articles on purge?</SPAN></TD>
		<TD class="row1">{html_options name="purgeLowScore" options=$NoYes selected=$purgeLowScore}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Use Bayes threshold for low rank purge?</SPAN></TD>
		<TD class="row1">{html_options name="purgeUseBayes" options=$NoYes selected=$purgeUseBAyes}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Low Rank Purge Threshold:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="purgeLSThres" VALUE="{$purgeLSThres}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Automatically ignore articles without a description?</SPAN></TD>
		<TD class="row1">{html_options name="autoIgnoreBlank" options=$NoYes selected=$autoIgnoreBlank}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Automatically ignore duplicate articles?</SPAN></TD>
		<TD class="row1">{html_options name="duplicateCheck" options=$NoYes selected=$duplicateCheck}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Update Bayes score when whitelisting/blacklisting articles?</SPAN></TD>
		<TD class="row1">{html_options name="autoBayesScore" options=$NoYes selected=$autoBayesScore}</TD>
	</TR>
	<TR>
		<TD CLASS="cat" ALIGN=CENTER COLSPAN=2><SPAN CLASS="cattitle">Display Settings</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Number of columns:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="numColumns" VALUE="{$numColumns}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Maximum number of articles to display:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="maxArticles" VALUE="{$maxArticles}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Maximum number of articles to display on admin tools:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="admMaxArticles" VALUE="{$admMaxArticles}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Site Title:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="siteName" VALUE="{$siteName}" SIZE=32 MAXLENGTH=64></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Site Template:</SPAN></TD>
		<TD class="row1">{html_options name=templateName output=$selectTemplate values=$selectTemplate selected=$templateName}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Is site embedded in another page?</SPAN></TD>
		<TD class="row1">{html_options name="isEmbedded" options=$NoYes selected=$isEmbedded}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Time Zone Offset:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="timeZone" VALUE="{$timeZone}" SIZE=5 MAXLENGTH=5></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="row1"><SPAN CLASS="gensmall">NOTE: Offset is from GMT</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Date Display Format:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="dateDisplay" VALUE="{$dateDisplay}" SIZE=32 MAXLENGTH=64></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="row1"><SPAN CLASS="gensmall">NOTE: Date formatting is for the PHP <A HREF="http://us.php.net/manual/en/function.date.php" TARGET=blank>date()</A> command</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Show Admin link on front page?</SPAN></TD>
		<TD class="row1">{html_options name="showAdminLink" options=$NoYes selected=$showAdminLink}</TD>
	</TR>
	<TR>
		<TD COLSPAN=2 CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">RSS Output</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Allow RSS feed output?</SPAN></TD>
		<TD class="row1">{html_options name="allowRSS" options=$NoYes selected=$allowRSS}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">RSS copyright information:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="copyright" VALUE="{$copyright}" SIZE=64 MAXLENGTH=255></TD>
	</TR>
	<TR>
		<TD CLASS="cat" ALIGN=CENTER COLSPAN=2><SPAN CLASS="cattitle">Filter Settings</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Bayes threshold:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="bayesThreshold" VALUE="{$bayesThreshold}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD CLASS="cat" ALIGN=CENTER COLSPAN=2><SPAN CLASS="cattitle">Administrative Authentication</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Authentication Method:</SPAN></TD>
		<TD class="row1">{html_options name=authMethod output=$authDisplay values=$authValues selected=$authMethod}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Administrative user:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=TEXT NAME="username" VALUE="{$username}" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Current password:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=PASSWORD NAME="curpass" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">New password:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=PASSWORD NAME="newpass1" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT class="row1"><SPAN CLASS="gen">Confirm new password:</SPAN></TD>
		<TD class="row1"><INPUT TYPE=PASSWORD NAME="newpass2" VALUE="" SIZE=10 MAXLENGTH=10></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2 class="row1"><INPUT TYPE=SUBMIT VALUE="Update Settings"></TD>
	</TR>
</TABLE>
</FORM>
{include file="footer.tpl"}