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

class Matomo_Analytics_Adminhtml_IndexController
    extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();

        $siteId = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_SITE);
        $installPath = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_INSTALL);
        $token = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_TOKEN);

        if ($token && $siteId) {

            $params = '?module=Widgetize' .
                '&action=iframe' .
                '&moduleToWidgetize=Dashboard' .
                '&actionToWidgetize=index' .
                '&idSite=' . $siteId .
                '&period=week' .
                '&date=yesterday' .
                '&token_auth=' . $token;

            $block = $this->getLayout()->createBlock('core/text', 'matomo-block')->setText(
                '<iframe src="' . $installPath . '/index.php' . $params . '" 
                         frameborder="0" 
                         marginheight="0" 
                         marginwidth="0" 
                         width="100%" 
                         height="1000px"></iframe>'
            );

        } else {
            $block = $this->getLayout()->createBlock('core/text', 'matomo-block')->setText(
                $this->__('You are missing your Matomo Token Auth Key.')
            );
        }

        $this->_addContent($block);
        $this->_setActiveMenu('matomo_menu')->renderLayout();

    }
}
