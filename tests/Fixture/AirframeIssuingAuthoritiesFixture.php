<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AirframeIssuingAuthoritiesFixture
 */
class AirframeIssuingAuthoritiesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'title' => 'Lorem ipsum dolor sit amet',
                'status' => 'Lorem ipsum dolor sit amet',
                'created' => '2025-07-20 23:08:49',
                'modified' => '2025-07-20 23:08:49',
            ],
        ];
        parent::init();
    }
}
