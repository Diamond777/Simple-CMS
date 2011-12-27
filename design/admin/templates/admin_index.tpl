{* Smarty *}
<!DOCTYPE html>
<html>
{include file='admin_head.tpl'}
<body>
	<div id="wrapper">
		<div id="header"><a href="./"><img border="0" src="/design/admin/images/header_text.png" /></a></div>
	</div>

	<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="menubackgr" style="padding-left:5px;">
				<div id="myMenuID"></div>
	{include file='admin_menu.tpl'}
			</td>
			<!--td class="menubackgr" align="right">
				<div id="wrapper1">
					<div><a href="./" style="color: black; text-decoration: none;">0 <img src="/design/admin/images/nomail.png" align="middle" border="0" alt="Почта" /></a></div>
					<div>0 <img src="/design/admin/images/users.png" align="middle" alt="Пользователей он-лайн" /></div>
				</div>
			</td-->
			<td class="menubackgr" align="right" style="padding-right:5px;"><a style="color: #333333; font-weight: bold;" href="?logout=1">Выйти</a> <strong>admin</strong></td>
		</tr>
	</table>

	<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="menudottedline" width="40%"><div class="pathway"><a href="./"><strong>Админка</strong></a></div></td>
		<td class="menudottedline" align="right"></td>
	</tr>
	</table>

	<br />

	<div align="center" class="centermain">
		<div class="main">
			<table class="adminheading" border="0">
				<tr><th class="{$heading_class}">{$heading}</th></tr>
			</table>
			<table class="adminform">
				<tr>
					<td valign="top">{if isset($tpl2) && $tpl2=='standart_add_page'}{include file='admin_standart_add_page.tpl'}{else}{$content}{/if}</td>
				</tr>
			</table>
		</div>
	</div>

	<div align="center" class="footer">
		<table width="99%" border="0">
			<tr>
				<td align="center">
					<small>{$loaded_in}</small><br>
					&copy; 2009
				</td>
			</tr>
		</table>
	</div>
</body>
</html>