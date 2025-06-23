<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\DateTime;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\FactoryLocator;

class PlaneComponent extends Component {
    public array $components = ['Authentication.Authentication'];

    protected \App\Model\Table\PlanesTable $Planes;
    protected \App\Model\Table\UserAircraftsTable $UserAircrafts;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * GetPlanes method
     * This function is used to get list of all planes.
     *
     * @return array.
     */
    public function getPlanes($ids=null) {
        $cond = '';
        if(!empty($ids)) {
            $cond = ['Planes.id IN'=>$ids];
        }
        $planeModel = $this->getController()->fetchTable('Planes');
        $planes = $planeModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => function ($e) {
                            return $e->plane_code . ' (' . $e->plane_type . ')';
                        }
        ))->where($cond)->toArray();
        return $planes;
    }

    public function getPlaneName($id=null) {
        $planeModel = $this->getController()->fetchTable('Planes');
        return $planeModel->get($id)->plane_code;
    }

    public function orderType($id=null) {
        $planeModel = $this->getController()->fetchTable('Planes');
        return $planeModel->get($id)->order_type;
    }

    public function getSelectedAircraft()
    {
        $result = [];
        $airIdsArr = [];
        $airIds = "1=1";
        $authUserData = $this->Authentication->getResult()->getData();
        $userID = $authUserData['id'];
        $userAirModel = $this->getController()->fetchTable('UserAircrafts');
        $res = $userAirModel->find('all')->where(['UserAircrafts.user_id'=>$userID])->enableHydration(false)->first();
        if(!empty($res)) {
            if($authUserData['id'] != 1 && !empty($res['aircraft_ids'])) {
                $airIdsArr = unserialize($res['aircraft_ids']);
                if(is_array($airIdsArr)) {
                    $arrayIds = implode("','",$airIdsArr);
                    $airIds = "Planes.id IN ('".$arrayIds."')";
                } else {
                    $airIdsArr = [];
                    $airIds = "Planes.id IN ('')";
                }
            }
        }

        $result['airIds'] = $airIds;
        $result['airIdsArr'] = $airIdsArr;
        return $result;
    }

    public function getAllPlanes()
    {
        //Display assigned aircraft only
        $airIds = $this->getSelectedAircraft();
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            if(!empty($airIds['airIdsArr']) && is_array($airIds['airIdsArr'])) {
                $planes = $this->getPlanes($airIds['airIdsArr']);
            } else {
                $planes = [];
            }
        } else {
            //Get all aircraft
            $planes = $this->getPlanes();
        }

        return $planes;
    }

    public function planesWithCode($ids=null) {
        $cond = '';
        if(!empty($ids)) {
            $cond = ['Planes.id IN'=>$ids];
        }
        $planeModel = $this->getController()->fetchTable('Planes');
        $planes = $planeModel->find('list', array (
            'keyField' => function ($e) {
                            return $e->plane_code;
                        }, 
            'valueField' => function ($e) {
                            return $e->plane_code . ' (' . $e->plane_type . ')';
                        }
        ))->where($cond)->toArray();
        return $planes;
    }

    //Planes with plane code
    public function getAllPlanesWithCode()
    {
        //Display assigned aircraft only
        $airIds = $this->getSelectedAircraft();
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            if(!empty($airIds['airIdsArr']) && is_array($airIds['airIdsArr'])) {
                $planes = $this->planesWithCode($airIds['airIdsArr']);
            } else {
                $planes = [];
            }
        } else {
            //Get all aircraft
            $planes = $this->planesWithCode();
        }

        return $planes;
    }
    
}