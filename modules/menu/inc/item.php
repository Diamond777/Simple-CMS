<?
	$general_table = 'item';
	$general_id = $general_table.'_alias';

	$edit = (!empty($_GET['edit'])) ? $_GET['edit'] : false;
	$menu_id = (!empty($_REQUEST['menu_id']) && is_numeric($_REQUEST['menu_id']) && $_REQUEST['menu_id'] > 0) ? $_REQUEST['menu_id'] : false;


	$form = new form($MODULE_TABLE[$general_table], $general_id);


	$form->addElement('item_date', 'hidden', '', array('required' => true));

	$form->addElement('menu_id', 'select', 'Меню', array(
		'required' => true,
		'disabled' => !empty($menu_id),
		'db' => array(
			'table' => $MODULE_TABLE['menu'],
			'id' => 'menu_id',
			'heading_fild' => 'menu_heading',
			'order' => 'menu_id'
		),
		'style' => 'width:100%;'
	));

	$form->addElement('item_heading', 'text', 'Название пункта меню', array(
		'required' => true,
		'toAlias' => 'item_alias',
		'style' => 'width:100%;'
	));

	$form->addElement('item_alias', 'text', 'Алиас', array(
		'required' => true,
		'readonly' => !empty($edit),
		'style' => 'width:100%;'
	));

	$form->addElement('item_text', 'textarea', 'Контент', array(
		'style' => 'width:100%;',
		'tinyMCE' => true
	));

	$form->addElement('item_sort', 'text', 'Сортировка', array('style' => 'width:100%;'));

	$form->addElement('submit', 'submit', (!$edit) ? 'Добавить' : 'Редактировать', array('style' => 'width:100%;'));

	$form->addElement('item_title', 'text', 'Title', array('style' => 'width:100%;'));

	$form->addElement('item_keywords', 'text', 'Ключевые слова', array('style' => 'width:100%;'));

	$form->addElement('item_description', 'text', 'Описание', array('style' => 'width:100%;'));

	$form->addElement('item_footer', 'textarea', 'Footer', array(
		'style' => 'width:100%;',
		'tinyMCE' => true
	));



	if ($REQUEST[3] == 'add') {
		$upload = $form->uploadData($edit);

		if ($upload === true) {
			header('Location: /'.$REQUEST[0].'/'.$REQUEST[1].'/'.$REQUEST[2].'/edit/?menu_id='.$menu_id); exit;
			unset($_POST);
		}
		elseif ($upload === false) {
			if (!count($form->insert_errors)) $message = '<p class="error">Неизвестная ошибка!</p>';
			else foreach ($form->insert_errors as $error) {
				$message .= '<p class="error">'.$error.'</p>';
			}
			$form->setData($_POST);
		}
		else {
			if ($edit) $form->setDataById($edit);
			else {
				$base_data = array();

//				$base_data['item_date'] = (!empty($base_data['item_date'])) ? $base_data['item_date'] : date('Y-m-d H:i:s');
				$base_data['item_date'] = date('Y-m-d H:i:s');

				if ($menu_id) {
					$total = db::total(array('select'=>array(), 'from'=>"`".$MODULE_TABLE['menu']."`", 'where'=>array("`menu_id`='".$menu_id."'")));
					$base_data['menu_id'] = ($total) ? $menu_id : false;


					if (!$edit && @!$base_data['item_sort']) {
						$max = $db->query("SELECT MAX(`item_sort`) FROM `".$MODULE_TABLE[$general_table]."` WHERE `menu_id`='".$menu_id."';")->fetchColumn();
						$base_data['item_sort'] = (floor($max / 10) + 1) * 10;
					}
				}

				$form->setData($base_data);
			}
		}

################################################################################

		echo $form->makeJS('check');

		echo '<div class="tab-page" id="tab1"><h2 class="tab">Общие</h2><script type="text/javascript">tabPane.addTabPage(document.getElementById("tab1"));</script>';

		echo $form->getElement('item_date');
		echo '<div class="form_row">'.$form->getElement('menu_id').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_heading').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_alias').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_text').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_sort').'</div>';
		echo '<div class="form_row">'.$form->getElement('submit').'</div>';

		echo '</div>';



		echo '<div class="tab-page" id="tab2"><h2 class="tab">META-тэги</h2><script type="text/javascript">tabPane.addTabPage(document.getElementById("tab2"));</script>';

		echo '<div class="form_row">'.$form->getElement('item_title').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_keywords').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_description').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_footer').'</div>';
		echo '<div class="form_row">'.$form->getElement('submit').'</div>';

		echo '</div>';
	}
	else {
		if (!empty($_GET['delete'])) {
			$db->exec("DELETE FROM `".$MODULE_TABLE[$general_table]."` WHERE `".$general_id."`='".$_GET['delete']."';");
			form::delete_files($_GET['delete'], UPLOAD_DIR.$REQUEST[1].'/'.$REQUEST[2].'/', 'jpg', $PHOTO_SIZES);
			header('Location: '.replaceURLget('delete', false, $_SERVER['REQUEST_URI'])); exit;
		}


		$q = array();
		$q['where'] = array();
		$q['order'] = array();

		if ($menu_id) $q['where'][] = "`menu_id`='".$menu_id."'";
		$q['order'][] = "`item_sort`";
		$q['order'][] = "`".$general_id."`";

		echo $form->showData(array('menu_id', 'item_alias', 'item_heading', 'item_sort'), $q);
	}
?>