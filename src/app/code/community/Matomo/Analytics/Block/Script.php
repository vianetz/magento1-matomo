<?php
declare(strict_types=1);

/**
 * Based on Piwik Extension for Magento created by Adrian Speyer
 *
 * @category    Matomo
 * @package     Matomo_Analytics
 * @copyright   Copyright (c) 2018 Thiago Contardi.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * @method array<string> getOrderIds()
 */
final class Matomo_Analytics_Block_Script extends Mage_Core_Block_Template
{
    /**
     * Get a specific page name (maybe customized via layout)
     */
    public function getPageName(): ?string
    {
        return $this->_getData('page_name');
    }

    /**
     * Render information about specified orders and their items
     * @see https://matomo.org/guide/reports/ecommerce/
     */
    public function getOrdersTrackingCode(): string
    {
        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return '';
        }

        /** @var Mage_Sales_Model_Resource_Order_Collection $collection */
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));
        $result = array();

        /** @var Mage_Sales_Model_Order $order */
        foreach ($collection as $order) {
            /** @var Mage_Sales_Model_Order_Item $item */
            foreach ($order->getAllVisibleItems() as $item) {
                if ($item->getQtyOrdered()) {
                    $qty = number_format((float)$item->getQtyOrdered(), 0, '.', '');
                } else {
                    $qty = '0';
                }
                $result[] = sprintf('_paq.push([\'addEcommerceItem\', \'%s\', \'%s\', \'%s\', %s, %s]);',
                    $this->jsQuoteEscape($item->getSku()),
                    $this->jsQuoteEscape($item->getName()),
                    $this->getFirstCategoryName($item->getProductId()),
                    $item->getBasePrice(),
                    $qty
                );
            }

            if ($order->getGrandTotal()) {
                $subtotal = $order->getGrandTotal() - $order->getShippingAmount() - $order->getShippingTaxAmount();
            } else {
                $subtotal = '0.00';
            }
            $result[] = sprintf("_paq.push(['trackEcommerceOrder' , '%s', %s, %s, %s, %s]);",
                $order->getIncrementId(),
                $order->getBaseGrandTotal(),
                $subtotal,
                $order->getBaseTaxAmount(),
                $order->getBaseShippingAmount()
            );
        }
        return implode("\n", $result);
    }

    /**
     * Render information when cart updated
     * @see https://matomo.org/guide/reports/ecommerce/
     */
    public function getEcommerceCartUpdate(): string
    {
        $result = array();

        /** @var Mage_Checkout_Model_Cart $cart */
        $cart = Mage::getModel('checkout/cart');
        $quote = $cart->getQuote();

        /** @var Mage_Sales_Model_Quote_Item $cartItem */
        foreach ($quote->getAllVisibleItems() as $cartItem) {
            $productName = $cartItem->getName();
            $productName = str_replace('"', "", $productName);

            if ($cartItem->getPrice() == 0 || $cartItem->getPrice() < 0.00001) {
                continue;
            }

            $result[] = sprintf(
                "_paq.push(['addEcommerceItem', '%s', '%s', '%s', %s, %s]);",
                $this->jsQuoteEscape($cartItem->getSku()),
                $this->jsQuoteEscape($productName),
                $this->getFirstCategoryName($cartItem->getProductId()),
                $cartItem->getPrice(),
                $cartItem->getQty()
            );
        }

        //total in cart
        $grandTotal = $quote->getGrandTotal();
        if ($grandTotal != 0) {
            $result[] = sprintf("_paq.push(['trackEcommerceCartUpdate', %s]);", $grandTotal);
        }

        return implode("\n", $result);
    }

    /**
     * Render information when product page view
     * @see https://matomo.org/guide/reports/ecommerce/
     */
    public function getProductPageview(): string
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::registry('current_product');

        if ($product instanceof Mage_Catalog_Model_Product) {
            return sprintf(
                "_paq.push(['setEcommerceView', '%s', '%s', '%s', %s]);",
                $this->jsQuoteEscape($product->getSku()),
                $this->jsQuoteEscape($product->getName()),
                $this->getFirstCategoryName((int)$product->getId()),
                $product->getFinalPrice()
            );
        }

        return '';
    }

    /**
     * Render information of category view
     * @see https://matomo.org/guide/reports/ecommerce/
     */
    public function getCategoryPageview(): string
    {
        $currentCategory = Mage::registry('current_category');

        if ($currentCategory instanceof Mage_Catalog_Model_Category) {
            return sprintf("_paq.push(['setEcommerceView', false, false, '%s']);", $currentCategory->getName());
        }

        return '';
    }

    public function getSiteId(): string
    {
        return Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_SITE);
    }

    public function getInstallPath(): string
    {
        $installPath = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_INSTALL);

        // remove https or https
        return preg_replace('/^https?:/', '', $installPath);
    }

    public function getSearchResultCount(): int
    {
        if ($this->getRequest()->getControllerName() !== 'result') {
            return 0;
        }

        $queryText = $this->helper('catalogsearch')->getQuery()->getQueryText();
        /** @var \Mage_CatalogSearch_Model_Resource_Fulltext_Engine $engine */
        $engine = $this->helper('catalogsearch')->getEngine();

        return $engine->getResultCollection()
            ->addSearchFilter($queryText)
            ->getSize();
    }

    public function is404(): bool
    {
        return $this->getAction() === 'noRoute';
    }

    public function _toHtml(): string
    {
        if (! $this->helper('matomoanalytics')->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }

    private function getFirstCategoryName(int $productId): string
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product')->load($productId);

        $categoryIds = $product->getCategoryIds();
        if (empty($categoryIds)) {
            return '';
        }

        $categoryId = $categoryIds[0];
        /** @var Mage_Catalog_Model_Category $category */
        $category = Mage::getModel('catalog/category')->load($categoryId);

        return $category->getName();
    }
}
