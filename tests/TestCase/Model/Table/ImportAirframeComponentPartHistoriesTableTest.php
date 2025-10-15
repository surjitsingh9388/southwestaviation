<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ImportAirframeComponentPartHistoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ImportAirframeComponentPartHistoriesTable Test Case
 */
class ImportAirframeComponentPartHistoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ImportAirframeComponentPartHistoriesTable
     */
    protected $ImportAirframeComponentPartHistories;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.ImportAirframeComponentPartHistories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ImportAirframeComponentPartHistories') ? [] : ['className' => ImportAirframeComponentPartHistoriesTable::class];
        $this->ImportAirframeComponentPartHistories = $this->getTableLocator()->get('ImportAirframeComponentPartHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ImportAirframeComponentPartHistories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ImportAirframeComponentPartHistoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
