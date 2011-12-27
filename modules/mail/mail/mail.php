<?
	require_once(dirname(__FILE__).'/'.'action.php');


	$form = new form(false, false);


	$form->addElement('from', 'text', 'E-Mail отправителя', array(
		'required' => true,
		'style' => 'width:100%;'
	));


	$form->addElement('fromName', 'text', 'Имя отправителя', array(
		'required' => false,
		'style' => 'width:100%;'
	));


	$form->addElement('to', 'text', 'E-Mail получателя', array(
		'required' => true,
		'style' => 'width:100%;'
	));


	$form->addElement('subject', 'text', 'Тема сообщения', array(
		'required' => true,
		'style' => 'width:100%;'
	));


	$form->addElement('message', 'textarea', 'Сообщение', array(
		'required' => true,
		'style' => 'width:100%; height:200px;'
	));

	$form->addElement('submit', 'submit', 'Отправить', array('style' => 'width:100%;'));



	$form->setData($base_data);

	echo $form->makeJS('check');
?>


<div class="tab-page" id="tab1">
	<h2 class="tab">Общие</h2>
	<script type="text/javascript">tabPane.addTabPage( document.getElementById( "tab1" ) );</script>

<?
	echo '<div class="form_row">'.$form->getElement('from').'</div>';
	echo '<div class="form_row">'.$form->getElement('fromName').'</div>';
	echo '<div class="form_row">'.$form->getElement('to').'</div>';
	echo '<div class="form_row">'.$form->getElement('subject').'</div>';
	echo '<div class="form_row">'.$form->getElement('message').'</div>';
	echo '<div class="form_row">'.$form->getElement('submit').'</div>';
?>
</div>