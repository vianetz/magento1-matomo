<?php
/**
 *
 * Based on Piwik Extension for Magento created by Adrian Speyer
 *
 * @category    Matomo
 * @package     Matomo_Analytics
 * @copyright   Copyright (c) 2018 Thiago Contardi.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */


class Matomo_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_ACTIVE = 'matomo/analytics/active';
    const XML_PATH_SITE = 'matomo/analytics/site';
    const XML_PATH_INSTALL = 'matomo/analytics/install';
    const XML_PATH_TOKEN = 'matomo/analytics/token';

    /**
     *
     * @param mixed $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        $siteId = Mage::getStoreConfig(self::XML_PATH_SITE, $store);
        return $siteId && Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
    }
}
