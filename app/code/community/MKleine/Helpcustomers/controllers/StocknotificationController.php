<?php

class MKleine_Helpcustomers_StocknotificationController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_forward('add');
    }

    public function addAction()
    {
        /** @var $helper MKleine_Helpcustomers_Helper_Data */
        $helper = Mage::helper('mk_helpcustomers');

        $productId = $this->getRequest()->getParam('id', false);
        if ($helper->stockNotificationActive() && $productId) {
            /** @var $notModel MKleine_Helpcustomers_Model_Stocknotification */
            $notModel = Mage::getModel('mk_helpcustomers/stocknotification');
            $notModel->prepare($productId);
            $notModel->save();
        }
        else {
            // Send 404 when extension not active or parameter missing
            $this->_forward('noRoute');
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('mkleine.helpcustomers.stocknotification.add')
            ->setProductId($productId);

        $this->renderLayout();
    }

    public function removeAction()
    {
        /** @var $helper MKleine_Helpcustomers_Helper_Data */
        $helper = Mage::helper('mk_helpcustomers');

        $productId = $this->getRequest()->getParam('id', false);
        if ($helper->stockNotificationActive() && $productId) {
            /** @var $notModel MKleine_Helpcustomers_Model_Stocknotification */
            $notModel = Mage::getModel('mk_helpcustomers/stocknotification');
            $notModel->prepare($productId);
            if ($notModel->getId()) {
                // Delete entry
                $notModel->delete();
            }
        }
        else {
            // Send 404 when extension not active or parameter missing
            $this->_forward('noRoute');
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('mkleine.helpcustomers.stocknotification.remove')
            ->setProductId($productId);

        $this->renderLayout();
    }
}