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

	subBlack template - made to look like subBlack for phpBB
	ignorelist.tpl
	Ignore List template
*}
{include file="header.tpl"}
<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER WIDTH=85%>
	<TR>
		<TH COLSPAN=3>Ignored Articles</TH>
	</TR>
	{capture assign=prevNext}
<TABLE WIDTH=100% ALIGN=CENTER CELLSPACING=0 CELLPADDING=2>
	<TR>
		<TD ALIGN=LEFT WIDTH=25% CLASS="row1">
		{if $startPrev != -1}
			<A HREF="{$ROOT_URL}index.php?action={$action}&start={$startPrev}" CLASS="genmed">&lt;&nbsp;Previous&nbsp;{$admMaxArticles}</A>
		{else}
			&nbsp;
		{/if}</TD>
		<TD ALIGN=CENTER WIDTH=50% CLASS="row1"><SPAN CLASS="genmed">There are a total of {$cnt} articles for this category.</SPAN></TD>
		<TD ALIGN=RIGHT WIDTH=25% CLASS="row1">
		{if $startNext != -1}
  			<A HREF="{$ROOT_URL}index.php?action={$action}&start={$startNext}" CLASS="genmed">Next&nbsp;{$admMaxArticles}&nbsp;&gt;</A>
  		{else}
  			&nbsp;
  		{/if}
  	</TD>
  </TR>
</TABLE>
{/capture}
<TR>
	<TD COLSPAN=2 CLASS="row1">{$prevNext}</TD>
</TR>
	<TR>
		<TD CLASS="cat"><SPAN CLASS="cattitle">Article</SPAN></TH>
		<TD CLASS="cat" COLSPAN=2>&nbsp;</TD>
	</TR>
	{section loop=$articles name=article}
  	{cycle values="row1,row2,row3" assign=cellClass}
	<TR>
		<TD CLASS="{$cellClass}">{include file="article.tpl"}</TD>
		<TD CLASS="{$cellClass}"><INPUT TYPE=BUTTON onClick="location.href='index.php?action=admignrem&itemID={$articles[article].itemID}';" VALUE="Remove From Ignore List"></TD>
	</TR>
	{/section}
	<TR>
	<TD COLSPAN=2 CLASS="row1">{$prevNext}</TD>
</TR>
</TABLE>
{include file="footer.tpl}