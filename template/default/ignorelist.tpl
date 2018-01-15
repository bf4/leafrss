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
	ignorelist.tpl
	Ignore List template
*}
{include file="header.tpl"}
<H2 ALIGN=CENTER>Ignored Articles</H2>
{capture assign=prevNext}
<TABLE WIDTH=90%>
	<TR>
		<TD ALIGN=LEFT WIDTH=25%>
		{if $startPrev != -1}
			<A HREF="{$ROOT_URL}index.php?action={$action}&start={$startPrev}">&lt;&nbsp;Previous&nbsp;{$admMaxArticles}</A>
		{else}
			&nbsp;
		{/if}</TD>
		<TH WIDTH=50%>There are a total of {$cnt} articles for this category.</TH>
		<TD ALIGN=RIGHT WIDTH=25%>
		{if $startNext != -1}
  			<A HREF="{$ROOT_URL}index.php?action={$action}&start={$startNext}">Next&nbsp;{$admMaxArticles}&nbsp;&gt;</A>
  		{else}
  			&nbsp;
  		{/if}
  	</TD>
  </TR>
</TABLE>
{/capture}
{$prevNext}
<TABLE WIDTH=90% CELLSPACING=0 CELLPADDING=2>
	<TR>
		<TH>Article</TH>
		<TH COLSPAN=2>&nbsp;</TH>
	</TR>
	{section loop=$articles name=article}
  	{cycle values="grey1,grey2" assign=cellClass}
	<TR>
		<TD CLASS="{$cellClass}">{include file="article.tpl"}</TD>
		<TD CLASS="{$cellClass}"><INPUT TYPE=BUTTON onClick="location.href='index.php?action=admignrem&itemID={$articles[article].itemID}';" VALUE="Remove From Ignore List"></TD>
	</TR>
	{/section}
</TABLE>
{$prevNext}
{include file="footer.tpl}