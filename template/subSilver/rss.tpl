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
	rss.tpl
	template for outputting rss format
*}
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
	<channel>
		<title>{$siteName}</title>
		<link>{$ROOT_URL}</link>
		<description>{$siteName}</description>
		<generator>LeafRSS v{$LEAF_RSS_VERSION}</generator>
	{if $copyright <> ""}	<copyright>{$copyright}</copyright>
	{/if}
{section loop=$columns name=column}
{assign var="articles" value=$columns[column]}
{section name=article loop=$articles}
  	<item>
	  		<title>{$articles[article].title}</title>
	  		<link>{$articles[article].link}</link>
	  		<source url="{$articles[article].sourceURL}">{$articles[article].feedDescription}</source>
	  		<description>{$articles[article].description}</description>
{if $articles[article].pubdate != ""}				<pubDate>{$articles[article].pubDate|date_format:"%a, %d %b %Y %H:%M:%S %z"}</pubDate>{/if}{if $articles[article].author != ""}
				<author>{$articles[article].author}</author>{/if}
			<guid{$articles[article].isPermalink}>{$articles[article].guid}</guid>
		</item>
	{/section}
{/section}
	</channel>
</rss>