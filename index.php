<?
	require_once(dirname(__FILE__).'/includes/config.php');
	require_once(INC_DIR.'smarty.php');

	header("Content-type: text/html; charset=UTF-8");


	$REQUEST = get_request_variables($_SERVER['REQUEST_URI']);
	if (!count($REQUEST)) $REQUEST[0] = 'index';


	ob_start();

	switch ($REQUEST[0]) {
		case 'admin': require_once(PAGES_DIR.$REQUEST[0].'.php'); break;
		case 'captcha': require_once(INC_DIR.'libs/func.'.$REQUEST[0].'.php'); captcha(); break;
		default: require_once(PAGES_DIR.'index.php');
	}

	$content = ob_get_clean();

	if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {echo getResponse(array('html' => $content, 'alias' => $REQUEST[0], 'status' => 'done')); exit;}

	$smarty->assign('content', $content);

	$smarty->assign('REQUEST', $REQUEST);
	$smarty->assign('loaded_in', get_loaded_in());

	$smarty->display($template.'.tpl');
?>