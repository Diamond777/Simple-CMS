<?

if($_POST && $_GET['menu_id']){

	$sql = array();
	
	foreach($_POST['level'] as $key => $value) $sql[] = "(NULL ,  '".$_GET['menu_id']."',  '".$value."',  '".$_POST['name'][$key]."',  '".($key*10)."')";
	
	$db->query('DELETE FROM `test_modules_name` WHERE `test_modules_name`.`menu_id` = "'.$_GET['menu_id'].'";');
	
	$db->query('REPLACE INTO `test_modules_name` (`name_id` ,`menu_id` ,`name_alias` ,`name_heading` ,`name_sort`) VALUES '.join(',', $sql).';');
	$i = 0;
	foreach($_POST['level'] as $key => $value){
	
		$parent = ( ++$i > 1 ) ? '`'.$_POST['level'][$key-1].'_id` INT unsigned not null default \'0\',' : '';
		
		$MODULE_TABLE[$value] = TABLEPREFIX.$_GET['menu_id'].'_'.$value;
		
		$sructure = "
			`".$value."_id` INT unsigned not null auto_increment,  
			".$parent."		                                       
			`".$value."_alias` VARCHAR(250) not null default '',   
			`".$value."_heading` VARCHAR(250) not null default '', 
			`".$value."_sort` INT unsigned not null default '0',   
			PRIMARY KEY `".$value."_id` (`".$value."_id`),         
			KEY `".$value."_sort` (`".$value."_sort`)			   
		";
		db::create_table($MODULE_TABLE[$value], $sructure);
		
	}
	
	
}
if($_GET['menu_id']){
	
	$res = $db->query("SELECT * FROM `".$MODULE_TABLE['name']."` WHERE `menu_id`='".$_GET['menu_id']."' ORDER BY `name_id`;");
	
	$inp = array();
	
	if ($res) while ($data = $res->fetch(PDO::FETCH_ASSOC)) {
		
		$inp[] = '<div class="inp"><input type="text" name="level['.$data['name_id'].']" value="'.$data['name_alias'].'"/><input type="text" name="name['.$data['name_id'].']" value="'.$data['name_heading'].'"/><a class="plus" href="#"></a></div>';
		
	}
	
}

?>
<link href="http://cms/design/admin/css/admin_modules_create.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://cms/design/admin/js/admin_modules_create.js"></script>


<div id="modulecreate">
	
	<?
		if(count($inp)>1) echo join('', $inp);
		else{
			?>
				<div class="inp"><input type="text" name="level[1]" value="level_1"/><input type="text" name="name[1]" value="name_1"/><a class="plus" href="#"></a></div>	
			<?
		}
	?>

</div>
<input type="hidden" value=""/>
<div class="form_row"><button type="submit" value="" style="width:100%;">Добавить</button></div>