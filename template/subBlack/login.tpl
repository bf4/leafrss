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
	login.tpl
	Login screen template
*}
{include file="header.tpl"}
{if $smarty.session.login_msg}
	<H3>{$smarty.session.login_msg}</H3>
{/if}
	<FORM ACTION="index.php?action=login2" METHOD=POST NAME="login">
	<TABLE cellpadding="2" cellspacing="1" border="0" class="forumline" ALIGN=CENTER>
	<TR>
		<TH>Please log in</TH>
	</TR>
	<TR>
		<TD CLASS="row1" ALIGN=RIGHT><SPAN CLASS="gen">Username: <INPUT TYPE=TEXT SIZE=12 NAME="username"><BR>Password: <INPUT TYPE=PASSWORD SIZE=12 NAME="password"></SPAN></TD>
	</TR>
	<TR>
		<TD ALIGN=CENTER CLASS="row1"><INPUT TYPE=SUBMIT VALUE="Log In"></TD>
	</TR>
</TABLE>
</FORM>
	<SCRIPT TYPE="text/javascript">document.login.username.focus();</SCRIPT>
{include file="footer.tpl"}