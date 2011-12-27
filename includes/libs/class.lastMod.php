<?
	class lastMod {
		var $lastMod = array();
		function addDate($date) {$this->lastMod[] = (!is_int($date)) ? strtotime($date) : $date;}
		function send() {
			rsort($this->lastMod);
			$date = ( $this->lastMod[0] > (time() - 31536000*5) ) ? $this->lastMod[0] : false;
			if ($date) header('Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T', $date));
		}
	}
?>