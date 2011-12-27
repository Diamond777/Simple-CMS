<?
	function getThisParam($MODULE_TABLE){
		
		global $db;
		
		$params_arr = array();
		$params = $db->query('SHOW FIELDS FROM `'.$MODULE_TABLE.'`');
		while($params_data = $params->fetch(PDO::FETCH_ASSOC)) $params_arr[$params_data['Field']] = $params_data;
		
		return $params_arr;
	}
	
	
	
	
	
	$MODULE_TABLE[$_GET['edit']] = TABLEPREFIX.$_GET['menu_id'].'_'.$_GET['edit'];
	$params_arr = getThisParam($MODULE_TABLE[$_GET['edit']]);
	
	// print_r($params_arr);
	// $i = 0;
	
	if($_POST){
		
		// $item_params[] = array();
		
		foreach($_POST['heading_attr'] as $key => $value){
			
			
			switch($_POST['type_attr'][$key]){
				
				case 1: $type = 'varchar(255)'; break;
				case 2: $type = 'text'; break;
				case 3: $type = 'text'; break;
				case 4: $type = 'varchar(255)'; break;
				
				
			}
			
			if(@$params_arr[$_POST['name_attr'][$key]]){ 
			
				if($type != $params_arr[$_POST['name_attr'][$key]]['Type']) $db->query('ALTER TABLE `'.$MODULE_TABLE[$_GET['edit']].'` CHANGE `'.$params_arr[$_POST['name_attr'][$key]]['Field'].'` `'.$params_arr[$_POST['name_attr'][$key]]['Field'].'` '.$type.' CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
			
			}else{
			
				$db->query('ALTER TABLE `'.$MODULE_TABLE[$_GET['edit']].'` ADD `'.$_POST['name_attr'][$key].'` '.$type.' NOT NULL');
			
			}
			
			$item_params = array(
				
				'item_params_id' => 'NULL',
				'item_params_heading' => $_POST['heading_attr'][$key],
				'item_params_alias' => $_POST['name_attr'][$key],
				'item_params_type' => $_POST['type_attr'][$key],
				'item_params_lavel' => $_GET['edit'],
				'menu_id' => $_GET['menu_id']
				
			);
			
			$item_param = $db->query("SELECT * FROM `".$MODULE_TABLE['item_params']."` WHERE `item_params_alias` = '".$item_params['item_params_alias']."' AND `item_params_lavel` = '".$_GET['edit']."' AND `menu_id` = '".$item_params['menu_id']."' LIMIT 1");
			$item_param = $item_param->fetch(PDO::FETCH_ASSOC);
			
			$part = true;
			foreach($item_params as $key => $value){
				
				
				if($key!='item_params_id'){
					if(isset($item_param[$key]) && $item_param[$key] != $item_params[$key]){
						
						// $query['set'][] = "`".$key."` = '".$item_params[$key]."'";  // надо то что было заменить на то что прислали
						$db->query("UPDATE `".$MODULE_TABLE['item_params']."` SET `".$key."` = '".$item_params[$key]."' WHERE `item_params_id` = ".$item_param['item_params_id']." AND `item_params_lavel` = '".$_GET['edit']."' AND `menu_id` = '".$item_params['menu_id']."'");
						
						$part = false;
					
					}
				}
				
			}
			if(!$item_param['item_params_id']) $db->query("INSERT INTO `".$MODULE_TABLE['item_params']."` (`".join('`,`',array_keys($item_params))."`) VALUES ('".join("','",array_values($item_params))."')");
				
		}
		
	}
	
?>

<script type="text/javascript" src="http://cms/design/admin/js/admin_modules_create.js"></script>



			<div class="dlx">
<?
	
	$params_arr = getThisParam($MODULE_TABLE[$_GET['edit']]);
	$i = 0;
	
	$item_param_res = $db->query("SELECT * FROM `".$MODULE_TABLE['item_params']."` WHERE `menu_id` = '".$_GET['menu_id']."' AND `item_params_lavel` = '".$_GET['edit']."' ");
	while($item_param_data = $item_param_res->fetch(PDO::FETCH_ASSOC))$item_param[$item_param_data['item_params_alias']] = $item_param_data;
	
	foreach($params_arr as $key => $value){
		
		$i++;
		$n = strlen($_GET['edit'])+1;
		$selectedType = array();
		switch($value['Field']){
		
			case $_GET['edit'].'_id' 		: $disabled ='disabled="disabled"'; $name="ID"; 		 break;
			case $_GET['edit'].'_alias' 	: $disabled ='disabled="disabled"'; $name="Алиас";	 	 break;
			case $_GET['edit'].'_heading' 	: $disabled ='disabled="disabled"'; $name="Название"; 	 break;
			case $_GET['edit'].'_sort' 		: $disabled ='disabled="disabled"'; $name="Сортировка";	 break;
			default : $disabled = ''; $name = $item_param[$value['Field']]['item_params_heading']; $selectedType[$item_param[$value['Field']]['item_params_type']] = 'selected="selected" ';  break;
			
		}
		// print_r($item_param);
		
		
		
		?>
			
				<div class="attr_box">
					<label>Название<input <?=$disabled?> type="text" name="heading_attr[<?=$i?>]" value="<?=$name?>"/></label>
					<label>Алиас<input <?=$disabled?> type="text" name="name_attr[<?=$i?>]" value="<?=$value['Field']?>"/></label>
					<label>Тип<select <?=$disabled?> class="type_attr" name="type_attr[<?=$i?>]">
						<option <?=@$selectedType[1]?>value="1">TEXT [VARCHAR(255)]</option>
						<option <?=@$selectedType[2]?>value="2">TEXTAREA [TEXT]</option>
						<option <?=@$selectedType[3]?>value="3">TEXTAREA [TEXT][TINIMCE]</option>
						<option <?=@$selectedType[4]?>value="4">- SELECT [VARCHAR(255)]</option>
						<option <?=@$selectedType[5]?>value="5">- SELECT [VARCHAR(255)][DB]</option>
						<option <?=@$selectedType[6]?>value="6">- FILE [MULTY]</option>
					</select>
					</label>
				</div>
			
		<?
		
	}

?>	
			</div>
<input type="hidden" name="menu_id" value="<?=$_REQUEST['menu_id']?>" />
<input type="hidden" name="num" value="<?=count($params_arr)?>" />
<button class="add_attr">+</button>
<button type="submit">Готово</button>