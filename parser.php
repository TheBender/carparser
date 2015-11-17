<?php

include_once 'simple_html_dom.php';

$page = file_get_html('http://auto.ru/cars/audi/all/');

foreach($page->find('div[class="sales-list-item"]') as $element) {
    if (isset($element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'})) {
        echo 'ID: ' . $element->find('tr[class="sales-list-row in-logging"]')[0]->{'data-sale_id'} . '</br>';
        echo 'offer_create_time ' . '</br>';
        echo 'picture_url ' . $element->find('img')[0]->src . '</br>';
        echo 'mark'  . '</br>';
        echo 'model ' . 'generation' . '</br>';
        echo 'price ' . '</br>';
        echo 'manufacture_year ' . '</br>';
        echo 'mileage ' . '</br>';
        echo 'color ' . '</br>';
        echo 'body_type ' . '</br>';
        echo 'owner_phone ' . '</br>';
        echo 'city ' . '</br>';
    }
}
?>