<?php
	include_once 'simple_html_dom.php';

	$str = "Добавлено 18 ноября 2015";
	preg_match("%(\\d\\d) (.*) (\\d\\d\\d\\d)%", $str, $matches);
	foreach ($matches as $item) {
		echo $item . '</br>';
	}
?>