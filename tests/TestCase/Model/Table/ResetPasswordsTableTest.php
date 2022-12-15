<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResetPasswordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResetPasswordsTable Test Case
 */
class ResetPasswordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ResetPasswordsTable
     */
    public $ResetPasswords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.reset_passwords',
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
        $config = TableRegistry::exists('ResetPasswords') ? [] : ['className' => ResetPasswordsTable::class];
        $this->ResetPasswords = TableRegistry::get('ResetPasswords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResetPasswords);

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
