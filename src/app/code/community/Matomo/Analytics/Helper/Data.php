<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2012 Adrian Speyer.
 * @copyright Copyright (c) 2018 Thiago Contardi.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/** @package Matomo\Analytics */
final class Matomo_Analytics_Helper_Data extends Mage_Core_Helper_Abstract
{
    private const XML_PATH_ACTIVE = 'matomo/analytics/active';
    public const XML_PATH_SITE = 'matomo/analytics/site';
    public const XML_PATH_INSTALL = 'matomo/analytics/install';
    public const XML_PATH_TOKEN = 'matomo/analytics/token';

    /** @param mixed|null $store */
    public function isEnabled($store = null): bool
    {
        return Mage::getStoreConfig(self::XML_PATH_SITE, $store)
            && Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
    }
}
