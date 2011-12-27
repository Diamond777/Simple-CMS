<?
	$modul_menu_config = array(
		'id' => 999,
		'index' => 'mail',
		'access_lvl' => 999
	);


	if ($_SESSION['admin']['access_lvl'] >= $modul_menu_config['access_lvl']) {
		$A_MENU[$modul_menu_config['id']] = array(
			'img' => "null",
			'name' => "'Mail'",
			'href' => "'/admin/".$modul_menu_config['index']."/'",
		);
	}
?>