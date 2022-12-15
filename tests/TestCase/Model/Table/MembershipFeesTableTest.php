<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembershipFeesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembershipFeesTable Test Case
 */
class MembershipFeesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MembershipFeesTable
     */
    public $MembershipFees;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.membership_fees',
        'app.memberships',
        'app.fee_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MembershipFees') ? [] : ['className' => MembershipFeesTable::class];
        $this->MembershipFees = TableRegistry::getTableLocator()->get('MembershipFees', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MembershipFees);

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
