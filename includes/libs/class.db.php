<?
	class db {
		public function connect($host = '', $user = '', $pass = '', $db = '') {
			$host = (!empty($host)) ? $host : SQL_HOST;
			$user = (!empty($user)) ? $user : SQL_USER;
			$pass = (!empty($pass)) ? $pass : SQL_PASS;
			$db = (!empty($db)) ? $db : SQL_DB;

			try {
				$pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			} catch (Exception $e) {
				echo '<strong>Failed:</strong> ' . $e->getMessage();
			}

			$pdo->exec("SET NAMES utf8;");

			return $pdo;
		}



		public function total($q) {
			global $db;
			$q['select'] = "COUNT(*)";
			if (!count($q['where'])) $q['where'][] = 1;
			if (is_array($q['from'])) $q['from'] = join(',', $q['from']);
			if (is_array($q['select'])) $q['select'] = join(',', $q['select']);

			return $db->query("SELECT " . $q['select'] . " FROM " . $q['from'] . " WHERE " . join(' AND ', $q['where']) . ";")->fetchColumn();
		}



		public function get_mediates($db_params) {
			global $db, $MODULE_TABLE;
			$query = "
				SELECT `".$db_params['table']."`.*
				FROM
					`".$MODULE_TABLE[$db_params['table']]."` as `".$db_params['table']."` LEFT JOIN
					`".$MODULE_TABLE[$db_params['table_mediate']]."` as `".$db_params['table_mediate']."`
				USING (`".$db_params['foreign_key']."`)
				WHERE 1
					AND `".$db_params['table_mediate']."`.`".$db_params['key']."`='".$db_params['id']."'
			;";
			$res = $db->query($query);
			if (!$res->rowCount()) return false;

			$data = array();
			while ($data2 = $res->fetch(PDO::FETCH_ASSOC)) $data[] = $data2;
			return $data;
		}



		public function get_friend_table($table, $general_id, $value) {
			global $db, $MODULE_TABLE;
			$query = "
				SELECT `".$table."`.*
				FROM `".$MODULE_TABLE[$table]."` as `".$table."`
				WHERE `".$table."`.`".$general_id."`='".$value."'
			;";
			$res = $db->query($query);
			if (!$res->rowCount()) return false;

			$data = array();
			while ($data2 = $res->fetch(PDO::FETCH_ASSOC)) $data[] = $data2;
			return $data;
		}



		public function create_table($table, $sructure) {
			global $db;

			$db->exec("DROP TABLE IF EXISTS `".$table."`;");
			$db->exec("CREATE TABLE `".$table."` (".$sructure.") ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

			if ($db->query("SHOW TABLES FROM `".SQL_DB."` LIKE '".$table."'")->fetchColumn()) echo '<p class="blue">Таблица <b>'.$table.'</b> успешно создана!</p>';
			else echo '<p class="error">Таблицу <b>'.$table.'</b> создать не удалось!</p>';
		}



		public function replaceAll($table, $array) {
			global $db;

			$values = array();
			foreach ($array as $key => $item) {
				if (is_array($item)) $values[] = "('".join("','", $item)."')";
				else unset($array[$key]);
			}

			if (!count($values)) return true;
			else return ($db->exec("REPLACE INTO `".$table."` (`".join('`,`', array_keys(end($array)))."`) VALUES ".join(',', $values).";")) ? true : false;
		}
	}
?>