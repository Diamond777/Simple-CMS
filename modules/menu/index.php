<?
	require(dirname(__FILE__).'/'.'cfg.php');

	if (!empty($REQUEST[3]) && @file_exists(dirname(__FILE__).'/inc/'.$REQUEST[2].'.php')) {
		switch ($REQUEST[2]) {
			case 'install':	$HEADING = 'Установка'; break;
			case 'menu':		$HEADING = ($REQUEST[3] == 'edit') ? 'Редактировать меню' : 'Добавить меню'; break;
			case 'item':		$HEADING = ($REQUEST[3] == 'edit') ? 'Редактировать пункты меню' : 'Добавить пункт меню'; break;
			default: header('Location: /'.$REQUEST[0].'/'); exit;
		}

		$smarty->assign('heading',				$MODULE_HEADING.' <small><small>[ '.$HEADING.' ]</small></small>');
		$smarty->assign('heading_class',	'sections');
		require_once(dirname(__FILE__).'/inc/'.$REQUEST[2].'.php');

		if ($REQUEST[3] == 'add') {
			$message = (!empty($message)) ? $message : '';
			$smarty->assign('heading_page',		$HEADING);
			$smarty->assign('message',				$message);
			$smarty->assign('form_action',		$_SERVER['REQUEST_URI']);
			$smarty->assign('tpl2',						'standart_add_page');
		}
	}
?>