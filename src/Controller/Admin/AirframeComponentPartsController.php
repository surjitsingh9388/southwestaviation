<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\I18n\FrozenTime;
use Cake\Http\Exception\BadRequestException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Cake\Utility\Text;

/**
 * AirframeComponentParts Controller
 *
 * @property \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts
 *
 * @method \App\Model\Entity\AirframeComponentPart[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AirframeComponentPartsController extends AppController
{
    /*private $planeObj;
    private $groupObj;
    private $airCompTimeObj;
    private $partInstTimeObj;
    private $airCompLastCwObj;
    private $parentChildRelObj;*/
    protected \App\Model\Table\PlanesTable $planeObj;
    protected \App\Model\Table\GroupsTable $groupObj;
    protected \App\Model\Table\AirframeComponentTimesTable $airCompTimeObj;
    protected \App\Model\Table\PartInstalledTimesTable $partInstTimeObj;
    protected \App\Model\Table\AirframeComponentLastCwTable $airCompLastCwObj;
    protected \App\Model\Table\ParentChildRelationsTable $parentChildRelObj;
    protected \App\Model\Table\ImportAirframeComponentPartHistoriesTable $ImportAirframeComponentPartHistories;
    protected \App\Model\Table\AirframeComponentsTable $AirframeComponents;

    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize():void {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AtaCode');
        $this->loadComponent('Disposition');
        $this->loadComponent('AdsbStatus');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeComponentPart');
        $this->loadComponent('Report'); 
        $this->loadComponent('AircraftHistory');  
        $this->loadComponent('Position'); 
        $this->loadComponent('AirframeMoc');
        $this->loadComponent('AirframeRequirementType');
        $this->loadComponent('AirframeRequirementSource');
        $this->loadComponent('AirframeIssuingAuthority');   

        $this->planeObj = $this->fetchTable('Planes');
        $this->groupObj = $this->fetchTable('Groups');
        $this->airCompTimeObj = $this->fetchTable('AirframeComponentTimes');
        $this->partInstTimeObj = $this->fetchTable('PartInstalledTimes');
        $this->airCompLastCwObj = $this->fetchTable('AirframeComponentLastCw');
        $this->parentChildRelObj = $this->fetchTable('ParentChildRelations');
        $this->ImportAirframeComponentPartHistories = $this->fetchTable('ImportAirframeComponentPartHistories');
        $this->AirframeComponents = $this->fetchTable('AirframeComponents');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $airIds = $this->Plane->getSelectedAircraft();
        $query['count']  = "SELECT count( AirframeComponentParts.`id`) AS count  FROM `airframe_component_parts` AirframeComponentParts LEFT JOIN `planes` Planes ON AirframeComponentParts.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentParts.`airframe_component_id` = AirframeComponents.`id` WHERE ".$airIds['airIds'];

        $query['detail'] = "SELECT AirframeComponentParts.`id`, Planes.`plane_code`, AirframeComponents.`log_book`,  AirframeComponentParts.`ata_code`, AirframeComponentParts.`mfg_code`, AirframeComponentParts.`item_type`, AirframeComponentParts.`ad_sb_number`, AirframeComponentParts.`amendment`, AirframeComponentParts.`description` FROM `airframe_component_parts` AirframeComponentParts LEFT JOIN `planes` Planes ON AirframeComponentParts.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentParts.`airframe_component_id` = AirframeComponents.`id` WHERE ".$airIds['airIds'];
        
        return $query;
    }

    public function ajaxManageCompPartsSearch() {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Planes.plane_code LIKE '%".$search."%' OR AirframeComponents.log_book LIKE '%".$search."%' OR  AirframeComponentParts.ata_code LIKE '%".$search."%' OR  AirframeComponentParts.mfg_code LIKE '%".$search."%' OR  AirframeComponentParts.item_type LIKE '%".$search."%' OR  AirframeComponentParts.ad_sb_number LIKE '%".$search."%' OR  AirframeComponentParts.amendment LIKE '%".$search."%' OR  AirframeComponentParts.description LIKE '%".$search."%'
            )";
        }

        $columns = array(
            0 => 'AirframeComponentParts.id',
            1 => 'Planes.plane_code',
            2 => 'AirframeComponents.log_book',
            3 => 'AirframeComponentParts.ata_code', 
            4 => 'AirframeComponentParts.description'
        );

        $count = $query['count'].$cond;
        $detail = $query['detail'].$cond;
        $totalCount = $query['count'];
        $conn = ConnectionManager::get('default');
        $results = $conn->execute($count)->fetchAll('assoc');
        $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;
        $totalFiltered = $totalData;
        $results = $conn->execute( $totalCount )->fetchAll('assoc');
        $totalRecords = isset($results[0]['count']) ? $results[0]['count'] : 0;
        $sidx = $columns[$requestData['order'][0]['column']];
        $sord = $requestData['order'][0]['dir'];
        $start = $requestData['start'];
        $length = PAGINATION_LIMIT;

        $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
        $results = $conn->execute( $SQL )->fetchAll('assoc');

        $i = 0;
        $j=1;
         
        $data = array();
        $view = $edit = $delete = '';
        foreach ($results as $row) {
            $ataAD = '';
            if (!empty($row['ata_code']) || !empty($row['mfg_code'])) {
                $ataAD = $row['ata_code'].' '.$row['mfg_code'];
            } elseif (!empty($row['item_type']) || !empty($row['ad_sb_number']) || !empty($row['amendment'])) {
                $ataAD = $row['item_type'].' '.$row['ad_sb_number'].' '.$row['amendment'];
            }

            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["plane_code"];
            $nestedData[] = $row["log_book"];
            $nestedData[] = $ataAD;
            $nestedData[] = $row["description"];
           
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                $view = '<a href="airframe_component_parts/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                $edit = ' <a href="airframe_component_parts/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                $delete = ' <a data-airframe_component_part_id="'.$row['id'].'" data-url="airframe_component_parts/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["log_book"].' Component Part?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $airCompParts = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($airCompParts);die;
    }

    /**
     * View method
     *
     * @param string|null $id AirframeComponentPart id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $airCompParts = $this->AirframeComponentParts->get($id, [
                            'contain' => [
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ]
                                ],
                                'AirframeComponents'=>[
                                    'fields'=>[
                                        'AirframeComponents.id',
                                        'AirframeComponents.log_book',
                                        'AirframeComponents.position'
                                    ],
                                    'AirframeComponentTimes'=>[
                                        /*'fields'=>[
                                            'AirframeComponentTimes.id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']*/
                                        'strategy' => 'select',
                                        'queryBuilder' => function ($q) {
                                            return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                        }
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    //'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                                    'strategy' => 'select',
                                    'queryBuilder' => function ($q) {
                                        return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                                    }
                                ],
                                'AirframeCategories'=>[
                                    'fields'=>[
                                        'AirframeCategories.id',
                                        'AirframeCategories.category_name'
                                    ]
                                ]
                            ]
                        ]);

        $this->set('airCompParts', $airCompParts);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }

        $airCompParts = $this->AirframeComponentParts->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            
            if(empty($postData['parent_id'])) {
                $postData['parent_id'] = 0;
            }

            if(empty($postData['plane_id']) || empty($postData['airframe_component_id'])){
                $this->Flash->error(__('The Aircraft and Component could not be blank. Please, try again.'));
                return $this->redirect($this->referer());
            }
            
            $connection = ConnectionManager::get('default');
            $counts = 0;
            
            foreach($postData['plane_id'] as $key=>$plane_id){
                $airCompParts = $this->AirframeComponentParts->newEmptyEntity();

                $partdata = $postData;
                unset($partdata['plane_id']);
                unset($partdata['airframe_component_id']);

                $partdata['plane_id'] = $plane_id;
                $partdata['airframe_component_id'] = $postData['airframe_component_id'][$key];

                if(!empty($partdata['item_type_id'])){
                    $itemtypearr = $this->AirframeComponentPart->getItemTypesById($partdata['item_type_id']);
                    $partdata['item_type'] = $itemtypearr['sort_title'];
                }else{
                    $partdata['item_type'] = '';
                }

                if(!empty($partdata['requirement_type_id'])){
                    $requirementtypearr = $this->AirframeComponentPart->getRequirementTypesById($partdata['requirement_type_id']);
                    $partdata['requirement_type'] = $requirementtypearr['title'];
                }else{
                    $partdata['requirement_type'] = '';
                }

                if(!empty($partdata['issuing_authority_id'])){
                    $authorityarr = $this->AirframeComponentPart->getIssuingAuthorityById($partdata['issuing_authority_id']);
                    $partdata['authority'] = $authorityarr['title'];
                }else{
                    $partdata['authority'] = '';
                }

                $airCompParts = $this->AirframeComponentParts->patchEntity($airCompParts, $partdata);
                if($this->AirframeComponentParts->save($airCompParts)){
                    //save attachment
                    $airframe_component_part_id = $airCompParts->id;
                    $plane_id = $airCompParts->plane_id;
                    if(!empty($postData['filenames'])){
                        for($i = 0; $i<count($postData['filenames']); $i++){
                            $componentpartfiles = $connection
                                    ->execute(
                                        'SELECT max(position) as positions FROM airframe_component_part_files WHERE airframe_component_part_id = :airframe_component_part_id and status = "1" limit 1',
                                        ['airframe_component_part_id' => $airframe_component_part_id],
                                        ['created' => 'datetime']
                                    )
                                    ->fetch('assoc');
                            
                            $position = !empty($componentpartfiles) ? $componentpartfiles['positions']+1 : '1';
                            
                            $attachmentdata = [
                                                'plane_id'=>$plane_id,
                                                'airframe_component_part_id'=>$airframe_component_part_id,
                                                'file_name' => $postData['filenames'][$i],
                                                'file_size' => $postData['filesize'][$i],
                                                'position'=>$position,
                                                'added_by' => $authUserData['id'],
                                                'created_at'=>date("Y-m-d H:i:s")
                                            ];

                            $connection->insert('airframe_component_part_files', $attachmentdata, ['created' => 'datetime']);
                        }
                    }
                    
                    $counts++;
                    //Post data process and calculate nextdue
                    $partdata = $this->AirframeComponentPart->postDataProcess($partdata);

                    //Save related data in airframe_component_last_cw table
                    $partdata['airframe_component_part_id'] = $airCompParts->id;
                    $partdata['last_revised_by'] = $authUserData['id'];
                    $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                    
                    $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $partdata);
                    $this->airCompLastCwObj->save($partsDetails);

                    //Part installed time
                    $partInstalledTimes = $this->partInstTimeObj->newEmptyEntity();
                    $partInstalledTimes = $this->partInstTimeObj->patchEntity($partInstalledTimes, $partdata);
                    $this->partInstTimeObj->save($partInstalledTimes);
                    //End part installed time

                    //save to aircraft history table
                    $this->AircraftHistory->saveAirframeComponentPartHistory($partdata);
                }
            }
            if ($counts == count($postData['plane_id'])) {
                $this->Flash->success(__('The Aircraft Component Part has been saved.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The Aircraft Component Part could not be saved. Please, try again.'));
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $ataCode   = $this->AtaCode->getAtaCodes();
        $dispArr   = $this->Disposition->getDispositions();
        $adsbArr   = $this->AdsbStatus->getAdsbStatus();
        $positionArr   = $this->Position->getPositions();
        $airCPComp = $this->AirframeComponentPart;
        $this->set(compact('airCompParts', 'actionItems', 'planes', 'ataCode', 'dispArr', 'adsbArr', 'airCPComp', 'positionArr'));
    }

    /**
     * Edit method
     *
     * @param string|null $id AirframeComponentPart id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id=null, $typ=null, $act=null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }
        
        //Get previous saved details for display
        $airCompParts = $this->AirframeComponentParts->get($id, [
                            'contain' => [
                                'Groups'=>[
                                    'fields'=>[
                                        'Groups.id',
                                        'Groups.airframe_component_part_id',
                                        'Groups.group_name'
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'strategy' => 'select',
                                    'queryBuilder' => function ($q) {
                                        return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                                    }
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'strategy' => 'select',
                                        'queryBuilder' => function ($q) {
                                            return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                        }
                                    ]
                                ],
                                'PartInstalledTimes'=>[
                                    'sort'=>['PartInstalledTimes.id'=>'DESC']
                                ]
                            ]
                        ]);
        
        if (!empty($airCompParts['airframe_component_last_cw']) && isset($airCompParts['airframe_component_last_cw'][0])) {

            // Check and replace last_revised_by
            if (!empty($airCompParts['airframe_component_last_cw'][0]['last_revised_by']) &&
                is_numeric($airCompParts['airframe_component_last_cw'][0]['last_revised_by'])) {
                $airCompParts['airframe_component_last_cw'][0]['last_revised_by'] = 
                    $this->User->getUserName($airCompParts['airframe_component_last_cw'][0]['last_revised_by']);
            }

            // Check and replace last_reported_by
            if (!empty($airCompParts['airframe_component_last_cw'][0]['last_reported_by']) && is_numeric($airCompParts['airframe_component_last_cw'][0]['last_reported_by'])) {
                $airCompParts['airframe_component_last_cw'][0]['last_reported_by'] = 
                    $this->User->getUserName($airCompParts['airframe_component_last_cw'][0]['last_reported_by']);
            }
        }


        //Get all childs
        $partIds = [''];
        if(!empty($airCompParts->plane_id) && !empty($airCompParts->group->id) && $id) {
            $prChildRes = $this->parentChildRelObj->find()
                            ->where([
                                'ParentChildRelations.plane_id'=>$airCompParts->plane_id, 
                                'ParentChildRelations.group_id'=>$airCompParts->group->id, 
                                'ParentChildRelations.parent_id'=>$id
                            ])
                            ->select(['airframe_component_part_id'])
                            ->enableHydration(false)->toArray();
                                    
            if(!empty($prChildRes)) {
                foreach ($prChildRes as $key => $value) {
                    $partIds[] = $value['airframe_component_part_id'];
                }
            }
        }

        //To check parent child relation
        $childQuery = function ($q) use ($partIds) {
                        return $q->where(['AirframeComponentParts.id IN'=>$partIds])
                        ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                        ->contain([
                            'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]
                        ]);
                    };

        $partRecords = $this->AirframeComponentParts->find()
                        ->where(['AirframeComponentParts.plane_id'=>$airCompParts->plane_id, 'AirframeComponentParts.id IN'=>$partIds])
                        ->contain([ 
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ],
                                'AirframeComponentParts'=>$childQuery
                            ],
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'AirframeComponentTimes'=>[
                                    'fields'=>[
                                        'AirframeComponentTimes.id',
                                        'AirframeComponentTimes.airframe_component_id',
                                        'AirframeComponentTimes.hours',
                                        'AirframeComponentTimes.cycles' 
                                    ],
                                    'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                ],
                                'Utilizations'=>[
                                    'fields'=>[
                                        'Utilizations.id',
                                        'Utilizations.airframe_component_id',
                                        'Utilizations.hours',
                                        'Utilizations.cycles'
                                    ]
                                ]
                            ],
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                            /*'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]*/
                        ])
                        ->enableHydration(false)->toArray();
   
        //Get conditional records
        $subResults = $this->condRecords($partRecords, $params=array());
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();

            /*if(empty($postData['parent_id'])) {
                $postData['parent_id'] = 0;
            }*/

            if(!empty($postData['item_type_id'])){
                $itemtypearr = $this->AirframeComponentPart->getItemTypesById($postData['item_type_id']);
                $postData['item_type'] = $itemtypearr['sort_title'];
            }else{
                $postData['item_type'] = '';
            }

            if(!empty($postData['requirement_type_id'])){
                $requirementtypearr = $this->AirframeComponentPart->getRequirementTypesById($postData['requirement_type_id']);
                $postData['requirement_type'] = $requirementtypearr['title'];
            }else{
                $postData['requirement_type'] = '';
            }

            if(!empty($postData['issuing_authority_id'])){
                $authorityarr = $this->AirframeComponentPart->getIssuingAuthorityById($postData['issuing_authority_id']);
                $postData['authority'] = $authorityarr['title'];
            }else{
                $postData['authority'] = '';
            }

            $airCompParts = $this->AirframeComponentParts->patchEntity($airCompParts, $postData);
            if ($this->AirframeComponentParts->save($airCompParts)) {
                
                //Post data process and calculate nextdue
                $postData = $this->AirframeComponentPart->postDataProcess($postData);
                
                //Save related data in airframe_component_last_cw table
                $postData['airframe_component_part_id'] = $airCompParts->id;
                $postData['last_revised_by'] = $authUserData['id'];
                $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                
                $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                $this->airCompLastCwObj->save($partsDetails);
                //End to save related data in airframe_component_last_cw table

                $this->Flash->success(__('The aircraft component part has been saved.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The aircraft component part could not be saved. Please, try again.'));
        }

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $airComps  = $this->AirframeComponent->getRAirComps($airCompParts->plane_id);
        $ataCode   = $this->AtaCode->getAtaCodes($airCompParts->ata_code);
        $dispArr   = $this->Disposition->getDispositions($airCompParts->disposition);
        $adsbArr   = $this->AdsbStatus->getAdsbStatus($airCompParts->ab_sb_status);
        $positionArr   = $this->Position->getPositions();
        $airCPComp = $this->AirframeComponentPart;

        $responseArr = $this->AirframeComponentPart->getAirframeComponentPartAttachments($id);
        $partfiletblrow = $responseArr['tblrow'];
        $attachmentcounts = $responseArr['attachmentcounts'];

        $this->set(compact('airCompParts', 'actionItems', 'subResults', 'planes', 'airComps', 'ataCode', 'dispArr', 'adsbArr', 'airCPComp', 'typ', 'act', 'partfiletblrow', 'attachmentcounts', 'positionArr'));
    }

    /**
     * Delete method
     *
     * @param string|null $id AirframeComponentPart id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);

        //Delete Aircraft Parts related data
        $airCompLastCw = array('AirframeComponentLastCw.airframe_component_part_id' => $id);
        $this->airCompLastCwObj->deleteAll($airCompLastCw,false);

        //Delete Groups
        $grp = array('Groups.airframe_component_part_id' => $id);
        $this->groupObj->deleteAll($grp,false);

        //Delete installed/uninstalled parts data
        $partInstTime = array('PartInstalledTimes.airframe_component_part_id' => $id);
        $this->partInstTimeObj->deleteAll($partInstTime,false);

        //Reset child status
        $this->AirframeComponentParts->updateAll(
            ['parent_id' => 0],
            ['parent_id' => $id]
        );

        $airCompParts = $this->AirframeComponentParts->get($id);

        try {
            if ($this->AirframeComponentParts->delete($airCompParts)) {
                $this->Flash->success(__('Part has been deleted.'));
            } else {
                $this->Flash->error(__('Part could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }    
        return $this->redirect(['action' => 'index']);
    }

    //Change next due, remaining and interval values based on recurring or threshold selected
    public function changeNextdueRem()
    {
        $params = $this->request->getData();
        $report = $this->AirframeComponentParts->find()
                    ->where(['AirframeComponentParts.id'=>$params['partId']])
                    ->contain([ 
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ]
                        ],
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position'
                            ],
                            'AirframeComponentTimes'=>[
                                /*'fields'=>[
                                    'AirframeComponentTimes.id',
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles' 
                                ],
                                'sort'=>['AirframeComponentTimes.id'=>'DESC']*/
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                }
                            ]
                        ],
                        'AirframeComponentLastCw'=>[
                            //'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                            }
                        ],
                    ])
                    ->enableHydration(false)->first();

        //calculations
        $data = array();
        if(!empty($report)) {        
            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            $getRes = $this->AirframeComponentPart->getDataProcess($params, $report);
            if(!empty($getRes)) {
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
            }

            //Component Times
            $compHours  = !empty($report['airframe_component']['airframe_component_times'][0]['hours']) ? $report['airframe_component']['airframe_component_times'][0]['hours'] : 0;
            $compCycles = !empty($report['airframe_component']['airframe_component_times'][0]['cycles']) ? $report['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

            //Start Remaining           
            $remData = $this->Report->getRemaining($mos);
            $totalRemD = $remData['totalRemD'];
            $remMos  = $remData['maintRemMos'];
            $remDays = $remData['maintRemDays'];
            $remHrs  = !empty($hrs) ? $hrs - $compHours : '';
            $remAfl  = !empty($afl) ? $afl - $compCycles : '';
            //End Remaining

            $hrs = !empty($hrs) ? round($hrs,1) : '0';
            $remHrs = !empty($remHrs) ? round($remHrs,1) : '0';

            $data = array(
                    'nextMos' => !empty($mos) ? date('m-d-Y', strtotime($mos)) : '',
                    'nextHrs' => $hrs,
                    'nextAfl' => $afl,
                    'remMos'  => $remMos,
                    'remDays' => $remDays,
                    'remHrs'  => $remHrs,
                    'remAfl'  => $remAfl,
                    'override'=> $report['airframe_component_last_cw'][0]['override']
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Update remaining maintenance due
    public function updateRemaining()
    {
        $params = $this->request->getData();
        $mos = $params['nextMos'];

        $remData = $this->Report->getRemaining($mos);
        $remMos  = $remData['maintRemMos'];
        $remDays = $remData['maintRemDays'];
        $totalRemD    = $remData['totalRemD'];

        $data = array(
                'remMos'  => $remMos,
                'remDays' => $remDays
            );
        $result = array('status'=>'success', 'data'=>$data);
        echo json_encode($result);die;
    }

    //Change next due, remaining and interval values based on recurring or threshold selected
    public function addNextdueRem()
    {
        $params = $this->request->getData();
        $report = $this->airCompTimeObj->find()
                    ->where(['AirframeComponentTimes.plane_id'=>$params['planeId'], 'AirframeComponentTimes.airframe_component_id'=>$params['compId']])
                    ->select(['id', 'hours', 'cycles'])
                    ->order(['AirframeComponentTimes.id DESC'])
                    ->enableHydration(false)->toArray();

        //Check if part months value is date then convert it to months and days
        if(!empty($params['partMos']) && !is_numeric($params['partMos'])) {
            $remData = $this->checkmydate(trim($params['partMos']), trim($params['lastCwMos']));
            if(!empty($remData) && is_array($remData)) {
                $params['intvAdjMos'] = $remData['maintRemMos'];
                $params['intvAdjDay'] = $remData['maintRemDays'];
            } else {
                $params['intvAdjMos'] = 0;
                $params['intvAdjDay'] = 0;
            }
        } elseif(!empty($params['partMos']) && is_numeric(trim($params['partMos']))) {
            $params['intvAdjMos'] = -trim($params['partMos']);
            $params['intvAdjDay'] = 0;

        } elseif(!empty($params['overhaulType']) && empty($params['partMos'])) {
            $params['intvAdjMos'] = 0;
            $params['intvAdjDay'] = 0;
        }

        //Adjust overhaul and repair
        if(!empty($params['partHrs'])) {
            $params['intvAdjHrs'] = -trim($params['partHrs']);
        } elseif(!empty($params['overhaulType']) && empty($params['partHrs'])) {
            $params['intvAdjHrs'] = 0;
        }

        if(!empty($params['partAfl'])) {
            $params['intvAdjAfl'] = -trim($params['partAfl']);
        } elseif(!empty($params['overhaulType']) && empty($params['partAfl'])) {
            $params['intvAdjAfl'] = 0;
        }

        //calculations
        $data = array();
        if(!empty($params)) {
            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            $getRes = $this->AirframeComponentPart->getDataProcess($params);
            if(!empty($getRes)) {
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
            }

            //Component Times
            $compHours  = !empty($report[0]['hours']) ? $report[0]['hours'] : 0;
            $compCycles = !empty($report[0]['cycles']) ? $report[0]['cycles'] : 0;
            
            //Start Remaining           
            $remData = $this->Report->getRemaining($mos);
            $totalRemD = $remData['totalRemD'];
            $remMos  = $remData['maintRemMos'];
            $remDays = $remData['maintRemDays'];
            $remHrs  = !empty($hrs) ? $hrs - $compHours : '';
            $remAfl  = !empty($afl) ? $afl - $compCycles : '';
            //End Remaining
            $hrs = !empty($hrs) ? round($hrs,1) : '0';
            $remHrs = !empty($remHrs) ? round($remHrs,1) : '0';

            $data = array(
                    'nextMos' => !empty($mos) ? date('m-d-Y', strtotime($mos)) : '',
                    'nextHrs' => $hrs,
                    'nextAfl' => $afl,
                    'remMos'  => $remMos,
                    'remDays' => $remDays,
                    'remHrs'  => $remHrs,
                    'remAfl'  => $remAfl,
                    'override'=> $params['isOverride'],
                    'hours'   => $compHours,
                    'cycles'  => $compCycles,
                    'intvAdjMos' => !empty($params['intvAdjMos']) ? $params['intvAdjMos'] : 0,
                    'intvAdjDay' => !empty($params['intvAdjDay']) ? $params['intvAdjDay'] : 0,
                    'intvAdjHrs' => !empty($params['intvAdjHrs']) ? $params['intvAdjHrs'] : 0,
                    'intvAdjAfl' => !empty($params['intvAdjAfl']) ? $params['intvAdjAfl'] : 0
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Get component times
    public function getCompTimes() 
    {
        $params = $this->request->getData();
        $report = $this->airCompTimeObj->find()
                    ->where(['AirframeComponentTimes.plane_id'=>$params['planeId'], 'AirframeComponentTimes.airframe_component_id'=>$params['compId']])
                    ->select(['id', 'hours', 'cycles'])
                    ->order(['AirframeComponentTimes.id DESC'])
                    ->enableHydration(false)->toArray();

        if(!empty($report)) {
            $data = array(
                    'id' => $report[0]['id'],
                    'hours' => $report[0]['hours'],
                    'cycles' => $report[0]['cycles']
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;

        } else {
            $result = array('status'=>'failure', 'data'=>array());
            echo json_encode($result);die;
        }
    }

    //Get parts list for dropdown
    public function partsList()
    {
        $this->viewBuilder()->setLayout('ajax');
        $params = $this->request->getData();
        $parts = $this->AirframeComponentParts->find()
                    ->where(['AirframeComponentParts.parent_id'=>0, 'AirframeComponentParts.plane_id'=>$params['airId']])
                    ->select(['id', 'description'])
                    ->enableHydration(false)->toArray();
        
        $result = array();
        if(!empty($parts)) {
            foreach ($parts as $key => $value) {
                $result[$value['id']] = substr($value['description'], 0, 30);
            }
        }
        $this->set('partDropdown', $result);
    }

    //Get group details
    public function getGroupRecord()
    {
        $params = $this->request->getData();
        $record = $this->groupObj->find() 
                        ->where(['Groups.id'=>$params['groupId'], 'Groups.plane_id'=>$params['planeId'], 'Groups.airframe_component_id'=>$params['compId'], 'Groups.airframe_component_part_id'=>$params['partId']])
                        ->enableHydration(false)->first();
        
        $tHtml = '';
        if(!empty($record)) {
            $tHtml = '<div class="x_panel"><div class="x_content"><table class="table table-hover" style="padding:0;margin:0;">';

                $tHtml .= '<input type="hidden" name="id" value="'.$record['id'].'"><input type="hidden" name="plane_id" value="'.$record['plane_id'].'"><input type="hidden" name="airframe_component_id" value="'.$record['airframe_component_id'].'"><input type="hidden" name="airframe_component_part_id" value="'.$record['airframe_component_part_id'].'">';
                $tHtml .= '<tr>
                            <td>Name</td>
                            <td><input type="text" class="form-control" name="group_name" value="'.$record['group_name'].'"></td>
                        </tr>';
                        
            $tHtml .= '</table>';
            $tHtml .= '</div></div>';

            $result = array('status'=>'success', 'type'=>'Edit', 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $tHtml = '<div class="x_panel"><div class="x_content"><table class="table table-hover" style="padding:0;margin:0;">';

                $tHtml .= '<input type="hidden" name="id" value=""><input type="hidden" name="plane_id" value="'.$params['planeId'].'"><input type="hidden" name="airframe_component_id" value="'.$params['compId'].'"><input type="hidden" name="airframe_component_part_id" value="'.$params['partId'].'">';
                $tHtml .= '<tr>
                            <td>Name</td>
                            <td><input type="text" class="form-control" name="group_name" value=""></td>
                        </tr>';
                
            $tHtml .= '</table>';
            $tHtml .= '</div></div>';

            $result = array('status'=>'success', 'type'=>'Create', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    /**
     * Add/Update Group
     */
    public function addGroup()
    {
        $params = $this->request->getData();
        if(!empty($params['id'])) {
            $groupRecord = $this->groupObj->get($params['id']);
            $groupRecord = $this->groupObj->patchEntity($groupRecord, $params);
            if($this->groupObj->save($groupRecord)) {
                $res = array('status'=>'success', 'data'=>$params['group_name'], 'message'=>'Group updated successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        } else {
            $groupRecord = $this->groupObj->newEmptyEntity();
            $groupRecord = $this->groupObj->patchEntity($groupRecord, $params);
            if($this->groupObj->save($groupRecord)) {
                $res = array('status'=>'success', 'data'=>$params['group_name'], 'message'=>'Group added successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        }
    }

    //Get all parts list to create parent/chaild relation
    public function getPartsRecord()
    {
        $params = $this->request->getData();
        //pr($params);die;
        $results = [];
        /*if(!empty($params['planeId'])) {
            //To check parent child relation
            $childQuery = function ($q) { 
                        return $q->where(['AirframeComponentParts.parent_id !='=>0, 'AirframeComponentParts.id !='=>'ParentChildRelations.parent_id'])
                        ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                        ->contain([
                            'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]
                        ]);
                    };
            
            $partIds = [''];
            if(!empty($params['planeId']) && !empty($params['partId'])) {
                $prChildRes = $this->parentChildRelObj->find()
                                ->where([
                                    'ParentChildRelations.plane_id'=>$params['planeId'], 
                                    'ParentChildRelations.parent_id'=>$params['partId']
                                ])
                                ->select(['airframe_component_part_id'])
                                ->enableHydration(false)->toArray();
                                        
                if(!empty($prChildRes)) {
                    foreach ($prChildRes as $key => $value) {
                        $partIds[] = $value['airframe_component_part_id'];
                    }
                }
            }

            $reports = $this->AirframeComponentParts->find()
                        ->where(['AirframeComponentParts.plane_id'=>$params['planeId']])
                        ->contain([ 
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ],
                                'AirframeComponentParts'=>$childQuery
                            ],
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'AirframeComponentTimes'=>[
                                    'fields'=>[
                                        'AirframeComponentTimes.id',
                                        'AirframeComponentTimes.airframe_component_id',
                                        'AirframeComponentTimes.hours',
                                        'AirframeComponentTimes.cycles' 
                                    ],
                                    'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                ]
                            ],
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                        ])
                        ->enableHydration(false)->toArray();

            //Get conditional records
            $results = $this->condRecords($reports, $params);
        }*/

        if (!empty($params['planeId'])) {
            $partIds = [];
        
            // Get child part IDs if partId is provided
            if (!empty($params['partId'])) {
                $prChildRes = $this->parentChildRelObj->find()
                    ->where([
                        'ParentChildRelations.plane_id' => $params['planeId'],
                        'ParentChildRelations.parent_id' => $params['partId']
                    ])
                    ->select(['airframe_component_part_id'])
                    ->enableHydration(false)
                    ->toArray();
        
                if (!empty($prChildRes)) {
                    foreach ($prChildRes as $value) {
                        $partIds[] = $value['airframe_component_part_id'];
                    }
                }
            }
        
            // Define child relation condition using a closure
            $childQuery = function ($q) {
                return $q->where([
                        'AirframeComponentParts.parent_id !=' => 0,
                        'AirframeComponentParts.id != ParentChildRelations.parent_id'
                    ])
                    ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                    ->contain([
                        'ParentChildRelations' => [
                            'fields' => ['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                        ]
                    ]);
            };
        
            // Main report query
            $reports = $this->AirframeComponentParts->find()
                ->where(['AirframeComponentParts.plane_id' => $params['planeId']])
                ->contain([
                    'Planes' => [
                        'fields' => ['Planes.id', 'Planes.plane_code'],
                        'AirframeComponentParts' => $childQuery
                    ],
                    'AirframeComponents' => [
                        'fields' => ['AirframeComponents.id', 'AirframeComponents.log_book', 'AirframeComponents.position'],
                        'AirframeComponentTimes' => function ($q) {
                            return $q->select([
                                    'AirframeComponentTimes.id',
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles'
                                ])
                                ->order(['AirframeComponentTimes.id' => 'DESC']);
                        }
                    ],
                    'AirframeComponentLastCw' => function ($q) {
                        return $q->order(['AirframeComponentLastCw.id' => 'DESC']);
                    }
                ])
                ->enableHydration(false)
                ->toArray();
        
            // Apply your custom filter
            $results = $this->condRecords($reports, $params);
        }
        

        $partId = '';
        if(!empty($params['partId'])) {
            $partId = $params['partId'];
        }

        //If quick ref no need of groupId
        if(!empty($params['quickRef'])) {
            $groupId = '';
        } else {
            $groupId = $params['groupId'];
        }

        $tHtml = '';
        if(!empty($results)) {
            $tHtml = '<input type="hidden" name="plane_id" id="selPlaneId" data-planeid="'.$params['planeId'].'"><input type="hidden" name="parentid" id="selParentId" data-parentid="'.$partId.'"><input type="hidden" name="groupid" id="selGroupId" data-groupid="'.$groupId.'">
            <div class="action-bar">
                <div class="actionbar-lft">
                    <div class="lftWrap">
                        <div class="input-group search-control mb-0">
                            <input id="searchItemPopup" type="text" class="form-control" placeholder="Search Maintenance Items">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><img src="'.ROOT_DIR.'/images/icons/zoom.png" class="zoom-img" alt=""></button>
                            </div>
                        </div>

                        <div class="sortWrap">
                            <div class="sortTxt">Sort By</div>
                            <div class="dropdown">
                                <select class="selectpicker actionCls " id="sortByIdPopup">
                                    <option>Aircraft</option>
                                    <option value="atacode">ATA Code</option>
                                    <option value="description">Description</option>
                                    <option value="lastcomplied">Last Complied With</option>
                                    <option value="intervals">Intervals</option>
                                    <option value="nextDue">Next Due</option>
                                    <option value="remaining">Remaining</option>
                                    <option value="status">Due Status</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            $tHtml .= '<div class="overflow-x-axis">
                <table id="customReportPopup" class="table table-hover table-header-dark">
                    <thead>
                        <tr>
                            <th width="3%"><input type="checkbox" name="air_check" id="ckbCheckAllPopup"></th>
                            <th width="7%">Aircraft</th>
                            <th width="5%">ATA</th>
                            <th width="15%">Reference & Component & Item Type</th>
                            <th width="30%">Description</th>
                            <th width="8%">Interval</th>
                            <th width="8%">Threshold</th>
                            <th width="8%">Recurring</th>
                            <th width="8%">Next Due</th>
                            <th width="8%">Tolerance</th>
                        </tr>
                    </thead>
                    <tbody id="aircraftPartsListPopup">';
                    foreach ($results as $row) {
                        //Interval
                        $reqFeq = '';
                        if(!empty($row['reqFeq']['reqFreqMos'])) {
                            $reqFeq .= 'Months: '.$row['reqFeq']['reqFreqMos'].'<br>';
                        }

                        if(!empty($row['reqFeq']['reqFreqHrs'])) {
                            $reqFeq .= 'Hours: '.$row['reqFeq']['reqFreqHrs'].'<br>';
                        }

                        if(!empty($row['reqFeq']['reqFreqAfl'])) {
                            $reqFeq .= 'Cycles: '.$row['reqFeq']['reqFreqAfl'].'<br>';
                        }

                        //Threshold
                        $threshold = '';
                        if(!empty($row['threshold']['thresMos'])) {
                            $threshold .= 'Months: '.$row['threshold']['thresMos'].'<br>';
                        }

                        if(!empty($row['threshold']['thresDays'])) {
                            $threshold .= 'Days: '.$row['threshold']['thresDays'].'<br>';
                        }

                        if(!empty($row['threshold']['thresHrs'])) {
                            $threshold .= 'Hours: '.round($row['threshold']['thresHrs'],1).'<br>';
                        }

                        if(!empty($row['threshold']['thresAfl'])) {
                            $threshold .= 'Cycles: '.$row['threshold']['thresAfl'].'<br>';
                        }

                        //Recurring
                        $recurring = '';
                        if(!empty($row['recurring']['recMos'])) {
                            $recurring .= 'Months: '.$row['recurring']['recMos'].'<br>';
                        }

                        if(!empty($row['recurring']['recDays'])) {
                            $recurring .= 'Days: '.$row['recurring']['recDays'].'<br>';
                        }

                        if(!empty($row['recurring']['recHrs'])) {
                            $recurring .= 'Hours: '.round($row['recurring']['recHrs'],1).'<br>';
                        }

                        if(!empty($row['recurring']['recAfl'])) {
                            $recurring .= 'Cycles: '.$row['recurring']['recAfl'].'<br>';
                        }

                        //Next Due
                        $nextDue = '';
                        if(!empty($row['nextDue']['mos'])) {
                            $nextDue .= $row['nextDue']['mos'].'<br>';
                        }

                        if(!empty($row['nextDue']['hrs'])) {
                            $nextDue .= 'Hours: '.$row['nextDue']['hrs'].'<br>';
                        }

                        if(!empty($row['nextDue']['afl'])) {
                            $nextDue .= 'Cycles: '.$row['nextDue']['afl'].'<br>';
                        }

                        //Tolerance
                        $tolerance = '';
                        if(!empty($row['tolerance']['tolrMos'])) {
                            $tolerance .= 'Months: '.$row['tolerance']['tolrMos'].'<br>';
                        }

                        if(!empty($row['tolerance']['tolrDays'])) {
                            $tolerance .= 'Days: '.$row['tolerance']['tolrDays'].'<br>';
                        }

                        if(!empty($row['tolerance']['tolrHrs'])) {
                            $tolerance .= 'Hours: '.round($row['tolerance']['tolrHrs'],1).'<br>';
                        }

                        if(!empty($row['tolerance']['tolrAfl'])) {
                            $tolerance .= 'Cycles: '.$row['tolerance']['tolrAfl'].'<br>';
                        }

                        $partNum = !empty($row['partDet']['partNum']) ? $row['partDet']['partNum'] : '-';
                        $serlNum = !empty($row['partDet']['partSerial']) ? $row['partDet']['partSerial'] : '-';

                        $tHtml .= '<tr>';
                            $tHtml .= '<td>
                                <input type="checkbox" class="chkBoxClsPopup" name="childcheckbox" value="'.$row['partDet']['id'].'">';
                                if(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'P') {
                                    $tHtml .= '<br><span class="parent-child-circle" title="This item is a parent.">'.$row['partDet']['parentChild'].'</span>';
                                } elseif(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'C') {
                                    $tHtml .= '<br><span class="parent-child-circle" title="Child of '.$row['partDet']['parentName'].'.">'.$row['partDet']['parentChild'].'</span>';
                                }
                            $tHtml .= '</td>';
                            
                            $tHtml .= '<td class="collapse-tr">'.$row['plane']['plane_code'].'</td>';

                            $tHtml .= '<td class="collapse-tr">'.$row['ataCode']['ata_value'].'</td>';

                            $tHtml .= '<td class="collapse-tr">'.$row['ataCode']['reference'].'<br>'.$row['ataCode']['log_book'].'<br>'.$row['ataCode']['item_type'].'</td>';

                            $tHtml .= '<td class="collapse-tr">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">'.$row['partDet']['partDesc'].'</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><span class="lblColor">Part Number <br></span>'.$partNum.'</div>
                                        <div class="col-md-6"><span class="lblColor">Serial Number <br></span>'.$serlNum.'</div>
                                    </div>
                                </td>';

                            $tHtml .= '<td class="collapse-tr">'.$reqFeq.'</td>';
                            $tHtml .= '<td class="collapse-tr">'.$threshold.'</td>';
                            $tHtml .= '<td class="collapse-tr">'.$recurring.'</td>';
                            $tHtml .= '<td class="collapse-tr">'.$nextDue.'</td>';
                            $tHtml .= '<td class="collapse-tr">'.$tolerance.'</td>';
                        $tHtml .= '</tr>';
                    } 
                    $tHtml .= '</tbody>
                </table>
            </div>';

            $result = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    //Add to parent group
    public function addToParent_old()
    {
        $params = $this->request->getData();
        //pr($params);die;
        if($this->request->is(['patch', 'post', 'put']))
        {
            $parentId = $params['parentId'];
            $partIds  = $params['partIds'];

            $this->AirframeComponentParts->updateAll(
                ['parent_id' => $parentId],
                ['id IN' => $partIds]
            );

            $result = array('status'=>'success', 'message'=>'Updated successfully.');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Add to parent group
    public function addToParent()
    {
        $params = $this->request->getData();
        //pr($params);die;
        if(!empty($params) && $this->request->is(['patch', 'post', 'put']))
        {
            $relData = array();
            foreach ($params['partIds'] as $key => $value) {
                $relData[$key]['plane_id'] = $params['planeId'];
                $relData[$key]['group_id'] = $params['groupId'];
                $relData[$key]['parent_id'] = $params['parentId'];
                $relData[$key]['airframe_component_part_id'] = $value;
            }

            $parentChildRel = $this->parentChildRelObj->newEntities($relData);
            if($this->parentChildRelObj->saveMany($parentChildRel)) {

                $this->AirframeComponentParts->updateAll(
                    ['parent_id' => 1],
                    ['id IN' => $params['partIds']]
                );

                $result = array('status'=>'success', 'message'=>'Updated successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Delete Multiple Parts with associated record delete
    public function deleteMultiParts()
    {
        $params = $this->request->getData();

        if(!empty($params['aircounts'])) {
            //Delete by Aircraft Ids
            $aircraftIds = explode(',', $params['pids']);
            
            //AirframeComponentLastCw
            $childCond1 = array('AirframeComponentLastCw.plane_id in' => $aircraftIds);
            $this->airCompLastCwObj->deleteAll($childCond1,false);

            //Groups
            $childCond2 = array('Groups.plane_id in' => $aircraftIds);
            $this->groupObj->deleteAll($childCond2,false);

            //PartInstalledTimes
            $childCond3 = array('PartInstalledTimes.plane_id in' => $aircraftIds);
            $this->partInstTimeObj->deleteAll($childCond3,false);

            $condition = array('AirframeComponentParts.plane_id in' => $aircraftIds);
            if($this->AirframeComponentParts->deleteAll($condition,false)) {
                $result = array('status'=>'success', 'message'=>'Deleted successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
                echo json_encode($result);die;
            }
        } else {
            //Delete by Part Ids
            if(is_array($params['partids'])) {
                $partIds = $params['partids'];
            } else {
                $partIds = array($params['partids']);
            }

            //AirframeComponentLastCw
            $childCond1 = array('AirframeComponentLastCw.airframe_component_part_id in' => $partIds);
            $this->airCompLastCwObj->deleteAll($childCond1,false);

            //Groups
            $childCond2 = array('Groups.airframe_component_part_id in' => $partIds);
            $this->groupObj->deleteAll($childCond2,false);

            //PartInstalledTimes
            $childCond3 = array('PartInstalledTimes.airframe_component_part_id in' => $partIds);
            $this->partInstTimeObj->deleteAll($childCond3,false);

            //Reset child status: not working will check later
            $this->AirframeComponentParts->updateAll(
                ['parent_id' => 0],
                ['parent_id IN' => $partIds]
            );

            $condition = array('AirframeComponentParts.id in' => $partIds);
            if($this->AirframeComponentParts->deleteAll($condition,false)) {
                $result = array('status'=>'success', 'message'=>'Deleted successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
                echo json_encode($result);die;
            }
        }
    }

    //Remove all childs status if parent deleted
    public function resetParentChild()
    {
        $params = $this->request->getData();
        if($this->request->is(['patch', 'post', 'put'])) 
        {
            $parentId = $params['parentId'];
            $partIds = $params['partIds'];
            /*$this->AirframeComponentParts->updateAll(
                ['parent_id' => 0],
                ['id IN' => $partIds]
            );*/
            if(count($params['partIds']) == 1) {
                $deleteVal = array(['ParentChildRelations.parent_id'=>$parentId, 'ParentChildRelations.airframe_component_part_id in'=>$partIds]);
                $this->parentChildRelObj->deleteAll($deleteVal,false);
            } elseif(count($params['partIds']) > 1) {
                $deleteVal = array(['ParentChildRelations.parent_id'=>$parentId, 'ParentChildRelations.airframe_component_part_id in'=>$partIds]);
                $this->parentChildRelObj->deleteAll($deleteVal,false);
            }

            $result = array('status'=>'success', 'message'=>'Updated successfully.');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Upload airframe component part attachment
    public function uploadAirframeCompPartAttachment(){
        $postData = $this->request->getData();
        if(!empty($postData['file_name'])) 
        {
            $isvalidfile = 1;
            $arr_ext = array('pdf','doc', 'docx', 'xls', 'xlsx');
            
            $attachment = $postData['file_name']; 
            $name = $attachment->getClientFilename();
            $type = $attachment->getClientMediaType();
            $size = $attachment->getSize();
            $temp = $attachment->getStream()->getMetadata('uri');

            $ext = substr(strrchr($name , '.'), 1);
            
            if (!in_array($ext, $arr_ext)) {
                $isvalidfile = 0;
            }
            
            if($isvalidfile){
                $foldername = 'airframe_component_parts';
                $filelocation = WWW_ROOT . $foldername.'/' . $name;
                if(!isset($postData['source'])){
                    $tableName = 'airframe_component_part_files';
                    if(empty($postData['airframe_component_part_id'])){
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                        echo json_encode($result);die;
                    }
                }else{
                    $tableName = '';
                }

                if(!empty($postData['airframe_component_part_id'])){
                    $airCompParts = $this->AirframeComponentParts->get($postData['airframe_component_part_id']);
                    $postData['plane_id'] = $airCompParts->plane_id;
                }
                
                $tblrow = $this->AirframeComponentPart->uploadComponentPartFilesToServer($postData, $filelocation, $foldername, $tableName);
                if($tblrow != ''){
                    $result = array('status'=>'success', 'message'=>"Saved successfully.", 'tblrow'=>$tblrow);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
            }
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            echo json_encode($result);die;
        }
    }


    public function deleteAirframeCompPartAttachment(){
        if($this->request->is(['patch', 'post', 'put'])) 
        {
            $postData = $this->request->getData();
            
            if(!empty($postData['id'])){
                $attachments = $this->AirframeComponentPart->deleteAirframeComponentPartAttachments($postData['id']);
                if(!empty($attachments)){
                    $responseArr = $this->AirframeComponentPart->getAirframeComponentPartAttachments($postData['airframe_component_part_id']);
                    $tblrow = $responseArr['tblrow'];

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.", 'tblrow'=>$tblrow);
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
        }

        echo json_encode($result);die;
    }

    public function fetchAircraftComponentPopupHTML(){
        $this->viewBuilder()->setLayout('ajax');
        $planes = $this->Plane->getAllPlanes();

        $postData = $this->request->getData();
        $selectedAircraft = !empty($postData['aircraft']) ? $postData['aircraft'] : [];
        $selectedComponent = !empty($postData['component']) ? $postData['component'] : [];

        $components = [];
        if(!empty($selectedComponent) && !empty($selectedAircraft)){
            foreach($selectedAircraft as $planeId){
                $airComps = $this->AirframeComponents->find('all')->where(['AirframeComponents.plane_id' => $planeId])->select(['id', 'log_book', 'position'])->enableHydration(false)->toArray();
            
                $results = array();
                foreach ($airComps as $key => $value) {
                    if($value['log_book'] == 'Airframe') {
                        $results[$value['id']] = $value['log_book'];
                    } else {
                        $results[$value['id']] = $value['log_book'].$value['position'];
                    }
                }

                $components[$planeId] = $results;
            }
        }

        $this->set(compact('planes', 'selectedAircraft', 'selectedComponent', 'components'));
        $this->render('/element/aircraft_component_popup');
    }

    public function addNewRowAircraftCompntPartAjax(){
        $this->viewBuilder()->setLayout('ajax');
        $planes = $this->Plane->getAllPlanes();
        $postData = $this->request->getData();
 
        $counter = $postData['counter'];

        $aircraftId = '';
        $componentId = '';
        $components = [];

        $this->set(compact('planes', 'aircraftId', 'componentId', 'components', 'counter'));
        $this->render('/element/aircraft_component_add_row');
    }

    public function importAircraftCompPartExcel()
    {
        $this->request->allowMethod(['post']);

        $uploadedFile = $this->request->getData('aircraft_component_part_edit_file');
        if (!$uploadedFile || $uploadedFile->getError()) {
            throw new BadRequestException('No file uploaded or upload failed.');
        }

        // Get the temporary file path directly (no moving needed)
        $tmpFilePath = $uploadedFile->getStream()->getMetadata('uri');

        // Load the Excel file
        $spreadsheet = IOFactory::load($tmpFilePath);
        $sheet = $spreadsheet->getActiveSheet();
        $excelrows = $sheet->toArray();

        $authUserData = $this->Authentication->getResult()->getData();

        // Begin database transaction
        $batchId = Text::uuid();
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            foreach ($excelrows as $index => $row) {
                if ($index === 0) continue;
                //pr($row);exit;
                
                $row = array_map(function ($value) {
                                    return is_string($value) ? trim($value) : $value;
                                }, $row);

                $airCompParts = $this->AirframeComponentParts->find()
                    ->where(['id' => $row['0']])
                    ->first();
                
                if (!empty($airCompParts)) {
                    // Save old data for undo
                    $historyData = [
                        'table_name' => 'airframe_component_parts',
                        'row_id'     => $airCompParts->id,
                        'old_data'   => json_encode($airCompParts->toArray(), JSON_UNESCAPED_UNICODE),
                        'batch_id'   => $batchId
                    ];

                    $history = $this->ImportAirframeComponentPartHistories->newEmptyEntity();
                    $history = $this->ImportAirframeComponentPartHistories->patchEntity($history, $historyData);

                    if (!$this->ImportAirframeComponentPartHistories->save($history)) {
                        throw new \Exception("Failed to save history: " . json_encode($history->getErrors()));
                    }
                    
                    $postData = [];

                    $postData['part_id'] = $row['0'];
                    $postData['plane_id'] = $this->Plane->getPlaneId($row['1']);
                    $postData['airframe_component_id'] = $this->AirframeComponent->getAirframeComponentId($postData['plane_id'], $row['2']);
                    $postData['ad_sb_number'] = $row['3'];
                    $postData['requirement_source_id'] = $this->AirframeRequirementSource->getRequirementSourceId($row['4']);
                    $itemtypearr = $this->AirframeComponentPart->getItemTypeDetByTitle($row['5']);
                    $postData['item_type_id'] = $itemtypearr['id'] ?? '';
                    $postData['item_type'] = $itemtypearr['sort_title'] ?? '';
                    $postData['requirement_type_id'] = $this->AirframeRequirementType->getRequirementTypeId($row['6']);
                    $postData['requirement_type'] = $row['6'];
                    $postData['amendment'] = $row['7'];
                    $postData['issuing_authority_id'] = $this->AirframeIssuingAuthority->getIssuingAuthorityId($row['8']);
                    $postData['authority'] = $row['8'];
                    $postData['ata_code'] = $this->AtaCode->getATAId($row['9']);
                    $postData['disposition'] = $this->Disposition->getDispId($row['10']);
                    $postData['ad_sb_status'] = $this->AdsbStatus->adsbId($row['11']);
                    $postData['avg_man_hrs'] = $row['12'];
                    $postData['reference'] = $row['13'];
                    $postData['position_id'] = $this->Position->getPositionId($row['14']);
                    $postData['moc_id'] = $this->AirframeMoc->getMocsId($row['15']);
                    $postData['mfg_code'] = $row['16'];
                    $postData['description'] = $row['17'];
                    $postData['moc'] = $row['18'];
                    $postData['notes'] = $row['19'];
                    $postData['work_description'] = $row['20'];
                    $postData['tags'] = $row['21'];
                    $postData['last_cw_date'] = $row['22'];
                    $postData['last_cw_hrs'] = $row['23'];
                    $postData['last_cw_afl'] = $row['24'];
                    $postData['override'] = $row['25'];
                    $postData['eom'] = $row['26'];
                    $postData['next_due_date'] = $row['27'];
                    $postData['next_due_hrs'] = $row['28'];
                    $postData['next_due_afl'] = $row['29'];
                    /*$postData[''] = $row['30'];
                    $postData[''] = $row['31'];
                    $postData[''] = $row['32'];
                    $postData[''] = $row['33'];*/
                    $postData['tolerance_mos'] = $row['34'];
                    $postData['tolerance_days'] = $row['35'];
                    $postData['tolerance_hrs'] = $row['36'];
                    $postData['tolerance_afl'] = $row['37'];
                    $postData['alert_days'] = $row['38'];
                    $postData['alert_hrs'] = $row['39'];
                    $postData['alert_afl'] = $row['40'];
                    $postData['is_recThres'] = $row['41'];
                    $postData['recurring_mos'] = $row['42'];
                    $postData['recurring_days'] = $row['43'];
                    $postData['recurring_hrs'] = $row['44'];
                    $postData['recurring_afl'] = $row['45'];
                    $postData['is_recThres'] = empty($row['41']) ? $row['46'] : $row['41'];
                    $postData['threshold_mos'] = $row['47'];
                    $postData['threshold_days'] = $row['48'];
                    $postData['threshold_hrs'] = $row['49'];
                    $postData['threshold_afl'] = $row['50'];
                    $postData['required_frequency_mos'] = $row['51'];
                    $postData['required_frequency_days'] = $row['52'];
                    $postData['required_frequency_hrs'] = $row['53'];
                    $postData['required_frequency_afl'] = $row['54'];
                    $postData['adjustment_mos'] = $row['55'];
                    $postData['adjustment_days'] = $row['56'];
                    $postData['adjustment_hrs'] = $row['57'];
                    $postData['adjustment_afl'] = $row['58'];
                    $postData['part_number'] = $row['59'];
                    $postData['serial_number'] = $row['60'];
                    $postData['new_months'] = $row['61'];
                    $postData['new_hours'] = $row['62'];
                    $postData['new_landings'] = $row['63'];
                    $postData['overhaul_months'] = $row['64'];
                    $postData['overhaul_hours'] = $row['65'];
                    $postData['overhaul_landings'] = $row['66'];
                    $postData['repair_months'] = $row['67'];
                    $postData['repair_hours'] = $row['68'];
                    $postData['repair_landings'] = $row['69'];
                    $postData['work_card'] = $row['70'];
                    $postData['position'] = $row['71'];
                    $postData['version'] = $row['72'];
                    //$postData['last_revised_by'] = $row['73'];
                    $postData['admin_notes'] = $row['74'];
                    //pr($postData);exit;
                    $airCompParts = $this->AirframeComponentParts->patchEntity($airCompParts, $postData);
                    if ($this->AirframeComponentParts->save($airCompParts)) {
                        
                        //Post data process and calculate nextdue
                        $postData = $this->AirframeComponentPart->postDataProcess($postData);
                        
                        //Save related data in airframe_component_last_cw table
                        $postData['airframe_component_part_id'] = $airCompParts->id;
                        $postData['last_revised_by'] = $authUserData['id'];
                        $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                        
                        $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                        $this->airCompLastCwObj->save($partsDetails);

                        //Save history for undo
                        $history = $this->ImportAirframeComponentPartHistories->newEntity([
                            'table_name' => 'airframe_component_last_cw',
                            'row_id'     => $partsDetails->id,
                            'old_data'   => json_encode([]), // empty means this was a NEW row
                            'batch_id'   => $batchId
                        ]);
                        $this->ImportAirframeComponentPartHistories->saveOrFail($history);
                    }else{
                        throw new \Exception("Failed to save row {$index}: " . json_encode($airCompParts->getErrors()));
                    }
                }
            }

            // If loop finishes without errors → commit everything
            $connection->commit();
            $response = ['status' => 'success', 'message' => 'Excel imported successfully', 'batch_id' => $batchId];

        } catch (\Exception $e) {
            // Any failure here undoes ALL inserts
            $connection->rollback();
            $response = ['status' => 'error', 'message' => 'Import failed: ' . $e->getMessage()];
        }

        return $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode($response));
    }

    public function undoCompPartImportExcel($batchId)
    {
        $this->request->allowMethod(['post']);

        $history = $this->ImportAirframeComponentPartHistories->find()
            ->where(['batch_id' => $batchId])
            ->all();

        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            foreach ($history as $record) {
                $oldData = json_decode($record->old_data, true);

                if ($record->table_name === 'airframe_component_parts') {
                    $entity = $this->AirframeComponentParts->get($record->row_id);
                    $entity = $this->AirframeComponentParts->patchEntity($entity, $oldData);
                    $this->AirframeComponentParts->save($entity);
                }

                if ($record->table_name === 'airframe_component_last_cw') {
                    if (empty($oldData)) {
                        // Means this row was newly created → delete it on undo
                        $this->airCompLastCwObj->deleteOrFail(
                            $this->airCompLastCwObj->get($record->row_id)
                        );
                    } else {
                        // If it was updated instead of new (future-proofing)
                        $entity = $this->airCompLastCwObj->get($record->row_id);
                        $entity = $this->airCompLastCwObj->patchEntity($entity, $oldData);
                        $this->airCompLastCwObj->saveOrFail($entity);
                    }
                }
            }

            $connection->commit();
            $response = ['status' => 'success', 'message' => 'All imported Excel data has been undone successfully.'];
        } catch (\Exception $e) {
            $connection->rollback();
            $response = ['status' => 'error', 'message' => 'Undo failed: ' . $e->getMessage()];
        }

        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode($response));
    }

}