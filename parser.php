<?php

include_once 'simple_html_dom.php';

$page = file_get_html('http://auto.ru/cars/audi/all/');

function date_translator($str){
    preg_match("%(\\d\\d) (.*) (\\d\\d\\d\\d)%", $str, $matches);
    
}


foreach($page->find('div[class="sales-list-item"]') as $element) {
    if (isset($element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'}) and $element->find('td[class="sales-list-cell"]')[3]->plaintext !== 'Реклама ') {
        $raw_array = $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-stat_params'};
        $array = json_decode($raw_array, true);
        $offer_link = 'http:' . $element->find('a[class="sales-list-link"]')[0]->href;
        $offer = file_get_html($offer_link);
        //echo 'ID: ' . $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'} . '</br>';
        $ID = $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'};
        var_dump($ID);
        //echo 'offer_create_time ' . $offer->find('li[title="Дата размещения"]')[0]->plaintext . '</br>';
        $offer_create_time = $offer->find('li[title="Дата размещения"]')[0]->plaintext;
        //echo 'picture_url ' . $element->find('img')[0]->src . '</br>';
        $picture_url = $element->find('img')[0]->src;
        //echo 'mark ' . $array['card_mark'] . '</br>';
        $mark = $array['card_mark'];
        //echo 'model ' . $array['card_model'] . ' generation ' . $array['card_generation'] . '</br>';
        $model = $array['card_model'] . $array['card_generation'];
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

        mysql_pconnect("localhost","root","") or die ("Невозможно подключение к MySQL");
        mysql_query('SET NAMES utf8');
        mysql_query('SET CHARACTER SET utf8' );
        mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"' );
        mysql_select_db("carparser") or die ("Невозможно открыть таблицу с данными");
        //$db = 'carparser';
        //mysql_query('SET NAMES "utf8"');
        //mysql_query("set character_set_connection=utf8");
        //mysql_query("set names utf8");

        $result = mysql_query("INSERT INTO offers (id,offer_create_time,picture_url,mark,model,price,manufacture_year,mileage,color,body_type,phone,city) VALUES ('".$ID."','".$offer_create_time."','".$picture_url."','".$mark."','".$model."','".$price."','".$manufacture_year."','".$mileage."','".$color."','".$body_type."','".$phone."','".$city."')");
        if ($result) {
            var_dump($result);
            echo "<p>Данные успешно добавлены в таблицу.</p>";
        } else {
            var_dump($result);
            echo "<p>Произошла ошибка.</p>";
        }
    }
}
?>