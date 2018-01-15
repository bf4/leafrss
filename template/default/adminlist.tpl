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
	Administrative Article List template
*}
{include file="header.tpl"}
<H2 ALIGN=CENTER>{$listTitle}</H2>
{capture assign=prevNext}
<TABLE WIDTH=90% ALIGN=CENTER>
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
<FORM ACTION="index.php?action=admmassupdate" METHOD=POST NAME="massupdate">
	<TABLE WIDTH=90% ALIGN=CENTER CELLSPACING=0 CELLPADDING=2 BORDER=0>
  	<TR><TH>Article</TH>
  	{if $isBWlist}
  		<TH>Score</TH><TH>&nbsp;</TH>
  		<TH ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/accept.gif" ALT="Accept"></TH>
  		<TH ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/deny.gif" ALT="Deny"></TH>
  		<TH ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/ignore.gif" ALT="Ignore">
  	{else}
  		<TH>&nbsp;
  	{/if}</TH>
  	</TR>
  	{section loop=$articles name=article}
  	{cycle values="grey1,grey2" assign=cellClass}
  	<TR>
  		<TD CLASS="{$cellClass}">{include file="article.tpl"}</TD>
  		<TD CLASS="{$cellClass}">
  		{if $isBlack}
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admacc&itemID={$articles[article].itemID}';" VALUE="Remove From Blacklist">
  		{elseif $isWhite}
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admden&itemID={$articles[article].itemID}';" VALUE="Remove From Whitelist">
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admign&itemID={$articles[article].itemID}';" VALUE="Ignore">
  		{else}
  			<NOBR>{$articles[article].bayesValue}</NOBR></TD>
  			<TD CLASS="{$cellClass}"><NOBR><INPUT TYPE=BUTTON onClick="location.href='index.php?action=admacc&itemID={$articles[article].itemID}';" VALUE="Accept">&nbsp;
  				<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admden&itemID={$articles[article].itemID}';" VALUE="Deny"></NOBR></TD>
  			<TD CLASS="wlist"><INPUT TYPE=CHECKBOX NAME="wlist[{$articles[article].itemID}]" onClick="document.massupdate.elements['blist[{$articles[article].itemID}]'].checked=false"></TD>
  			<TD CLASS="blist"><INPUT TYPE=CHECKBOX NAME="blist[{$articles[article].itemID}]" onClick="document.massupdate.elements['ignore[{$articles[article].itemID}]'].checked=false; document.massupdate.elements['wlist[{$articles[article].itemID}]'].checked=false"></TD>
  			<TD CLASS="ignore"><INPUT TYPE=CHECKBOX NAME="ignore[{$articles[article].itemID}]" onClick="document.massupdate.elements['blist[{$articles[article].itemID}]'].checked=false">
  		{/if}
  		</TD>
  	</TR>
  	{/section}
  {if $isBWlist}
  	<TR><TD COLSPAN=6 ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE="Update checked items"></TD></TR>
  {/if}
	</TABLE>
</FORM>
{$prevNext}
{include file="footer.tpl"}
