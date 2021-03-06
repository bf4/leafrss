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
	Administrative Article List template
*}
{include file="header.tpl"}
<P>&nbsp</P>
<FORM ACTION="index.php?action=admmassupdate" METHOD=POST NAME="massupdate">
<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER WIDTH=85%>
	<TR>
		<TH COLSPAN=6>{$listTitle}</TH>
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
	<TD COLSPAN=6 CLASS="row1">{$prevNext}</TD>
</TR>
<TR>
	<TD CLASS="cat"><SPAN CLASS="cattitle">Article</SPAN></TD>
  	{if $isBWlist}
  		<TD CLASS="cat" ALIGN=CENTER><SPAN CLASS="cattitle">Bayes Score</SPAN></TD><TD CLASS="cat">&nbsp;</TD>
  		<TD CLASS="cat" ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/accept.gif" ALT="Accept"></TD>
  		<TD CLASS="cat" ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/deny.gif" ALT="Deny"></TD>
  		<TD CLASS="cat" ALIGN=CENTER><IMG SRC="{$ROOT_URL}template/{$templateName}/ignore.gif" ALT="Ignore">
  	{else}
  		<TD CLASS="cat" COLSPAN=5>&nbsp;
  	{/if}</TD>
  	</TR>
  	{section loop=$articles name=article}
  	{cycle values="row1,row2,row3" assign=cellClass}
  	<TR>
  		<TD CLASS="{$cellClass}">{include file="article.tpl"}</TD>
  		<TD CLASS="{$cellClass}">
  		{if $isBlack}
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admundoacc&itemID={$articles[article].itemID}';" VALUE="Add to Whitelist">
  		{elseif $isWhite}
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admundoden&itemID={$articles[article].itemID}';" VALUE="Add to Blacklist">
  			<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admign&itemID={$articles[article].itemID}';" VALUE="Ignore">
  		{else}
				<NOBR><SPAN CLASS="genmed">{$articles[article].bayesValue}</SPAN></NOBR></TD>
  			<TD CLASS="{$cellClass}"><NOBR><INPUT TYPE=BUTTON onClick="location.href='index.php?action=admacc&itemID={$articles[article].itemID}';" VALUE="Accept">&nbsp;
  				<INPUT TYPE=BUTTON onClick="location.href='index.php?action=admden&itemID={$articles[article].itemID}';" VALUE="Deny"></NOBR></TD>
  			<TD CLASS="wlist" ALIGN=CENTER><INPUT TYPE=CHECKBOX NAME="wlist[{$articles[article].itemID}]" onClick="document.massupdate.elements['blist[{$articles[article].itemID}]'].checked=false"></TD>
  			<TD CLASS="blist" ALIGN=CENTER><INPUT TYPE=CHECKBOX NAME="blist[{$articles[article].itemID}]" onClick="document.massupdate.elements['ignore[{$articles[article].itemID}]'].checked=false; document.massupdate.elements['wlist[{$articles[article].itemID}]'].checked=false"></TD>
  			<TD CLASS="ignore" ALIGN=CENTER><INPUT TYPE=CHECKBOX NAME="ignore[{$articles[article].itemID}]" onClick="document.massupdate.elements['blist[{$articles[article].itemID}]'].checked=false">
  		{/if}
  		</TD>
  	</TR>
  	{/section}
  {if $isBWlist}
  	<TR><TD COLSPAN=6 ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE="Update checked items"></TD></TR>
  {/if}
  <TR>
		<TD COLSPAN=6 CLASS="row1">{$prevNext}</TD>
	</TR>
	</TABLE>
</FORM>
{include file="footer.tpl"}
