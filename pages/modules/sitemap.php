<?
	ksort($menu);
	foreach ($menu as $m) {
		echo '<ul>';
		foreach ($m as $key => $item) {
			if ($item['alias'] == 'sitemap') continue;
			echo '<li><a href="'.$item['href'].'">'.$item['name'].'</a></li>';
		}
		echo '</ul>';
	}
?>