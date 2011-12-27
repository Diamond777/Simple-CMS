<?
	class form {
		protected $table = '';
		protected $primary_key = '';

		protected $types = array(
			'hidden',
			'submit',
			'button',
			'text',
			'textarea',
			'checkbox',
			'select',
			'advanced',
			'file'
		);


		protected $elements = array();

		protected $required = array();

		protected $multiple = array();

		protected $byType = array();


		protected $makeAlias = array();


		public $insert_errors = array();


		public $tinyMCE = false;



		public function __construct($table = false, $primary_key = false) {
			$this->table = $table;
			$this->primary_key = $primary_key;

			foreach ($this->types as $type) {
				$this->byType[$type] = array();
			}
		}


		public function addElement($id, $type, $heading = '', $param = array()) {
			if (!in_array($type, $this->types)) return false;

			if ($id == $this->primary_key) $param['unique'] = true;


			if (!empty($param['toAlias'])) {
				$this->makeAlias[$id] = $param['toAlias'];

				$param['onchange'] = (isset($param['onchange'])) ? $param['onchange'] : '';
				$param['onchange'] .= "addAlias(this.value,'".$param['toAlias']."');";
			}


			if ($type == 'textarea' && !empty($param['tinyMCE'])) {
				$this->tinyMCE = true;
				$param['class'] = (!empty($param['class'])) ? $param['class'].' mceEditor' : 'mceEditor';
			}

			$this->elements[$id] = array(
				'id' => $id,
				'type' => $type,
				'heading' => $heading,
				'param' => $param
			);

			$this->byType[$type][$id] = &$this->elements[$id];

			if ($type == 'select' && !empty($param['multiple'])) $this->multiple[$id] = &$this->elements[$id];

			if (!empty($param['required'])) $this->required[$id] = &$this->elements[$id];
		}


		public function getForm() {
			$result = array();
			foreach ($this->elements as $id => $element) $result[$id] = $this->getElement($id);
			return join('', $result);
		}


		public function addValue($id, $value) {
			if (!isset($this->elements[$id])) return false;
			$this->elements[$id]['value'] = $value;
			return true;
		}


		public function setData($data) {
			if (is_array($data) && count($data)) foreach ($data as $id => $value) {
				$this->addValue($id, $value);
			}
		}


		public function setDataById($id) {
			global $db;

			$res = $db->query("SELECT * FROM `".$this->table."` WHERE `".$this->primary_key."`='".$id."' LIMIT 1;");
			$base_data = $res->fetch(PDO::FETCH_ASSOC);

			if (count($this->multiple)) foreach ($this->multiple as $element) {
				$res = $db->query("SELECT `".$element['param']['insert']['foreign_key']."` FROM `".$element['param']['insert']['table']."` WHERE `".$this->primary_key."`='".$id."';");
				while ($data = $res->fetch(PDO::FETCH_ASSOC)) $base_data[$element['id']][] = $data[$element['param']['insert']['foreign_key']];
			}

			$this->setData($base_data);
		}


		public function uploadData($edit = false) {
			global $db, $_POST;

			foreach ($this->required as $id => $element) {
				if (empty($_POST[$id]) && !($edit === false && $id == $this->primary_key && empty($_POST[$id]))) return NULL;
			}

			$this->insert_errors = array();
			$insert_data = array();

			foreach ($this->elements as $id => $element) {
				if (array_key_exists($id, $this->multiple) || array_key_exists($id, $this->byType['advanced']) || array_key_exists($id, $this->byType['file'])) continue;


				switch ($element['type']) {
					case 'button':
					case 'submit':
						continue;
						break;
					case 'checkbox':
						$insert_data[$id] = (!empty($_POST[$id])) ? 1 : 0;
						break;
					case 'textarea':
						$insert_data[$id] = $_POST[$id];
						break;
					default:
						$insert_data[$id] = htmlspecialchars(trim($_POST[$id]));
				}


				if (!$edit && !empty($element['param']['unique'])) {
					if (self::checkDublicate($this->table, $id, $insert_data[$id])) {
						$val = ($id = $this->primary_key) ? '<a href="?edit='.$insert_data[$id].'">'.$insert_data[$id].'</a>' : '<strong>'.$insert_data[$id].'</strong>';
						$this->insert_errors[] = 'Ошибка! <strong>'.$element['heading'].'</strong> '.$val.' уже есть в БД.';
					}
				}
			}


			if (count($this->insert_errors)) return false;


			$query = "REPLACE INTO `".$this->table."` (`".join("`,`", array_keys($insert_data))."`) VALUES ('".join("','", $insert_data)."');";
			if (!$db->exec($query)) {
				$this->insert_errors[] = 'По техническим причинам запись добавить не удалось!';
				return false;
			}
			$insert_id = (empty($insert_data[$this->primary_key])) ? $db->lastInsertId() : $insert_data[$this->primary_key];


			if (count($this->multiple)) foreach ($this->multiple as $id => $element) {
				self::insert_select_multiple($element['param']['insert']['table'], $_POST[$id], $element['param']['insert']['foreign_key'], $insert_id);
			}

			if (count($this->byType['advanced'])) foreach ($this->byType['advanced'] as $id => $element) {
				self::insert_some_filds($element['param']['insert']['table'], $element['param']['insert']['fields'], $insert_id);
			}

			if (count($this->byType['file'])) foreach ($this->byType['file'] as $id => $element) {
				self::upload_files($insert_id, $id, UPLOAD_DIR.$element['param']['dir'], $element['param']['extension'], $element['param']['size']);
			}

			return true;
		}


		public function getElement($id) {
			global $db;

			if (!isset($this->elements[$id])) return false;
			$element = &$this->elements[$id];

			if (!isset($element['value'])) $element['value'] = false;

			$input_param = $other_params = array();
			$input_param[] = '';

			if (!empty($element['param']['class'])) $input_param[] = 'class="'.$element['param']['class'].'"';
			if (!empty($element['param']['style'])) $input_param[] = 'style="'.$element['param']['style'].'"';
			if (!empty($element['param']['onchange'])) $input_param[] = 'onchange="'.$element['param']['onchange'].'"';
			if (!empty($element['param']['onclick'])) $input_param[] = 'onclick="'.$element['param']['onclick'].'"';

			$other_params['required'] = (!empty($element['param']['required'])) ? '<span class="red">*</span> ' : '';

			switch ($element['type']) {


				case 'hidden':
					$el_id = (!empty($element['param']['multiple'])) ? $id.'[]' : $id;
					return '<input type="hidden" id="'.$el_id.'" name="'.$el_id.'" value="'.$element['value'].'" />';
					break;


				case 'submit':
					return '<input type="submit" value="'.$element['heading'].'"'.join(' ', $input_param).' />';
					break;


				case 'button':
					return '<input type="button" value="'.$element['heading'].'"'.join(' ', $input_param).' />';
					break;


				case 'text':
					if (!empty($element['param']['readonly'])) $input_param[] = 'readonly="readonly"';

					$elementHTML = '
						<div><strong>'.$other_params['required'].$element['heading'].':</strong></div>
						<input type="text" name="'.$id.'" id="'.$id.'" value="'.str_replace('"', "&quot;", $element['value']).'"'.join(' ', $input_param).' />
					';

					if (!empty($element['param']['captcha'])) $elementHTML = '<table width="100%" cellspacing="0" cellpadding="0"><tr><td><img src="/captcha/" /></td><td width="100%">'.$elementHTML.'</td></tr></table>';

					return $elementHTML;
					break;


				case 'textarea':
					return '
						<div><strong>'.$other_params['required'].$element['heading'].':</strong></div>
						<textarea name="'.$id.'" id="'.$id.'"'.join(' ', $input_param).'>'.$element['value'].'</textarea>
					';
					break;


				case 'checkbox':
					return '<strong>'.$other_params['required'].$element['heading'].':</strong> <input type="checkbox" name="'.$id.'" id="'.$id.'"'.( ($element['value']) ? 'checked="checked"' : '' ).' />';
					break;


				case 'select':
					if (!empty($element['param']['multiple'])) $input_param[] = 'multiple="multiple"';
					if (!empty($element['param']['disabled'])) $input_param[] = 'disabled="disabled"';


					$el_id = (!empty($element['param']['multiple'])) ? $id.'[]' : $id;


					$q = array(
						'select' => array("*"),
						'from' => array("`".$element['param']['db']['table']."`"),
						'where' => array("1"),
						'order' => (!empty($element['param']['order'])) ? "ORDER BY ".$element['param']['order'] : "",
						'limit' => ""
					);

					$res = $db->query("SELECT " . join(',', $q['select']) . " FROM " . join(',', $q['from']) . " WHERE " . join(' AND ', $q['where']) . " " . $q['order'] . " " . $q['limit'] . ";");

					$hidden = array();
					$hidden[] = '';

					$options = array();
					$options[] = (empty($element['param']['required']) && empty($element['param']['multiple'])) ? '<option value=""></option>' : '';

					while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
						$selected = '';

						if (0
							|| is_array($element['value']) && count($element['value']) && in_array($data[$element['param']['db']['id']], $element['value'])
							|| !is_array($element['value']) && $element['value'] == $data[$element['param']['db']['id']]
						) {
							$selected = ' selected="selected"';
							if (!empty($element['param']['disabled'])) {
								$hidden[] = '<input type="hidden" id="'.$el_id.'" name="'.$el_id.'" value="'.$data[$element['param']['db']['id']].'" />';
							}
						}


						if (is_array($element['param']['db']['heading_fild']) && count($element['param']['db']['heading_fild'])) {
							$item_name = array();
							foreach ($element['param']['db']['heading_fild'] as $val) if (!empty($data[$val])) $item_name[] = $data[$val];
							$item_name = join(' / ', $item_name);
						}
						else $item_name = $data[$element['param']['db']['heading_fild']];


						$options[] = '<option value="'.$data[$element['param']['db']['id']].'"'.$selected.'>'.$item_name.'</option>';
					}


					$el_id = (!empty($element['param']['disabled'])) ? '' : $el_id;

					return '
						<div><strong>'.$other_params['required'].$element['heading'].':</strong></div>
						<select id="'.$el_id.'" name="'.$el_id.'"'.join(' ', $input_param).'>'.join('', $options).'</select>
						'.join('', $hidden).'
					';
					break;


				case 'advanced':
					$indexes = array_keys($element['param']['fields']);

					$js_indexes = array();
					$tr = array();
					$th = array();

					$link = (!empty($element['param']['multiple'])) ? '<a href="#" onclick="add_some_filds(\''.$id.'\',indexes_'.$id.');return false;">Еще одна запись?</a>' : '';

					foreach ($element['param']['fields'] as $key => $item) {
						$js_indexes[] = "indexes_".$id."[indexes_".$id.".length]='".$key."';";
						$th[] = '<td align="center"><strong>'.$item.'</strong></td>';
					}
					$tr[] = '<tr>'.join('',$th).'</tr>';


					$num_rows = 0;
					if (@count($element['value'])) {
						foreach ($element['value'] as $key => $val) {
							$num_rows++;
							$td = array();
							foreach ($indexes as $key => $item) $td[] = '<td><input type="text" name="'.$id.'_'.$item.'_'.$num_rows.'" value="'.$val[$item].'" /></td>';
							$tr[] = '<tr>'.join('', $td).'</tr>';
						}
					}
					else {
						$num_rows++;
						$td = array();
						foreach ($indexes as $key => $item) $td[] = '<td><input type="text" name="'.$id.'_'.$item.'_'.$num_rows.'" value="" /></td>';
						$tr[] = '<tr>'.join('', $td).'</tr>';
					}


					return '
						<script language="JavaScript">var indexes_'.$id.'=new Array();'.join('', $js_indexes).'</script>
						<div><strong>'.$other_params['required'].$element['heading'].':</strong></div>
						<table id="filds_table_'.$id.'" cellpadding="0" cellspacing="0">'.join('', $tr).'</table>
						<input type="hidden" id="num_filds_'.$id.'" name="num_filds_'.$id.'" value="'.$num_rows.'" />
						'.$link.'
					';
					break;


				case 'file':
					$elementHTML = '<div><strong>'.$other_params['required'].$element['heading'].':</strong></div>';
					$elementHTML .= '<input type="hidden" id="num_files_'.$id.'" name="num_files_'.$id.'" value="1" />';


					if (!empty($element['param']['showPhotos'])) {
						$n = 1;
						$img = array();
						while (1) {
							$i = get_img($element['param']['showPhotos'].'_'.$n.'.jpg', $element['param']['dir'], array($element['param']['size'][2], $element['param']['size'][0]), 1, false, array(), array('rel'=>'test'));
							if (empty($i)) break;
							$img[] = '<li style="float:left; margin:0 10px 10px 0;">'.$i.'</li>';
							$n++;
						}
						$elementHTML .= '<ul style="margin:0px; padding:0px;">'.join('',$img).'</ul><div style="clear:both;"></div>';
					}


					if (!empty($element['param']['advanced'])) $elementHTML .= '
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td><input type="radio" id="delete_files_'.$id.'1" name="delete_files_'.$id.'" value="0" checked="checked"></td>
								<td><label for="delete_files_'.$id.'1">Добавить фотографии</label></td>
								<td width="20"></td>
								<td><input type="radio" id="delete_files_'.$id.'2" name="delete_files_'.$id.'" value="1"></td>
								<td><label for="delete_files_'.$id.'2">Заменить фотографии</label></td>
							</tr>
						</table>
					';


					$elementHTML .= (!empty($element['param']['multiple'])) ? '<table id="files_table_'.$id.'" width="100%" cellpadding="0" cellspacing="0"><tr><td><input type="file" name="'.$id.'1" /></td></tr></table><a href="#" onclick="add_file_fild(\''.$id.'\');return false;">Еще один файл?</a>' : '<input type="file" name="'.$id.'1" />';

					return $elementHTML;
					break;


				default:
					return false;
			}
		}


		public function makeJS($funcName) {
			$result = array();
			$func = array();

			$func[] = "document.getElementById('message').innerHTML = '';";

			if (count($this->makeAlias)) foreach ($this->makeAlias as $string => $alias) {
				$func[] = "addAlias(document.getElementById('".$string."').value,'".$alias."');";
				$func[] = "if (!checkAlias(document.getElementById('".$alias."').value)) document.getElementById('message').innerHTML+='<p class=\"error\">Ошибка! Алиас содержит недопустимые символы!</p>';";
			}

			foreach ($this->required as $id => $element) {
				$func[] = "if (document.getElementById('".$id."').value==\"\") document.getElementById('message').innerHTML+='<p class=\"error\">Ошибка! Не заполнено обязательное поле \"".$element['heading']."\"!</p>';";
			}

			$func[] = "if (document.getElementById('message').innerHTML=='') return true;";
			$func[] = "return false;";




			if ($this->tinyMCE) $result[] = '
<script type="text/javascript" src="'.ADMIN_SUP_SRC.'tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="'.ADMIN_SUP_SRC.'tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>

<script type="text/javascript">
	$().ready(function() {
		$("textarea.mceEditor").tinymce({
			// Location of TinyMCE script
			script_url : "'.ADMIN_SUP_SRC.'tiny_mce/tiny_mce.js",

			// General options
			theme : "advanced",
			skin : "o2k7",
			skin_variant : "silver",
			language : "ru",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
//			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			file_browser_callback : "tinyBrowser",

			width : "100%",
			height : "300px",

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
			';

			$result[] = '<script language="JavaScript">function '.$funcName.'() {'.join("\n", $func).'}</script>';

			return join('', $result);
		}


		public function showData($elements, $base_q = array(), $params = array()) {
			global $db, $REQUEST, $_GET;

			$params['clone'] = (empty($params['clone'])) ? false : true;

			$colspan = 3;

			$start = (isset($_GET['start']) && is_numeric($_GET['start'])) ? $_GET['start'] : 0;

			$q = array();
			$q['select'] = array("*");
			$q['from'] = "`".$this->table."`";
			$q['where'] = array();
			$q['order'] = "";
			$q['limit'] = "LIMIT ".$start.",".LIMIT_ON_PAGE;

/******************************************************************************/

			$headings = array();
			$headings[] = '<th>№</th>';
			foreach ($elements as $id) {
				$element = &$this->elements[$id];
				$headings[] = '<th>'.$element['heading'].'</th>';
				$colspan++;
				if ($element['type'] == 'select') $q['select'][] = "(SELECT `".$element['param']['db']['heading_fild']."` FROM `".$element['param']['db']['table']."` WHERE `".$element['param']['db']['id']."`=`".$this->table."`.`".$element['param']['db']['id']."`) as `".$element['param']['db']['heading_fild']."`";
			}
			$headings[] = '<th>&nbsp;</th><th>&nbsp;</th>';
			if ($params['clone']) {
				$headings[] = '<th>&nbsp;</th>';
				$colspan++;
			}

/******************************************************************************/

			if (isset($base_q['where']) && count($base_q['where'])) $q['where'] = $base_q['where'];
			if (isset($base_q['order']) && count($base_q['order'])) $q['order'] = "ORDER BY ".join(',', $base_q['order']);
			if (!count($q['where'])) $q['where'][] = "1";


			$pages_html = get_pages(db::total($q), $start);


			$n = $start;

			$rows = array();
			$rows[] = '<tr>'.join('', $headings).'</tr>';

			$res = $db->query("SELECT " . join(',', $q['select']) . " FROM ".$q['from']." WHERE " . join(' AND ', $q['where']) . " " . $q['order'] . " " . $q['limit'] . ";");
			while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
				$n++;

				$cols = array();
				$cols[] = '<td><strong>'.$n.'</strong></td>';
				foreach ($elements as $id) {
					$element = &$this->elements[$id];
					switch ($element['type']) {
						case 'checkbox':
							$val = (!empty($data[$id])) ? '<span style="color:#090;">Да</span>' : '<span style="color:#900;">Нет</span>';
							break;
						case 'select':
							$val = $data[$element['param']['db']['heading_fild']];
							break;
						case 'file':
							$val = get_img($data[$this->primary_key].'_1.jpg', $REQUEST[1].'/'.$REQUEST[2].'/', array($element['param']['size'][1], $element['param']['size'][0]));
							break;
						default:
							$val = $data[$id];
					}
					$cols[] = '<td>'.$val.'</td>';
				}
				$cols[] = '<td><a title="Редактировать" href="/'.$REQUEST[0].'/'.$REQUEST[1].'/'.$REQUEST[2].'/add/?edit='.$data[$this->primary_key].'"><img border="0" src="/design/admin/images/ThemeOffice/edit.png" /></a></td>';
				if ($params['clone']) $cols[] = '<td><a title="Клонировать" href="/'.$REQUEST[0].'/'.$REQUEST[1].'/'.$REQUEST[2].'/add/?clone='.$data[$this->primary_key].'"><img border="0" src="/design/admin/images/ThemeOffice/restore.png" /></a></td>';
				$cols[] = '<td><a title="Удалить" href="'.replaceURLget('delete', $data[$this->primary_key]).'" onclick="if (!confirm(\'Вы действительно хотите удалить эту запись?\')) return false;"><img src="/design/admin/images/publish_x.png" border="0" /></a></td>';


				$rows[] = '<tr align="center">'.join('', $cols).'</tr>';
			}

			if (count($rows) <= 1) $rows[] = '<tr><td colspan="'.$colspan.'"><center>В БД пока нет записей.</center></td></tr>';


			return $pages_html.'<table class="adminlist" width="100%" cellpadding="5" cellspacing="0">'.join('', $rows).'</table>';
		}



		public function checkDublicate($table, $key, $id) {
			return db::total(array('from'=>"`".$table."`", 'where'=>array("`".$key."`='".$id."'")));
		}



		public function insert_communications($table, $from, $id) {
			global $db;

			$db->exec("DELETE FROM `".$table."` WHERE `id1`='".$id."' OR `id2`='".$id."';");
			if (count($from)) {
				$q = array();

				foreach ($from as $key => $item) {
					if ($item == $id) continue;
					$q[] = "('".$id."','".$item."')";
				}
				if (count($q)) $db->exec("INSERT INTO `".$table."` (`id1`,`id2`) VALUES ".join(',', $q).";");
			}
		}



		protected function insert_select_multiple($table, $from, $foreign_id, $id) {
			global $db;

			$db->exec("DELETE FROM `".$table."` WHERE `".$this->primary_key."`='".$id."';");
			if (count($from)) {
				$items = array();
				foreach ($from as $key => $item) $items[] = "('".$id."','".$item."')";
				$db->exec("INSERT INTO `".$table."` (`".$this->primary_key."`,`".$foreign_id."`) VALUES ".join(',', $items).";");
			}
		}



		public function insert_some_filds($id, $table, $indexes, $id) {
			global $db, $_POST;

			$num_rows = $_POST['num_filds_'.$id];

			$db->exec("DELETE FROM `".$table."` WHERE `".$this->primary_key."`='".$id."';");

			if (count($indexes)) {
				$row = array();

				for ($i = 1; $i <= $num_rows; $i++) {
					$cel = array();
					$cel[] = $id;
					$empty = true;

					foreach ($indexes as $item) {
						$ind = $id.'_'.$item.'_'.$i;
						if (!empty($_POST[$ind])) $empty = false;
						$cel[] = $_POST[$ind];
					}

					if (!$empty) $row[] = "('".join("'", $cel)."')";
				}

				if (count($row)) $db->exec("INSERT INTO `".$table."` (`".$this->primary_key."`,`".join('`,`', $indexes)."`) VALUES ".join(',', $row).";");
			}
		}



		public function delete_files($id, $dir, $type, $subdir = false) {
			$i = 1;
			$file = $id.'_'.$i.'.'.$type;
			do {
				while (is_readable($dir.$file)) {
					if (is_readable($dir.$file)) unlink($dir.$file);
					if (is_array($subdir) && count($subdir)) foreach ($subdir as $item) if (is_readable($dir.$item.'/'.$file)) unlink($dir.$item.'/'.$file);
					$i++;
					$file = $id.'_'.$i.'.'.$type;
				}
				$i++;
				$file = $id.'_'.$i.'.'.$type;
			} while (is_readable($dir.$file));
		}


		public function upload_files($id, $group, $dir, $type, $size = false) {
			global $_POST, $action_edit;

			$num_files = $_POST['num_files_'.$group];
			$delete_files = true;

			if (isset($_POST['delete_files_'.$group])) $delete_files = $_POST['delete_files_'.$group];
			else {
				$delete_files = false;
				for($i = 1; $i <= $num_files; $i++) if ($_FILES[$group.$i]['tmp_name']) {$delete_files = true; break;}
			}

			self::enumeration_files($id, $group, $dir, $type, $num_files, $size, $delete_files);
		}


		public function enumeration_files($id, $group, $dir, $type, $num_files, $size, $delete_files = false) {
			global $_FILES;

			if ($delete_files) self::delete_files($id, $dir, $type, $size);

			$n = 1;
			while (is_readable($dir.$id.'_'.$n.'.'.$type)) $n++;
			for ($i = 1; $i <= $num_files; $i++) {
				$res_f_name = $id.'_'.$n.'.'.$type;
				$f = $group.$i;

				if ($_FILES[$f]['tmp_name']) {
					self::files_handling($_FILES[$f]['tmp_name'], $dir, $res_f_name, $size);
					$n++;
				}
			}
		}



		public function files_handling($distr, $dir, $file, $size) {
			global $message;

			if (!make_dir($dir)) {$message .= '<p class="red">Ошибка! Не удалось создать папку '.$dir.'.</p>'; return false;}

			if (!move_uploaded_file($distr, $dir.$file)) $message .= '<p class="red">Ошибка! Не удалось загрузить файл.</p>';
			elseif (is_array($size) && count($size)) {
				foreach ($size as $item) {
					if (!make_dir($dir.$item.'/')) {$message .= '<p class="red">Ошибка! Не удалось создать папку '.$dir.$item.'/'.'.</p>'; break;}
					$exp = explode('x', $item);
					if (!resizer($dir.$file, $dir.$item.'/'.$file, $exp[0], $exp[1])) {$message .= '<p class="red">Ошибка! Не удалось обработать фотографию.</p>'; break;}
				}
			}
		}
	}
?>