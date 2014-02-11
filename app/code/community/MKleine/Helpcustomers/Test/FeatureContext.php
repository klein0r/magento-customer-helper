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
class MKleine_Helpcustomers_Test_FeatureContext extends \Behat\MinkExtension\Context\RawMinkContext
{
    const EXCEPTION_NO_TABLE_ROW_FOUND = 1000;
    const EXCEPTION_CUSTOMER_NOT_CREATED = 1001;

    const CUSTOMER_DEFAULT_STORE_ID = 1;
    const CUSTOMER_DEFAULT_WEBSITE_ID = 1;

    /**
     * @Given /^a customer with mail "([^"]*)" exists$/
     */
    public function aCustomerWithMailExists($email)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer');
        $customer->setWebsiteId(self::CUSTOMER_DEFAULT_WEBSITE_ID);
        $customer->loadByEmail($email);

        if (!$customer->getId()) {
            // Create a test customer

            $customerData = array(
                'email' => $email,
                'firstname' => 'Just',
                'lastname' => 'Testing'
            );

            // Set a random password for new customers
            $customer->setPassword(md5(rand() . time()));
            $customer->setStore(Mage::app()->getStore(self::CUSTOMER_DEFAULT_STORE_ID));

            $customer->addData($customerData);

            $customer->save();
            $customer->setConfirmation(null);
            $customer->save();

            if (!$customer->getId()) {
                throw Mage::exception('MKleine_Helpcustomers', Mage::helper('mk_helpcustomers')->__('Unable to create test customer'),
                    self::EXCEPTION_CUSTOMER_NOT_CREATED);
            }
        }
    }

    /**
     * @Given /^the table "([^"]*)" is empty$/
     */
    public function theTableIsEmpty($table)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $tableName = $write->getTableName($table);

        $write->query("TRUNCATE TABLE `{$tableName}`");
    }

    /**
     * @When /^a customer tries to login with email "([^"]*)" and password "([^"]*)"$/
     */
    public function aCustomerTriesToLoginWithEmailAndPassword($email, $password)
    {
        try {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = Mage::getModel('customer/customer');
            $customer->setWebsiteId(self::CUSTOMER_DEFAULT_WEBSITE_ID);
            $customer->authenticate($email, $password);
        } catch (Mage_Core_Exception $e) {
            if ($e->getCode() != Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD) {
                // Throw other exceptions than invalid password
                throw $e;
            }
        }
    }

    /**
     * @Then /^an entry must exist in table "([^"]*)"$/
     */
    public function anEntryMustExistInTable($table)
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $tableName = $read->getTableName($table);

        $result = $read->fetchAll("SELECT COUNT(*) as nmbrRows FROM `{$tableName}`");
        if ($result[0]['nmbrRows'] == 0) {
            throw Mage::exception('MKleine_Helpcustomers', Mage::helper('mk_helpcustomers')->__('No rows in table'),
                self::EXCEPTION_NO_TABLE_ROW_FOUND);
        }
    }
}