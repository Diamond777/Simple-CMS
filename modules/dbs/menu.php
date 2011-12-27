<?
	$modul_menu_config = array(
		'id' => 1000,
		'index' => 'dbs',
		'access_lvl' => 999
	);


	if ($_SESSION['admin']['access_lvl'] >= $modul_menu_config['access_lvl']) {
		$A_MENU[$modul_menu_config['id']] = array(
			'img' => "null",
			'name' => "'DBSearch'",
			'href' => "'/admin/".$modul_menu_config['index']."/'",
			'child' => ""
		);
	}
?>