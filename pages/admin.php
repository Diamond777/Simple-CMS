<?
	$smarty->template_dir = ADMIN_DESIGN_DIR.'templates/';

	if (check_user()) {
		$template = 'admin_index';

		require_once(INC_DIR.'libs/admin/class.adminMenu.php');
		$adminMenu = new adminMenu();
		$smarty->assign('ADMIN_MENU', $adminMenu->getHTML());

		if (!empty($REQUEST[1]) && is_readable(MODULES_DIR.$REQUEST[1].'/index.php')) require_once(MODULES_DIR.$REQUEST[1].'/index.php');
		else {
			$smarty->assign('heading',				'Панель управления');
			$smarty->assign('heading_class',	'cpanel');
			echo '<strong>Добро пожаловать в панель управления сайтом <a href="'.SITE.'">'.TITLE.'</a></strong>';
		}
	}
	else {
		$template = 'admin_login';
		echo '<div class="ctr"><img src="/design/admin/images/security.png" width="64" height="64" alt="security" /></div><p>Добро пожаловать!</p><p>Введите пароль для доступа в панель управления.</p>';
	}
?>