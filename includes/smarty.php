<?
	require_once (SMARTY_DIR.'Smarty.class.php');

	$smarty=new Smarty();

	$smarty->template_dir =								SITE_DESIGN_DIR.'templates/';
	$smarty->compile_dir =								SMARTY_DIR_ROOT.'templates_c/';
	$smarty->config_dir =									SMARTY_DIR_ROOT.'configs/';
	$smarty->cache_dir =									SMARTY_DIR_ROOT.'cache/';

	$smarty->debugging = false;

	$smarty->assign('SITE'								,SITE);
	$smarty->assign('ADMIN_SRC'						,ADMIN_SRC);


	$smarty->assign('SITE_DESIGN_SRC'			,SITE_DESIGN_SRC);
	$smarty->assign('ADMIN_DESIGN_SRC'		,ADMIN_DESIGN_SRC);
	$smarty->assign('DEFAULT_DESIGN_SRC'	,DEFAULT_DESIGN_SRC);


	$smarty->assign('TITLE'								,TITLE);
	$smarty->assign('DESCRIPTION'					,DESCRIPTION);
	$smarty->assign('KEYWORDS'						,KEYWORDS);

	$smarty->assign('ADMIN_EMAIL'					,ADMIN_EMAIL);
?>