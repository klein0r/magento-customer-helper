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

$table = $installer->getConnection()
    ->newTable($installer->getTable('helpcustomers_stocknotification'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
            'nullable'  => false,
        ), 'store_id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
            'nullable'  => false,
        ), 'customer_id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
            'nullable'  => false,
        ), 'product_id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable'  => false,
        ), 'created_at')

    ->addForeignKey($installer->getFkName('helpcustomers_stocknotification', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->addForeignKey($installer->getFkName('helpcustomers_stocknotification', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->addForeignKey($installer->getFkName('helpcustomers_stocknotification', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $installer->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->setComment('Customer Notifications');

$installer->getConnection()->createTable($table);
