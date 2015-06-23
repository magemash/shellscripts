<?php
/**
 * @author     Graeme Houston (getsquare.co.uk)
 * @copyright  Copyright (c) 2014 GetSquare
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @file       AddOptions.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '5000M');
set_time_limit(0);


require_once '../../app/Mage.php';

Mage::init();

/**
 * Build an array of Ring Skus
 * @return array
 */

$collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('*');

$ringSkus = array();

foreach ($collection as $product) {

    $name = strtolower($product->getName());

    if (strpos($name, ' ring') !== false) {

        $ringSkus[] = $product->getSku();
    }

}

/**
 * Build our Options Array
 * @return array
 */

$options = array();
$sizes = array(
    "H",
    "H.5",
    "I",
    "I.5",
    "J",
    "J.5",
    "K",
    "K.5",
    "L",
    "L.5",
    "M",
    "M.5",
    "N",
    "N.5",
    "O",
    "O.5",
    "P",
    "P.5",
    "Q",
    "Q.5",
    "R",
    "R.5",
    "S",
    "S.5",
    "T",
    "T.5",
    "U",
    "U.5",
    "V",
    "V.5",
    "W",
    "W.5",
    "X",
    "X.5",
    "Y",
    "Y.5",
    "Z",
    "Z.5"
);
$i = 1;

foreach ($ringSkus as $sku) {

    $options[$sku] = array(
        'title' => 'Size',
        'type' => 'drop_down',
        'is_require' => 1,
        'sort_order' => 0,
        'values' => array()
    );

    foreach ($sizes as $size) {

        $options[$sku]['values'][] = array(
            'title' => $size,
            'price' => 0.00,
            'price_type' => 'fixed',
            'sku' => '',
            'sort_order' => $i++
        );

    }
}

/**
 * Iterate through skus and add custom options
 * @return void
 */
foreach ($options as $sku => $option) {
    $id = Mage::getModel('catalog/product')->getIdBySku($sku);
    $product = Mage::getModel('catalog/product')->load($id);

    if (!$product->getOptionsReadonly()) {
        $product->setProductOptions(array($option));
        $product->setCanSaveCustomOptions(true);
        $product->save();

        echo $product->getSku() . " saved! \n";
    }
}










