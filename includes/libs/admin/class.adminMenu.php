<?
	class adminMenu {
		var $modulesDir = MODULES_DIR;
		var $menu = array();
		var $items = '';


		function adminMenu() {
			$this->menu[0] = array(
				'img' => "null",
				'name' => "'Главная'",
				'href' => "'".ADMIN_SRC."'",
				'child' => ""
			);

			if (is_readable($this->modulesDir) && is_dir($this->modulesDir) && $dh = opendir($this->modulesDir)) {
				while (($file = readdir($dh)) !== false) {
					if ($file == '.' || $file == '..' || !is_dir($this->modulesDir.$file)) continue;
					$this->get_menu($this->modulesDir.$file);
				}
				closedir($dh);

				if (count($this->menu)) {
					ksort($this->menu);
					$this->items .= $this->add_child($this->menu, true);
				}
			}
		}


		function get_menu($dir) {
			global $db;

			$A_MENU = array();
			if (is_readable($dir.'/menu.php')) require_once($dir.'/menu.php');
			foreach ($A_MENU as $key => $item) $this->menu[$key] = $item;
		}


		function add_child($child, $all_sp = false) {
			$items = '';
			$i = 0;
			foreach ($child as $arr) {
				if (!is_array($arr)) {
					if ($arr == 'sp') {
						$items .= ',_cmSplit';
						continue;
					}
					else return false;
				}
				elseif ($all_sp && $i) $items .= ',_cmSplit';

				$items .= '
					,[
						'.$arr['img'].',
						'.$arr['name'].',
						'.$arr['href'].',
						null,
						'.$arr['name'];
				if (!empty($arr['child'])) $items .= $this->add_child($arr['child']);
				$items .= ']';
				$i++;
			}
			return $items;
		}


		function getHTML() {return "var myMenu=[".$this->items."]; cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');";}
	}
?>