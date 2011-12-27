<?
	function addZnak($img_src, $znak_src, $delit = 5) {
		$res_file = $img_src;

		if (function_exists('image_type_to_mime_type') && function_exists('exif_imagetype')) {
			$mime = image_type_to_mime_type(@exif_imagetype($img_src));
			switch ($mime) {
				case 'image/jpeg': $img=imagecreatefromjpeg($img_src); break;
				case 'image/gif': $img=imagecreatefromgif($img_src); break;
				case 'image/png': $img=imagecreatefrompng($img_src); break;
				default: return false;
			}

			$mime = image_type_to_mime_type(@exif_imagetype($znak_src));
			switch ($mime) {
				case 'image/jpeg': $znak=imagecreatefromjpeg($znak_src); break;
				case 'image/gif': $znak=imagecreatefromgif($znak_src); break;
				case 'image/png': $znak=imagecreatefrompng($znak_src); break;
				default: return false;
			}
		}
		else {
			$img = imagecreatefromjpeg($img_src);
			$znak = imagecreatefromjpeg($znak_src);
		}


		$file_info = getimagesize($img_src);
		$img_w = $file_info[0];
		$img_h = $file_info[1];

		$file_info = getimagesize($znak_src);
		$znak_w = $file_info[0];
		$znak_h = $file_info[1];


		$w = round($img_w / $delit);
		$h = round($img_h / $delit);


		if ($img_w / $img_h > $znak_w / $znak_h) {
			$znak_res_h = $h;
			$znak_res_w = round($znak_res_h * $znak_w / $znak_h);
		}
		else {
			$znak_res_w = $w;
			$znak_res_h = round($znak_res_w * $znak_h / $znak_w);
		}


		if (ImageCopyResampled($img, $znak, $img_w - $znak_res_w, $img_h - $znak_res_h, 0, 0, $znak_res_w, $znak_res_h, $znak_w, $znak_h)) {
			if (@file_exists($res_file)) unlink($res_file);
			@imageJpeg($img, $res_file);
		}
		if (@file_exists($res_file)) return true;
		return false;
	}



	function resizer($file_src, $file_res, $width, $height) {
		if (function_exists('image_type_to_mime_type') && function_exists('exif_imagetype')) {
			$mime = image_type_to_mime_type(@exif_imagetype($file_src));
			switch ($mime) {
				case 'image/jpeg': $gd=imagecreatefromjpeg($file_src); break;
				case 'image/gif': $gd=imagecreatefromgif($file_src); break;
				case 'image/png': $gd=imagecreatefrompng($file_src); break;
				default: return false;
			}
		}
		else $gd = imagecreatefromjpeg($file_src);

		$file_info = getimagesize($file_src);

		$file_res_w = $file_w = $file_info[0];
		$file_res_h = $file_h = $file_info[1];

		if ($width > 0 && $file_res_w > $width) {
			$file_res_w = $width;
			$file_res_h = round($file_res_w * $file_h / $file_w);
		}
		if ($height > 0 && $file_res_h > $height) {
			$file_res_h = $height;
			$file_res_w = round($file_res_h * $file_w / $file_h);
		}

		$result = imagecreatetruecolor($file_res_w, $file_res_h);

		if (ImageCopyResampled($result, $gd, 0, 0, 0, 0, $file_res_w, $file_res_h, $file_w, $file_h)) {
			if (@file_exists($file_res)) unlink($file_res);
			@imageJpeg($result, $file_res);
		}

		if (@file_exists($file_res)) return true;

		return false;
	}
?>