<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Entity;
use Cake\Chronos\ChronosInterface;

/**
 * Dashboard Controller
 */
class HistoryReportsController extends AppController
{   
    public function initialize():void {
        parent::initialize();
    }

    public function index(){
        $inventoryhistories = [];
        $this->set('inventoryhistories');
    }

    public function ajaxHistoryReportSearch(){
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('History Reports', $actionStatus))
            {
                $actionItems = $actionStatus['History Reports'];
            }
        }
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();

        $query = [];        
        if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
            parse_str($requestData['columns'][1]['search']['value'], $requestData);
        }
        if(!empty($requestData['filterBySection'])){
            $query['count'] = "SELECT count(userhistory.id) AS count  FROM ".$requestData['filterBySection']." as userhistory WHERE 1=1 ";

            $query['detail'] = "SELECT userhistory.id, userhistory.title, userhistory.created, userhistory.description, u.full_name FROM `".$requestData['filterBySection']."` as userhistory join users as u on userhistory.user_id = u.id WHERE 1=1 ";
        }else{
            $query['count'] = "SELECT count(userhistory.id) AS count  FROM inventory_item_histories as userhistory WHERE 1=1 ";

            $query['detail'] = "SELECT userhistory.id, userhistory.title, userhistory.created, userhistory.description, u.full_name FROM `user_histories` userhistory join users u on userhistory.user_id = u.id WHERE 1=1 ";
        }
        
        if(!empty($requestData['date_from']) && !empty($requestData['date_to'])){
            $date_from = str_replace('-', '/', $requestData['date_from']);
            $date_from = date("Y-m-d", strtotime($date_from));
            
            $date_to = str_replace('-', '/', $requestData['date_to']);
            $date_to = date("Y-m-d", strtotime($date_to));
            
            $querystr = ' and DATE(userhistory.created) >="'.$date_from.'" and DATE(userhistory.created) <="'.$date_to.'"';
            $query['count'] .= $querystr;
            $query['detail'] .= $querystr;
        }
        $requestData= $this->request->getData();
        //$cond = $this->InventoryFilter->inventoryCatalogFilter($requestData);
        //print_r($requestData);exit;
        //echo $cond;exit;
        $columns = array(
            0 => 'userhistory.id',
            1 => 'userhistory.title',
            2 => 'u.full_name',
            3 => 'userhistory.created',
            4 => 'userhistory.description'
        );

        $cond = '';
        if(!empty($authUserData['is_manager']) && !empty($authUserData['team_member_id'])){
            $teamIdsArray = array_map('intval', explode(',', $authUserData['team_member_id'])); // Ensure values are integers
            $teamIdsSql = '(' . implode(',', $teamIdsArray) . ')';
            
            $cond .= " AND (userhistory.user_id IN $teamIdsSql OR userhistory.user_id = " . (int)$authUserData['id'] . ")";
        }else if($authUserData['role_id'] != 1){
            $cond .= " AND userhistory.user_id = ".$authUserData['id'];
        }

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
        $length = $requestData['length'];

        $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
        $results = $conn->execute( $SQL )->fetchAll('assoc');
        
        $data = array();
        
        foreach ( $results as $row){
            /*$description = @unserialize($row['description']); 
            if ($description === false){
                $description = $row['description'];
            }else{
                $description = json_encode($description, JSON_PRETTY_PRINT);
            }*/

            $description = $this->safeUnserializeAndFormat($row['description']);
            
            $nestedData= [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["title"];
            $nestedData[] = $row["full_name"];
            $nestedData[] = date('m-d-Y H:i:s', strtotime($row['created']));
            $nestedData[] = '<a class="toggleplusminus"><i class="fa fa-plus"></i></a>'.$row['title'].'<pre class="history-detail-block hide-block">'.$description.'</pre>';

            $data[] = $nestedData;
        }

        $returndata = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
    
        echo json_encode($returndata);die;
    }

    public function safeUnserializeAndFormat($serialized) {
        try {
            $data = @unserialize($serialized);

            // unserialize might succeed but return an incomplete object
            if ($data === false) {
                return $serialized; // not a valid serialized string
            }

            // If it's a Cake Entity, safely convert to array
            if ($data instanceof Entity) {
                $array = $data->toArray();

                // Recursively walk and safely format Chronos/DateTime objects
                array_walk_recursive($array, function (&$value) {
                    if ($value instanceof ChronosInterface || $value instanceof \DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                    }
                });

                return json_encode($array, JSON_PRETTY_PRINT);
            }

            // If it's an array or object, JSON encode it directly
            if (is_array($data) || is_object($data)) {
                return json_encode($data, JSON_PRETTY_PRINT);
            }

            // Fallback
            return $data;

        } catch (\Throwable $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

}