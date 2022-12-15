<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubscriptionFeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubscriptionFeesTable Test Case
 */
class SubscriptionFeesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SubscriptionFeesTable
     */
    public $SubscriptionFees;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.subscription_fees',
        'app.subscriptions',
        'app.fee_types',
        'app.payments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SubscriptionFees') ? [] : ['className' => SubscriptionFeesTable::class];
        $this->SubscriptionFees = TableRegistry::getTableLocator()->get('SubscriptionFees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SubscriptionFees);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
