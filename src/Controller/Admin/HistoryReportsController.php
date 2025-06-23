<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

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
            $query['count'] = "SELECT count(itemhistory.id) AS count  FROM ".$requestData['filterBySection']." as itemhistory WHERE 1=1 ";

            $query['detail'] = "SELECT itemhistory.id, itemhistory.title, itemhistory.created, itemhistory.description, u.full_name FROM `".$requestData['filterBySection']."` as itemhistory join users as u on itemhistory.user_id = u.id WHERE 1=1 ";
        }else{
            $query['count'] = "SELECT count(itemhistory.id) AS count  FROM inventory_item_histories as itemhistory WHERE 1=1 ";

            $query['detail'] = "SELECT itemhistory.id, itemhistory.title, itemhistory.created, itemhistory.description, u.full_name FROM `inventory_item_histories` itemhistory join users u on itemhistory.user_id = u.id WHERE 1=1 ";
        }

        if(!empty($requestData['date_from']) && !empty($requestData['date_to'])){
            $date_from = str_replace('-', '/', $requestData['date_from']);
            $date_from = date("Y-m-d", strtotime($date_from));
            
            $date_to = str_replace('-', '/', $requestData['date_to']);
            $date_to = date("Y-m-d", strtotime($date_to));
            
            $querystr = ' and DATE(itemhistory.created) >="'.$date_from.'" and DATE(itemhistory.created) <="'.$date_to.'"';
            $query['count'] .= $querystr;
            $query['detail'] .= $querystr;
        }
        $requestData= $this->request->getData();
        //$cond = $this->InventoryFilter->inventoryCatalogFilter($requestData);
        //print_r($requestData);exit;
        //echo $cond;exit;
        $columns = array(
            0 => 'itemhistory.id',
            1 => 'itemhistory.title',
            2 => 'u.full_name',
            3 => 'itemhistory.created',
            4 => 'itemhistory.description'
        );

        $count = $query['count'];
        $detail = $query['detail'];
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
            $description = @unserialize($row['description']); 
            if ($description === false){
                $description = $row['description'];
            }else{
                $description = json_encode($description, JSON_PRETTY_PRINT);
            }
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
}