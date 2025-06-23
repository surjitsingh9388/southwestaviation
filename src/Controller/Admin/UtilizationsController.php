<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Database\Expression\QueryExpression;
use Cake\Core\Configure;
use Dompdf\Dompdf;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Utilizations Controller
 */
class UtilizationsController extends AppController
{
    /*private $planeObj;
    private $airCompObj;
    private $airCompPartObj;
    private $ataCodeObj;
    private $addDiscObj;*/

    protected \App\Model\Table\PlanesTable $planeObj;
    protected \App\Model\Table\AirframeComponentsTable $airCompObj;
    protected \App\Model\Table\AirframeComponentPartsTable $airCompPartObj;
    protected \App\Model\Table\AtaCodesTable $ataCodeObj;
    // protected \App\Model\Table\AddDiscrepanciesTable $addDiscObj; // Uncomment if needed

    public array $paginate = array(
        'limit' => 10
    );
    
    public function initialize():void 
    {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeCategory');
        $this->loadComponent('AtaCode');

        $this->planeObj = $this->fetchTable('Planes');
        $this->airCompObj = $this->fetchTable('AirframeComponents');
        $this->airCompPartObj = $this->fetchTable('AirframeComponentParts');
        $this->ataCodeObj = $this->fetchTable('AtaCodes');
        //$this->addDiscObj = $this->fetchTable('AddDiscrepancies');
    }
    
    public function index()
    {
    }

    //Aircraft Utilization
    public function aircraftUtilization() 
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Utilization', $actionStatus))
            {
                $actionItems = $actionStatus['Utilization'];
            }
        }

        $params = $_GET;
        $planeId = $params['plane_id'];
        $results = $this->planeObj->find() 
                        ->where(['Planes.id'=>$planeId])
                        ->contain([
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.plane_id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'Utilizations'
                            ]
                        ])
                        ->select(['Planes.id', 'Planes.plane_code'])
                        ->enableHydration(false)->first();
        $this->set(compact('actionItems', 'results'));
    }

    //Get utilization record
    public function getUtilRecord()
    {
        $params = $this->request->getData();
        $childCond = ['Utilizations.id'=>$params['utilId'], 'Utilizations.airframe_component_id'=>$params['compId']];
        $childQuery = function ($q) use ($childCond) { 
                    return $q->where($childCond)->select(['Utilizations.id', 'Utilizations.plane_id', 'Utilizations.airframe_component_id', 'Utilizations.hours', 'Utilizations.cycles']);
                };
        $record = $this->airCompObj->find() 
                        ->where(['AirframeComponents.id'=>$params['compId'], 'AirframeComponents.plane_id'=>$params['planeId']])
                        ->contain([
                            'Utilizations'=>$childQuery
                        ])
                        ->select(['AirframeComponents.id', 'AirframeComponents.log_book', 'AirframeComponents.position'])
                        ->enableHydration(false)->first();
        //pr($record);die;

        $tHtml = '';
        if($record['log_book'] == 'Airframe') {
            $logBook = $record['log_book'];
        } else {
            $logBook = $record['log_book'].' '.$record['position'];
        }

        if(!empty($record)) {
            $tHtml = '<div class="x_panel"><div class="x_content"><table class="table table-hover" style="padding:0;margin:0;">';
                
                if(!empty($record['utilizations'][0]['id'])) {
                    $utilId = $record['utilizations'][0]['id'];
                } else {
                    $utilId = "";
                }

                if(!empty($record['utilizations'][0]['hours'])) {
                    $hours = $record['utilizations'][0]['hours'];
                } else {
                    $hours = "";
                }

                if(!empty($record['utilizations'][0]['cycles'])) {
                    $cycles = $record['utilizations'][0]['cycles'];
                } else {
                    $cycles = "";
                }

                $tHtml .= '<input type="hidden" class="form-control" name="id" value="'.$utilId.'"><input type="hidden" class="form-control" name="plane_id" value="'.$params['planeId'].'"><input type="hidden" class="form-control" name="airframe_component_id" value="'.$params['compId'].'">';
                $tHtml .= '<tr>
                            <td>Hours</td>
                            <td><input type="text" class="form-control" name="hours" value="'.$hours.'"></td>
                        </tr>';
                $tHtml .= '<tr>
                            <td>Cycles</td>
                            <td><input type="text" class="form-control" name="cycles" value="'.$cycles.'"></td>
                        </tr>';

            $tHtml .= '</table>';
            $tHtml .= '</div></div>';

            $result = array('status'=>'success', 'comp'=>$logBook, 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'comp'=>$logBook, 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    /**
     * Add Utilization
     */
    public function addUtilization() 
    {
        $params = $this->request->getData();
        $data = array('compId'=>$params['airframe_component_id'], 'hours'=>$params['hours'], 'cycles'=>$params['cycles']);
        //pr($params);die;
        if(!empty($params['id'])) {
            $utilRecord = $this->Utilizations->get($params['id']);
            $utilRecord = $this->Utilizations->patchEntity($utilRecord, $params);
            if($this->Utilizations->save($utilRecord)) {
                $res = array('status'=>'success', 'data'=>$data, 'message'=>'Utilizations updated successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        } else {
            $utilRecord = $this->Utilizations->newEmptyEntity();
            $utilRecord = $this->Utilizations->patchEntity($utilRecord, $params);
            if($this->Utilizations->save($utilRecord)) {
                $res = array('status'=>'success', 'data'=>$data, 'message'=>'Utilizations updated successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        }
    }
}