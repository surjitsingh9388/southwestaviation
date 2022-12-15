<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MembershipsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MembershipsTable Test Case
 */
class MembershipsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MembershipsTable
     */
    public $Memberships;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.memberships',
        'app.membership_types',
        'app.tier_levels',
        'app.currencies',
        'app.membership_fees',
        'app.subscriptions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Memberships') ? [] : ['className' => MembershipsTable::class];
        $this->Memberships = TableRegistry::getTableLocator()->get('Memberships', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Memberships);

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
