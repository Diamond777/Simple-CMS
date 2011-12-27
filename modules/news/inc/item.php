<?
	$general_table = 'item';
	$general_id = $general_table.'_alias';

	$edit = (!empty($_GET['edit'])) ? $_GET['edit'] : false;


	$form = new form($MODULE_TABLE[$general_table], $general_id);


	$form->addElement('item_date', 'text', 'Дата', array(
		'required' => true,
		'style' => 'width:100%;'
	));

	$form->addElement('item_heading', 'text', 'Заголовок', array(
		'required' => true,
		'toAlias' => 'item_alias',
		'style' => 'width:100%;'
	));

	$form->addElement('item_alias', 'text', 'Алиас', array(
		'required' => true,
		'readonly' => !empty($edit),
		'style' => 'width:100%;'
	));

	$form->addElement('item_announce', 'text', 'Анонс', array('style' => 'width:100%;'));

	$form->addElement('item_text', 'textarea', 'Текст', array(
		'style' => 'width:100%;',
		'tinyMCE' => true
	));

	$form->addElement('photos', 'file', 'Фото', array(
		'style' => 'width:100%;',
		'dir' => $REQUEST[1].'/'.$REQUEST[2].'/',
		'size' => $PHOTO_SIZES,
		'extension' => 'jpg',
		'multiple' => true,
		'advanced' => true,
		'showPhotos' => $edit
	));

	$form->addElement('item_public', 'checkbox', 'Опубликовать на сайте?');

	$form->addElement('submit', 'submit', (!$edit) ? 'Добавить' : 'Редактировать', array('style' => 'width:100%;'));



	if ($REQUEST[3] == 'add') {
		$upload = $form->uploadData($edit);

		if ($upload === true) {
			header('Location: /'.$REQUEST[0].'/'.$REQUEST[1].'/'.$REQUEST[2].'/edit/'); exit;
			unset($_POST);
		}
		elseif ($upload === false) {
			if (!count($form->insert_errors)) $message = '<p class="error">Неизвестная ошибка!</p>';
			else foreach ($form->insert_errors as $error) {
				$message = '<p class="error">'.$error.'</p>';
			}
			$form->setData($_POST);
		}
		else {
			if ($edit) $form->setDataById($edit);
			else {
				$base_data = array();
				$base_data['item_date'] = (!empty($base_data['item_date'])) ? $base_data['item_date'] : date('Y-m-d H:i:s');
				$base_data['item_public'] = (!isset($base_data['item_public'])) ? 1 : $base_data['item_public'];
				$form->setData($base_data);
			}
		}

################################################################################

		echo $form->makeJS('check');


		echo '<div class="tab-page" id="tab1"><h2 class="tab">Общие</h2><script type="text/javascript">tabPane.addTabPage(document.getElementById("tab1"));</script>';

		echo '<div class="form_row">'.$form->getElement('item_date').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_heading').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_alias').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_announce').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_text').'</div>';
		echo '<div class="form_row">'.$form->getElement('photos').'</div>';
		echo '<div class="form_row">'.$form->getElement('item_public').'</div>';
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
		$q['order'] = array("`item_public`", "`item_date` DESC");
		echo $form->showData(array('photos', 'item_date', $general_id, 'item_heading', 'item_announce', 'item_public'), $q);
	}
?>