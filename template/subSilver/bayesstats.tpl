{* /*************************************************

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
	admin.tpl
	Administrative Menu template
*}
{include file="header.tpl"}
{if $msg}
<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
{if ($listKeys <> 0)}
<P>&nbsp</P>
<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER WIDTH=85%>
	<TR>
		<TH COLSPAN=3>Bayes Statistics</TH>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Keyword statistics</SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="row1" ALIGN=CENTER><SPAN CLASS="gen">There are {$listKeys} bayes keywords in the database.</SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">Top 5 keywords: {$top5kw}</SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">Worst 5 keywords: {$bot5kw}</SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Whitelist statistics</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row1" WIDTH="33%" ALIGN=CENTER><SPAN CLASS="gen">Minimum Bayes Value</SPAN></TD>
		<TD CLASS="row1" WIDTH="34%" ALIGN=CENTER><SPAN CLASS="gen">Average Bayes Value</SPAN></TD>
		<TD CLASS="row1" WIDTH="33%" ALIGN=CENTER><SPAN CLASS="gen">Maximum Bayes Value</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$minWLBayes}</TD>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$avgWLBayes}</TD>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$maxWLBayes}</TD>
	</TR>
	<TR>
		<TD COLSPAN=3 CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Blacklist statistics</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row1" ALIGN=CENTER><SPAN CLASS="gen">Minimum Bayes Value</SPAN></TD>
		<TD CLASS="row1" ALIGN=CENTER><SPAN CLASS="gen">Average Bayes Value</SPAN></TD>
		<TD CLASS="row1" ALIGN=CENTER><SPAN CLASS="gen">Maximum Bayes Value</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$minBLBayes}</SPAN></TD>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$avgBLBayes}</SPAN></TD>
		<TD CLASS="row2" ALIGN=CENTER><SPAN CLASS="gen">{$maxBLBayes}</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Current Bayes Threshold</SPAN></TD>
		<TD CLASS="cat">&nsbp;</TD>
		<TD CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Recommended Bayes Threshold</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row2" ALIGN=CENTER COLSPAN=3><SPAN CLASS="gen">{$curBayes}</SPAN></TD>
		<TD CLASS="row2">&nbsp;</TD>
		<TD CLASS="row2" ALIGN=CENTER COLSPAN=3><SPAN CLASS="gen">{$recBayes}</SPAN></TD>
	</TR>
	<TR>
		<TD CLASS="row2" ALIGN=CENTER COLSPAN=3>
			<FORM METHOD=POST ACTION="index.php?action=bayesstats">
			<INPUT TYPE=HIDDEN NAME="bayesThres" VALUE="{$recBayes}">
			<INPUT TYPE=SUBMIT VALUE="Click here to set Bayes threshold to recommended value">
			</FORM>
		</TD>
	</TR>
	<TR>
		<TD COLSPAN=3 ALIGN=CENTER><IMG SRC="bayesgraph.php"></TD>
	</TR>
	<TR>
		<TD COLSPAN=3 ALIGN=CENTER><IMG SRC="bayeswordsgraph.php"></TD>
	</TR>
</TABLE>
{/if}
{include file="footer.tpl"}