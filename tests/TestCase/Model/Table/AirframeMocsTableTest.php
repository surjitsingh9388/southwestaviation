<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirframeMocsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirframeMocsTable Test Case
 */
class AirframeMocsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirframeMocsTable
     */
    protected $AirframeMocs;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AirframeMocs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirframeMocs') ? [] : ['className' => AirframeMocsTable::class];
        $this->AirframeMocs = $this->getTableLocator()->get('AirframeMocs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirframeMocs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirframeMocsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
