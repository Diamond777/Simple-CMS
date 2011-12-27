<?
	$MODULE_NAME = 'menu';
	require(MODULES_DIR.$MODULE_NAME.'/cfg.php');

	$menu = array();
	$menu_pages = array();

	$res = $db->query("SELECT * FROM `".$MODULE_TABLE['item']."` ORDER BY `item_sort`,`item_alias`;");

	if ($res) while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
		$href = ($data['item_alias'] != 'index') ? '/'.$data['item_alias'].'/' : '/';
		$menu[$data['menu_id']][] = array('alias' => $data['item_alias'], 'href' => $href, 'name' => $data['item_heading']);
		$menu_pages[$data['item_alias']] = $data['menu_id'];
	}

	if (count($menu)) foreach ($menu as $key => $val) $smarty->assign('menu'.$key, $val);
	$smarty->assign('menu_pages', $menu_pages);
?>