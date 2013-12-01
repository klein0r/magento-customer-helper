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
class MKleine_Helpcustomers_Model_Mailer extends Mage_Core_Model_Abstract
{
    const XML_PATH_EXTENSION_ACTIVE = 'customer/helpcustomers/active';
    const XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE = 'customer/helpcustomers/logon_fail_email_template';

    public function sendMails()
    {
        $timeGap = Mage::getModel('core/date')->Date(null, time() - 10 * 60);

        /** @var $collection MKleine_Helpcustomers_Model_Mysql4_Faillog_Collection */
        $collection = Mage::getModel('mk_helpcustomers/faillog')->getCollection();
        $collection->addFieldToFilter('updated_at', array('lt' => $timeGap));
        $collection->load();

        /** @var $failItem MKleine_Helpcustomers_Model_Faillog */
        foreach ($collection as $failItem) {
            $mailTemplateId = Mage::getStoreConfig(self::XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE, $failItem->getStoreId());

            // Check if module is active for given store
            if ($mailTemplateId && Mage::getStoreConfig(self::XML_PATH_EXTENSION_ACTIVE, $failItem->getStoreId())) {

                /** @var $customer MKleine_Helpcustomers_Model_Customer */
                $customer = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore($failItem->getStoreId())->getWebsiteId())
                    ->load($failItem->getCustomerId());

                if ($customer->getId()) {
                    /** @var $mailer Mage_Core_Model_Email_Template_Mailer */
                    $mailer = Mage::getModel('core/email_template_mailer');
                    $emailInfo = Mage::getModel('core/email_info');

                    $emailInfo->addTo($customer->getEmail(), $customer->getName());

                    $mailer->addEmailInfo($emailInfo);

                    // Set all required params and send emails
                    $mailer->setSender(Mage::getStoreConfig(Mage_Admin_Model_User::XML_PATH_FORGOT_EMAIL_IDENTITY, $failItem->getStoreId()));
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
                        'customer' => $customer,
                        'fail_count' => $failItem->getFailCount(),
                        'mailer' => $mailer
                    ));
                }

            }
        }

        return $this;
    }
}