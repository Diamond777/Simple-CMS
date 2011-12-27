<?
	function full_rmdir($directory, $delete_root = true) {
		if (!@file_exists($directory)) return true;
		if ($dir = opendir($directory)) {

			while ($file = readdir($dir)) {
				if ($file == '.' || $file == '..') continue;

				if (is_dir($directory.$file)) {
					if (!full_rmdir($directory.$file.'/')) return false;
				}
				elseif (!unlink($directory.$file)) return false;
			}

			closedir($dir);

			if ($delete_root && !@rmdir($directory)) return false;
			return true;
		}
		else return false;
	}



	function make_dir($dir, $chmod = 0777) {
		if ((!is_readable($dir) || !is_dir($dir)) && (!@mkdir($dir,$chmod) || !is_readable($dir) || !is_dir($dir))) return false;
		return true;
	}
?>