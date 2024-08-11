<?php
declare(strict_types=1);

/**
 * @copyright Copyright (c) 2012 Adrian Speyer.
 * @copyright Copyright (c) 2018 Thiago Contardi.
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/** @package Matomo\Analytics */
final class Matomo_Analytics_Adminhtml_Matomo_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction(): void
    {
        $this->loadLayout();

        $siteId = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_SITE);
        $installPath = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_INSTALL);
        $token = Mage::getStoreConfig(Matomo_Analytics_Helper_Data::XML_PATH_TOKEN);

        $block = $this->getLayout()->createBlock('core/text', 'matomo-block');
        if ($block === false) {
            return;
        }

        if ($token && $siteId) {
            $params = '?module=Widgetize' .
                '&action=iframe' .
                '&moduleToWidgetize=Dashboard' .
                '&actionToWidgetize=index' .
                '&idSite=' . $siteId .
                '&period=week' .
                '&date=yesterday' .
                '&token_auth=' . $token;

            $block->setText('<iframe src="' . $installPath . '/index.php' . $params . '" 
                 frameborder="0" 
                 marginheight="0" 
                 marginwidth="0" 
                 width="100%" 
                 height="1000px"></iframe>'
            );
        } else {
            $block->setText($this->__('You are missing your Matomo Token Auth Key.'));
        }

        $this->_addContent($block);
        $this->_setActiveMenu('matomo_menu')->renderLayout();
    }
}
