<?
	if (isset($_REQUEST['table'])) $_SESSION['dbs']['table'] = $_REQUEST['table'];
	if (isset($_REQUEST['search'])) $_SESSION['dbs']['search'] = $_REQUEST['search'];

	if (!isset($_SESSION['dbs']['table'])) $_SESSION['dbs']['table'] = false;
	if (!isset($_SESSION['dbs']['search'])) $_SESSION['dbs']['search'] = false;


	if (!empty($_SESSION['dbs']['table'])) {
		$q = array(
			'select' => array(),
			'from' => array(),
			'where' => array(),
			'order' => "",
			'limit' => ""
		);

		$q['select'][] = "*";
		$q['from'][] = "`".$_SESSION['dbs']['table']."`";
		$q['where'][] = "1";
		$q['limit'] = "LIMIT 1";

		$fields = array();
		$fields_html = array();
		$res = $db->query("SELECT " . join(',', $q['select']) . " FROM " . join(',', $q['from']) . " WHERE " . join(' AND ', $q['where']) . " " . $q['order'] . " " . $q['limit'] . ";");
		$data = $res->fetch(PDO::FETCH_ASSOC);
		foreach ($data as $key => $item) {
			$fields[] = $key;
			$fields_html[] = '<th>'.$key.'</th>';
		}


		if (!empty($_SESSION['dbs']['search'])) {
			$q2 = array();
			foreach ($fields as $field) $q2[] = "`".$field."` LIKE '%".$_SESSION['dbs']['search']."%'";
			if (!count($q)) $q2[] = '0';

			$q['where'][] = "(".join(' OR ', $q2).")";
		}
		else unset($_SESSION['dbs']['search']);

		$q['limit'] = "";

		$res = $db->query("SELECT " . join(',', $q['select']) . " FROM " . join(',', $q['from']) . " WHERE " . join(' AND ', $q['where']) . " " . $q['order'] . " " . $q['limit'] . ";");

		$rows = array();
		$rows[] = '<tr>'.join('', $fields_html).'</tr>';
		while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
			$row = array();
			foreach ($fields as $field) $row[] = '<td>'.(($data[$field] != '') ? nl2br($data[$field]) : '&nbsp;').'</td>';
			$rows[] = '<tr>'.join('', $row).'</tr>';
		}

		$content = (count($rows)) ? '<table border="1" cellpadding="2" cellspacing="0">'.join('', $rows).'</table>' : '';
	}



	$all_tables = array();
	$res = $db->query("SHOW TABLES;");
	while ($tab = $res->fetch(PDO::FETCH_NUM)) {
		$selected = (isset($_SESSION['dbs']['table']) && $tab[0] == $_SESSION['dbs']['table']) ? ' selected="selected"' : '';
		$all_tables[] = '<option value="'.$tab[0].'"'.$selected.'>'.$tab[0].'</option>';
	}
	if (!count($all_tables)) $all_tables[] = '';



	$form = '
		<form method="post">
		<table>
			<tr>
				<td>Search</td>
				<td><input type="text" name="search" value="'.@$_SESSION['dbs']['search'].'" onchange="this.form.submit();" /></td>
				<td>in</td>
				<td><select name="table" onChange="this.form.submit();">'.join('', $all_tables).'</select></td>
				<!--td><input type="button" value="Выбрать" onclick="this.form.submit();"></td-->
			</tr>
		</table>
		</form>
	';


	echo $form;
	if (!empty($content)) echo '<br />'.$content.'<br />'.$form;
?>