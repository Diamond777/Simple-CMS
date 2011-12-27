<?
	$MODULE_NAME = 'modules';							# Имя папки модуля
	$MODULE_HEADING = 'Менеджер модулей';				# Название модуля

################################ Настройки меню ################################

	$modul_menu_config = array(
		'id' => 9000, 
		'index' => $MODULE_NAME,
		'heading' => $MODULE_HEADING,
		'access_lvl' => 0
	);

################################## Таблицы БД ##################################

	$MODULE_TABLE['menu']				= TABLEPREFIX.$MODULE_NAME.'_menu';
	$MODULE_TABLE['item']				= TABLEPREFIX.$MODULE_NAME.'_item';
	$MODULE_TABLE['item_params']		= TABLEPREFIX.$MODULE_NAME.'_item_params';
	$MODULE_TABLE['name']				= TABLEPREFIX.$MODULE_NAME.'_name';

############################## Размеры фотографий ##############################

	$PHOTO_SIZES = array('800x600', '100x0', '0x60', '0x150', '250x500');
?>