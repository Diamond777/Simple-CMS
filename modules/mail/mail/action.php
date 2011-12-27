<?
	$error_message = '';

	if (1
		&& !empty($_POST['from'])
		&& !empty($_POST['to'])
		&& !empty($_POST['subject'])
		&& !empty($_POST['message'])
	) {
		if (!eregi("^[a-z0-9\._-]+@[a-z0-9\._-]+\.[a-z]{2,4}\$", $_POST['from'])) $error_message .= '<p class="error">Ошибка! Некорректно указан "E-Mail отправителя"!</p>';
		if (!eregi("^[a-z0-9\._-]+@[a-z0-9\._-]+\.[a-z]{2,4}\$", $_POST['to'])) $error_message .= '<p class="error">Ошибка! Некорректно указан "E-Mail получателя"!</p>';

		$mail = new PHPMailer();
		$mail->SetLanguage('ru', LANG_DIR.'PHPMailer/');
		$mail->CharSet = "utf-8";
		$mail->From = $_POST['from'];
		$mail->FromName = $_POST['fromName'];
		$mail->Subject = $_POST['subject'];

		$htmlBody = '
			<html>
			<head><title>'.$_POST['subject'].'</title></head>
			<body>'.nl2br(stripslashes($_POST['message'])).'</body>
			</html>
		';

		$mail->AltBody = 'Невозможно отобразить html-код!';
		$mail->MsgHTML($htmlBody);
		$mail->AddAddress($_POST['to']);
		$error_message .= $mail->ErrorInfo;

		if (empty($error_message)) {
			$error_message .= (!$mail->Send()) ? '<p class="error">Ошибка! По техническим причинам сообщение отправить не удалось.</p>' : '<p class="green">Сообщение успешно отправлено!</p>';
			unset($_POST);
		}
	}

	$base_data = (isset($_POST)) ? $_POST : false;
?>