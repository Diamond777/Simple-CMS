<?
	$MODULE_NAME = 'menu';										# Имя папки модуля
	$MODULE_HEADING = 'Менеджер меню';				# Название модуля

################################ Настройки меню ################################

	$modul_menu_config = array(
		'id' => 20,
		'index' => $MODULE_NAME,
		'heading' => $MODULE_HEADING,
		'access_lvl' => 0
	);

################################## Таблицы БД ##################################

	$MODULE_TABLE['menu']				= TABLEPREFIX.$MODULE_NAME.'_menu';
	$MODULE_TABLE['item']				= TABLEPREFIX.$MODULE_NAME.'_item';

############################## Размеры фотографий ##############################

	$PHOTO_SIZES = array('800x600', '100x0', '0x60', '0x150', '250x500');
?>