<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirframeRequirementTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirframeRequirementTypesTable Test Case
 */
class AirframeRequirementTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirframeRequirementTypesTable
     */
    protected $AirframeRequirementTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AirframeRequirementTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirframeRequirementTypes') ? [] : ['className' => AirframeRequirementTypesTable::class];
        $this->AirframeRequirementTypes = $this->getTableLocator()->get('AirframeRequirementTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirframeRequirementTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirframeRequirementTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
