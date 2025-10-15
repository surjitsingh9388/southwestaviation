<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AirframeIssuingAuthoritiesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AirframeIssuingAuthoritiesTable Test Case
 */
class AirframeIssuingAuthoritiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AirframeIssuingAuthoritiesTable
     */
    protected $AirframeIssuingAuthorities;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.AirframeIssuingAuthorities',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('AirframeIssuingAuthorities') ? [] : ['className' => AirframeIssuingAuthoritiesTable::class];
        $this->AirframeIssuingAuthorities = $this->getTableLocator()->get('AirframeIssuingAuthorities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->AirframeIssuingAuthorities);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AirframeIssuingAuthoritiesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
