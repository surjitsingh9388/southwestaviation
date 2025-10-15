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


class AirframeRequirementSourceComponent extends Component {
    protected \App\Model\Table\AirframeRequirementSourcesTable $AirframeRequirementSources;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * Get Requirement Sources method
     * This function is used to get list of all requirement source.
     *
     * @return array.
     */
    public function getRequirementSources($id = null) {
        $reqSourceModel = $this->getController()->fetchTable('AirframeRequirementSources');
        $reqSourceRes = $reqSourceModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->toArray();
        return $reqSourceRes;
    }

    //Get disposition title
    public function getRequirementSourceTitle($reqSourceId)
    {
        $reqSourceModel = $this->getController()->fetchTable('AirframeRequirementSources');
        $res = '';
        if(!empty($reqSourceId)) {
            $result = $reqSourceModel->find()->where(['AirframeRequirementSources.id'=>$reqSourceId])->select(['AirframeRequirementSources.id', 'AirframeRequirementSources.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }

    public function getRequirementSourceId($title)
    {
        $reqSourceModel = $this->getController()->fetchTable('AirframeRequirementSources');
        $res = '';
        if(!empty($title)) {
            $result = $reqSourceModel->find()->where(['LOWER(AirframeRequirementSources.title)'=>strtolower($title)])->select('AirframeRequirementSources.id')->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}