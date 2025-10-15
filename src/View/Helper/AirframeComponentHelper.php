<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\Locator\LocatorAwareTrait;
use App\Model\Table\AirframeItemTypesTable;
use App\Model\Table\AirframeRequirementTypesTable;
use App\Model\Table\AirframeIssuingAuthoritiesTable;

class AirframeComponentHelper extends Helper
{
    use LocatorAwareTrait;
    protected AirframeItemTypesTable $AirframeItemTypes;
    protected AirframeRequirementTypesTable $AirframeRequirementTypes;
    protected AirframeIssuingAuthoritiesTable $AirframeIssuingAuthorities;

    public function getItemTypeId($sort_title)
    {
        $this->AirframeItemTypes = $this->getTableLocator()->get('AirframeItemTypes');
        $result = $this->AirframeItemTypes->find()
                                        ->where(['sort_title' => $sort_title])
                                        ->select(['id'])
                                        ->first();
        $item_type_id = $result ? $result->id : null;

        return $item_type_id;
    }

    public function getRequirementTypeId($title)
    {
        $this->AirframeRequirementTypes = $this->getTableLocator()->get('AirframeRequirementTypes');
        $result = $this->AirframeRequirementTypes->find()
                                            ->where(['title' => $title])
                                            ->select(['id'])
                                            ->first();

        $requirement_type_id = $result ? $result->id : null;

        return $requirement_type_id;
    }

    public function getIssuingAuthoritiesId($title)
    {
        $airframeIssuingAuthorities = $this->getTableLocator()->get('AirframeIssuingAuthorities');
        $result = $airframeIssuingAuthorities->find()
                                            ->where(['title' => $title])
                                            ->select(['id'])
                                            ->first();

        $issuing_authority_id = $result ? $result->id : null;

        return $issuing_authority_id;
    }

}
?>