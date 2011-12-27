<?
	function check_user() {
		global $_SESSION, $_REQUEST, $ADMIN_PASSWORD;
		if (empty($_REQUEST['logout'])) {
			foreach($ADMIN_PASSWORD as $key => $val) {
				if (isset($_SESSION['admin']['password']) && $key == $_SESSION['admin']['password'] || isset($_REQUEST['password']) && $key == md5($_REQUEST['password'])) {
					$_SESSION['admin']['password'] = $key;
					$_SESSION['admin']['access_lvl'] = $val;
					return true;
				}
			}
			unset($_SESSION['admin']);
			return false;
		}
		else {
			unset($_SESSION['admin']);
			header('Location: '.ADMIN_SRC); exit;
		}
	}
?>