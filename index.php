<?php
require_once 'vendor/autoload.php';

$config = include('config.php');

global $config;

use App\Controller\ReadJson;
use App\Collection\Product;
use App\Collection\Cart;

// Read and analyze JSON file
// Convert JSON to array then parse data
$data = new ReadJson($config['tempFile']);

$scanItems = array(
    array(
        'code'  => 'prod10000274',
        'qty'   => 2,
        'weight'    =>  null
    ),
    array(
        'code'  => 'prod50000193',
        'qty'   => 1,
        'weight'    =>  1000
    ),
    array(
        'code'  => 'prod50000201',
        'qty'   => 1,
        'weight'    =>  250
    ),
    array(
        'code'  => 'prod10000364',
        'qty'   => 5,
        'weight'    =>  null
    ),
    array(
        'code'  => 'prod10000411',
        'qty'   => 4,
        'weight'    =>  null
    ),
   
);

$cart = new Cart();

foreach($scanItems as $scanItem) {
    $key = array_search($scanItem['code'], array_column($data->getResult(), 'mProductId'));
    $item = $data->getResult()[$key];
    $saleType = null;
    $saleType = (($item['mIsOnBuyOneTakeOne']) ? "mIsOnBuyOneTakeOne" : $saleType);
    $saleType = (($item['mIsOnBuyTwoTakeOne']) ? "mIsOnBuyTwoTakeOne": $saleType);

    for($i=0; $i < $scanItem['qty']; $i++) {
        $cart->add_item(new Product($item['mSkuType'], $item['mName'], $item['mPrice'], $scanItem['weight'], $saleType), $item['mProductId']);
    }    
}

echo '<div style=\'width:400px\'>';
$cart->showReceipt();
echo '</div>';