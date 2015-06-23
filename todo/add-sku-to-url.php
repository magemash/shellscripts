<?php
// !! This file should not be on production.

require( __DIR__ . '/../app/Mage.php');

Mage::app('admin', 'store');

$stores = Mage::app()->getStores();

$products = Mage::getResourceModel('catalog/product_collection')
    ->addAttributeToSelect('name')
    ->addAttributeToSelect('sku');

foreach ($products as $product) {
    $p = $products->getItemByColumnValue('sku', $product->getSku());

    $p->getGroupPrice();

    $url = Mage::getModel('catalog/product_url')->formatUrlKey($p->getName() . ' ' . $p->getSku());


    echo $url . "\n";

    $p->setUrlKey($url);

    foreach ($stores as $store) {
        $p->setStoreId($store->getId())->setUrlKey($url);
        $p->save();
    }

}

