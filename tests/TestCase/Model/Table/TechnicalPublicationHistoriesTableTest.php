<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TechnicalPublicationHistoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TechnicalPublicationHistoriesTable Test Case
 */
class TechnicalPublicationHistoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TechnicalPublicationHistoriesTable
     */
    protected $TechnicalPublicationHistories;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.TechnicalPublicationHistories',
        'app.TechnicalPublications',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TechnicalPublicationHistories') ? [] : ['className' => TechnicalPublicationHistoriesTable::class];
        $this->TechnicalPublicationHistories = $this->getTableLocator()->get('TechnicalPublicationHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TechnicalPublicationHistories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TechnicalPublicationHistoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\TechnicalPublicationHistoriesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
