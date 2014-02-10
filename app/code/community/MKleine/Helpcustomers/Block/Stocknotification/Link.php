<?php
/**
 * MKleine - (c) Matthias Kleine
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@mkleine.de so we can send you a copy immediately.
 *
 * @category    MKleine
 * @package     MKleine_Helpcustomers
 * @copyright   Copyright (c) 2013 Matthias Kleine (http://mkleine.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class MKleine_Helpcustomers_Block_Stocknotification_Link
 *
 */
class MKleine_Helpcustomers_Block_Stocknotification_Link extends Mage_Core_Block_Template
{
    /**
     * Returns the current product of the registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Checks if the customers is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    /**
     * Checks if the stock notification extension is active for the current store
     * @return bool
     */
    public function extensionActive()
    {
        /** @var $helper MKleine_Helpcustomers_Helper_Data */
        $helper = Mage::helper('mk_helpcustomers');
        return ($helper->stockNotificationActive() == 1);
    }

    /**
     * Checks, if a notification for the current product is already registered
     *
     * @return bool
     */
    public function notificationExists()
    {
        /** @var $notModel MKleine_Helpcustomers_Model_Stocknotification */
        $notModel = Mage::getModel('mk_helpcustomers/stocknotification');
        $notModel->prepare($this->getProduct()->getId());
        return ($notModel->getId() > 0);
    }
}