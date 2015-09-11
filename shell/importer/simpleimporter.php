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

require_once 'abstractimporter.php';
require_once '../abstract.php';

class MageMash_Shell_Simpleimporter extends MageMash_Shell_Abstractimporter
{
    /**
     * Run script
     *
     */
    public function run()
    {
        $data = $this->getFile();

        die();

        for($i=1; $i<count($data); $i++) {
            $sku = $data[$i][0];

            $product = $productModel
                ->loadByAttribute('sku', $sku);

            $description = $data[$i][4];
            $cats = $data[$i][5];

            $product->setCategoryIds($cats);
            $product->setDescription($description);

            try {
                $product->save();
                echo "save " . $sku. "\n";
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        }
    }
}

$shell = new MageMash_Shell_Simpleimporter();
$shell->setFile('import/import.csv');
$shell->run();