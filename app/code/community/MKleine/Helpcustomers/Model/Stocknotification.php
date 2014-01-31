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
 * Class MKleine_Helpcustomers_Model_Stocknotification
 *
 * @method getProductId
 * @method setProductId
 * @method getStoreId
 * @method setStoreId
 * @method getCustomerId
 * @method setCustomerId
 */
class MKleine_Helpcustomers_Model_Stocknotification extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mk_helpcustomers/stocknotification');
    }

    public function prepare($productId)
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $storeId = Mage::app()->getStore()->getId();

        /** @var $collection MKleine_Helpcustomers_Model_Mysql4_Stocknotification_Collection */
        $collection = $this->getCollection()
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('store_id', $storeId)
            ->load();

        if ($collection->getSize() == 0)
        {
            $this->setProductId($productId);
            $this->setCustomerId($customerId);
            $this->setStoreId($storeId);
        }
        else {
            $this->load($collection->getFirstItem()->getId());
        }
    }
}