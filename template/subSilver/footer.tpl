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
	footer.tpl
	page footer template
*}
		</TD>
	</TR>
</TABLE>
<TABLE WIDTH="100%">
{if ($action != "")||($showAdminLink == 1)}
	<TR>
		<TD ALIGN=RIGHT CLASS="copyright">
{if $action != ""}
<A HREF="index.php">Home</A>&nbsp;|&nbsp;
{/if}
{if $authMethod == "session"}
	{if $smarty.session.is_logged_in}
<A HREF="index.php?action=logout">Log Out</A>&nbsp;|&nbsp;
  {else}
<A HREF="index.php?action=login">Log In</A>&nbsp;|&nbsp;
  {/if}
{/if}
<A HREF="index.php?action=admin">Admin panel</A></TD>
	</TR>{/if}
	<TR>
		<TD CLASS="copyright" ALIGN=CENTER>Powered By <A HREF="http://www.leafrss.com/" TARGET=_blank><IMG SRC="images/leafrss.gif" BORDER=0 ALT="LeafRSS"></A> v{$LEAF_RSS_VERSION}</TD>
	</TR>
</TABLE>
</TD></TR></TABLE>
</BODY>
</HTML>
