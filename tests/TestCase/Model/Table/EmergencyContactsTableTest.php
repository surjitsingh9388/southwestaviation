<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EmergencyContactsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EmergencyContactsTable Test Case
 */
class EmergencyContactsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EmergencyContactsTable
     */
    public $EmergencyContacts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.emergency_contacts',
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
        $config = TableRegistry::exists('EmergencyContacts') ? [] : ['className' => EmergencyContactsTable::class];
        $this->EmergencyContacts = TableRegistry::get('EmergencyContacts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EmergencyContacts);

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
