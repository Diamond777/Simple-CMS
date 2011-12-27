<?
	class URL {
		private function defaultURL($url) {
			if ($url === false) {
				global $_SERVER;
				return $_SERVER['REQUEST_URI'];
			}
			return $url;
		}



		public function get_request_variables($url) {
			$url = self::defaultURL($url);

			$arr = explode('/', $url);

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



		public function urlVariables($url = false) {
			$url = self::defaultURL($url);

			$res = array();

			$res['get'] = array();
			$res['request'] = array();
			$res['sharp'] = '';
			$res['page'] = '';
			$res['request'] = self::get_request_variables($url);
			
			if (!$res['request']) return false;

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
	}
?>