<?php
include_once 'simple_html_dom.php';

function date_translator($str){
	preg_match("%(\\d\\d) (.*) (\\d\\d\\d\\d)%", $str, $matches);
	$day = $matches[1];
	$year = $matches[3];
	switch($matches[2]){
		case 'января':
			$mounth = '01';
			break;
		case 'февраля':
			$mounth = '02';
			break;
		case 'марта':
			$mounth = '03';
			break;
		case 'апреля':
			$mounth = '04';
			break;
		case 'мая':
			$mounth = '05';
			break;
		case 'июня':
			$mounth = '06';
			break;
		case 'июля':
			$mounth = '07';
			break;
		case 'августа':
			$mounth = '08';
			break;
		case 'сентября':
			$mounth = '09';
			break;
		case 'октября':
			$mounth = '10';
			break;
		case 'ноября':
			$mounth = '11';
			break;
		case 'декабря':
			$mounth = '12';
			break;
		default:
			$mounth = $matches[2];
	}
	echo $day . '.' . $mounth . '.' . $year;
}
date_translator('Добавлено 19 сентября 2015');
?>