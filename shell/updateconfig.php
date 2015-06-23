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

require_once 'abstract.php';
require_once 'config.php';

class MageMash_Shell_UpdateConfig extends Mage_Shell_Abstract
{
    protected $config;
    protected $configModel;

    /**
     * Run script
     *
     */
    public function run()
    {
        $this->setConfigModel();
        $config = $this->config;

        ini_set("display_errors", 1);
        Mage::app('admin')->setUseSessionInUrl(false);
        Mage::getConfig()->init();
        foreach ($config as $key => $value) {
            switch($key) {
                case "default":
                    $this->addConfig($value);
                    break;
                case "websites":
                    foreach ($value as $k => $v) {
                        $this->addConfig($v, 'websites', $k);
                    }
                    break;
                case "stores":
                    foreach ($value as $k => $v) {
                        $this->addConfig($v, 'stores', $k);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    protected function setConfigModel()
    {
        $this->configModel = new Mage_Core_Model_Config();
    }

    protected function addConfig($items, $scope = "default", $scopeId = 0)
    {
        foreach ($items as $key => $value) {
            $this->configModel->saveConfig($key, $value, $scope, $scopeId);
        }
    }
}

$shell = new MageMash_Shell_UpdateConfig();
$shell->setConfig($config);
$shell->run();