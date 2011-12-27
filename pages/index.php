<?
	$template = 'index';

	$SITE_TITLE = TITLE;
	$SITE_DESCRIPTION = DESCRIPTION;
	$SITE_KEYWORDS = KEYWORDS;

	$lastMod = new lastMod;

	require_once(INC_DIR.'ini.php');
	if (empty($menu_pages[$REQUEST[0]])) send_error(404);

################################################################################

	$MODULE_NAME = 'menu';
	require(MODULES_DIR.$MODULE_NAME.'/cfg.php');

	$value = (!empty($REQUEST[0])) ? $REQUEST[0] : send_error(404);

	$res = $db->query("SELECT * FROM `".$MODULE_TABLE['item']."` WHERE `item_alias`='".$value."' LIMIT 1;");
	if (!$res->rowCount()) send_error(404);

	$menu_exp_data = $res->fetch(PDO::FETCH_ASSOC);

	$lastMod->addDate($menu_exp_data['item_date']);

	$HTML = $menu_exp_data['item_text'];

	if (!preg_match_all("/(?:#([a-z0-9._-]+)#)/iU", $HTML, $matches));
	else foreach ($matches[1] as $key => $VAL) {
		$modul = '';
		if (!empty($VAL) && is_readable(PAGES_DIR.'modules/'.$VAL.'.php')) {ob_start(); require_once(PAGES_DIR.'modules/'.$VAL.'.php'); $modul = ob_get_clean();}
		$HTML = str_replace('#'.$VAL.'#', $modul, $HTML);
	}


	if (!preg_match_all("/(?:<h1>(.+)<\/h1>)/iU", $HTML, $matches));
	else foreach ($matches[1] as $key => $VAL) {
		$h1 = '<h1>'.firstSimbolToUp($VAL).'</h1>';
		$title = preg_replace("/<(.+)>/iU", '', $VAL);

		if (!empty($_GET['start']) && is_numeric($_GET['start'])) {
			$p = round($_GET['start'] / LIMIT_ON_PAGE) + 1;
			$SITE_TITLE = $VAL.' (страница '.$p.')'.' - '.$SITE_TITLE;
		}
		else $SITE_TITLE = $title.' - '.$SITE_TITLE;
		$SITE_KEYWORDS = $title.' '.$SITE_KEYWORDS;
		$HTML = str_replace('<h1>'.$VAL.'</h1>', $h1, $HTML);
	}

	if (!empty($menu_exp_data['item_title'])) $SITE_TITLE = $menu_exp_data['item_title'];
	if (!empty($menu_exp_data['item_description'])) $SITE_DESCRIPTION = $menu_exp_data['item_description'];
	if (!empty($menu_exp_data['item_keywords'])) $SITE_KEYWORDS = $menu_exp_data['item_keywords'];

################################################################################

//	echo crypt(BASE_DIR, CRYPT_MD5);
//	if (crypt(BASE_DIR, CRYPT_MD5) != '') exit;

################################################################################

	echo $HTML;

################################################################################

	$lastMod->send();

	$smarty->assign('TITLE', valid_title($SITE_TITLE));
	$smarty->assign('DESCRIPTION', $SITE_DESCRIPTION);
	$smarty->assign('KEYWORDS', valid_keywords($SITE_KEYWORDS));
	$smarty->assign('footer', $menu_exp_data['item_footer']);
?>