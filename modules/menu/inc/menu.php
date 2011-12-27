<?
	$general_table = 'menu';
	$general_id = $general_table.'_id';

	$edit = (!empty($_GET['edit'])) ? $_GET['edit'] : false;


	$form = new form($MODULE_TABLE[$general_table], $general_id);



	$form->addElement('menu_id', 'hidden', 'ID');

	$form->addElement('menu_heading', 'text', 'Название меню', array(
		'required' => true,
		'style' => 'width:100%;'
	));

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
				$form->setData($base_data);
			}
		}

################################################################################

		echo $form->makeJS('check');


		echo '<div class="tab-page" id="tab1"><h2 class="tab">Общие</h2><script type="text/javascript">tabPane.addTabPage(document.getElementById("tab1"));</script>';

		echo $form->getElement('menu_id');
		echo '<div class="form_row">'.$form->getElement('menu_heading').'</div>';
		echo '<div class="form_row">'.$form->getElement('submit').'</div>';

		echo '</div>';
	}
	else {
		if (!empty($_GET['delete']) && is_numeric($_GET['delete'])) {
			$db->exec("DELETE FROM `".$MODULE_TABLE[$general_table]."` WHERE `".$general_id."`='".$_GET['delete']."';");
			$db->exec("DELETE FROM `".$MODULE_TABLE['item']."` WHERE `".$general_id."`='".$_GET['delete']."';");
			form::delete_files($_GET['delete'], UPLOAD_DIR.$REQUEST[1].'/'.$REQUEST[2].'/', 'jpg', $PHOTO_SIZES);
			header('Location: '.replaceURLget('delete', false, $_SERVER['REQUEST_URI'])); exit;
		}


		$q = array();
		$q['order'] = array("`menu_id`");
		echo $form->showData(array('menu_heading'), $q);
	}
?>