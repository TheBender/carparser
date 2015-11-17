<?php

include_once 'simple_html_dom.php';

$page = file_get_html('http://auto.ru/cars/audi/all/');

foreach($page->find('div[class="sales-list-item"]') as $element) {
    if (isset($element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'})) {
        $raw_array = $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-stat_params'};
        $array = json_decode($raw_array, true);
        echo 'ID: ' . $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'} . '</br>';
        echo 'offer_create_time ' . '</br>';
        echo 'picture_url ' . $element->find('img')[0]->src . '</br>';
        echo 'mark ' . $array['card_mark'] . '</br>';
        echo 'model ' . $array['card_model'] . ' generation ' . $array['card_generation'] . '</br>';
        echo 'price ' . $array['card_price'] . '</br>';
        echo 'manufacture_year ' . $array['card_year'] . '</br>';
        echo 'mileage ' . $array['card_run'] . '</br>';
        $color = $element->find('span[class="ico-appear"]')[0]->style;
        //echo 'color ' . strstr($color, '#') . '</br>';
        preg_match("%#(\\d+\\w+|\\w+)%", $color, $matches);
        echo 'color ' . $matches[0] . '</br>';
        echo 'body_type ' . '</br>';
        echo 'owner_phone ' . '</br>';
        echo 'city ' . '</br>';
    }
}
?>