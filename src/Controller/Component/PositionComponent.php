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


class PositionComponent extends Component {
    protected \App\Model\Table\PositionsTable $Positions;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * getPositions method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getPositions($id = null) {
        $positionModel = $this->getController()->fetchTable('Positions');
        $positionRes = $positionModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->toArray();
        return $positionRes;
    }

    //Get position title
    public function positionTitle($position_Id)
    {
        $positionModel = $this->getController()->fetchTable('Positions');
        $res = '';
        if(!empty($position_Id)) {
            $result = $positionModel->find()->where(['Positions.id'=>$position_Id])->select(['Positions.id', 'Positions.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }

    public function getPositionId($title)
    {
        $positionModel = $this->getController()->fetchTable('Positions');
        $res = '';
        if(!empty($title)) {
            $result = $positionModel->find()->where(['LOWER(Positions.title)'=>strtolower($title)])->select(['Positions.id'])->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}