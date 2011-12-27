<?
	$MODULE_NAME = 'news';
	require(MODULES_DIR.$MODULE_NAME.'/cfg.php');

	$news = array();
	$limit = 3;

	$res = $db->query("SELECT * FROM `".$MODULE_TABLE['item']."` WHERE `item_public`='1' ORDER BY `item_date` desc LIMIT ".$limit.";");

	if ($res) while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
		$href = '/news/'.$data['item_alias'].'/';

		$img = get_img($data['item_alias'].'_1.jpg', $MODULE_NAME.'/item/', array($PHOTO_SIZES[1], $PHOTO_SIZES[0]), false, $href);

		$date = explode(' ',$data['item_date']);

		$news[] = array(
			'alias' => $data['item_alias'],
			'cDate' => cDate($date[0]),
			'date' => $data['item_date'],
			'img' => $img,
			'heading' => $data['item_heading'],
			'announce' => $data['item_announce'],
			'text' => $data['item_text'],
			'href' => $href
		);

		$smarty->assign('news', $news);
	}
?>