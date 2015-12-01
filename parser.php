<?php

include_once 'simple_html_dom.php';
mysql_pconnect("localhost","root","") or die ("Невозможно подключение к MySQL");
mysql_query('SET NAMES utf8');
mysql_query('SET CHARACTER SET utf8' );
mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"' );
mysql_select_db("carparser") or die ("Невозможно открыть таблицу с данными");


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
    return $day . '.' . $mounth . '.' . $year;
}

$mark_list = file_get_html('http://auto.ru/');
$link_list = array();
foreach($mark_list->find('li[class="mmm__item"]') as $mark_element){
    if(null !== $mark_element->find('sup[class="mmm__count"]')){
        //echo $mark_element->find('a')[0]->href . '</br>';
        $link_to_add = 'http:' . $mark_element->find('a')[0]->href;
        array_push($link_list, $link_to_add);
    }
}

foreach($link_list as $link){
    $page = file_get_html($link);
    foreach($page->find('div[class="sales-list-item"]') as $element) {
        if (isset($element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'}) and $element->find('td[class="sales-list-cell"]')[3]->plaintext !== 'Реклама ') {
            $raw_array = $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-stat_params'};
            $array = json_decode($raw_array, true);
            $ID = $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'};
            var_dump($ID);
            $check_id = mysql_query("SELECT * FROM `offers` WHERE `id` = '". $ID ."'");
            if ($check_id) {
                $offer_link = 'http:' . $element->find('a[class="sales-list-link"]')[0]->href;
                $offer = file_get_html($offer_link);
                //echo 'offer_create_time ' . $offer->find('li[title="Дата размещения"]')[0]->plaintext . '</br>';
                $offer_create_time = date_translator($offer->find('li[title="Дата размещения"]')[0]->plaintext);
                //echo 'picture_url ' . $element->find('img')[0]->src . '</br>';
                if(isset($element->find('img')[0]->src)){
                    $picture_url = $element->find('img')[0]->src;
                }else{
                    $picture_url = null;
                }
                //echo 'mark ' . $array['card_mark'] . '</br>';
                $mark = $array['card_mark'];
                //echo 'model ' . $array['card_model'] . ' generation ' . $array['card_generation'] . '</br>';
                if(isset($array['card_generation'])){
                    $model = $array['card_model'] . $array['card_generation'];
                }else{
                    $model = $array['card_model'];
                }
                //echo 'price ' . $array['card_price'] . '</br>';
                $price = $array['card_price'];
                //echo 'manufacture_year ' . $array['card_year'] . '</br>';
                $manufacture_year = $array['card_year'];
                //echo 'mileage ' . $array['card_run'] . '</br>';
                $mileage = $array['card_run'];
                $color = $element->find('span[class="ico-appear"]')[0]->style;
                preg_match("%#(\\d+\\w+|\\w+)%", $color, $matches);
                //echo 'color ' . $matches[0] . '</br>';
                $color = $matches[0];
                //echo 'body_type ' . $element->find('span[class="sales-list-body-name"]')[0]->plaintext . '</br>';
                $body_type = $element->find('span[class="sales-list-body-name"]')[0]->plaintext;
                ////добываем телефон
                //echo $offer->find('div[class="show-phone__number"]')[0]->plaintext . '</br>';
                $phone = $offer->find('div[class="show-phone__number"]')[0]->plaintext;
                //echo 'city ' . $element->find('div[class="sales-list-region"]')[0]->plaintext . '</br>';
                $city = $element->find('div[class="sales-list-region"]')[0]->plaintext;

                $result = mysql_query("INSERT INTO offers (id,offer_create_time,picture_url,mark,model,price,manufacture_year,mileage,color,body_type,phone,city) VALUES ('".$ID."','".$offer_create_time."','".$picture_url."','".$mark."','".$model."','".$price."','".$manufacture_year."','".$mileage."','".$color."','".$body_type."','".$phone."','".$city."')");
                if ($result) {
                    var_dump($result);
                    echo "<p>Данные успешно добавлены в таблицу.</p>";
                } else {
                    var_dump($result);
                    echo "<p>Произошла ошибка.</p>";
                }
                sleep(5);
            } else {
                echo "<p>Уже существует</p>";
            }
        }
    }
}

?>