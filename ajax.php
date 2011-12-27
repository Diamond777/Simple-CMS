<?
	header('Content-Type: text/json; charset=UTF-8');

	if (@$_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') exit;

	require_once(dirname(__FILE__).'/includes/config.php');
	require_once (INC_DIR.'libs/class.ajax.php');

	$ajax = array();
	if (isset($_REQUEST['query'])) switch ($_REQUEST['query']) {
		case 'subpath':
			require_once(dirname(__FILE__).'/ajax/'.$_REQUEST['query'].'.php');
			break;

		default: ajax::error();
	}

	ajax::response($ajax);
?>