<?
	$key = 'menu';
	$sructure = "
		`".$key."_id` INT unsigned not null auto_increment,
		`".$key."_heading` VARCHAR(250) not null default '',
		PRIMARY KEY `".$key."_id` (`".$key."_id`)
	";
	db::create_table($MODULE_TABLE[$key], $sructure);



	$key = 'item';
	$sructure = "
		`".$key."_alias` VARCHAR(250) not null default '',
		`".$key."_date` DATETIME not null default '0000-00-00 00:00:00',
		`menu_id` INT unsigned not null default '0',

		`".$key."_heading` VARCHAR(250) not null default '',
		`".$key."_link` VARCHAR(250) not null default '',

		`".$key."_text` TEXT not null default '',

		`".$key."_title` TEXT not null default '',
		`".$key."_keywords` TEXT not null default '',
		`".$key."_description` TEXT not null default '',
		`".$key."_footer` TEXT not null default '',

		`".$key."_sort` INT unsigned not null default '0',
		PRIMARY KEY `".$key."_alias` (`".$key."_alias`),
		KEY `".$key."_date` (`".$key."_date`),
		KEY `menu_id` (`menu_id`),
		KEY `".$key."_sort` (`".$key."_sort`,`".$key."_alias`)
	";
	db::create_table($MODULE_TABLE[$key], $sructure);
?>