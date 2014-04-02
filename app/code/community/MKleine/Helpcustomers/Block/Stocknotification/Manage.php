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
 * Class MKleine_Helpcustomers_Block_Stocknotification_Manage
 *
 * @method getNotifications
 * @method setNotifications
 */
class MKleine_Helpcustomers_Block_Stocknotification_Manage extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mkleine/helpcustomers/stocknotification/manage.phtml');

        $orders = Mage::getResourceModel('mk_helpcustomers/stocknotification_collection')
            ->addFieldToSelect('*')
            ->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
            ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
            ->setOrder('created_at', 'desc')
        ;

        $this->setNotifications($orders);

        Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('root')->setHeaderTitle(Mage::helper('mk_helpcustomers')->__('My stock notifications'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'helpcustomers.stocknotification.customer.pager')
            ->setCollection($this->getNotifications());

        $this->setChild('pager', $pager);
        $this->getNotifications()->load();

        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param $notification MKleine_Helpcustomers_Model_Stocknotification
     */
    public function getRemoveUrl($notification)
    {
        return $this->getUrl('helpcustomers/stocknotification/remove', array( 'id' => $notification->getProductId() ));
    }
}