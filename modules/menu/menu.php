<?
	require('cfg.php');

	if ($_SESSION['admin']['access_lvl'] == $modul_menu_config['access_lvl'] || $_SESSION['admin']['access_lvl'] >= 500) {
		$items = array();

################################## Меню сайта ##################################

		$res = $db->query("SELECT * FROM `".$MODULE_TABLE['menu']."` ORDER BY `menu_id`;");
		if ($res && $res->rowCount()) {
			$menu_items = array();
			$res_item = $db->query("SELECT * FROM `".$MODULE_TABLE['item']."` ORDER BY `item_sort`,`item_alias`;");
			while ($data = $res_item->fetch(PDO::FETCH_ASSOC)) $menu_items[$data['menu_id']][] = $data;

			while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
				$menu_childs = array();
				if (isset($menu_items[$data['menu_id']]) && count($menu_items[$data['menu_id']])) {
					foreach ($menu_items[$data['menu_id']] as $key => $arr) {
						$menu_childs[] = array(
							'img' => "'<img src=\"/design/admin/images/ThemeOffice/edit.png\" />'",
							'name' => "'".addslashes($arr['item_heading'])."'",
							'href' => "'/admin/".$modul_menu_config['index']."/item/add/?edit=".$arr['item_alias']."'",
							'child' => ""
						);
					}
					$menu_childs[] = 'sp';
				}
				$menu_childs[] = array(
					'img' => "'<img src=\"/design/admin/images/ThemeOffice/add_section.png\" />'",
					'name' => "'Добавить пункт меню'",
					'href' => "'/admin/".$modul_menu_config['index']."/item/add/?menu_id=".$data['menu_id']."'",
					'child' => ""
				);
				$menu_childs[] = array(
					'img' => "'<img src=\"/design/admin/images/ThemeOffice/edit.png\" />'",
					'name' => "'Редактировать пункты меню'",
					'href' => "'/admin/".$modul_menu_config['index']."/item/edit/?menu_id=".$data['menu_id']."'",
					'child' => ""
				);


				$items[] = array(
					'img' => "'<img src=\"/design/admin/images/ThemeOffice/menus.png\" />'",
					'name' => "'".addslashes($data['menu_heading'])."'",
					'href' => "'/admin/".$modul_menu_config['index']."/menu/add/?edit=".$data['menu_id']."'",
					'child' => $menu_childs
				);
			}
			$items[] = 'sp';
		}

		$items[] = array(
			'img' => "'<img src=\"/design/admin/images/ThemeOffice/add_section.png\" />'",
			'name' => "'Добавить меню'",
			'href' => "'/admin/".$modul_menu_config['index']."/menu/add/'",
			'child' => ""
		);
		$items[] = array(
			'img' => "'<img src=\"/design/admin/images/ThemeOffice/edit.png\" />'",
			'name' => "'Редактировать меню'",
			'href' => "'/admin/".$modul_menu_config['index']."/menu/edit/'",
			'child' => ""
		);

#################################### Сборка ####################################

		if ($_SESSION['admin']['access_lvl'] >= 500) {
			$items[] = 'sp';
			$items[] = array(
				'img' => "'<img src=\"/design/admin/images/ThemeOffice/config.png\" />'",
				'name' => "'Установка'",
				'href' => "'/admin/".$modul_menu_config['index']."/install/install/'",
				'child' => ""
			);
		}


		$A_MENU[$modul_menu_config['id']] = array(
			'img' => "null",
			'name' => "'".$modul_menu_config['heading']."'",
			'href' => "null",
			'child' => $items
		);
	}
?>