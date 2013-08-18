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
    const XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE = 'customer/password/logon_fail_email_template';

    public function customer_customer_authenticated($observer)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $observer->getModel();

        if ($customerId = $customer->getId()) {
            /** @var $model MKleine_Helpcustomers_Model_Maillog */
            $model = Mage::getModel('mk_helpcustomers/maillog');
            $model->loadMaillogByCustomerId($customerId);

            if ($model->getId()) {
                $model->delete();
            }
        }
    }

    public static function send_mail()
    {
        $mailTemplateId = Mage::getStoreConfig(self::XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE);
        if ($mailTemplateId) {
            $timeGap = Mage::getModel('core/date')->Date(null, time() - 30 * 60);

            /** @var $collection MKleine_Helpcustomers_Model_Mysql4_Maillog_Collection */
            $collection = Mage::getModel('mk_helpcustomers/maillog')->getCollection();
            $collection->addFieldToFilter('updated_at', array( 'lt' => $timeGap ) );
            $collection->load();

            /** @var $failItem MKleine_Helpcustomers_Model_Maillog */
            foreach ($collection as $failItem) {
                /** @var $customer MKleine_Helpcustomers_Model_Customer */
                $customer = Mage::getModel('customer/customer')->load($failItem->getCustomerId());

                if ($customer->getId()) {
                    /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
                    $mailer = Mage::getModel('core/email_template_mailer');
                    $emailInfo = Mage::getModel('core/email_info');

                    $emailInfo->addTo($customer->getEmail(), $customer->getName());

                    $mailer->addEmailInfo($emailInfo);

                    // Set all required params and send emails
                    $mailer->setSender(Mage::getStoreConfig(Mage_Admin_Model_User::XML_PATH_FORGOT_EMAIL_IDENTITY));
                    $mailer->setStoreId($failItem->getStoreId());
                    $mailer->setTemplateId($mailTemplateId);

                    $mailer->setTemplateParams(array(
                        'customer' => $customer,
                        'failcount' => $failItem->getFailCount()
                    ));

                    // Send the mail
                    $mailer->send();

                    $failItem->delete();

                    Mage::dispatchEvent('mk_helpcustomers_fail_login_mail_sent', array(
                        'failcount' => $failItem->getFailCount(),
                        'customer' => $customer,
                        'mailer' => $mailer
                    ));
                }
            }
        }
    }
}