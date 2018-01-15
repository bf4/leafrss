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
	article.tpl
	Individual article display template
*}
{if $articles[article].imageURL != ""}
	<A HREF="{$articles[article].link}" TARGET=_blank>
	<IMG SRC="{$articles[article].imageURL}" ALIGN={$articles[article].imageAlign} WIDTH=120 BORDER=0></A>
{/if}
<A HREF="{$articles[article].link}" TARGET=_blank>{$articles[article].title}</A>
<BR><SPAN CLASS="source">Source: <A CLASS="source" HREF="{$articles[article].feedURL}">{$articles[article].feedDescription}</A></SPAN>
{if $articles[article].author != ""}
	<BR><SPAN CLASS="author">{$articles[article].author}</SPAN>
{/if}
{if $articles[article].pubDate != ""}
  <BR><SPAN CLASS="author"><B>Publish date:</B>&nbsp;{$articles[article].pubDate}</SPAN>
{/if}
<DT><font size="-1">{$articles[article].description}</font></DT>