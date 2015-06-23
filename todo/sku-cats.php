<?php
// Boot up Magento
require_once '../app/Mage.php';

Mage::setIsDeveloperMode(true);
Mage::init();

error_reporting(E_ALL);
ini_set('display_errors', -1);

Mage::app('admin', 'stores');
$store = Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$file = 'sku-cats.csv';
$csv = new Varien_File_Csv();
$data = $csv->getData($file);

$array = array();

$productCollection = Mage::getResourceModel('catalog/product_collection');

foreach ($data as $field) {
    $catArray = array();

    foreach (explode(',', $field[1]) as $catId) {
        if ($catId != " " && $catId != "") {
            $catArray[] = trim($catId);
        }
    }

    $product = $productCollection->getItemByColumnValue('sku', $field[0]);
    $product->setCategoryIds($catArray);
    $product->getGroupPrice();

    $product->save();
    echo "sku " . $field[0] . "\n";
}

echo "Categories Added";
die();
