<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TechnicalPublicationPermissionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TechnicalPublicationPermissionsTable Test Case
 */
class TechnicalPublicationPermissionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TechnicalPublicationPermissionsTable
     */
    protected $TechnicalPublicationPermissions;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.TechnicalPublicationPermissions',
        'app.Users',
        'app.Roles',
        'app.TechnicalPublications',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TechnicalPublicationPermissions') ? [] : ['className' => TechnicalPublicationPermissionsTable::class];
        $this->TechnicalPublicationPermissions = $this->getTableLocator()->get('TechnicalPublicationPermissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TechnicalPublicationPermissions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TechnicalPublicationPermissionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TechnicalPublicationPermissionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
