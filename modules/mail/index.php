<?
	$MODULE_NAME = 'mail';
	$MODULE_HEADING = 'Mail';

	if (!empty($REQUEST[1]) && @file_exists(dirname(__FILE__).'/'.$REQUEST[1].'/'.$REQUEST[1].'.php')) {
		$HEADING = 'Отправить E-mail';

		$smarty->assign('heading',				$MODULE_HEADING.' <small><small>[ '.$HEADING.' ]</small></small>');
		$smarty->assign('heading_class',	'sections');

		require_once(dirname(__FILE__).'/'.$REQUEST[1].'/'.$REQUEST[1].'.php');


		$message = (!empty($error_message))?$error_message : '<br />';
		$smarty->assign('heading_page',		$HEADING);
		$smarty->assign('message',				$message);
		$smarty->assign('form_action',		$_SERVER['REQUEST_URI']);
		$smarty->assign('tpl2',						'standart_add_page');
	}
?>