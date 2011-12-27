<?
	define('BASE_DIR'						,realpath(dirname(__FILE__).'/../').'/');
	define('SITE'								,'http://'.$_SERVER['HTTP_HOST'].'/');

	define('SQL_HOST'						,'localhost');
	define('SQL_USER'						,'wc_work');
	define('SQL_PASS'						,'husccexo_work');
	define('SQL_DB'							,'wc_work');



	define('ADMIN_EMAIL'				,'vit@firstpro.ru');

	define('SESSION_NAME'				,'test');
	define('TABLEPREFIX'				,SESSION_NAME.'_');


	session_name(SESSION_NAME);
	session_start();


	define('TITLE'							,'');
	define('DESCRIPTION'				,'');
	define('KEYWORDS'						,'');

################################################################################

	define('ADMIN_DIR'					,BASE_DIR.'admin/');
	define('DESIGN_DIR'					,BASE_DIR.'design/');
	define('INC_DIR'						,BASE_DIR.'includes/');
	define('MODULES_DIR'				,BASE_DIR.'modules/');
	define('PAGES_DIR'					,BASE_DIR.'pages/');
	define('UPLOAD_DIR'					,BASE_DIR.'upload/');
	define('LANG_DIR'						,INC_DIR.'languages/');
	define('SMARTY_DIR_ROOT'		,INC_DIR.'smarty/');
	define('SMARTY_DIR'					,SMARTY_DIR_ROOT.'smarty/');


	define('ADMIN_SRC'					,SITE.'admin/');
	define('DESIGN_SRC'					,SITE.'design/');
	define('UPLOAD_SRC'					,SITE.'upload/');


	define('SITE_DESIGN_DIR'		,DESIGN_DIR.'site/');
	define('SITE_DESIGN_SRC'		,DESIGN_SRC.'site/');
	define('ADMIN_DESIGN_DIR'		,DESIGN_DIR.'admin/');
	define('ADMIN_DESIGN_SRC'		,DESIGN_SRC.'admin/');
	define('DEFAULT_DESIGN_DIR'	,DESIGN_DIR.'default/');
	define('DEFAULT_DESIGN_SRC'	,DESIGN_SRC.'default/');


	define('ADMIN_SUP_DIR'			,BASE_DIR.'supplements/');
	define('ADMIN_SUP_SRC'			,SITE.'supplements/');

################################################################################

	require_once (INC_DIR.'libs/func.other.php');
	define('START_TIME'					,getmicrotime());
	require_once (INC_DIR.'libs/class.db.php');
	require_once (INC_DIR.'libs/func.files.php');
	require_once (INC_DIR.'libs/func.img.php');
	require_once (INC_DIR.'libs/func.output.php');
	require_once (INC_DIR.'libs/class.phpmailer.php');
	require_once (INC_DIR.'libs/class.lastMod.php');

	require_once (INC_DIR.'libs/admin/func.admin.php');
	require_once (INC_DIR.'libs/admin/class.form.php');

################################################################################

	$db = db::connect();
	define('LIMIT_ON_PAGE'			,10);

################################################################################

	$ADMIN_PASSWORD = array();
	$ADMIN_PASSWORD['a66abb5684c45962d887564f08346e8d'] = 0;
	$ADMIN_PASSWORD['ceae48a8e66abd24cef7d0e7bd9018bd'] = 600;
	$ADMIN_PASSWORD['af1117c147a1b8642cdbca9a4ff5d70d'] = 999;
?>