<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirframeRequirementSourcesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirframeRequirementSourcesTable Test Case
 */
class AirframeRequirementSourcesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirframeRequirementSourcesTable
     */
    protected $AirframeRequirementSources;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AirframeRequirementSources',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirframeRequirementSources') ? [] : ['className' => AirframeRequirementSourcesTable::class];
        $this->AirframeRequirementSources = $this->getTableLocator()->get('AirframeRequirementSources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirframeRequirementSources);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirframeRequirementSourcesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
