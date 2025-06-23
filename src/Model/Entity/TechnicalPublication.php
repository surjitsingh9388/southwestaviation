<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class TechnicalPublication extends Entity
{ 
    protected array $_accessible = [
        'main_page_id' => true,
        'subpage_id'=>true,
        'parent_id'=>true,
        'is_folder' => true,
        'folder_file_name' => true,
        'permission_user_ids'=>true,
        'added_by' => true,
        'updated_by' => true,
        'created_at' => true,
        'updated_at' => true
    ];
}
