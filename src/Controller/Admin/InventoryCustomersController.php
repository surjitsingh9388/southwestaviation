<?php
    
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use App\View\Helper\InventoryAircraftWorkOrderHelper;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Cake\Datasource\FactoryLocator;
    use Cake\I18n\FrozenTime;
    use Mpdf\Mpdf;

    class InventoryCustomersController extends AppController
    {
        protected \App\Model\Table\UsersTable $Users;
        protected \App\Model\Table\InventoryItemsTable $InventoryItems;
        protected \App\Model\Table\InventoriesTable $Inventories;
        protected \App\Model\Table\CustomerOTCAircraftsTable $CustomerOTCAircrafts;
        protected \App\Model\Table\CustomerAircraftContractRatesTable $CustomerAircraftContractRates;
        protected \App\Model\Table\CustomerAdditionalShippingAddressesTable $CustomerAdditionalShippingAddresses;
        protected \App\Model\Table\CustomerAircraftComplianceInspectionsTable $CustomerAircraftComplianceInspections;
        protected \App\Model\Table\CustomerAircraftComplianceAirframesTable $CustomerAircraftComplianceAirframes;
        protected \App\Model\Table\CustomerAircraftComplianceEnginesTable $CustomerAircraftComplianceEngines;
        protected \App\Model\Table\CustomerAircraftComplianceInspectionHistoriesTable $CustomerAircraftComplianceInspectionHistories;
        protected \App\Model\Table\CustomerAircraftMaintenanceOverviewsTable $CustomerAircraftMaintenanceOverviews;
        protected \App\Model\Table\CustomerAircraftMaintenanceEnginesTable $CustomerAircraftMaintenanceEngines;
        protected \App\Model\Table\CustomerAircraftMaintEngineCylHistoriesTable $CustomerAircraftMaintEngineCylHistories;
        protected \App\Model\Table\CustomerAircraftMaintenancePropsTable $CustomerAircraftMaintenanceProps;
        protected \App\Model\Table\CustomerAircraftMaintenanceAppliancesTable $CustomerAircraftMaintenanceAppliances;
        protected \App\Model\Table\CustomerAircraftMaintenanceAdsTable $CustomerAircraftMaintenanceAds;
        protected \App\Model\Table\CustomerAircraftMaintenanceNotesTable $CustomerAircraftMaintenanceNotes;
        protected \App\Model\Table\CustomerOTCInfoesTable $CustomerOTCInfoes;
        protected \App\Model\Table\CustomerOTCInfoInvoicesTable $CustomerOTCInfoInvoices;
        protected \App\Model\Table\CustomerOTCInfoInvoicePartsTable $CustomerOTCInfoInvoiceParts;
        protected \App\Model\Table\CustomerAircraftWorkOrdersTable $CustomerAircraftWorkOrders;
        protected \App\Model\Table\CustomerAircraftWOItemsTable $CustomerAircraftWOItems;
        protected \App\Model\Table\CustomerAircraftWOItemOverviewsTable $CustomerAircraftWOItemOverviews;
        protected \App\Model\Table\CustomerAircraftWOItemServicesTable $CustomerAircraftWOItemServices;
        protected \App\Model\Table\CustomerAircraftWOOSRInfoesTable $CustomerAircraftWOOSRInfoes;
        protected \App\Model\Table\CustomerAircraftWOOSRVendorsTable $CustomerAircraftWOOSRVendors;
        protected \App\Model\Table\CustomerAircraftWOOSRInfoPOesTable $CustomerAircraftWOOSRInfoPOes;
        protected \App\Model\Table\CustomerAircraftWOOSRPORemindersTable $CustomerAircraftWOOSRPOReminders;
        protected \App\Model\Table\CustomerAircraftWOOSRPOItemsTable $CustomerAircraftWOOSRPOItems;
        protected \App\Model\Table\CustomerAircraftWOATACodesTable $CustomerAircraftWOATACodes;
        protected \App\Model\Table\CustomerAircraftWOLaborKitsTable $CustomerAircraftWOLaborKits;
        protected \App\Model\Table\CustomerAircraftWOLogBookValueOverviewsTable $CustomerAircraftWOLogBookValueOverviews;
        protected \App\Model\Table\CustomerAircraftWOLogBookValueEnginesTable $CustomerAircraftWOLogBookValueEngines;
        protected \App\Model\Table\CustomerAircraftWOLogBookValuePropsTable $CustomerAircraftWOLogBookValueProps;
        protected \App\Model\Table\CustomerAircraftWOOptionGeneralInfoesTable $CustomerAircraftWOOptionGeneralInfoes;
        protected \App\Model\Table\CustomerAircraftWOOptionGenInfoDepositsTable $CustomerAircraftWOOptionGenInfoDeposits;
        protected \App\Model\Table\CustomerAircraftWOOptionMiscChargesTable $CustomerAircraftWOOptionMiscCharges;
        protected \App\Model\Table\CustomerAircraftWOOptionMiscFuelChargesTable $CustomerAircraftWOOptionMiscFuelCharges;
        protected \App\Model\Table\CustomerAircraftWOOptionPricingInfoesTable $CustomerAircraftWOOptionPricingInfoes;
        protected \App\Model\Table\CustomerAircraftWOOptionWarrantyInfoesTable $CustomerAircraftWOOptionWarrantyInfoes;
        protected \App\Model\Table\CustomerAircraftWOMessagesTable $CustomerAircraftWOMessages;
        protected \App\Model\Table\CustomerAircraftWOOptionExtraTaxesTable $CustomerAircraftWOOptionExtraTaxes;
        protected \App\Model\Table\CustomerAircraftWOOptionTaxInfoesTable $CustomerAircraftWOOptionTaxInfoes;
        protected \App\Model\Table\CustomerAircraftWOOptionBillingInfoesTable $CustomerAircraftWOOptionBillingInfoes;
        protected \App\Model\Table\CustomerAircraftWOItemPartsTable $CustomerAircraftWOItemParts;
        protected \App\Model\Table\InventoryToolsTable $InventoryTools;
        protected \App\Model\Table\CustomerAircraftWOItemToolsTable $CustomerAircraftWOItemTools;
        protected \App\Model\Table\InventoryCustomerAddressesTable $InventoryCustomerAddresses;
        protected \App\Model\Table\AircraftMaintenanceJetEnginesTable $AircraftMaintenanceJetEngines;
        protected \App\Model\Table\AircraftMaintenanceHelicopterOverviewsTable $AircraftMaintenanceHelicopterOverviews;
        protected \App\Model\Table\AircraftWOLogBookValueHelicopterOverviewsTable $AircraftWOLogBookValueHelicopterOverviews;
        protected \App\Model\Table\AircraftWOLogBookValueJetEnginesTable $AircraftWOLogBookValueJetEngines;
        protected \App\Model\Table\AircraftWOItemDiscrepancyHistoriesTable $AircraftWOItemDiscrepancyHistories;
        protected \App\Model\Table\AircraftWOItemCorrectiveActionHistoriesTable $AircraftWOItemCorrectiveActionHistories;
        protected \App\Model\Table\CustomerRepairOrderRatesTable $CustomerRepairOrderRates;
        protected \App\Model\Table\InventoryHistoriesTable $InventoryHistories;
        protected \App\Model\Table\AircraftWOItemSignoffsTable $AircraftWOItemSignoffs;
        protected \App\Model\Table\UserMenuItemsTable $UserMenuItems;
        protected \App\Model\Table\AircraftWOItemServiceLogsTable $AircraftWOItemServiceLogs;
        protected \App\Model\Table\UserPTORequestsTable $UserPTORequests;
        protected \App\Model\Table\StatementsTable $Statements;

        public function initialize():void {
            $this->Users                                            = $this->fetchTable('Users'); 
            $this->InventoryItems                                   = $this->fetchTable('InventoryItems'); 
            $this->Inventories                                      = $this->fetchTable('Inventories'); 
            $this->CustomerOTCAircrafts                             = $this->fetchTable('CustomerOTCAircrafts'); 
            $this->CustomerAircraftContractRates                    = $this->fetchTable('CustomerAircraftContractRates'); 
            $this->CustomerAdditionalShippingAddresses              = $this->fetchTable('CustomerAdditionalShippingAddresses'); 
            $this->CustomerAircraftComplianceInspections            = $this->fetchTable('CustomerAircraftComplianceInspections'); 
            $this->CustomerAircraftComplianceAirframes              = $this->fetchTable('CustomerAircraftComplianceAirframes'); 
            $this->CustomerAircraftComplianceEngines                = $this->fetchTable('CustomerAircraftComplianceEngines'); 
            $this->CustomerAircraftComplianceInspectionHistories    = $this->fetchTable('CustomerAircraftComplianceInspectionHistories'); 
            $this->CustomerAircraftMaintenanceOverviews             = $this->fetchTable('CustomerAircraftMaintenanceOverviews'); 
            $this->CustomerAircraftMaintenanceEngines               = $this->fetchTable('CustomerAircraftMaintenanceEngines'); 
            $this->CustomerAircraftMaintEngineCylHistories          = $this->fetchTable('CustomerAircraftMaintEngineCylHistories'); 
            $this->CustomerAircraftMaintenanceProps                 = $this->fetchTable('CustomerAircraftMaintenanceProps'); 
            $this->CustomerAircraftMaintenanceAppliances            = $this->fetchTable('CustomerAircraftMaintenanceAppliances'); 
            $this->CustomerAircraftMaintenanceAds                   = $this->fetchTable('CustomerAircraftMaintenanceAds'); 
            $this->CustomerAircraftMaintenanceNotes                 = $this->fetchTable('CustomerAircraftMaintenanceNotes'); 
            $this->CustomerOTCInfoes                                = $this->fetchTable('CustomerOTCInfoes'); 
            $this->CustomerOTCInfoInvoices                          = $this->fetchTable('CustomerOTCInfoInvoices'); 
            $this->CustomerOTCInfoInvoiceParts                      = $this->fetchTable('CustomerOTCInfoInvoiceParts');
            $this->CustomerAircraftWorkOrders                       = $this->fetchTable('CustomerAircraftWorkOrders');
            $this->CustomerAircraftWOItems                          = $this->fetchTable('CustomerAircraftWOItems');
            $this->CustomerAircraftWOItemOverviews                  = $this->fetchTable('CustomerAircraftWOItemOverviews');
            $this->CustomerAircraftWOItemServices                   = $this->fetchTable('CustomerAircraftWOItemServices');
            $this->CustomerAircraftWOOSRInfoes                      = $this->fetchTable('CustomerAircraftWOOSRInfoes');
            $this->CustomerAircraftWOOSRVendors                     = $this->fetchTable('CustomerAircraftWOOSRVendors');
            $this->CustomerAircraftWOOSRInfoPOes                    = $this->fetchTable('CustomerAircraftWOOSRInfoPOes');
            $this->CustomerAircraftWOOSRPOReminders                 = $this->fetchTable('CustomerAircraftWOOSRPOReminders');
            $this->CustomerAircraftWOOSRPOItems                     = $this->fetchTable('CustomerAircraftWOOSRPOItems');
            $this->CustomerAircraftWOATACodes                       = $this->fetchTable('CustomerAircraftWOATACodes');
            $this->CustomerAircraftWOLaborKits                      = $this->fetchTable('CustomerAircraftWOLaborKits');
            $this->CustomerAircraftWOLogBookValueOverviews          = $this->fetchTable('CustomerAircraftWOLogBookValueOverviews'); 
            $this->CustomerAircraftWOLogBookValueEngines            = $this->fetchTable('CustomerAircraftWOLogBookValueEngines'); 
            $this->CustomerAircraftWOLogBookValueProps              = $this->fetchTable('CustomerAircraftWOLogBookValueProps');
            $this->CustomerAircraftWOOptionGeneralInfoes            = $this->fetchTable('CustomerAircraftWOOptionGeneralInfoes');
            $this->CustomerAircraftWOOptionGenInfoDeposits          = $this->fetchTable('CustomerAircraftWOOptionGenInfoDeposits');
            $this->CustomerAircraftWOOptionMiscCharges              = $this->fetchTable('CustomerAircraftWOOptionMiscCharges');
            $this->CustomerAircraftWOOptionMiscFuelCharges          = $this->fetchTable('CustomerAircraftWOOptionMiscFuelCharges');
            $this->CustomerAircraftWOOptionPricingInfoes            = $this->fetchTable('CustomerAircraftWOOptionPricingInfoes');
            $this->CustomerAircraftWOOptionWarrantyInfoes           = $this->fetchTable('CustomerAircraftWOOptionWarrantyInfoes');
            $this->CustomerAircraftWOMessages                       = $this->fetchTable('CustomerAircraftWOMessages');
            $this->CustomerAircraftWOOptionExtraTaxes               = $this->fetchTable('CustomerAircraftWOOptionExtraTaxes');
            $this->CustomerAircraftWOOptionTaxInfoes                = $this->fetchTable('CustomerAircraftWOOptionTaxInfoes');
            $this->CustomerAircraftWOOptionBillingInfoes            = $this->fetchTable('CustomerAircraftWOOptionBillingInfoes');
            $this->CustomerAircraftWOItemParts                      = $this->fetchTable('CustomerAircraftWOItemParts');
            $this->InventoryTools                                   = $this->fetchTable('InventoryTools');
            $this->CustomerAircraftWOItemTools                      = $this->fetchTable('CustomerAircraftWOItemTools');
            $this->InventoryCustomerAddresses                       = $this->fetchTable('InventoryCustomerAddresses');
            $this->AircraftMaintenanceJetEngines                    = $this->fetchTable('AircraftMaintenanceJetEngines');
            $this->AircraftMaintenanceHelicopterOverviews           = $this->fetchTable('AircraftMaintenanceHelicopterOverviews');
            $this->AircraftWOLogBookValueHelicopterOverviews        = $this->fetchTable('AircraftWOLogBookValueHelicopterOverviews');
            $this->AircraftWOLogBookValueJetEngines                 = $this->fetchTable('AircraftWOLogBookValueJetEngines');
            $this->AircraftWOItemDiscrepancyHistories               = $this->fetchTable('AircraftWOItemDiscrepancyHistories');
            $this->AircraftWOItemCorrectiveActionHistories          = $this->fetchTable('AircraftWOItemCorrectiveActionHistories');
            $this->CustomerRepairOrderRates                         = $this->fetchTable('CustomerRepairOrderRates');
            $this->InventoryHistories                               = $this->fetchTable('InventoryHistories');
            $this->AircraftWOItemSignoffs                           = $this->fetchTable('AircraftWOItemSignoffs');
            $this->UserMenuItems                                    = $this->fetchTable('UserMenuItems');
            $this->AircraftWOItemServiceLogs                        = $this->fetchTable('AircraftWOItemServiceLogs');
            $this->UserPTORequests                                  = $this->fetchTable('UserPTORequests');
            $this->Statements                                       = $this->fetchTable('Statements');


            $this->loadComponent('Address');
            $this->loadComponent('InventoryFilter');
            $this->loadComponent('InventoryAttachment');
            $this->loadComponent('Inventory');
            $this->loadComponent('CustomerOTC');
            $this->loadComponent('AircraftWOItemHistory');
            $this->loadComponent('InventoryHistory');
            
            parent::initialize();
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer/OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer/OTC'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function ajaxInventoryCustomerSearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $requestData= $this->request->getData();
                
                $query = [];
                $query['count'] = "SELECT count(id) AS count  FROM inventory_customers WHERE 1=1 ";

                $query['detail'] = "SELECT invc.id, customer_name, name2, city, state, country, cellular_phone, c.name as country_name FROM `inventory_customers` invc left join countries c on invc.country = c.id WHERE 1=1 ";

                if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                    parse_str($requestData['columns'][1]['search']['value'], $requestData);
                }
                
                $cond = $this->InventoryFilter->inventoryCustomersFilter($requestData);
                $requestData= $this->request->getData();
                //echo $cond;exit;
                $columns = array(
                    0 => 'id',
                    1 => 'customer_name',
                    2 => 'name2',
                    3 => 'city',
                    4 => 'state',
                    5 => 'country_name',
                    6 => 'cellular_phone',
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
                
                $data = array();
                $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
                $defaultUOM = unserialize(DEFAULT_UOM);
                
                foreach ( $results as $row){
                    $nestedData= [];
                    $nestedData[] = $row["customer_name"].'<input type="hidden" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >';
                    $nestedData[] = $row["name2"];
                    $nestedData[] = $row["city"];
                    $nestedData[] = $row["state"];
                    $nestedData[] = $row["country_name"];
                    $nestedData[] = $row["cellular_phone"];
    
                    $data[] = $nestedData;
                }
    
                $roles = array(
                    "draw"            => intval( $requestData['draw'] ),
                    "recordsTotal"    => intval( $totalRecords ),
                    "recordsFiltered" => intval( $totalFiltered ),
                    "data"            => $data
                );
            
                echo json_encode($roles);die;
            }
        }

        public function saveCustomerInfo()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer/OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer/OTC'];
                }
            }
            $inventorycustomers = $this->InventoryCustomers->newEmptyEntity();
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;

                $whereArr = ['InventoryCustomers.customer_name'=>$postData['customer_name']];
                if(!empty($postData['customer_info_id'])){
                    $whereArr['id !='] = $postData['customer_info_id'];

                    $inventorycustomers = $this->InventoryCustomers->get($postData['customer_info_id']);
                    $postData['updated_by'] = $authUserData['id'];
                    $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                }else{
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created'] = new \Cake\I18n\FrozenTime('now');
                }
                //print_r($inventorycustomers);exit;
                $inventorycustomerdata = $this->InventoryCustomers->exists($whereArr);
                
                if(!$inventorycustomerdata){
                    $postData['status'] = isset($postData['status']) && $postData['status']!= '' ? $postData['status'] : '1';
                    $postData['use_dealer_price'] = isset($postData['use_dealer_price']) && $postData['use_dealer_price']!= '' ? $postData['use_dealer_price'] : '0';
                    $postData['show_on_invoice'] = isset($postData['show_on_invoice']) && $postData['show_on_invoice']!= '' ? $postData['show_on_invoice'] : '0';
                    $postData['taxable'] = isset($postData['taxable']) && $postData['taxable']!= '' ? $postData['taxable'] : '0';
                    $postData['country_on_printout'] = isset($postData['country_on_printout']) && $postData['country_on_printout']!= '' ? $postData['country_on_printout'] : '0';
                    $postData['ship_country_on_printout'] = isset($postData['ship_country_on_printout']) && $postData['ship_country_on_printout']!= '' ? $postData['ship_country_on_printout'] : '0';
                    $postData['notes_on_wo_create'] = isset($postData['notes_on_wo_create']) && $postData['notes_on_wo_create']!= '' ? $postData['notes_on_wo_create'] : '0';
                    $postData['change_shop_supplier'] = isset($postData['change_shop_supplier']) && $postData['change_shop_supplier']!= '' ? $postData['change_shop_supplier'] : '0';
                    $postData['requires_owner_authorization'] = isset($postData['requires_owner_authorization']) && $postData['requires_owner_authorization']!= '' ? $postData['requires_owner_authorization'] : '0';
                    $postData['country_for_tax_rate'] = isset($postData['country_for_tax_rate']) && $postData['country_for_tax_rate']!= '' ? $postData['country_for_tax_rate'] : '0';

                    $inventorycustomers = $this->InventoryCustomers->patchEntity($inventorycustomers, $postData);

                    if ($this->InventoryCustomers->save($inventorycustomers)) {
                        $id = $inventorycustomers->id;

                        $this->Flash->success(__('Customer/OTC has been saved.'));
                        
                    }else{
                        $this->Flash->error(__('Customer/OTC could not be saved. Please, try again.'));
                        return $this->redirect(['action' => 'index']);
                    }
                }else{
                    $this->Flash->error(__('Customer/OTC '.$postData['customer_name'].' already exists.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            $id = $inventorycustomers->id;
            return $this->redirect(['action' => 'customerinfo', $id]);
        }

        public function saveCustomerInfoNotes(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                $inventorycustomers = $this->InventoryCustomers->newEmptyEntity();

                $inventorycustomers = $this->InventoryCustomers->get($postData['customer_id']);
                $inventorycustomers = $this->InventoryCustomers->patchEntity($inventorycustomers, $postData);
                if ($this->InventoryCustomers->save($inventorycustomers)) {
                    $response = ['status'=>'success', 'message'=>''];
                }else{
                    $response = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($response);die;
            }
        }

        public function saveCustomerInfoMedia(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                if (!empty($postData['customer_id']) && !empty($postData['filenames'])) {
                    $this->InventoryAttachment->saveInvCustomerAttachment($postData['customer_id'], $postData);

                    $response = ['status'=>'success', 'message'=>''];
                }else{
                    $response = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($response);die;
            }
            
        }

        public function bulkMediaUpload(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt','mp4');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventorycustomers';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadSelectedFilesToServer($postData, $filelocation, $foldername);
                    
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

        public function deleteCustomerInfoMedia(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInvCustomerAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function deleteCustomerOTC($id = null){
            
            $this->request->allowMethod(['post', 'delete']);
            $this->autoRender = false;

            $customer = $this->InventoryCustomers->get($id);

            if ($this->InventoryCustomers->delete($customer)) {
                $result = array('status'=>'success', 'message'=>"Customer deleted successfully.");
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }
            echo json_encode($result);die;
        }

        public function customerinfo($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer/OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer/OTC'];
                }
            }
            $inventorycustomers = $this->InventoryCustomers->get($id);

            if(empty($inventorycustomers)){
                return $this->redirect(['action' => 'index']);
            }

            $customerOTCPageInfo = $this->CustomerOTC->getCustomerOTCPageInfo($id);
            extract($customerOTCPageInfo);

            $countries = $this->Address->getCountryList();
            //echo "<pre>";print_r($countries);exit;
            $aircraftmakes = $this->CustomerOTC->getAircraftMakeList();

            $aircraftregdet = $this->CustomerOTCAircrafts->find('all')->where(['customer_id'=>$id])->select(['CustomerOTCAircrafts.id', 'CustomerOTCAircrafts.aircraft_registration_number']);

            $customerotcaircrafts = $this->CustomerOTCAircrafts->find('all')->where(['customer_id'=>$id])->select($this->CustomerOTCAircrafts)->first();

            $aircraftmodels = [];
            if(!empty($customerotcaircrafts->aircraft_make_id)){
                $aircraftmodels = $this->CustomerOTC->getAircraftModelList($customerotcaircrafts->aircraft_make_id);
            }
            //echo "<pre>";print_r($aircraftmodels);exit;

            $customerotcinfoes = $this->CustomerOTCInfoes->find('all')->where(['customer_id'=>$id])->select($this->CustomerOTCInfoes)->first();
            if(empty($customerotcinfoes)){
                $customerotcinfoes = $this->CustomerOTCInfoes->newEmptyEntity();
            }
            
            $invoiceparthisttblrow = $this->CustomerOTC->OTCInvoicePartHistHTML($id);

            $customeraddrdropdown = $this->CustomerOTC->getCustomerShippingAddrDropdown($id);

            $customerInfoMedia = $this->CustomerOTC->getCustomerInfoAttachment($id);

            $this->set(compact('inventorycustomers', 'actionItems', 'countries', 'userlist', 'created_by', 'clientphone', 'aircraftmakes', 'aircraftregdet', 'customerotcaircrafts', 'aircraftmodels', 'customerotcinfoes', 'invoiceparthisttblrow', 'repairorderhistory', 'aircraftworkorderdata', 'customeraddrdropdown', 'repairorderrates', 'customerInfoMedia'));
        }

        public function getInvItemsWithInventoriesList(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $statusarr = array('1');
                $postData = $this->request->getData();
                
                if(isset($postData['inventory_item_id']) && !empty($postData['inventory_item_id'])){
                    $inventoryitems = $this->InventoryItems->find('all')->where(['InventoryItems.id'=>$postData['inventory_item_id'], 'InventoryItems.status'=>'1'])->select($this->InventoryItems)->select(['inv.id', 'inv.conditions', 'inv.serial_no', 'inv.qty', 'inv.status'])
                    ->join([
                        'inv' => [
                            'table' => 'inventories',
                            'type' => 'LEFT',
                            'conditions' => 'inv.inventory_item_id = InventoryItems.id',
                        ]
                    ]);
                    
                    if ($inventoryitems->count() > 0) {
                        $conditions = [];
                        //$serialno = [];
                        $inventoryitemlist = [];
                        $inventoryconditions = unserialize(INVENTORY_CONDITION);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $qty = 0;
                        foreach($inventoryitems as $invitm){
                            if(in_array($invitm['inv']['status'], $statusarr)){
                                if(!empty($invitm['inv']['conditions'])){
                                    $conditions[$invitm['inv']['conditions']] = $inventoryconditions[$invitm['inv']['conditions']];
                                }
                                /*if(!empty($invitm['inv']['serial_no'])){
                                    $serialno[$invitm['inv']['id']] = $invitm['inv']['serial_no'];
                                }*/
                                
                                $qty += $invitm['inv']['qty'];
                            }
                            $invitm->default_uom = $defaultUOM[$invitm->default_uom];
                            $inventoryitemlist = $invitm;
                        }
                        
                        $inventoryitemlist->qty = $qty;
                        $conditions = array_unique($conditions);
                        //$serialno = array_unique($serialno);
                        $inventoryitems = $inventoryitemlist;

                        $result = array('status'=>'success', 'message'=>"", 'inventoryitems'=>$inventoryitems, 'conditions'=>$conditions);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function getInventoryDetByCondition(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $statusarr = array('1', '2', '7', '9', '12');
                $postData = $this->request->getData();
                
                if(isset($postData['conditions']) && !empty($postData['conditions'])){
                    $serialno = $this->Inventory->getAllInventoriesSerialNoByCondId($postData['inventory_item_id'], $postData['conditions']);

                    $result = array('status'=>'success', 'message'=>"", 'serialno'=>$serialno);
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function uploadWOItemFiles(){
            
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
                    $foldername = 'inventorycustomers';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tableName = 'customer_aircraft_wo_item_file';

                    $tblrow = $this->InventoryAttachment->uploadCustWOFilesToServer($postData, $filelocation, $foldername, $tableName);
                    
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

        public function uploadWOItemPhotos(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('bmp','jpg','jpeg', 'png','tif');
                
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
                    $foldername = 'inventorycustomers';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tableName = 'customer_aircraft_wo_item_photo';

                    $tblrow = $this->InventoryAttachment->uploadCustWOFilesToServer($postData, $filelocation, $foldername, $tableName);
                    
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

        public function fetchCustomerOTCPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                
                $customer_id = isset($postData['customer_id']) ? $postData['customer_id'] : '';
                $aircraft_id = isset($postData['aircraft_id']) ? $postData['aircraft_id'] : '';
                $sessionUser = $this->request->getSession()->read('Auth');;
                $section = $postData['section'];
                $authUserData = $this->Authentication->getResult()->getData();

                if(empty($aircraft_id) && !empty($postData['work_order_id'])){
                    $workorderdet = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
                    $aircraft_id = $workorderdet->aircraft_id;
                }
                
                $countries = $this->Address->getCountryList();
                $customerOTCPageInfo = $this->CustomerOTC->getCustomerOTCPageInfo($customer_id, $aircraft_id);
                extract($customerOTCPageInfo);
                
                $this->set(compact('inventorycustomers', 'countries', 'userlist', 'created_by', 'clientphone', 'aircraft_id', 'aircraftregdetail', 'sessionUser', 'customer_id'));
                
                $fileName = '/element/InventoryPopup/customer_otc/';
                if($section == 'upload_new_cust_media'){
                    $fileName .= 'inventory_customer_upload_media';
                    $customerInfoMedia = $this->CustomerOTC->getCustomerInfoAttachment($customer_id);
                    $this->set(compact('customerInfoMedia'));
                }else if($section == 'new_cust_note'){
                    $fileName .= 'inventory_customer_add_notes';
                }else if($section == 'cust_otc_aircraft_maintenance'){
                    $fileName .= 'inventory_customer_aircarft_maintenance';

                    if(!empty($postData['clicksection'])){
                        $engine_type = !empty($aircraftregdetail->aircraft_engine_type) ? $aircraftregdetail->aircraft_engine_type : '1';
                    }else{
                        $engine_type = !empty($postData['engine_type']) ? $postData['engine_type'] : '1';
                        
                        if($aircraftregdetail->aircraft_engine_type != $engine_type){
                            $res = $this->CustomerOTCAircrafts->updateAll(
                                array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'aircraft_engine_type' => $engine_type),
                                array('id' => $postData['aircraft_id'])
                            );
                        }
                    }
                    $postData['engine_type'] = !empty($postData['engine_type']) ? $postData['engine_type'] : $engine_type;

                    $componentResp = $this->CustomerOTC->getAircraftMaintenanceTabData($postData);
                    extract($componentResp);

                    if(empty($aircraftmaintoverview)){
                        if($engine_type != '5'){
                            $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->newEmptyEntity();
                        }else{
                            $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->newEmptyEntity();
                        }
                    }
                    
                    if(empty($aircraftmaintengine) && $engine_type != '5'){
                        if($engine_type == '1' || $engine_type == '2'){
                            $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->newEmptyEntity();
                        }else{
                            $aircraftmaintengine = $this->AircraftMaintenanceJetEngines->newEmptyEntity();
                        }
                    }

                    $aircraftmaintenginecylhistory = $this->CustomerAircraftMaintEngineCylHistories->newEmptyEntity();

                    if(empty($aircraftmaintprops)){
                        $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->newEmptyEntity();
                    }

                    $aircraftmaintappliances = $this->CustomerAircraftMaintenanceAppliances->newEmptyEntity();
                    $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->newEmptyEntity();

                    if(empty($aircraftmaintnotes)){
                        $aircraftmaintnotes = $this->CustomerAircraftMaintenanceNotes->newEmptyEntity();
                    }
                    
                    
                    $this->set(compact('aircraftmaintoverview', 'aircraftmaintengine', 'aircraftmaintenginecylhistory', 'aircraftmaintenginecylhistorylist', 'aircraftmaintprops', 'aircraftmaintappliances','aircraftmaintapplianceslist', 'aircraftmaintadslist', 'aircraftmaintads', 'aircraftmaintnotes', 'engine_type', 'aircraftregdetail', 'section'));

                }else if($section == 'cust_otc_aircraft_compliance'){
                    $fileName .= 'inventory_customer_aircarft_compliance';
                    $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->newEmptyEntity();
                    $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->newEmptyEntity();
                    $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->newEmptyEntity();

                    $this->set(compact('aircraftcomplinspections', 'aircraftcomplairframes', 'aircraftcomplengines'));
                }else if($section == 'aircraft_create_wo_btn'){
                    $fileName .= 'inventory_aircraft_create_work_order';
                    $this->openAircraftWorkOrderPopup($customerOTCPageInfo, $postData);

                }else if($section == 'add_new_aircraft_btn'){
                    $fileName .= 'inventory_new_aircraft_add';
                }else if($section == 'cust_addl_ship_addr_btn'){
                    $fileName .= 'customer_addl_shipping_address';
                    $customerAddlShipAddrList = $this->CustomerOTC->getAllCustAddlShippingAddress($customer_id);

                    $this->set(compact('customerAddlShipAddrList'));
                }else if($section == 'cust_add_ship_addr_btn'){
                    $fileName .= 'customer_add_shipping_address';
                }else if($section == 'aircraft_maint_update_times_btn'){
                    if($aircraftregdetail->aircraft_engine_type != '5'){
                        $fileName .= 'aircraft_maintenance_update_time';

                        $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceOverviews)->first();
                    }else{
                        $fileName .= 'aircraft_maintenance_update_helicopter_time';

                        $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceOverviews)->first();
                    }
                    
                    $this->set(compact('aircraftmaintoverview'));
                }else if($section == 'aircraft_view_contract_price_btn'){
                    $fileName .= 'aircraft_contract_pricing_add';
                    $aircraftContractRates = $this->CustomerOTC->getAllAircraftContractRate($aircraft_id);

                    $this->set(compact('aircraftContractRates'));
                }else if($section == 'contract_price_dept_add_btn'){
                    $fileName .= 'aircraft_contract_rate_add';
                    $customercontractrates = $this->CustomerAircraftContractRates->newEmptyEntity();
                    if(!empty($postData['contract_rate_id'])){
                        $contract_rate_id = $postData['contract_rate_id'];
                        $customercontractrates = $this->CustomerAircraftContractRates->get($contract_rate_id);
                    }
                    $this->set(compact('customercontractrates'));
                }else if($section == 'customer_aircraft_info_more_btn'){
                    $fileName .= 'customer_aircraft_info_more';
                }else if($section == 'aircraft_wo_move_items'){
                    $fileName .= 'aircraft_wo_move_item';

                    $work_order_id = $postData['work_order_id'];
                    $aircraftwoitems = $this->CustomerAircraftWOItems->find('all', array('order'=>'wo_item_position ASC'))
                                                                        ->where(['work_order_id'=>$work_order_id])
                                                                        ->select($this->CustomerAircraftWOItems)
                                                                        ->select(['wo_item_overviews.wo_category', 'wo_item_overviews.wo_grouping', 'wo_item_overviews.labor_kit_name', 'wo_item_overviews.warranty'])
                                                                        ->join([
                                                                            'wo_item_overviews' => [
                                                                                'table' => 'customer_aircraft_wo_item_overviews',
                                                                                'type' => 'LEFT',
                                                                                'conditions' => 'wo_item_overviews.wo_item_id = CustomerAircraftWOItems.id',
                                                                            ]
                                                                        ]);

                    $this->set(compact('aircraftwoitems'));
                }else if($section == 'aircraft_wo_sign_offs'){
                    $fileName .= 'aircraft_wo_signoff';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $wodetails = $this->CustomerAircraftWorkOrders->get($work_order_id);
                    $aircraftwoitems = $this->CustomerAircraftWOItems->find('all', array('order'=>'wo_item_position ASC'))
                                                                        ->where(['work_order_id'=>$work_order_id])
                                                                        ->select($this->CustomerAircraftWOItems);
                                                                        
                    $aircraftwoitemsingle = [];
                    foreach($aircraftwoitems as $key=>$items){
                        if($items['id'] == $wo_item_id){
                            $aircraftwoitemsingle = $items;
                            break;
                        }
                    }
                    
                    $wosignoffitems = $this->CustomerOTC->getWOItemSignOff($aircraftwoitemsingle->id);
                    
                    $woitemsignoffdet = [];
                    if(!empty($wosignoffitems)){
                        foreach($wosignoffitems as $row){
                            $woitemsignoffdet[$row['signoff_category']] = $row;
                        }
                    }
                    $this->set(compact('aircraftwoitems', 'wodetails', 'woitemsignoffdet', 'wo_item_id'));
                }else if($section == 'add_wo_technician_btn'){
                    $fileName .= 'aircraft_wo_technician_add';

                    $aircraftwoitemserviceslist = $this->CustomerAircraftWOItemServices->find('all', array('order'=>'CustomerAircraftWOItemServices.id DESC'))
                                    ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$postData['wo_item_id']])
                                    ->select(['repair_technician']);
                    $wotechnicianids = [];
                    if($aircraftwoitemserviceslist->count() > 0){
                        foreach($aircraftwoitemserviceslist as $services){
                            $wotechnicianids[] = $services['repair_technician'];
                        }
                    }
                    $this->set(compact('wotechnicianids'));
                }else if($section == 'aircarft_wo_add_outside_repair'){
                    $fileName .= 'edit_outstanding_repair';

                    $wooutstandingoutside = $this->CustomerAircraftWOOSRInfoes->newEmptyEntity();
                    if(!empty($postData['wo_osr_id'])){
                        $wooutstandingoutside = $this->CustomerAircraftWOOSRInfoes->get($postData['wo_osr_id']);
                    }
                    $wo_item_id = $postData['wo_item_id'];
                    $work_order_id = $postData['work_order_id'];
                    $wodetails = $this->CustomerAircraftWorkOrders->get($work_order_id);
                    $inventoryvendors = $this->CustomerOTC->getWOOSRVendorList();
                    $woitemdata = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);

                    $this->set(compact('wooutstandingoutside', 'inventoryvendors', 'wo_item_id', 'work_order_id', 'wodetails', 'woitemdata'));
                }else if($section == 'aircraft_wo_part_add_btn'){
                    $fileName .= 'aircraft_wo_part_add';

                    $invpartnumbers = $this->CustomerOTC->getWOItemPartNumberList();

                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->newEmptyEntity();
                    $conditions = [];
                    $serialno = [];
                    $inventoryconditions = unserialize(INVENTORY_CONDITION);
                    
                    if(!empty($postData['wo_item_part_id'])){
                        $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->get($postData['wo_item_part_id']);
                        $inventoriesdata = $this->Inventories->find('all')->where(['Inventories.inventory_item_id'=>$aircraftwoitemparts->part_number])->select(['id', 'conditions', 'serial_no', 'status']);
                        
                        $statusarr = array('1', '2', '7', '9', '12');
                        foreach($inventoriesdata as $inventories){
                            if(in_array($inventories['status'], $statusarr)){
                                if(!empty($inventories['conditions'])){
                                    $conditions[$inventories['id']] = $inventoryconditions[$inventories['conditions']];
                                }
                                if(!empty($inventories['serial_no'])){
                                    $serialno[$inventories['id']] = $inventories['serial_no'];
                                }
                            }
                        }
                        
                        $conditions = array_unique($conditions);
                        $serialno = array_unique($serialno);
                    }
                    $wo_item_id = $postData['wo_item_id'];

                    $vendorlist = [];
                    $locationlist = [];

                    $this->set(compact('invpartnumbers', 'aircraftwoitemparts', 'wo_item_id', 'serialno', 'conditions', 'vendorlist', 'locationlist'));
                }else if($section == 'wo_osr_send_msg_btn'){
                    $fileName .= 'aircraft_wo_osr_send_msg';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $work_order_no = $postData['work_order_no'];
                    $wo_item_no = $postData['wo_item_no'];

                    $this->set(compact('wo_item_id', 'work_order_id', 'work_order_no', 'wo_item_no'));
                }else if($section == 'wo_osr_move_item_btn'){
                    $fileName .= 'wo_osr_move_item';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $wo_item_no = $postData['wo_item_no'];
                    $osr_info_id = $postData['osr_info_id'];

                    $this->set(compact('wo_item_id', 'work_order_id', 'wo_item_no', 'osr_info_id'));
                }else if($section == 'wo_move_item_file_btn'){
                    $fileName .= 'wo_move_item_file';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $wo_item_no = $postData['wo_item_no'];
                    $wo_move_item_file_id = $postData['wo_move_item_file_id'];
                    $wo_move_item_file = $postData['wo_move_item_file'];

                    $this->set(compact('wo_item_id', 'work_order_id', 'wo_item_no', 'wo_move_item_file_id', 'wo_move_item_file'));
                }else if($section == 'wo_move_item_photo_btn'){
                    $fileName .= 'wo_move_item_photo';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $wo_item_no = $postData['wo_item_no'];
                    $wo_move_item_photo_id = $postData['wo_move_item_photo_id'];
                    $wo_move_item_photo = $postData['wo_move_item_photo'];

                    $this->set(compact('wo_item_id', 'work_order_id', 'wo_item_no', 'wo_move_item_photo_id', 'wo_move_item_photo'));
                }else if($section == 'aircraft_wo_tool_add_btn'){
                    $fileName .= 'aircraft_wo_tool_add';

                    $wo_item_id = $postData['wo_item_id'];
                    $toollists = $this->InventoryTools->find('all')->select(['id', 'tool_name']);
                    $tooldropdown = [];
                    foreach($toollists as $tools){
                        $tooldropdown[$tools['id']] = $tools['tool_name'];
                    }

                    $woitemtools = $this->CustomerAircraftWOItemTools->newEmptyEntity();

                    $this->set(compact('tooldropdown', 'wo_item_id', 'woitemtools'));
                }else if($section == 'create_otc_invoice_btn'){
                    $fileName .= 'confirm_create_otc_invoice';
                }else if($section == 'aircraft_upload_media_btn'){
                    $fileName .= 'aircraft_upload_media';

                    $aircraftInfoMedia = $this->CustomerOTC->getAircraftInfoAttachment($aircraft_id);
                    $this->set(compact('aircraftInfoMedia'));
                }else if($section == 'aircraft_compliance_list'){
                    $fileName .= 'aircraft_compliance_list';

                    $aircraftComplianceList = $this->CustomerOTC->getAircraftComplianceList($aircraft_id);
                    $this->set(compact('aircraftComplianceList'));
                }else if($section == 'aircraft-maint-eng-cyl'){
                    $fileName .= 'aircraft_maint_eng_cyldate';
                }else if($section == 'customer_list'){
                    $fileName .= 'customer_list';
                    $customerlists = $this->CustomerOTC->getAllCustomerList();
                    $this->set(compact('customerlists'));
                }else if($section == 'confirm_create_otc_invoice_btn'){
                    $fileName .= 'create_otc_invoice';
                    $otcinfotblrow = '';
                    $totalpartsubtotal = 0;
                    if(!empty($postData['otc_invoice_id'])){
                        $otfinfodata = $this->CustomerOTC->allPartOnInvoiceHTML($postData['otc_invoice_id']);
                        $otcinfotblrow = $otfinfodata['tblrow'];
                        $totalpartsubtotal = $otfinfodata['totalpartsubtotal'];

                        $otcinfoinvoices = $this->CustomerOTCInfoInvoices->get($postData['otc_invoice_id']);
                    }else{
                        $invoice_number = $this->CustomerOTC->getOTCInvoiceNumber();
                        $otcinfoinvoices = $this->CustomerOTCInfoInvoices->newEmptyEntity();
                        $postData = [];
                        $postData['customer_id'] = $customer_id;
                        $postData['otc_invoice_no'] = $invoice_number;
                        $postData['invoice_date_created'] = new \Cake\I18n\FrozenTime('now');
                        $postData['invoice_created_by'] = $created_by;
                        $postData['invoice_status'] = '1';
                        $postData['otc_invoice_no'] = $invoice_number;
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $otcinfoinvoices = $this->CustomerOTCInfoInvoices->patchEntity($otcinfoinvoices, $postData);
                        
                        $this->CustomerOTCInfoInvoices->save($otcinfoinvoices);
                    }
                    
                    $this->set(compact('otcinfoinvoices', 'otcinfotblrow', 'totalpartsubtotal'));
                }else if($section == 'create_otc_invoice_ad_part_btn'){
                    $fileName .= 'customer_otc_invoice_add_part';

                    $inventoryitemlist = $this->Inventory->getAllInventoryItems();
                    $inventoryitemdropdowndata = [];
                    foreach($inventoryitemlist as $inventoryitem){
                        $inventoryitemdropdowndata[$inventoryitem->id] = $inventoryitem->part_number;
                    }

                    $conditions = [];
                    $serialno = [];
                    $otcinfoinvoiceparts = $this->CustomerOTCInfoInvoiceParts->newEmptyEntity();
                    if(!empty($postData['otc_invoice_part_id'])){
                        $otcinfoinvoiceparts = $this->CustomerOTCInfoInvoiceParts->get($postData['otc_invoice_part_id']);
                        $conditions = $this->Inventory->getAllInventoriesConditions($otcinfoinvoiceparts['part_number']);
                        $serialno = $this->Inventory->getAllInventoriesSerialNoByCondId($postData['otc_invoice_part_id'], $otcinfoinvoiceparts['part_conditions']);
                    }

                    $this->set(compact('otcinfoinvoiceparts', 'inventoryitemdropdowndata', 'conditions', 'serialno'));
                }else if($section == 'add_wo_services_note_btn'){
                    $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->newEmptyEntity();
                    $fileName .= 'aircraft_wo_services_note';
                    if(!empty($postData['wo_services_id'])){
                        $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->get($postData['wo_services_id']);
                    }
                    $this->set(compact('aircraftwoitemservices'));
                }else if($section == 'aircraft_wo_osr_vendor'){
                    $fileName .= 'aircraft_wo_osr_vendor';

                    $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->newEmptyEntity();
                    if(!empty($postData['vendor_id'])){
                        $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->get($postData['vendor_id']);
                    }
                    $this->set(compact('aircraftwoosrvendors'));
                }else if($section == 'aircraft_create_new_wo_osr_vendor'){
                    $fileName .= 'aircraft_create_new_wo_osr_vendor';
                }else if($section == 'aircraft_wo_osr_vendor_list'){
                    $fileName .= 'aircraft_wo_osr_vendor_list';
                    $woosrvendorlist = $this->CustomerAircraftWOOSRVendors->find('all')->where(['status'=>'1'])->select($this->CustomerAircraftWOOSRVendors);
                    
                    $this->set(compact('woosrvendorlist'));
                }else if($section == 'aircraft_wo_osr_vendor_media'){
                    $fileName .= 'aircraft_wo_osr_vendor_media';
                    $osr_vendor_id = $postData['osr_vendor_id'];
                    $osrVendorInfoMedia = $this->CustomerOTC->getWOOSRVendorAttachment($osr_vendor_id);
                    $this->set(compact('osrVendorInfoMedia', 'osr_vendor_id'));
                }else if($section == 'aircraft_wo_osr_service_po'){
                    $fileName .= 'aircraft_wo_osr_service_po';
                    $woosrinfopo = [];
                    $allserviceitemspo = [];

                    if(!empty($postData['osr_purchase_order_no'])){
                        $woosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->find('all')->where(['po_no'=>$postData['osr_purchase_order_no']])->select($this->CustomerAircraftWOOSRInfoPOes)->first();

                        $allserviceitemspo = $this->CustomerOTC->getAllServiceItemOnPO($woosrinfopo['id']);
                    }
                    $woosrvendorlist = $this->CustomerOTC->getWOOSRVendorDataAndPhones();
                    extract($woosrvendorlist);
                    $po_created_by = $authUserData['id'];
                    $this->set(compact('woosrinfopo', 'woosrvendordata', 'woosrvendorphones', 'allserviceitemspo', 'po_created_by'));
                }else if($section == 'aircraft_wo_osr_po_tracking_num'){
                    $fileName .= 'aircraft_wo_osr_po_tracking_num';

                    $woosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->get($postData['osr_infopoes_id']);
                    $this->set(compact('woosrinfopo'));
                }else if($section == 'aircraft_wo_osr_service_po_notes'){
                    $fileName .= 'aircraft_wo_osr_service_po_notes';

                    $woosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->get($postData['osr_infopoes_id']);
                    $this->set(compact('woosrinfopo'));
                }else if($section == 'aircraft_wo_osr_po_checkin_labor'){
                    $fileName .= 'aircraft_wo_osr_po_checkin_labor';

                    $woosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->get($postData['osr_infopoes_id']);
                    $woosrinfo = $this->CustomerAircraftWOOSRInfoes->find('all')->where(['osr_purchase_order_no'=>$woosrinfopo['po_no']])->select($this->CustomerAircraftWOOSRInfoes)->first();
                    $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->find('all')->where(['osr_po_id'=>$postData['osr_infopoes_id']])->select($this->CustomerAircraftWOOSRPOItems)->first();
                    
                    $woosrvendordata = $this->CustomerOTC->getWOOSRVendorList();
                    $allserviceitemspo = $this->CustomerOTC->getAllServiceItemOnPO($woosrinfopo['id']);

                    $this->set(compact('woosrinfopo', 'woosrvendordata', 'allserviceitemspo', 'woosrinfo', 'woosrpoitems'));
                }else if($section == 'aircraft_wo_osr_po_media'){
                    $fileName .= 'aircraft_wo_osr_po_media';
                    $osr_info_po_id = $postData['osr_info_po_id'];
                    $osrPurchaseOrderMedia = $this->CustomerOTC->getWOOSRPurcahseOrderAttachment($osr_info_po_id);
                    $this->set(compact('osrPurchaseOrderMedia', 'osr_info_po_id'));
                }else if($section == 'aircraft_wo_osr_po_reminder'){
                    $fileName .= 'aircraft_wo_osr_po_reminder';
                    $wo_osr_po_id = $postData['osr_info_po_id'];
                    $osrPOReminders = $this->CustomerAircraftWOOSRPOReminders->find('all')->where(['wo_osr_po_id'=>$wo_osr_po_id])->select($this->CustomerAircraftWOOSRPOReminders);
                    $osrPOReminderlist = [];
                    foreach($osrPOReminders as $reminder){
                        $osrPOReminderlist[] = $reminder;
                    }
                    $this->set(compact('wo_osr_po_id', 'osrPOReminderlist'));
                }else if($section == 'aircraft_wo_osr_po_item'){
                    $fileName .= 'aircraft_wo_osr_po_item';
                    $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->newEmptyEntity();
                    if(isset($postData['osr_po_item_id'])){
                        $osr_po_item_id = $postData['osr_po_item_id'];
                        $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->get($osr_po_item_id);
                    }
                    $wodropdown = $this->CustomerOTC->getAircraftWODropDown();
                    $wo_osr_po_id = $postData['wo_osr_po_id'];

                    $this->set(compact('woosrpoitems', 'wodropdown', 'wo_osr_po_id'));
                }else if($section == 'aircraft_wo_all_osr_list'){
                    $fileName .= 'aircraft_wo_all_osr_list';

                    $work_order_id = $postData['work_order_id'];
                    $aircraftwoitemosrinfoes = $this->CustomerOTC->getAllOSRByWOId($work_order_id);
                    $this->set(compact('aircraftwoitemosrinfoes'));
                }else if($section == 'aircraft_wo_item_note'){
                    $fileName .= 'aircraft_wo_item_note';

                    $wo_item_id = $postData['wo_item_id'];
                    $aircraftwoitems = $this->CustomerAircraftWOItems->get($wo_item_id);
                    $this->set(compact('aircraftwoitems'));
                }else if($section == 'aircraft_wo_item_list'){
                    $fileName .= 'aircraft_wo_item_list';

                    $work_order_id = $postData['work_order_id'];
                    $work_order_no = $postData['work_order_no'];

                    $aircraftwoitemlist = $this->CustomerOTC->getWOItemListByWOId($work_order_id);
                    $this->set(compact('aircraftwoitemlist', 'work_order_no'));
                }else if($section == 'aircraft_work_order_options'){
                    $aircraftworkorders = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
                    $fileName .= 'aircraft_work_order_options';
                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->find('all')->where(['work_order_id'=>$work_order_id])->select($this->CustomerAircraftWOOptionGeneralInfoes)->first();
                    if(empty($wooptiongeninfoes)){
                        $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->newEmptyEntity();
                        
                        $getInfoData = [];

                        $getInfoData['work_order_id'] = $work_order_id;
                        $getInfoData['wo_item_id'] = $wo_item_id;
                        $getInfoData['general_info_status'] = '1';
                        $getInfoData['added_by'] = $authUserData['id'];
                        $getInfoData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->patchEntity($wooptiongeninfoes, $getInfoData);
                        $this->CustomerAircraftWOOptionGeneralInfoes->save($wooptiongeninfoes);
                    }

                    $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->find('all')->where(['work_order_id'=>$work_order_id])->select($this->CustomerAircraftWOOptionMiscCharges)->first();
                    if(empty($wooptionmisccharges)){
                        $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->newEmptyEntity();
                        $misFueldChargesData = [];

                        $misFueldChargesData['work_order_id'] = $work_order_id;
                        $misFueldChargesData['wo_item_id'] = $wo_item_id;
                        $misFueldChargesData['misc_chages_status'] = '1';
                        $misFueldChargesData['added_by'] = $authUserData['id'];
                        $misFueldChargesData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->patchEntity($wooptionmisccharges, $misFueldChargesData);
                    
                        $this->CustomerAircraftWOOptionMiscCharges->save($wooptionmisccharges);
                    }
                    
                    $workorderoptiondata = $this->CustomerOTC->getAircraftWOViewOptionData($postData);
                    extract($workorderoptiondata);
                    
                    $aircraftwoitems = $this->CustomerAircraftWOItems->get($wo_item_id);
                    $warrantylist = $this->CustomerOTC->getWorkOrderWarrantyList($work_order_id);
                    $this->set(compact('work_order_id', 'wo_item_id', 'aircraftworkorders', 'wooptiongeninfoes', 'wooptionmiscchargs', 'wooptionpricinginfoes', 'wooptionwarrantyinfoes', 'wooptiontaxinfoes', 'wooptionbillinginfoes', 'aircraftwoitems', 'warrantylist', 'wooptionmiscfuelchargs'));
                }else if($section == 'aircraft_option_email_work_order'){
                    $fileName .= 'aircraft_option_email_work_order';
                }else if($section == 'aircraft_wo_option_create_atacode'){
                    $fileName .= 'aircraft_wo_option_create_atacode';

                    $woitematacodes = $this->CustomerAircraftWOATACodes->newEmptyEntity();
                    $this->set(compact('woitematacodes'));
                }else if($section == 'aircraft_wo_option_create_laborkit'){
                    $fileName .= 'aircraft_wo_option_create_laborkit';

                    $woitemlaborkits = $this->CustomerAircraftWOLaborKits->newEmptyEntity();
                    $this->set(compact('woitemlaborkits'));
                }else if($section == 'aircraft_wo_option_logbook_helper'){
                    $fileName .= 'aircraft_wo_option_logbook_helper';
                }else if($section == 'aircraft_wo_option_logbook_values'){
                    $fileName .= 'aircraft_wo_option_logbook_values';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    
                    $engine_type = !empty($aircraftregdetail['aircraft_engine_type']) ? $aircraftregdetail['aircraft_engine_type'] : '1';
                    $postData['engine_type'] = $engine_type;

                    $componentResp = $this->CustomerOTC->getAircraftWOLogBookValueTabData($postData);
                    extract($componentResp);

                    if(empty($aircraftmaintoverview)){
                        if($engine_type != '5'){
                            $aircraftmaintoverview = $this->CustomerAircraftWOLogBookValueOverviews->newEmptyEntity();
                        }else{
                            $aircraftmaintoverview = $this->AircraftWOLogBookValueHelicopterOverviews->newEmptyEntity();
                        }
                    }
                    
                    if(empty($aircraftmaintengine) && $engine_type != '5'){
                        if($engine_type == '1' || $engine_type == '2'){
                            $aircraftmaintengine = $this->CustomerAircraftWOLogBookValueEngines->newEmptyEntity();
                        }else{
                            $aircraftmaintengine = $this->AircraftWOLogBookValueJetEngines->newEmptyEntity();
                        }
                    }

                    if(empty($aircraftmaintprops)){
                        $aircraftmaintprops = $this->CustomerAircraftWOLogBookValueProps->newEmptyEntity();
                    }
                    $aircraftwoitems = $this->CustomerAircraftWOItems->get($wo_item_id);

                    $this->set(compact('aircraftmaintoverview', 'aircraftmaintengine', 'aircraftmaintprops', 'engine_type', 'aircraftregdetail', 'work_order_id', 'wo_item_id', 'section', 'aircraftwoitems'));

                }else if($section == 'aircraft_wo_geninfo_manage_deposits'){
                    $fileName .= 'aircraft_wo_geninfo_manage_deposits';

                    $optiongeninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->newEmptyEntity();
                    $general_info_id = $postData['general_info_id'];

                    $optiongeninfodepositlist = $this->CustomerOTC->getAircraftWOViewOptionGenInfoDeposits($general_info_id);

                    $this->set(compact('general_info_id', 'optiongeninfodeposits', 'optiongeninfodepositlist'));
                }else if($section == 'aircraft_wo_option_taxinfo_extra_taxes'){
                    $fileName .= 'aircraft_wo_option_taxinfo_extra_taxes';

                    $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->newEmptyEntity();

                    $tax_id = $postData['option_tax_info_id'];
                    
                    $wooptionextrataxlist = $this->CustomerAircraftWOOptionExtraTaxes->find('all', array(
                        'order' => 'id DESC'))->where(['tax_id'=>$tax_id])->select(['id', 'extra_tax_name']);

                    $this->set(compact('wooptionextrataxes', 'tax_id', 'wooptionextrataxlist'));
                }else if($section == 'aircraft_wo_option_new_extra_tax'){
                    $fileName .= 'aircraft_wo_option_new_extra_tax';
                    $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->newEmptyEntity();

                    $tax_id = $postData['option_tax_info_id'];
                    $this->set(compact('wooptionextrataxes', 'tax_id'));
                }else if($section == 'wo_set_all_items_specific_dept'){
                    $fileName .= 'wo_set_all_items_specific_dept';
                }else if($section == 'aircraft_wo_item_allparts_list'){
                    $fileName .= 'aircraft_wo_item_allparts_list';
                    $workorderpartslist = $this->CustomerOTC->getAircraftWOAllPartsList($postData['work_order_id']);

                    $this->set(compact('workorderpartslist'));
                }else if($section == 'aircraft_wo_item_parts_requisitions'){
                    $fileName .= 'aircraft_wo_item_parts_requisitions';

                    $vendorlist = $this->CustomerOTC->getWOPartsVendorList();
                    $wo_item_id = $postData['wo_item_id'];
                    $work_order_id = $postData['work_order_id'];

                    $workorderpartslist = $this->CustomerOTC->getAircraftWOAllPartsList($work_order_id);
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->newEmptyEntity();
                    $invpartnumbers = $this->CustomerOTC->getWOItemPartNumberList();

                    $this->set(compact('vendorlist', 'wo_item_id', 'work_order_id', 'workorderpartslist', 'aircraftwoitemparts', 'invpartnumbers'));
                }else if($section == 'aircraft_wo_item_parts_send_reqmsg'){
                    $fileName .= 'aircraft_wo_item_parts_send_reqmsg';

                    $wo_item_id = $postData['wo_item_id'];
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->newEmptyEntity();
                    $this->set(compact('wo_item_id', 'aircraftwoitemparts'));
                }else if($section == 'aircraft_wo_item_parts_view'){
                    $fileName .= 'aircraft_wo_item_parts_view';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $vendorlist = $this->CustomerOTC->getWOPartsVendorList();
                    $aircraftwoitemparts = $this->CustomerOTC->getAircraftWOAllPartsById($postData);
                    $this->set(compact('work_order_id', 'aircraftwoitemparts', 'vendorlist', 'wo_item_id'));
                }else if($section == 'aircraft_wo_item_parts_to_pull'){
                    $fileName .= 'aircraft_wo_item_parts_to_pull';

                    $aircraftwoitemparts[] = $this->CustomerOTC->getAircraftWOAllPartsById($postData);
                    $this->set(compact('aircraftwoitemparts'));
                }else if($section == 'aircraft_wo_item_part_notes'){
                    $fileName .= 'aircraft_wo_item_part_notes';

                    $aircraftwoitemparts = $this->CustomerOTC->getAircraftWOAllPartsById($postData);
                    $this->set(compact('aircraftwoitemparts'));
                }else if($section == 'aircraft_work_order_mark_items'){
                    $fileName .= 'aircraft_work_order_mark_items';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $aircraftwoitems = $this->CustomerAircraftWOItems->find('all', array('order'=>'wo_item_position ASC'))
                                                                        ->where(['work_order_id'=>$work_order_id])
                                                                        ->select($this->CustomerAircraftWOItems)
                                                                        ->select(['wo_item_overviews.owner_authentication'])
                                                                        ->join([
                                                                            'wo_item_overviews' => [
                                                                                'table' => 'customer_aircraft_wo_item_overviews',
                                                                                'type' => 'LEFT',
                                                                                'conditions' => 'wo_item_overviews.wo_item_id = CustomerAircraftWOItems.id',
                                                                            ]
                                                                        ]);
                    
                    $this->set(compact('aircraftwoitems', 'wo_item_id'));
                }else if($section == 'aircraft_wo_completion_password'){
                    $fileName .= 'aircraft_wo_completion_password';
                }else if($section == 'aircraft_wo_item_tool_edit'){
                    $fileName .= 'aircraft_wo_item_tool_edit';

                    $wo_item_tool_id = $postData['wo_item_tool_id'];
                    $woitemtools = $this->CustomerOTC->getWOItemToolById($postData);
                    $aircraftwoitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);

                    $this->set(compact('wo_item_tool_id', 'woitemtools', 'aircraftwoitems'));
                }else if($section == 'inventory_customer_add_address'){
                    $fileName .= 'inventory_customer_add_address';

                    $inventorycustomeraddresses = $this->InventoryCustomerAddresses->newEmptyEntity();
                    $states = '';
                    $this->set(compact('inventorycustomeraddresses', 'states'));
                }else if($section == 'update_logbook_value_open_wo'){
                    $fileName .= 'update_logbook_value_open_wo';

                    $engine_type = $aircraftregdetail->aircraft_engine_type;

                    $workorderlist = $this->CustomerOTC->getWorkOrderLogbookValueList($postData['aircraft_id'], $engine_type);
                    $this->set(compact('workorderlist'));
                }else if($section == 'confirm_create_new_work_order'){
                    $aircraft_id = $postData['aircraft_id'];

                    $workordernolist = $this->CustomerOTC->getAircraftOpenWorkOrderNo($aircraft_id);
                    if(!empty($workordernolist)){
                        $fileName .= 'confirm_create_new_work_order';

                        $this->set(compact('workordernolist'));
                    }else{
                        echo 'no-data';exit;
                    }
                }else if($section == 'wo_item_discrepancy'){
                    $fileName .= 'wo_item_discrepancy';
                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $workorderinfo = $this->CustomerAircraftWOItems->get($wo_item_id);
                    $this->set(compact('workorderinfo', 'work_order_id', 'wo_item_id'));
                }else if($section == 'wo_item_discrepancy_history'){
                    $fileName .= 'wo_item_discrepancy_history';
                    $wo_item_id = $postData['wo_item_id'];

                    $discrepancyhistorylist = $this->CustomerOTC->getWOItemDiscrepancyHistory($wo_item_id);
                    $this->set(compact('discrepancyhistorylist'));
                }else if($section == 'wo_item_corrective_action'){
                    $fileName .= 'wo_item_corrective_action';
                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $workorderinfo = $this->CustomerAircraftWOItems->get($wo_item_id);
                    $this->set(compact('workorderinfo', 'work_order_id', 'wo_item_id'));
                }else if($section == 'wo_item_corrective_action_history'){
                    $fileName .= 'wo_item_corrective_action_history';
                    $wo_item_id = $postData['wo_item_id'];

                    $correctiveactionhistorylist = $this->CustomerOTC->getWOItemCorrectiveActionHistory($wo_item_id);
                    $this->set(compact('correctiveactionhistorylist'));
                }else if($section == 'work_order_print_preview'){
                    $fileName .= 'work_order_print_preview';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];

                    $this->set(compact('work_order_id', 'wo_item_id'));
                }else if($section == 'work_order_print_preview_category' || $section == 'work_order_print_preview_maintenance' || $section == 'wo_print_preview_servicelist_signoff' || $section == 'wo_print_preview_servicelist_department' || $section == 'wo_print_preview_date_range' || $section == 'wo_print_preview_warranty' || $section == 'wo_print_preview_logbook_labels' || $section == 'wo_pp_logbook_labels_print_option' || $section == 'wo_ppreview_logbook_labels_pickitem' || $section == 'wo_pp_logbook_labels_statement'){

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $wo_report_type = $postData['wo_report_type'];

                    if($section == 'work_order_print_preview_maintenance'){
                        $fileName .= 'work_order_print_preview_maintenance';
                    }else if($section == 'wo_print_preview_servicelist_signoff'){
                        $fileName .= 'wo_print_preview_servicelist_signoff';
                    }else if($section == 'wo_print_preview_servicelist_department'){
                        $fileName .= 'wo_print_preview_servicelist_department';
                    }else if($section == 'wo_print_preview_date_range'){
                        $fileName .= 'wo_print_preview_date_range';
                    }else if($section == 'wo_print_preview_warranty'){
                        $fileName .= 'wo_print_preview_warranty';
                    }else if($section == 'wo_print_preview_logbook_labels'){
                        $fileName .= 'wo_print_preview_logbook_labels';
                        
                        $logbokCategories = $this->CustomerAircraftWOItems->find('all', ['order'=>'wo_item_overview.log_book_category'])
                        ->where(['CustomerAircraftWOItems.work_order_id'=>$work_order_id])
                        ->select(['wo_item_overview.log_book_category'])
                        ->join([
                            'wo_item_overview'=>[
                                'table'=>'customer_aircraft_wo_item_overviews',
                                'type'=>'INNER',
                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                            ]
                        ])
                        ->group('wo_item_overview.log_book_category')
                        ->toArray();

                        $this->set(compact('logbokCategories'));
                    }else if($section == 'wo_pp_logbook_labels_print_option'){
                        $fileName .= 'wo_pp_logbook_labels_print_option';

                        $log_book_category = $postData['log_book_category'];
                        $technicianlist = $this->CustomerOTC->getUserDropdownList();
                        $statementlist = $this->Statements->find('all', ['order'=>'id DESC'])->where(['status'=>'1'])->select($this->Statements);
                        $statementdropdown = [];
                        foreach($statementlist as $statements){
                            $statementdropdown[$statements['id']] = $statements['statement_name'];
                        }

                        $this->set(compact('log_book_category', 'technicianlist', 'statementdropdown'));
                        
                    }else if($section == 'wo_ppreview_logbook_labels_pickitem'){
                        $fileName .= 'wo_pp_logbook_labels_pickitem';

                        $log_book_category = $postData['log_book_category'];

                        $correctiveActions = $this->CustomerAircraftWOItems->find('all', ['order'=>'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.wo_corrective_action !='=>''])
                        ->where(['CustomerAircraftWOItems.work_order_id'=>$work_order_id, 'wo_item_overview.log_book_category'=>$log_book_category, 'wo_item_overview.donot_use_inlogbook'=>'0'])
                        ->select(['CustomerAircraftWOItems.wo_corrective_action', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id'])
                        ->join([
                            'wo_item_overview'=>[
                                'table'=>'customer_aircraft_wo_item_overviews',
                                'type'=>'INNER',
                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                            ]
                        ])
                        ->toArray();

                        $technicianlist = $this->CustomerOTC->getUserDropdownList();
                        $this->set(compact('correctiveActions', 'log_book_category', 'technicianlist', 'postData'));
                    }else if($section == 'wo_pp_logbook_labels_statement'){
                        $fileName .= 'wo_pp_logbook_labels_statement';
                        $statement_name = $postData['statement_name'];
                        $this->set(compact('statement_name'));
                    }else{
                        $fileName .= 'work_order_print_preview_category';
                    }

                    $this->set(compact('work_order_id', 'wo_item_id', 'wo_report_type'));
                }else if($section == 'wo_print_preview_maintenance_bytask'){
                    $fileName .= 'wo_print_preview_maintenance_bytask';

                    $work_order_id = $postData['work_order_id'];
                    $wo_item_id = $postData['wo_item_id'];
                    $wo_report_type = $postData['wo_report_type'];

                    $technicianlist = $this->CustomerOTC->getUserDropdownList();

                    $this->set(compact('work_order_id', 'wo_item_id', 'wo_report_type', 'technicianlist'));
                }else if($section == 'wo_photo_upload'){
                    $fileName .= 'wo_photo_upload_popup';
                }else if($section == 'wo_file_upload'){
                    $fileName .= 'wo_file_upload_popup';
                }
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function openAircraftWorkOrderPopup($customerOTCPageInfo, $postData){
            
            $aircraftwoitemoverviews = $this->CustomerAircraftWOItemOverviews->newEmptyEntity();
            $aircraftwoitems = $this->CustomerAircraftWOItems->newEmptyEntity();
            $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->newEmptyEntity();
            $aircraftworkorders = $this->CustomerAircraftWorkOrders->newEmptyEntity();
            $aircraftwoitemosrinfoes = $this->CustomerAircraftWOOSRInfoes->newEmptyEntity();

            $aircraft_id = !empty($postData['aircraft_id']) ? $postData['aircraft_id'] : '';
            $customer_id = !empty($postData['customer_id']) ? $postData['customer_id'] : '';
            $authUserData = $this->Authentication->getResult()->getData();

            if(isset($postData['work_order_id']) && !empty($postData['work_order_id'])){
                $aircraftworkorders = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);

                $aircraft_id = !empty($postData['aircraft_id']) ? $postData['aircraft_id'] : $aircraftworkorders->aircraft_id;
                $customer_id = !empty($postData['customer_id']) ? $postData['customer_id'] : $aircraftworkorders->wo_customer_id;
            }else if(isset($postData['work_order_no']) && !empty($postData['work_order_no'])){
                $aircraftworkorders = $this->CustomerAircraftWorkOrders->find('all')->where(['work_order_no'=>$postData['work_order_no']])->select($this->CustomerAircraftWorkOrders)->first();
                
                $postData['work_order_id'] = $aircraftworkorders->id;
                $aircraft_id = !empty($postData['aircraft_id']) ? $postData['aircraft_id'] : $aircraftworkorders->aircraft_id;
                $customer_id = !empty($postData['customer_id']) ? $postData['customer_id'] : $aircraftworkorders->wo_customer_id;

                $customerOTCPageInfo = $this->CustomerOTC->getCustomerOTCPageInfo($customer_id, $aircraft_id);
            }

            extract($customerOTCPageInfo);

            $aircraftinfodet = $this->CustomerOTCAircrafts->get($aircraft_id);
            $wo_customer_name = '('.$aircraftinfodet->aircraft_registration_number.') '.$inventorycustomers->customer_name;

            $current_item_position = '1';
            if(empty($postData['work_order_id'])){
                $work_order_number = $this->CustomerOTC->getAircraftWorkOrderNumber();
                $totalitemcount = 1;
                
                $postData = [];     
                $postData['aircraft_id'] = $aircraft_id;
                $postData['wo_customer_id'] = $customer_id;
                $postData['work_order_no'] = $work_order_number;
                $postData['wo_phone_no'] = !empty($clientphone) ? array_values($clientphone)[0] : '';
                $postData['wo_status'] = '1';
                $postData['added_by'] = $authUserData['id'];
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                $aircraftworkorders = $this->CustomerAircraftWorkOrders->patchEntity($aircraftworkorders, $postData);
                
                $this->CustomerAircraftWorkOrders->save($aircraftworkorders);
                $postData['work_order_id'] = $aircraftworkorders->id;
                
                //save work order item data
                $wo_item_id = $this->saveAircraftWOItems($postData);
                $postData['wo_item_id'] = $wo_item_id;

                //save work order item overviews
                $wo_overviews_id = $this->saveAircraftWOItemOverviews($postData);
                //unset($postData['wo_item_id']);
            }

            $workorderalldata = $this->CustomerOTC->getWrkOrderAllTabData($postData);
            extract($workorderalldata);
            $sessionUser = $this->request->getSession()->read('Auth');;

            $login_user_id = $authUserData['id'];
            $userMenuItems = $this->CustomerOTC->checkWorkOrderMenuPermission();


            $this->set(compact('work_order_number', 'totalitemcount', 'aircraftwoitems', 'aircraftworkorders', 'aircraftwoitemoverviews', 'aircraftwoitemservices', 'technicianData', 'aircraftwoitemosrinfoes', 'current_item_position', 'wo_item_positions', 'wo_item_position_index', 'sessionUser', 'aircraftwoitemphotoes', 'aircraftwoitemfiles', 'login_user_id', 'aircraftwoitemparts', 'woitemtoollists', 'inventorycustomers', 'clientphone', 'userlist', 'wo_customer_name', 'woitemhistorylist', 'woitemsummary', 'userMenuItems', 'signoffincompletes'));
        }

        public function loadAircraftWorkOrderPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $section = $postData['section'];
                $search_by = !empty($postData['search_by']) ? $postData['search_by'] : '';

                $fileName = '/element/InventoryPopup/customer_otc/';

                if($section == 'advanced_wo_find_options'){
                    $fileName .= 'advanced_wo_find_options';

                    $vendorlist = $this->CustomerOTC->getWOOSRVendorList();
                    $aircraftregnumlist = $this->CustomerOTC->getAircraftRegistrationNumberList();

                    $this->set(compact('vendorlist', 'aircraftregnumlist'));
                }else if($search_by == 'create_new'){
                    $customerAircraft = $this->CustomerOTCAircrafts->find('all')->where(['id'=>$postData['filter_aircraft_id']])->select(['customer_id', 'id'])->first();
                    
                    $postData['customer_id'] = $customerAircraft->customer_id;
                    $postData['aircraft_id'] = $customerAircraft->id;

                    $customer_id = isset($postData['customer_id']) ? $postData['customer_id'] : '';
                    $aircraft_id = isset($postData['aircraft_id']) ? $postData['aircraft_id'] : '';
                    $sessionUser = $this->request->getSession()->read('Auth');;
                    
                    $countries = $this->Address->getCountryList();
                    $customerOTCPageInfo = $this->CustomerOTC->getCustomerOTCPageInfo($customer_id, $aircraft_id);
                    extract($customerOTCPageInfo);
                    
                    $this->set(compact('inventorycustomers', 'countries', 'userlist', 'created_by', 'clientphone', 'aircraft_id', 'aircraftregdetail', 'sessionUser', 'customer_id'));

                    $this->openAircraftWorkOrderPopup($customerOTCPageInfo, $postData);

                    $fileName .= 'inventory_aircraft_create_work_order';
                }else if($search_by == 'aircraft_wo_no'){
                    if(!empty($postData['work_order_id'])){
                        $aircraftWorkOrders = $this->CustomerAircraftWorkOrders->find('all')->where(['id'=>$postData['work_order_id']])->select(['aircraft_id', 'wo_customer_id'])->first();
                    }else{
                        $aircraftWorkOrders = $this->CustomerAircraftWorkOrders->find('all')->where(['work_order_no'=>$postData['work_order_no']])->select(['aircraft_id', 'wo_customer_id'])->first();
                    }
                    
                    if(!empty($aircraftWorkOrders)){
                        $postData['customer_id'] = $aircraftWorkOrders->wo_customer_id;
                        $postData['aircraft_id'] = $aircraftWorkOrders->aircraft_id;

                        $customer_id = isset($postData['customer_id']) ? $postData['customer_id'] : '';
                        $aircraft_id = isset($postData['aircraft_id']) ? $postData['aircraft_id'] : '';
                        $sessionUser = $this->request->getSession()->read('Auth');;
                        
                        $countries = $this->Address->getCountryList();
                        $customerOTCPageInfo = $this->CustomerOTC->getCustomerOTCPageInfo($customer_id, $aircraft_id);
                        extract($customerOTCPageInfo);
                        
                        $this->set(compact('inventorycustomers', 'countries', 'userlist', 'created_by', 'clientphone', 'aircraft_id', 'aircraftregdetail', 'sessionUser', 'customer_id'));

                        $this->openAircraftWorkOrderPopup($customerOTCPageInfo, $postData);

                        $fileName .= 'inventory_aircraft_create_work_order';
                    }else{
                        echo 'No work order found!';exit;
                    }
                }else if($search_by == 'open_work_order' || $search_by == 'all_work_order'){
                    $filter = [];
                    if($search_by == 'open_work_order'){
                        $filter = ['wo_status'=>'1'];
                    }
                    
                    $aircrafOpenWorkOrders = $this->CustomerOTC->getListOfWorkOrders($filter);
                    
                    $fileName .= 'list_of_open_work_order';
                    $this->set(compact('aircrafOpenWorkOrders', 'search_by'));
                }else if($search_by == 'open_warranty_claim_work_order' || $search_by == 'all_warranty_claim_work_order'){
                    $filter = [];
                    if($search_by == 'open_warranty_claim_work_order'){
                        $filter = ['wo_status'=>'1'];
                    }
                    
                    $aircrafOpenWorkOrders = $this->CustomerOTC->getListOfWarrantyClaimsWO($filter);
                    
                    $fileName .= 'list_of_warranty_claims_work_order';
                    $this->set(compact('aircrafOpenWorkOrders', 'search_by'));
                }else if($search_by == 'aircraft_registration_number' || $search_by == 'aircraft_serial_number' || $search_by == 'customer_name'){
                    if($search_by == 'aircraft_registration_number'){
                        $search_page_heading = 'List of All Work Orders / Quotes for Reg. Number: '.$postData['aircraft_registration_number'];
                        $whereArr = ['otc_aircrafts.aircraft_registration_number'=> $postData['aircraft_registration_number']];
                    }else if($search_by == 'aircraft_serial_number'){
                        $search_page_heading = 'List of All Work Orders / Quotes for Serial: '.$postData['aircraft_serial_number'];
                        $whereArr = ['otc_aircrafts.aircraft_serial'=> $postData['aircraft_serial_number']];
                    }else if($search_by == 'customer_name'){
                        $search_page_heading = 'List of All Work Orders / Quotes for Customer: '.$postData['customer_name'];
                        $whereArr = ['customers.customer_name LIKE'=> $postData['customer_name'].'%'];
                    }
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'discrepancy_histories.discrepancy'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'discrepancy_histories' => [
                                                                    'table' => 'aircraft_wo_item_discrepancy_histories',
                                                                    'type' => 'LEFT',
                                                                    'conditions' => 'discrepancy_histories.wo_item_id = wo_items.id',
                                                                ]
                                                            ]);
                                                            
                    if($search_by == 'customer_name'){
                        $aircrafAllWorkOrders = $aircrafAllWorkOrders->join([
                                                                    'customers' => [
                                                                        'table' => 'inventory_customers',
                                                                        'type' => 'INNER',
                                                                        'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                                                    ]
                                                                ]);
                    }
                    $aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'customer_po'){
                    $search_page_heading = 'List of All Work Orders / Quotes for Customer P/O: '.$postData['customer_po'];
                    $aircrafAllWorkOrders = [];

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'discrepancy' || $search_by == 'corrective_action'){
                    if($search_by == 'discrepancy'){
                        $search_page_heading = 'List of All Work Orders / Quotes with Discrepancy: '.$postData['discrepancy'];
                        $whereArr = ['wo_items.wo_discrepancy LIKE'=> $postData['discrepancy'].'%'];
                    }else if($search_by == 'corrective_action'){
                        $search_page_heading = 'List of All Work Orders / Quotes with Corrective Action: '.$postData['corrective_action'];
                        $whereArr = ['wo_items.wo_corrective_action LIKE'=> $postData['corrective_action'].'%'];
                    }
                    
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'wo_items.wo_discrepancy', 'wo_items.wo_corrective_action', 'wo_items.wo_item_position'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ]);
                    $aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'warranty_claim_no'){
                    $search_page_heading = 'List of All Work Orders / Quotes with Claim: '.$postData['warranty_claim_no'];
                    $whereArr = ['item_overviews.warranty_claim_no LIKE'=> $postData['warranty_claim_no'].'%'];
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';

                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'wo_items.wo_item_position', 'wo_items.wo_discrepancy', 'item_overviews.warranty', 'item_overviews.warranty_claim_no'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'item_overviews' => [
                                                                    'table' => 'customer_aircraft_wo_item_overviews',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'item_overviews.wo_item_id = wo_items.id',
                                                                ]
                                                            ]);
                    $aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'warranty_wo_status'){                                                                                                                                                                                      
                    $aircraftWOStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
                    $search_page_heading = 'List of All Work Orders / Quotes with Warranty: '.$aircraftWOStatus[$postData['warranty_wo_status']];
                    $whereArr = ['item_overviews.warranty_claim_no !='=> '', 'CustomerAircraftWorkOrders.wo_status'=>$postData['warranty_wo_status']];
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
                    
                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'wo_items.wo_item_position', 'wo_items.wo_discrepancy', 'item_overviews.warranty', 'item_overviews.warranty_claim_no', 'customers.customer_name'])
                                                            ->select(['hrs_worked'=>'SUM(item_services.hrs_worked)'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'item_overviews' => [
                                                                    'table' => 'customer_aircraft_wo_item_overviews',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'item_overviews.wo_item_id = wo_items.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'item_services' => [
                                                                    'table' => 'customer_aircraft_wo_item_services',
                                                                    'type' => 'LEFT',
                                                                    'conditions' => 'item_services.wo_item_id = wo_items.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'customers' => [
                                                                    'table' => 'inventory_customers',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                                                ]
                                                            ]);
                    $aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'part_number'){
                    $search_page_heading = 'List of All Work Orders / Quotes with Part Number: '.$postData['part_number'];
                    $aircrafAllWorkOrders = [];

                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'advanced_find_option'){
                    $aircraftarr = $this->CustomerOTCAircrafts->get($postData['adv_registration_number']);
                    $search_page_heading = 'Found item for: '.$aircraftarr->aircraft_registration_number;
                    $whereArr = [];
                    if(!empty($postData['adv_registration_number'])){
                        $whereArr['otc_aircrafts.id'] = $postData['adv_registration_number'];
                    }
                    if(!empty($postData['adv_discrpancy'])){
                        $whereArr['wo_items.wo_discrepancy LIKE'] = $postData['adv_discrpancy'].'%';
                    }
                    if(!empty($postData['adv_corrective_action'])){
                        $whereArr['wo_items.wo_corrective_action LIKE'] = $postData['adv_corrective_action'].'%';
                    }
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';

                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'wo_items.wo_discrepancy', 'wo_items.wo_corrective_action'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ]);
                                                            
                    $aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');
                    
                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'other_find_option'){
                    $search_page_heading = 'Found item for Misc. Charges Notes';
                    $whereArr = [];
                    if(!empty($postData['wo_misc_charges_notes'])){
                        $whereArr['wo_option_misc_charges.misc_charge_description_for_invoice LIKE'] = $postData['wo_misc_charges_notes'].'%';
                    }
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
                    
                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'customers.customer_name'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_option_misc_charges' => [
                                                                    'table' => 'customer_aircraft_wo_option_misc_charges',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_option_misc_charges.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'customers' => [
                                                                    'table' => 'inventory_customers',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                                                ]
                                                            ]);
                                                            
                    //$aircrafAllWorkOrders = $aircrafAllWorkOrders->group('wo_items.work_order_id');
                    
                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'advanced_parts_search'){
                    $search_page_heading = 'Advanced Parts Search Results';
                    $whereArr = [];
                    if(!empty($postData['wo_part_number'])){
                        $whereArr['wo_item_parts.part_number LIKE'] = $postData['wo_part_number'].'%';
                    }
                    if(!empty($postData['wo_description'])){
                        $whereArr['wo_item_parts.part_description LIKE'] = $postData['wo_description'].'%';
                    }
                    if(!empty($postData['wo_old_serial_no'])){
                        $whereArr['wo_item_parts.old_serial_number LIKE'] = $postData['wo_old_serial_no'].'%';
                    }
                    if(!empty($postData['wo_new_serial_no'])){
                        $whereArr['wo_item_parts.serial_number LIKE'] = $postData['wo_new_serial_no'].'%';
                    }
                    if(!empty($postData['wo_supplier'])){
                        $whereArr['wo_item_parts.supplier'] = $postData['wo_supplier'];
                    }
                    $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
                      
                    $aircrafAllWorkOrders = $this->CustomerAircraftWorkOrders->find('all')
                                                            ->where($whereArr)
                                                            ->select($this->CustomerAircraftWorkOrders)
                                                            ->select(['otc_aircrafts.aircraft_registration_number', 'wo_item_parts.part_number', 'wo_item_parts.part_description', 'wo_item_parts.serial_number', 'customers.customer_name'])
                                                            ->join([
                                                                'otc_aircrafts' => [
                                                                    'table' => 'customer_otc_aircrafts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'otc_aircrafts.id = CustomerAircraftWorkOrders.aircraft_id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_items' => [
                                                                    'table' => 'customer_aircraft_wo_items',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'wo_item_parts' => [
                                                                    'table' => 'customer_aircraft_wo_item_parts',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'wo_item_parts.wo_item_id = wo_items.id',
                                                                ]
                                                            ])
                                                            ->join([
                                                                'customers' => [
                                                                    'table' => 'inventory_customers',
                                                                    'type' => 'INNER',
                                                                    'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                                                ]
                                                            ]);
                                                            
                    $this->set(compact('aircrafAllWorkOrders', 'search_page_heading', 'search_by'));
                    $fileName .= 'list_of_all_work_order_quotes';
                }else if($search_by == 'import_work_order_from_email'){
                    $fileName .= 'import_work_order_from_email';
                }else if($search_by == 'export_work_order_to_file'){
                    $fileName .= 'export_work_order_to_file';

                    $dropdowndatas = $this->CustomerOTC->getWOROFilterExportDropDownData();
                    extract($dropdowndatas);
                    
                    $this->set(compact('inventorycustomers', 'aircraftregnumbers', 'aircraftmodels', 'aircraftmakes', 'atacodes', 'laborkits', 'userlist', 'osrvendorlist', 'supplierlist'));
                }
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function getAircraftModelByMake(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $make_id = $postData['aircraft_make_id'];

                $connection = ConnectionManager::get('default');
                $results = $this->CustomerOTC->getAircraftModelList($make_id);
                $aircraftmodels = '<option value="">Select Model</option>';
                foreach($results as $model_id=>$model_name){
                    $aircraftmodels .= '<option value="'.$model_id.'">'.$model_name.'</option>';
                }

                echo $aircraftmodels;die;
            }
        }

        public function saveCustomerOTCAircraft(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $customerotcaircrafts = $this->CustomerOTCAircrafts->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;
                    $customerotcaircraftsdata = '';
                    if(empty($postData['aircraft_id'])){
                        $customer_id = $postData['customer_id'];
                        $customerotcaircraftsdata = $this->CustomerOTCAircrafts->exists(['CustomerOTCAircrafts.aircraft_registration_number'=>$postData['aircraft_registration_number'], 'customer_id'=>$customer_id]);
                    }else{
                        $customerotcaircrafts = $this->CustomerOTCAircrafts->get($postData['aircraft_id']);
                        $customer_id = $customerotcaircrafts->customer_id;
                    }
                    //echo "<pre>";print_r($customerotcaircraftsdata);exit;
                    if(!$customerotcaircraftsdata){
                        $postData['aircraft_engine_type'] = !empty($postData['aircraft_engine_type']) ? $postData['aircraft_engine_type'] : '1';
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $customerotcaircrafts = $this->CustomerOTCAircrafts->patchEntity($customerotcaircrafts, $postData);

                        if ($this->CustomerOTCAircrafts->save($customerotcaircrafts)) {
                            $aircraft_id = $customerotcaircrafts->id;

                            $this->getAircraftInfoHtmlData($customer_id, $aircraft_id);
                        }else{
                            $message = 'Something went wrong, please try again';
                            echo $message;die;
                        }
                    }else{
                        $message = 'Aircraft registration number already exist.';
                        echo $message;die;
                    }
                }else{
                    $message = 'Something went wrong, please try again';
                    echo $message;die;
                }
            }
        }

        public function getAircraftDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                if(!empty($postData['aircraft_id'])){
                    $aircraft_id = $postData['aircraft_id'];
                    $aircraftregdet = $this->CustomerOTCAircrafts->get($aircraft_id);

                    $reponsearr = ['status'=>'success', 'message'=>'', 'aircraftregdet'=>$aircraftregdet];
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function deleteCustomerOTCAircraft(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['id'])){
                        $entity = $this->CustomerOTCAircrafts->get($postData['id']);
                        $result = $this->CustomerOTCAircrafts->delete($entity);

                        $this->getAircraftInfoHtmlData($postData['customer_id']);
                    }else{
                        $message = 'Something went wrong, please try again';
                        echo $message;die;
                    }
                    
                }else{
                    $message = 'Something went wrong, please try again';
                    echo $message;die;
                }
            }
        }

        public function saveAircraftContractRates(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    $customercontractrates = $this->CustomerAircraftContractRates->newEmptyEntity();
                    if(!empty($postData['contract_rate_id'])){
                        $customercontractrates = $this->CustomerAircraftContractRates->get($postData['contract_rate_id']);
                    }
                    //echo "<pre>";print_r($customercontractrates);exit;
                    //$customerotcaircraftsdata = $this->CustomerAircraftContractRates->exists(['CustomerAircraftContractRates.aircraft_registration_number'=>$postData['aircraft_registration_number']]);
                    
                    //if(!$customerotcaircraftsdata){
                      
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $customercontractrates = $this->CustomerAircraftContractRates->patchEntity($customercontractrates, $postData);

                        if ($this->CustomerAircraftContractRates->save($customercontractrates)) {
                            $contract_rate_id = $customercontractrates->id;
                            $aircraftContractRates = $this->CustomerOTC->getAllAircraftContractRate($postData['aircraft_id']);
                            
                            $reponsearr = ['status'=>'success', 'message'=>'', 'aircraftContractRates'=>$aircraftContractRates];
                        }else{
                            $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                        }
                    /*}else{
                        $reponsearr = ['status'=>'failed', 'message'=>'Aircraft registration number already exist.'];
                    }*/
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function deleteAircraftContractRates(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['id'])){
                        $entity = $this->CustomerAircraftContractRates->get($postData['id']);
                        $result = $this->CustomerAircraftContractRates->delete($entity);

                        $aircraftContractRates = $this->CustomerOTC->getAllAircraftContractRate($postData['aircraft_id']);
                        
                        $response = ['status'=>'success', 'message'=>'', 'aircraftContractRates'=>$aircraftContractRates];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getAircraftContractRateDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                if(!empty($postData['contract_rate_id'])){
                    $contract_rate_id = $postData['contract_rate_id'];
                    $aircraftContractRateDet = $this->CustomerAircraftContractRates->get($contract_rate_id);

                    $reponsearr = ['status'=>'success', 'message'=>'', 'aircraftContractRateDet'=>$aircraftContractRateDet];
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($reponsearr);die;
            }
        }
 
        public function fetchAircraftInfoHtml(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $aircraft_id = $postData['aircraft_id'];
                $customer_id = $postData['customer_id'];
                
                $this->getAircraftInfoHtmlData($customer_id, $aircraft_id);
            }
        }

        public function getAircraftInfoHtmlData($customer_id, $aircraft_id=''){
            $customerotcaircrafts = [];
            $make_id = '';
            if(!empty($aircraft_id)){
                $customerotcaircrafts = $this->CustomerOTCAircrafts->get($aircraft_id);
                $make_id = $customerotcaircrafts->aircraft_make_id;
            }else{
                $customerotcaircrafts = $this->CustomerOTCAircrafts->find('all')->where(['customer_id'=>$customer_id])->select($this->CustomerOTCAircrafts)->first();
                if(!empty($customerotcaircrafts->aircraft_make_id)){
                    $make_id = $customerotcaircrafts->aircraft_make_id;
                }
            }
            $aircraftregdet = $this->CustomerOTCAircrafts->find('all')->where(['customer_id'=>$customer_id])->select(['CustomerOTCAircrafts.id', 'CustomerOTCAircrafts.aircraft_registration_number']);
            
            $conn = ConnectionManager::get('default');
            $query = "select id, make from customer_otc_aircraft_make where status = '1' order by make";
            $results = $conn->execute($query)->fetchAll('assoc');
            $aircraftmakes = [];
            foreach($results as $row){
                $aircraftmakes[$row['id']] = $row['make'];
            }

            $aircraftmodels = $this->CustomerOTC->getAircraftModelList($make_id);

            $aircraftworkorderdata = [];
            if(!empty($customerotcaircrafts)){
                $aircraftworkorderdata = $this->CustomerAircraftWorkOrders->find('all')->where(['aircraft_id'=>$aircraft_id, 'order_type'=>'1'])->select(['CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'CustomerAircraftWorkOrders.created_at']);
            }
            
            $this->set(compact('customerotcaircrafts', 'aircraftregdet', 'aircraftmakes', 'aircraftmodels', 'aircraftworkorderdata'));
            
            $this->viewBuilder()->setLayout('ajax');
            $fileName = '/element/Inventory/customer_otc/customer_aircraft_info_add';
            $this->render($fileName);
        }

        public function saveAdditionalShippingAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    $addlshippingaddresses = $this->CustomerAdditionalShippingAddresses->newEmptyEntity();
                    if(!empty($postData['additional_shipping_address_id'])){
                        $addlshippingaddresses = $this->CustomerAdditionalShippingAddresses->get($postData['additional_shipping_address_id']);
                    }
                    //echo "<pre>";print_r($postData);exit;
                    //$customerotcaircraftsdata = $this->CustomerAdditionalShippingAddresses->exists(['CustomerAdditionalShippingAddresses.aircraft_registration_number'=>$postData['aircraft_registration_number']]);
                    
                    //if(!$customerotcaircraftsdata){
                      
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $addlshippingaddresses = $this->CustomerAdditionalShippingAddresses->patchEntity($addlshippingaddresses, $postData);

                        if ($this->CustomerAdditionalShippingAddresses->save($addlshippingaddresses)) {
                            $additional_shipping_address_id = $addlshippingaddresses->id;
                            $customerAddlShipAddrList = $this->CustomerOTC->getAllCustAddlShippingAddress($postData['customer_id']);
                            
                            $reponsearr = ['status'=>'success', 'message'=>'','customerAddlShipAddrList'=>$customerAddlShipAddrList];
                        }else{
                            $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                        }
                    /*}else{
                        $reponsearr = ['status'=>'failed', 'message'=>'Aircraft registration number already exist.'];
                    }*/
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function getAdditionalShippingAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                if(!empty($postData['additional_shipping_address_id'])){
                    $additional_shipping_address_id = $postData['additional_shipping_address_id'];
                    $customerAddlShipAddrList = $this->CustomerAdditionalShippingAddresses->get($additional_shipping_address_id);

                    $reponsearr = ['status'=>'success', 'message'=>'', 'customerAddlShipAddrList'=>$customerAddlShipAddrList];
                }else{
                    $reponsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($reponsearr);die;
            }
        }

        public function deleteAddlShippingAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['id'])){
                        $entity = $this->CustomerAdditionalShippingAddresses->get($postData['id']);
                        $result = $this->CustomerAdditionalShippingAddresses->delete($entity);

                        $customerAddlShipAddrList = $this->CustomerOTC->getAllCustAddlShippingAddress($postData['customer_id']);
                        
                        $response = ['status'=>'success', 'message'=>'', 'customerAddlShipAddrList'=>$customerAddlShipAddrList];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Please select shipping address.'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function aircraftMediaUpload(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt','mp4');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'customer_otc_aircraft';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadAircraftInfoFilesToServer($postData, $filelocation, $foldername);
                    
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

        public function saveAircraftInfoMedia(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                if (!empty($postData['aircraft_id']) && !empty($postData['filenames'])) {
                    $this->InventoryAttachment->saveAircraftInfoMedia($postData['aircraft_id'], $postData);

                    $response = ['status'=>'success', 'message'=>''];
                }else{
                    $response = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($response);die;
            }
            
        }

        public function deleteAircraftMedia(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteAircraftMediaAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function saveAircraftComplInspections(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $aircraftcomplinspectionsdata = '';
                    if(empty($postData['compliance_inspections_id'])){
                        $aircraftcomplinspectionsdata = $this->CustomerAircraftComplianceInspections->exists(['CustomerAircraftComplianceInspections.inspection_name'=>$postData['inspection_name']]);
                    }else{
                        $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->get($postData['compliance_inspections_id']);
                    }
                    //echo "<pre>";print_r($aircraftcomplinspectionsdata);exit;
                    if(!$aircraftcomplinspectionsdata){
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->patchEntity($aircraftcomplinspections, $postData);

                        if ($this->CustomerAircraftComplianceInspections->save($aircraftcomplinspections)) {
                            $id = $aircraftcomplinspections->id;

                            $response = ['status'=>'success', 'message'=>'', 'inspections_id'=>$id];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Inspection name already exist.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftComplInspHistory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftcomplinspections = $this->CustomerAircraftComplianceInspectionHistories->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $aircraftcomplinspectionsdata = '';
                    
                    if(!$aircraftcomplinspectionsdata){
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $aircraftcomplinspections = $this->CustomerAircraftComplianceInspectionHistories->patchEntity($aircraftcomplinspections, $postData);

                        if ($this->CustomerAircraftComplianceInspectionHistories->save($aircraftcomplinspections)) {
                            $aircraft_inspection_id = $postData['aircraft_inspection_id'];
                            $tblrow = $this->CustomerOTC->getAircraftCompInspListHtml($aircraft_inspection_id);
                            $response = ['status'=>'success', 'message'=>'', 'tblrow'=>$tblrow];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Inspection code already exist.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftComplAirframe(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $aircraftcomplairframesdata = '';
                    if(empty($postData['compliance_airframe_id'])){
                        $aircraftcomplairframesdata = $this->CustomerAircraftComplianceAirframes->exists(['CustomerAircraftComplianceAirframes.name'=>$postData['name']]);
                    }else{
                        $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->get($postData['compliance_airframe_id']);
                    }
                    //echo "<pre>";print_r($aircraftcomplairframesdata);exit;
                    if(!$aircraftcomplairframesdata){
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->patchEntity($aircraftcomplairframes, $postData);
                        
                        if ($this->CustomerAircraftComplianceAirframes->save($aircraftcomplairframes)) {
                            $id = $aircraftcomplairframes->id;

                            $response = ['status'=>'success', 'message'=>'', 'airframe_id'=>$id];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Airframe name already exist.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftComplEngines(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $aircraftcomplenginesdata = '';
                    if(empty($postData['compliance_engine_id'])){
                        $aircraftcomplenginesdata = $this->CustomerAircraftComplianceEngines->exists(['CustomerAircraftComplianceEngines.name'=>$postData['name']]);
                    }else{
                        $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->get($postData['compliance_engine_id']);
                    }
                    //echo "<pre>";print_r($aircraftcomplenginesdata);exit;
                    if(!$aircraftcomplenginesdata){
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->patchEntity($aircraftcomplengines, $postData);
                        
                        if ($this->CustomerAircraftComplianceEngines->save($aircraftcomplengines)) {
                            $id = $aircraftcomplengines->id;

                            $response = ['status'=>'success', 'message'=>'', 'engine_id'=>$id];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Airframe name already exist.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getAircraftComplianceDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $fileName = '';
                    if($postData['section'] == 'inspections'){
                        $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->newEmptyEntity();
                        $aircraft_inspection_id = '';
                        if(!empty($postData['id'])){
                            $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->get($postData['id']);
                            $aircraft_id = $aircraftcomplinspections->aircraft_id;
                            $aircraft_inspection_id = $aircraftcomplinspections->id;
                        }else{
                            $aircraft_id = $postData['aircraft_id'];
                        }

                        $aircraftcomplinspectionshisties = [];
                        if(!empty($aircraft_inspection_id)){
                            $aircraftcomplinspectionshisties = $this->CustomerAircraftComplianceInspectionHistories->find('all')->where(['aircraft_inspection_id'=>$aircraft_inspection_id])->select($this->CustomerAircraftComplianceInspectionHistories);
                        }
                        
                        $this->set(compact('aircraftcomplinspections', 'aircraft_id', 'aircraftcomplinspectionshisties'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_inspections';
                    }else if($postData['section'] == 'airframe'){
                        $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->newEmptyEntity();
                        if(!empty($postData['id'])){
                            $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->get($postData['id']);
                            $aircraft_id = $aircraftcomplairframes->aircraft_id;
                        }else{
                            $aircraft_id = $postData['aircraft_id'];
                        }
                        $this->set(compact('aircraftcomplairframes', 'aircraft_id'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_airframe';
                    }else if($postData['section'] == 'engine'){
                        $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->newEmptyEntity();
                        if(!empty($postData['id'])){
                            $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->get($postData['id']);
                            $aircraft_id = $aircraftcomplengines->aircraft_id;
                        }else{
                            $aircraft_id = $postData['aircraft_id'];
                        }
                        $this->set(compact('aircraftcomplengines', 'aircraft_id'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_engine';
                    }

                    if(!empty($fileName)){
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render($fileName);
                    }
                }
            }
        }

        public function removeAircraftComplianceDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $fileName = '';
                    if($postData['section'] == 'inspections'){
                        $entity = $this->CustomerAircraftComplianceInspections->get($postData['id']);
                        $result = $this->CustomerAircraftComplianceInspections->delete($entity);

                        $aircraftcomplinspections = $this->CustomerAircraftComplianceInspections->newEmptyEntity();
                        $aircraft_id = $postData['aircraft_id'];
                        
                        $this->set(compact('aircraftcomplinspections', 'aircraft_id'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_inspections';
                    }else if($postData['section'] == 'airframe'){
                        $entity = $this->CustomerAircraftComplianceAirframes->get($postData['id']);
                        $result = $this->CustomerAircraftComplianceAirframes->delete($entity);

                        $aircraftcomplairframes = $this->CustomerAircraftComplianceAirframes->newEmptyEntity();
                        $aircraft_id = $postData['aircraft_id'];
                        
                        $this->set(compact('aircraftcomplairframes', 'aircraft_id'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_airframe';
                    }else if($postData['section'] == 'engine'){
                        $entity = $this->CustomerAircraftComplianceEngines->get($postData['id']);
                        $result = $this->CustomerAircraftComplianceEngines->delete($entity);
                        
                        $aircraftcomplengines = $this->CustomerAircraftComplianceEngines->newEmptyEntity();
                        $aircraft_id = $postData['aircraft_id'];
                        
                        $this->set(compact('aircraftcomplengines', 'aircraft_id'));
                        $fileName = '/element/Inventory/customer_otc/customer_aircraft_compliance_engine';
                    }

                    if(!empty($fileName)){
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render($fileName);
                    }
                }
            }
        }
        
        public function saveAircraftMaintOverview(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_overview_id'])){
                        $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->get($postData['maintenance_overview_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $postData['use_hobbs'] = !empty($postData['use_hobbs']) ? $postData['use_hobbs'] : '0';
                    $postData['tach_is_flight_time'] = !empty($postData['tach_is_flight_time']) ? $postData['tach_is_flight_time'] : '0';

                    $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->patchEntity($aircraftmaintoverview, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceOverviews->save($aircraftmaintoverview)) {
                        $id = $aircraftmaintoverview->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintHelicopterOverview(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $mainthelicopteroverview = $this->AircraftMaintenanceHelicopterOverviews->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_helicopter_overview_id'])){
                        $mainthelicopteroverview = $this->AircraftMaintenanceHelicopterOverviews->get($postData['maintenance_helicopter_overview_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $postData['use_hobbs'] = !empty($postData['use_hobbs']) ? $postData['use_hobbs'] : '0';
                    $postData['tach_is_flight_time'] = !empty($postData['tach_is_flight_time']) ? $postData['tach_is_flight_time'] : '0';

                    $mainthelicopteroverview = $this->AircraftMaintenanceHelicopterOverviews->patchEntity($mainthelicopteroverview, $postData);
                    
                    if ($this->AircraftMaintenanceHelicopterOverviews->save($mainthelicopteroverview)) {
                        $id = $mainthelicopteroverview->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWOLogBookValHelicopterOverview(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $mainthelicopteroverview = $this->AircraftWOLogBookValueHelicopterOverviews->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['logbook_value_helicopter_overview_id'])){
                        $mainthelicopteroverview = $this->AircraftWOLogBookValueHelicopterOverviews->get($postData['logbook_value_helicopter_overview_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $postData['use_hobbs'] = !empty($postData['use_hobbs']) ? $postData['use_hobbs'] : '0';
                    $postData['tach_is_flight_time'] = !empty($postData['tach_is_flight_time']) ? $postData['tach_is_flight_time'] : '0';

                    $mainthelicopteroverview = $this->AircraftWOLogBookValueHelicopterOverviews->patchEntity($mainthelicopteroverview, $postData);
                    
                    if ($this->AircraftWOLogBookValueHelicopterOverviews->save($mainthelicopteroverview)) {
                        $id = $mainthelicopteroverview->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintEngine(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_engine_id'])){
                        $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->get($postData['maintenance_engine_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->patchEntity($aircraftmaintengine, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceEngines->save($aircraftmaintengine)) {
                        $id = $aircraftmaintengine->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintJetEngine(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintjetengine = $this->AircraftMaintenanceJetEngines->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_jet_engine_id'])){
                        $aircraftmaintjetengine = $this->AircraftMaintenanceJetEngines->get($postData['maintenance_jet_engine_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $aircraftmaintjetengine = $this->AircraftMaintenanceJetEngines->patchEntity($aircraftmaintjetengine, $postData);
                    
                    if ($this->AircraftMaintenanceJetEngines->save($aircraftmaintjetengine)) {
                        $id = $aircraftmaintjetengine->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWOLogBookValJetEngine(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintjetengine = $this->AircraftWOLogBookValueJetEngines->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['logbook_value_jet_engine_id'])){
                        $aircraftmaintjetengine = $this->AircraftWOLogBookValueJetEngines->get($postData['logbook_value_jet_engine_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $aircraftmaintjetengine = $this->AircraftWOLogBookValueJetEngines->patchEntity($aircraftmaintjetengine, $postData);
                    
                    if ($this->AircraftWOLogBookValueJetEngines->save($aircraftmaintjetengine)) {
                        $id = $aircraftmaintjetengine->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintEngHistory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintenginecylhistory = $this->CustomerAircraftMaintEngineCylHistories->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    /*if(!empty($postData['maintenance_eng_history_id'])){
                        $aircraftmaintenginecylhistory = $this->CustomerAircraftMaintEngineCylHistories->get($postData['maintenance_eng_history_id']);

                        $postData['updated_by'] = $authUserData['id'];
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                    }*/
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                    $aircraftmaintenginecylhistory = $this->CustomerAircraftMaintEngineCylHistories->patchEntity($aircraftmaintenginecylhistory, $postData);
                    
                    if ($this->CustomerAircraftMaintEngineCylHistories->save($aircraftmaintenginecylhistory)) {
                        $id = $aircraftmaintenginecylhistory->id;

                        $aircraftmaintenginecylhistory = $this->CustomerAircraftMaintEngineCylHistories->get($id);
                        
                        $tblrow   = '<tr>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->eng_cyl_date.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_a.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_1.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_2.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_3.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_4.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_5.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine1_cylinder_6.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_b.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_1.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_2.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_3.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_4.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_5.'</td>';
                        $tblrow .= '<td>'.$aircraftmaintenginecylhistory->engine2_cylinder_6.'</td>';
                        $tblrow .= '</tr>';
                        
                        $response = ['status'=>'success', 'message'=>'', 'tblrow'=>$tblrow];

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintProp(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_prop_id'])){
                        $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->get($postData['maintenance_prop_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->patchEntity($aircraftmaintprops, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceProps->save($aircraftmaintprops)) {
                        $id = $aircraftmaintprops->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintApplianceInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintappliances = $this->CustomerAircraftMaintenanceAppliances->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_appliance_id'])){
                        $aircraftmaintappliances = $this->CustomerAircraftMaintenanceAppliances->get($postData['maintenance_appliance_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftmaintappliances = $this->CustomerAircraftMaintenanceAppliances->patchEntity($aircraftmaintappliances, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceAppliances->save($aircraftmaintappliances)) {
                        $id = $aircraftmaintappliances->id;
                        $tblrow = $this->CustomerOTC->getAircraftMaintApplianceListHtml($postData['aircraft_id']);
                        
                        $response = ['status'=>'success', 'message'=>'', 'tblrow'=>$tblrow, 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function fetchAircraftMaintApplianceInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                
                $applianceinfo = $this->CustomerAircraftMaintenanceAppliances->newEmptyEntity();
                
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                
                if(!empty($postData['maintenance_appliance_id'])){
                    $aircraftmaintappliances = $this->CustomerAircraftMaintenanceAppliances->get($postData['maintenance_appliance_id']);
                    $aircraft_id = $aircraftmaintappliances->aircraft_id;
                    
                    $aircraftarr = $this->CustomerOTCAircrafts->get($aircraft_id);
                    $engine_type = $aircraftarr->aircraft_engine_type;

                    $this->set(compact('aircraftmaintappliances', 'aircraft_id'));
                    $this->viewBuilder()->setLayout('ajax');
                    
                    $propFileName = '';
                    if($engine_type == '1' || $engine_type == '2'){
                        $propFileName = 'aircraft_maintenance_single_appliances';
                    }else if($engine_type == '3' || $engine_type == '4' || $engine_type == '5'){
                        $propFileName = 'aircraft_maintenance_turbine_appliances';
                    }

                    $this->render('/element/Inventory/customer_otc/'.$propFileName);
                }else{
                    $response = 'failure';
                    echo $response;die;
                }
                
            }
        }

        public function removeAircraftMaintApplianceInfoDet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['id'])){
                        $entity = $this->CustomerAircraftMaintenanceAppliances->get($postData['id']);
                        $result = $this->CustomerAircraftMaintenanceAppliances->delete($entity);

                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintAdsInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_ad_id'])){
                        $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->get($postData['maintenance_ad_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->patchEntity($aircraftmaintads, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceAds->save($aircraftmaintads)) {
                        $id = $aircraftmaintads->id;
                        $tblrow = $this->CustomerOTC->getAircraftMaintAdsListHtml($postData['aircraft_id']);
                        
                        $response = ['status'=>'success', 'message'=>'', 'tblrow'=>$tblrow, 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function fetchAircraftMaintAdsInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->newEmptyEntity();
               
                if(!empty($postData['maintenance_ad_id'])){
                    $aircraftmaintads = $this->CustomerAircraftMaintenanceAds->get($postData['maintenance_ad_id']);
                    
                    $aircraft_id = $aircraftmaintads->aircraft_id;
                    
                    $aircraftarr = $this->CustomerOTCAircrafts->get($aircraft_id);
                    $engine_type = $aircraftarr->aircraft_engine_type;

                    $this->set(compact('aircraftmaintads', 'aircraft_id'));
                    $this->viewBuilder()->setLayout('ajax');
                    
                    $propFileName = '';
                    if($engine_type == '1' || $engine_type == '2'){
                        $propFileName = 'aircraft_maintenance_single_ad';
                    }else if($engine_type == '3' || $engine_type == '4' || $engine_type == '5'){
                        $propFileName = 'aircraft_maintenance_turbine_ad';
                    }
                    
                    $this->render('/element/Inventory/customer_otc/'.$propFileName);
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
                
            }
        }

        public function removeAircraftMaintAdsInfoDet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['id'])){
                        $entity = $this->CustomerAircraftMaintenanceAds->get($postData['id']);
                        $result = $this->CustomerAircraftMaintenanceAds->delete($entity);

                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftMaintenanceNotes(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $aircraftmaintnotes = $this->CustomerAircraftMaintenanceNotes->newEmptyEntity();
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['maintenance_note_id'])){
                        $aircraftmaintnotes = $this->CustomerAircraftMaintenanceNotes->get($postData['maintenance_note_id']);

                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $aircraftmaintnotes = $this->CustomerAircraftMaintenanceNotes->patchEntity($aircraftmaintnotes, $postData);
                    
                    if ($this->CustomerAircraftMaintenanceNotes->save($aircraftmaintnotes)) {
                        $id = $aircraftmaintnotes->id;
                        
                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveCustomerOTCInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;
                    
                    $customerotcinfoes = $this->CustomerOTCInfoes->find('all')->where(['customer_id'=>$postData['customer_id']])->select($this->CustomerOTCInfoes)->first();
                    if(!empty($customerotcinfoes)){
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $customerotcinfoes = $this->CustomerOTCInfoes->newEmptyEntity();
                    }
                    
                    $customerotcinfoes = $this->CustomerOTCInfoes->patchEntity($customerotcinfoes, $postData);
                    
                    if ($this->CustomerOTCInfoes->save($customerotcinfoes)) {
                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveCustomerOTCInfoInvoice(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['customer_otc_invoice_id'])){
                        $customerotcinfoinvoices = $this->CustomerOTCInfoInvoices->get($postData['customer_otc_invoice_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $customerotcinfoinvoices = $this->CustomerOTCInfoInvoices->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $customerotcinfoinvoices = $this->CustomerOTCInfoInvoices->patchEntity($customerotcinfoinvoices, $postData);
                    
                    if ($this->CustomerOTCInfoInvoices->save($customerotcinfoinvoices)) {
                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveCustomerOTCInfoInvoicePart(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;
                    
                    $customerotcinfoinvoices = $this->CustomerOTCInfoInvoiceParts->newEmptyEntity();
                    if(!empty($postData['otc_invoice_part_id'])){
                        $customerotcinfoinvoices = $this->CustomerOTCInfoInvoiceParts->get($postData['otc_invoice_part_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    $postData['give_discount_percentage'] = !empty($postData['give_discount']) ? $postData['give_discount_percentage'] : '0.00';
                    
                    $customerotcinfoinvoices = $this->CustomerOTCInfoInvoiceParts->patchEntity($customerotcinfoinvoices, $postData);
                    
                    if ($this->CustomerOTCInfoInvoiceParts->save($customerotcinfoinvoices)) {
                        $invoiceparthisttblrow = $this->CustomerOTC->OTCInvoicePartHistHTML($postData['customer_id']);
                        
                        $otfinfodata = $this->CustomerOTC->allPartOnInvoiceHTML($postData['otc_invoice_id']);
                        $otcinfotblrow = $otfinfodata['tblrow'];
                        $totalpartsubtotal = $otfinfodata['totalpartsubtotal'];

                        $response = ['status'=>'success', 'message'=>'', 'invoiceparthisttblrow'=>$invoiceparthisttblrow, 'otcinfotblrow'=>$otcinfotblrow, 'totalpartsubtotal'=>$totalpartsubtotal];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function removeCustomerOTCInfoInvoicePart(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    $customerotcinfoinvoices = $this->CustomerOTCInfoInvoiceParts->get($postData['otc_invoice_part_id']);
                    $customer_id = $customerotcinfoinvoices->customer_id;
                    $otc_invoice_id = $customerotcinfoinvoices->otc_invoice_id;

                    $result = $this->CustomerOTCInfoInvoiceParts->delete($customerotcinfoinvoices);
                    
                    if ($result) {
                        $invoiceparthisttblrow = $this->CustomerOTC->OTCInvoicePartHistHTML($customer_id);
                        
                        $otfinfodata = $this->CustomerOTC->allPartOnInvoiceHTML($otc_invoice_id);
                        $otcinfotblrow = $otfinfodata['tblrow'];
                        $totalpartsubtotal = $otfinfodata['totalpartsubtotal'];

                        $response = ['status'=>'success', 'message'=>'', 'invoiceparthisttblrow'=>$invoiceparthisttblrow, 'otcinfotblrow'=>$otcinfotblrow, 'totalpartsubtotal'=>$totalpartsubtotal];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWorkOrderDet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                foreach($postData as $key=>$val){
                    $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                }
                //echo "<pre>";print_r($postData);exit;
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    
                    $postData['item_is_warranty'] = isset($postData['item_is_warranty']) ? $postData['item_is_warranty'] : '0';
                    $postData['donot_use_inlogbook'] = isset($postData['donot_use_inlogbook']) ? $postData['donot_use_inlogbook'] : '0';
                    $postData['requires_rii'] = isset($postData['requires_rii']) ? $postData['requires_rii'] : '0';
                    $postData['special_hourly_rate_for_item'] = isset($postData['special_hourly_rate_for_item']) ? $postData['special_hourly_rate_for_item'] : '0';
                    $postData['labor_is_taxable'] = isset($postData['labor_is_taxable']) ? $postData['labor_is_taxable'] : '0';
                    $postData['is_lead_tech_on_item'] = isset($postData['is_lead_tech_on_item']) ? $postData['is_lead_tech_on_item'] : '0';
                    $postData['currently_on_overtime'] = isset($postData['currently_on_overtime']) ? $postData['currently_on_overtime'] : '0';
                    
                    $customeraircraftworkorders = $this->CustomerAircraftWorkOrders->newEmptyEntity();
                    if(!empty($postData['work_order_id'])){
                        $customeraircraftworkorders = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
                        $postData['aircraft_id'] = $customeraircraftworkorders->aircraft_id;
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $customeraircraftworkorders = $this->CustomerAircraftWorkOrders->patchEntity($customeraircraftworkorders, $postData);
                    
                    if ($this->CustomerAircraftWorkOrders->save($customeraircraftworkorders)) {
                        $work_order_id = $customeraircraftworkorders->id;
                        $postData['work_order_id'] = $work_order_id;

                        if(!empty($postData['wo_item_id'])){
                            $customeraircraftwoitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
                            if($customeraircraftwoitems->wo_item_status == '3'){
                                $wo_item_id = $postData['wo_item_id'];
                                $wo_overviews_id = '';
                                $wo_services_id = '';

                                $response = ['status'=>'success', 'message'=>'', 'wo_item_id'=>$wo_item_id, 'wo_id'=>$work_order_id, 'wo_overviews_id'=>$wo_overviews_id, 'wo_services_id'=>$wo_services_id];

                                echo json_encode($response);die;
                            }
                        }

                        //save work order items
                        $wo_item_id = $this->saveAircraftWOItems($postData);
                        $postData['wo_item_id'] = $wo_item_id;

                        //save work order item overviews
                        $wo_overviews_id = $this->saveAircraftWOItemOverviews($postData);

                        //save work order item services
                        $wo_services_id = '';
                        if(!empty($postData['repair_technician'])){
                            $wo_services_id = $this->saveAircraftWOItemServices($postData, 'nxtprev');
                        }

                        $response = ['status'=>'success', 'message'=>'', 'wo_item_id'=>$wo_item_id, 'wo_id'=>$work_order_id, 'wo_overviews_id'=>$wo_overviews_id, 'wo_services_id'=>$wo_services_id];

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function validateWOStatusComplPassword(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;

                    if (!empty($postData['work_order_id']) && !empty($postData['completionpassword'])) {
                        $work_order_id = $postData['work_order_id'];
                        $aircraftworkorders = $this->CustomerAircraftWorkOrders->get($work_order_id);
                        $old_work_order_status = $aircraftworkorders->wo_status;

                        $validateaccesscode = $this->CustomerOTC->validateUserAccessCode($postData['completionpassword']);
                        if($validateaccesscode == '0'){
                            $response = ['status'=>'failure', 'message'=>'Invalid access code.'];
                            echo json_encode($response);die;
                        }
                        
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                        if(empty($aircraftworkorders->work_completed_date) && $postData['wo_status'] == '6'){
                            $postData['work_completed_date'] = $postData['updated_at'];
                        }
                        $aircraftworkorders = $this->CustomerAircraftWorkOrders->patchEntity($aircraftworkorders, $postData);
                    
                        if ($this->CustomerAircraftWorkOrders->save($aircraftworkorders)) {
                            $response = ['status'=>'success', 'message'=>''];

                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAndGetAircraftWODet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;

                    if (!empty($postData['work_order_id'])) {
                        //delete work order items
                        if(!empty($postData['is_delete_item']) && !empty($postData['wo_item_id'])){
                            $entity = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
                            $result = $this->CustomerAircraftWOItems->delete($entity);
                            if(!empty($postData['last_item_position']) && $postData['last_item_position'] == '1'){
                                $postData['is_new_item'] = '1';
                            }
                        }

                        //save work order items
                        if(!empty($postData['is_new_item'])){
                            
                            $wo_item_id = $this->saveAircraftWOItems($postData);
                            //$postData['wo_item_id'] = $wo_item_id;
                            $woitems = $this->CustomerAircraftWOItems->get($wo_item_id);
                            $postData['current_item_position'] = $woitems->wo_item_position;
                            $postData['wo_item_id'] = $wo_item_id;
                            
                            //save work order item overviews
                            $wo_overviews_id = $this->saveAircraftWOItemOverviews($postData);
                            unset($postData['wo_item_id']);
                        }
                        
                        $aircraftworkorders = $this->CustomerAircraftWorkOrders->newEmptyEntity();
                        $aircraftwoitems = $this->CustomerAircraftWOItems->newEmptyEntity();

                        $workorderalldata = $this->CustomerOTC->getWrkOrderAllTabData($postData);
                        extract($workorderalldata);

                        $userdet = $this->Users->find('all')->where(['suspended'=>'0'])->select(['Users.id', 'Users.full_name']);
                        $userlist = [];

                        foreach($userdet as $users){
                            $userlist[$users['id']] = $users['full_name'];
                        }

                        $sessionUser = $this->request->getSession()->read('Auth');;

                        $inventorycustomers = $this->InventoryCustomers->get($postData['customer_id']);
                        $userMenuItems = $this->CustomerOTC->checkWorkOrderMenuPermission();

                        $this->set(compact('inventorycustomers', 'totalitemcount', 'aircraftwoitems', 'aircraftworkorders', 'aircraftwoitemoverviews', 'aircraftwoitemservices', 'technicianData', 'aircraftwoitemosrinfoes', 'userlist', 'sessionUser', 'current_item_position', 'wo_item_positions', 'wo_item_position_index', 'aircraftwoitemphotoes', 'aircraftwoitemfiles', 'aircraftwoitemparts', 'woitemtoollists', 'woitemhistorylist', 'woitemsummary', 'userMenuItems', 'signoffincompletes'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/aircraft_create_wo_item_section');
                    }else{
                        $response = 'failure';
                        echo $response;die;
                    }
                }else{
                    $response = 'failure';
                    echo $response;die;
                }
            }
        }

        public function getWorkOrderItemDetails(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();

                    $workorderalldata = $this->CustomerOTC->getWrkOrderAllTabData($postData);
                    extract($workorderalldata);

                    $userdet = $this->Users->find('all')->where(['suspended'=>'0'])->select(['Users.id', 'Users.full_name']);
                    $userlist = [];

                    foreach($userdet as $users){
                        $userlist[$users['id']] = $users['full_name'];
                    }

                    $sessionUser = $this->request->getSession()->read('Auth');;

                    $inventorycustomers = $this->InventoryCustomers->get($postData['customer_id']);
                    $userMenuItems = $this->CustomerOTC->checkWorkOrderMenuPermission();

                    $this->set(compact('inventorycustomers', 'totalitemcount', 'aircraftwoitems', 'aircraftworkorders', 'aircraftwoitemoverviews', 'aircraftwoitemservices', 'technicianData', 'aircraftwoitemosrinfoes', 'userlist', 'sessionUser', 'current_item_position', 'wo_item_positions', 'wo_item_position_index', 'aircraftwoitemphotoes', 'aircraftwoitemfiles', 'aircraftwoitemparts', 'woitemtoollists', 'woitemhistorylist', 'woitemsummary', 'userMenuItems', 'signoffincompletes'));

                    $this->viewBuilder()->setLayout('ajax');
                    $this->render('/element/Inventory/customer_otc/aircraft_create_wo_item_section');
                }else{
                    $response = 'failure';
                    echo $response;die;
                }
            }
        }
        
        public function saveWorkOrderItemNote(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $wo_item_id = $postData['wo_item_id'];
                    if (!empty($postData['wo_item_id'])) {
                        $customeraircraftwoitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                        $customeraircraftwoitems = $this->CustomerAircraftWOItems->patchEntity($customeraircraftwoitems, $postData);
                        $this->CustomerAircraftWOItems->save($customeraircraftwoitems);
                        
                        $this->AircraftWOItemHistory->saveWorkOrderItemHistory($customeraircraftwoitems);

                        $response = ['status'=>'success', 'message'=>'', 'wo_item_id'=>$wo_item_id];

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWOItems($postData){
            $customeraircraftwoitems = $this->CustomerAircraftWOItems->newEmptyEntity();
            
            $discrepancy = '';
            $corrective_action = '';
            $authUserData = $this->Authentication->getResult()->getData();

            if(!empty($postData['wo_item_id'])){
                $customeraircraftwoitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);

                $discrepancy = $customeraircraftwoitems->wo_discrepancy;
                $corrective_action = $customeraircraftwoitems->wo_corrective_action;

                $postData['updated_by'] = $authUserData['id'];
                $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
            }else{
                $woitemlist = $this->CustomerAircraftWOItems->find('all', array('order' => 'wo_item_position DESC'))->where(['work_order_id'=>$postData['work_order_id']])->first();

                $wo_item_position = !empty($woitemlist) ? $woitemlist->wo_item_position+1 : 1;

                $postData['wo_item_position'] = $wo_item_position;
                $postData['added_by'] = $authUserData['id'];
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
            }

            $postData['wo_created_by'] = !empty($postData['wo_created_by']) ? $postData['wo_created_by'] : $authUserData['id'];
            $postData['wo_item_status'] = !empty($postData['wo_item_status']) ? $postData['wo_item_status'] : (!empty($postData['wo_item_current_status']) ? $postData['wo_item_current_status'] : '1');
            $postData['show_on_estimate_invoice'] = !empty($postData['show_on_estimate_invoice']) ? $postData['show_on_estimate_invoice'] : '0';
            
            $customeraircraftwoitems = $this->CustomerAircraftWOItems->patchEntity($customeraircraftwoitems, $postData);
            $this->CustomerAircraftWOItems->save($customeraircraftwoitems);
            $wo_item_id = $customeraircraftwoitems->id;

            if(empty($postData['wo_item_id'])){
                $this->AircraftWOItemHistory->saveWorkOrderItemHistory($customeraircraftwoitems);
            }

            if(!empty($postData['wo_discrepancy']) || !empty($discrepancy)){
                $discrepancydata = [];
                $discrepancydata['work_order_id'] = $postData['work_order_id'];
                $discrepancydata['wo_item_id'] = $wo_item_id;
                $discrepancydata['wo_discrepancy'] = $postData['wo_discrepancy'];
                $discrepancydata['added_by'] = $authUserData['id'];
                
                $this->saveWOItemDiscrepancyData($discrepancydata);
            }

            if(!empty($postData['wo_corrective_action']) || !empty($corrective_action)){
                $correctiveactiondata = [];
                $correctiveactiondata['work_order_id'] = $postData['work_order_id'];
                $correctiveactiondata['wo_item_id'] = $wo_item_id;
                $correctiveactiondata['wo_corrective_action'] = $postData['wo_corrective_action'];
                $correctiveactiondata['added_by'] = $authUserData['id'];
                $this->saveWOItemCorrectiveActionData($correctiveactiondata);
            }

            if(!empty($postData['wo_item_id']) && $customeraircraftwoitems->wo_item_status == '3'){
                $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select(['CustomerAircraftWOItemParts.part_number', 'CustomerAircraftWOItemParts.serial_number']);

                if($aircraftwoitemparts->count() > 0){
                    foreach($aircraftwoitemparts as $parts){
                        if(!empty($parts['part_number']) && !empty($parts['serial_number'])){
                            $inventory_status = '11';
                            $inventoryitemdet = $this->CustomerOTC->getInventroyItemInventoriesDet($parts['part_number'], $parts['serial_number'], $inventory_status);
                            if(!empty($inventoryitemdet) && $inventoryitemdet['status'] == '11'){
                                $inventory_id = $inventoryitemdet['id'];

                                $this->Inventories->updateAll(
                                    array('updated_by'=>$authUserData['id'], 'modified'=>new \Cake\I18n\FrozenTime('now'), 'status'=>'2'),
                                    array('id' => $inventory_id)
                                );
                                $title = 'Physical Inventory '.$parts['part_number'].' '.$parts['serial_number'].' was updated.';
                                
                                $aircraftwoitems = $this->CustomerOTC->getWOItemDetailByItemId($postData['wo_item_id']);

                                $description = 'Inventory install on Work Order No. '.$aircraftwoitems['work_order_no'].' Item No. '.$aircraftwoitems['wo_items']['wo_item_position'];
                                
                                $this->AircraftWOItemHistory->saveDynamicInventoryHistory($inventory_id, $title, $description);
                            }
                        }
                    }
                }
            }

            return $wo_item_id;
        }

        public function saveAircraftWOItemOverviews($postData){
            $woitemoverviews = $this->CustomerAircraftWOItemOverviews->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select($this->CustomerAircraftWOItemOverviews)->first();
            $authUserData = $this->Authentication->getResult()->getData();

            $isnew = 0;
            if(!empty($woitemoverviews)){
                $isnew = 1;
                $postData['updated_by'] = $authUserData['id'];
                $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
            }else{
                $woitemoverviews = $this->CustomerAircraftWOItemOverviews->newEmptyEntity();

                $postData['added_by'] = $authUserData['id'];
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
            }

            $postData['owner_authentication'] = !empty($postData['owner_authentication']) ? $postData['owner_authentication'] : '2';
            $postData['way_of_billing'] = !empty($postData['way_of_billing']) ? $postData['way_of_billing'] : '1';
            $postData['department'] = !empty($postData['department']) ? $postData['department'] : '1';
            $postData['wo_category'] = !empty($postData['wo_category']) ? $postData['wo_category'] : '7';

            $postData['special_rate_hr'] = preg_replace('/[^0-9.]/', '', $postData['special_rate_hr']);
            $postData['estimated_rate']  = preg_replace('/[^0-9.]/', '', $postData['estimated_rate']);
            $postData['flat_rate']       = preg_replace('/[^0-9.]/', '', $postData['flat_rate']);
            $postData['shipping_in']     = preg_replace('/[^0-9.]/', '', $postData['shipping_in']);
            
            $woitemoverviews = $this->CustomerAircraftWOItemOverviews->patchEntity($woitemoverviews, $postData);
            $this->CustomerAircraftWOItemOverviews->save($woitemoverviews);
            $wo_overviews_id = $woitemoverviews->id;

            if(empty($isnew)){
                $history_title = 'Item Overview was created.';
                $this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($woitemoverviews, $history_title);
            }

            return $wo_overviews_id;
        }

        public function saveAircraftWOItemServices($postData, $source = ''){
            $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->newEmptyEntity();
            $woitemservicelogs = $this->AircraftWOItemServiceLogs->newEmptyEntity();
            $authUserData = $this->Authentication->getResult()->getData();

            $postData['service_rate_an_hour'] = !empty($postData['service_rate_an_hour']) ? str_replace('$','',$postData['service_rate_an_hour']) : '0.00';

            $postData['wo_item_id'] = !empty($postData['wo_item_id']) ? $postData['wo_item_id'] : $postData['service_wo_item_id'];
            
            $currentdatetime =  new \Cake\I18n\FrozenTime('now');
            
            $postData['is_lead_tech_on_item'] = !empty($postData['is_lead_tech_on_item']) ? $postData['is_lead_tech_on_item'] : '0';
            $postData['currently_on_overtime'] = !empty($postData['currently_on_overtime']) ? $postData['currently_on_overtime'] : '0';

            if(!empty($postData['wo_services_id'])){
                $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->get($postData['wo_services_id']);
            }

            if(!empty($postData['wo_services_id']) && empty($source)){

                $postData['updated_by'] = $authUserData['id'];
                $postData['updated_at'] = $currentdatetime;
                
                $postData['service_add_time'] = !empty($postData['service_add_time']) ? $postData['service_add_time'] : 0;

                $calulatedtime = 0;
                if(!empty($postData['service_add_time'])){
                    if(!empty($postData['currently_on_overtime'])){
                        $postData['service_overtime_hrs'] += $postData['service_add_time'];
                    }
                    $postData['service_override_hrs'] += $postData['service_add_time'];

                    $postData['login_time'] = $currentdatetime;
                    $postData['logout_time'] = $currentdatetime;

                    $postData['hours_worked'] = $postData['service_add_time'];
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                }else if(isset($postData['is_timer_start']) && $postData['is_timer_start'] == '1'){
                    $postData['login_time'] = $currentdatetime;
                    $postData['hours_worked'] = '0.00';
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                }else if(isset($postData['is_timer_start']) && $postData['is_timer_start'] == '2'){
                    $woitemservicelogs = $this->AircraftWOItemServiceLogs->find('all')->where(['wo_services_id'=>$postData['wo_services_id'], 'logout_time is'=>NULL])->select($this->AircraftWOItemServiceLogs)->first();

                    $postData['logout_time'] = $currentdatetime;

                    $milliseconds = strtotime($postData['logout_time']) - strtotime($woitemservicelogs->login_time);
                    $calulatedtime = $milliseconds / 3600;
                    $calulatedtime = round($calulatedtime, 2);

                    $postData['hrs_worked'] += $calulatedtime;
                    $postData['total_hrs_for_tech'] = $postData['total_hrs_for_tech']+$calulatedtime;
                    $postData['total_hrs_for_item'] = $postData['total_hrs_for_item']+$calulatedtime;
                    $postData['hours_worked'] = $calulatedtime;

                    if(!empty($postData['currently_on_overtime'])){
                        $postData['service_overtime_hrs'] += $calulatedtime;
                    }
                }
                
                $postData['total_hrs_for_tech'] = $postData['service_override_hrs']+$postData['hrs_worked'];
                $totalitemhour = $this->CustomerOTC->getWOItemServicesTotalTechHrs($postData['wo_item_id'], $postData['wo_services_id']);

                $totalitemhour = !empty($totalitemhour) ? $totalitemhour : 0;
                $totalitemhour += $postData['total_hrs_for_tech'];
                $postData['total_hrs_for_item'] = $totalitemhour;

                $this->CustomerAircraftWOItemServices->updateAll(
                    array('total_hrs_for_item'=>$postData['total_hrs_for_item']),
                    array('wo_item_id' => $postData['wo_item_id'])
                );
                
                if(!empty($postData['service_add_time']) || !empty($postData['is_timer_start'])){
                    $woitemservicelogs = $this->AircraftWOItemServiceLogs->patchEntity($woitemservicelogs, $postData);
                    $this->AircraftWOItemServiceLogs->save($woitemservicelogs);
                }
                unset($postData['added_by']);
                unset($postData['created_at']);

                $AircraftWOItemHistoriesModel =  $this->fetchTable('AircraftWOItemHistories');
                $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
                $AircraftWOItemHistories->wo_item_id = $postData['wo_item_id'];

                $AircraftWOItemHistories->title = 'Service was updated.';
                $description = '';
                if(!empty($postData['logout_time'])){
                    $description .= 'Logout time was changed from "" to "'.$postData['logout_time'].'".<br/>';
                }else{
                    $description .= 'Login time was changed from "" to "'.$postData['login_time'].'".<br/>';
                }
                if(!empty($description)){
                    $AircraftWOItemHistories->user_id = $authUserData['id'];
                    $AircraftWOItemHistories->description = $description;
                    $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
                }
            }else{
                $aircraftwoitemservicesdata = $this->CustomerAircraftWOItemServices->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->first();

                $postData['total_hrs_for_item'] = !empty($aircraftwoitemservicesdata) ? $aircraftwoitemservicesdata->total_hrs_for_item : '0';
                $postData['added_by'] = $authUserData['id'];
                $postData['service_rate_an_hour'] = !empty($postData['service_rate_an_hour']) && $postData['service_rate_an_hour'] != '0.00' ? $postData['service_rate_an_hour'] : ESTIMATEDRATE;
                $postData['technician_billing_style'] = !empty($postData['technician_billing_style']) ? $postData['technician_billing_style'] : '1';
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
            }

            $aircraftwoitemservices = $this->CustomerAircraftWOItemServices->patchEntity($aircraftwoitemservices, $postData);
            $this->CustomerAircraftWOItemServices->save($aircraftwoitemservices);
            
            $wo_services_id = $aircraftwoitemservices->id;

            if(empty($postData['wo_services_id'])){
                $history_title = 'Item Services was created.';
                $this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($aircraftwoitemservices, $history_title);
            }

            return $wo_services_id;
        }

        public function saveAircraftWOItemOSRInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    $customeraircraftwoitemosrinfo = $this->CustomerAircraftWOOSRInfoes->newEmptyEntity();
                    if(!empty($postData['wo_osrinfo_id'])){
                        $customeraircraftwoitemosrinfo = $this->CustomerAircraftWOOSRInfoes->get($postData['wo_osrinfo_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                    }
                    $postData['is_add_to_po'] = !empty($postData['is_add_to_po']) ? $postData['is_add_to_po'] : '0';

                    $osr_po_id = 0;
                    if(!empty($postData['is_add_to_po']) && empty($customeraircraftwoitemosrinfo->osr_purchase_order_no)){
                        
                        $woitemosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->find('all')->where(['po_no'=>$postData['osr_purchase_order_no']])->select($this->CustomerAircraftWOOSRInfoPOes)->first();
                        if(empty($woitemosrinfopo)){
                            $woitemosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->newEmptyEntity();
                        }
                        $osrinfopodata = [];
                        $osrinfopodata['po_no'] = $postData['osr_purchase_order_no'];
                        $osrinfopodata['vendor_id'] = $postData['osr_repair_done_by'];
                        $osrinfopodata['date_order_placed'] = new \Cake\I18n\FrozenTime('now');
                        $osrinfopodata['po_status'] = '1';
                        $osrinfopodata['created_by'] = $authUserData['id'];
                        $osrinfopodata['added_by'] = $authUserData['id'];
                        $osrinfopodata['created_at'] = new \Cake\I18n\FrozenTime('now');
                        
                        $woitemosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->patchEntity($woitemosrinfopo, $osrinfopodata);
                        $this->CustomerAircraftWOOSRInfoPOes->save($woitemosrinfopo);
                        $osr_po_id = $woitemosrinfopo->id;

                        //$history_title = 'Outside Repair Service P/O was created.';
                        //$this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($woitemosrinfopo, $history_title);
                        
                        $woitemosrpoitems = $this->CustomerAircraftWOOSRPOItems->newEmptyEntity();
                        $osrpoitemdata = [];
                        $osrpoitemdata['osr_po_id'] = $osr_po_id;
                        $osrpoitemdata['destination_id'] = '1';
                        $osrpoitemdata['destination'] = $postData['work_order_id'];
                        $osrpoitemdata['item_no'] = '1';
                        $osrpoitemdata['part_number'] = $postData['osr_part_number'];
                        $osrpoitemdata['created_by'] = $authUserData['id'];
                        $osrpoitemdata['added_by'] = $authUserData['id'];
                        $osrpoitemdata['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $woitemosrpoitems = $this->CustomerAircraftWOOSRPOItems->patchEntity($woitemosrpoitems, $osrpoitemdata);
                        $this->CustomerAircraftWOOSRPOItems->save($woitemosrpoitems);

                        //$history_title = 'Outside Repair Service P/O Item was created.';
                        //$this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($woitemosrpoitems, $history_title);
                    }

                    if(!empty($postData['is_create_new_ro']) && empty($customeraircraftwoitemosrinfo->osr_invoice_no)){
                        $aircraftworkorders = $this->CustomerAircraftWorkOrders->find('all')->where(['work_order_no'=>$postData['osr_invoice_no']])->select($this->CustomerAircraftWorkOrders)->first();
                        if(empty($aircraftworkorders)){
                            $aircraftworkorders = $this->CustomerAircraftWorkOrders->newEmptyEntity();
                        }

                        $aircraftworkorderdet = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
                        
                        $woData = [];
                        $woData['aircraft_id'] = $aircraftworkorderdet->aircraft_id;
                        $woData['wo_customer_id'] = $aircraftworkorderdet->wo_customer_id;
                        $woData['work_order_no'] = $postData['osr_invoice_no'];
                        $woData['order_type'] = '2';
                        $woData['wo_status'] = '1';
                        $woData['added_by'] = $authUserData['id'];
                        $woData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $aircraftworkorders = $this->CustomerAircraftWorkOrders->patchEntity($aircraftworkorders, $woData);
                        
                        $this->CustomerAircraftWorkOrders->save($aircraftworkorders);

                        $woitemArr = [];
                        $woitemArr['work_order_id'] = $aircraftworkorders->id;
                        $wo_item_id = $this->saveAircraftWOItems($woitemArr);
                        $postData['wo_item_id'] = $wo_item_id;

                        //save work order item overviews
                        $wo_overviews_id = $this->saveAircraftWOItemOverviews($postData);
                        //unset($postData['wo_item_id']);

                    }
                    
                    $postData['is_create_new_ro'] = !empty($postData['is_create_new_ro']) ? $postData['is_create_new_ro'] : '0';
                    
                    $customeraircraftwoitemosrinfo = $this->CustomerAircraftWOOSRInfoes->patchEntity($customeraircraftwoitemosrinfo, $postData);
                    $this->CustomerAircraftWOOSRInfoes->save($customeraircraftwoitemosrinfo);
                    $wo_osrinfo_id = $customeraircraftwoitemosrinfo->id;
                    if(empty($postData['wo_osrinfo_id'])){
                        $history_title = 'Item Outside Repair was created.';
                        $this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($customeraircraftwoitemosrinfo, $history_title);
                    }
                    
                    $wo_item_id = $postData['wo_item_id'];
                    $osrlist = $this->getWOItemOSRListHTML($wo_item_id);

                    $response = ['status'=>'success', 'message'=>'', 'wo_osrinfo_id'=>$wo_osrinfo_id, 'osrlist'=>$osrlist];

                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }

            return $wo_osrinfo_id;
        }

        public function saveAircraftWOServicesTechnician(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    
                    $wo_services_id = $this->saveAircraftWOItemServices($postData);
                    if(!empty($wo_services_id)){
                        $postData['wo_service_id'] = $wo_services_id;
                        $this->fetchWorkOrderServicesHtml();
                    }else{
                        echo 'Failuer';die;
                    }
                }else{
                    echo 'Failuer';die;
                }
            }
        }

        public function fetchWorkOrderServicesHtml(){
            $postData = $this->request->getData();
            if(!empty($postData['wo_item_id']) || !empty($postData['service_wo_item_id'])){
                $wo_item_id = !empty($postData['wo_item_id']) ? $postData['wo_item_id'] : $postData['service_wo_item_id'];

                $aircraftwoitemserviceslist = $this->CustomerOTC->getAircraftWOServicesData($wo_item_id, $postData['repair_technician']);
                extract($aircraftwoitemserviceslist);
                $repair_technician_id = $postData['repair_technician'];
                $aircraftwoitems = $this->CustomerAircraftWOItems->get($wo_item_id);

                $this->set(compact('aircraftwoitemservices', 'technicianData', 'repair_technician_id', 'wo_item_id', 'aircraftwoitems'));
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/Inventory/customer_otc/customer_aircraft_wo_service');
            }
        }

        public function saveAircraftWOServicesNote(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    $customeraircraftwoitemservices = $this->AircraftWOItemServiceLogs->newEmptyEntity();
                    if(!empty($postData['wo_services_id'])){
                        $customeraircraftwoitemservices = $this->AircraftWOItemServiceLogs->get($postData['wo_services_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    $customeraircraftwoitemservices = $this->AircraftWOItemServiceLogs->patchEntity($customeraircraftwoitemservices, $postData);
                    if($this->AircraftWOItemServiceLogs->save($customeraircraftwoitemservices)){
                        $response = ['status'=>'success', 'message'=>''];
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    }
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function deleteAircraftWOServices(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_services_id'])){
                        $woitemservices = $this->CustomerAircraftWOItemServices->find('all')->where(['CustomerAircraftWOItemServices.id'=>$postData['wo_services_id']])->first();
                        if(!empty($woitemservices)){
                            $result = $this->CustomerAircraftWOItemServices->delete($woitemservices);
                        }
                        $itemserverices = $this->AircraftWOItemServiceLogs->find('all')->where(['wo_services_id'=>$postData['wo_services_id']])->first();
                        if(!empty($itemserverices)){
                            $wo_item_id = $itemserverices['wo_item_id'];
                            $result = $this->AircraftWOItemServiceLogs->deleteAll([
                                                'wo_services_id' => $postData['wo_services_id']
                                            ]);

                            $total_hours = $this->AircraftWOItemServiceLogs->find()
                                                ->where(['wo_item_id' => $wo_item_id])
                                                ->select([
                                                    'total_hours' => $this->AircraftWOItemServiceLogs->find()->func()->sum('hours_worked')
                                                ])
                                                ->first()
                                                ->total_hours ?? 0.00;

                            $this->CustomerAircraftWOItemServices->updateAll(
                                ['total_hrs_for_item' => $total_hours],
                                ['wo_item_id' => $wo_item_id]
                            );
                        }
                        $this->fetchWorkOrderServicesHtml();
                    }else{
                        $message = 'failure';
                        echo $message;die;
                    }
                    
                }else{
                    $message = 'failure';
                    echo $message;die;
                }
            }
        }

        public function fetchWOOSRCreateHtml(){
            $postData = $this->request->getData();
            $wooutstandingoutside = $this->CustomerAircraftWOOSRInfoes->newEmptyEntity();
            if(!empty($postData['wo_osr_id'])){
                $wooutstandingoutside = $this->CustomerAircraftWOOSRInfoes->get($postData['wo_osr_id']);
            }
            $wo_item_id = $postData['wo_item_id'];
            $work_order_id = $postData['work_order_id'];
            
            $inventoryvendors = $this->CustomerOTC->getWOOSRVendorList();
            $wodetails = $this->CustomerAircraftWorkOrders->get($work_order_id);

            $this->set(compact('wooutstandingoutside', 'inventoryvendors', 'wo_item_id', 'work_order_id', 'wodetails'));
            
            $this->viewBuilder()->setLayout('ajax');
            $this->render('/element/Inventory/customer_otc/aircraft_wo_osr_create_fld');
            
        }

        public function setOSRPOROData(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    if(!empty($postData['addtoporo'])){
                        if($postData['addtoporo'] == '1'){
                            $response = $this->CustomerOTC->getAircraftWOOSRPONumber($postData);
                        }else{
                            $response = $this->CustomerOTC->getAircraftWOOSRRONumber($postData);
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    }
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOOSRVendorDetail(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    $whereArr = ['vendor_name'=>$postData['vendor_name']];
                    if(!empty($postData['osr_vendor_id'])){
                        $whereArr['id !='] = $postData['osr_vendor_id'];
                    }
                    $checkduplicate = $this->CustomerAircraftWOOSRVendors->find('all')->where($whereArr)->select($this->CustomerAircraftWOOSRVendors)->count();
                    if(empty($checkduplicate)){
                        $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->newEmptyEntity();
                        if(!empty($postData['osr_vendor_id'])){
                            $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->get($postData['osr_vendor_id']);
                            $postData['updated_by'] = $authUserData['id'];
                            $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                        }else{
                            $postData['added_by'] = $authUserData['id'];
                            $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        }   
                        
                        $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->patchEntity($aircraftwoosrvendors, $postData);
                        if($this->CustomerAircraftWOOSRVendors->save($aircraftwoosrvendors)){
                            $vendor_id = $aircraftwoosrvendors->id;
                            if(!empty($postData['source'])){
                                $this->fetchWOOSRCreateVendorHtml($vendor_id);
                            }else{
                                $response = 'success';
                                echo $response;die;
                            }
                        }else{
                            $response = $vendor_id;
                            echo $response;die;
                        }
                    }else{
                        $response = 'duplicate';
                        echo $response;die;
                    }
                }else{
                    $response = 'failure';
                    echo $response;die;
                }
            }
        }

        public function fetchWOOSRCreateVendorHtml($vendor_id=''){
            $postData = $this->request->getData();
            $vendor_id = !empty($vendor_id) ? $vendor_id : $postData['vendor_id'];
            $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->newEmptyEntity();
            if(!empty($vendor_id)){
                $aircraftwoosrvendors = $this->CustomerAircraftWOOSRVendors->get($vendor_id);
            }
            
            $inventoryvendors = $this->CustomerOTC->getWOOSRVendorList();
            $this->set(compact('aircraftwoosrvendors'));

            $this->viewBuilder()->setLayout('ajax');
            $this->render('/element/Inventory/customer_otc/aircraft_wo_osr_contact_info');
            
        }

        public function saveWOOSRVendorMedia(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                if (!empty($postData['osr_vendor_id']) && !empty($postData['filenames'])) {
                    $this->InventoryAttachment->saveWOOSRVendorMedia($postData['osr_vendor_id'], $postData);

                    $response = ['status'=>'success', 'message'=>''];
                }else{
                    $response = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($response);die;
            }
        }

        public function uploadWOOSRVendorMedia(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt','mp4');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventorycustomers';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadWOOSRVendorMediaToServer($postData, $filelocation, $foldername);
                    
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

        public function deleteWOOSRVendorMedia(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteWOOSRVendorMedia($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function deleteWOOSRVendor(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['osr_vendor_id'])){
                        $entity = $this->CustomerAircraftWOOSRVendors->get($postData['osr_vendor_id']);
                        $result = $this->CustomerAircraftWOOSRVendors->delete($entity);

                        $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                    
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($result);die;
            }          
        }

        public function fetchWOOSRVendorPhones(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $vendors = $this->CustomerAircraftWOOSRVendors->get($postData['vendor_id']);
                    
                    $woosrvendorphones = [];
                    
                    if(!empty($vendors)){
                        if(!empty($vendors['vendor_phone'])){
                            $woosrvendorphones[$vendors['vendor_phone']] = $vendors['vendor_phone'];
                        }
                        if(!empty($vendors['alt_phone'])){
                            $woosrvendorphones[$vendors['alt_phone']] = $vendors['alt_phone'];
                        }
                        if(!empty($vendors['alt_phone2'])){
                            $woosrvendorphones[$vendors['alt_phone2']] = $vendors['alt_phone2'];
                        }
                    }
                    $result = array('status'=>'success', 'message'=>"", 'woosrvendorphones'=>$woosrvendorphones);
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function saveWOOSRServicePO(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    
                    if(!empty($postData['po_no']) || !empty($postData['osr_infopoes_id'])){
                        $aircraftwoosrinfopoes = $this->CustomerAircraftWOOSRInfoPOes->newEmptyEntity();
                        if(!empty($postData['osr_infopoes_id'])){
                            $aircraftwoosrinfopoes = $this->CustomerAircraftWOOSRInfoPOes->get($postData['osr_infopoes_id']);
                            $postData['updated_by'] = $authUserData['id'];
                            $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                        }else{
                            $postData['added_by'] = $authUserData['id'];
                            $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        }

                        $aircraftwoosrinfopoes = $this->CustomerAircraftWOOSRInfoPOes->patchEntity($aircraftwoosrinfopoes, $postData);
                        if($this->CustomerAircraftWOOSRInfoPOes->save($aircraftwoosrinfopoes)){
                            $osr_infopoes_id = $aircraftwoosrinfopoes->id;
                            $result = array('status'=>'success', 'message'=>"", 'osr_infopoes_id'=>$osr_infopoes_id);
                        }else{
                            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        }
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function saveWOOSRPurchaseOrderMedia(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                if (!empty($postData['osr_info_po_id']) && !empty($postData['filenames'])) {
                    $this->InventoryAttachment->saveWOOSRPurchaseOrderMedia($postData['osr_info_po_id'], $postData);

                    $response = ['status'=>'success', 'message'=>''];
                }else{
                    $response = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }

                echo json_encode($response);die;
            }
        }

        public function uploadWOOSRPurchaseOrderMedia(){
            
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt','mp4');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventorycustomers';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadWOOSRPurchaseOrderMediaToServer($postData, $filelocation, $foldername);
                    
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

        public function deleteWOOSRPurchaseOrderMedia(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteWOOSRPurchaseOrderMedia($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function saveWOOSRPurchaseOrderReminder(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    
                    if(!empty($postData['wo_osr_po_id'])){
                        $reminder_id = [];
                        for($i=0; $i<count($postData['send_reminder_to']); $i++){
                            $reminderdata = [];
                            if(!empty($postData['reminder_id'][$i])){
                                $aircraftwoosrporeminders = $this->CustomerAircraftWOOSRPOReminders->get($postData['reminder_id'][$i]);
                                $reminderdata['updated_by'] = $authUserData['id'];
                                $reminderdata['updated_at'] = new \Cake\I18n\FrozenTime('now');
                            }else{
                                $aircraftwoosrporeminders = $this->CustomerAircraftWOOSRPOReminders->newEmptyEntity();
                                $reminderdata['added_by'] = $authUserData['id'];
                                $reminderdata['created_at'] = new \Cake\I18n\FrozenTime('now');
                            }
                            
                            $reminderdata['wo_osr_po_id']       = $postData['wo_osr_po_id'];
                            $reminderdata['reminder']           = !empty($postData['reminder'][$i]) ? $postData['reminder'][$i] : '';
                            $reminderdata['send_reminder_to']   = $postData['send_reminder_to'][$i];
                            $reminderdata['notify_date_time']   = $postData['notify_date_time'][$i];
                            
                            $aircraftwoosrporeminders = $this->CustomerAircraftWOOSRPOReminders->patchEntity($aircraftwoosrporeminders, $reminderdata);

                            $this->CustomerAircraftWOOSRPOReminders->save($aircraftwoosrporeminders);
                            $reminder_id[] = $aircraftwoosrporeminders->id;
                        }

                        $result = array('status'=>'success', 'message'=>"", 'reminder_id'=>$reminder_id);
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function fetchWOOSRCheckInLaborHTML($vendor_id=''){
            $postData = $this->request->getData();
            
            $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->get($postData['osr_po_item_id']);
            $woosrinfopo = $this->CustomerAircraftWOOSRInfoPOes->get($woosrpoitems['osr_po_id']);
            $woosrinfo = $this->CustomerAircraftWOOSRInfoes->find('all')->where(['osr_purchase_order_no'=>$woosrinfopo->po_no])->select($this->CustomerAircraftWOOSRInfoes)->first();
            
            $woosrvendordata = $this->CustomerOTC->getWOOSRVendorList();
            $allserviceitemspo = $this->CustomerOTC->getAllServiceItemOnPO($woosrpoitems['osr_po_id']);

            $this->set(compact('woosrinfopo', 'woosrvendordata', 'allserviceitemspo', 'woosrinfo', 'woosrpoitems'));

            $this->viewBuilder()->setLayout('ajax');
            $this->render('/element/Inventory/customer_otc/aircraft_osr_po_checkin_labor_info');
            
        }

        public function getExistingPONumByVendorId(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $osrInfoPOData = $this->CustomerAircraftWOOSRInfoPOes->find('all')->where(['vendor_id'=>$postData['vendor_id']])->select($this->CustomerAircraftWOOSRInfoPOes)->all()->last();
                    if(!empty($osrInfoPOData)){
                        $po_no = $osrInfoPOData->po_no;
                        $vendor_name = $postData['vendor_name'];
                        $this->set(compact('po_no', 'vendor_name'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/InventoryPopup/customer_otc/aircraft_wo_osr_addto_currentpo');
                    }else{
                        echo "Not Exist";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function saveWOOSRPOItems(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    
                    $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->newEmptyEntity();
                    if(!empty($postData['osr_po_item_id'])){
                        $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->get($postData['osr_po_item_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    $postData['tax_labor'] = !empty($postData['tax_labor']) ? $postData['tax_labor'] : '0';
                    $postData['tax_part'] = !empty($postData['tax_part']) ? $postData['tax_part'] : '0';

                    $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->patchEntity($woosrpoitems, $postData);
                    if($this->CustomerAircraftWOOSRPOItems->save($woosrpoitems)){
                        $osr_po_item_id = $woosrpoitems->id;

                        $allserviceitemspo = $this->CustomerOTC->getAllServiceItemOnPO($woosrpoitems['osr_po_id']);
                        
                        $total_purchase_order = 0;
                        $serviceitem_po_tr = '';
                        foreach($allserviceitemspo as $key=>$serviceitems){
                            $total = ($serviceitems['labor_cost']+$serviceitems['part_cost']+$serviceitems['ship_out']+$serviceitems['ship_in']);
                            $osr_po_item_active = '';
                            if($key == '0'){
                                $osr_po_item_active = 'osr-po_item-active';
                            }
                            $serviceitem_po_tr .='<tr class="editwoosrpoitem '.$osr_po_item_active.'" data-val="'.$serviceitems['id'].'">';
                            $serviceitem_po_tr .='<td>'.($serviceitems['work_order']['order_type'] == '1' ? 'Work Order' : 'Repair Order').'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['work_order']['work_order_no'].'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['wo_item']['wo_item_position'].'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['part_number'].'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['osr_description_of_work'].'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['labor_cost'].'</td>';
                            $serviceitem_po_tr .='<td>'.$serviceitems['part_cost'].'</td>';
                            $serviceitem_po_tr .='<td>'.$total.'</td>';
                            $serviceitem_po_tr .='</tr>';
                            
                            $total_purchase_order += $total;
                        }

                        $result = array('status'=>'success', 'message'=>"", 'serviceitem_po_tr'=>$serviceitem_po_tr, 'total_purchase_order'=>$total_purchase_order);
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function deleteWOOSRRecord(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    if(!empty($postData['wo_osrinfo_id'])){
                        $woosrinfoes = $this->CustomerAircraftWOOSRInfoes->get($postData['wo_osrinfo_id']);

                        $osrInfoPOData = $this->CustomerAircraftWOOSRInfoPOes->find('all')->where(['po_no'=>$woosrinfoes['osr_purchase_order_no']])->select($this->CustomerAircraftWOOSRInfoPOes);
                        $poids = [];
                        if($osrInfoPOData->count() > 0){
                            foreach($osrInfoPOData as $podata){
                                $poids[] = $podata['id'];
                            }
                        }

                        if(count($poids) > 0){
                            $this->CustomerAircraftWOOSRInfoPOes->deleteAll(['po_no'=>$woosrinfoes['osr_purchase_order_no']]);

                            $this->CustomerAircraftWOOSRPOItems->deleteAll(['CustomerAircraftWOOSRPOItems.osr_po_id IN' => $poids]);
                        }
                        $wo_item_id = $woosrinfoes->wo_item_id;

                        $history_title = 'Item Outside Repair Deleted';
                        $history_description = 'Outside Repair `'.$woosrinfoes->osr_repair_done_by.'` deleted from work order item.';
                        $this->AircraftWOItemHistory->saveWOItemTabDeletedDataToHistory($woosrinfoes, $history_title, $history_description);

                        $result = $this->CustomerAircraftWOOSRInfoes->delete($woosrinfoes);

                        $osrlist = $this->getWOItemOSRListHTML($wo_item_id);

                        $result = array('status'=>'success', 'message'=>"", 'osrlist'=>$osrlist);
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function getWOItemOSRListHTML($wo_item_id){
            $aircraftwoitemosrinfoes = $this->CustomerOTC->getAircraftWOOSRList($wo_item_id);
            $osrlist = '';
            foreach($aircraftwoitemosrinfoes as $key=>$osrinfo){
                $activeclass = '';
                if($key == 0){
                    $activeclass = 'wo-osr-list-active';
                }
                $osrlist .= '<tr class="editwoosritem" data-val="'.$osrinfo['id'].'">';
                $osrlist .= '<td>'.$osrinfo['vendors']['vendor_name'].'</td>';
                $osrlist .= '<td>'.$osrinfo['osr_invoice_no'].'</td>';
                $osrlist .= '<td>'.$osrinfo['osr_part_number'].'</td>';
                $osrlist .= '<td>'.$osrinfo['osr_labor_charge'].'</td>';
                $osrlist .= '<td>'.$osrinfo['osr_parts_charge'].'</td>';
                $osrlist .= '<td>'.$osrinfo['osr_purchase_order_no'].'</td>';
                $osrlist .= '</tr>';
            }

            return $osrlist;
        }

        public function deleteWOOSRPOItemsRecord(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    if(!empty($postData['wo_osr_po_item_id'])){
                        $woosrpoitems = $this->CustomerAircraftWOOSRPOItems->get($postData['wo_osr_po_item_id']);
                        $osr_po_id = $woosrpoitems['osr_po_id'];
                        $this->CustomerAircraftWOOSRPOItems->delete($woosrpoitems);

                        $allserviceitemspo = $this->CustomerOTC->getAllServiceItemOnPO($osr_po_id);
                        $poitemlist = '';
                        foreach($allserviceitemspo as $key=>$serviceitems){
                            $firstelem = $key==0 ? 'osr-po_item-active' : '';
                            $order_type = $serviceitems['work_order']['order_type'] == '1' ? 'Work Order' : 'Repair Order';
                            $poitemlist .= '<tr class="editwoosrpoitem '.$firstelem.'" data-val="'.$serviceitems['id'].'">';
                            $poitemlist .= '<td>'.$order_type.'</td>';
                            $poitemlist .= '<td>'.$serviceitems['work_order']['work_order_no']. '</td>';
                            $poitemlist .= '<td>1</td>';
                            $poitemlist .= '<td>'.$serviceitems['part_number']. '</td>';
                            $poitemlist .= '<td>'.$serviceitems['osr_description_of_work']. '</td>';
                            $poitemlist .= '<td>'.$serviceitems['labor_cost']. '</td>';
                            $poitemlist .= '<td>'.$serviceitems['part_cost']. '</td>';
                            $poitemlist .= '<td>'.($serviceitems['labor_cost']+$serviceitems['part_cost']+$serviceitems['ship_out']+$serviceitems['ship_in']). '</td>';
                            $poitemlist .= '</tr>';
                        }
                        
                        $result = array('status'=>'success', 'message'=>"", 'poitemlist'=>$poitemlist);
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                    echo json_encode($result);die;
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($result);die;
                }
                
            }
        }

        public function saveAircraftWOATACode(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    
                    $woitematacodescount = $this->CustomerAircraftWOATACodes->find('all')->where(['ata_code'=>$postData['ata_code']])->select($this->CustomerAircraftWOATACodes)->count();

                    $woitematacodes = $this->CustomerAircraftWOATACodes->newEmptyEntity();
                    if(empty($woitematacodescount)){
                        
                        if(!empty($postData['wo_ata_code_id'])){
                            $woitematacodes = $this->CustomerAircraftWOATACodes->get($postData['wo_ata_code_id']);
                            $postData['updated_by'] = $authUserData['id'];
                            $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                        }else{
                            $postData['user_id'] = $authUserData['id'];
                            $postData['added_by'] = $authUserData['id'];
                            $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        }
                        
                        $woitematacodes = $this->CustomerAircraftWOATACodes->patchEntity($woitematacodes, $postData);
                        if($this->CustomerAircraftWOATACodes->save($woitematacodes)){
                            $wo_ata_code_id = $woitematacodes->id;
                            $result = array('status'=>'success', 'message'=>"", 'wo_ata_code_id'=>$wo_ata_code_id);
                        }else{
                            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        }
                    }else{
                        $result = array('status'=>'failure', 'message'=>'This ATA code already exists. Please try again with a different ATA code.');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function saveAircraftWOLaborKit(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    $woitemlaborkitscount = $this->CustomerAircraftWOLaborKits->find('all')->where(['labor_kit'=>$postData['labor_kit']])->select($this->CustomerAircraftWOLaborKits)->count();
                    if(empty($woitemlaborkitscount)){
                        $woitemlaborkits = $this->CustomerAircraftWOLaborKits->newEmptyEntity();
                        if(!empty($postData['wo_labor_kit_id'])){
                            $woitemlaborkits = $this->CustomerAircraftWOLaborKits->get($postData['wo_labor_kit_id']);
                            $postData['updated_by'] = $authUserData['id'];
                            $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                        }else{
                            $postData['user_id'] = $authUserData['id'];
                            $postData['added_by'] = $authUserData['id'];
                            $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        }
                        
                        $woitemlaborkits = $this->CustomerAircraftWOLaborKits->patchEntity($woitemlaborkits, $postData);
                        if($this->CustomerAircraftWOLaborKits->save($woitemlaborkits)){
                            $wo_labor_kit_id = $woitemlaborkits->id;
                            $result = array('status'=>'success', 'message'=>"", 'wo_labor_kit_id'=>$wo_labor_kit_id);
                        }else{
                            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        }
                    }else{
                        $result = array('status'=>'failure', 'message'=>'This Labor Kit already exists. Please try again with a different Labor Kit.');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                echo json_encode($result);die;
            }
        }

        public function saveAircraftWOLogBookValOverview(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    //echo "<pre>";print_r($postData);exit;
                    $aircraftlogbookvaloverview = $this->CustomerAircraftWOLogBookValueOverviews->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueOverviews)->first(); 
                    if(empty($aircraftlogbookvaloverview)){
                        $aircraftlogbookvaloverview = $this->CustomerAircraftWOLogBookValueOverviews->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $postData['use_hobbs'] = !empty($postData['use_hobbs']) ? $postData['use_hobbs'] : '0';
                    $postData['tach_is_flight_time'] = !empty($postData['tach_is_flight_time']) ? $postData['tach_is_flight_time'] : '0';

                    $aircraftlogbookvaloverview = $this->CustomerAircraftWOLogBookValueOverviews->patchEntity($aircraftlogbookvaloverview, $postData);
                    
                    if ($this->CustomerAircraftWOLogBookValueOverviews->save($aircraftlogbookvaloverview)) {
                        $id = $aircraftlogbookvaloverview->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWOLogBookValEngine(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    $aircraftlogbookvalengine = $this->CustomerAircraftWOLogBookValueEngines->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueEngines)->first(); 
                    if(empty($aircraftlogbookvalengine)){
                        $aircraftlogbookvalengine = $this->CustomerAircraftWOLogBookValueEngines->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftlogbookvalengine = $this->CustomerAircraftWOLogBookValueEngines->patchEntity($aircraftlogbookvalengine, $postData);
                    
                    if ($this->CustomerAircraftWOLogBookValueEngines->save($aircraftlogbookvalengine)) {
                        $id = $aircraftlogbookvalengine->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }
		
		public function saveAircraftWOLogBookValProp(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    //echo "<pre>";print_r($postData);exit;
                    $aircraftlogbookvalprops = $this->CustomerAircraftWOLogBookValueProps->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueProps)->first();
                    if(empty($aircraftlogbookvalprops)){
                        $aircraftlogbookvalprops = $this->CustomerAircraftWOLogBookValueProps->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $aircraftlogbookvalprops = $this->CustomerAircraftWOLogBookValueProps->patchEntity($aircraftlogbookvalprops, $postData);
                    
                    if ($this->CustomerAircraftWOLogBookValueProps->save($aircraftlogbookvalprops)) {
                        $id = $aircraftlogbookvalprops->id;

                        $response = ['status'=>'success', 'message'=>'', 'id'=>$id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                    
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function refreshWOLogBookValWithMaint(){
            $postData = $this->request->getData();
            if(!empty($postData['aircraft_id'])){
                //$logbookdata = $this->CustomerOTC->getAircraftWOLogBookValueTabData($postData);
                //extract($logbookdata);

                $aircraftregdetail = $this->CustomerOTCAircrafts->get($postData['aircraft_id']);
                $engine_type = $aircraftregdetail->aircraft_engine_type;
                $section = 'aircraft_wo_option_logbook_values';

                if($engine_type != '5'){
                    $this->CustomerAircraftMaintenanceOverviews = $this->fetchTable('CustomerAircraftMaintenanceOverviews');

                    $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceOverviews)->first();
                    if(!empty($aircraftmaintoverview)){
                        $aircraftmaintoverview->id = '';
                    }
                }else{
                    $this->AircraftMaintenanceHelicopterOverviews = $this->fetchTable('AircraftMaintenanceHelicopterOverviews');

                    $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceHelicopterOverviews)->first();
                    if(!empty($aircraftmaintoverview)){
                        $aircraftmaintoverview->id = '';
                    }
                }

                $aircraftmaintengine = '';
                $aircraftmaintenginecylhistorylist = '';
                if($engine_type != '5'){
                    if($engine_type == '1' || $engine_type == '2'){
                        $this->CustomerAircraftMaintenanceEngines = $this->fetchTable('CustomerAircraftMaintenanceEngines');
                        $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceEngines)->first(); 
                        if(!empty($aircraftmaintengine)){
                            $aircraftmaintengine->id = '';
                        }
                    }else{
                        $this->AircraftMaintenanceJetEngines = $this->fetchTable('AircraftMaintenanceJetEngines');
                        $aircraftmaintengine = $this->AircraftMaintenanceJetEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceJetEngines)->first(); 
                        if(!empty($aircraftmaintengine)){
                            $aircraftmaintengine->id = '';
                        }
                    }
                }
                
                $this->CustomerAircraftMaintenanceProps = $this->fetchTable('CustomerAircraftMaintenanceProps');
                $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceProps)->first();
                if(!empty($aircraftmaintprops)){
                    $aircraftmaintprops->id = ''; 
                }

                $work_order_id  = $postData['work_order_id'];
                $wo_item_id     = $postData['wo_item_id'];

                $aircraftwoitems = $this->CustomerAircraftWOItems->get($wo_item_id);
                
                $this->set(compact('aircraftwoitems', 'aircraftmaintoverview', 'aircraftmaintengine', 'aircraftmaintprops', 'work_order_id', 'wo_item_id', 'aircraftregdetail', 'engine_type', 'section'));
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/Inventory/customer_otc/aircraft_wo_option_logbook_val_tab');
            }
        }

        public function saveWOViewOptionGenInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    //echo "<pre>";print_r($postData);exit;
                    $is_add = 0;
                    $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select($this->CustomerAircraftWOOptionGeneralInfoes)->first();
                    if(empty($wooptiongeninfoes)){
                        $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }

                    $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->patchEntity($wooptiongeninfoes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionGeneralInfoes->save($wooptiongeninfoes)) {
                        $general_info_id = $wooptiongeninfoes->id;
                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionGenInfoHistory($wooptiongeninfoes);
                        }

                        $response = ['status'=>'success', 'message'=>'', 'general_info_id'=>$general_info_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOViewOptionGenInfoDeposit(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    $geninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->get($postData['general_info_id']);
                    $postData['wo_item_id'] = $geninfoes->wo_item_id;
                    $geninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->newEmptyEntity();
                    
                    $is_add = 0;
                    if(!empty($postData['general_info_deposit_id'])){
                        $geninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->get($postData['general_info_deposit_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $is_add = 1;
                    }

                    $geninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->patchEntity($geninfodeposits, $postData);
                    
                    if ($this->CustomerAircraftWOOptionGenInfoDeposits->save($geninfodeposits)) {
                        $gen_info_deposit_id = $geninfodeposits->id;

                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionGenInfoDepositHistory($geninfodeposits, $postData['wo_item_id']);
                        }
                        
                        $genDepositsHTML = $this->getWOViewOptionGenInfoDepositHTML($postData['general_info_id']);

                        $response = ['status'=>'success', 'message'=>'', 'gen_info_deposit_id'=>$gen_info_deposit_id, 'deposittr'=>$genDepositsHTML['deposittr'], 'total_amount'=>$genDepositsHTML['total_amount']];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOViewOptionGenInfoDepositHTML($general_info_id){
            $wooptiongeninfodeposits = $this->CustomerOTC->getAircraftWOViewOptionGenInfoDeposits($general_info_id);
            $deposittr = '';
            $woPaymentMethod = unserialize(WOPAYMENTMETHOD);
            $total_amount = 0;
            foreach($wooptiongeninfodeposits as $deposits){
                $deposittr .= '<tr class="wo-gen-info-deposit" data-val="'.$deposits['id'].'">';
                $deposittr .= '<td>'.$deposits['deposit_date'].'</td>';
                $deposittr .= '<td>'.$deposits['customers']['customer_name'].'</td>';
                $deposittr .= '<td>'.number_format($deposits['amount_to_add'], 2).'</td>';
                $deposittr .= '<td>'.$deposits['check_number'].'</td>';
                $deposittr .= '<td>'.$woPaymentMethod[$deposits['payment_method']].'</td>';
                $deposittr .= '</tr>';

                $total_amount += $deposits['amount_to_add'];
            }

            $returnArr = ['deposittr'=>$deposittr, 'total_amount'=>number_format($total_amount,2)];

            return $returnArr;
        }

        public function deleteWOViewOptionGenInfoDeposit(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    if(!empty($postData['general_info_deposit_id'])){
                        $geninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->get($postData['general_info_deposit_id']);
                        $general_info_id = $geninfodeposits->general_info_id;

                        $this->CustomerAircraftWOOptionGenInfoDeposits->delete($geninfodeposits);

                        $genDepositsHTML = $this->getWOViewOptionGenInfoDepositHTML($general_info_id);

                        $response = ['status'=>'success', 'message'=>'', 'deposittr'=>$genDepositsHTML['deposittr'], 'total_amount'=>$genDepositsHTML['total_amount']];

                        echo json_encode($response);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                    echo json_encode($result);die;
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($result);die;
                }
                
            }
        }

        public function saveWOViewOptionMiscCharges(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    //echo "<pre>";print_r($postData);exit;
                    $is_add = 0;
                    $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select($this->CustomerAircraftWOOptionMiscCharges)->first();
                    if(empty($wooptionmisccharges)){
                        $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscCharges->patchEntity($wooptionmisccharges, $postData);
                    
                    if ($this->CustomerAircraftWOOptionMiscCharges->save($wooptionmisccharges)) {
                        $misc_charges_id = $wooptionmisccharges->id;

                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionMiscChargesHistory($wooptionmisccharges);
                        }

                        $response = ['status'=>'success', 'message'=>'', 'misc_charges_id'=>$misc_charges_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOViewOptionMiscFuelCharges(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    $wooptionmiscfuelcharges = $this->CustomerAircraftWOOptionMiscFuelCharges->newEmptyEntity();
                    
                    $misfuelchargesarr = [];
                    $misfuelchargesarr['misc_charges_id']   = $postData['misc_charges_id'];
                    $misfuelchargesarr['gallon']            = $postData['fuel_gallons'];
                    $misfuelchargesarr['price']             = $postData['fuel_price'];

                    $is_add = 0;
                    if(!empty($postData['misc_fuel_charges_id'])){
                        $wooptionmiscfuelcharges = $this->CustomerAircraftWOOptionMiscFuelCharges->get($postData['misc_fuel_charges_id']);
                        $misfuelchargesarr['updated_by'] = $authUserData['id'];
                        $misfuelchargesarr['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $misfuelchargesarr['added_by'] = $authUserData['id'];
                        $misfuelchargesarr['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }
                    
                    $wooptionmiscfuelcharges = $this->CustomerAircraftWOOptionMiscFuelCharges->patchEntity($wooptionmiscfuelcharges, $misfuelchargesarr);
                    
                    if ($this->CustomerAircraftWOOptionMiscFuelCharges->save($wooptionmiscfuelcharges)) {
                        $misc_fuel_charges_id = $wooptionmiscfuelcharges->id;

                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionMiscFuelChargesHistory($postData['wo_item_id'], $wooptionmiscfuelcharges);
                        }
                        
                        $fuelChargesHTML = $this->getWOViewOptionMiscFuelChargesHTML($postData['misc_charges_id']);

                        $response = ['status'=>'success', 'message'=>'', 'misc_fuel_charges_id'=>$misc_fuel_charges_id, 'fuelchargestr'=>$fuelChargesHTML];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOViewOptionMiscFuelChargesHTML($misc_charges_id){
            $optionfuelcharges = $this->CustomerOTC->getAircraftWOViewOptionMiscFuelCharges($misc_charges_id);
            $fuelchargestr = '';

            foreach($optionfuelcharges as $fuelcharges){
                $fuelchargestr .= '<tr class="wo-mis-fuel-charges" data-val="'.$fuelcharges['id'].'">';
                $fuelchargestr .= '<td>'.number_format($fuelcharges['gallon'], 2).'</td>';
                $fuelchargestr .= '<td>'.number_format($fuelcharges['price'], 2).'</td>';
                $fuelchargestr .= '<td>'.number_format(($fuelcharges['gallon']*$fuelcharges['price']), 2).'</td>';
                $fuelchargestr .= '<td><i class="fa fa-times remove-wo-misc-fuel-charges" title="Delete fuel charges" data-val="'.$fuelcharges['id'].'" style="cursor:pointer;"></i></td>';
                $fuelchargestr .= '</tr>';
            }

            return $fuelchargestr;
        }

        public function deleteWOViewOptionMiscFuelCharges(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    if(!empty($postData['misc_fuel_charges_id'])){
                        $wooptionmisccharges = $this->CustomerAircraftWOOptionMiscFuelCharges->get($postData['misc_fuel_charges_id']);
                        
                        $this->CustomerAircraftWOOptionMiscFuelCharges->delete($wooptionmisccharges);

                        $response = ['status'=>'success', 'message'=>''];

                        echo json_encode($response);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                    echo json_encode($result);die;
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($result);die;
                }
                
            }
        }

        public function deleteWOItemPhoto(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['wo_item_photo_id'])){
                    $tableName = 'customer_aircraft_wo_item_photo';
                    $attachments = $this->InventoryAttachment->deleteAttachmentFileFromTable($postData['wo_item_photo_id'], $tableName);

                    $wo_item_id = $postData['wo_item_id'];
                    
                    $photoHTML = $this->getWOItemPhotoHTML($wo_item_id);
                    $woitemphototr = $photoHTML['woitemphototr'];
                    $woitemphotocount = $photoHTML['woitemphotocount'];
                    $result = array('status'=>'success', 'message'=>"Deleted successfully.", 'woitemphototr'=>$woitemphototr, 'woitemphotocount'=>$woitemphotocount);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function getWOItemPhotoHTML($wo_item_id){
            $aircraftwoitemphotoes = $this->CustomerOTC->getAircraftWOItemPhotoes($wo_item_id);
                    
            $woitemphototr = '';
            if(!empty($aircraftwoitemphotoes)){
                foreach($aircraftwoitemphotoes as $key=>$photes){
                    $ext = substr(strrchr($photes['file_name'] , '.'), 1);
                    $iconcss = '';
                    if($ext == 'pdf'){
                        $iconcss = 'icon-pdf';
                    }else if($ext == 'doc' || $ext == 'docx'){
                        $iconcss = 'icon-doc';
                    }else if($ext == 'xls' || $ext == 'xlsx'){
                        $iconcss = 'icon-excel';
                    }else if($ext == 'txt'){
                        $iconcss = 'icon-text';
                    }else{
                        $iconcss = 'icon-generic';
                    }

                    $wo_item_photo_active = '';
                    if($key == 0){
                        $wo_item_photo_active = 'wo-item-photo-active';
                    }
                
                    $woitemphototr .='<tr class="aircraft-wo-item-photo '.$wo_item_photo_active.'" data-val="'.$photes['id'].'">';
                    $woitemphototr .='<td class="document-name"><span class="document-management-icon '.$iconcss.'"></span>
                    <a href="'.Router::url('/', true).'/customer_otc_aircraft/' . $photes['file_name'].'">'.$photes['file_name'].'</a></td>';
                    $woitemphototr .='<td>'.$photes['caption'].'</td>';
                    $woitemphototr .='</tr>';
                }
            }
            $woitemphotocount = count($aircraftwoitemphotoes);

            $respArr = ['woitemphototr'=>$woitemphototr, 'woitemphotocount'=>$woitemphotocount];
            return $respArr;
        }

        public function deleteWOItemFile(){
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['wo_item_file_id'])){
                    $tableName = 'customer_aircraft_wo_item_file';
                    $attachments = $this->InventoryAttachment->deleteAttachmentFileFromTable($postData['wo_item_file_id'], $tableName);

                    $wo_item_id = $postData['wo_item_id'];
                    $filesHTML = $this->getWOItemFileHTML($wo_item_id);

                    $woitemfiletr = $filesHTML['woitemfiletr'];
                    $woitemfilecount = $filesHTML['woitemfilecount'];

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.", 'woitemfiletr'=>$woitemfiletr, 'woitemfilecount'=>$woitemfilecount);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function getWOItemFileHTML($wo_item_id){
            $aircraftwoitemfiles = $this->CustomerOTC->getAircraftWOItemFiles($wo_item_id);
            $woitemfiletr = '';
            if(!empty($aircraftwoitemfiles)){
                foreach($aircraftwoitemfiles as $key=>$files){
                    $ext = substr(strrchr($files['file_name'] , '.'), 1);
                    $iconcss = '';
                    if($ext == 'pdf'){
                        $iconcss = 'icon-pdf';
                    }else if($ext == 'doc' || $ext == 'docx'){
                        $iconcss = 'icon-doc';
                    }else if($ext == 'xls' || $ext == 'xlsx'){
                        $iconcss = 'icon-excel';
                    }else if($ext == 'txt'){
                        $iconcss = 'icon-text';
                    }else{
                        $iconcss = 'icon-generic';
                    }

                    $wo_item_file_active = '';
                    if($key == 0){
                        $wo_item_file_active = 'wo-item-file-active';
                    }
                
                    $woitemfiletr .='<tr class="aircraft-wo-item-file '.$wo_item_file_active.'" data-val="'.$files['id'].'">';
                    $woitemfiletr .='<td class="document-name"><span class="document-management-icon '.$iconcss.'"></span>
                    <a href="'.Router::url('/', true).'/customer_otc_aircraft/' . $files['file_name'].'">'.$files['file_name'].'</a></td>';
                    $woitemfiletr .='<td>'.$files['caption'].'</td>';
                    $woitemfiletr .='</tr>';
                }
            }
            $woitemfilecount = count($aircraftwoitemfiles);

            $respArr = ['woitemfiletr'=>$woitemfiletr, 'woitemfilecount'=>$woitemfilecount];
            return $respArr;
        }

        public function saveWOViewOptionPricingInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    //echo "<pre>";print_r($postData);exit;
                    $is_add = 0;
                    $wooptionpricinginfoes = $this->CustomerAircraftWOOptionPricingInfoes->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select($this->CustomerAircraftWOOptionPricingInfoes)->first();
                    if(empty($wooptionpricinginfoes)){
                        $wooptionpricinginfoes = $this->CustomerAircraftWOOptionPricingInfoes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $wooptionpricinginfoes = $this->CustomerAircraftWOOptionPricingInfoes->patchEntity($wooptionpricinginfoes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionPricingInfoes->save($wooptionpricinginfoes)) {
                        $pricing_info_id = $wooptionpricinginfoes->id;

                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionPricingInfoHistory($wooptionpricinginfoes);
                        }

                        $response = ['status'=>'success', 'message'=>'', 'pricing_info_id'=>$pricing_info_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOViewOptionWarrantyInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    //echo "<pre>";print_r($postData);exit;
                    $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id'], 'company_id'=>$postData['company_id']])->select($this->CustomerAircraftWOOptionWarrantyInfoes)->first();
                    $is_add = 0;
                    if(empty($wooptionwarrantyinfoes)){
                        $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    $postData['pay_labor']      = !empty($postData['pay_labor']) ? $postData['pay_labor'] : '0';
                    $postData['pay_parts']      = !empty($postData['pay_parts']) ? $postData['pay_parts'] : '0';
                    $postData['pay_shipping']   = !empty($postData['pay_shipping']) ? $postData['pay_shipping'] : '0';
                    $postData['taxable']        = !empty($postData['taxable']) ? $postData['taxable'] : '0';
                    $postData['customer_pays_warranty_tax'] = !empty($postData['customer_pays_warranty_tax']) ? $postData['customer_pays_warranty_tax'] : '0';
                    $postData['use_labor_rate'] = !empty($postData['use_labor_rate']) ? $postData['use_labor_rate'] : '0';

                    $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->patchEntity($wooptionwarrantyinfoes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionWarrantyInfoes->save($wooptionwarrantyinfoes)) {
                        if(!empty($is_add)){
                            $this->AircraftWOItemHistory->saveWOItemOptionMiscFuelChargesHistory($wooptionmiscfuelcharges);
                        }

                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function sendWOViewMessage(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    $wooptionsendmsg = $this->CustomerAircraftWOMessages->newEmptyEntity();
                    $postData['added_by'] = $authUserData['id'];
                    $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    if(!empty($postData['osr_message'])){
                        $messages = $postData['osr_message'].'
                        
'.$postData['message'];
                        $postData['message'] = $messages;
                    }
                    
                    $wooptionsendmsg = $this->CustomerAircraftWOMessages->patchEntity($wooptionsendmsg, $postData);
                    
                    if ($this->CustomerAircraftWOMessages->save($wooptionsendmsg)) {
                        $work_order_id = !empty($postData['work_order_id']) ? $postData['work_order_id'] : '';
                        
                        $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList($work_order_id);
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $msgtr = $InventoryAircraftWorkOrderHelper->getWOMessageListHTML($receivedmsglist);
                        
                        $response = ['status'=>'success', 'message'=>'', 'msgtr'=>$msgtr];
                        echo json_encode($response);die;
                    }else{
                        //var_dump($wooptionsendmsg->errors()); die();
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function refreshWOViewMessageList(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $work_order_id = !empty($postData['work_order_id']) ? $postData['work_order_id'] : '';
                    $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList($work_order_id);
                    $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                    $msgtr = $InventoryAircraftWorkOrderHelper->getWOMessageListHTML($receivedmsglist);

                    $response = ['status'=>'success', 'message'=>'', 'msgtr'=>$msgtr];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function deleteWOViewMessage(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['message_id'])){
                        $womessagelist = $this->CustomerAircraftWOMessages->get($postData['message_id']);
                        $work_order_id = $womessagelist->work_order_id;

                        $result = $this->CustomerAircraftWOMessages->delete($womessagelist);
                        
                        $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList($work_order_id);
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $msgtr = $InventoryAircraftWorkOrderHelper->getWOMessageListHTML($receivedmsglist);

                        $response = ['status'=>'success', 'message'=>'', 'msgtr'=>$msgtr];
                        echo json_encode($response);die;
                    }else{
                        $message = 'Something went wrong, please try again';
                        echo $message;die;
                    }
                    
                }else{
                    $message = 'Something went wrong, please try again';
                    echo $message;die;
                }
            }
        }

        public function saveWOViewOptionTaxInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    //echo "<pre>";print_r($postData);exit;
                    $wooptiontaxinfoes = $this->CustomerAircraftWOOptionTaxInfoes->find('all')->where(['wo_item_id'=>$postData['wo_item_id']])->select($this->CustomerAircraftWOOptionTaxInfoes)->first();
                    $is_add = 0;
                    if(empty($wooptiontaxinfoes)){
                        $wooptiontaxinfoes = $this->CustomerAircraftWOOptionTaxInfoes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        $is_add = 1;
                    }else{
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $wooptiontaxinfoes = $this->CustomerAircraftWOOptionTaxInfoes->patchEntity($wooptiontaxinfoes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionTaxInfoes->save($wooptiontaxinfoes)) {
                        $option_tax_info_id = $wooptiontaxinfoes->id;
                        if($is_add == 1){
                            $this->AircraftWOItemHistory->saveWOItemOptionTaxInfoHistory($wooptiontaxinfoes);
                        }

                        $response = ['status'=>'success', 'message'=>'', 'option_tax_info_id'=>$option_tax_info_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOViewOptionExtraTaxes(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    //echo "<pre>";print_r($postData);exit;

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    
                    $is_add = 0;
                    if(empty($postData['extra_taxes_id'])){
                        $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->get($postData['extra_taxes_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->patchEntity($wooptionextrataxes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionExtraTaxes->save($wooptionextrataxes)) {
                        $extra_taxes_id = $wooptionextrataxes->id;
                        if($is_add == 1){
                            $this->AircraftWOItemHistory->saveWOItemOptionExtraTaxInfoHistory($wooptionextrataxes);
                        }

                        $newaddedtax = '<div class="wo-option-extra-taxes-list wo-option-extra-taxes-list-active" data-val="'.$extra_taxes_id.'">'.$wooptionextrataxes->extra_tax_name.'</div>';

                        $response = ['status'=>'success', 'message'=>'', 'extra_taxes_id'=>$extra_taxes_id, 'newaddedtax'=>$newaddedtax];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOViewOptionExtraTaxesData(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->get($postData['extra_taxes_id']);
                    if(!empty($wooptionextrataxes)){
                        $tax_id = $wooptionextrataxes->tax_id;
                        $this->set(compact('wooptionextrataxes', 'tax_id'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/aircraft_wo_option_taxinfo_extra_tax_setup');
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function deleteWOViewOptionExtraTaxes(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['extra_taxes_id'])){
                        $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->get($postData['extra_taxes_id']);
                        $tax_id = $wooptionextrataxes->tax_id;
                        $result = $this->CustomerAircraftWOOptionExtraTaxes->delete($wooptionextrataxes);
                        
                        $wooptionextrataxes = $this->CustomerAircraftWOOptionExtraTaxes->newEmptyEntity();
                        $this->set(compact('wooptionextrataxes', 'tax_id'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/aircraft_wo_option_taxinfo_extra_tax_setup');
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function saveWOViewOptionBillingInfo(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();

                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }

                    //echo "<pre>";print_r($postData);exit;
                    $is_add = 0;
                    if(empty($postData['option_billing_info_id'])){
                        $wooptionbillinginfoes = $this->CustomerAircraftWOOptionBillingInfoes->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $is_add = 1;
                    }else{
                        $wooptionbillinginfoes = $this->CustomerAircraftWOOptionBillingInfoes->get($postData['option_billing_info_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    if($postData['billing_rate_method'] == '1'){
                        $postData['aircraft_rate_method'] = '';
                        $postData['aircraft_rate_hour'] = '0.00';
                    }else{
                        $postData['min_hour_rate'] = '0.00';
                    }
                    
                    $wooptionbillinginfoes = $this->CustomerAircraftWOOptionBillingInfoes->patchEntity($wooptionbillinginfoes, $postData);
                    
                    if ($this->CustomerAircraftWOOptionBillingInfoes->save($wooptionbillinginfoes)) {
                        $option_billing_info_id = $wooptionbillinginfoes->id;

                        if($is_add == 1){
                            $this->AircraftWOItemHistory->saveWOItemOptionBillingInfoHistory($wooptionbillinginfoes);
                        }

                        $response = ['status'=>'success', 'message'=>'', 'option_billing_info_id'=>$option_billing_info_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function reorganizeWorkOrderItem(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $work_order_id = $postData['work_order_id'];
                    $aircraftwoitemlists = $this->CustomerAircraftWOItems->find('all')->where(['work_order_id'=>$work_order_id])->select($this->CustomerAircraftWOItems);

                    $itemno = 1;
                    foreach($aircraftwoitemlists as $woitems){
                        $itemdata['wo_item_position'] = $itemno++;
                        $woitems = $this->CustomerAircraftWOItems->patchEntity($woitems, $itemdata);
                    
                        $this->CustomerAircraftWOItems->save($woitems);
                    }

                    $response = ['status'=>'success', 'message'=>'Item Reorganize Successfully.'];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function loadWorkOrder(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Work Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Work Orders'];
                }
            }

            $aircraftoptiondata = $this->CustomerOTC->getAircraftOptionDataWO();
            
            $this->set(compact('actionItems', 'aircraftoptiondata'));
        }

        public function filterOpenWODeparts(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $filter = ['wo_status'=>'1', 'wo_category'=>$postData['wo_category']];
                    $aircrafOpenWorkOrders = $this->CustomerOTC->getListOfWorkOrders($filter);
                    
                    $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                    $openwohtml = $InventoryAircraftWorkOrderHelper->getListOfOpenWorkOrderHTML($aircrafOpenWorkOrders);

                    $response = ['status'=>'success', 'message'=>'', 'openwohtml'=>$openwohtml];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveAircraftWOItemParts(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    foreach($postData as $key=>$val){
                        $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                    }
                    //echo "<pre>";print_r($postData);exit;

                    $authUserData = $this->Authentication->getResult()->getData();
                    
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->newEmptyEntity();
                    if(!empty($postData['wo_item_part_id'])){
                        $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->get($postData['wo_item_part_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    $postData['give_discount_percentage'] = !empty($postData['give_discount']) ? $postData['give_discount_percentage'] : '0.00';

                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->patchEntity($aircraftwoitemparts, $postData);
                    //echo "<pre>";print_r($aircraftwoitemparts);exit;
                    if ($this->CustomerAircraftWOItemParts->save($aircraftwoitemparts)) {
                        $wo_item_part_id = $aircraftwoitemparts->id;
                        if(empty($postData['wo_item_part_id'])){
                            $invitemsdet = $this->InventoryItems->find('all')->where(['part_number'=>$postData['part_number']])->select(['id', 'is_this_item_serialized'])->first();

                            /*if(empty($invitemsdet) && empty($postData['wo_item_part_id'])){
                                $invitemsdet = $this->InventoryItems->newEmptyEntity();
                                $invitemdata = [];
                                $invitemdata['name'] = $postData['wo_item_part_name'];
                                $invitemdata['part_number'] = $postData['part_number'];
                                $invitemdata['safety_stock_threshold'] = '1';
                                $invitemdata['default_uom'] = '1';
                                $invitemdata['description'] = $postData['part_description'];
                                $invitemdata['added_by'] = $postData['added_by'];

                                $invitemsdet = $this->InventoryItems->patchEntity($invitemsdet, $invitemdata);
                                $this->InventoryItems->save($invitemsdet);

                                $this->InventoryHistory->saveInventoryItemHistory($invitemsdet);

                                $invdet = $this->Inventories->newEmptyEntity();
                                $invdata = [];
                                $invdata['inventory_item_id'] = $invitemsdet->id;
                                $invdata['uom'] = $invitemsdet->default_uom;
                                $invdata['qty'] = $postData['qty_needed'];
                                $invdata['added_by'] = $postData['added_by'];

                                $invdet = $this->Inventories->patchEntity($invdet, $invdata);
                                $this->Inventories->save($invdet);

                                $this->InventoryHistory->saveInventoryHistory($invdet);
                            }*/

                            if(!empty($invitemsdet)){
                                $invWhereCond = ['inventory_item_id'=>$invitemsdet->id, 'status'=>'1'];
                                if(!empty($invitemsdet->is_this_item_serialized)){
                                    $invWhereCond['serial_no'] = $postData['serial_number'];
                                }
                                $inventoryDetailsEntity = $this->Inventories->find('all')
                                                            ->where($invWhereCond)
                                                            ->select($this->Inventories)
                                                            ->first();

                                $inventoryDetails = $inventoryDetailsEntity ? $inventoryDetailsEntity->toArray() : [];

                                if(!empty($inventorydetails)){
                                    $inventory_id = $inventorydetails['id'];
                                    if(!empty($invitemsdet->is_this_item_serialized) || $inventorydetails['qty'] == '1'){
                                        $this->Inventories->updateAll(
                                            array('updated_by'=>$authUserData['id'], 'modified'=>new \Cake\I18n\FrozenTime('now'), 'status'=>'11'),
                                            array('id' => $inventory_id)
                                        );
                                    }else{
                                        $newqty = $inventorydetails['qty']-1;
                                        unset($inventorydetails['id']);
                                        unset($inventorydetails['added_by']);
                                        unset($inventorydetails['updated_by']);
                                        unset($inventorydetails['created']);
                                        unset($inventorydetails['modified']);

                                        $this->Inventories->updateAll(
                                            array('updated_by'=>$authUserData['id'], 'modified'=>new \Cake\I18n\FrozenTime('now'), 'qty'=>$newqty),
                                            array('id' => $inventory_id)
                                        );

                                        $newInventries = $this->Inventories->newEmptyEntity();
                                        $inventorydata = $inventorydetails;
                                        $inventorydata['qty'] = '1';
                                        $inventorydata['status'] = '11';
                                        $inventorydata['added_by'] = $authUserData['id'];
                                        $inventorydata['created'] = new \Cake\I18n\FrozenTime('now');
                                        
                                        $newInventries = $this->Inventories->patchEntity($newInventries, $inventorydata);
                                        $this->Inventories->save($newInventries);
                                    }
                                    $title = 'Physical Inventory '.$postData['part_number'].' '.$postData['serial_number'].' was updated.';
                                    
                                    $aircraftwoitems = $this->CustomerOTC->getWOItemDetailByItemId($postData['wo_item_id']);

                                    $description = 'Inventory Requested on Work Order No. '.$aircraftwoitems['work_order_no'].' Item No. '.$aircraftwoitems['wo_items']['wo_item_position'];
                                    
                                    $this->AircraftWOItemHistory->saveDynamicInventoryHistory($inventory_id, $title, $description);

                                    $postData['work_order_id'] = $aircraftwoitems['id'];
                                    $postData['work_order_no'] = $aircraftwoitems['work_order_no'];
                                    
                                    $this->Inventory->saveInventoryRequestForWO($postData, $wo_item_part_id);
                                }
                            }
                        }
                        
                        $woitempartlists = $this->CustomerOTC->getAircraftWOItemParts($postData['wo_item_id']);
                        if(empty($postData['wo_item_part_id'])){
                            $history_title = 'Item Part was created.';
                            $this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($aircraftwoitemparts, $history_title);
                        }
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $woitempartshtml = $InventoryAircraftWorkOrderHelper->getWorkOrderItemPartListHTML($woitempartlists);

                        $response = ['status'=>'success', 'message'=>'', 'woitempartshtml'=>$woitempartshtml];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOItemPartsRequisition(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->get($postData['wo_item_part_id']);
                    if(!empty($aircraftwoitemparts)){
                        $vendorlist = $this->CustomerOTC->getWOPartsVendorList();
                        $invpartnumbers = $this->CustomerOTC->getWOItemPartNumberList();

                        $this->set(compact('aircraftwoitemparts', 'vendorlist', 'invpartnumbers'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/aircraft_wo_part_requisition_block');
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function refreshAircraftWOAllPartsList(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if (!empty($postData['work_order_id'])) {
                        $workorderpartslist = $this->CustomerOTC->getAircraftWOAllPartsList($postData['work_order_id']);
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $woitempartshtml = $InventoryAircraftWorkOrderHelper->getWorkOrderAllPartsListHTML($workorderpartslist);

                        $response = ['status'=>'success', 'message'=>'', 'woitempartshtml'=>$woitempartshtml];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getInvItemsWithInvListByItemNumber(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                //$statusarr = array('1', '2', '7', '9', '12');
                $postData = $this->request->getData();
                
                if(isset($postData['inventory_item_id']) && !empty($postData['inventory_item_id'])){
                    $inventoryitems = $this->InventoryItems->find('all')->where(['InventoryItems.part_number'=>$postData['inventory_item_id'], 'InventoryItems.status'=>'1'])->select($this->InventoryItems)->select(['inv.id', 'inv.conditions', 'inv.serial_no', 'inv.qty', 'inv.status', 'inv.location_id', 'inv.vendor'])
                    ->join([
                        'inv' => [
                            'table' => 'inventories',
                            'type' => 'LEFT',
                            'conditions' => 'inv.inventory_item_id = InventoryItems.id',
                        ]
                    ]);
                    
                    $conditions = [];
                    $serialno = [];
                    $inventoryitemlist = [];
                    $inventoryconditions = unserialize(INVENTORY_CONDITION);
                    $defaultUOM = unserialize(DEFAULT_UOM);
                    
                    $qty = 0;
                    $locationidarr = [];
                    $vendoridarr = [];

                    if ($inventoryitems->count() > 0) {
                        foreach($inventoryitems as $invitm){
                            if(isset($invitm['inv'])){
                                if($invitm['inv']['status'] == '1'){
                                    if(!empty($invitm['inv']['conditions'])){
                                        $conditions[$inventoryconditions[$invitm['inv']['conditions']]] = $inventoryconditions[$invitm['inv']['conditions']];
                                    }
                                    if(!empty($invitm['inv']['serial_no'])){
                                        $serialno[$invitm['inv']['serial_no']] = $invitm['inv']['serial_no'];
                                    }
                                    if(!empty($invitm['inv']['location_id'])){
                                        $locationidarr[] = $invitm['inv']['location_id'];
                                    }
                                    if(!empty($invitm['inv']['vendor'])){
                                        $vendoridarr[] = $invitm['inv']['vendor'];
                                    }
                                    
                                    $qty += !empty($invitm['inv']['qty']) ? $invitm['inv']['qty'] : 0;
                                }
                            }
                            $invitm->default_uom = $defaultUOM[$invitm->default_uom];
                            $inventoryitemlist = $invitm;
                        }
                        if(!empty($inventoryitemlist)){
                            $inventoryitemlist->qty = $qty;
                        }
                        
                    }

                    $conditions = !empty($conditions) ? array_unique($conditions) : [];
                    $serialno = !empty($serialno) ? array_unique($serialno) : [];
                    $locationidarr = !empty($locationidarr) ? array_unique($locationidarr) : [];
                    $vendoridarr = !empty($vendoridarr) ? array_unique($vendoridarr) : [];
                    $inventoryitems = $inventoryitemlist;

                    $locationlist = $this->CustomerOTC->getAllItemLocationByInvId($locationidarr);
                    $vendorlist = $this->CustomerOTC->getAllItemSupplierByInvId($vendoridarr);

                    $result = array('status'=>'success', 'message'=>"", 'inventoryitems'=>$inventoryitems, 'conditions'=>$conditions, 'serialno'=>$serialno, 'locationlist'=>$locationlist, 'vendorlist'=>$vendorlist);
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function woItemMarkAllPartsTaxable(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_item_id'])){
                        $res = $this->CustomerAircraftWOItemParts->updateAll(
                            array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'part_taxable'=>'1'),
                            array('wo_item_id' => $postData['wo_item_id'])
                        );

                        if($res) {
                            $response = ['status'=>'success', 'message'=>''];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getAircraftWOItemNextPrevPart(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->newEmptyEntity();
                    if(!empty($postData['wo_item_id'])){
                        $work_order_id = $postData['work_order_id'];
                        $wo_item_id = $postData['wo_item_id'];
                        $vendorlist = $this->CustomerOTC->getWOPartsVendorList();
                        $aircraftwoitemparts = $this->CustomerOTC->getAircraftWOAllPartsById($postData);
                        if(!empty($aircraftwoitemparts)){
                            $this->set(compact('work_order_id', 'aircraftwoitemparts', 'vendorlist', 'wo_item_id'));

                            $this->viewBuilder()->setLayout('ajax');
                            $this->render('/element/Inventory/customer_otc/aircraft_wo_item_parts_view_block');
                        }else{
                            echo "Empty";die;
                        }
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function woMarkAllItemsYes(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_item_ids'])){
                        $wo_item_arr = explode(',', $postData['wo_item_ids']);
                        
                        $res = $this->CustomerAircraftWOItemOverviews->updateAll(
                            array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'owner_authentication'=>'2'),
                            array('wo_item_id IN' => $wo_item_arr)
                        );

                        if($res) {
                            $response = ['status'=>'success', 'message'=>''];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function aircraftWOROExportExcelData(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $woroExportData = $this->CustomerOTC->woRoExportExcelFilterData($postData);
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                } 
            }
        }

        public function getWOItemSignoffCategoryList(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $wo_item_id = $postData['wo_item_id'];
                    $wosignoffitems = $this->CustomerAircraftWOItems->get($wo_item_id);
                    if(!empty($wosignoffitems)){
                        $wosignoffitemsdet = $this->CustomerOTC->getWOItemSignOff($wosignoffitems->id);
                    
                        $woitemsignoffdet = [];
                        if(!empty($wosignoffitemsdet)){
                            foreach($wosignoffitemsdet as $row){
                                $woitemsignoffdet[$row['signoff_category']] = $row;
                            }
                        }
                        
                        $this->set(compact('wosignoffitems', 'woitemsignoffdet', 'wo_item_id'));

                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/aircraft_wo_signoff_category_block');
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function saveWOItemSignoffCategory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    if(empty($postData['signoff_inspection_code'])){
                        $response = ['status'=>'failure', 'message'=>'Please fill Inspection Code'];
                        echo json_encode($response);die;
                    }
                    
                    if(!empty($postData['wo_item_id']) && !empty($postData['signoff_category']) && !empty($postData['signoff_inspection_code'])){
                        
                        $wo_item_id = $postData['wo_item_id'];
                        $signoff_category = $postData['signoff_category'];

                        $isvalidcertificationcode = $this->CustomerOTC->validateUserCertificationCode($postData['signoff_inspection_code']);
                        if($isvalidcertificationcode == '0'){
                            $response = ['status'=>'failure', 'message'=>'Invalid inspection code.'];
                            echo json_encode($response);die;
                        }
                        
                        $woitemsignoffdet = $this->CustomerOTC->getWOItemSignOff($wo_item_id, $signoff_category);
                        $woitemdet = $this->CustomerAircraftWOItems->get($wo_item_id);
                        if(empty($woitemsignoffdet)){
                            
                            if($signoff_category == '1' && empty($woitemdet->wo_corrective_action)){
                                $response = ['status'=>'failure', 'message'=>'There must be a corrective action entered to add the final sign-off to this item.'];
                                echo json_encode($response);die;
                            }else{
                                $woitemsignoffs = $this->AircraftWOItemSignoffs->newEmptyEntity();

                                $postData['inspected_by'] = $authUserData['id'];
                                $postData['inspected_date'] = new \Cake\I18n\FrozenTime('now');
                                $postData['added_by'] = $authUserData['id'];
                                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');

                                $woitemsignoffs = $this->AircraftWOItemSignoffs->patchEntity($woitemsignoffs, $postData);
                            
                                if ($this->AircraftWOItemSignoffs->save($woitemsignoffs)) {
                                    $title = 'Signoff for Item '.$woitemdet['wo_item_position'].' was updated.';
                                    $description = 'Sign-off Category '.$signoff_category.'.<br/>';
                                    $description .= 'Inspected By '.$postData['inspected_by'].'.<br/>';
                                    $description .= 'Inspect Date '.$postData['inspected_date'].'.';
                                    
                                    $this->AircraftWOItemHistory->saveWOItemSignOffHistory($wo_item_id, $title, $description);

                                    if($signoff_category == '1'){
                                        $woItemData = [];
                                        $woItemData['wo_item_status'] = '3';
                                        $woItemData['updated_by'] = $authUserData['id'];
                                        $woItemData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                                        $woitemdet = $this->CustomerAircraftWOItems->patchEntity($woitemdet, $woItemData);
                                        
                                        $this->CustomerAircraftWOItems->save($woitemdet);
                                    }
                                }else{
                                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                                    echo json_encode($response);die;
                                }
                            }
                        }
                        $wosignoffitems = $this->CustomerOTC->getWOItemSignOff($wo_item_id);
                        $woitemsignoffdet = [];
                        $signoffcategory = [];
                        if(!empty($wosignoffitems)){
                            foreach($wosignoffitems as $row){
                                if($row['signoff_category'] == $signoff_category){
                                    $signoffcategory = $row;
                                }
                                $woitemsignoffdet[$row['signoff_category']] = $row;
                            }
                        }
                        
                        $inspected_by = !empty($signoffcategory['users']['full_name']) ? $signoffcategory['users']['full_name'] : '';
                        $inspected_date = !empty($signoffcategory['inspected_date']) ? $signoffcategory['inspected_date'] : '';
                        $signoff_info = '';
                        $signoff_id =  !empty($signoffcategory['id']) ? $signoffcategory['id'] : '';
                        if(!empty($inspected_by) && !empty($inspected_date)){
                            $signoff_info = $inspected_by.' ('.date('m/d/Y', strtotime($inspected_date)).')';
                        }
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $signoffinspectionhtml = $InventoryAircraftWorkOrderHelper->getWOItemSignoffCategoryHTML($woitemsignoffdet, $wo_item_id);

                        $work_order_id = $woitemdet->work_order_id;

                        $aircraftwoitems = $this->CustomerAircraftWOItems->find('all', array('order'=>'wo_item_position ASC'))
                                                                        ->where(['work_order_id'=>$work_order_id])
                                                                        ->select($this->CustomerAircraftWOItems);

                        $signoffitemhtml = $InventoryAircraftWorkOrderHelper->getWOItemListForSignOffHTML($aircraftwoitems);

                        $response = ['status'=>'success', 'message'=>'', 'signoff_info'=>$signoff_info, 'signoffinspectionhtml'=>$signoffinspectionhtml, 'signoff_id'=>$signoff_id, 'signoffitemhtml'=>$signoffitemhtml];
                        echo json_encode($response);die;
                        
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function clearWOItemSignoffCategory(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    if(!empty($postData['wo_item_id']) && !empty($postData['signoff_category']) && !empty($postData['signoff_inspection_code'])){
                        
                        $wo_item_id = $postData['wo_item_id'];
                        $signoff_category = $postData['signoff_category'];
                        $signoff_id = $postData['signoff_id'];
                        
                        $isvalidcertificationcode = $this->CustomerOTC->validateUserCertificationCode($postData['signoff_inspection_code']);
                        if($isvalidcertificationcode == '0'){
                            $response = ['status'=>'failure', 'message'=>'Invalid inspection code.'];
                            echo json_encode($response);die;
                        }
                        
                        $woitemdet = $this->CustomerAircraftWOItems->get($wo_item_id);

                        $woitemsignoffdet = $this->CustomerOTC->getWOItemSignOff($wo_item_id, $signoff_category);
                        if(!empty($woitemsignoffdet)){
                            $result = $this->AircraftWOItemSignoffs->delete($woitemsignoffdet);
                            if ($result) {
                                if($signoff_category == '1'){
                                    $woItemData = [];
                                    $woItemData['wo_item_status'] = '1';
                                    $woItemData['updated_by'] = $authUserData['id'];
                                    $woItemData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                                    $woitemdet = $this->CustomerAircraftWOItems->patchEntity($woitemdet, $woItemData);
                                    
                                    $this->CustomerAircraftWOItems->save($woitemdet);
                                }
                            }else{
                                $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                                echo json_encode($response);die;
                            }
                        }
                        $wosignoffitems = $this->CustomerOTC->getWOItemSignOff($wo_item_id);
                        $woitemsignoffdet = [];
                        $signoffcategory = [];
                        if(!empty($wosignoffitems)){
                            foreach($wosignoffitems as $row){
                                $woitemsignoffdet[$row['signoff_category']] = $row;
                            }
                        }
                        
                        $inspected_by = '';
                        $inspected_date = '';
                        $signoff_info = '';
                        $signoff_id =  '';
                        if(!empty($inspected_by) && !empty($inspected_date)){
                            $signoff_info = $inspected_by.' ('.date('m/d/Y', strtotime($inspected_date)).')';
                        }
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $signoffinspectionhtml = $InventoryAircraftWorkOrderHelper->getWOItemSignoffCategoryHTML($woitemsignoffdet, $wo_item_id);

                        $work_order_id = $woitemdet->work_order_id;
                        $aircraftwoitems = $this->CustomerAircraftWOItems->find('all', array('order'=>'wo_item_position ASC'))
                                                                        ->where(['work_order_id'=>$work_order_id])
                                                                        ->select($this->CustomerAircraftWOItems);

                        $signoffitemhtml = $InventoryAircraftWorkOrderHelper->getWOItemListForSignOffHTML($aircraftwoitems);

                        $response = ['status'=>'success', 'message'=>'', 'signoff_info'=>$signoff_info, 'signoffinspectionhtml'=>$signoffinspectionhtml, 'signoff_id'=>$signoff_id, 'signoffitemhtml'=>$signoffitemhtml];
                        echo json_encode($response);die;
                        
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWorkOrderItemTool(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    if(empty($postData['wo_item_tool_id'])){
                        $inventorytool = $this->InventoryTools->get($postData['tool_id']);

                        $tooldata = [];
                        $tooldata['wo_item_id'] = $postData['wo_item_id'];
                        $tooldata['tool_id'] = $inventorytool['id'];
                        $tooldata['wo_item_tool_name'] = $inventorytool['tool_name'];
                        $tooldata['equipment_description'] = $inventorytool['equipment_description'];
                        $tooldata['model_no'] = $inventorytool['model_no'];
                        $tooldata['serial_no'] = $inventorytool['serial_no'];
                        
                        $tooldata['certification'] = $inventorytool['certification'];
                        $tooldata['added_by'] = $authUserData['id'];
                        $tooldata['created_at'] = new \Cake\I18n\FrozenTime('now');

                        $woitemtools = $this->CustomerAircraftWOItemTools->newEmptyEntity();
                    }else{
                        $woitemtools = $this->CustomerAircraftWOItemTools->get($postData['wo_item_tool_id']);
                        $tooldata = $postData;
                        
                        $tooldata['updated_by'] = $authUserData['id'];
                        $tooldata['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $woitemtools = $this->CustomerAircraftWOItemTools->patchEntity($woitemtools, $tooldata);
                    
                    if ($this->CustomerAircraftWOItemTools->save($woitemtools)) {
                        if(empty($postData['wo_item_tool_id'])){
                            $history_title = 'Item Tool was created.';
                            $this->AircraftWOItemHistory->saveWOItemTabCreateDataToHistory($woitemtools, $history_title);
                        }

                        $woitemtoollists = $this->CustomerOTC->getAircraftWOItemTools($postData['wo_item_id']);
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $woitemtoolhtml = $InventoryAircraftWorkOrderHelper->getWorkOrderItemToolListHTML($woitemtoollists);

                        $response = ['status'=>'success', 'message'=>'', 'woitemtoolhtml'=>$woitemtoolhtml];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOItemToolNextPrev(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_item_tool_id'])){
                        $wo_item_tool_id = $postData['wo_item_tool_id'];
                        
                        $woitemtools = $this->CustomerOTC->getWOItemToolById($postData);
                        
                        if(!empty($woitemtools)){
                            $this->set(compact('woitemtools', 'wo_item_tool_id'));

                            $this->viewBuilder()->setLayout('ajax');
                            $this->render('/element/Inventory/customer_otc/aircraft_wo_item_tool_edit_block');
                        }else{
                            echo "Empty";die;
                        }
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function deleteWOItemTool(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_item_tool_id'])){
                        $woitemtool = $this->CustomerAircraftWOItemTools->get($postData['wo_item_tool_id']);
                        $tool_name = $woitemtool->wo_item_tool_name;
                        $history_title = 'Item Tool Deleted';
                        $history_description = 'Tool `'.$tool_name.'` deleted from work order item.';
                        $this->AircraftWOItemHistory->saveWOItemTabDeletedDataToHistory($woitemtool, $history_title, $history_description);

                        $result = $this->CustomerAircraftWOItemTools->delete($woitemtool);

                        $woitemtoollists = $this->CustomerOTC->getAircraftWOItemTools($postData['wo_item_id']);
                        
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $woitemtoolhtml = $InventoryAircraftWorkOrderHelper->getWorkOrderItemToolListHTML($woitemtoollists);

                        $response = ['status'=>'success', 'message'=>'', 'woitemtoolhtml'=>$woitemtoolhtml];
                        echo json_encode($response);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                        echo json_encode($response);die;
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOItemOSRNextPrev(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if(!empty($postData['wo_osrinfo_id'])){
                        $wooutstandingoutside = $this->CustomerOTC->getWOItemOSRById($postData);
                        
                        if(!empty($wooutstandingoutside)){
                            $wo_item_id = $postData['wo_item_id'];
                            $work_order_id = $postData['work_order_id'];
                            $wodetails = $this->CustomerAircraftWorkOrders->get($work_order_id);
                            $inventoryvendors = $this->CustomerOTC->getWOOSRVendorList();
                            $woitemdata = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
        
                            $this->set(compact('wooutstandingoutside', 'inventoryvendors', 'wo_item_id', 'work_order_id', 'wodetails', 'woitemdata'));

                            $this->viewBuilder()->setLayout('ajax');
                            $this->render('/element/Inventory/customer_otc/aircraft_wo_osr_create_fld');
                        }else{
                            echo "Empty";die;
                        }
                    }else{
                        echo "Failed";die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function customerOTCInfoInvoiceReport()
        {
            $postData = $this->request->getData();
            
            $mainHtml = $this->CustomerOTC->customerOTCInvoicePDFReport($postData);

            if(!empty($mainHtml)){
                $html ='<html lang="en">
                <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                  <title></title>
                  <style>
                  body{
                    font-family: Arial, Helvetica, sans-serif;
                  }
                  table {
                    width: 100%;
                  }
                  .invoice-h {
                  border: 1px solid #858585;
                }
                .table-invoice {
                  border: 1px solid #2c2c2c;
                  font-size: 14px;
                  padding-bottom: 10px;
                }
              
                .table-invoice tbody td {
                  padding: 2px 0px 2px 10px;
                }
              
                .table-invoice thead td { 
                  padding: 5px 0px 5px 10px;
                  font-weight: bold;
                  background-color: #d3d3d3;
                  border-bottom: 1px solid #7b7b7b;
                }
                .tb-in-td {
                  padding-top: 10px !important;
                }
                .invoice-h {
                  border: 1px solid #858585;
                }
                .invoice-h td {
                  background-color: #d3d3d3;
                  font-weight: bold;
                  font-size: 25px;
                  text-align: center;
                }
                .table-invoice-align {
                  display: flex;
                  flex-direction: column;
                  justify-content: space-between;
                  height: 187px;
                }
                .td-invoice-top {
                  padding-top: 20px;
                }
                .billing-table {
                  font-size: 14px;
                }
                .billing-table td {
                  padding: 2px 0px 2px 0px;
                }
                .pay-method {
                  padding: 50px 0px 10px 0px !important;  
                }
              .table-invoice-list {
                font-size: 14px;
              }
              .table-invoice-list thead td {
                font-weight: bold;
                padding: 7px 7px 7px 7px;
                border-top: 2px solid #9f9f9f;
                border-bottom: 3px solid #000000;
              }
              .table-invoice-list tbody td {
                padding: 7px 7px 7px 7px;
                border-bottom: 2px solid #a9a9a9;
              }
              .table-return {
                font-size: 14px;
              }
              .table-total {
                width: auto;
              }
              .table-total td {
                padding: 3px 7px 3px 7px;
              }
              .table-total td:first-child { 
                padding-right: 30px;
              }
              .total-inv-amt td {
                border-bottom: 1px solid #808080;
                padding-bottom: 8px !important;
              }
              .deposit-amt td{
                padding-top: 9px !important;
                padding-bottom: 9px !important;
              }
              .balance-amt td {
                background-color: #d3d3d3;
                padding-top: 10px;
                padding-bottom: 10px;
              }
              .td-rt {
                padding-right: 0px !important;
              }
              .table-balance {
                border: 1px solid #515151;
              }
                @media print {
                  body {-webkit-print-color-adjust: exact; print-color-adjust: exact;}
                  }
                  </style>
                </head>
                
                <body>'.$mainHtml.'</body></html>';
                //echo $html;exit;
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->AddPage('L', // L - landscape, P - portrait 
                '', '', '', '',
                5, // margin_left
                5, // margin right
                15, // margin top
                10, // margin bottom
                0, // margin header
                0); // margin footer

                $mpdf->WriteHTML($html);
                
                //save the file on particular location
                //https://mpdf.github.io/reference/mpdf-functions/output.html
                $fileName = "otc_invoice_info_reports_".date('YmdHis').".pdf";
                $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");

                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            }else{
                $result = array('status'=>'failure', 'message'=>'There were no records for the criteria specified.');
                echo json_encode($result);die;
            }
            
        }

        public function saveCustomerAddlAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $authUserData = $this->Authentication->getResult()->getData();

                $customeraddresses = $this->InventoryCustomerAddresses->newEmptyEntity();

                $postData = $this->request->getData();//print_r($postData);exit;
                $postData['added_by'] = $authUserData['id'];
                $postData['created'] = new \Cake\I18n\FrozenTime('now');
                $postData['is_shipping_address'] = '1';
                
                $customer_id = $postData['customer_id'];

                $customeraddresses = $this->InventoryCustomerAddresses->patchEntity($customeraddresses, $postData);
                
                if ($this->InventoryCustomerAddresses->save($customeraddresses)) {
                    $id = $customeraddresses->id;

                    $shiptoaddrdropdown = '';
                    $customeraddressdet = $this->InventoryCustomerAddresses->find('all', ['order'=>'address ASC'])->where(['customer_id'=>$customer_id])->select($this->InventoryCustomerAddresses);
                    
                    $customeraddressarr = [];
                    $customeraddrdropdown = '';
                    foreach($customeraddressdet as $addr){
                        $selected = '';
                        if($addr['id'] == $id){
                            $selected = 'selected';
                            $customeraddressarr = $addr;
                            if(!empty($addr['state'])){
                                $statearr = $this->CustomerOTC->getStateNameById($addr['state']);
                                $customeraddressarr['state'] = $statearr['name'];
                            }
                        }
                        $customeraddrdropdown .= '<option value="'.$addr['id'].'">'.$addr['name']." | ".$addr['address'].'</option>';
                    }
                    
                    $result = array('status'=>'success', 'message'=>"Address Detail Saved successfully.", 'customeraddressarr'=>$customeraddressarr, 'customeraddrdropdown'=>$customeraddrdropdown);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function getCustomerShippingAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if (!empty($postData['shipping_address_id'])) {
                        $customeraddressarr = $this->InventoryCustomerAddresses->get($postData['shipping_address_id']);
                        
                        if(!empty($customeraddressarr['state'])){
                            $statearr = $this->CustomerOTC->getStateNameById($customeraddressarr['state']);
                            $customeraddressarr['state'] = $statearr['name'];
                        }

                        $response = ['status'=>'success', 'message'=>'', 'customeraddressarr'=>$customeraddressarr];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function checkWorkOrderLogBookValue(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if (!empty($postData['aircraft_id'])) {
                        $customeraddressarr = $this->CustomerOTC->getWorkOrderLogbookValueList($postData['aircraft_id'], $postData['engine_type']);
                        
                        $islogbookvalue = $customeraddressarr->count();
                        
                        $response = ['status'=>'success', 'message'=>'', 'islogbookvalue'=>$islogbookvalue];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function updateWOLogBookValueFromMaint(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    if (!empty($postData['aircraft_id']) && !empty($postData['workorderids'])) {
                        $aircraftdet = $this->CustomerOTCAircrafts->get($postData['aircraft_id']);
                        
                        $engine_type = $aircraftdet->aircraft_engine_type;
                        $postData['engine_type'] = $engine_type;

                        if($engine_type != '5'){
                            $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceOverviews)->first()->toArray();
                        }else{
                            $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceHelicopterOverviews)->first()->toArray();
                        }
                        
                        if($engine_type != '5'){
                            if($engine_type == '1' || $engine_type == '2'){
                                $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceEngines)->first()->toArray(); 
                            }else{
                                $aircraftmaintengine = $this->AircraftMaintenanceJetEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceJetEngines)->first()->toArray(); 
                            }

                            $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceProps)->first()->toArray(); 
                        }
                        
                        foreach($postData['workorderids'] as $work_order_id){
                            $postData['work_order_id'] = $work_order_id;

                            $logbookvaluetabdata = $this->CustomerOTC->getAircraftWOLogBookValueTabData($postData);

                            $logbookvalueoverview   = $logbookvaluetabdata['aircraftmaintoverview'];
                            $logbookvalueengine     = $logbookvaluetabdata['aircraftmaintengine'];
                            $logbookvalueprops      = $logbookvaluetabdata['aircraftmaintprops'];

                            //update logbook value overview table
                            $logbookvalueoverview->updated_by = $authUserData['id'];
                            $logbookvalueoverview->updated_at = new \Cake\I18n\FrozenTime('now');

                            unset($aircraftmaintoverview['id']);
                            unset($aircraftmaintoverview['added_by']);
                            unset($aircraftmaintoverview['updated_by']);
                            unset($aircraftmaintoverview['created_at']);
                            unset($aircraftmaintoverview['updated_at']);

                            if($engine_type != '5'){
                                $logbookvalueoverview = $this->CustomerAircraftWOLogBookValueOverviews->patchEntity($logbookvalueoverview, $aircraftmaintoverview);
                                $this->CustomerAircraftWOLogBookValueOverviews->save($logbookvalueoverview);
                            }else{
                                $logbookvalueoverview = $this->AircraftWOLogBookValueHelicopterOverviews->patchEntity($logbookvalueoverview, $aircraftmaintoverview);
                                $this->AircraftWOLogBookValueHelicopterOverviews->save($logbookvalueoverview);
                            }
                            //update logbook value engine table
                            if($engine_type != '5'){
                                unset($aircraftmaintengine['id']);
                                unset($aircraftmaintengine['added_by']);
                                unset($aircraftmaintengine['updated_by']);
                                unset($aircraftmaintengine['created_at']);
                                unset($aircraftmaintengine['updated_at']);

                                if(!empty($logbookvalueengine)){
                                    $logbookvalueengine->updated_by = $authUserData['id'];
                                    $logbookvalueengine->updated_at = new \Cake\I18n\FrozenTime('now');
                                }else{
                                    $logbookvalueengine = $this->CustomerAircraftWOLogBookValueEngines->newEmptyEntity();

                                    $logbookvalueengine['added_by'] = $authUserData['id'];
                                    $logbookvalueengine['work_order_id'] = $work_order_id;
                                }
                                
                                if($engine_type == '1' || $engine_type == '2'){
                                    $logbookvalueengine = $this->CustomerAircraftWOLogBookValueEngines->patchEntity($logbookvalueengine, $aircraftmaintengine);
                                    $this->CustomerAircraftWOLogBookValueEngines->save($logbookvalueengine);
                                }else{
                                    $logbookvalueengine = $this->AircraftWOLogBookValueJetEngines->patchEntity($logbookvalueengine, $aircraftmaintengine);
                                    $this->AircraftWOLogBookValueJetEngines->save($logbookvalueengine);
                                }
                                
                                //update logbook value props table

                                unset($aircraftmaintprops['id']);
                                unset($aircraftmaintprops['added_by']);
                                unset($aircraftmaintprops['updated_by']);
                                unset($aircraftmaintprops['created_at']);
                                unset($aircraftmaintprops['updated_at']);

                                if(!empty($logbookvalueprops)){
                                    $logbookvalueprops->updated_by = $authUserData['id'];
                                    $logbookvalueprops->updated_at = new \Cake\I18n\FrozenTime('now');
                                }else{
                                    $logbookvalueprops = $this->CustomerAircraftWOLogBookValueProps->newEmptyEntity();
                                    $logbookvalueprops['added_by'] = $authUserData['id'];
                                    $logbookvalueprops['work_order_id'] = $work_order_id;
                                }
                                
                                $logbookvalueprops = $this->CustomerAircraftWOLogBookValueProps->patchEntity($logbookvalueprops, $aircraftmaintprops);
                                $this->CustomerAircraftWOLogBookValueProps->save($logbookvalueprops);
                            }
                        }
                        $response = ['status'=>'success', 'message'=>''];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOItemDiscrepancy(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();
                $authUserData = $this->Authentication->getResult()->getData();
                
                $discrepancy = $this->saveWOItemDiscrepancyData($postData);
                
                if (!empty($discrepancy)) {
                    $woitemdata = [];
                    $woitemdata['updated_by'] = $authUserData['id'];
                    $woitemdata['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    $woitemdata['wo_discrepancy'] = $discrepancy;

                    $woitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
                    $woitems = $this->CustomerAircraftWOItems->patchEntity($woitems, $woitemdata);
                    $this->CustomerAircraftWOItems->save($woitems);
                    
                    $response = ['status'=>'success', 'message'=>'', 'discrepancy'=>$discrepancy];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOItemDiscrepancyData($postData){
            $authUserData = $this->Authentication->getResult()->getData();
            $discrepancy_count = $this->AircraftWOItemDiscrepancyHistories->find('all')->where(['wo_item_id'=>$postData['wo_item_id'], 'discrepancy'=>$postData['wo_discrepancy']])->select($this->AircraftWOItemDiscrepancyHistories)->count();
            
            $discrepancy = '';
            if(empty($discrepancy_count)){
                $discrepancyhistory = $this->AircraftWOItemDiscrepancyHistories->newEmptyEntity();
                
                $postData['discrepancy'] = $postData['wo_discrepancy'];
                $postData['added_by'] = $authUserData['id'];
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                
                $discrepancyhistory = $this->AircraftWOItemDiscrepancyHistories->patchEntity($discrepancyhistory, $postData);
                
                if ($this->AircraftWOItemDiscrepancyHistories->save($discrepancyhistory)) {
                    $discrepancy = $discrepancyhistory->discrepancy;
                }
            }

            return $discrepancy;
        }

        public function saveWOItemCorrectiveAction(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();
                
                $corrective_action = $this->saveWOItemCorrectiveActionData($postData);
                
                if (!empty($corrective_action)) {
                    $woitemdata = [];
                    $woitemdata['updated_by'] = $authUserData['id'];
                    $woitemdata['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    $woitemdata['wo_corrective_action'] = $corrective_action;

                    $woitems = $this->CustomerAircraftWOItems->get($postData['wo_item_id']);
                    $woitems = $this->CustomerAircraftWOItems->patchEntity($woitems, $woitemdata);
                    $this->CustomerAircraftWOItems->save($woitems);
                    
                    $response = ['status'=>'success', 'message'=>'', 'corrective_action'=>$corrective_action];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOItemCorrectiveActionData($postData){
            $corrective_action_count = $this->AircraftWOItemCorrectiveActionHistories->find('all')->where(['wo_item_id'=>$postData['wo_item_id'], 'corrective_action'=>$postData['wo_corrective_action']])->select($this->AircraftWOItemCorrectiveActionHistories)->count();
            $authUserData = $this->Authentication->getResult()->getData();

            $corrective_action = '';
            if(empty($corrective_action_count)){
                $correctiveactionhistory = $this->AircraftWOItemCorrectiveActionHistories->newEmptyEntity();
                
                $postData['corrective_action'] = $postData['wo_corrective_action'];
                $postData['added_by'] = $authUserData['id'];
                $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                
                $correctiveactionhistory = $this->AircraftWOItemCorrectiveActionHistories->patchEntity($correctiveactionhistory, $postData);
                
                if ($this->AircraftWOItemCorrectiveActionHistories->save($correctiveactionhistory)) {
                    $corrective_action = $correctiveactionhistory->corrective_action;
                }
            }

            return $corrective_action;
        }

        public function saveCustomerRepairOrderRates(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $authUserData = $this->Authentication->getResult()->getData();

                    if(empty($postData['repair_order_rates_id'])){
                        $repairorderrates = $this->CustomerRepairOrderRates->newEmptyEntity();
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                    }else{
                        $repairorderrates = $this->CustomerRepairOrderRates->get($postData['repair_order_rates_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');
                    }
                    
                    $repairorderrates = $this->CustomerRepairOrderRates->patchEntity($repairorderrates, $postData);
                    
                    if ($this->CustomerRepairOrderRates->save($repairorderrates)) {
                        $repair_order_rates_id = $repairorderrates->id;

                        $response = ['status'=>'success', 'message'=>'', 'repair_order_rates_id'=>$repair_order_rates_id];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function fetchMessageListPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $work_order_id = !empty($postData['work_order_id']) ? $postData['work_order_id'] : '';
                $work_order_no = '';
                if(!empty($work_order_id)){
                    $aircraftwodet = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
                    $work_order_no = $aircraftwodet->work_order_no;
                }
                $wo_item_id = !empty($postData['wo_item_id']) ? $postData['wo_item_id'] : '';
                $section_clk = $postData['section_clk'];

                $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList($work_order_id);
                
                $this->set(compact('work_order_no', 'section_clk', 'wo_item_id', 'receivedmsglist'));
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/InventoryPopup/customer_otc/aircraft_wo_msgs_forall_users');
            }
        }

        public function fetchMessageSendPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList();
                
                $workorderlist = $this->CustomerAircraftWorkOrders->find('all')->where(['wo_status'=>'1'])->select(['id', 'work_order_no']);
                $worodata = $this->CustomerOTC->getWorkOrderitemListDropDown();

                $work_order_id = !empty($postData['work_order_id']) ? $postData['work_order_id'] : '';
                $wo_item_id = !empty($postData['wo_item_id']) ? $postData['wo_item_id'] : '';
                $section_clk = $postData['section_clk'];

                if($section_clk == 'new-msg'){
                    $wooptionmessages = $this->CustomerAircraftWOMessages->newEmptyEntity();
                }else{
                    $messagedata = $this->CustomerOTC->getAircraftWOMsgList($work_order_id, $postData['replay_message_id']);
                    $messages = '


----Original Message---
From: '.$messagedata['sent_from'].'
To: '.$messagedata['sent_to'].'
Subject: '.$messagedata['message_subject'].'   
Send: '.$messagedata['created_at'].'

'.$messagedata['message'];
                    

                    $wooptionmessages = $this->CustomerAircraftWOMessages->get($postData['replay_message_id']);
                    $wooptionmessages->message = $messages;
                    $wooptionmessages->message_to = $wooptionmessages->added_by;
                }

                $is_from_part = !empty($postData['is_from_part']) ? $postData['is_from_part'] : '';

                $userdet = $this->Users->find('all')->where(['suspended'=>'0'])->select(['Users.id', 'Users.full_name']);
                $userlist = [];

                foreach($userdet as $users){
                    $userlist[$users['id']] = $users['full_name'];
                }

                $messageobj = $this->CustomerAircraftWOMessages->newEmptyEntity();

                $this->set(compact('worodata', 'work_order_id', 'wo_item_id', 'wooptionmessages', 'section_clk', 'is_from_part', 'userlist', 'messageobj'));
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/InventoryPopup/customer_otc/aircraft_wo_send_new_message');
            }
        }

        public function fetchMessageViewPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $authUserData = $this->Authentication->getResult()->getData();

                $work_order_id = !empty($postData['work_order_id']) ? $postData['work_order_id'] : '';
                $message_id = $postData['message_id'];
                $work_order_no = !empty($postData['work_order_no']) ? $postData['work_order_no'] : '';

                $wooptionmessages = $this->CustomerOTC->getAircraftWOMsgList($work_order_id, $message_id);
                $messageobj = $this->CustomerAircraftWOMessages->get($message_id);
                if(empty($wooptionmessages->is_mark_read)){
                    $res = $this->CustomerAircraftWOMessages->updateAll(
                        array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'is_mark_read' => '1'),
                        array('id' => $message_id)
                    );
                }
                $sessionUser = $this->request->getSession()->read('Auth');;
                
                $userptorequests = [];
                if(!empty($wooptionmessages['pto_request_id'])){
                    $userptorequests = $this->UserPTORequests->get($wooptionmessages['pto_request_id']);
                }

                $this->set(compact('wooptionmessages', 'work_order_no', 'sessionUser', 'messageobj', 'userptorequests'));
                $this->viewBuilder()->setLayout('ajax');
                $this->render('/element/InventoryPopup/customer_otc/aircraft_wo_option_view_message');
            }
        }

        public function checkNewMessage(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $newmsgcount = $this->CustomerOTC->getNewMessageCount();
                
                echo $newmsgcount;die;
            }
        }

        public function markMessageReadUnread(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $messageid = $postData['messageid'];
                    $is_mark_read = $postData['is_mark_read'];
                    $authUserData = $this->Authentication->getResult()->getData();

                    if(isset($is_mark_read) && !empty($messageid)){
                        $res = $this->CustomerAircraftWOMessages->updateAll(
                            array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'is_mark_read' => $is_mark_read),
                            array('id IN' => $messageid)
                        );
                        
                        if ($res) {
                            $receivedmsglist = $this->CustomerOTC->getAircraftWOMsgList();
                            $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                            $msgtr = $InventoryAircraftWorkOrderHelper->getWOMessageListHTML($receivedmsglist);

                            $response = ['status'=>'success', 'message'=>'', 'msgtr'=>$msgtr];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWOItemSignoffCategoryDet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $wo_item_id = $postData['wo_item_id'];
                    $signoff_category = $postData['signoff_category'];
                    if(isset($signoff_category) && !empty($wo_item_id)){
                        $is_signoff_done = '0';
                        
                        $signoffinspectioninfo = $this->CustomerOTC->getWOItemSignOff($wo_item_id, $signoff_category);
                        if(!empty($signoffinspectioninfo) && $signoff_category == '1'){
                            $is_signoff_done = '1';
                        }else if($signoff_category != '1'){
                            $signoffdata = $this->CustomerOTC->getWOItemSignOff($wo_item_id, '1');
                            if(!empty($signoffdata)){
                                $is_signoff_done = '1';
                            }
                        }
                        $userMenuItems = $this->CustomerOTC->checkWorkOrderMenuPermission();
                        
                        $this->set(compact('signoffinspectioninfo', 'wo_item_id', 'signoff_category', 'userMenuItems', 'is_signoff_done'));
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/wo_item_signoff_inspection_info');
                    }else{
                        echo 'Failed';die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function checkWOItemSignoffCompl(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $wo_item_id = $postData['wo_item_id'];
                    $signoff_category = $postData['signoff_category'];
                    if(isset($signoff_category) && !empty($wo_item_id)){
                        $signoffinspectioninfo = $this->CustomerOTC->getWOItemSignOff($wo_item_id, $signoff_category);
                        $is_singoff_done = '0';
                        if(!empty($signoffinspectioninfo)){
                            $is_singoff_done = '1';
                        }
                        $response = ['status'=>'success', 'message'=>'', 'is_singoff_done'=>$is_singoff_done];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function printPreviewWO()
        {
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData(); 
                    
                    $reportdata = $this->CustomerOTC->getWOPrintPreviewReport($postData);
                    //print_r($reportdata);exit;
                    if(!empty($reportdata)){
                        ini_set('memory_limit', '512M');
                        ini_set('max_execution_time', 300);

                        $this->viewBuilder()->setLayout('ajax');
                        $report_type = $postData['wo_report_type'];
                        
                        $data = ['report_type'=>$report_type, 'reportdata'=>$reportdata];

                        if($report_type >= '39' && $report_type <= '40'){
                            $report_date = $postData['maint_date_to_show'];
                            $data['report_date']=$report_date;
                        }else if($report_type == '49' || $report_type == '56'){
                            $signoff_category = $postData['wo_signoff_preview'];
                            $data['signoff_category']=$signoff_category;
                        }else if($report_type == '52'){
                            $wo_department = $postData['wo_department_preview'];
                            $data['wo_department']=$signoff_category;
                        }else if($report_type == '37'){
                            $log_book_category = $postData['log_book_category'];
                            
                            $technician_name = '';
                            if(!empty($postData['labels_technician'])){
                                $userdet = $this->Users->find('all')->where(['id'=>$postData['labels_technician']])->select(['Users.full_name'])->first();

                                $technician_name = !empty($userdet) ? $userdet['full_name'] : '';
                            }
                            $postData['technician_name'] = $technician_name;
                            $statementdata = [];
                            if(!empty($postData['statement_name'])){
                                $statementdata = $this->Statements->get($postData['statement_name']);
                            }

                            $data['log_book_category'] = $log_book_category;
                            $data['postData'] = $postData;
                            $data['statementdata'] = $statementdata;
                        }

                        $settings = $this->CustomerOTC->getSettings();
                        $data['settings'] = $settings;
                        //$this->set(compact('reportdata', 'report_type', 'settings'));
                        
                        $view = new View();
                        $html = $view->element('/Inventory/customer_otc/work_order_report/work_order_print_report', $data);
                        
                        //echo $html;exit;
                        $mpdf = new Mpdf();
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->AddPage('', // L - landscape, P - portrait 
                        '', '', '', '',
                        0, // margin_left
                        0, // margin right
                        15, // margin top
                        10, // margin bottom
                        0, // margin header
                        0); // margin footer

                        $mpdf->WriteHTML($html);
                        
                        //save the file on particular location
                        //https://mpdf.github.io/reference/mpdf-functions/output.html
                        $fileName = "work_order_reports_".date('YmdHis').".pdf";
                        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");

                        $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                        echo json_encode($result);die;
                    }else{
                        $result = array('status'=>'failure', 'message'=>'There were no records.');
                        echo json_encode($result);die;
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong, please try again.');
                    echo json_encode($result);die;
                }
            }
        }

        public function validateWorkOrderACTT(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $work_order_id = $postData['work_order_id'];
                    
                    if(!empty($work_order_id)){
                        $aircraftmaintoverview = $this->CustomerAircraftWOLogBookValueOverviews->find('all')->where(['work_order_id'=>$work_order_id])->select($this->CustomerAircraftWOLogBookValueOverviews)->first();
                        
                        $is_actt_blank = '0';
                        if(!empty($aircraftmaintoverview) && !empty($aircraftmaintoverview->current_ac_tt)){
                            $is_actt_blank = '1';
                        }
                        $response = ['status'=>'success', 'message'=>'', 'is_actt_blank'=>$is_actt_blank];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function woItemOverviewWarranty(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $work_order_id = $postData['work_order_id'];
                    
                    if(!empty($work_order_id)){
                        $whereArr = ['CustomerAircraftWOItems.work_order_id'=>$work_order_id, 'item_overviews.item_is_warranty'=>'1'];
                        $woWarrantydet = $this->CustomerAircraftWOItems->find('all')
                                                        ->where($whereArr)
                                                        ->select(['item_overviews.warranty'])
                                                        ->join([
                                                            'item_overviews' => [
                                                                'table' => 'customer_aircraft_wo_item_overviews',
                                                                'type' => 'INNER',
                                                                'conditions' => 'item_overviews.wo_item_id = CustomerAircraftWOItems.id',
                                                            ]
                                                        ])
                                                        ->toArray();
                        
                        $warranty_count = count($woWarrantydet);
                        
                        $response = ['status'=>'success', 'message'=>'', 'warranty_count'=>$warranty_count];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getWarrantyInfoPaymentOptions(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $warranty_id = $postData['warranty_id'];
                    if(!empty($warranty_id)){
                        $work_order_id = $postData['work_order_id'];
                        $wo_item_id = $postData['wo_item_id'];

                        $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->find('all')->where(['work_order_id'=>$work_order_id, 'company_id'=>$warranty_id])->select($this->CustomerAircraftWOOptionWarrantyInfoes)->first();
                        
                        if(empty($wooptionwarrantyinfoes)){
                            $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->newEmptyEntity();
                        }
                        $wooptionwarrantyinfoes->company_id = $warranty_id;

                        $this->set(compact('wooptionwarrantyinfoes', 'wo_item_id', 'work_order_id'));
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/Inventory/customer_otc/wo_option_warranty_info_payment');
                    }else{
                        echo 'Failed';die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function validateLogbookCatFinalInspection(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $postData= $this->request->getData();

                $whereCondArr = ['wo_item_overview.log_book_category'=>$postData['log_book_category'], 'CustomerAircraftWOItems.work_order_id'=>$postData['work_order_id']];
                
                $woitems = $this->CustomerAircraftWOItems->find('all')
                                        ->where($whereCondArr)
                                        ->select(['CustomerAircraftWOItems.id'])
                                        ->join([
                                            'wo_item_overview'=>[
                                                'table'=>'customer_aircraft_wo_item_overviews',
                                                'type'=>'INNER',
                                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                        ])
                                        ->toArray();
                $itemidArr = [];
                foreach($woitems as $items){
                    $itemidArr[] = $items['id'];
                }

                $wosignoffitemscount = 0;
                if(!empty($itemidArr)){
                    $wosignoffitemscount = $this->AircraftWOItemSignoffs->find('all')
                                                            ->where(['wo_item_id IN'=>$itemidArr, 'inspected_by'=>$postData['labels_technician'], 'signoff_category'=>'1'])
                                                            ->select(['signoff_category'])
                                                            ->count();
                    
                    
                   
                }

                $response = ['status'=>'success', 'message'=>'', 'wosignoffitemscount'=>$wosignoffitemscount];
                echo json_encode($response);die;
            }
        }

        public function validateWOItemCorrectiveAction(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $postData= $this->request->getData();

                $whereCondArr = ['corrective_action LIKE'=>'%'.$postData['authentication_no_txt'].'%', 'wo_item_id'=>$postData['wo_item_id']];
                
                $corrective_action_count = $this->AircraftWOItemCorrectiveActionHistories->find('all')
                                        ->where($whereCondArr)
                                        ->select(['corrective_action'])
                                        ->count();
                
                $response = ['status'=>'success', 'message'=>'', 'corrective_action_count'=>$corrective_action_count];
                echo json_encode($response);die;
            }
        }

        public function saveWOOSRMoveItem(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $postData= $this->request->getData();
                $current_wo_item_id = $postData['wo_item_id'];
                $woitems = $this->CustomerAircraftWOItems->find('all')->where(['work_order_id'=>$postData['work_order_id'], 'wo_item_position'=>$postData['osr_item_number']])->select(['id'])->first();
                if(!empty($woitems)){
                    $woitemosrinfo = $this->CustomerAircraftWOOSRInfoes->get($postData['osr_info_id']);

                    $postData['wo_item_id'] = $woitems->id;
                    $postData['updated_by'] = $authUserData['id'];
                    $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                    $woitemosrinfo = $this->CustomerAircraftWOOSRInfoes->patchEntity($woitemosrinfo, $postData);

                    if($this->CustomerAircraftWOOSRInfoes->save($woitemosrinfo)){
                        $wo_item_id = $current_wo_item_id;
                        $osrlist = $this->getWOItemOSRListHTML($wo_item_id);

                        $response = ['status'=>'success', 'message'=>'', 'osrlist'=>$osrlist];

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try agian.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Please enter correct item no.'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOMoveItemFile(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $postData= $this->request->getData();

                $woitems = $this->CustomerAircraftWOItems->find('all')->where(['work_order_id'=>$postData['work_order_id'], 'wo_item_position'=>$postData['wo_file_item_number']])->select(['id'])->first();

                if(!empty($woitems)){

                    $connection = ConnectionManager::get('default');

                    $res = $connection->update(
                                                'customer_aircraft_wo_item_file',         // Table name
                                                ['wo_item_id' => $woitems->id], // Column(s) to update
                                                ['id' => $postData['wo_move_item_file_id']]           // WHERE condition
                                            );

                    if($res){
                        $wo_item_id = $postData['wo_item_id'];
                        $filesHTML = $this->getWOItemFileHTML($wo_item_id);

                        $woitemfiletr = $filesHTML['woitemfiletr'];
                        $woitemfilecount = $filesHTML['woitemfilecount'];

                        $response = array('status'=>'success', 'message'=>"", 'woitemfiletr'=>$woitemfiletr, 'woitemfilecount'=>$woitemfilecount);

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try agian.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Please enter correct item no.'];
                    echo json_encode($response);die;
                }
            }
        }

        public function saveWOMoveItemPhoto(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Customer OTC', $actionStatus))
                {
                    $actionItems = $actionStatus['Customer OTC'];
                }
            }
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                $this->viewBuilder()->setLayout('ajax');
                $postData= $this->request->getData();

                $woitems = $this->CustomerAircraftWOItems->find('all')->where(['work_order_id'=>$postData['work_order_id'], 'wo_item_position'=>$postData['wo_photo_item_number']])->select(['id'])->first();

                if(!empty($woitems)){

                    $connection = ConnectionManager::get('default');

                    $res = $connection->update(
                                                'customer_aircraft_wo_item_photo', // Table name
                                                ['wo_item_id' => $woitems->id], // Column(s) to update
                                                ['id' => $postData['wo_move_item_photo_id']] // WHERE condition
                                            );

                    if($res){
                        $wo_item_id = $postData['wo_item_id'];
                        $filesHTML = $this->getWOItemPhotoHTML($wo_item_id);

                        $woitemphototr = $filesHTML['woitemphototr'];
                        $woitemphotocount = $filesHTML['woitemphotocount'];

                        $response = array('status'=>'success', 'message'=>"", 'woitemphototr'=>$woitemphototr, 'woitemphotocount'=>$woitemphotocount);

                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try agian.'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Please enter correct item no.'];
                    echo json_encode($response);die;
                }
            }
        }

        public function removeWOItemParts(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    
                    $authUserData = $this->Authentication->getResult()->getData();
                    
                    $aircraftwoitemparts = $this->CustomerAircraftWOItemParts->get($postData['wo_item_part_id']);
                    
                    if (!empty($aircraftwoitemparts)) {
                        if(!empty($aircraftwoitemparts['serial_number'])){
                            $this->Inventories->updateAll(
                                array('updated_by'=>$authUserData['id'], 'modified'=>new \Cake\I18n\FrozenTime('now'), 'status'=>'1'),
                                array('serial_no'=>$aircraftwoitemparts['serial_number'])
                            );
                        }

                        if(!empty($postData['wo_item_part_id'])){
                            $history_title = 'Item Part Deleted';
                            $history_description = 'Part Number `'.$aircraftwoitemparts->part_number.'` deleted from work order item.';
                            $this->AircraftWOItemHistory->saveWOItemTabDeletedDataToHistory($aircraftwoitemparts, $history_title, $history_description);
                        }

                        $result = $this->CustomerAircraftWOItemParts->delete($aircraftwoitemparts);
                        if($result){
                            $this->Inventory->removeInventoryRequestForWO($postData['wo_item_part_id']);
                        }
                        
                        $woitempartlists = $this->CustomerOTC->getAircraftWOItemParts($postData['wo_item_id']);
                        $InventoryAircraftWorkOrderHelper = new InventoryAircraftWorkOrderHelper(new \Cake\View\View());
                        $woitempartshtml = $InventoryAircraftWorkOrderHelper->getWorkOrderItemPartListHTML($woitempartlists);

                        $response = ['status'=>'success', 'message'=>'', 'woitempartshtml'=>$woitempartshtml];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }
    }   

?>
