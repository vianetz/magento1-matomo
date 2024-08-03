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

final class Matomo_Analytics_Model_Observer
{
    /**
     * Add order information into Piwik block to render on checkout success pages
     */
    public function setMatomoAnalyticsOnOrderSuccessPageView(Varien_Event_Observer $observer): void
    {
        if (! Mage::helper('matomoanalytics')->isEnabled()) {
            return;
        }

        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || ! is_array($orderIds)) {
            return;
        }

        $block = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('matomo_script');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
    }
}
   
   