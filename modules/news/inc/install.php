<?
	$key = 'item';
	$sructure = "
		`".$key."_alias` VARCHAR(250) not null default '',
		`".$key."_date` DATETIME not null default '0000-00-00 00:00:00',
		`".$key."_heading` VARCHAR(250) not null default '',
		`".$key."_announce` TEXT not null default '',
		`".$key."_text` TEXT not null default '',
		`".$key."_public` BOOLEAN not null default '0',
		PRIMARY KEY `".$key."_alias` (`".$key."_alias`),
		KEY `".$key."_date` (`".$key."_date`),
		KEY `".$key."_public` (`".$key."_public`)
	";
	db::create_table($MODULE_TABLE[$key], $sructure);
?>