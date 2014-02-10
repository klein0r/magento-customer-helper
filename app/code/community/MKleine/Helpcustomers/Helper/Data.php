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
class MKleine_Helpcustomers_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_LOGON_FAIL_ACTIVE = 'customer/helpcustomers/logon_fail_active';
    const XML_PATH_LOGON_FAIL_EMAIL_TEMPLATE = 'customer/helpcustomers/logon_fail_email_template';

    const XML_PATH_STOCKNOTIFICATION_ACTIVE = 'customer/helpcustomers/stocknotification_active';
    const XML_PATH_STOCKNOTIFICATION_EMAIL_TEMPLATE = 'customer/helpcustomers/stocknotification_email_template';

    public function logonFailActive($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_LOGON_FAIL_ACTIVE, $store);
    }

    public function stockNotificationActive($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_STOCKNOTIFICATION_ACTIVE, $store);
    }
}