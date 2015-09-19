<?php
/**
 * Update Config Shell Script
 *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public License
 * version 2.1 as published by the Free Software Foundation.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details at
 * http://www.gnu.org/copyleft/lgpl.html
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Scott Pringle <scott@magemash.com>
 * @version     1
 */

require_once 'abstracdatafixer.php';
require_once '../abstract.php';

class MageMash_Shell_Skudatafixer extends MageMash_Shell_Abstractdatafixer
{
    public function run()
    {
        $products = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku');

        foreach ($products as $product) {
            $url = Mage::getModel('catalog/product_url')->formatUrlKey($product->getName());

            echo $url . "\n";

            $p->setUrlKey($url);
            $p->save();
        }

    }
}

$shell = new MageMash_Shell_Skudatafixer();
$shell->run();