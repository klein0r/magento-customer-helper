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
class MKleine_Helpcustomers_Test_Model_Mailer extends EcomDev_PHPUnit_Test_Case
{
    protected function setUp()
    {
        parent::setUp();


    }

    public function testSendMails()
    {
        /** @var $mailModel MKleine_Helpcustomers_Model_Mailer */
        $mailModel = Mage::getSingleton('mk_helpcustomers/mailer');
        $mailModel->sendMails();

        //$this->assertTrue(true);
    }

    protected function tearDown()
    {
        parent::tearDown();


    }
}