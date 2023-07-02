<?php
declare(strict_types=1);

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

final class Matomo_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ACTIVE = 'matomo/analytics/active';
    const XML_PATH_SITE = 'matomo/analytics/site';
    const XML_PATH_INSTALL = 'matomo/analytics/install';
    const XML_PATH_TOKEN = 'matomo/analytics/token';

    /**
     * @param mixed $store
     */
    public function isEnabled($store = null): bool
    {
        return Mage::getStoreConfig(self::XML_PATH_SITE, $store)
            && Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
    }
}
