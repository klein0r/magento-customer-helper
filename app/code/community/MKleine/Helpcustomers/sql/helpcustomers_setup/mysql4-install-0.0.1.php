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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

    CREATE TABLE IF NOT EXISTS {$this->getTable('helpcustomers_faillog')} (
      `faillog_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `store_id` smallint(5) unsigned NOT NULL COMMENT 'Store Id',
      `customer_id` int(10) unsigned NOT NULL,
      `fail_count` int(3) unsigned NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Created At',
      `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Updated At',
      PRIMARY KEY (`faillog_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

/** @var $connection Varien_Db_Adapter_Pdo_Mysql */
$connection = $installer->getConnection();

$connection->addIndex(
    $this->getTable('helpcustomers_faillog'),
    $installer->getIdxName(
        $this->getTable('helpcustomers_faillog'),
        array('customer_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    array('customer_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();
