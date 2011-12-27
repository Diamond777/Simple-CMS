<?
class ajax {
	public function response($data) {
		$array = array();

		if (!count($data)) $data['status'] = 'waiting';
		foreach ($data as $key => $val) $array[] = '"'.$key.'":"'.preg_replace(array('/[\s]+/u', '/"/'), array(' ', '\"'), $val).'"';

		echo '{'.join(',', $array).'}';
		exit;
	}


	public function waiting() {
		self::response(array('status' => 'waiting'));
	}


	public function error($html = false) {
		self::response(array('html' => $html, 'status' => 'error'));
	}


	public function done($html = false) {
		self::response(array('html' => $html, 'status' => 'done'));
	}
}
?>