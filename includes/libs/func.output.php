<?
	function get_pages($total, $start, $href = false, $limit = false, $mode = false) {
		$this_url = (empty($href)) ? urlVariables() : urlVariables($href);
		unset($this_url['get']['start']);

		$limit = (!empty($limit)) ? $limit : LIMIT_ON_PAGE;

		$num_pages = ceil($total / $limit);
		if ($num_pages <= 1) return '';

		$pgs = array();

		for ($i = 1; $i <= $num_pages; $i++) {
			$get = $this_url['get'];
			$_start = ($i - 1) * $limit;

			if ($_start) $get['start'] = $_start;

			$_href = (count($get)) ? $this_url['page'].'?'.makeURLget($get) : $this_url['page'];

			$pgs[] = ($start == $_start) ? '<strong>'.$i.'</strong>' : '<a href="'.$_href.'">'.$i.'</a>';
		}

		$str = '';

		switch ($mode) {
			case '1':
				if ($start > 0) {
					$get = $this_url['get'];
					$_start = ($start - $limit);
					if ($_start) $get['start'] = $_start;
					$_href = (count($get)) ? $this_url['page'].'?'.makeURLget($get) : $this_url['page'];
					$str .= '<a href="'.$_href.'">Назад</a> ';
				}
				$str .= join(' ', $pgs);
				if ($total > ($start + $limit)) {
					$get = $this_url['get'];
					$_start = ($start + $limit);
					if ($_start) $get['start'] = $_start;
					$_href = (count($get)) ? $this_url['page'].'?'.makeURLget($get) : $this_url['page'];
					$str .= '<a href="'.$_href.'">Вперед</a> ';
				}
				break;
			case 'array':
				return $pgs;
				break;
			case 'pagesHTML':
				return '<div class="pagesHTML">'.join(' ', $pgs).'</div>';
				break;
			default: $str = 'Страницы: '.join(' ', $pgs);
		}

		return '<p>'.$str.'</p>';
	}


	function format_price($price, $sp = '’') {
		$strlen = strlen($price);
		$num_sp = floor($strlen / 3);
		$remainder = $strlen % 3;
		if ($num_sp) {
			$new_price = '';
			for ($i = 0; $i < $strlen; $i++) {
				if ((($i - $remainder) % 3) == 0 && $i != 0) $new_price .= $sp;
				$new_price .= $price[$i];
			}
		}
		else $new_price = $price;
		return $new_price;
	}


	function get_img($file_name, $path = '', $size = false, $border = 1, $href = false, $iparam = array(), $aparam = array()) {
		if (!empty($size) && !empty($size[0])) {
			if (is_readable(UPLOAD_DIR.$path.$size[0].'/'.$file_name)) $sm_file = $path.$size[0].'/'.$file_name;
		}
		elseif (is_readable(UPLOAD_DIR.$path.$file_name)) $sm_file = $path.$file_name;

		if (empty($sm_file)) return false;

		$iparams = $aparams = array();
		$iparams[] = ''; $aparams[] = '';
		if (count($iparam)) foreach ($iparam as $key => $item) $iparams[] = $key.'="'.$item.'"';
		if (count($aparam)) foreach ($aparam as $key => $item) $aparams[] = $key.'="'.$item.'"';

		$big_file = (!empty($size) && !empty($size[1]) && is_readable(UPLOAD_DIR.$path.$size[1].'/'.$file_name)) ? $path.$size[1].'/'.$file_name : false;

		$i_size = getimagesize(UPLOAD_DIR.$sm_file);
		$w_sm = $i_size[0];
		$h_sm = $i_size[1];

		$style = 'width:'.$w_sm.'px; height:'.$h_sm.'px; border:'.(string)(int) $border.'px solid;';

		$img = '<img'.join(' ', $iparams).' src="'.UPLOAD_SRC.$sm_file.'" style="'.$style.'" />';
		if ($big_file && empty($href)) {
			$i_size = getimagesize(UPLOAD_DIR.$big_file);
			$w_big = $i_size[0];
			$h_big = $i_size[1];

			$img = '<a'.join(' ', $aparams).' class="fancy" href="'.UPLOAD_SRC.$big_file.'">'.$img.'</a>';
		}
		elseif (!empty($href)) $img = '<a'.join(' ', $aparams).' href="'.$href.'">'.$img.'</a>';
		return $img;
	}


	function cDate($date) {
		$exp = explode('-', $date);
		if ($exp[0] == '0000') return false;
		if ($exp[1] == '00' || $exp[2] == '00') $date = $exp[0];
		else $date = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)", "\\3.\\2.\\1", $date);
		return $date;
	}


	function get_day($date = 0) {
		$date = (!empty($date)) ? $date : date('l');
		$day = '';
		switch ($date) {
			case 'Sunday':		$day='Воскресенье';	break;
			case 'Monday':		$day='Понедельник';	break;
			case 'Tuesday':		$day='Вторник';			break;
			case 'Wednesday':	$day='Среда';				break;
			case 'Thursday':	$day='Четверг';			break;
			case 'Friday':		$day='Пятница';			break;
			case 'Saturday':	$day='Суббота';			break;
		}
		return $day;
	}

	function get_day_short($date = 0) {
		$date = (!empty($date)) ? $date : date('l');
		$day = '';
		switch ($date) {
			case 'Sunday':		$day = 'Вс';	break;
			case 'Monday':		$day = 'Пн';	break;
			case 'Tuesday':		$day = 'Вт';	break;
			case 'Wednesday':	$day = 'Ср';	break;
			case 'Thursday':	$day = 'Чт';	break;
			case 'Friday':		$day = 'Пт';	break;
			case 'Saturday':	$day = 'Сб';	break;
		}
		return $day;
	}

	function get_month($date = 0, $lang = 'ru') {
		$date = (!empty($date)) ? $date : date('m');
		$mm = '';
		switch ($date) {
			case '1':		$mm = ($lang == 'ru') ? 'января' : 'January';			break;
			case '2':		$mm = ($lang == 'ru') ? 'февраля' : 'February';		break;
			case '3':		$mm = ($lang == 'ru') ? 'марта' : 'March';				break;
			case '4':		$mm = ($lang == 'ru') ? 'апреля' : 'April';				break;
			case '5':		$mm = ($lang == 'ru') ? 'мая' : 'May';						break;
			case '6':		$mm = ($lang == 'ru') ? 'июня' : 'June';					break;
			case '7':		$mm = ($lang == 'ru') ? 'июля' : 'July';					break;
			case '8':		$mm = ($lang == 'ru') ? 'августа' : 'August';			break;
			case '9':		$mm = ($lang == 'ru') ? 'сентября' : 'September';	break;
			case '10':	$mm = ($lang == 'ru') ? 'октября' : 'October';		break;
			case '11':	$mm = ($lang == 'ru') ? 'ноября' : 'November';		break;
			case '12':	$mm = ($lang == 'ru') ? 'декабря' : 'December';		break;
		}
		return $mm;
	}


	function get_loaded_in() {return 'loaded in '.number_format( (getmicrotime() - START_TIME), 3, '.', '' ).' sec';}


	function myStrToUpOrLow($str, $mode = 'upper') {
		$lowerArray = array('а','б','в','г','д','е','ё','ж','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ы','ъ','э','ю','я');
		$upperArray = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ы','Ъ','Э','Ю','Я','','','','Я');
		switch ($mode) {
			case 'lower':		return str_replace($upperArray, $lowerArray, strtolower($str)); break;
			default:        return str_replace($lowerArray, $upperArray, strtoupper($str));
		}
		return str_replace($upperArray, $lowerArray, $str);
	}


	function firstSimbolToUp($str) {
return $str;

		$upper = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
		$lower = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";

		$firstSimbol = substr($str, 0, 2);

		if (strstr($lower,$firstSimbol) === false) return ucfirst($str);
		return strtr($firstSimbol, $lower, $upper).substr($str, 2);
	}


	function cyr_to_lat($st) {
		$arr1=array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц'	,'ч'	,'ш'	,'щ'		,'ь','ы','ъ','э','ю'	,'я'	,'_','-',' ','ї','є');
		$arr2=array('a','b','v','g','d','e','e','j','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch'	,'sh'	,'shch'	,''	,'i',''	,'e','yu'	,'ya'	,'_','-','-','i','ie');

		$st = str_ireplace($arr1, $arr2, myStrToUpOrLow($st, 'lower'));
		$st = preg_replace('/[^a-z0-9_-]/', '', $st);

		return $st;
	}
?>