<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\Locator\LocatorAwareTrait;
use App\Model\Table\TechnicalPublicationsTable;
use App\Model\Table\TechnicalPublicationPermissionsTable;

class TechnicalPublicationHelper extends Helper
{
    use LocatorAwareTrait;
    protected TechnicalPublicationsTable $TechnicalPublications;
    protected TechnicalPublicationPermissionsTable $TechnicalPublicationPermissions;

    public function getFolderFilePermission($main_page_id, $subpage_id, $folder_name, $user_id)
    {
        $this->TechnicalPublications = $this->getTableLocator()->get('TechnicalPublications');
        $this->TechnicalPublicationPermissions = $this->getTableLocator()->get('TechnicalPublicationPermissions');

        $techpubldet = $this->TechnicalPublications->find()
                                                    ->where([
                                                        'TechnicalPublications.main_page_id' => $main_page_id,
                                                        'TechnicalPublications.subpage_id'   => $subpage_id,
                                                        'TechnicalPublications.folder_file_name' => $folder_name
                                                    ])
                                                    ->contain([
                                                        'TechnicalPublicationPermissions' => function ($q) use ($user_id) {
                                                            return $q->where(['user_id' => $user_id]);
                                                        }
                                                    ])
                                                    ->first();
        
        $has_subfolder_permission = 0;
        if ($this->TechnicalPublicationPermissions->hasAnyPermissionRecursive($user_id, $techpubldet->id)) {
            $has_subfolder_permission = 1;
        }

        $technical_publication_permissions = !empty($techpubldet['technical_publication_permissions']) ? $techpubldet['technical_publication_permissions'][0] : [];
        
        $returndata = [
                    'has_subfolder_permission'=>$has_subfolder_permission,
                    'technical_publication_permissions'=>$technical_publication_permissions
        ];

        return $returndata;
    }
}

?>