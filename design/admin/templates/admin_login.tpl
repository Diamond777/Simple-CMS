{* Smarty *}
<!DOCTYPE html>
<html>
<head>
	<title>{$TITLE} - Админка</title>
	<meta http-equiv=Content-Language content=ru>
	<meta http-equiv=Content-Type content="text/html; charset=UTF-8">

	<link rel="stylesheet" href="{$ADMIN_DESIGN_SRC}css/admin_login.css" type="text/css" />
	<script language="JavaScript" src="{$ADMIN_DESIGN_SRC}js/script.js" type="text/javascript"></script>
</head>

<body onload="setFocus();">
	<div id="wrapper">
		<div id="header">
			<div id="joomla"><a href="./"><img border="0" src="/design/admin/images/header_text.png" /></a></div>
		</div>
	</div>

	<div id="ctr" align="center">
			<div class="login">
			<div class="login-form">
				<img src="/design/admin/images/login.gif" alt="Login" />
				<form method="post" name="loginForm" id="loginForm">
				<div class="form-block">
					<!--div class="inputlabel">Имя</div>
					<div><input title=" Введите ваше имя " name="usrname" type="text" class="inputbox" size="15" /></div-->
					<div class="inputlabel">Пароль</div>
					<div><input title=" Здесь введите пароль " name="password" type="password" class="inputbox" size="15" /></div>
					<div align="left"><input  title=" Нажмите сюда после ввода имени и пароля " type="submit" name="submit" class="button" value="Войти" /></div>
				</div>
				</form>
			</div>
			<div class="login-text">{$content}</div>
			<div class="clr"></div>
		</div>
	</div>
	<div id="break"></div>

	<noscript>Внимание! Javascript должен быть разрешен для нормального функционирования Админки</noscript>

	<!--div align="center" class="footer">
		<table width="99%" border="0">
			<tr>
				<td align="center">&copy; 2009</td>
			</tr>
		</table>
	</div-->
	</body>
</html>