<?php
// Boot up Magento
require_once '../app/Mage.php';

Mage::setIsDeveloperMode(true);
Mage::init();

error_reporting(E_ALL);
ini_set('display_errors', -1);

Mage::app('admin', 'stores');
$store = Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$file = 'cats.csv';
$csv = new Varien_File_Csv();
$data = $csv->getData($file);

$array = array();

for($i=0; $i<count($data); $i++)
{
    $current = "";

    foreach ($data as $field) {
        if ($field[0] != "") {
            $current = $field[0];
//            $array[$current] = [];
            $array[$current] = buildCats($data, array());
        }

        if ($field[1] == "") {
            continue;
        }

        if ($current != "") {
            $array[$current][] = $field[1];
        }
    }

    //renderCat($row);


    //var_dump( $data[$i] );
}

function buildCats($row, $array)
{
    foreach ($row as $field) {

    }
}

var_dump($array);
die();

$categoryCollection = Mage::getResourceModel('catalog/category_collection');

$topCat = $categoryCollection
    ->addFieldToFilter('name', 'Finishing Touches')
    ->getFirstItem();

if ($topCat->getId() === null) {
    try{
        $topCat = Mage::getModel('catalog/category');
        $topCat->setName("Finishing Touches");
        $topCat->setIsActive(0);
        $parentCategory = Mage::getModel('catalog/category')->load(2);
        $topCat->setPath($parentCategory->getPath());
        $topCat->save();
    } catch(Exception $e) {
        var_dump($e->getMessage());
    }
}

$occasions = Mage::getModel('interflora/occasions')->getOptionArray();

foreach ($occasions as $key => $value) {
    $childCat = $topCat
        ->getChildrenCategoriesWithInactive()
        ->addFieldToFilter('name', $value);

    if ($childCat->count() < 1) {
        try{
            $c = Mage::getModel('catalog/category');
            $c->setName($value);
            $c->setIsActive(0);
            $parentCategory = $topCat->getFirstItem();
            $c->setPath($topCat->getPath());
            $c->save();
        } catch(Exception $e) {
            var_dump($e);
        }
    }
}


