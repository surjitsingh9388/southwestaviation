<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirframeItemTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirframeItemTypesTable Test Case
 */
class AirframeItemTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirframeItemTypesTable
     */
    protected $AirframeItemTypes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AirframeItemTypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirframeItemTypes') ? [] : ['className' => AirframeItemTypesTable::class];
        $this->AirframeItemTypes = $this->getTableLocator()->get('AirframeItemTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirframeItemTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirframeItemTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
