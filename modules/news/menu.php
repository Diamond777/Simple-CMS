<?
	require('cfg.php');

if (0) // удалить эту строку, чтоб подключить модуль новостей
	if ($_SESSION['admin']['access_lvl'] == $modul_menu_config['access_lvl'] || $_SESSION['admin']['access_lvl'] >= 500) {
##################################### item #####################################

		$item = array();

		$item[] = array(
			'img' => "'<img src=\"/design/admin/images/ThemeOffice/add_section.png\" />'",
			'name' => "'Добавить запись'",
			'href' => "'/admin/".$modul_menu_config['index']."/item/add/'",
			'child' => ""
		);
		$item[] = array(
			'img' => "'<img src=\"/design/admin/images/ThemeOffice/edit.png\" />'",
			'name' => "'Редактировать записи'",
			'href' => "'/admin/".$modul_menu_config['index']."/item/edit/'",
			'child' => ""
		);

################################## Установка ###################################

		$admin = array(
			'img' => "'<img src=\"/design/admin/images/ThemeOffice/config.png\" />'",
			'name' => "'Установка'",
			'href' => "'/admin/".$modul_menu_config['index']."/install/install/'",
			'child' => ""
		);

#################################### Сборка ####################################

		$childs = array();

		foreach ($item as $arr) $childs[] = $arr;

		if ($_SESSION['admin']['access_lvl'] >= 500) {
			$childs[] = 'sp';
			$childs[] = $admin;
		}

		$A_MENU[$modul_menu_config['id']] = array(
			'img' => "null",
			'name' => "'".$modul_menu_config['heading']."'",
			'href' => "null",
			'child' => $childs
		);
	}
?>