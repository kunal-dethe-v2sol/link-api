<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OtpRequestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OtpRequestsTable Test Case
 */
class OtpRequestsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OtpRequestsTable
     */
    public $OtpRequests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.otp_requests'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('OtpRequests') ? [] : ['className' => OtpRequestsTable::class];
        $this->OtpRequests = TableRegistry::get('OtpRequests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OtpRequests);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
