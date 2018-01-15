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
	main.tpl 
	Front Page template
*}
{include file="header.tpl"}
{if $err_msg != ""}
	<H3 ALIGN=CENTER>{$err_msg}</H3>
{else}
<TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=95% ALIGN=CENTER><TR>
{section loop=$columns name=column}
	<TD WIDTH={$colWidth}% VALIGN=TOP><TABLE CELLPADDING=5 CELLSPACING=0 WIDTH=100%>
	{assign var="articles" value=$columns[column]}
  {section name=article loop=$articles}
  	<TR><TD>
  		{include file="article.tpl"}
  	</TD></TR>
  {/section}
	</TABLE></TD>
{/section}
</TR></TABLE>
{/if}
{include file="footer.tpl"}