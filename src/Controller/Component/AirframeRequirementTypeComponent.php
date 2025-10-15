<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\FactoryLocator;


class AirframeRequirementTypeComponent extends Component {
    protected \App\Model\Table\AirframeRequirementTypesTable $AirframeRequirementTypes;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    //Get moc id
    public function getRequirementTypeId($title)
    {
        $airframeRequirementTypesModel = $this->getController()->fetchTable('AirframeRequirementTypes');
        $res = '';
        if(!empty($title)) {
            $result = $airframeRequirementTypesModel->find()->where(['LOWER(AirframeRequirementTypes.title)'=>strtolower($title)])->select('AirframeRequirementTypes.id')->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}