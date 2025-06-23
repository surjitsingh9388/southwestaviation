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
use Cake\Routing\Router;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class AirframeComponentComponent extends Component {
    protected \App\Model\Table\AirframeComponentsTable $AirframeComponents;
    protected \App\Model\Table\AtaCodesTable $AtaCodes;
    protected \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    
    /**
     * GetAirframeComponents method
     * This function is used to get list of all components.
     *
     * @return array.
     */
    public function getAirComps() {
        $airCompModel = $this->getController()->fetchTable('AirframeComponents');
        $airComps = $airCompModel->find('all')->select(['id', 'log_book', 'position'])->enableHydration(false)->toArray();

        $results = array();
        foreach ($airComps as $key => $value) {
            if($value['log_book'] == 'Airframe') {
                $results[$value['id']] = $value['log_book'];
            } else {
                $results[$value['id']] = $value['log_book'].' '.$value['position'];
            }
        }
        return $results;
    }

    public function getRAirComps($id = null) {
        $airCompModel = $this->getController()->fetchTable('AirframeComponents');
        $airComps = $airCompModel->find('all')->where(['AirframeComponents.plane_id' => $id])->select(['id', 'log_book', 'position'])->enableHydration(false)->toArray();

        $results = array();
        foreach ($airComps as $key => $value) {
            if($value['log_book'] == 'Airframe') {
                $results[$value['id']] = $value['log_book'];
            } else {
                $results[$value['id']] = $value['log_book'].' '.$value['position'];
            }
        }
        return $results;
    }

    public function getCompDetail($id = null) {
        $airCompModel = $this->getController()->fetchTable('AirframeComponents');
        $airComp = $airCompModel->find('all')->where(['AirframeComponents.id' => $id])->select(['id', 'log_book', 'position'])->enableHydration(false)->first();

        if($airComp['log_book'] == 'Airframe') {
            $result = $airComp['log_book'];
        } else {
            $result = $airComp['log_book'].' '.$airComp['position'];
        }
        return $result;
    }

    /*public function getRAirComps($id = null) {
        $airCompModel = $this->getController()->fetchTable('AirframeComponents');
        $airComps = $airCompModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'log_book'.' '.'position'
        ))->where(['AirframeComponents.plane_id' => $id])->toArray();
        return $airComps;
    }*/

    public function getAtaCode($id = null) {
        $ataCodeModel = $this->getController()->fetchTable('AtaCodes');
        $ataCodes = $ataCodeModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'ata_code'
        ))->toArray();
        return $ataCodes;
    }

    public function getAirCompsDetail($id = null) {
        $airCompModel = $this->getController()->fetchTable('AirframeComponents');
        $airComps = $airCompModel->find('all')->where(['AirframeComponents.plane_id' => $id])->select(['id', 'log_book', 'position', 'serial_no'])->contain(['Planes'=>['fields'=>['Planes.id', 'Planes.plane_code']]])->enableHydration(false)->toArray();
        //pr($airComps);die;
        $results = array();
        foreach ($airComps as $key => $value) {
            if($value['log_book'] == 'Airframe') {
                $results[$value['id']] = $value['plane']['plane_code'].' - '.$value['log_book'].' SN: '.$value['serial_no'];
            } else {
                $results[$value['id']] = $value['plane']['plane_code'].' - '.$value['log_book'].' '.$value['position'].' SN: '.$value['serial_no'];
            }
        }
        return $results;
    }

    public function getPartsList($airId = null, $id = null) {
        $partModel = $this->getController()->fetchTable('AirframeComponentParts');
        $parts = $partModel->find()
                    ->where(['AirframeComponentParts.id !='=>$id, 'AirframeComponentParts.parent_id'=>0, 'AirframeComponentParts.plane_id'=>$airId])
                    ->select(['id', 'description'])
                    ->enableHydration(false)->toArray();
        $results = array();
        foreach ($parts as $key => $value) {
            $results[$value['id']] = substr($value['description'], 0, 30);
        }
        return $results;
    }    
}