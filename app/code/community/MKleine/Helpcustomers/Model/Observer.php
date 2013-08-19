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
class MKleine_Helpcustomers_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Will be called when a customer is authenticated successfully
     * @param $observer
     */
    public function customer_customer_authenticated($observer)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $observer->getModel();

        if ($customerId = $customer->getId()) {
            /** @var $model MKleine_Helpcustomers_Model_Faillog */
            $model = Mage::getModel('mk_helpcustomers/faillog');
            $model->loadFaillogByCustomerId($customerId);

            if ($model->getId()) {
                $model->delete();
            }
        }
    }

    /**
     * Will be called by the cron job every 10 Minutes and sends a mail
     * to all customers which failed to login
     */
    public static function send_mail()
    {
        /** @var $mailModel MKleine_Helpcustomers_Model_Mailer */
        $mailModel = Mage::getSingleton('mk_helpcustomers/mailer');
        $mailModel->sendMails();
    }
}