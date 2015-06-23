<?php
// !! This file should not be on production.

require( __DIR__ . '/../app/Mage.php');

Mage::app('admin');

$scope = 'stores';
$scopeId = 0;

Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);


class Remove_Images
{
    protected $skus;
    protected $mediaApi;

    public function __construct()
    {
        $this->mediaApi = Mage::getModel("catalog/product_attribute_media_api");
    }

    public function run()
    {
        $this->getSkus();
        $this->removeImages();
    }

    protected function removeImages()
    {
        $productCollection = Mage::getResourceModel('catalog/product_collection');

        foreach ($this->skus as $sku) {
            $product = $productCollection->getItemByColumnValue('sku', $sku[0]);

            $this->removeImage($product);
        }
    }

    protected function removeImage(&$product)
    {
        if ($product->getId()){
            echo $product->getId() . "\n";

            $items = $this->mediaApi->items($product->getId());
            if (is_array($items)) {
                foreach($items as $item) {
                    $this->mediaApi->remove($product->getId(), $item['file']);
                    echo $item['file'] . "\n";
                }
            }
        }
    }

    protected function getSkus()
    {
        $file = 'removeImages/skus.csv';
        $csv = new Varien_File_Csv();
        $data = $csv->getData($file);

        $this->skus = $data;
    }
}

$removeImages = new Remove_Images();
$removeImages->run();