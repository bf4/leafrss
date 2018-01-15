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

	subBlack template - made to look like subBlack for phpBB
	feededit.tpl
	Feed Editor template
*}
{include file="header.tpl"}
{if $msg != ""}
<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
<P>&nbsp;</P>
<FORM ACTION="index.php?action={$feedaction}" METHOD=POST NAME="feededit">
{if $action == "feededit"}
<INPUT TYPE=HIDDEN NAME="feedID" VALUE="{$feedID}">
{/if}
<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER WIDTH=85%>
  <TR>
  	<TH COLSPAN=7>RSS Feed Editor</TH>
  </TR>
  <TR>
  	<TD ALIGN=CENTER COLSPAN=7 CLASS="cat"><SPAN CLASS="cattitle">Current feeds</SPAN></TD>
  </TR>
  <TR>
  	<TH STYLE="cursor: pointer" onClick="javascript: location.href='index.php?action=feedlist&sortby=desc{$descorder}';"><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "descasc"}asc{else}blank{/if}.gif"><BR>Feed Description<BR><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "descdesc"}desc{else}blank{/if}.gif"></TH>
  	<TH STYLE="cursor: pointer" onClick="javascript: location.href='index.php?action=feedlist&sortby=wl{$wlorder}';"><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "wlasc"}asc{else}blank{/if}.gif"><BR>Whitelist<BR>count<BR><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "wldesc"}desc{else}blank{/if}.gif"></TH>
  	<TH STYLE="cursor: pointer" onClick="javascript: location.href='index.php?action=feedlist&sortby=bl{$blorder}';"><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "blasc"}asc{else}blank{/if}.gif"><BR>Blacklist<BR>count<BR><IMG SRC="{$ROOT_URL}template/{$templateName}/{if $sortby == "bldesc"}desc{else}blank{/if}.gif"></TH>
  	<TH>Disable</TH>
  	<TH>View</TH>
  	<TH>Home Page</TH>
  	<TH COLSPAN=3>Tools</TH>
  </TR>
{section loop=$feeds name=feed}
{cycle values="row1,row2,row3" assign=cellClass}
	<TR>
		<TD CLASS="{$cellClass}"><SPAN CLASS="gen"><SPAN CLASS="{$feeds[feed].rowcolor}">{$feeds[feed].Description}</SPAN></SPAN></TD>
		<TD CLASS="{$cellClass}" ALIGN=CENTER><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feed{$feeds[feed].togglefeed|lower}&feedID={$feeds[feed].feedID}';" VALUE="{$feeds[feed].togglefeed}"></TD>
		<TD CLASS="{$cellClass}" ALIGN=CENTER><A HREF="{$feeds[feed].URL}" TARGET=_blank CLASS="gen">View Feed</A></TD>
		<TD CLASS="{$cellClass}" ALIGN=CENTER><A HREF="{$feeds[feed].homePageURL}" TARGET=_blank CLASS="gen">View Home Page</A></TD>
		<TD CLASS="{$cellClass}" ALIGN=CENTER><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feededit&feedID={$feeds[feed].feedID}';" VALUE="Edit Feed"></TD>
		<TD CLASS="{$cellClass}" ALIGN=CENTER><INPUT TYPE=BUTTON onClick="if(confirm('This will remove all articles from this feed as well.\nAre you sure you want to do this?')){literal}{{/literal}location.href='index.php?action=feedremove&feedID={$feeds[feed].feedID}';{literal}}{/literal}" VALUE="Remove Feed">
		<TD CLASS="{$cellClass}" ALIGN=CENTER><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feeddata&feedID={$feeds[feed].feedID}';" VALUE="Update Feed Data"></TD>
	</TR>
{/section}
	<TR>
		<TD ALIGN=CENTER COLSPAN=7 CLASS="cat"><SPAN CLASS="cattitle">{$formTitle}</TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT COLSPAN=2 CLASS="row1"><SPAN CLASS="gen">Feed Description:</SPAN></TD>
		<TD COLSPAN=5 CLASS="row1"><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="Description" VALUE="{$Description}"></TD>
	</TR>
	<TR>
    <TD ALIGN=RIGHT COLSPAN=2 CLASS="row1"><SPAN CLASS="gen">Feed URL:</SPAN></TD>
    <TD COLSPAN=5 CLASS="row1"><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="URL" VALUE="{$URL}"></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT COLSPAN=2 CLASS="row1"><SPAN CLASS="gen">Feed Home Page URL:</SPAN></TD>
		<TD COLSPAN=5 CLASS="row1"><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="homePageURL" VALUE="{$homePageURL}"></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT COLSPAN=2 CLASS="row1"><SPAN CLASS="gen">Notes:</SPAN></TD>
		<TD COLSPAN=5 CLASS="row1"><TEXTAREA NAME="notes" COLS=64 ROWS=5>{$notes}</TEXTAREA></TD>
	</TR>
	<TR>
		<TD COLSPAN=2 CLASS="row1">&nbsp;</TD>
		<TD COLSPAN=5 CLASS="row1"><INPUT TYPE=CHECKBOX NAME="isAutoWL" onClick="if(document.feededit.isAutoWL.checked){literal}{
				document.feededit.isAutoIgnored.disabled = false;
			} else {
				document.feededit.isAutoIgnored.disabled = true;
				document.feededit.isAutoIgnored.checked = false;}"{/literal}{$isAutoWL}><SPAN CLASS="gen">Automatically whitelist</SPAN></TD>
	</TR>
	<TR>
		<TD COLSPAN=2 CLASS="row1">&nbsp;</TD>
		<TD COLSPAN=5 CLASS="row1"><INPUT TYPE=CHECKBOX NAME="isAutoIgnored"{$isAutoIgnored}><SPAN CLASS="gen">Do not learn from this feed</SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=7 CLASS="row1"><INPUT TYPE=SUBMIT VALUE="{$buttonName} Feed"></TD>
	</TR>
</TABLE>
</FORM>
{include file="footer.tpl"}