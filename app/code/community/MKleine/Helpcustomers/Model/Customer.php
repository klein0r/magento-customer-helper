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
class MKleine_Helpcustomers_Model_Customer extends Mage_Customer_Model_Customer
{
    public function validatePassword($password)
    {
        $return = parent::validatePassword($password);

        $customerId = $this->getId();
        $storeId = $this->getStore()->getId();

        // Password incorrect / Login failed
        if (!$return && $customerId) {
            /** @var $model MKleine_Helpcustomers_Model_Faillog */
            $model = Mage::getModel('mk_helpcustomers/faillog');
            $model->loadFaillogByCustomerId($customerId);

            $failCount = $model->getFailCount() ? $model->getFailCount() : 0;

            if (!$model->getId()) {
                $model->setCustomerId($customerId);
            }

            $model->setStoreId($storeId);
            $model->setFailCount(++$failCount);
            $model->setUpdatedAt(Mage::getModel('core/date')->timestamp(time()));
            $model->save();

            // Send event
            Mage::dispatchEvent('mk_helpcustomers_login_failed', array(
                'customer' => $this,
                'fail_count' => $failCount,
                'password' => $password
            ));
        }

        return $return;
    }
}