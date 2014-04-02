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
 * Class MKleine_Helpcustomers_Model_Mailer
 *
 */
class MKleine_Helpcustomers_Model_Mailer extends Mage_Core_Model_Abstract
{
    const LOGON_FAIL_TIME_GAP = 600;

    /**
     * @param $store int StoreId
     * @param $template int TemplateId
     * @param $email string Receiver Mail address
     * @param $name string Name of receiver
     * @param array $templateVars list of template vars
     * @return Mage_Core_Model_Email_Template_Mailer
     */
    protected function sendMail($store, $template, $email, $name, $templateVars = array())
    {
        /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');

        $emailInfo->addTo($email, $name);
        $mailer->addEmailInfo($emailInfo);

        // Set all required params and send emails
        $mailer->setSender(Mage::getStoreConfig(Mage_Admin_Model_User::XML_PATH_FORGOT_EMAIL_IDENTITY, $store));
        $mailer->setStoreId($store);
        $mailer->setTemplateId($template);

        // Set all template vars
        $mailer->setTemplateParams($templateVars);

        // Send the mail
        $mailer->send();

        return $mailer;
    }

    /**
     * Sends all fail log entries for the last x Minutes to all customers
     * which failed to logon
     *
     * @return $this
     */
    public function sendFaillogMails()
    {
        /** @var $helper MKleine_Helpcustomers_Helper_Data */
        $helper = Mage::helper('mk_helpcustomers');

        $timeGap = Mage::getModel('core/date')->Date(null, time() - self::LOGON_FAIL_TIME_GAP);

        /** @var $collection MKleine_Helpcustomers_Model_Mysql4_Faillog_Collection */
        $collection = Mage::getModel('mk_helpcustomers/faillog')->getCollection();
        $collection->addFieldToFilter('updated_at', array('lt' => $timeGap));
        $collection->load();

        /** @var $failItem MKleine_Helpcustomers_Model_Faillog */
        foreach ($collection as $failItem) {
            $mailTemplateId = Mage::getStoreConfig(MKleine_Helpcustomers_Helper_Data::XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE, $failItem->getStoreId());

            // Check if module is active for given store
            if ($mailTemplateId && $helper->logonFailActive($failItem->getStoreId())) {

                /** @var $customer MKleine_Helpcustomers_Model_Customer */
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore($failItem->getStoreId())->getWebsiteId())
                    ->load($failItem->getCustomerId());

                if ($customer->getId()) {

                    $this->sendMail($failItem->getStoreId(), $mailTemplateId, $customer->getEmail(), $customer->getName(), array(
                        'customer' => $customer,
                        'failcount' => $failItem->getFailCount()
                    ));

                    $failItem->delete();

                    Mage::dispatchEvent('mk_helpcustomers_logon_fail_mail_sent', array(
                        'customer' => $customer,
                        'fail_count' => $failItem->getFailCount()
                    ));
                }

            }
        }

        return $this;
    }

    /**
     * Sends notification message about product status to registered
     * customers
     *
     * @param $productId
     * @param $qty
     * @return $this
     */
    public function sendStocknotificationMails($productId, $qty)
    {
        /** @var $helper MKleine_Helpcustomers_Helper_Data */
        $helper = Mage::helper('mk_helpcustomers');

        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')->load($productId);

        /** @var $collection MKleine_Helpcustomers_Model_Mysql4_Stocknotification_Collection */
        $collection = Mage::getModel('mk_helpcustomers/stocknotification')
            ->getCollection()
            ->addFieldToFilter('product_id', $productId);

        /** @var $notification MKleine_Helpcustomers_Model_Stocknotification */
        foreach ($collection as $notification) {
            $mailTemplateId = Mage::getStoreConfig(MKleine_Helpcustomers_Helper_Data::XML_PATH_STOCKNOTIFICATION_EMAIL_TEMPLATE, $notification->getStoreId());

            // Check if module is active for given store
            if ($mailTemplateId && $helper->stockNotificationActive($notification->getStoreId())) {

                /** @var $customer MKleine_Helpcustomers_Model_Customer */
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore($notification->getStoreId())->getWebsiteId())
                    ->load($notification->getCustomerId());

                if ($customer->getId()) {

                    $this->sendMail($notification->getStoreId(), $mailTemplateId, $customer->getEmail(), $customer->getName(), array(
                        'customer' => $customer,
                        'product' => $product,
                        'qty' => $qty
                    ));

                    $notification->delete();

                    Mage::dispatchEvent('mk_helpcustomers_stocknotification_mail_sent', array(
                        'customer' => $customer,
                        'product' => $product
                    ));
                }

            }
        }

        return $this;
    }
}