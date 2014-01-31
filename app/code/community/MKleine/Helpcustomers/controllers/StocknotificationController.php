<?php

class MKleine_Helpcustomers_StocknotificationController extends Mage_Core_Controller_Front_Action
{
    public function addAction()
    {
        $this->loadLayout();

        $productId = $this->getRequest()->getParam('id', false);
        if ($productId) {
            /** @var $notModel MKleine_Helpcustomers_Model_Stocknotification */
            $notModel = Mage::getModel('mk_helpcustomers/stocknotification');
            $notModel->prepare($productId);
            $notModel->save();
        }

        $this->getLayout()->getBlock('mkleine.helpcustomers.stocknotification.add')
            ->setProductId($productId);

        $this->renderLayout();
    }

    public function removeAction()
    {
        $this->loadLayout();

        $productId = $this->getRequest()->getParam('id', false);
        if ($productId) {
            /** @var $notModel MKleine_Helpcustomers_Model_Stocknotification */
            $notModel = Mage::getModel('mk_helpcustomers/stocknotification');
            $notModel->prepare($productId);
            if ($notModel->getId()) {
                // Delete entry
                $notModel->delete();
            }
        }

        $this->getLayout()->getBlock('mkleine.helpcustomers.stocknotification.remove')
            ->setProductId($productId);

        $this->renderLayout();
    }
}