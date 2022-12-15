<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompanionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompanionsTable Test Case
 */
class CompanionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CompanionsTable
     */
    public $Companions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.companions',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Companions') ? [] : ['className' => CompanionsTable::class];
        $this->Companions = TableRegistry::get('Companions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Companions);

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
