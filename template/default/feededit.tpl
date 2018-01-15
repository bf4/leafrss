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
	feededit.tpl
	Feed Editor template
*}
{include file="header.tpl"}
<H2 ALIGN=CENTER>RSS Feed Editor</H2>
{if $msg != ""}
<H3 ALIGN=CENTER>{$msg}</H3>
{/if}
<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=1>
  <TR>
  	<TD ALIGN=CENTER COLSPAN=9 CLASS="cat"><SPAN CLASS="cattitle">Current feeds</SPAN></TD>
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
	<TR>
		<TD CLASS="{$feeds[feed].rowcolor}">{$feeds[feed].Description}</TD>
		<TD CLASS="{$feeds[feed].rowcolor}" ALIGN=CENTER>{$feeds[feed].wlCount}</TD>
		<TD CLASS="{$feeds[feed].rowcolor}" ALIGN=CENTER>{$feeds[feed].blCount}</TD>
		<TD><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feed{$feeds[feed].togglefeed|lower}&feedID={$feeds[feed].feedID}';" VALUE="{$feeds[feed].togglefeed}"></TD>
		<TD><A HREF="{$feeds[feed].URL}" TARGET=_blank>View Feed</A></TD>
		<TD><A HREF="{$feeds[feed].homePageURL}" TARGET=_blank>View Home Page</A></TD>
		<TD><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feededit&feedID={$feeds[feed].feedID}';" VALUE="Edit Feed"></TD>
		<TD><INPUT TYPE=BUTTON onClick="if(confirm('This will remove all articles from this feed as well.\nAre you sure you want to do this?')){literal}{{/literal}location.href='index.php?action=feedremove&feedID={$feeds[feed].feedID}';{literal}}{/literal}" VALUE="Remove Feed">
		<TD><INPUT TYPE=BUTTON onClick="location.href='index.php?action=feeddata&feedID={$feeds[feed].feedID}';" VALUE="Update Feed Data"></TD>
	</TR>
{/section}
</TABLE>
<FORM ACTION="index.php?action={$feedaction}" METHOD=POST NAME="feededit">
{if $action == "feededit"}
<INPUT TYPE=HIDDEN NAME="feedID" VALUE="{$feedID}">
{/if}
<H3>{$formTitle}<HR></H3>
<TABLE CELLSPACING=0 CELLPADDING=2>
	<TR>
		<TD ALIGN=RIGHT>Feed Description:</TD>
		<TD><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="Description" VALUE="{$Description}"></TD>
	</TR>
	<TR>
    <TD ALIGN=RIGHT>Feed URL:</TD>
    <TD><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="URL" VALUE="{$URL}"></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT>Feed Home Page URL:</TD>
		<TD><INPUT TYPE=TEXT SIZE=64 MAXLENGTH=128 NAME="homePageURL" VALUE="{$homePageURL}"></TD>
	</TR>
	<TR>
		<TD ALIGN=RIGHT>Notes:</TD>
		<TD><TEXTAREA NAME="notes" COLS=64 ROWS=5>{$notes}</TEXTAREA></TD>
	</TR>
	<TR>
		<TD>&nbsp;</TD>
		<TD><INPUT TYPE=CHECKBOX NAME="isAutoWL" onClick="if(document.feededit.isAutoWL.checked){literal}{
				document.feededit.isAutoIgnored.disabled = false;
			} else {
				document.feededit.isAutoIgnored.disabled = true;
				document.feededit.isAutoIgnored.checked = false;}"{/literal}{$isAutoWL}>Automatically whitelist</TD>
	</TR>
	<TR>
		<TD>&nbsp;</TD>
		<TD><INPUT TYPE=CHECKBOX NAME="isAutoIgnored"{$isAutoIgnored}>Do not learn from this feed</TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER COLSPAN=2><INPUT TYPE=SUBMIT VALUE="{$buttonName} Feed"></TD>
	</TR>
</TABLE>
</FORM>
{include file="footer.tpl"}