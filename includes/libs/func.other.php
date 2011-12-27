<?
	function getmicrotime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}


	function get_request_variables($request) {
		$arr = explode('/', $request);
		$res = array();
		foreach ($arr as $key => $val) {
			$exp = explode('#', $val);
			$exp = explode('?', $exp[0]);
			$val = str_replace(array('.html', '.htm', '.xml'), '', $exp[0]);
			$val = (!empty($val)) ? $val : false;

			if ($val != '') $res[] = $val;
		}
		return $res;
	}


	function clean_bufer() {
		global $HTML, $VAL;
		$HTML = '#'.$VAL.'#';
	}


	function valid_keywords($str) {
		$keys = array();
		$new_str = '';

		$str = str_replace( array('/', ',', '.', '/', '"', "'", '\\'), ' ', $str );
		$exp = explode(' ', $str);
		foreach ($exp as $key => $item) {
			if (empty($item) || strlen($item) < 2 || in_array($item, $keys)) continue;
			$keys[] = $item;
		}
		foreach ($keys as $key => $item) {
			if ($key) $new_str .= ' ';
			$new_str .= $item;
		}
		return $new_str;
	}


	function valid_title($str) {
return $str;
		$str = ucfirst($str);
		$first = myStrToUpOrLow( mb_substr($str, 0, 1, 'utf8'), 'upper' );
		return $first.mb_substr($str, 1, strlen($str), 'utf8');
	}


	function urlVariables($url = false) {
		global $_SERVER;

		if ($url === false) $url = $_SERVER['REQUEST_URI'];
		$res = array();

		$res['get'] = array();
		$res['request'] = array();
		$res['page'] = '';
		if (!($res['request'] = get_request_variables($url))) return false;

		$exp = explode('#', $url);
		$exp = explode('?', $exp[0]);
		$res['page'] = $exp[0];

		if (!empty($exp[1])) {
			$get_exp = explode('&', $exp[1]);
			foreach ($get_exp as $item) {
				if (empty($item)) continue;
				$exp = explode('=',$item);
				$res['get'][$exp[0]] = $exp[1];
			}
		}

		return $res;
	}


	function makeURLget($data) {
		if (!is_array($data) || !count($data)) return false;
		$res = array();
		foreach ($data as $key=>$item) $res[] = $key.'='.$item;
		return join('&',$res);
	}


	function replaceURLget($var, $val, $url = false) {
		$this_url = urlVariables($url);

		if (empty($val)) unset($this_url['get'][$var]);
		else $this_url['get'][$var] = $val;

		return (count($this_url['get'])) ? @$this_url['page'].'?'.makeURLget($this_url['get']) : $this_url['page'];
	}


	function send_error($error) {
		global $smarty, $template;
		$template = (!empty($template)) ? $template : 'index';
		if ($error == 404) {
			header('Status: 404 Not Found');
			header('HTTP/1.1 404 Not Found');
			$smarty->assign('TITLE', '404');
			$smarty->assign('content', '<h1>404</h1><p>Страница не найдена!</p>');
			$smarty->display($template.'.tpl');
			exit;
		}
	}


	function add_links($keyword, $url, $content, $modus_vivendi = true) {
		$_content = $content;

		if ($modus_vivendi) $find = "/(?:[^а-яa-z0-9-]|^)(".$keyword.")(?:[^а-яa-z0-9-]|$)/ui";
		else $find = "/(?:[^а-яa-z0-9-]|^)([а-яa-z0-9-]*".$keyword."[а-яa-z0-9-]*)(?:[^а-яa-z0-9-]|$)/ui";

		if (!preg_match_all($find, $_content, $matches)) return $_content;
		else {
			$array = array_unique($matches[1]);
			foreach ($array as $key => $item) {
				$_item = '<a href="'.$url.'" target="_blank">'.$item.'</a>';

				$find = "/([^а-яa-z0-9-]|^)(".$item.")([^а-яa-z0-9-]|$)/ui";
				$_content = preg_replace($find, "\\1".$_item."\\3", $_content);
			}
			return $_content;
		}
	}


	function make_captcha() {
		global $_SESSION;

		$x = round(rand(10, 50));
		$y = round(rand(1, 10));
		$z = round(rand(0, 1));
		if ($z) {
			$_SESSION['captcha'] = $x + $y;
			return '<span style="white-space:nowrap;">'.$x.' + '.$y.' = </span>';
		}
		else {
			$_SESSION['captcha'] = $x - $y;
			return '<span style="white-space:nowrap;">'.$x.' - '.$y.' = </span>';
		}
	}
?>