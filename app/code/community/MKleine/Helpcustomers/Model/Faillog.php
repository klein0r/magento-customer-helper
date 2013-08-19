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
class MKleine_Helpcustomers_Model_Faillog extends Mage_Customer_Model_Customer
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mk_helpcustomers/faillog');
    }

    /**
     * Loads a faillog by a specific variable
     * @param $customerId Id of customer
     * @return $this MKleine_Helpcustomers_Model_Faillog
     */
    public function loadFaillogByCustomerId($customerId)
    {
        $this->load($customerId, 'customer_id');
        return $this;
    }

    public function delete() {
        Mage::register('isSecureArea', true);
        $ret = parent::delete();
        Mage::unregister('isSecureArea');

        return $ret;
    }
}