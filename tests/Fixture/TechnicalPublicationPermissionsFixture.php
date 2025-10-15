<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TechnicalPublicationPermissionsFixture
 */
class TechnicalPublicationPermissionsFixture extends TestFixture
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
                'user_id' => 1,
                'role_id' => 1,
                'technical_publication_id' => 1,
                'parent_id' => 1,
                'action_add' => 1,
                'action_edit' => 1,
                'action_view' => 1,
                'action_delete' => 1,
                'updated_by' => 1,
                'created' => 1755750277,
                'modified' => '2025-08-20 23:24:37',
                'deleted' => '2025-08-20 23:24:37',
            ],
        ];
        parent::init();
    }
}
