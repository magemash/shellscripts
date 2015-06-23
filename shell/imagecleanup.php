<?php
/**
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Magpleasure
 * @package    Magpleasure_Imagecleanup
 * @copyright  Copyright (c) 2014 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Magento Image CleanUp
 *
 * @category    Magpleasure
 * @package     Magpleasure_Imagecleanup
 * @author      Igor Goltsov <general@magpleasure.com>
 */
class Mage_Shell_Imagecleanup extends Mage_Shell_Abstract
{
    protected $_tableName;

    protected function _isCleanUp()
    {
        return $this->getArg('cleanup');
    }

    protected function _isSilent()
    {
        return $this->getArg('silent');
    }

    /**
     * Get Wrapped Table Name
     *
     * @param string $tableName
     * @return string
     */
    protected function _getTableName($tableName)
    {
        if (!$this->_tableName) {
            /** @var $resource Mage_Core_Model_Resource */
            $resource = Mage::getSingleton('core/resource');
            $tableName = str_replace(array_keys($this->_tableNameFixes), array_values($this->_tableNameFixes), $tableName);
            $this->_tableName = $resource->getTableName($tableName);
        }
        return $this->_tableName;
    }

    protected function _echo($message)
    {
        echo "{$message}\n";
    }

    /**
     * Read Connection
     *
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getReadConnection()
    {
        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        return $resource->getConnection('core_read');
    }

    protected function _isInDatabase($imagePath)
    {
        $tableName = $this->_getTableName("catalog_product_entity_media_gallery");
        $sql = sprintf("SELECT count(*) FROM %s WHERE value = '%s'", $tableName, $imagePath);
        return !!$this->_getReadConnection()->fetchOne($sql);
    }

    protected function _getCacheImageFromImage($image)
    {
        $parts = explode("/", $image);
        if (count($parts)){
            return $parts[count($parts) - 1];
        }

        return false;
    }

    protected function _escapeSpaces($fileName)
    {
        return str_replace(' ', '\ ', $fileName);
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        $date = new Zend_Date(null, null, Mage::app()->getLocale()->getLocaleCode());
        $count = 0;
        $this->_echo(sprintf("Starting image cleanup %s", $date->__toString(Zend_Date::DATETIME_MEDIUM)));

        $imagePath = Mage::getBaseDir('media')."/catalog/product";
        $imageCachePath = Mage::getBaseDir('media')."/catalog/product/cache";
        $shellCommand = sprintf("find %s \\( -iname '*.gif' -o -iname '*.jpg' -o -iname '*.png' \\) -a -not -ipath '*cache*' -a -not -iname 'google*'", $imagePath);

        exec($shellCommand, $images);
        foreach ($images as $image){

            if (!$this->_isInDatabase(str_replace($imagePath, "", $image))){

                if ($this->_isCleanUp()){

                    $cacheImage = $this->_getCacheImageFromImage(str_replace($imagePath, "", $image));
                    if ($cacheImage){

                        $shellCommand = sprintf("find %s -name '%s'", $imageCachePath, $cacheImage);

                        $cachedImages = array();
                        exec($shellCommand, $cachedImages);

                        if (is_array($cachedImages) && count($cachedImages)){

                            foreach ($cachedImages as $cacheImage){
                                exec(sprintf("rm %s", $this->_escapeSpaces($cacheImage)));
                            }
                        }
                    }

                    if (!$this->_isSilent()){
                        $this->_echo(sprintf("Removing unused image ..%s", str_replace($imagePath, "", $image)));
                    }

                    exec(sprintf("rm %s", $this->_escapeSpaces($image)));

                } else {

                    if (!$this->_isSilent()){
                        $this->_echo(sprintf("Not touching ..%s", str_replace($imagePath, "", $image)));
                    }
                }

                $count++;
            }
        }

        $this->_echo("");
        $this->_echo(sprintf("%s unused images found.", $count));

        if (!$this->_isCleanUp() && $count){
            $this->_echo("Use \"php imagecleanup.php --cleanup\" to remove images founded.");
        }

        $this->_echo("Complete.");
    }
}

$shell = new Mage_Shell_Imagecleanup();
$shell->run();
