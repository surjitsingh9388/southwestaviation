<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ImportAirframeComponentPartHistoriesFixture
 */
class ImportAirframeComponentPartHistoriesFixture extends TestFixture
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
                'table_name' => 'Lorem ipsum dolor sit amet',
                'row_id' => 1,
                'old_data' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'batch_id' => 'aa0eb36a-567e-431f-936f-d0bf5010e124',
                'created' => '2025-09-02 00:36:00',
            ],
        ];
        parent::init();
    }
}
