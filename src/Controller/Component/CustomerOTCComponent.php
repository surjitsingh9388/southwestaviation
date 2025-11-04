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
use Cake\Datasource\ConnectionManager;
use Cake\View\viewBuilder;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Datasource\FactoryLocator;

class CustomerOTCComponent extends Component {
    public array $components = ['Timezone', 'Authentication.Authentication'];

    protected \App\Model\Table\CustomerAircraftContractRatesTable $CustomerAircraftContractRates;
    protected \App\Model\Table\CustomerAdditionalShippingAddressesTable $CustomerAdditionalShippingAddresses;
    protected \App\Model\Table\CustomerAircraftComplianceInspectionsTable $CustomerAircraftComplianceInspections;
    protected \App\Model\Table\CustomerAircraftComplianceAirframesTable $CustomerAircraftComplianceAirframes;
    protected \App\Model\Table\CustomerAircraftComplianceEnginesTable $CustomerAircraftComplianceEngines;
    protected \App\Model\Table\CustomerAircraftMaintenanceAppliancesTable $CustomerAircraftMaintenanceAppliances;
    protected \App\Model\Table\CustomerAircraftMaintenanceAdsTable $CustomerAircraftMaintenanceAds;
    protected \App\Model\Table\CustomerAircraftWOMessagesTable $CustomerAircraftWOMessages;
    protected \App\Model\Table\CustomerOTCAircraftsTable $CustomerOTCAircrafts;
    protected \App\Model\Table\InventoryCustomersTable $InventoryCustomers;
    protected \App\Model\Table\CustomerAircraftComplianceInspectionHistoriesTable $CustomerAircraftComplianceInspectionHistories;
    protected \App\Model\Table\CustomerOTCInfoInvoicePartsTable $CustomerOTCInfoInvoiceParts;
    protected \App\Model\Table\CustomerAircraftMaintenanceOverviewsTable $CustomerAircraftMaintenanceOverviews;
    protected \App\Model\Table\AircraftMaintenanceHelicopterOverviewsTable $AircraftMaintenanceHelicopterOverviews;
    protected \App\Model\Table\CustomerAircraftMaintenanceEnginesTable $CustomerAircraftMaintenanceEngines;
    protected \App\Model\Table\AircraftMaintenanceJetEnginesTable $AircraftMaintenanceJetEngines;
    protected \App\Model\Table\CustomerAircraftMaintEngineCylHistoriesTable $CustomerAircraftMaintEngineCylHistories;
    protected \App\Model\Table\CustomerAircraftMaintenancePropsTable $CustomerAircraftMaintenanceProps;
    protected \App\Model\Table\CustomerAircraftMaintenanceNotesTable $CustomerAircraftMaintenanceNotes;
    protected \App\Model\Table\CustomerOTCInfoInvoicesTable $CustomerOTCInfoInvoices;
    protected \App\Model\Table\CustomerAircraftWorkOrdersTable $CustomerAircraftWorkOrders;
    protected \App\Model\Table\UsersTable $Users;
    protected \App\Model\Table\CustomerRepairOrderRatesTable $CustomerRepairOrderRates;
    protected \App\Model\Table\CustomerAircraftWOItemsTable $CustomerAircraftWOItems;
    protected \App\Model\Table\CustomerAircraftWOItemOverviewsTable $CustomerAircraftWOItemOverviews;
    protected \App\Model\Table\CustomerAircraftWOItemServicesTable $CustomerAircraftWOItemServices;
    protected \App\Model\Table\CustomerAircraftWOOSRInfoesTable $CustomerAircraftWOOSRInfoes;
    protected \App\Model\Table\AircraftWOItemSignoffsTable $AircraftWOItemSignoffs;
    protected \App\Model\Table\AircraftWOItemServiceLogsTable $AircraftWOItemServiceLogs;
    protected \App\Model\Table\CustomerAircraftWOOSRVendorsTable $CustomerAircraftWOOSRVendors;
    protected \App\Model\Table\CustomerAircraftWOOSRPOItemsTable $CustomerAircraftWOOSRPOItems;
    protected \App\Model\Table\CustomerAircraftWOLogBookValueOverviewsTable $CustomerAircraftWOLogBookValueOverviews;
    protected \App\Model\Table\AircraftWOLogBookValueHelicopterOverviewsTable $AircraftWOLogBookValueHelicopterOverviews;
    protected \App\Model\Table\CustomerAircraftWOLogBookValueEnginesTable $CustomerAircraftWOLogBookValueEngines;
    protected \App\Model\Table\AircraftWOLogBookValueJetEnginesTable $AircraftWOLogBookValueJetEngines;
    protected \App\Model\Table\CustomerAircraftWOLogBookValuePropsTable $CustomerAircraftWOLogBookValueProps;
    protected \App\Model\Table\CustomerAircraftWOOptionGeneralInfoesTable $CustomerAircraftWOOptionGeneralInfoes;
    protected \App\Model\Table\CustomerAircraftWOOptionGenInfoDepositsTable $CustomerAircraftWOOptionGenInfoDeposits;
    protected \App\Model\Table\CustomerAircraftWOOptionMiscChargesTable $CustomerAircraftWOOptionMiscCharges;
    protected \App\Model\Table\CustomerAircraftWOOptionPricingInfoesTable $CustomerAircraftWOOptionPricingInfoes;
    protected \App\Model\Table\CustomerAircraftWOOptionWarrantyInfoesTable $CustomerAircraftWOOptionWarrantyInfoes;
    protected \App\Model\Table\CustomerAircraftWOOptionTaxInfoesTable $CustomerAircraftWOOptionTaxInfoes;
    protected \App\Model\Table\CustomerAircraftWOOptionBillingInfoesTable $CustomerAircraftWOOptionBillingInfoes;
    protected \App\Model\Table\CustomerAircraftWOItemPartsTable $CustomerAircraftWOItemParts;
    protected \App\Model\Table\InventoryRequestItemsTable $InventoryRequestItems;
    protected \App\Model\Table\InventoryItemsTable $InventoryItems;
    protected \App\Model\Table\CustomerAircraftWOATACodesTable $CustomerAircraftWOATACodes;
    protected \App\Model\Table\CustomerAircraftWOLaborKitsTable $CustomerAircraftWOLaborKits;
    protected \App\Model\Table\InventoryVendorsTable $InventoryVendors;
    protected \App\Model\Table\InventoryLocationsTable $InventoryLocations;
    protected \App\Model\Table\InventoryToolCertificationHistoriesTable $InventoryToolCertificationHistories;
    protected \App\Model\Table\CustomerAircraftWOItemToolsTable $CustomerAircraftWOItemTools;
    protected \App\Model\Table\InventoryToolsTable $InventoryTools;
    protected \App\Model\Table\InventoryCustomerAddressesTable $InventoryCustomerAddresses;
    protected \App\Model\Table\StatesTable $States;
    protected \App\Model\Table\AircraftWOItemDiscrepancyHistoriesTable $AircraftWOItemDiscrepancyHistories;
    protected \App\Model\Table\AircraftWOItemCorrectiveActionHistoriesTable $AircraftWOItemCorrectiveActionHistories;
    protected \App\Model\Table\AircraftWOItemHistoriesTable $AircraftWOItemHistories;
    protected \App\Model\Table\UserMenuItemsTable $UserMenuItems;
    protected \App\Model\Table\CustomerAircraftWOOptionMiscFuelChargesTable $CustomerAircraftWOOptionMiscFuelCharges;
    protected \App\Model\Table\SettingsTable $Settings;
    protected \App\Model\Table\InventoryCustomerPhonesTable $InventoryCustomerPhones;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    
    public function getAllAircraftContractRate($aircraft_id){
        $this->CustomerAircraftContractRates = $this->getController()->fetchTable('CustomerAircraftContractRates');
        $contractRateList = $this->CustomerAircraftContractRates->find('all')->where(['aircraft_id'=>$aircraft_id, 'status'=>'1'])->select(['CustomerAircraftContractRates.id', 'CustomerAircraftContractRates.aircraft_id', 'CustomerAircraftContractRates.contract_rate_department', 'CustomerAircraftContractRates.rate_an_hour']);
        $aircraftContractRates = [];
        $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
        foreach($contractRateList as $row){
            $data = [];
            $data['id'] = $row['id'];
            $data['aircraft_id'] = $row['aircraft_id'];
            $data['department'] = $contractRateDepartment[$row['contract_rate_department']];
            $data['rate_an_hour'] = $row['rate_an_hour'];
            $aircraftContractRates[] = $data;
        }
        return $aircraftContractRates;
    }

    public function getAircraftModelList($make_id = ''){
        $connection = ConnectionManager::get('default');
        
        $makecond = '';
        if(!empty($make_id)){
            $results = $connection->execute(
                "SELECT id, model FROM customer_otc_aircraft_model WHERE status = '1' and make_id = :make_id",
                ['make_id' => $make_id])->fetchAll('assoc');
        }else{
            $results = $connection->execute(
                "SELECT id, model FROM customer_otc_aircraft_model WHERE status = '1'")->fetchAll('assoc');
        }

        $aircraftmodels = [];
        foreach($results as $row){
            $aircraftmodels[$row['id']] = $row['model'];
        }

        return $aircraftmodels;
    }

    public function getAllCustAddlShippingAddress($customer_id){
        $this->CustomerAdditionalShippingAddresses = $this->getController()->fetchTable('CustomerAdditionalShippingAddresses');

        $results = $this->CustomerAdditionalShippingAddresses->find('all')->where(['customer_id'=>$customer_id, 'status'=>'1'])->select(['CustomerAdditionalShippingAddresses.id', 'CustomerAdditionalShippingAddresses.additional_shipping_address_description', 'CustomerAdditionalShippingAddresses.additional_shipping_full_address']);
        $customerAddlShipAddrList = [];

        foreach($results as $row){
            $data = [];
            $data['id'] = $row['id'];
            $data['additional_shipping_address_description'] = $row['additional_shipping_address_description'];
            $data['additional_shipping_full_address'] = $row['additional_shipping_full_address'];
            $customerAddlShipAddrList[] = $data;
        }
        return $customerAddlShipAddrList;
    }

    public function getCustomerInfoAttachment($customer_id){
        $connection = ConnectionManager::get('default');
        
        $customerInfoAttachments = $connection->execute(
            "SELECT ca.*, u.full_name FROM inventory_customer_attachments ca join  users u on ca.uploaded_by = u.id WHERE ca.status = '1' and ca.inventory_customer_id = :customer_id",
            ['customer_id' => $customer_id])->fetchAll('assoc');

        return $customerInfoAttachments;
    }

    public function getAircraftInfoAttachment($aircraft_id){
        $connection = ConnectionManager::get('default');
        
        $customerInfoAttachments = $connection->execute(
            "SELECT ca.*, u.full_name FROM customer_otc_aircraft_attachments ca join  users u on ca.uploaded_by = u.id WHERE ca.status = '1' and ca.aircraft_id = :aircraft_id",
            ['aircraft_id' => $aircraft_id])->fetchAll('assoc');

        return $customerInfoAttachments;
    }

    public function getAircraftComplianceList($aircraft_id){
        $this->CustomerAircraftComplianceInspections = $this->getController()->fetchTable('CustomerAircraftComplianceInspections');
        $this->CustomerAircraftComplianceAirframes = $this->getController()->fetchTable('CustomerAircraftComplianceAirframes');
        $this->CustomerAircraftComplianceEngines = $this->getController()->fetchTable('CustomerAircraftComplianceEngines');

        $inspectionItemList = $this->CustomerAircraftComplianceInspections->find('all')->where(['CustomerAircraftComplianceInspections.aircraft_id'=>$aircraft_id])->select(['CustomerAircraftComplianceInspections.id', 'CustomerAircraftComplianceInspections.inspection_name']);

        $airframeItemList = $this->CustomerAircraftComplianceAirframes->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['CustomerAircraftComplianceAirframes.id', 'CustomerAircraftComplianceAirframes.name']);

        $engineItemList = $this->CustomerAircraftComplianceEngines->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['CustomerAircraftComplianceEngines.id', 'CustomerAircraftComplianceEngines.name']);

        $return = ['inspectionItemList'=>$inspectionItemList, 'airframeItemList'=>$airframeItemList, 'engineItemList'=>$engineItemList];

        return $return;
    }

    public function getAircraftMaintApplianceListHtml($aircraft_id){
        $this->CustomerAircraftMaintenanceAppliances = $this->getController()->fetchTable('CustomerAircraftMaintenanceAppliances');
        $aircraftmaintapplianceslist = $this->CustomerAircraftMaintenanceAppliances->find('all')->where(['aircraft_id'=>$aircraft_id])->select($this->CustomerAircraftMaintenanceAppliances);

        $tblrow = '';
        foreach($aircraftmaintapplianceslist as $row){
            $tblrow .= '<tr class="airmaintapplrow" data-val="'.$row->id.'">';
            $tblrow .= '<td>'.$row->a_appliance.'</td>';
            $tblrow .= '<td>'.$row->a_manufacturer.'</td>';
            $tblrow .= '<td>'.$row->a_model_no.'</td>';
            $tblrow .= '<td>'.$row->a_part_no.'</td>';
            $tblrow .= '<td>'.$row->a_serial_no.'</td>';
            $tblrow .= '</tr>';
        }

        return $tblrow;
    }

    public function getAircraftMaintAdsListHtml($aircraft_id){
        $this->CustomerAircraftMaintenanceAds = $this->getController()->fetchTable('CustomerAircraftMaintenanceAds');
        $aircraftmaintadslist = $this->CustomerAircraftMaintenanceAds->find('all')->where(['aircraft_id'=>$aircraft_id])->select($this->CustomerAircraftMaintenanceAds);

        $tblrow = '';
        foreach($aircraftmaintadslist as $row){
            $tblrow .= '<tr class="airmaintadsrow" data-val="'.$row->id.'">';
            $tblrow .= '<td>'.$row->ad_no.'</td>';
            $tblrow .= '<td>'.$row->ad_name.'</td>';
            $tblrow .= '<td>'.$row->ad_revision_date.'</td>';
            $tblrow .= '<td>'.$row->ad_notes.'</td>';
            $tblrow .= '</tr>';
        }

        return $tblrow;
    }

    public function getAllCustomerList(){
        $this->InventoryCustomers = $this->getController()->fetchTable('InventoryCustomers');
        
        $connection = ConnectionManager::get('default');
        
        $customerlists = $connection->execute(
            "SELECT c.id, c.customer_name, c.cellular_phone, c.city, GROUP_CONCAT(aircraft_registration_number separator '<br>') as aircraft_reg_no FROM `inventory_customers` c join customer_otc_aircrafts a on c.id = a.customer_id WHERE a.status='1' group by a.customer_id  order by c.customer_name")->fetchAll('assoc');
        
        return $customerlists;
    }

    public function getAircraftCompInspListHtml($aircraft_inspection_id){
        $this->CustomerAircraftComplianceInspectionHistories = $this->getController()->fetchTable('CustomerAircraftComplianceInspectionHistories');
        $compinsphistorylist = $this->CustomerAircraftComplianceInspectionHistories->find('all')->where(['aircraft_inspection_id'=>$aircraft_inspection_id])->select($this->CustomerAircraftComplianceInspectionHistories);

        $tblrow = '';
        foreach($compinsphistorylist as $row){
            $tblrow .= '<tr class="airmcompinsphistrow" data-val="'.$row->id.'">';
            $tblrow .= '<td>'.$row->inspection_code.'</td>';
            $tblrow .= '<td>'.$row->date_override.'</td>';
            $tblrow .= '<td>'.$row->insp_current_ac_tach.'</td>';
            $tblrow .= '</tr>';
        }

        return $tblrow;
    }

    public function OTCInvoicePartHistHTML($customer_id){
        $this->CustomerOTCInfoInvoiceParts = $this->getController()->fetchTable('CustomerOTCInfoInvoiceParts');
        $invoicepartlists = $this->CustomerOTCInfoInvoiceParts->find('all')
                                    ->where(['CustomerOTCInfoInvoiceParts.customer_id'=>$customer_id])
                                    ->select($this->CustomerOTCInfoInvoiceParts)->select(['invoice.otc_invoice_no', 'inventory.serial_no', 'invitm.part_number', 'invoice.id'])
                                    ->join([
                                        'invitm' => [
                                            'table' => 'inventory_items',
                                            'type' => 'INNER',
                                            'conditions' => 'invitm.id = CustomerOTCInfoInvoiceParts.part_number',
                                        ],
                                        'invoice' => [
                                            'table' => 'customer_otc_info_invoices',
                                            'type' => 'INNER',
                                            'conditions' => 'invoice.id = CustomerOTCInfoInvoiceParts.otc_invoice_id',
                                        ],
                                        'inventory' => [
                                            'table' => 'inventories',
                                            'type' => 'LEFT',
                                            'conditions' => 'inventory.id = CustomerOTCInfoInvoiceParts.serial_number',
                                        ]
                                    ])->order(['invoice.id'=>'DESC']);

        $tblrow = '';
        foreach($invoicepartlists as $row){
            $tblrow .= '<tr class="otcinfotblrow" data-val="'.$row['invoice']['id'].'">';
            $tblrow .= '<td>'.$row['invoice']['otc_invoice_no'].'</td>';
            $tblrow .= '<td>'.$row['invitm']['part_number'].'</td>';
            $tblrow .= '<td>'.$row['part_description'].'</td>';
            $tblrow .= '<td>'.$row['qty_needed'].'</td>';
            $tblrow .= '<td>'.$row['inventory']['serial_no'].'</td>';
            $tblrow .= '</tr>';
        }

        return $tblrow;
    }

    public function allPartOnInvoiceHTML($otc_invoice_id){
        $this->CustomerOTCInfoInvoiceParts = $this->getController()->fetchTable('CustomerOTCInfoInvoiceParts');
        $invoicepartlists = $this->CustomerOTCInfoInvoiceParts->find('all')
                                    ->where(['CustomerOTCInfoInvoiceParts.otc_invoice_id'=>$otc_invoice_id])
                                    ->select($this->CustomerOTCInfoInvoiceParts)
                                    ->select(['invitm.part_number'])
                                    ->join([
                                        'invitm' => [
                                            'table' => 'inventory_items',
                                            'type' => 'INNER',
                                            'conditions' => 'invitm.id = CustomerOTCInfoInvoiceParts.part_number',
                                        ]
                                    ])
                                    ->order(['CustomerOTCInfoInvoiceParts.id'=>'DESC']);

        $tblrow = '';
        $totalpartsubtotal = 0;
        foreach($invoicepartlists as $key=>$row){
            $totalprice = $row['price_each'];
            if(!empty($row['give_discount_percentage'])){
                $caldiscount = ($row['price_each']*$row['give_discount_percentage'])/100;
                $totalprice = $totalprice-$caldiscount;
            }
            $activeclass = '';
            if($key == 0){
                $activeclass = 'otcinvoicetblrow_active';
            }
            
            $tblrow .= '<tr class="otcinvoicetblrow '.$activeclass.'" data-val="'.$row['id'].'">';
            $tblrow .= '<td>'.$row['qty_needed'].'</td>';
            $tblrow .= '<td>'.$row['qty_cust_owned'].'</td>';
            $tblrow .= '<td>'.$row['invitm']['part_number'].'</td>';
            $tblrow .= '<td>'.$row['part_description'].'</td>';
            $tblrow .= '<td>'.$row['price_each'].'</td>';
            $tblrow .= '<td>'.$totalprice.'</td>';
            $tblrow .= '</tr>';

            $totalpartsubtotal += $totalprice;
        }

        return ['tblrow'=>$tblrow, 'totalpartsubtotal'=>$totalpartsubtotal];
    }

    public function getAircraftMaintenanceTabData($postData){
        
        $engine_type = $postData['engine_type'];
        if($engine_type != '5'){
            $this->CustomerAircraftMaintenanceOverviews = $this->getController()->fetchTable('CustomerAircraftMaintenanceOverviews');

            $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceOverviews)->first();
        }else{
            $this->AircraftMaintenanceHelicopterOverviews = $this->getController()->fetchTable('AircraftMaintenanceHelicopterOverviews');

            $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceHelicopterOverviews)->first();
        }

        $aircraftmaintengine = '';
        $aircraftmaintenginecylhistorylist = '';
        if($engine_type != '5'){
            if($engine_type == '1' || $engine_type == '2'){
                $this->CustomerAircraftMaintenanceEngines = $this->getController()->fetchTable('CustomerAircraftMaintenanceEngines');
                $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceEngines)->first(); 
            }else{
                $this->AircraftMaintenanceJetEngines = $this->getController()->fetchTable('AircraftMaintenanceJetEngines');
                $aircraftmaintengine = $this->AircraftMaintenanceJetEngines->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->AircraftMaintenanceJetEngines)->first(); 
            }
            
            if($engine_type == '1' || $engine_type == '2'){
                $this->CustomerAircraftMaintEngineCylHistories = $this->getController()->fetchTable('CustomerAircraftMaintEngineCylHistories');
                $aircraftmaintenginecylhistorylist = $this->CustomerAircraftMaintEngineCylHistories->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintEngineCylHistories);
            }
        }
        
        $this->CustomerAircraftMaintenanceProps = $this->getController()->fetchTable('CustomerAircraftMaintenanceProps');
        $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceProps)->first(); 
        
        $this->CustomerAircraftMaintenanceAppliances = $this->getController()->fetchTable('CustomerAircraftMaintenanceAppliances');
        $aircraftmaintapplianceslist = $this->CustomerAircraftMaintenanceAppliances->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceAppliances); 
        
        $this->CustomerAircraftMaintenanceAds = $this->getController()->fetchTable('CustomerAircraftMaintenanceAds');
        $aircraftmaintadslist = $this->CustomerAircraftMaintenanceAds->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceAds); 
        
        $this->CustomerAircraftMaintenanceNotes = $this->getController()->fetchTable('CustomerAircraftMaintenanceNotes');
        $aircraftmaintnotes = $this->CustomerAircraftMaintenanceNotes->find('all')->where(['aircraft_id'=>$postData['aircraft_id']])->select($this->CustomerAircraftMaintenanceNotes)->first(); 
        

        $returnArr = ['aircraftmaintoverview'=>$aircraftmaintoverview, 'aircraftmaintengine'=>$aircraftmaintengine, 'aircraftmaintenginecylhistorylist'=>$aircraftmaintenginecylhistorylist, 'aircraftmaintprops'=>$aircraftmaintprops, 'aircraftmaintapplianceslist'=>$aircraftmaintapplianceslist, 'aircraftmaintadslist'=>$aircraftmaintadslist, 'aircraftmaintnotes'=>$aircraftmaintnotes];
        
        return $returnArr;
    }

    public function getOTCInvoiceNumber(){
        $this->CustomerOTCInfoInvoices = $this->getController()->fetchTable('CustomerOTCInfoInvoices');
        $otcinfoinvoices = $this->CustomerOTCInfoInvoices->find('all')->select($this->CustomerOTCInfoInvoices)->all()->last();
        
        $currentyear = date('y');
        if(!empty($otcinfoinvoices)){
            $otcinvoicearr = explode('-', $otcinfoinvoices->otc_invoice_no);
            if($otcinvoicearr[0] == $currentyear){
                $otcinvoiceid = $otcinvoicearr[1]+1;
                $remaingtoaddzero = 4-strlen($otcinvoiceid);
                $invoice_number = $otcinvoicearr[0].'-';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $invoice_number .= '0';
                }
                $invoice_number .= $otcinvoiceid;
            }else{
                $invoice_number = $currentyear.'-0001';
            }
        }else{
            $invoice_number = $currentyear.'-0001';
        }
        
        return $invoice_number;
    }

    public function getCustomerOTCPageInfo($customer_id, $aircraft_id=''){
        $this->CustomerOTCAircrafts = $this->getController()->fetchTable('CustomerOTCAircrafts');
        $this->InventoryCustomers = $this->getController()->fetchTable('InventoryCustomers');
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $this->Users = $this->getController()->fetchTable('Users');
        $this->CustomerRepairOrderRates = $this->getController()->fetchTable('CustomerRepairOrderRates');

        $aircraftregdetail = [];
        $userlist = [];
        $created_by = '';
        $created_by_userid = '';
        $clientphone = [];

        if(!empty($aircraft_id)){
            $aircraftregdetail = $this->CustomerOTCAircrafts->get($aircraft_id);
        }
        $inventorycustomers = '';
        if(!empty($customer_id)){
            $inventorycustomers = $this->InventoryCustomers->get($customer_id);
            
            /*if(!empty($inventorycustomers->work_phone)){
                $clientphone[$inventorycustomers->work_phone] = $inventorycustomers->work_phone;
            }
            if(!empty($inventorycustomers->home_phone)){
                $clientphone[$inventorycustomers->home_phone] = $inventorycustomers->home_phone;
            }*/
            if(!empty($inventorycustomers->cellular_phone)){
                $clientphone[$inventorycustomers->cellular_phone] = $inventorycustomers->cellular_phone;
            }
            
            $userdet = $this->Users->find('all')->where(['suspended'=>'0'])->select(['Users.id', 'Users.full_name']);
            
            foreach($userdet as $users){
                $userlist[$users['id']] = $users['full_name'];
                if($inventorycustomers->added_by == $users['id']){
                    $created_by = $users['full_name'];
                    $created_by_userid = $users['id'];
                }
            }
        }

        $repairorderhistory = [];
        $repairorderrates = [];

        if(!empty($customer_id)){
            $repairorderhistory = $this->CustomerAircraftWorkOrders->find('all')->where(['wo_customer_id'=>$customer_id, 'order_type'=>'2'])->select($this->CustomerAircraftWorkOrders);

            $repairorderrates = $this->CustomerRepairOrderRates->find('all')->where(['customer_id'=>$customer_id])->select($this->CustomerRepairOrderRates)->first();
        }
        $aircraftworkorderdata = [];
        if(!empty($aircraft_id)){
            $aircraftworkorderdata = $this->CustomerAircraftWorkOrders->find('all')->where(['aircraft_id'=>$aircraft_id, 'order_type'=>'1'])->select(['CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'CustomerAircraftWorkOrders.created_at']);
        }
        

        $returnArr = ['aircraftregdetail'=>$aircraftregdetail, 'inventorycustomers'=>$inventorycustomers, 'clientphone'=>$clientphone, 'userlist'=>$userlist, 'created_by'=>$created_by, 'created_by_userid'=>$created_by_userid, 'repairorderhistory'=>$repairorderhistory, 'aircraftworkorderdata'=>$aircraftworkorderdata, 'repairorderrates'=>$repairorderrates];

        return $returnArr;
    }

    public function getAircraftWorkOrderNumber(){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $aircraftworkorders = $this->CustomerAircraftWorkOrders->find('all', array('order' => 'work_order_no ASC'))->select($this->CustomerAircraftWorkOrders)->all()->last();
        
        $currentyear = date('y');
        if(!empty($aircraftworkorders)){
            $wonoarr = explode('-', $aircraftworkorders->work_order_no);
            if($wonoarr[0] == $currentyear){
                $aircraftworkorderid = $wonoarr[1]+1;
                $remaingtoaddzero = 4-strlen($aircraftworkorderid);
                $work_order_number = $wonoarr[0].'-';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $work_order_number .= '0';
                }
                $work_order_number .= $aircraftworkorderid;
            }else{
                $work_order_number = $currentyear.'-0001';
            }
        }else{
            $work_order_number = $currentyear.'-0001';
        }

        return $work_order_number;
    }

    public function getWrkOrderAllTabData($postData){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
        $this->CustomerAircraftWOItemOverviews = $this->getController()->fetchTable('CustomerAircraftWOItemOverviews');
        $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');
        $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');

        $aircraftworkorders = $this->CustomerAircraftWorkOrders->get($postData['work_order_id']);
        $work_order_number = $aircraftworkorders->work_order_no;
        
        $aircraftwoitemlist = $this->CustomerAircraftWOItems->find('all', array('order' => 'wo_item_position ASC'))->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOItems);
        $totalitemcount = $aircraftwoitemlist->count();
        $totalitemcount = empty($totalitemcount) ? 1 : $totalitemcount;
        //$aircraftwoitems = $aircraftwoitemlist->first();
        
        
        $current_item_position = !empty($postData['current_item_position']) ? $postData['current_item_position'] : '0';
        
        $wo_item_positions = [];
        $wo_item_position_index = 0;
        $signoffincompletes = '0';

        $this->AircraftWOItemSignoffs = $this->getController()->fetchTable('AircraftWOItemSignoffs');

        if($aircraftwoitemlist->count() > 0){
            $aircraftwoitems = [];
            foreach($aircraftwoitemlist as $key=>$woitems){
                $wo_item_positions[] = $woitems['wo_item_position'];
                if(empty($current_item_position) && $key=='0'){
                    $aircraftwoitems = $woitems;
                    $current_item_position = $woitems['wo_item_position'];
                    $wo_item_position_index = $key;
                }else if($woitems['wo_item_position'] == $current_item_position){
                    $aircraftwoitems = $woitems;
                    $wo_item_position_index = $key;
                }
                if($woitems['wo_item_status'] == '1'){
                    $wosignoffitems = $this->AircraftWOItemSignoffs->find('all', ['order'=>'signoff_category'])
                                                            ->where(['wo_item_id'=>$woitems['id']])
                                                            ->select(['signoff_category']);
                    
                    if($wosignoffitems->count() > 0){
                        $issignoffdone = '0';
                        foreach($wosignoffitems as $key=>$signoffs){
                            if($signoffs['signoff_category'] == '1'){
                                $issignoffdone = '1';
                                break;
                            }else if($signoffs['signoff_category'] >= '12' && $signoffs['signoff_category'] <= '23'){
                                $issignoffdone = '1';
                                $signoffincompletes++;
                                break;
                            }
                        }
                        if(empty($issignoffdone)){
                            $signoffincompletes = $signoffincompletes+2;
                        }
                    }else{
                        $signoffincompletes = $signoffincompletes+2;
                    }
                }
            }
        }else{
            $aircraftwoitems = '';
        }

        $wo_item_id = '';
        $work_order_id = '';
        if(!empty($aircraftwoitems)){
            $wo_item_id = $aircraftwoitems->id;
            $work_order_id = $aircraftwoitems->work_order_id;
        }

        $aircraftwoitemoverviews = '';
        $aircraftwoitemservices = '';
        $technicianData = [];
        $aircraftwoitemosrinfoes = [];
        $aircraftwoitemphotoes = [];
        $aircraftwoitemfiles = [];
        $aircraftwoitemparts = [];
        $woitemtoollists = [];
        $woitemhistorylist = [];
        $woitemsummary = [];

        if(!empty($wo_item_id)){
            $aircraftwoitemoverviews = $this->CustomerAircraftWOItemOverviews->find('all')
                                        ->where(['wo_item_id'=>$wo_item_id])
                                        ->select($this->CustomerAircraftWOItemOverviews)
                                        ->select(['wo_ata_codes.ata_code', 'wo_labor_kits.labor_kit'])
                                        ->join([
                                            'wo_ata_codes' => [
                                                'table' => 'customer_aircraft_wo_ata_codes',
                                                'type' => 'LEFT',
                                                'conditions' => 'wo_ata_codes.id = CustomerAircraftWOItemOverviews.ata_code',
                                            ]
                                        ])
                                        ->join([
                                            'wo_labor_kits' => [
                                                'table' => 'customer_aircraft_wo_labor_kits',
                                                'type' => 'LEFT',
                                                'conditions' => 'wo_labor_kits.id = CustomerAircraftWOItemOverviews.labor_kit_name',
                                            ]
                                        ])
                                        ->first();
            
            $aircraftwoitemserviceslist = $this->getAircraftWOServicesData($wo_item_id);
            extract($aircraftwoitemserviceslist);

            $aircraftwoitemosrinfoes = $this->getAircraftWOOSRList($wo_item_id);
            $aircraftwoitemphotoes = $this->getAircraftWOItemPhotoes($wo_item_id);
            $aircraftwoitemfiles = $this->getAircraftWOItemFiles($wo_item_id);
            $aircraftwoitemparts = $this->getAircraftWOItemParts($wo_item_id);
            $woitemtoollists = $this->getAircraftWOItemTools($wo_item_id);
            $woitemhistorylist = $this->getAircraftWOItemHistoryData($wo_item_id);
            $woitemsummary = $this->getWOItemSummaryData($work_order_id, $wo_item_id);
        }
        
        $aircraftwoitemservices = !empty($aircraftwoitemservices) ? $aircraftwoitemservices : $this->CustomerAircraftWOItemServices->newEmptyEntity();

        $resp = array('aircraftworkorders'=>$aircraftworkorders, 'work_order_number'=>$work_order_number, 'aircraftwoitems'=>$aircraftwoitems, 'totalitemcount'=>$totalitemcount, 'aircraftwoitemoverviews'=>$aircraftwoitemoverviews, 'aircraftwoitemservices'=>$aircraftwoitemservices, 'technicianData'=>$technicianData, 'aircraftwoitemosrinfoes'=>$aircraftwoitemosrinfoes, 'current_item_position'=>$current_item_position, 'wo_item_positions'=>$wo_item_positions, 'wo_item_position_index'=>$wo_item_position_index, 'aircraftwoitemphotoes'=>$aircraftwoitemphotoes, 'aircraftwoitemfiles'=>$aircraftwoitemfiles, 'aircraftwoitemparts'=>$aircraftwoitemparts, 'woitemtoollists'=>$woitemtoollists, 'woitemhistorylist'=>$woitemhistorylist,'woitemsummary'=>$woitemsummary, 'signoffincompletes'=>$signoffincompletes);
        
        return $resp;
    }

    public function getAircraftWOOSRList($wo_item_id){
        $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
        $aircraftwoitemosrinfoes = $this->CustomerAircraftWOOSRInfoes->find('all')
                                    ->where(['CustomerAircraftWOOSRInfoes.wo_item_id'=>$wo_item_id])
                                    ->select($this->CustomerAircraftWOOSRInfoes)
                                    ->select(['vendors.vendor_name'])
                                    ->join([
                                        'vendors' => [
                                            'table' => 'customer_aircraft_wo_osr_vendors',
                                            'type' => 'LEFT',
                                            'conditions' => 'vendors.id = CustomerAircraftWOOSRInfoes.osr_repair_done_by',
                                        ]
                                    ])
                                    ->order(['CustomerAircraftWOOSRInfoes.id'=>'DESC']);
                                   
        return $aircraftwoitemosrinfoes;
    }

    public function getAircraftWOServicesData($wo_item_id, $repair_technician_id=''){
        $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');
        $this->CustomerAircraftWOItemOverviews = $this->getController()->fetchTable('CustomerAircraftWOItemOverviews');
        $this->AircraftWOItemServiceLogs = $this->getController()->fetchTable('AircraftWOItemServiceLogs');

        $aircraftwoitemservices = '';
        $technicianData = [];

        if(!empty($wo_item_id)){
            $aircraftwoitemserviceslist = $this->CustomerAircraftWOItemServices->find('all', array('order'=>'CustomerAircraftWOItemServices.id DESC'))
                                    ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$wo_item_id])
                                    ->select($this->CustomerAircraftWOItemServices)
                                    ->select(['users.full_name'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'LEFT',
                                            'conditions' => 'users.id = CustomerAircraftWOItemServices.repair_technician',
                                        ]
                                    ])
                                    ->order(['CustomerAircraftWOItemServices.id'=>'DESC']);
            
            foreach($aircraftwoitemserviceslist as $key=>$row){
                if($key == 0 || $row['repair_technician'] == $repair_technician_id){
                    $aircraftwoitemservices = $row;
                    if(isset($aircraftwoitemservices['users']['full_name']) && !empty($aircraftwoitemservices['users']['full_name'])){
                        $aircraftwoitemservices->repair_technician_name = $aircraftwoitemservices['users']['full_name'];
                    }
                    $serviceslogarr = $this->AircraftWOItemServiceLogs->find('all')->where(['wo_services_id'=>$row['id']])->select($this->AircraftWOItemServiceLogs);

                    $login_time = '';
                    foreach($serviceslogarr as $slogs){
                        if(empty($slogs['logout_time'])){
                            $login_time = $slogs['login_time'];
                        }
                    }
                    $aircraftwoitemservices->login_time = $login_time;
                }
                if(isset($row['users']['full_name']) && !empty($row['users']['full_name'])){
                    $technicianData[$row['repair_technician']] = $row['users']['full_name'];
                }
            }
            
            if(!empty($aircraftwoitemservices) && empty($aircraftwoitemservices->estimated_hrs_for_item)){
                $aircraftwoitemoverviews = $this->CustomerAircraftWOItemOverviews->find('all')
                                    ->where(['wo_item_id'=>$wo_item_id])
                                    ->select($this->CustomerAircraftWOItemOverviews)
                                    ->first();

                $aircraftwoitemservices->estimated_hrs_for_item = !empty($aircraftwoitemoverviews->estimated_hour) ? $aircraftwoitemoverviews->estimated_hour : 0;
            }
        }
        
        $resp = ['aircraftwoitemservices'=>$aircraftwoitemservices, 'technicianData'=>$technicianData];

        return $resp;
    }

    public function getAircraftWOItemPhotoes($wo_item_id){
        $connection = ConnectionManager::get('default');

        $woitemphotoes = $connection
                            ->execute(
                                'SELECT * FROM customer_aircraft_wo_item_photo WHERE wo_item_id = :wo_item_id and status = "1"',
                                ['wo_item_id' => $wo_item_id]
                            )
                            ->fetchAll('assoc');

        return $woitemphotoes;
    }

    public function getAircraftWOItemFiles($wo_item_id){
        $connection = ConnectionManager::get('default');

        $woitemfiles = $connection
                            ->execute(
                                'SELECT * FROM customer_aircraft_wo_item_file WHERE wo_item_id = :wo_item_id and status = "1"',
                                ['wo_item_id' => $wo_item_id]
                            )
                            ->fetchAll('assoc');

        return $woitemfiles;
    }
    
    public function getWOItemSummaryData($work_order_id, $wo_item_id){
        $connection = ConnectionManager::get('default');
        
        $woservicedata = $connection->execute(
            "SELECT SUM(s.total_hrs_for_tech) as totaltechhour, SUM(DISTINCT s.estimated_hrs_for_item) as totalestimatedhour from customer_aircraft_wo_item_services s join customer_aircraft_wo_items i on s.wo_item_id = i.id where i.work_order_id = :work_order_id",
            ['work_order_id' => $work_order_id])->fetch('assoc');
        
        $totaltechhour = !empty($woservicedata['totaltechhour']) ? $woservicedata['totaltechhour'] : 0;
        $totalestimatedhour = !empty($woservicedata['totalestimatedhour']) ? $woservicedata['totalestimatedhour'] : 0;
        
        $summarydata = ['totaltechhour'=>$totaltechhour, 'totalestimatedhour'=>$totalestimatedhour];

        return $summarydata;
    }

    public function getAircraftWOOSRPONumber($postData){
        $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
        $woosrinfocount = 0;
        if(!empty($postData['osr_purchase_order_no'])){
            $woosrinfocount = $this->CustomerAircraftWOOSRInfoes->find('all')->where(['osr_purchase_order_no'=>$postData['osr_purchase_order_no']])->select($this->CustomerAircraftWOOSRInfoes)->count();
        }
        
        if(empty($woosrinfocount)){
            $woosrinfodet = $this->CustomerAircraftWOOSRInfoes->find('all', array('order' => 'osr_purchase_order_no ASC'))->where(['is_add_to_po'=>'1'])->select(['osr_purchase_order_no'])->all()->last();
            
            $currentyear = date('y');
            if(!empty($woosrinfodet)){
                $otcinvoicearr = explode('-', $woosrinfodet->osr_purchase_order_no);
                if($otcinvoicearr[0] == $currentyear){
                    $aircraftworkorderid = $otcinvoicearr[1]+1;
                    $remaingtoaddzero = 4-strlen($aircraftworkorderid);
                    $osr_purchase_order_no = $otcinvoicearr[0].'-';
                    for($i=1; $i<=$remaingtoaddzero; $i++){
                        $osr_purchase_order_no .= '0';
                    }
                    $osr_purchase_order_no .= $aircraftworkorderid;
                }else{
                    $osr_purchase_order_no = $currentyear.'-0001';
                }
            }else{
                $osr_purchase_order_no = $currentyear.'-0001';
            }
            
            $message = '';
            $status = 'success';
        }else{
            $message = 'A Purchase Order number already exists for this item and cannot be added.

Remove the Purchase Order Number information on this screen and try again.';
            $osr_purchase_order_no = '';
            $status = 'failure';
        }

        return $resp = ['status'=>$status, 'message'=>$message, 'osr_purchase_order_no'=>$osr_purchase_order_no];
    }

    public function getAircraftWOOSRRONumber($postData){
        $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
        $woosrinfocount = 0;
        if(!empty($postData['osr_invoice_no'])){
            $woosrinfocount = $this->CustomerAircraftWOOSRInfoes->find('all')->where(['osr_invoice_no'=>$postData['osr_invoice_no']])->select($this->CustomerAircraftWOOSRInfoes)->count();
        }
        if(empty($woosrinfocount)){
            $osr_invoice_no = $this->getAircraftWorkOrderNumber();
            
            $message = '';
            $status = 'success';
        }else{
            $message = 'A repair order for this OSR item cannot be created because an invoice number already exists.';
            $osr_invoice_no = '';
            $status = 'failure';
        }

        return $resp = ['status'=>$status, 'message'=>$message, 'osr_invoice_no'=>$osr_invoice_no];
    }

    public function getWOOSRVendorList(){
        $this->CustomerAircraftWOOSRVendors = $this->getController()->fetchTable('CustomerAircraftWOOSRVendors');

        $woosrvendors = $this->CustomerAircraftWOOSRVendors->find('all')->where(['status'=>'1'])->select(['id', 'vendor_name']);
        $osrvendorarr = [];
        foreach($woosrvendors as $woosrvendor){
            $osrvendorarr[$woosrvendor['id']] = $woosrvendor['vendor_name'];
        }

        return $osrvendorarr;
    }

    public function getWOOSRVendorAttachment($osr_vendor_id){
        $connection = ConnectionManager::get('default');
        
        $osrVendorInfoMedia = $connection->execute(
            "SELECT ca.*, u.full_name FROM customer_aircraft_wo_osr_vendor_attachments ca join users u on ca.uploaded_by = u.id WHERE ca.status = '1' and ca.osr_vendor_id = :osr_vendor_id",
            ['osr_vendor_id' => $osr_vendor_id])->fetchAll('assoc');

        return $osrVendorInfoMedia;
    }

    public function getWOOSRVendorDataAndPhones(){
        $this->CustomerAircraftWOOSRVendors = $this->getController()->fetchTable('CustomerAircraftWOOSRVendors');

        $woosrvendorlist = $this->CustomerAircraftWOOSRVendors->find('all')->where(['status'=>'1'])->select($this->CustomerAircraftWOOSRVendors);
        $woosrvendordata = [];
        $woosrvendorphones = [];
        
        foreach($woosrvendorlist as $vendors){
            $woosrvendordata[$vendors['id']] = $vendors['vendor_name'];
            if(!empty($woosrinfopo['vendor_id']) && $vendors['id'] == $woosrinfopo['vendor_id']){
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
        }

        $returnArr = ['woosrvendordata'=>$woosrvendordata, 'woosrvendorphones'=>$woosrvendorphones];

        return $returnArr;
    }

    public function getAllServiceItemOnPO($osr_po_id){
        $this->CustomerAircraftWOOSRPOItems = $this->getController()->fetchTable('CustomerAircraftWOOSRPOItems');

        $allserviceitemspo = $this->CustomerAircraftWOOSRPOItems->find('all')
                                                                ->where(['osr_po_id'=>$osr_po_id, 'mark_arrived'=>'0'])
                                                                ->select($this->CustomerAircraftWOOSRPOItems)
                                                                ->select(['work_order.work_order_no', 'work_order.order_type', 'wo_item.wo_item_position'])
                                                                ->join([
                                                                    'work_order' => [
                                                                        'table' => 'customer_aircraft_work_orders',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'work_order.id = CustomerAircraftWOOSRPOItems.destination',
                                                                    ]
                                                                ])
                                                                ->join([
                                                                    'wo_osr_info_poes' => [
                                                                        'table' => 'customer_aircraft_wo_osr_info_poes',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'wo_osr_info_poes.id = CustomerAircraftWOOSRPOItems.osr_po_id',
                                                                    ]
                                                                ])
                                                                ->join([
                                                                    'wo_osr_infoes' => [
                                                                        'table' => 'customer_aircraft_wo_osr_infoes',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'wo_osr_infoes.osr_purchase_order_no = wo_osr_info_poes.po_no',
                                                                    ]
                                                                ])
                                                                ->join([
                                                                    'wo_item' => [
                                                                        'table' => 'customer_aircraft_wo_items',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'wo_item.id = wo_osr_infoes.wo_item_id',
                                                                    ]
                                                                ]);

        return $allserviceitemspo;
    }

    public function getWOOSRPurcahseOrderAttachment($osr_info_po_id){
        $connection = ConnectionManager::get('default');
        
        $osrVendorInfoMedia = $connection->execute(
            "SELECT ca.*, u.full_name FROM customer_aircraft_wo_osr_info_po_attachments ca join users u on ca.uploaded_by = u.id WHERE ca.status = '1' and ca.osr_info_po_id = :osr_info_po_id",
            ['osr_info_po_id' => $osr_info_po_id])->fetchAll('assoc');

        return $osrVendorInfoMedia;
    }

    public function getAircraftWODropDown(){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderlists = $this->CustomerAircraftWorkOrders->find('all')->where(['wo_status'=>'1'])->select(['id', 'work_order_no']);
        $workorderarr = [];
        if($workorderlists->count() > 0){
            foreach($workorderlists as $workorder){
                $workorderarr[$workorder['id']] = $workorder['work_order_no'];
            }
        }
        
        return $workorderarr;
    }

    public function getAllOSRByWOId($work_order_id){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');

        $aircraftwoitemosrinfoes = $this->CustomerAircraftWorkOrders->find('all')
                                    ->where(['CustomerAircraftWorkOrders.id'=>$work_order_id])
                                    ->select(['vendors.vendor_name', 'osr_infoes.osr_invoice_no', 'osr_infoes.osr_purchase_order_no', 'osr_infoes.id', 'osr_infoes.osr_part_number', 'osr_infoes.osr_labor_charge', 'osr_infoes.osr_parts_charge', 'wo_items.item_no'])
                                    ->join([
                                        'wo_items' => [
                                            'table' => 'customer_aircraft_wo_items',
                                            'type' => 'INNER',
                                            'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                        ]
                                    ])
                                    ->join([
                                        'osr_infoes' => [
                                            'table' => 'customer_aircraft_wo_osr_infoes',
                                            'type' => 'INNER',
                                            'conditions' => 'osr_infoes.wo_item_id = wo_items.id',
                                        ]
                                    ])
                                    ->join([
                                        'vendors' => [
                                            'table' => 'customer_aircraft_wo_osr_vendors',
                                            'type' => 'INNER',
                                            'conditions' => 'vendors.id = osr_infoes.osr_repair_done_by',
                                        ]
                                    ])
                                    ->order(['osr_infoes.id'=>'DESC']);

        return $aircraftwoitemosrinfoes;
    }

    public function getWOItemListByWOId($work_order_id){
        $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');

        $aircraftwoitemosrinfoes = $this->CustomerAircraftWOItems->find('all')
                                    ->where(['CustomerAircraftWOItems.work_order_id'=>$work_order_id])
                                    ->select(['CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.wo_item_status', 'wo_item_overview.wo_category', 'wo_item_services.is_lead_tech_on_item'])
                                    ->join([
                                        'wo_item_overview' => [
                                            'table' => 'customer_aircraft_wo_item_overviews',
                                            'type' => 'LEFT',
                                            'conditions' => 'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id',
                                        ]
                                    ])
                                    ->join([
                                        'wo_item_services' => [
                                            'table' => 'customer_aircraft_wo_item_services',
                                            'type' => 'LEFT',
                                            'conditions' => 'wo_item_services.wo_item_id = CustomerAircraftWOItems.id',
                                        ]
                                    ])
                                    ->order(['CustomerAircraftWOItems.wo_item_position'=>'ASC']);

        return $aircraftwoitemosrinfoes;
    }
    
    public function getAircraftWOLogBookValueTabData($postData){

        $engine_type = $postData['engine_type'];
        if($engine_type != '5'){
            $this->CustomerAircraftWOLogBookValueOverviews = $this->getController()->fetchTable('CustomerAircraftWOLogBookValueOverviews');

            $aircraftmaintoverview = $this->CustomerAircraftWOLogBookValueOverviews->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueOverviews)->first();
        }else{
            $this->AircraftWOLogBookValueHelicopterOverviews = $this->getController()->fetchTable('AircraftWOLogBookValueHelicopterOverviews');

            $aircraftmaintoverview = $this->AircraftWOLogBookValueHelicopterOverviews->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->AircraftWOLogBookValueHelicopterOverviews)->first();
        }

        $aircraftmaintengine = '';
        if($engine_type != '5'){
            if($engine_type == '1' || $engine_type == '2'){
                $this->CustomerAircraftWOLogBookValueEngines = $this->getController()->fetchTable('CustomerAircraftWOLogBookValueEngines');
                $aircraftmaintengine = $this->CustomerAircraftWOLogBookValueEngines->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueEngines)->first(); 
            }else{
                $this->AircraftWOLogBookValueJetEngines = $this->getController()->fetchTable('AircraftWOLogBookValueJetEngines');
                $aircraftmaintengine = $this->AircraftWOLogBookValueJetEngines->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->AircraftWOLogBookValueJetEngines)->first(); 
            }
        }
        
        $this->CustomerAircraftWOLogBookValueProps = $this->getController()->fetchTable('CustomerAircraftWOLogBookValueProps');
        $aircraftmaintprops = $this->CustomerAircraftWOLogBookValueProps->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOLogBookValueProps)->first(); 
        
        $returnArr = ['aircraftmaintoverview'=>$aircraftmaintoverview, 'aircraftmaintengine'=>$aircraftmaintengine, 'aircraftmaintprops'=>$aircraftmaintprops];
        
        return $returnArr;
    }

    public function getAircraftWOViewOptionData($postData){
        $this->CustomerAircraftWOOptionGeneralInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionGeneralInfoes');
        $this->CustomerAircraftWOOptionGenInfoDeposits = $this->getController()->fetchTable('CustomerAircraftWOOptionGenInfoDeposits');
        $this->CustomerAircraftWOOptionMiscCharges = $this->getController()->fetchTable('CustomerAircraftWOOptionMiscCharges');
        $this->CustomerAircraftWOOptionPricingInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionPricingInfoes');
        $this->CustomerAircraftWOOptionWarrantyInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionWarrantyInfoes');
        $this->CustomerAircraftWOOptionTaxInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionTaxInfoes');
        $this->CustomerAircraftWOOptionBillingInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionBillingInfoes');

        $wooptiongeninfoes = $this->CustomerAircraftWOOptionGeneralInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionGeneralInfoes)->first();
        if(!empty($wooptiongeninfoes)){
            $wooptiongeninfodeposit = $this->CustomerAircraftWOOptionGenInfoDeposits->find('all')->where(['general_info_id'=>$wooptiongeninfoes->id]);
            $wooptiongeninfodeposit = $wooptiongeninfodeposit->select(['totalamounttoadd' => $wooptiongeninfodeposit->func()->sum('amount_to_add') ])->first();
            $wooptiongeninfoes->total_deposit_amount = (!empty($wooptiongeninfodeposit->totalamounttoadd) && (float)$wooptiongeninfodeposit->totalamounttoadd > 0)
                ? number_format((float)$wooptiongeninfodeposit->totalamounttoadd, 2)
                : '';

        }

        $wooptionmiscchargs = $this->CustomerAircraftWOOptionMiscCharges->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionMiscCharges)->first();
        
        $wooptionmiscfuelchargs = [];
        if(!empty($wooptionmiscchargs)){
            $wooptionmiscfuelchargs = $this->getAircraftWOViewOptionMiscFuelCharges($wooptionmiscchargs['id']);
        }

        $wooptionpricinginfoes = $this->CustomerAircraftWOOptionPricingInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionPricingInfoes)->first();

        $wooptionwarrantyinfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionWarrantyInfoes)->first();

        $wooptiontaxinfoes = $this->CustomerAircraftWOOptionTaxInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionTaxInfoes)->first();

        $wooptionbillinginfoes = $this->CustomerAircraftWOOptionBillingInfoes->find('all')->where(['work_order_id'=>$postData['work_order_id']])->select($this->CustomerAircraftWOOptionBillingInfoes)->first();
        
        $returnArr = [
                        'wooptiongeninfoes'=>$wooptiongeninfoes, 
                        'wooptionmiscchargs'=>$wooptionmiscchargs,
                        'wooptionmiscfuelchargs'=>$wooptionmiscfuelchargs,
                        'wooptionpricinginfoes'=>$wooptionpricinginfoes,
                        'wooptionwarrantyinfoes'=>$wooptionwarrantyinfoes,
                        'wooptiontaxinfoes'=>$wooptiontaxinfoes,
                        'wooptionbillinginfoes'=>$wooptionbillinginfoes
                    ];
        
        return $returnArr;
    }

    public function getAircraftWOViewOptionGenInfoDeposits($general_info_id){
        $this->CustomerAircraftWOOptionGenInfoDeposits = $this->getController()->fetchTable('CustomerAircraftWOOptionGenInfoDeposits');
        $wooptiongeninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->find('all')
                                    ->where(['general_info_id'=>$general_info_id])
                                    ->select($this->CustomerAircraftWOOptionGenInfoDeposits)
                                    ->select(['customers.customer_name'])
                                    ->join([
                                        'customers' => [
                                            'table' => 'inventory_customers',
                                            'type' => 'LEFT',
                                            'conditions' => 'customers.id = CustomerAircraftWOOptionGenInfoDeposits.paid_by',
                                        ]
                                    ]);

        return $wooptiongeninfodeposits;
    }

    public function getSentMessages($work_order_id = '', $message_id = '')
    {
        $connection = ConnectionManager::get('default');
        $authUserData = $this->Authentication->getResult()->getData();
        $user_id = $authUserData['id'];

        $currentdate = date('Y-m-d');
        $lastmonthdate = date('Y-m-d', strtotime('-1 month'));

        $wherecond = ["DATE(msg.created_at) BETWEEN :lastmonthdate AND :currentdate"];
        $whereArr = [
            'lastmonthdate' => $lastmonthdate,
            'currentdate' => $currentdate
        ];

        if (!empty($work_order_id)) {
            $wherecond[] = "msg.work_order_id = :work_order_id";
            $whereArr['work_order_id'] = $work_order_id;
        }

        if ($user_id != '1') {
            $wherecond[] = "msg.added_by = :added_by";
            $whereArr['added_by'] = $user_id;
        }

        if (!empty($message_id)) {
            $wherecond[] = "msg.id = :message_id";
            $whereArr['message_id'] = $message_id;
        }

        $whereClause = implode(' AND ', $wherecond);

        $sql = "
            SELECT 
                msg.id,
                user.full_name AS sent_from,
                user_to.full_name AS sent_to,
                msg.message_subject,
                msg.message,
                msg.created_at,
                msg.is_mark_read,
                msg.message_wo_ro,
                work_order.work_order_no,
                wo_items.wo_item_position
            FROM customer_aircraft_wo_messages msg
            LEFT JOIN customer_aircraft_work_orders work_order 
                ON msg.work_order_id = work_order.id
            LEFT JOIN customer_aircraft_wo_items wo_items 
                ON wo_items.id = msg.wo_item_id
            JOIN users user_to 
                ON user_to.id = msg.message_to
            JOIN users user 
                ON user.id = msg.added_by
            WHERE $whereClause
            ORDER BY msg.id DESC
        ";

        $sentmsglist = $connection->execute($sql, $whereArr)->fetchAll('assoc');
        return $sentmsglist;
    }

    public function getReceivedMessages($work_order_id = '', $message_id = '')
    {
        $connection = ConnectionManager::get('default');
        $authUserData = $this->Authentication->getResult()->getData();
        $user_id = $authUserData['id'];

        $currentdate = date('Y-m-d');
        $lastmonthdate = date('Y-m-d', strtotime('-1 month'));

        $wherecond = ["DATE(msg.created_at) BETWEEN :lastmonthdate AND :currentdate"];
        $whereArr = [
            'lastmonthdate' => $lastmonthdate,
            'currentdate' => $currentdate
        ];

        if (!empty($work_order_id)) {
            $wherecond[] = "msg.work_order_id = :work_order_id";
            $whereArr['work_order_id'] = $work_order_id;
        }

        if ($user_id != '1') {
            $wherecond[] = "msg.message_to = :message_to";
            $whereArr['message_to'] = $user_id;
        }

        if (!empty($message_id)) {
            $wherecond[] = "msg.id = :message_id";
            $whereArr['message_id'] = $message_id;
        }

        $whereClause = implode(' AND ', $wherecond);

        $sql = "
            SELECT 
                msg.id,
                user.full_name AS sent_from,
                user_to.full_name AS sent_to,
                msg.message_subject,
                msg.message,
                msg.created_at,
                msg.is_mark_read,
                msg.message_wo_ro,
                work_order.work_order_no,
                wo_items.wo_item_position
            FROM customer_aircraft_wo_messages msg
            LEFT JOIN customer_aircraft_work_orders work_order 
                ON msg.work_order_id = work_order.id
            LEFT JOIN customer_aircraft_wo_items wo_items 
                ON wo_items.id = msg.wo_item_id
            JOIN users user_to 
                ON user_to.id = msg.message_to
            JOIN users user 
                ON user.id = msg.added_by
            WHERE $whereClause
            ORDER BY msg.id DESC
        ";

        $receivedmsglist = $connection->execute($sql, $whereArr)->fetchAll('assoc');
        return $receivedmsglist;
    }

    public function getAircraftWOMsgList($work_order_id='', $message_id=''){
        $connection = ConnectionManager::get('default');
        $authUserData = $this->Authentication->getResult()->getData();
        $user_id = $authUserData['id'];
        
        $currentdate = date('Y-m-d');
        $lastsevendate = date('Y-m-d', strtotime('-7 days'));

        /*$wherecond = "DATE(msg.created_at) >= :lastsevendate and DATE(msg.created_at) <= :currentdate";
        $whereArr = ['lastsevendate'=>$lastsevendate, 'currentdate'=>$currentdate];*/

        $wherecond = " 1=1";
        $whereArr = [];
        if(!empty($work_order_id)){
            $wherecond .= " and msg.work_order_id = :work_order_id";
            $whereArr['work_order_id'] = $work_order_id;
        }
        
        if(!empty($message_id)){
            $wherecond .= " and msg.id = :message_id";
            $whereArr['message_id'] = $message_id;
        }
        $wherecond .= " order by msg.id desc";
        
        $receivedmsglist = $connection->execute(
            "SELECT msg.*, user_to.full_name as sent_to, user.full_name as sent_from, wo_items.wo_item_position, work_order.work_order_no from customer_aircraft_wo_messages msg left join customer_aircraft_work_orders work_order on msg.work_order_id = work_order.id left join customer_aircraft_wo_items wo_items on wo_items.id = msg.wo_item_id join users user_to on user_to.id = msg.message_to join users user on user.id = msg.added_by where $wherecond",
            $whereArr)->fetchAll('assoc');

        if(!empty($message_id)){
            $receivedmsglist = $receivedmsglist[0];
            if(!empty($receivedmsglist['message_wo_ro'])){
                $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');

                $aircraftwoitems = $this->CustomerAircraftWOItems->find('all')
                                            ->where(['CustomerAircraftWOItems.id'=>$receivedmsglist['message_wo_ro']])
                                            ->select(['CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_position', 'work_order.work_order_no'])
                                            ->join([
                                                'work_order' => [
                                                    'table' => 'customer_aircraft_work_orders',
                                                    'type' => 'INNER',
                                                    'conditions' => 'work_order.id = CustomerAircraftWOItems.work_order_id',
                                                ]
                                            ])
                                            ->first();
                                                    
                $receivedmsglist['msg_woro_no'] = $aircraftwoitems['work_order']['work_order_no'].'-'.$aircraftwoitems['wo_item_position'];
            }
        }
        return $receivedmsglist;
    }

    public function getListOfWorkOrders($filter=[]){
        $whereArr = ['CustomerAircraftWorkOrders.wo_status != '=> '0'];
        if(isset($filter['wo_status'])){
            $whereArr = ['CustomerAircraftWorkOrders.wo_status'=> $filter['wo_status']];
        }
        if(isset($filter['wo_category'])){
            $whereArr['item_overviews.wo_category'] = $filter['wo_category'];
        }
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';

        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $aircrafOpenWorkOrders = $this->CustomerAircraftWorkOrders->find('all', array('order'=>'CustomerAircraftWorkOrders.id DESC'));
        $aircrafOpenWorkOrders = $aircrafOpenWorkOrders->where($whereArr)
                                                        ->select($this->CustomerAircraftWorkOrders)
                                                        ->select(['otc_aircrafts.aircraft_registration_number', 'aircraft_make.make', 'customers.customer_name', 'wo_items.wo_item_position', 'itemcount' => $aircrafOpenWorkOrders->func()->count('wo_items.work_order_id')])
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
                                                            'aircraft_make' => [
                                                                'table' => 'customer_otc_aircraft_make',
                                                                'type' => 'LEFT',
                                                                'conditions' => 'aircraft_make.id = otc_aircrafts.aircraft_make_id',
                                                            ]
                                                        ])
                                                        ->join([
                                                            'customers' => [
                                                                'table' => 'inventory_customers',
                                                                'type' => 'INNER',
                                                                'conditions' => 'customers.id = otc_aircrafts.customer_id',
                                                            ]
                                                        ])
                                                        ->group('wo_items.work_order_id');
        if(isset($filter['wo_category'])){
            $aircrafOpenWorkOrders = $aircrafOpenWorkOrders->join([
                                                                    'item_overviews' => [
                                                                        'table' => 'customer_aircraft_wo_item_overviews',
                                                                        'type' => 'INNER',
                                                                        'conditions' => 'item_overviews.wo_item_id = wo_items.id',
                                                                    ]
                                                                ]);
        }

        return $aircrafOpenWorkOrders;
    }

    public function getListOfWarrantyClaimsWO($filter=[]){
        $whereArr = ['CustomerAircraftWorkOrders.wo_status != '=> '0'];
        if(isset($filter['wo_status'])){
            $whereArr = ['CustomerAircraftWorkOrders.wo_status'=> $filter['wo_status']];
        }
        $whereArr['item_overviews.warranty_claim_no != '] = '';
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';

        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $aircrafWarrantyClaimsWO = $this->CustomerAircraftWorkOrders->find('all', array('order'=>'CustomerAircraftWorkOrders.id DESC'));
        $aircrafWarrantyClaimsWO =  $aircrafWarrantyClaimsWO->where($whereArr)
                                                        ->select($this->CustomerAircraftWorkOrders)
                                                        ->select(['otc_aircrafts.aircraft_registration_number', 'wo_items.wo_item_position', 'wo_items.wo_discrepancy', 'item_overviews.warranty_claim_no', 'itemcount' => $aircrafWarrantyClaimsWO->func()->count('wo_items.work_order_id')])
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
                                                        ->group('wo_items.work_order_id');

        return $aircrafWarrantyClaimsWO;
    }

    public function getAircraftRegistrationNumberList(){
        $this->CustomerOTCAircrafts = $this->getController()->fetchTable('CustomerOTCAircrafts');
        $aircraftregdet = $this->CustomerOTCAircrafts->find('all')->where(['status'=>'1'])->select(['CustomerOTCAircrafts.id', 'CustomerOTCAircrafts.aircraft_registration_number']);
        $aircraftregnumList = [];
        foreach($aircraftregdet as $aircraft){
            $aircraftregnumList[$aircraft['id']] = $aircraft['aircraft_registration_number'];
        }
        
        return $aircraftregnumList;
    }

    public function getAircraftWOItemParts($wo_item_id){
        $this->CustomerAircraftWOItemParts = $this->getController()->fetchTable('CustomerAircraftWOItemParts');
        $this->InventoryRequestItems = $this->getController()->fetchTable('InventoryRequestItems');
        $this->InventoryItems = $this->getController()->fetchTable('InventoryItems');

        $woitempartlists = $this->CustomerAircraftWOItemParts->find('all')
                                    ->where(['CustomerAircraftWOItemParts.wo_item_id'=>$wo_item_id])
                                    ->select($this->CustomerAircraftWOItemParts)
                                    ->select(['invrequest.id', 'invrequest.title', 'invrequest.request_status', 'invrequest.request_number'])
                                    ->join([
                                        'invrequest'=>[
                                            'table'=>'inventory_requests',
                                            'type'=>'LEFT',
                                            'conditions'=>'invrequest.wo_item_part_id=CustomerAircraftWOItemParts.id'
                                        ]
                                    ])
                                    ->order(['CustomerAircraftWOItemParts.id'=>'DESC'])
                                    ->toArray();
                                    
        //print_r($woitempartlists);exit;
        return $woitempartlists;
    }

    public function getAircraftWOAllPartsList($work_order_id){
        $this->CustomerAircraftWOItemParts = $this->getController()->fetchTable('CustomerAircraftWOItemParts');
        $woitempartlists = $this->CustomerAircraftWOItemParts->find('all')
                                    ->where(['wo_items.work_order_id'=>$work_order_id])
                                    ->select($this->CustomerAircraftWOItemParts)
                                    ->select(['wo_items.wo_item_position'])
                                    ->join([
                                        'wo_items' => [
                                            'table' => 'customer_aircraft_wo_items',
                                            'type' => 'INNER',
                                            'conditions' => 'wo_items.id = CustomerAircraftWOItemParts.wo_item_id',
                                        ]
                                    ])
                                    ->order(['CustomerAircraftWOItemParts.id'=>'DESC']);

        return $woitempartlists;
    }

    public function getWOItemPartNumberList(){
        /*$connection = ConnectionManager::get('default');

        $inventoryitemsdata = $connection
                            ->execute(
                                "SELECT id, part_number FROM `inventory_items` WHERE is_this_item_serialized = '0' and status = '1' UNION select invpt.id, invpt.part_number from inventory_items invpt join inventories inv on invpt.id = inv.inventory_item_id where invpt.status = '1' and inv.status = '1'"
                            )
                            ->fetchAll('assoc');*/
        $this->InventoryItems = $this->getController()->fetchTable('InventoryItems');
        $inventoryitemsdata = $this->InventoryItems->find('all')
                                                    ->where(['InventoryItems.status'=>'1', 'inventory.status'=>'1'])
                                                    ->join([
                                                        'inventory'=>[
                                                            'table'=>'inventories', 
                                                            'type'=>'INNER',
                                                            'conditions'=>'inventory.inventory_item_id = InventoryItems.id'
                                                        ]
                                                    ])
                                                    ->select(['id', 'part_number']);
        $invpartnumbers = [];
        foreach($inventoryitemsdata as $invitems){
            $invpartnumbers[$invitems['part_number']] = $invitems['part_number'];
        }

        return $invpartnumbers;
    }

    public function getWOPartsVendorList(){
        $this->CustomerAircraftWOOSRVendors = $this->getController()->fetchTable('CustomerAircraftWOOSRVendors');

        $woosrvendors = $this->CustomerAircraftWOOSRVendors->find('all')->where(['status'=>'1'])->select(['id', 'vendor_name']);
        $osrvendorarr = [];
        foreach($woosrvendors as $woosrvendor){
            $osrvendorarr[$woosrvendor['vendor_name']] = $woosrvendor['vendor_name'];
        }

        return $osrvendorarr;
    }

    public function getAircraftWOAllPartsById($postData){
        $this->CustomerAircraftWOItemParts = $this->getController()->fetchTable('CustomerAircraftWOItemParts');
        
        $orderby = array('order'=>'CustomerAircraftWOItemParts.id DESC');
        $whereArr = [];
        if(empty($postData['clickbtn'])){
            $whereArr = ['CustomerAircraftWOItemParts.id'=>$postData['wo_item_part_id']];
        }else{
            if($postData['clickbtn'] == 'first'){
                $whereArr['CustomerAircraftWOItemParts.wo_item_id'] = $postData['wo_item_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemParts.id ASC');                
            }
            if($postData['clickbtn'] == 'last'){
                $whereArr['CustomerAircraftWOItemParts.wo_item_id'] = $postData['wo_item_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemParts.id DESC');                
            }
            if($postData['clickbtn'] == 'prev'){
                $whereArr['CustomerAircraftWOItemParts.wo_item_id'] = $postData['wo_item_id'];
                $whereArr['CustomerAircraftWOItemParts.id <'] = $postData['wo_item_part_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemParts.id DESC');                
            }
            if($postData['clickbtn'] == 'next'){
                $whereArr['CustomerAircraftWOItemParts.wo_item_id'] = $postData['wo_item_id'];
                $whereArr['CustomerAircraftWOItemParts.id >'] = $postData['wo_item_part_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemParts.id ASC');                
            }
        }
        
        $woitempartlists = $this->CustomerAircraftWOItemParts->find('all', $orderby)
                                    ->where($whereArr)
                                    ->select($this->CustomerAircraftWOItemParts)
                                    ->select(['wo_items.wo_item_position', 'wo_items.wo_discrepancy', 'wo_items.wo_item_status', 'work_order.work_order_no'])
                                    ->join([
                                        'wo_items' => [
                                            'table' => 'customer_aircraft_wo_items',
                                            'type' => 'INNER',
                                            'conditions' => 'wo_items.id = CustomerAircraftWOItemParts.wo_item_id',
                                        ]
                                    ])
                                    ->join([
                                        'work_order' => [
                                            'table' => 'customer_aircraft_work_orders',
                                            'type' => 'INNER',
                                            'conditions' => 'work_order.id = wo_items.work_order_id',
                                        ]
                                    ])
                                    ->first();

        return $woitempartlists;
    }

    public function getWOROFilterExportDropDownData(){
        $this->CustomerOTCAircrafts = $this->getController()->fetchTable('CustomerOTCAircrafts');
        $this->InventoryCustomers = $this->getController()->fetchTable('InventoryCustomers');

        $inventorycustomers = $this->getInventoryCustomerDropdownData();
        $aircraftregnumbers = $this->getAircraftRegistrationNumberList();
        $aircraftmodels = $this->getAircraftModelList();
        $aircraftmakes = $this->getAircraftMakeList();
        $atacodes = $this->getAircraftWOATACodeDropdownList();
        $laborkits = $this->getAircraftWOLaborKitDropdownList();
        $userlist = $this->getUserDropdownList();
        $osrvendorlist = $this->getWOOSRVendorList();
        $supplierlist = $this->getPartSupplierDropdownList();
        
        $returnArr = [
                    'inventorycustomers'=>$inventorycustomers,
                    'aircraftregnumbers'=>$aircraftregnumbers,
                    'aircraftmodels'=>$aircraftmodels,
                    'aircraftmakes'=>$aircraftmakes,
                    'atacodes'=>$atacodes,
                    'laborkits'=>$laborkits,
                    'userlist'=>$userlist,
                    'osrvendorlist'=>$osrvendorlist,
                    'supplierlist'=>$supplierlist
                ];
        
        return $returnArr;
    }

    public function getInventoryCustomerDropdownData(){
        $this->InventoryCustomers = $this->getController()->fetchTable('InventoryCustomers');

        $customerlists = $this->InventoryCustomers->find('all', array('order'=>'customer_name ASC'))->where(['status'=>'1'])->select(['id', 'customer_name']);
        $inventorycustomers = [];
        foreach($customerlists as $customer){
            $inventorycustomers[$customer['id']] = $customer['customer_name'];
        }

        return $inventorycustomers;
    }

    public function getAircraftMakeList(){
        $conn = ConnectionManager::get('default');
        $query = "select id, make from customer_otc_aircraft_make where status = '1' order by make";
        $results = $conn->execute($query)->fetchAll('assoc');
        $aircraftmakes = [];
        foreach($results as $row){
            $aircraftmakes[$row['id']] = $row['make'];
        }

        return $aircraftmakes;
    }

    public function getAircraftWOATACodeDropdownList(){
        $this->CustomerAircraftWOATACodes = $this->getController()->fetchTable('CustomerAircraftWOATACodes');

        $results = $this->CustomerAircraftWOATACodes->find('all')->where(['status'=>'1'])->select(['id', 'ata_code']);

        $atacodes = [];
        foreach($results as $row){
            $atacodes[$row['id']] = $row['ata_code'];
        }

        return $atacodes;
    }

    public function getAircraftWOLaborKitDropdownList(){
        $this->CustomerAircraftWOLaborKits = $this->getController()->fetchTable('CustomerAircraftWOLaborKits');

        $results = $this->CustomerAircraftWOLaborKits->find('all')->where(['status'=>'1'])->select(['id', 'labor_kit']);

        $laborkits = [];
        foreach($results as $row){
            $laborkits[$row['id']] = $row['labor_kit'];
        }

        return $laborkits;
    }

    public function getUserDropdownList(){
        $this->Users = $this->getController()->fetchTable('Users');
        $userdet = $this->Users->find('all')->where(['suspended'=>'0'])->select(['Users.id', 'Users.full_name']);
        
        $userlist = [];
        foreach($userdet as $users){
            if($users['id'] == '1'){
                continue;
            }else{
                $userlist[$users['id']] = $users['full_name'];
            }
        }

        return $userlist;
    }

    public function getPartSupplierDropdownList(){
        $this->InventoryVendors = $this->getController()->fetchTable('InventoryVendors');
        $supplierdet = $this->InventoryVendors->find('all')->where(['status'=>'1'])->select(['id', 'name']);
        
        $supplierlist = [];
        foreach($supplierdet as $row){
            $supplierlist[$row['id']] = $row['name'];
        }

        return $supplierlist;
    }

    public function getAllItemLocationByInvId($locationidarr){
        $location = array();
        if(!empty($locationidarr)){
            $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
            $locationarr = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1', 'InventoryLocations.id IN'=>$locationidarr]);
            
            if(!empty($locationarr)){
                foreach($locationarr as $val){
                    $location[$val['location_name']] = $val['location_name'];
                }
            }
        }

        return $location;
    }

    public function getAllItemSupplierByInvId($vendoridarr){
        $supplierlist = [];
        if(!empty($vendoridarr)){
            $this->InventoryVendors = $this->getController()->fetchTable('InventoryVendors');
            $supplierdet = $this->InventoryVendors->find('all')->where(['status'=>'1', 'id IN'=>$vendoridarr])->select(['id', 'name']);
            
            foreach($supplierdet as $row){
                $supplierlist[$row['name']] = $row['name'];
            }
        }
        return $supplierlist;
    }

    public function woRoExportExcelFilterData($postData){
        $whereArr = [];
        if(!empty($postData['wo_ro_customer_name'])){
            $whereArr[''] = $postData['wo_ro_customer_name'];
        }
        if(!empty($postData['wo_ro_registration_number'])){
            $whereArr[''] = $postData['wo_ro_registration_number'];
        }
        if(!empty($postData['wo_ro_make'])){
            $whereArr[''] = $postData['wo_ro_make'];
        }
        if(!empty($postData['wo_ro_model'])){
            $whereArr[''] = $postData['wo_ro_model'];
        }
        if(!empty($postData['wo_ro_ac_serial'])){
            $whereArr[''] = $postData['wo_ro_ac_serial'];
        }
        if(!empty($postData['wo_ro'])){
            $whereArr[''] = $postData['wo_ro'];
        }
        if(!empty($postData['wo_ro_status'])){
            $whereArr[''] = $postData['wo_ro_status'];
        }
        if(!empty($postData['wo_ro_status_type'])){
            $whereArr[''] = $postData['wo_ro_status_type'];
        }
        if(!empty($postData['wo_ro_lead_tech'])){
            $whereArr[''] = $postData['wo_ro_lead_tech'];
        }
        if(!empty($postData['wo_ro_sales_person'])){
            $whereArr[''] = $postData['wo_ro_sales_person'];
        }
        if(!empty($postData['wo_ro_misc_charges_notes'])){
            $whereArr[''] = $postData['wo_ro_misc_charges_notes'];
        }
        if(!empty($postData['wo_ro_created_from'])){
            $whereArr[''] = $postData['wo_ro_created_from'];
        }
        if(!empty($postData['wo_ro_created_to'])){
            $whereArr[''] = $postData['wo_ro_created_to'];
        }
        if(!empty($postData['wo_ro_completed_from'])){
            $whereArr[''] = $postData['wo_ro_completed_from'];
        }
        if(!empty($postData['wo_ro_completed_to'])){
            $whereArr[''] = $postData['wo_ro_completed_to'];
        }
        if(!empty($postData['wo_ro_ata_code'])){
            $whereArr[''] = $postData['wo_ro_ata_code'];
        }
        if(!empty($postData['wo_ro_category'])){
            $whereArr[''] = $postData['wo_ro_category'];
        }
        if(!empty($postData['wo_ro_department'])){
            $whereArr[''] = $postData['wo_ro_department'];
        }
        if(!empty($postData['wo_ro_grouping'])){
            $whereArr[''] = $postData['wo_ro_grouping'];
        }
        if(!empty($postData['wo_ro_labor_kit'])){
            $whereArr[''] = $postData['wo_ro_labor_kit'];
        }
        if(!empty($postData['wo_ro_warranty'])){
            $whereArr[''] = $postData['wo_ro_warranty'];
        }
        if(!empty($postData['wo_ro_warranty_claim_no'])){
            $whereArr[''] = $postData['wo_ro_warranty_claim_no'];
        }
        if(!empty($postData['wo_ro_discrepancy'])){
            $whereArr[''] = $postData['wo_ro_discrepancy'];
        }
        if(!empty($postData['wo_ro_corrective_action'])){
            $whereArr[''] = $postData['wo_ro_corrective_action'];
        }
        if(!empty($postData['wo_ro_item_notes'])){
            $whereArr[''] = $postData['wo_ro_item_notes'];
        }
        if(!empty($postData['wo_ro_item_status'])){
            $whereArr[''] = $postData['wo_ro_item_status'];
        }
        if(!empty($postData['wo_ro_include_signoffs'])){
            if(!empty($postData['wo_ro_signoff'])){
                $whereArr[''] = $postData['wo_ro_signoff'];
            }
            if(!empty($postData['wo_ro_signoff_by'])){
                $whereArr[''] = $postData['wo_ro_signoff_by'];
            }
            if(!empty($postData['wo_ro_signoff_from'])){
                $whereArr[''] = $postData['wo_ro_signoff_from'];
            }
            if(!empty($postData['wo_ro_signoff_to'])){
                $whereArr[''] = $postData['wo_ro_signoff_to'];
            }
        }
        if(!empty($postData['wo_ro_service_info'])){
            if(!empty($postData['wo_ro_service_technician'])){
                $whereArr[''] = $postData['wo_ro_service_technician'];
            }
            if(!empty($postData['wo_ro_loggedin_from'])){
                $whereArr[''] = $postData['wo_ro_loggedin_from'];
            }
            if(!empty($postData['wo_ro_loggedin_to'])){
                $whereArr[''] = $postData['wo_ro_loggedin_to'];
            }
        }
        if(!empty($postData['wo_ro_include_outside_repair'])){
            if(!empty($postData['wo_ro_osr_part_number'])){
                $whereArr[''] = $postData['wo_ro_osr_part_number'];
            }
            if(!empty($postData['wo_ro_osrdescription'])){
                $whereArr[''] = $postData['wo_ro_osrdescription'];
            }
            if(!empty($postData['wo_ro_osr_old_serial_no'])){
                $whereArr[''] = $postData['wo_ro_osr_old_serial_no'];
            }
            if(!empty($postData['wo_ro_osr_new_serial_no'])){
                $whereArr[''] = $postData['wo_ro_osr_new_serial_no'];
            }
            if(!empty($postData['wo_ro_osr_vendor'])){
                $whereArr[''] = $postData['wo_ro_osr_vendor'];
            }
        }
        if(!empty($postData['wo_ro_include_parts'])){
            if(!empty($postData['wo_ro_part_number'])){
                $whereArr[''] = $postData['wo_ro_part_number'];
            }
            if(!empty($postData['wo_ro_part_description'])){
                $whereArr[''] = $postData['wo_ro_part_description'];
            }
            if(!empty($postData['wo_ro_po_number'])){
                $whereArr[''] = $postData['wo_ro_po_number'];
            }
            if(!empty($postData['wo_ro_old_serial_no'])){
                $whereArr[''] = $postData['wo_ro_old_serial_no'];
            }
            if(!empty($postData['wo_ro_new_serial_no'])){
                $whereArr[''] = $postData['wo_ro_new_serial_no'];
            }
            if(!empty($postData['wo_ro_supplier'])){
                $whereArr[''] = $postData['wo_ro_supplier'];
            }
            if(!empty($postData['wo_ro_po_vendor'])){
                $whereArr[''] = $postData['wo_ro_po_vendor'];
            }
        }
        
    }

    public function getToolCertifiedHistoryList($tool_id){
        $this->InventoryToolCertificationHistories = $this->getController()->fetchTable('InventoryToolCertificationHistories');
        $certifiedhistlist = $this->InventoryToolCertificationHistories->find('all')->where(['tool_id'=>$tool_id])->select($this->InventoryToolCertificationHistories);

        return $certifiedhistlist;
    }

    public function getInventoryToolPhotoes($tool_id){
        $connection = ConnectionManager::get('default');

        $woitemphotoes = $connection
                            ->execute(
                                'SELECT * FROM inventory_tool_photo WHERE tool_id = :tool_id and status = "1"',
                                ['tool_id' => $tool_id]
                            )
                            ->fetchAll('assoc');

        return $woitemphotoes;
    }

    public function getInventoryToolFiles($tool_id){
        $connection = ConnectionManager::get('default');

        $woitemfiles = $connection
                            ->execute(
                                'SELECT * FROM inventory_tool_file WHERE tool_id = :tool_id and status = "1"',
                                ['tool_id' => $tool_id]
                            )
                            ->fetchAll('assoc');

        return $woitemfiles;
    }

    public function getInventoryToolTabData($postData){
        $this->CustomerAircraftWOItemTools = $this->getController()->fetchTable('CustomerAircraftWOItemTools');
        $this->InventoryToolCertificationHistories = $this->getController()->fetchTable('InventoryToolCertificationHistories');
        
        $inventorytools = $this->getInventoryToolById($postData);
        $tool_id = $postData['tool_id'];
        $certificationhistories = [];
        $wohistories = [];
        $invtoolphotoes = [];
        $invtoolfiles = [];
        $vendorlist = [];

        if(!empty($inventorytools)){
            $tool_id = $inventorytools->id;
            $certificationhistories = $this->InventoryToolCertificationHistories->find('all')->where(['tool_id'=>$tool_id])->select($this->InventoryToolCertificationHistories);
            $wohistories = $this->CustomerAircraftWOItemTools->find('all')
                                ->where(['CustomerAircraftWOItemTools.tool_id'=>$tool_id])
                                ->select(['work_order.work_order_no', 'wo_item.wo_item_position', 'inv_tool.calibration_date', 'inv_tool.due_date', 'inv_tool.created_at'])
                                ->join([
                                    'inv_tool' => [
                                        'table' => 'inventory_tools',
                                        'type' => 'INNER',
                                        'conditions' => 'inv_tool.id = CustomerAircraftWOItemTools.tool_id',
                                    ],
                                    'wo_item' => [
                                        'table' => 'customer_aircraft_wo_items',
                                        'type' => 'INNER',
                                        'conditions' => 'wo_item.id = CustomerAircraftWOItemTools.wo_item_id',
                                    ],
                                    'work_order' => [
                                        'table' => 'customer_aircraft_work_orders',
                                        'type' => 'INNER',
                                        'conditions' => 'work_order.id = wo_item.work_order_id',
                                    ]
                                ])->order(['inv_tool.id'=>'DESC']);
            
            $invtoolphotoes = $this->getInventoryToolPhotoes($tool_id);
            $invtoolfiles = $this->getInventoryToolFiles($tool_id);
            
            $vendorlist = $this->getPartSupplierDropdownList();
        }

        $returnArr = ['inventorytools'=>$inventorytools, 'certificationhistories'=>$certificationhistories, 'wohistories'=>$wohistories, 'invtoolphotoes'=>$invtoolphotoes, 'invtoolfiles'=>$invtoolfiles, 'vendorlist'=>$vendorlist, 'tool_id'=>$tool_id];

        return $returnArr;
    }

    public function getInventoryToolById($postData){
        $this->InventoryTools = $this->getController()->fetchTable('InventoryTools');
        
        $orderby = array('order'=>'InventoryTools.id DESC');
        $whereArr = [];
        if(empty($postData['clickbtn'])){
            $whereArr = ['InventoryTools.id'=>$postData['tool_id']];
        }else{
            if($postData['clickbtn'] == 'first'){
                $orderby = array('order'=>'InventoryTools.id ASC');                
            }
            if($postData['clickbtn'] == 'last'){
                $orderby = array('order'=>'InventoryTools.id DESC');                
            }
            if($postData['clickbtn'] == 'prev'){
                $whereArr['InventoryTools.id <'] = $postData['tool_id'];
                $orderby = array('order'=>'InventoryTools.id DESC');                
            }
            if($postData['clickbtn'] == 'next'){
                $whereArr['InventoryTools.id >'] = $postData['tool_id'];
                $orderby = array('order'=>'InventoryTools.id ASC');                
            }
        }
        
        $inventorytools = $this->InventoryTools->find('all', $orderby)
                                    ->where($whereArr)
                                    ->select($this->InventoryTools)
                                    ->first();
        
        return $inventorytools;
    }

    public function getAircraftWOItemTools($wo_item_id){
        $this->CustomerAircraftWOItemTools = $this->getController()->fetchTable('CustomerAircraftWOItemTools');
        
        $woitemtoollists = $this->CustomerAircraftWOItemTools->find('all', ['order'=>'CustomerAircraftWOItemTools.id DESC'])
                                    ->where(['CustomerAircraftWOItemTools.wo_item_id'=>$wo_item_id])
                                    ->select($this->CustomerAircraftWOItemTools);

        return $woitemtoollists;
    }

    public function getWOItemToolById($postData){
        $this->CustomerAircraftWOItemTools = $this->getController()->fetchTable('CustomerAircraftWOItemTools');
        
        $orderby = array('order'=>'CustomerAircraftWOItemTools.id DESC');
        $whereArr = [];
        if(empty($postData['clickbtn'])){
            $whereArr = ['CustomerAircraftWOItemTools.id'=>$postData['wo_item_tool_id']];
        }else{
            if($postData['clickbtn'] == 'first'){
                $orderby = array('order'=>'CustomerAircraftWOItemTools.id ASC');                
            }
            if($postData['clickbtn'] == 'last'){
                $orderby = array('order'=>'CustomerAircraftWOItemTools.id DESC');                
            }
            if($postData['clickbtn'] == 'prev'){
                $whereArr['CustomerAircraftWOItemTools.id <'] = $postData['wo_item_tool_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemTools.id DESC');                
            }
            if($postData['clickbtn'] == 'next'){
                $whereArr['CustomerAircraftWOItemTools.id >'] = $postData['wo_item_tool_id'];
                $orderby = array('order'=>'CustomerAircraftWOItemTools.id ASC');                
            }
        }
        $whereArr['CustomerAircraftWOItemTools.wo_item_id'] = $postData['wo_item_id'];
        
        $woitemtools = $this->CustomerAircraftWOItemTools->find('all', $orderby)
                                    ->where($whereArr)
                                    ->select($this->CustomerAircraftWOItemTools)
                                    ->first();
        
        return $woitemtools;
    }

    public function getWOItemOSRById($postData){
        $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
        
        $orderby = array('order'=>'CustomerAircraftWOOSRInfoes.id DESC');
        $whereArr = [];
        if(empty($postData['clickbtn'])){
            $whereArr = ['CustomerAircraftWOOSRInfoes.id'=>$postData['wo_osrinfo_id']];
        }else{
            if($postData['clickbtn'] == 'prev'){
                $whereArr['CustomerAircraftWOOSRInfoes.id <'] = $postData['wo_osrinfo_id'];
                $orderby = array('order'=>'CustomerAircraftWOOSRInfoes.id DESC');                
            }
            if($postData['clickbtn'] == 'next'){
                $whereArr['CustomerAircraftWOOSRInfoes.id >'] = $postData['wo_osrinfo_id'];
                $orderby = array('order'=>'CustomerAircraftWOOSRInfoes.id ASC');                
            }
        }
        $whereArr['CustomerAircraftWOOSRInfoes.wo_item_id'] = $postData['wo_item_id'];
        
        $wooutstandingoutside = $this->CustomerAircraftWOOSRInfoes->find('all', $orderby)
                                    ->where($whereArr)
                                    ->select($this->CustomerAircraftWOOSRInfoes)
                                    ->first();
        
        return $wooutstandingoutside;
    }

    public function customerOTCInvoicePDFReport($postData){
        $this->InventoryCustomers           = $this->getController()->fetchTable('InventoryCustomers');
        $this->CustomerOTCInfoInvoices      = $this->getController()->fetchTable('CustomerOTCInfoInvoices');
        $this->CustomerOTCInfoInvoiceParts  = $this->getController()->fetchTable('CustomerOTCInfoInvoiceParts');
        $this->Users                        = $this->getController()->fetchTable('Users');

        $customer_otc_invoice_id = $postData['customer_otc_invoice_id'];
        $otcinfoinvoices = $this->CustomerOTCInfoInvoices->get($customer_otc_invoice_id);
        $connection = ConnectionManager::get('default');

        $inventorycustomers = $connection
                            ->execute(
                                'SELECT invc.id, invc.customer_name, invc.address, ica.address as ship_to_address, invc.address2, ica.address2 as ship_to_address2, invc.city, ica.city as ship_to_city, invc.county, invc.state, invc.zip, ica.state as ship_to_state, ica.zip as ship_to_zip, invc.country, ica.country as ship_to_country, invc.cellular_phone, c.name as country_name, s.name as shipping_country_name, ica.name as shipping_customer_name, ica.phone_number as shipping_phone_number, ica.province as shipping_province FROM `inventory_customers` invc left join countries c on invc.country = c.id left join countries s on invc.ship_to_country = s.id left join inventory_customer_addresses ica on ica.id = invc.shipping_address_id where invc.id = :customer_id',
                                ['customer_id' => $otcinfoinvoices->customer_id]
                            )
                            ->fetch('assoc');
        
        $usersdet = $this->Users->get($otcinfoinvoices->added_by);
        
        $invoicepartlists = $this->CustomerOTCInfoInvoiceParts->find('all')
                                    ->where(['CustomerOTCInfoInvoiceParts.otc_invoice_id'=>$customer_otc_invoice_id])
                                    ->select($this->CustomerOTCInfoInvoiceParts)
                                    ->select(['invitm.part_number'])
                                    ->join([
                                        'invitm' => [
                                            'table' => 'inventory_items',
                                            'type' => 'INNER',
                                            'conditions' => 'invitm.id = CustomerOTCInfoInvoiceParts.part_number',
                                        ]
                                    ])
                                    ->order(['CustomerOTCInfoInvoiceParts.id'=>'DESC']);

        $mainHtml = '';
        if(!empty($customer_otc_invoice_id)){
            $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
            $defaultOTCShippingMethod = unserialize(DEFAULTOTCSHIPPINGMETHOD);

            $billingaddressdet = '';
            if(!empty($inventorycustomers['city'])){
                $billingaddressdet .= $inventorycustomers['city'].', ';
            }
            if(!empty($inventorycustomers['state'])){
                $billingaddressdet .= $inventorycustomers['state'].', ';
            }
            if(!empty($inventorycustomers['country_name'])){
                $billingaddressdet .= $inventorycustomers['country_name'].', ';
            }
            if(!empty($inventorycustomers['zip'])){
                $billingaddressdet .= $inventorycustomers['zip'];
            }

            $billingaddressdet = rtrim($billingaddressdet, ',');
            $shippingaddressdet = $billingaddressdet;

            if($otcinfoinvoices['invoice_ship_to'] == '2'){
                $shippingaddressdet = '<tr>
                                            <td>'.$inventorycustomers['shipping_customer_name'].'</td>
                                        </tr>
                                        <tr>
                                            <td>'.$inventorycustomers['ship_to_address'].'</td>
                                        </tr>';
                if(!empty($inventorycustomers['ship_to_address2'])){
                    $shippingaddressdet .= '<tr>
                                                <td>'.$inventorycustomers['ship_to_address2'].'</td>
                                            </tr>';
                }
                $shippcitydet = '';
                if(!empty($inventorycustomers['ship_to_city'])){
                    $shippcitydet .= $inventorycustomers['ship_to_city'].', ';
                }
                if(!empty($inventorycustomers['ship_to_state'])){
                    $statearr = $this->getStateNameById($inventorycustomers['ship_to_state']);
                    $shippcitydet .= $statearr['name'].', ';
                }else if(!empty($inventorycustomers['shipping_province'])){
                    $shippcitydet .= $inventorycustomers['shipping_province'].', ';
                }
                if(!empty($inventorycustomers['shipping_country_name'])){
                    $shippcitydet .= $inventorycustomers['shipping_country_name'].', ';
                }
                if(!empty($inventorycustomers['ship_to_zip'])){
                    $shippcitydet .= $inventorycustomers['ship_to_zip'];
                }
                $shippcitydet = rtrim($shippcitydet, ',');

                $shippingaddressdet .= '<tr>
                                            <td>'.$shippcitydet.'</td>
                                        </tr>';
                if(!empty($inventorycustomers['shipping_phone_number'])){
                    $shippingaddressdet .= '<tr>
                                                <td>Phone: '.$inventorycustomers['shipping_phone_number'].'</td>
                                            </tr>';
                }
            }else if($otcinfoinvoices['invoice_ship_to'] == '3'){
                $new_array_with_new_variablesTMP = explode("\n", $otcinfoinvoices['other_shipping_address']);
                $new_array_with_new_variables = array($new_array_with_new_variablesTMP[0]);
                array_shift($new_array_with_new_variablesTMP);
                array_push($new_array_with_new_variables, implode('\n', $new_array_with_new_variablesTMP));

                $shippingaddressdet = '<tr>
                                            <td>'.$new_array_with_new_variables[0].'</td>
                                        </tr>
                                        <tr>
                                            <td>'.$new_array_with_new_variables[1].'</td>
                                        </tr>';
            }

            $mainHtml .= '<table cellspacing="0">
                            <tr>
                                <td>
                                    <table class="invoice-h" cellspacing="0" cellpadding="10">
                                    <tr><td>INVOICE</td></tr>
                                    </table>
                                </td>
                            </tr>';
            
            $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
            $mainHtml .= '<tr>
                            <td class="td-invoice-top">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                <td valign="top">
                                    <table cellspacing="0" cellpadding="0" class="billing-table">
                                    <thead>
                                        <tr>
                                            <td><b>Bill To</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>'.$inventorycustomers['customer_name'].'</td>
                                        </tr>
                                        <tr>
                                            <td>'.$inventorycustomers['address'].'</td>
                                        </tr>
                                        <tr>
                                            <td>'.$inventorycustomers['address2'].'</td>
                                        </tr>
                                        <tr>
                                            <td>'.$billingaddressdet.'</td>
                                        </tr>
                                        <tr>
                                            <td>Phone: '.$inventorycustomers['cellular_phone'].'</td>
                                        </tr>
                                        <!--tr>
                                            <td class="pay-method"><b>Payment Method:</b>'.(!empty($otcinfoes['default_payment_method']) ? $defaultPaymentMethod[$otcinfoes['default_payment_method']] : '').'</td>
                                        </tr-->
                                    </tbody>
                                    </table>
                                </td>
                                <td valign="top">
                                    <table cellspacing="0" cellpadding="0" class="billing-table">
                                    <thead>
                                        <tr>
                                            <td><b>Ship To</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        '.$shippingaddressdet.'
                                    </tbody>
                                    </table>
                                </td>
                                <td width="250" valign="top">
                                    <table class="table-invoice" cellspacing="0" cellpadding="0">
                                        <thead>
                                        <tr>
                                            <td align="right">Invoice No.:</td>
                                            <td>'.$otcinfoinvoices['otc_invoice_no'].'</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td align="right" class="tb-in-td"><b>Customer ID:</b></td>
                                            <td class="tb-in-td">'.$inventorycustomers['customer_name'].'</td>
                                        </tr>
                                        <tr>
                                            <td align="right" ><b>Date:</b></td>
                                            <td>'.date('m/d/Y', strtotime($otcinfoinvoices['created_at'])).'</td>
                                        </tr>
                                        <tr>
                                            <td align="right"><b>Ship Via:</b></td>
                                            <td>'.(!empty($otcinfoinvoices['invoice_shipping_method']) ? $defaultOTCShippingMethod[$otcinfoinvoices['invoice_shipping_method']] : '').'</td>
                                        </tr>
                                        <tr>
                                            <td align="right"><b>P/O No.:</b></td>
                                            <td>'.$otcinfoinvoices['invoice_customer_po_no'].'</b></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><b>Terms:</b></td>
                                            <td>'.(!empty($otcinfoinvoices['invoice_terms']) ? $defaultPaymentMethod[$otcinfoinvoices['invoice_terms']] : '').'</td>
                                        </tr>
                                        <tr>
                                            <td align="right"><b>Contact:</b></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td align="right"><b>Sold By:</b></td>
                                            <td>'.$usersdet['full_name'].'</td>
                                        </tr>
                                    </tbody>
                                    </table>
                                    </td>
                                </tr>
                                </table>
                            </td>
                        </tr>';

            $mainHtml .= '<tr>
            <td>
              <table border="0" cellspacing="0" cellpadding="0" class="table-invoice-list">
                <thead>
                <tr>
                    <td>Part Number</td>
                    <td>Description</td>
                    <td align="right">Retail Ea.</td>
                    <td align="right">Your Price Ea.</td>
                    <td align="right">Qty Order</td>
                    <td align="right">Qty Ship Unit</td>
                    <td align="right">Total Retail</td>
                </tr>
            </thead>
            <tbody>';
            $totalparts = 0;
            $totalshippingcharges = 0;
            foreach($invoicepartlists as $keys=>$parts){
                $totalprice = $parts['price_each'];
                if(!empty($parts['give_discount_percentage'])){
                    $calprice = ($parts['price_each']*$parts['give_discount_percentage'])/100;
                    $totalprice = $parts['price_each']-$calprice;
                }
                $totalparts += $totalprice;
                $totalshippingcharges += $parts['drop_ship_charges'];
                
                $mainHtml .= '<tr>
                                <td>'.$parts['invitm']['part_number'].'</td>
                                <td>'.$parts['part_description'].'</td>
                                <td align="right">0.00</td>
                                <td align="right">'.$parts['price_each'].'</td>
                                <td align="right">'.$parts['qty_needed'].'</td>
                                <td align="right">'.$parts['qty_needed'].'</td>
                                <td align="right">'.$totalprice.'</td>
                            </tr>';
            }

            $mainHtml .= '</tbody>
                            </table>
                        </td>
                        </tr>';

                $mainHtml .= '<tr>
                <td>
                  <table border="0" cellspacing="0" cellpadding="0" class="table-return">
                    <tr>
                      <td width="700"> 
                        <table>
                          <tr>
                            <td>
                              <div><strong>Return Policy</strong></div>
                              <br/>
                              <div>All return request must be made within warranty time period starting at date of sale.</div>
                              <div>Southwest Aviation Specialties LLC reserves the right to refuse unauthorised returns.</div>
                              <div>Part(s) must be returned to Southwest Aviation Specialties LLC 8720 Jack Bates Ave. Tulsa, OK 74132, 
                                transportation charges prepaid. No COD shipments will be accepted.
                              </div>
                              <div>Part(s) must be returned in the same condition as received, without modifications, trimmed or painted. 
                                Any Safety Seal/Sticker that is tampered with will void warranty.
                              </div>
                              <div>
                                All Part(s) must be returned with all paperwork supplied with the part(s). All part(s) that ship with an 8130-3,
                                must have original 8130-3 returned with the part.
                              </div>
                              <div>
                                All Part(s) may be subject to a 25% of $25.00 (whichever is greater) restock fee, unless the return is due to out error 
                                or the part is found to be defective. 
                              </div>
                              <div>
                                Electronic instruments that are installed and damaged due to aircraft discrepancies will not be refunded.
                              </div>
                              <div>
                                Electronic piece parts are not warrantied upon install.
                              </div>
                              <div>
                                All refunds and or warranty considerations are at the sole discretion of Southwest Aviation Specialties, LLC and 
                                will either replace the part within 30 days or issue in store credit.
                              </div>
                              <div><strong>All Refunds will be credited to Buyers in-store account toward future purchase.</strong></div>
                              <div>No returns are accepted after Warranty time period.</div>
                            </td>
                          </tr>
                        </table>
                    </td>
                    <td valign="top" align="right">
                        <table border="0" cellspacing="0" cellpadding="0" class="table-total">
                        <tr>
                            <td align="right"><b>Total Parts:</b></td>
                            <td align="right">$'.$totalparts.'</td>
                        </tr>
                        <tr>
                            <td align="right"><b>Total Other Charges:</b></td>
                            <td align="right">$0.00</td>
                        </tr>
                        <tr>
                            <td align="right"><b>Total Shipping:</b></td>
                            <td align="right">$'.$totalshippingcharges.'</td>
                        </tr>
                        <tr>
                            <td align="right"><b>Total Tax:</b></td>
                            <td align="right">$0.00</td>
                        </tr>
                        <tr class="total-inv-amt">
                            <td align="right"><b>Total Invoice Amount:</b></td>
                            <td align="right">$'.$totalparts.'</td>
                        </tr>
                        <tr class="deposit-amt">
                            <td align="right"><b>Deposits & Credits:</b></td>
                            <td align="right">0.00 %</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="td-rt">
                                <table border="0" cellspacing="0" cellpadding="0" class="table-balance">
                                <tr class="balance-amt">
                                    <td align="right"><b>Balance Due (Dollars): </b></td>
                                    <td align="right">$'.$totalparts.'</td>
                                </tr>
                                </table>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>';
        }

        return $mainHtml;
    }

    public function getCustomerShippingAddrDropdown($customer_id){
        $this->InventoryCustomerAddresses = $this->getController()->fetchTable('InventoryCustomerAddresses');
        $customeraddressarr = $this->InventoryCustomerAddresses->find('all', ['order'=>'address ASC'])->where(['customer_id'=>$customer_id])->select(['id', 'name', 'address']);
        
        $customeraddrdropdown = [];
        foreach($customeraddressarr as $addr){
            $customeraddrdropdown[$addr['id']] = $addr['name']." | ".$addr['address'];
        }
        
        return $customeraddrdropdown;
    }

    public function getCustomerPhoneNumberDropdown($customer_id){
        $this->InventoryCustomerPhones = $this->getController()->fetchTable('InventoryCustomerPhones');
        $customerphonearr = $this->InventoryCustomerPhones->find('all', ['order'=>'id DESC'])->where(['customer_id'=>$customer_id])->select(['id', 'phone_number']);
        
        $customerphonedropdown = [];
        foreach($customerphonearr as $phone){
            $customerphonedropdown[$phone['phone_number']] = $phone['phone_number'];
        }
        
        return $customerphonedropdown;
    }

    public function getStateNameById($stateId) {
        $states = array();
        $stateModel = $this->getController()->fetchTable('States');
        $states = $stateModel->get($stateId);

        return $states;
    }

    public function getWorkOrderLogbookValueList($aircraft_id, $engine_type){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        $workorderlist = $this->CustomerAircraftWorkOrders->find('all', ['order'=>'CustomerAircraftWorkOrders.id'])
                                    ->where(['aircraft_id'=>$aircraft_id, 'wo_status'=>'1'])
                                    ->select($this->CustomerAircraftWorkOrders);
                                    
        if($engine_type != '5'){
            $workorderlist = $workorderlist->select(['overviews.current_ac_tt', 'overviews.current_ac_tach', 'overviews.hobbs'])
                                                ->join([
                                                    'overviews' => [
                                                        'table' => 'customer_aircraft_wo_logbook_value_overviews',
                                                        'type' => 'INNER',
                                                        'conditions' => 'overviews.work_order_id = CustomerAircraftWorkOrders.id',
                                                    ]
                                                ]);
        }else{
            $workorderlist = $workorderlist->select(['overviews.actt', 'overviews.hobbs'])
                                                ->join([
                                                    'overviews' => [
                                                        'table' => 'aircraft_wo_logbook_value_helicopter_overviews',
                                                        'type' => 'INNER',
                                                        'conditions' => 'overviews.work_order_id = CustomerAircraftWorkOrders.id',
                                                    ]
                                                ]);
        }

        return $workorderlist;
    }

    public function getAircraftOpenWorkOrderNo($aircraft_id){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderlist = $this->CustomerAircraftWorkOrders->find('all')->where(['aircraft_id'=>$aircraft_id, 'wo_status'=>'1'])->select(['work_order_no']);
    
        $workordernolist = [];
        if($workorderlist->count() > 0){
            foreach($workorderlist as $workorders){
                $workordernolist[] = $workorders['work_order_no'];
            }
        }
    
        return $workordernolist;
    }

    public function getWOItemDiscrepancyHistory($wo_item_id){
        $this->AircraftWOItemDiscrepancyHistories = $this->getController()->fetchTable('AircraftWOItemDiscrepancyHistories');

        $discrepancyhistorylist = $this->AircraftWOItemDiscrepancyHistories->find('all', ['order'=>'AircraftWOItemDiscrepancyHistories.id desc'])
                                    ->where(['wo_item_id'=>$wo_item_id])
                                    ->select($this->AircraftWOItemDiscrepancyHistories)
                                    ->select(['users.full_name'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = AircraftWOItemDiscrepancyHistories.added_by',
                                        ]
                                    ]);

        return $discrepancyhistorylist;
    }

    public function getWOItemCorrectiveActionHistory($wo_item_id){
        $this->AircraftWOItemCorrectiveActionHistories = $this->getController()->fetchTable('AircraftWOItemCorrectiveActionHistories');
        
        $correctiveactionhistorylist = $this->AircraftWOItemCorrectiveActionHistories->find('all', ['order'=>'AircraftWOItemCorrectiveActionHistories.id desc'])
                                    ->where(['wo_item_id'=>$wo_item_id])
                                    ->select($this->AircraftWOItemCorrectiveActionHistories)
                                    ->select(['users.full_name'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = AircraftWOItemCorrectiveActionHistories.added_by',
                                        ]
                                    ]);

        return $correctiveactionhistorylist;
    }

    public function getAircraftWOItemHistoryData($wo_item_id){
        $this->AircraftWOItemHistories = $this->getController()->fetchTable('AircraftWOItemHistories');

        $aircraftwoitemhistories = $this->AircraftWOItemHistories->find('all')
                                    ->where(['wo_item_id'=>$wo_item_id])
                                    ->select($this->AircraftWOItemHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = AircraftWOItemHistories.user_id',
                                        ]
                                    ])->order(['AircraftWOItemHistories.id'=>'DESC']);

        return $aircraftwoitemhistories;
    }

    public function getInventroyItemInventoriesDet($part_number, $serial_no, $inventory_status='1'){
        $connection = ConnectionManager::get('default');

        $inventoryitemdet = $connection->execute(
            "SELECT invitm.id as inv_item_id, inv.id, inv.status FROM `inventory_items` invitm join `inventories` inv on invitm.id = inv.inventory_item_id WHERE invitm.part_number = :part_number and inv.serial_no = :serial_no and inv.status = :inventory_status and invitm.status = '1'",
            ['part_number' => $part_number, 'serial_no'=>$serial_no, 'inventory_status'=>$inventory_status])->fetch('assoc');

        return $inventoryitemdet;
    }

    public function getWOItemDetailByItemId($wo_item_id){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderarr = $this->CustomerAircraftWorkOrders->find('all')
                                                        ->where(['wo_items.id'=>$wo_item_id])
                                                        ->select($this->CustomerAircraftWorkOrders)
                                                        ->select(['wo_items.wo_item_position'])
                                                        ->join([
                                                            'wo_items' => [
                                                                'table' => 'customer_aircraft_wo_items',
                                                                'type' => 'INNER',
                                                                'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                            ]
                                                        ])
                                                        ->first();

        return $workorderarr;
    }

    public function getWorkOrderitemListDropDown(){
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderlist = $this->CustomerAircraftWorkOrders->find('all')
                                                        ->where(['CustomerAircraftWorkOrders.wo_status'=>'1'])
                                                        ->select($this->CustomerAircraftWorkOrders)
                                                        ->select(['wo_items.wo_item_position', 'wo_items.id'])
                                                        ->join([
                                                            'wo_items' => [
                                                                'table' => 'customer_aircraft_wo_items',
                                                                'type' => 'INNER',
                                                                'conditions' => 'wo_items.work_order_id = CustomerAircraftWorkOrders.id',
                                                            ]
                                                        ]);
        $woitemdropdown = [];
        foreach($workorderlist as $workorder){
            $woitemdropdown[$workorder['wo_items']['id']] = $workorder['work_order_no'].'-'.$workorder['wo_items']['wo_item_position'];
        }

        return $woitemdropdown;
    }

    public function getWOItemServicesTotalTechHrs($wo_item_id, $services_id){
        $connection = ConnectionManager::get('default');
        
        $woservicedata = $connection->execute(
            "SELECT SUM(total_hrs_for_tech) as totaltechhour from customer_aircraft_wo_item_services where id != :services_id and wo_item_id = :wo_item_id",
            ['wo_item_id' => $wo_item_id, 'services_id'=>$services_id])->fetch('assoc');
        
        return $woservicedata['totaltechhour'];
    }

    public function validateUserAccessCode($time_clock_code){
        $this->Users = $this->getController()->fetchTable('Users');
        $authUserData = $this->Authentication->getResult()->getData();
        $validateaccesscode = $this->Users->find('all')->where(['id'=>$authUserData['id'], 'time_clock_code'=>$time_clock_code])->count();
        
        return $validateaccesscode;
    }

    public function validateUserCertificationCode($certification_code){
        $this->Users = $this->getController()->fetchTable('Users');
        $authUserData = $this->Authentication->getResult()->getData();

        $validatecertificationcode = $this->Users->find('all')->where(['id'=>$authUserData['id'], 'certification_code'=>$certification_code])->count();
        
        return $validatecertificationcode;
    }

    public function getNewMessageCount(){
        $this->CustomerAircraftWOMessages = $this->getController()->fetchTable('CustomerAircraftWOMessages');
        $authUserData = $this->Authentication->getResult()->getData();
        $user_id = $authUserData['id'];

        $currentdate = date('Y-m-d');
        $whereArr = ['DATE(created_at)'=>$currentdate, 'is_mark_read'=>'0'];
        if($user_id != '1'){
            $whereArr['message_to'] = $user_id;
        }
        $newmsgcount = $this->CustomerAircraftWOMessages->find('all')->where($whereArr)->count();

        return $newmsgcount;
    }

    public function getWOItemSignOff($wo_item_id, $signoff_category=''){
        $this->AircraftWOItemSignoffs = $this->getController()->fetchTable('AircraftWOItemSignoffs');

        $whereArr = ['wo_item_id'=>$wo_item_id];
        if(!empty($signoff_category)){
            $whereArr['signoff_category'] = $signoff_category;
        }
        $wosignoffitems = $this->AircraftWOItemSignoffs->find('all', ['order'=>'signoff_category'])
                                                        ->where($whereArr)
                                                        ->select($this->AircraftWOItemSignoffs)
                                                        ->select(['users.full_name', 'users.user_initials'])
                                                        ->join([
                                                            'users' => [
                                                                'table' => 'users',
                                                                'type' => 'INNER',
                                                                'conditions' => 'users.id = AircraftWOItemSignoffs.inspected_by',
                                                            ]
                                                        ]);
        if(!empty($signoff_category)){
            $wosignoffitems = $wosignoffitems->first();
        }else{
            $wosignoffitems = $wosignoffitems->all()->toArray();
        }
        return $wosignoffitems;
    }

    public function getAircraftOptionDataWO(){
        $this->CustomerOTCAircrafts = $this->getController()->fetchTable('CustomerOTCAircrafts');
        $customerAircrafts = $this->CustomerOTCAircrafts->find('all')
                                                    ->select(['CustomerOTCAircrafts.id', 'CustomerOTCAircrafts.aircraft_registration_number', 'CustomerOTCAircrafts.aircraft_year', 'CustomerOTCAircrafts.aircraft_serial', 'aircraft_model.model', 'aircraft_make.make'])
                                                    ->join([
                                                        'aircraft_model' => [
                                                            'table' => 'customer_otc_aircraft_model',
                                                            'type' => 'LEFT',
                                                            'conditions' => 'aircraft_model.id = CustomerOTCAircrafts.aircraft_model_id',
                                                        ]
                                                    ])
                                                    ->join([
                                                        'aircraft_make' => [
                                                            'table' => 'customer_otc_aircraft_make',
                                                            'type' => 'LEFT',
                                                            'conditions' => 'aircraft_make.id = CustomerOTCAircrafts.aircraft_make_id',
                                                        ]
                                                    ]);

        $aircraftoptiondata = [];

        foreach ($customerAircrafts as $aircraft) {
            $details = [];

            // Always include registration number first
            if (!empty($aircraft['aircraft_registration_number'])) {
                $details[] = $aircraft['aircraft_registration_number'];
            }

            // Add optional details if available
            if (!empty($aircraft['aircraft_make']['aircraft_make'])) {
                $details[] = $aircraft['aircraft_make']['aircraft_make'];
            }

            if (!empty($aircraft['aircraft_model']['model'])) {
                $details[] = $aircraft['aircraft_model']['model'];
            }

            if (!empty($aircraft['aircraft_year'])) {
                $details[] = $aircraft['aircraft_year'];
            }

            if (!empty($aircraft['aircraft_serial'])) {
                $details[] = $aircraft['aircraft_serial'];
            }

            // Combine all available details with a separator
            $aircraftoptiondata[$aircraft['id']] = implode(' | ', $details);
        }
        
        return $aircraftoptiondata;
    }

    public function checkWorkOrderMenuPermission(){
        $this->UserMenuItems = $this->getController()->fetchTable('UserMenuItems');
        $authUserData = $this->Authentication->getResult()->getData();
        $userMenuItems = $this->UserMenuItems->find('all')
                                            ->where(['UserMenuItems.menu_item_id'=>'26', 'UserMenuItems.user_id' => $authUserData['id']])->select($this->UserMenuItems)->first();

        return $userMenuItems;
    }

    public function getWOCustomerDetails($postData){
        $work_order_id = $postData['work_order_id'];
        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';

        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $wocustomerdet = $this->CustomerAircraftWorkOrders->find('all');
        $wocustomerdet = $wocustomerdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.address', 'customers.cellular_phone', 'customers.address2', 'customers.city', 'customers.state', 'customers.zip', 'customers.country'])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->first();

        return $wocustomerdet;
    }

    public function getWOPrintPreviewReport($postData){
        $report_type = $postData['wo_report_type'];
        
        $reportdata = [];

        if($report_type == '1'){
            $reportdata = $this->getWOCustomerDetails($postData);
        }else if(($report_type >= '5' && $report_type <= '11') || $report_type == '71'){
            $reportdata = $this->getWOCustomerEstimateData($postData);
        }else if(($postData['wo_report_type'] >= '12' && $postData['wo_report_type'] <= '23') || ($postData['wo_report_type'] >= '46' && $postData['wo_report_type'] <= '47') || ($postData['wo_report_type'] >= '72' && $postData['wo_report_type'] <= '74')){
            $reportdata = $this->getWOCustomerInvoicesData($postData);
        }else if($report_type >= '24' && $report_type <= '27'){
            $reportdata = $this->getWODiscrepancyData($postData);
        }else if($report_type >= '28' && $report_type <= '35'){
            $reportdata = $this->getWOItemByGroupData($postData);
        }else if($report_type >= '39' && $report_type <= '42'){
            $reportdata = $this->getWOMaintenanceData($postData);
        }else if($report_type == '43'){
            $reportdata = $this->getOSRProfileReportData($postData);
        }else if($report_type >= '48' && $report_type <= '57'){
            $reportdata = $this->getWOServicesList($postData);
        }else if($report_type == '58'){
            $reportdata = $this->getWOSignOffsList($postData);
        }else if($report_type >= '59' && $report_type <= '60'){
            $reportdata = $this->getWOTechniciansOnItems($postData);
        }else if($report_type >= '61' && $report_type <= '70'){
            $reportdata = $this->getWOTimeReportData($postData);
        }else if($report_type == '37'){
            $reportdata = $this->getWOLogbookLabelsReportData($postData);
        }

        return $reportdata;
    }

    public function getWOCustomerEstimateData($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.address', 'customers.cellular_phone', 'customers.address2', 'customers.city', 'customers.state', 'customers.zip', 'customers.country', 'customers.terms', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'wo_misc_charges.epa_charge_amount', 'wo_misc_charges.oil_analysis_amount', 'wo_misc_charges.mis_charge_amount', 'wo_misc_charges.pilot_services_amount', 'wo_misc_charges.shop_supplies_amount', 'wo_misc_charges.amount_per_tire', 'wo_misc_charges.tire', 'aircraft.aircraft_registration_number', 'wo_general_info.id', 'aircraft.aircraft_engine_type', 'wo_misc_charges.shop_supplies_method', 'aircraft.aircraft_serial', 'wo_misc_charges.id', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->join([
                                            'wo_misc_charges'=>[
                                                'table'=>'customer_aircraft_wo_option_misc_charges',
                                                'type'=>'LEFT',
                                                'conditions'=>'wo_misc_charges.work_order_id = CustomerAircraftWorkOrders.id'
                                            ]
                                        ])
                                        ->join([
                                            'wo_general_info'=>[
                                                'table'=>'customer_aircraft_wo_option_general_infoes',
                                                'type'=>'LEFT',
                                                'conditions'=>'wo_general_info.work_order_id = CustomerAircraftWorkOrders.id'
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];

            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];

            if($postData['wo_report_type'] == '71'){
                $maintoverview = $this->getLogbookValueOverviewData($engine_type, $work_order_id);
            }else{
                $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            }
            
            $workorderdet['logbook_value_overviews'] = !empty($maintoverview) ? $maintoverview : [];
            if(!empty($workorderdet['wo_misc_charges']['id'])){
                $miscfuelchargeslist = $this->getAircraftWOViewOptionMiscFuelCharges($workorderdet['wo_misc_charges']['id']);
                $totalfuelcharges = '0';
                foreach($miscfuelchargeslist as $fuelcharges){
                    $totalfuelcharges += $fuelcharges['gallon']*$fuelcharges['price'];
                }
                $workorderdet['wo_misc_charges']['totalfuelcharges'] = $totalfuelcharges;
                
            }
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $whereArr = ['work_order_id'=> $workorderdet['id']];
            if(!empty($postData['wo_category_preview'])){
                $whereArr['wo_item_overview.wo_category'] = $postData['wo_category_preview'];
            }
            if($postData['wo_report_type'] == '71'){
                if(!empty($postData['warranty'])){
                    $whereArr['wo_item_overview.warranty'] = $postData['warranty'];
                }else{
                    $whereArr['wo_item_overview.warranty !='] = '';
                    $whereArr['wo_item_overview.item_is_warranty'] = '1';
                }
            }
            
            $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
            
            $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                        ->where($whereArr)
                                        ->select(['wo_item_overview.wo_category', 'wo_item_overview.estimated_hour', 'wo_item_overview.owner_authentication', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'wo_item_overview.special_rate_hr', 'CustomerAircraftWOItems.id', 'wo_item_overview.estimated_rate', 'wo_item_overview.special_hourly_rate_for_item', 'wo_item_overview.shipping_in', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action', 'wo_item_overview.warranty', 'wo_item_overview.way_of_billing', 'wo_item_overview.flat_rate_qty', 'wo_item_overview.item_is_warranty'])
                                        ->join([
                                            'wo_item_overview'=>[
                                                'table'=>'customer_aircraft_wo_item_overviews',
                                                'type'=>'INNER',
                                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                            ]);
                                        
            if($postData['wo_report_type'] == '10'){
                $woitemdet = $woitemdet->order(['wo_item_overview.wo_category'=>'ASC']);
            }
            $woitemdet = $woitemdet->toArray();

            $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
            $this->CustomerAircraftWOOptionWarrantyInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionWarrantyInfoes');

            if($postData['wo_report_type'] == '71'){
                $whereArr = ['work_order_id'=>$workorderdet['id']];
                if(!empty($postData['warranty'])){
                    $whereArr['company_id'] = $postData['warranty'];
                }
                $optionWarrantyInfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->find('all')->where($whereArr)->select($this->CustomerAircraftWOOptionWarrantyInfoes)->first();
                $optionWarrantyInfoes = !empty($optionWarrantyInfoes) ? $optionWarrantyInfoes->toArray() : [];
                $workorderdet['warranty_infoes'] = $optionWarrantyInfoes;
            }

            foreach($woitemdet as $key=>$rows){
                //if($postData['wo_report_type'] != '6'){
                    $aircraftwoitemosrinfoes = $this->CustomerAircraftWOOSRInfoes->find('all')
                                                ->where(['CustomerAircraftWOOSRInfoes.wo_item_id'=>$rows['id']])
                                                ->select($this->CustomerAircraftWOOSRInfoes)
                                                ->toArray();
                    
                    $woitemdet[$key]['wo_osr_info'] = $aircraftwoitemosrinfoes;
                /*}else{
                    $woitemdet[$key]['wo_osr_info'] = [];
                }*/

                $woitemdet[$key]['part_details'] = $this->getWOItemPartDetails($rows['id']);
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        $wooptiongeninfodeposits = [];
        if(!empty($workorderdet['wo_general_info']['id'])){
            $this->CustomerAircraftWOOptionGenInfoDeposits = $this->getController()->fetchTable('CustomerAircraftWOOptionGenInfoDeposits');
            $wooptiongeninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->find('all')
                                        ->where(['general_info_id'=>$workorderdet['wo_general_info']['id']])
                                        ->select(['amount_to_add'])
                                        ->toArray();
        }
        $workorderdet['wo_geninfo_deposits'] = $wooptiongeninfodeposits;

        return $workorderdet;
    }

    public function getWOCustomerInvoicesData($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.address', 'customers.cellular_phone', 'customers.address2', 'customers.city', 'customers.state', 'customers.zip', 'customers.country', 'customers.terms', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'wo_misc_charges.epa_charge_amount', 'wo_misc_charges.oil_analysis_amount', 'wo_misc_charges.mis_charge_amount', 'wo_misc_charges.pilot_services_amount', 'wo_misc_charges.shop_supplies_amount', 'wo_misc_charges.amount_per_tire', 'wo_misc_charges.tire', 'aircraft.aircraft_registration_number', 'wo_general_info.id', 'aircraft.aircraft_serial', 'aircraft.aircraft_engine_type', 'wo_misc_charges.misc_charge_description_for_invoice', 'wo_misc_charges.shop_supplies_method', 'wo_misc_charges.id', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->join([
                                            'wo_misc_charges'=>[
                                                'table'=>'customer_aircraft_wo_option_misc_charges',
                                                'type'=>'LEFT',
                                                'conditions'=>'wo_misc_charges.work_order_id = CustomerAircraftWorkOrders.id'
                                            ]
                                        ])
                                        ->join([
                                            'wo_general_info'=>[
                                                'table'=>'customer_aircraft_wo_option_general_infoes',
                                                'type'=>'LEFT',
                                                'conditions'=>'wo_general_info.work_order_id = CustomerAircraftWorkOrders.id'
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            if(!empty($workorderdet['wo_misc_charges']['id'])){
                $miscfuelchargeslist = $this->getAircraftWOViewOptionMiscFuelCharges($workorderdet['wo_misc_charges']['id']);
                $totalfuelcharges = '0';
                foreach($miscfuelchargeslist as $fuelcharges){
                    $totalfuelcharges += $fuelcharges['gallon']*$fuelcharges['price'];
                }
                $workorderdet['wo_misc_charges']['totalfuelcharges'] = $totalfuelcharges;
                
            }

            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];

            if($postData['wo_report_type'] >= '72' && $postData['wo_report_type'] <= '74'){
                $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            }else{
                $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            }
            if($engine_type != '5'){
                if($engine_type == '1' || $engine_type == '2'){
                    $this->CustomerAircraftWOLogBookValueEngines = $this->getController()->fetchTable('CustomerAircraftWOLogBookValueEngines');
                    $aircraftlogbookengine = $this->CustomerAircraftWOLogBookValueEngines->find('all')->where(['work_order_id'=>$work_order_id])->select($this->CustomerAircraftWOLogBookValueEngines)->first(); 
                }else{
                    $this->AircraftWOLogBookValueJetEngines = $this->getController()->fetchTable('AircraftWOLogBookValueJetEngines');
                    $aircraftlogbookengine = $this->AircraftWOLogBookValueJetEngines->find('all')->where(['work_order_id'=>$work_order_id])->select($this->AircraftWOLogBookValueJetEngines)->first(); 
                }
                $workorderdet['aircraftlogbookengine'] = $aircraftlogbookengine;
            }else{
                $workorderdet['aircraftlogbookengine'] = [];
            }
            
            $workorderdet['logbook_value_overviews'] = !empty($maintoverview) ? $maintoverview : [];
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $whereArr = ['work_order_id'=> $workorderdet['id']];
            if(!empty($postData['wo_category_preview'])){
                $whereArr['wo_item_overview.wo_category'] = $postData['wo_category_preview'];
            }
            if($postData['wo_report_type'] == '22'){
                $orderby = ['order'=>'wo_item_overview.wo_category'];
            }else{
                $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
            }
            if($postData['wo_report_type'] == '46'){
                $whereArr['CustomerAircraftWOItems.wo_item_status != '] = '4';
            }else if($postData['wo_report_type'] == '47'){
                $whereArr['CustomerAircraftWOItems.wo_item_status'] = '4';
            }
            if($postData['wo_report_type'] >= '72' && $postData['wo_report_type'] <= '74'){
                if(!empty($postData['warranty'])){
                    $whereArr['wo_item_overview.warranty'] = $postData['warranty'];
                }else{
                    $whereArr['wo_item_overview.warranty !='] = '';
                    $whereArr['wo_item_overview.item_is_warranty'] = '1';
                }
            }

            $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                        ->where($whereArr)
                                        ->select(['wo_item_overview.wo_category', 'wo_item_overview.way_of_billing', 'wo_item_overview.flat_rate', 'wo_item_overview.flat_rate_qty', 'wo_item_overview.estimated_hour', 'wo_item_overview.owner_authentication', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'wo_item_overview.special_rate_hr', 'CustomerAircraftWOItems.id', 'wo_item_overview.estimated_rate', 'wo_item_overview.special_hourly_rate_for_item', 'wo_item_overview.shipping_in', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action', 'wo_item_overview.warranty', 'wo_item_overview.warranty_claim_no', 'wo_item_overview.item_is_warranty'])
                                        ->join([
                                            'wo_item_overview'=>[
                                                'table'=>'customer_aircraft_wo_item_overviews',
                                                'type'=>'INNER',
                                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                            ]);
                                        
            $woitemdet = $woitemdet->toArray();

            $this->CustomerAircraftWOOSRInfoes = $this->getController()->fetchTable('CustomerAircraftWOOSRInfoes');
            $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');
            $this->CustomerAircraftWOOptionWarrantyInfoes = $this->getController()->fetchTable('CustomerAircraftWOOptionWarrantyInfoes');
            $this->CustomerAircraftWOItemParts = $this->getController()->fetchTable('CustomerAircraftWOItemParts');
            
            if($postData['wo_report_type'] >= '72' && $postData['wo_report_type'] <= '74'){
                $whereArr = ['work_order_id'=>$workorderdet['id']];
                if(!empty($postData['warranty'])){
                    $whereArr['company_id'] = $postData['warranty'];
                }
                $optionWarrantyInfoes = $this->CustomerAircraftWOOptionWarrantyInfoes->find('all')->where($whereArr)->select($this->CustomerAircraftWOOptionWarrantyInfoes)->first();
                $optionWarrantyInfoes = !empty($optionWarrantyInfoes) ? $optionWarrantyInfoes->toArray() : [];
                $workorderdet['warranty_infoes'] = $optionWarrantyInfoes;
            }

            foreach($woitemdet as $key=>$rows){
                //if($postData['wo_report_type'] != '15'){
                    $aircraftwoitemosrinfoes = $this->CustomerAircraftWOOSRInfoes->find('all')
                                                ->where(['CustomerAircraftWOOSRInfoes.wo_item_id'=>$rows['id']])
                                                ->select($this->CustomerAircraftWOOSRInfoes)
                                                ->toArray();
                    
                    $woitemdet[$key]['wo_osr_info'] = $aircraftwoitemosrinfoes;
                /*}else{
                    $woitemdet[$key]['wo_osr_info'] = [];
                }*/

                $signoffdet = $this->getWOItemSignOff($rows['id']);
                $woitemdet[$key]['signoff_details'] = $signoffdet;  

                $woitemservicesdet = $this->CustomerAircraftWOItemServices->find('all')
                                                                ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$rows['id']])
                                                                ->select(['total_hrs_for_tech'])
                                                                ->toArray();
                $woitemdet[$key]['services_details'] = $woitemservicesdet;

                $woitemdet[$key]['part_details'] = $this->getWOItemPartDetails($rows['id']);
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        $wooptiongeninfodeposits = [];
        if(!empty($workorderdet['wo_general_info']['id'])){
            $this->CustomerAircraftWOOptionGenInfoDeposits = $this->getController()->fetchTable('CustomerAircraftWOOptionGenInfoDeposits');
            $wooptiongeninfodeposits = $this->CustomerAircraftWOOptionGenInfoDeposits->find('all')
                                        ->where(['general_info_id'=>$workorderdet['wo_general_info']['id']])
                                        ->select(['amount_to_add'])
                                        ->toArray();
        }
        $workorderdet['wo_geninfo_deposits'] = $wooptiongeninfodeposits;

        return $workorderdet;
    }

    public function getWODiscrepancyData($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.cellular_phone', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_serial', 'aircraft.aircraft_engine_type', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];

            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];

            $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            $workorderdet['logbook_value_overviews'] = !empty($maintoverview) ? $maintoverview : [];
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $woitemdet = $this->CustomerAircraftWOItems->find('all', ['order'=>'CustomerAircraftWOItems.wo_item_position'])
                                        ->where(['work_order_id'=>$workorderdet['id']])
                                        ->select(['wo_item_overview.wo_category', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action'])
                                        ->join([
                                            'wo_item_overview'=>[
                                                'table'=>'customer_aircraft_wo_item_overviews',
                                                'type'=>'INNER',
                                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                        ])
                                        ->toArray();
            
            foreach($woitemdet as $key=>$rows){
                $signoffdet = [];
                if($postData['wo_report_type'] >= '24' && $postData['wo_report_type'] <= '27'){
                    $signoffdet = $this->getWOItemSignOff($rows['id']);
                }
                $woitemdet[$key]['signoff_details'] = $signoffdet;  
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getWOItemByGroupData($postData){
        $work_order_id = $postData['work_order_id'];
        
        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.cellular_phone', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];

            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');

            $woitemdet = $this->CustomerAircraftWOItems->find('all', ['order'=>'CustomerAircraftWOItems.wo_item_position'])
                                            ->where(['work_order_id'=>$workorderdet['id']])
                                            ->select(['wo_item_overview.wo_category', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action', 'wo_item_overview.estimated_hour'])
                                            ->join([
                                                'wo_item_overview'=>[
                                                    'table'=>'customer_aircraft_wo_item_overviews',
                                                    'type'=>'INNER',
                                                    'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                                ]
                                            ])
                                            ->toArray();
            foreach($woitemdet as $key=>$rows){
                $signoffdet = [];
                if($postData['wo_report_type'] >= '24' && $postData['wo_report_type'] <= '27'){
                    $signoffdet = $this->getWOItemSignOff($rows['id']);
                }
                $woitemdet[$key]['signoff_details'] = $signoffdet;  

                $woitemservicesdet = $this->CustomerAircraftWOItemServices->find('all')
                                                                ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$rows['id']])
                                                                ->select(['total_hrs_for_tech'])
                                                                ->toArray();
                $woitemdet[$key]['services_details'] = $woitemservicesdet;
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getWOMaintenanceData($postData){
        $work_order_id = $postData['work_order_id'];
        
        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'customers.cellular_phone', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_serial', 'aircraft.aircraft_engine_type', 'aircraft.aircraft_make_id', 'aircraft.aircraft_model_id', 'aircraft.aircraft_year', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            $workorderdet = $this->getMakeModelDataForWOPrint($workorderdet);
            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $maintoverview = $this->getMaintenanceOverviewData($workorderdet['aircraft']['id'], $engine_type);
            $workorderdet['maintenance_overview'] = $maintoverview;
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $woitemdet = $this->CustomerAircraftWOItems->find('all', ['order'=>'CustomerAircraftWOItems.wo_item_position'])
                                        ->where(['work_order_id'=>$workorderdet['id']])
                                        ->select(['CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action'])
                                        ->toArray();
                                        
            foreach($woitemdet as $key=>$rows){
                $signoffdet = $this->getWOItemSignOff($rows['id']);
                $woitemdet[$key]['signoff_details'] = $signoffdet;  
            }
            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getOSRProfileReportData($postData){
        $work_order_id = $postData['work_order_id'];
        
        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no'])->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $woitemdet = $this->CustomerAircraftWOItems->find('all', ['order'=>'CustomerAircraftWOItems.wo_item_position'])
                                        ->where(['CustomerAircraftWOItems.work_order_id'=>$workorderdet['id']])
                                        ->select(['CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'vendors.vendor_name', 'osr_infoes.osr_invoice_no', 'osr_infoes.osr_purchase_order_no', 'osr_infoes.id', 'osr_infoes.osr_part_number', 'osr_infoes.osr_labor_charge', 'osr_infoes.osr_parts_charge', 'osr_infoes.osr_vendor_labor_charges', 'osr_infoes.osr_vendor_part_charges'])
                                        ->join([
                                            'osr_infoes'=>[
                                                'table'=>'customer_aircraft_wo_osr_infoes',
                                                'type'=>'LEFT',
                                                'conditions'=>'osr_infoes.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                        ])
                                        ->join([
                                            'vendors' => [
                                                'table' => 'customer_aircraft_wo_osr_vendors',
                                                'type' => 'LEFT',
                                                'conditions' => 'vendors.id = osr_infoes.osr_repair_done_by',
                                            ]
                                        ])
                                        ->toArray();

            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getWOServicesList($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        if($postData['wo_report_type'] == '52'){
            $whereArr['aircraft.aircraft_default_department'] = $postData['wo_department_preview'];
        }

        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_default_department'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            
            $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
            if($postData['wo_report_type'] == '50'){
                $orderby = ['order'=>'wo_item_overview.wo_category ASC'];    
            }
            $itemwherecond = ['work_order_id'=>$workorderdet['id']];
            if($postData['wo_report_type'] == '55'){
                $itemwherecond['CustomerAircraftWOItems.wo_item_status'] = '1';
            }
            if($postData['wo_report_type'] == '57'){
                $itemwherecond['wo_item_overview.requires_rii'] = '1';
            }

            $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                        ->where($itemwherecond)
                                        ->select(['wo_item_overview.wo_category', 'CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action', 'users.full_name'])
                                        ->join([
                                            'wo_item_overview'=>[
                                                'table'=>'customer_aircraft_wo_item_overviews',
                                                'type'=>'INNER',
                                                'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                            ]
                                        ])
                                        ->join([
                                            'users' => [
                                                'table' => 'users',
                                                'type' => 'INNER',
                                                'conditions' => 'users.id = CustomerAircraftWOItems.added_by',
                                            ]
                                        ])
                                        ->toArray();
            
            if($postData['wo_report_type'] == '53' || $postData['wo_report_type'] == '54'){
                $woitemarr = [];
                $totalitemval = 0;
                foreach($woitemdet as $key=>$rows){
                    $woitemarr[$rows['wo_item_status']][] = $rows;
                    $totalitemval++;
                }
                $workorderdet['wo_item'] = $woitemarr;
                $workorderdet['totalitemval'] = $totalitemval;
            }else{
                foreach($woitemdet as $key=>$rows){
                    $signoffdet = $this->getWOItemSignOff($rows['id']);
                    $woitemdet[$key]['signoff_details'] = $signoffdet;  
                }
                
                $workorderdet['wo_item'] = $woitemdet;
            }
        }

        return $workorderdet;
    }

    public function getWOSignOffsList($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        $whereArr['CustomerAircraftWorkOrders.order_type'] = '1';
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_engine_type', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            
            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];

            $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            $workorderdet['logbook_value_overviews'] = $maintoverview;

            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            
            $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
            
            $itemwherecond = ['work_order_id'=>$workorderdet['id'], 'CustomerAircraftWOItems.wo_item_status'=>'3'];
            
            $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                        ->where($itemwherecond)
                                        ->select(['CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action'])->toArray();
            
            foreach($woitemdet as $key=>$rows){
                $signoffdet = $this->getWOItemSignOff($rows['id']);
                $woitemdet[$key]['signoff_details'] = $signoffdet;  
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getWOTechniciansOnItems($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id];
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_engine_type', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                  'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            
            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];

            $maintoverview = $this->getMaintenanceOverviewData($aircraft_id, $engine_type);
            $workorderdet['logbook_value_overviews'] = $maintoverview;

            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');
            
            $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
            
            $itemwherecond = ['work_order_id'=>$workorderdet['id']];
            if($postData['wo_report_type'] == '60'){
                $itemwherecond['CustomerAircraftWOItems.id'] = $postData['wo_item_id'];
            }
            $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                        ->where($itemwherecond)
                                        ->select(['CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action'])->toArray();
            
            foreach($woitemdet as $key=>$rows){
                $signoffdet = $this->getWOItemSignOff($rows['id'], '1');
                $woitemdet[$key]['signoff_details'] = $signoffdet;  
                $woitemservicesdet = $this->CustomerAircraftWOItemServices->find('all')
                                                                ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$rows['id']])
                                                                ->select(['users.full_name'])
                                                                ->join([
                                                                    'users' => [
                                                                        'table' => 'users',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'users.id = CustomerAircraftWOItemServices.repair_technician',
                                                                    ]
                                                                ])
                                                                ->toArray();
                $woitemdet[$key]['services_details'] = $woitemservicesdet;
            }
            
            $workorderdet['wo_item'] = $woitemdet;
        }

        return $workorderdet;
    }

    public function getWOTimeReportData($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id, 'CustomerAircraftWorkOrders.order_type' => '1'];
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['customers.customer_name', 'CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                  'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->join([
                                            'customers' => [
                                                'table' => 'inventory_customers',
                                                'type' => 'INNER',
                                                'conditions' => 'customers.id = CustomerAircraftWorkOrders.wo_customer_id',
                                            ]
                                        ])
                                        ->all()->toList();
        
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $conn = ConnectionManager::get('default');

            if($postData['wo_report_type'] == '62'){
                $query = "SELECT SUM(s.hrs_worked) as total_hrs_worked, SUM(s.service_overtime_hrs) as total_overtime_hrs, u.full_name FROM `customer_aircraft_wo_item_services` s join users u on s.repair_technician = u.id join customer_aircraft_wo_items i on i.id = s.wo_item_id WHERE i.work_order_id = '".$work_order_id."' group by s.repair_technician";
                $woitemservicesdet = $conn->execute($query)->fetchAll('assoc');

                $workorderdet['services_details'] = $woitemservicesdet;
            }else if($postData['wo_report_type'] == '70'){
                $startdate = date('Y-m-d', strtotime("-1 month"));
                $enddate = date('Y-m-d');
                if(!empty($postData['start_date']) && !empty($postData['end_date'])){
                    $startdate = str_replace('-', '/', $postData['start_date']);
                    $startdate = date('Y-m-d', strtotime($startdate));
                    $enddate = str_replace('-', '/', $postData['end_date']);
                    $enddate = date('Y-m-d', strtotime($enddate));
                }

                $query = "SELECT DATE(service_logs.login_time) as logindate, wo_item.wo_item_position, wo_item.wo_discrepancy, SUM(service_logs.hours_worked) as total_hrs_worked, u.full_name, services.repair_technician FROM aircraft_wo_item_service_logs as service_logs join customer_aircraft_wo_item_services as services on service_logs.wo_services_id = services.id join customer_aircraft_wo_items as wo_item on services.wo_item_id = wo_item.id join users u on services.repair_technician = u.id WHERE wo_item.work_order_id = '".$work_order_id."' and DATE(login_time)>='".$startdate."' and DATE(login_time) <= '".$enddate."' group by service_logs.wo_services_id, DATE(service_logs.login_time) order by DATE(service_logs.login_time), u.full_name";
                
                $servicelogsdet = $conn->execute($query)->fetchAll('assoc');
                
                $servicelogsarr = [];
                if(!empty($servicelogsdet)){
                    foreach($servicelogsdet as $servicelog){
                        $servicelogsarr[$servicelog['logindate']][$servicelog['full_name']][] = $servicelog;
                    }
                }

                $workorderdet['services_details'] = $servicelogsarr;
            }else{
                $orderby = ['order'=>'CustomerAircraftWOItems.wo_item_position'];
                
                $itemwherecond = ['work_order_id'=>$workorderdet['id']];
                if($postData['wo_report_type'] == '67'){
                    $itemwherecond['CustomerAircraftWOItems.id'] = $postData['wo_item_id'];
                }
                
                $woitemdet = $this->CustomerAircraftWOItems->find('all', $orderby)
                                            ->where($itemwherecond)
                                            ->select(['CustomerAircraftWOItems.wo_discrepancy', 'CustomerAircraftWOItems.item_notes', 'CustomerAircraftWOItems.wo_item_position', 'CustomerAircraftWOItems.id', 'CustomerAircraftWOItems.wo_item_status', 'CustomerAircraftWOItems.wo_corrective_action', 'CustomerAircraftWOItems.item_notes', 'wo_item_overview.estimated_hour'])
                                            ->join([
                                                'wo_item_overview'=>[
                                                    'table'=>'customer_aircraft_wo_item_overviews',
                                                    'type'=>'INNER',
                                                    'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                                ]
                                            ])
                                            ->toArray();
                
                $this->CustomerAircraftWOItemServices = $this->getController()->fetchTable('CustomerAircraftWOItemServices');
                foreach($woitemdet as $key=>$rows){
                    $signoffdet = $this->getWOItemSignOff($rows['id'], '1');
                    $woitemdet[$key]['signoff_details'] = $signoffdet;  
                    
                    if($postData['wo_report_type'] == '68'){
                        $query = "SELECT SUM(`total_hrs_for_tech`) as total_hrs_worked, SUM(`estimated_hrs_for_item`) as total_estimated_hrs, SUM(total_hrs_for_item) as total_hrs_for_items FROM `customer_aircraft_wo_item_services` WHERE wo_item_id = ".$rows['id']." and total_hrs_for_tech > '0' group by wo_item_id HAVING total_hrs_worked > total_estimated_hrs";
                    }else{
                        $query = "SELECT SUM(`hrs_worked`) as total_hrs_worked, SUM(`service_overtime_hrs`) as total_overtime_hrs, SUM(total_hrs_for_item) as total_hrs_for_items FROM `customer_aircraft_wo_item_services` WHERE wo_item_id = ".$rows['id']." group by wo_item_id";
                    }
                    $woitemservicesdet = $conn->execute($query)->fetch('assoc');
                    if(!empty($woitemservicesdet)){
                        $woitemdet[$key]['services_details'] = $woitemservicesdet;
                    }
                    if($postData['wo_report_type'] == '64' || $postData['wo_report_type'] == '66' || $postData['wo_report_type'] == '67'){
                        $serviceslogarr = $this->CustomerAircraftWOItemServices->find('all')
                                                                ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$rows['id']])
                                                                ->select(['users.full_name', 'services_logs.login_time', 'services_logs.logout_time', 'services_logs.hours_worked', 'services_logs.currently_on_overtime'])
                                                                ->join([
                                                                    'services_logs'=>[
                                                                        'table'=>'aircraft_wo_item_service_logs',
                                                                        'type'=>'INNER',
                                                                        'conditions'=>'services_logs.wo_services_id = CustomerAircraftWOItemServices.id'
                                                                    ]
                                                                ])
                                                                ->join([
                                                                    'users' => [
                                                                        'table' => 'users',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'users.id = CustomerAircraftWOItemServices.repair_technician',
                                                                    ]
                                                                ])
                                                                ->toArray();
                        $woitemdet[$key]['services_logs_details'] = $serviceslogarr;
                    }else if($postData['wo_report_type'] == '69'){
                        $servicesarr = $this->CustomerAircraftWOItemServices->find('all')
                                                                ->where(['CustomerAircraftWOItemServices.wo_item_id'=>$rows['id']])
                                                                ->select($this->CustomerAircraftWOItemServices)
                                                                ->select(['users.full_name'])
                                                                ->join([
                                                                    'users' => [
                                                                        'table' => 'users',
                                                                        'type' => 'LEFT',
                                                                        'conditions' => 'users.id = CustomerAircraftWOItemServices.repair_technician',
                                                                    ]
                                                                ])
                                                                ->toArray();
                        $woitemdet[$key]['services_details'] = $servicesarr;
                    }
                }
                $workorderdet['wo_item'] = $woitemdet;
            }
        }

        return $workorderdet;
    }

    public function getWOLogbookLabelsReportData($postData){
        $work_order_id = $postData['work_order_id'];

        $whereArr = ['CustomerAircraftWorkOrders.id'=> $work_order_id, 'CustomerAircraftWorkOrders.order_type' => '1'];
        
        $this->CustomerAircraftWorkOrders = $this->getController()->fetchTable('CustomerAircraftWorkOrders');
        
        $workorderdet = $this->CustomerAircraftWorkOrders->find('all');
        $workorderdet = $workorderdet->where($whereArr)
                                        ->select(['CustomerAircraftWorkOrders.id', 'CustomerAircraftWorkOrders.work_order_no', 'aircraft.aircraft_registration_number', 'aircraft.aircraft_engine_type', 'aircraft.aircraft_make_id', 'aircraft.aircraft_model_id', 'aircraft.aircraft_serial', 'aircraft.id'])
                                        ->join([
                                            'aircraft'=>[
                                                'table'=>'customer_otc_aircrafts',
                                                    'type'=>'INNER',
                                                'conditions'=>'aircraft.id = CustomerAircraftWorkOrders.aircraft_id'
                                            ]
                                        ])
                                        ->all()->toList();
        
        if(!empty($workorderdet)){
            $workorderdet = $workorderdet[0];
            $workorderdet = $this->getMakeModelDataForWOPrint($workorderdet);
            $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
            $engine_type = $workorderdet['aircraft']['aircraft_engine_type'];
            $aircraft_id = $workorderdet['aircraft']['id'];
            $aircraftmaintenance = $this->getMaintenanceData($aircraft_id, $engine_type, $postData['log_book_category']);
            $workorderdet['aircraftmaintenance'] = $aircraftmaintenance;
            
            $whereCondArr = ['wo_item_overview.log_book_category'=>$postData['log_book_category'], 'CustomerAircraftWOItems.work_order_id'=>$work_order_id];
            if(!empty($postData['label_wo_item_id'])){
                $whereCondArr['CustomerAircraftWOItems.id IN'] = $postData['label_wo_item_id'];
            }
            $woitems = $this->CustomerAircraftWOItems->find('all')
                                    ->where($whereCondArr)
                                    ->select(['CustomerAircraftWOItems.wo_corrective_action', 'wo_item_overview.donot_use_inlogbook'])
                                    ->join([
                                        'wo_item_overview'=>[
                                            'table'=>'customer_aircraft_wo_item_overviews',
                                            'type'=>'INNER',
                                            'conditions'=>'wo_item_overview.wo_item_id = CustomerAircraftWOItems.id'
                                        ]
                                    ])
                                    ->all()->toArray();
            
            $workorderdet['wo_item'] = $woitems;

            return $workorderdet;
        }
    }

    public function getLogbookValueOverviewData($engine_type, $work_order_id){
        if($engine_type != '5'){
            $this->CustomerAircraftWOLogBookValueOverviews = $this->getController()->fetchTable('CustomerAircraftWOLogBookValueOverviews');

            $maintoverview = $this->CustomerAircraftWOLogBookValueOverviews->find('all')->where(['work_order_id'=>$work_order_id])->select(['current_ac_tt', 'hobbs', 'actc'=>'current_ac_tach', 'warranty_date', 'airframe_lndgs'])->first();
        }else{
            $this->AircraftWOLogBookValueHelicopterOverviews = $this->getController()->fetchTable('AircraftWOLogBookValueHelicopterOverviews');

            $maintoverview = $this->AircraftWOLogBookValueHelicopterOverviews->find('all')->where(['work_order_id'=>$work_order_id])->select(['current_ac_tt' => 'actt', 'hobbs', 'actc', 'airframe_lndgs'=>'xtube_lndgs'])->first();
        }
        
        return $maintoverview;
    }

    public function getMaintenanceOverviewData($aircraft_id, $engine_type){
        if($engine_type != '5'){
            $this->CustomerAircraftMaintenanceOverviews = $this->getController()->fetchTable('CustomerAircraftMaintenanceOverviews');

            $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['current_ac_tt', 'hobbs', 'actc'=>'current_ac_tach', 'warranty_date', 'airframe_lndgs', 'use_hobbs'])->first();
        }else{
            $this->AircraftMaintenanceHelicopterOverviews = $this->getController()->fetchTable('AircraftMaintenanceHelicopterOverviews');

            $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['current_ac_tt' => 'actt', 'hobbs', 'actc', 'airframe_lndgs'=>'xtube_lndgs'])->first();
        }
        
        return $aircraftmaintoverview;
    }

    public function getMaintenanceData($aircraft_id, $engine_type, $logbook_category){
        $aircraftmaintengine = [];
        $aircraftmaintprops = [];

        if($engine_type != '5'){
            $this->CustomerAircraftMaintenanceOverviews = $this->getController()->fetchTable('CustomerAircraftMaintenanceOverviews');

            $aircraftmaintoverview = $this->CustomerAircraftMaintenanceOverviews->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['current_ac_tt', 'hobbs', 'actc'=>'current_ac_tach', 'warranty_date', 'airframe_lndgs', 'use_hobbs', 'airframe_tt'])->first();
            if($logbook_category != '1'){
                if($logbook_category == '3' || $logbook_category == '7' || $logbook_category == '4' || $logbook_category == '8'){
                    if($engine_type == '1' || $engine_type == '2'){
                        $this->CustomerAircraftMaintenanceEngines = $this->getController()->fetchTable('CustomerAircraftMaintenanceEngines');
                        $aircraftmaintengine = $this->CustomerAircraftMaintenanceEngines->find('all')->where(['aircraft_id'=>$aircraft_id])->select($this->CustomerAircraftMaintenanceEngines)->first(); 
                    }else{
                        $this->AircraftMaintenanceJetEngines = $this->getController()->fetchTable('AircraftMaintenanceJetEngines');
                        $aircraftmaintengine = $this->AircraftMaintenanceJetEngines->find('all')->where(['aircraft_id'=>$aircraft_id])->select($this->AircraftMaintenanceJetEngines)->first(); 
                    }
                }else if($logbook_category == '5' || $logbook_category == '9' || $logbook_category == '6' || $logbook_category == '10'){
                    $this->CustomerAircraftMaintenanceProps = $this->getController()->fetchTable('CustomerAircraftMaintenanceProps');
                    $aircraftmaintprops = $this->CustomerAircraftMaintenanceProps->find('all')->where(['aircraft_id'=>$aircraft_id])->select($this->CustomerAircraftMaintenanceProps)->first();
                }
            }
        }else{
            $this->AircraftMaintenanceHelicopterOverviews = $this->getController()->fetchTable('AircraftMaintenanceHelicopterOverviews');

            $aircraftmaintoverview = $this->AircraftMaintenanceHelicopterOverviews->find('all')->where(['aircraft_id'=>$aircraft_id])->select(['current_ac_tt' => 'actt', 'hobbs', 'actc', 'airframe_lndgs'=>'xtube_lndgs'])->first();
        }
        
        $aircraftmaintenace['aircraftmaintoverview'] = $aircraftmaintoverview;
        $aircraftmaintenace['aircraftmaintengine'] = $aircraftmaintengine;
        $aircraftmaintenace['aircraftmaintprops'] = $aircraftmaintprops;

        return $aircraftmaintenace;
    }

    public function getMakeModelDataForWOPrint($workorderdet){
        if(!empty($workorderdet['aircraft']['aircraft_make_id'])){
            $conn = ConnectionManager::get('default');
            $query = "select id, make from customer_otc_aircraft_make where id='".$workorderdet['aircraft']['aircraft_make_id']."'";
            $results = $conn->execute($query)->fetchAll('assoc');
            if(!empty($results)){
                $workorderdet['aircraft']['aircraft_make_id'] = $results[0]['make'];
            }
        }

        if(!empty($workorderdet['aircraft']['aircraft_model_id'])){
            $conn = ConnectionManager::get('default');
            $query = "select id, model from customer_otc_aircraft_model where id='".$workorderdet['aircraft']['aircraft_model_id']."'";
            $results = $conn->execute($query)->fetchAll('assoc');
            if(!empty($results)){
                $workorderdet['aircraft']['aircraft_model_id'] = $results[0]['model'];
            }
        }

        return $workorderdet;
    }

    public function getWorkOrderWarrantyList($work_order_id){
        $this->CustomerAircraftWOItems = $this->getController()->fetchTable('CustomerAircraftWOItems');
        $warrantylist = $this->CustomerAircraftWOItems->find('all')
                                                    ->where(['CustomerAircraftWOItems.work_order_id'=>$work_order_id, 'wo_item_overviews.warranty !='=>''])
                                                    ->select(['wo_item_overviews.warranty'])
                                                    ->distinct()
                                                    ->join([
                                                        'wo_item_overviews'=>[
                                                            'table'=>'customer_aircraft_wo_item_overviews',
                                                            'type'=>'INNER',
                                                            'conditions'=>'wo_item_overviews.wo_item_id = CustomerAircraftWOItems.id'
                                                        ]
                                                    ]);

        return $warrantylist;
    }

    public function getAircraftWOViewOptionMiscFuelCharges($misc_charges_id){
        $this->CustomerAircraftWOOptionMiscFuelCharges = $this->getController()->fetchTable('CustomerAircraftWOOptionMiscFuelCharges');
        $miscfuelchargeslist = $this->CustomerAircraftWOOptionMiscFuelCharges->find('all')
                                                    ->where(['misc_charges_id'=>$misc_charges_id])
                                                    ->select($this->CustomerAircraftWOOptionMiscFuelCharges);

        return $miscfuelchargeslist;
    }

    public function getSettings(){
        $this->Settings = $this->getController()->fetchTable('Settings');
        $settings = $this->Settings->find('all')
                                    ->where(['status'=>'1'])
                                    ->select($this->Settings)
                                    ->order(['Settings.id'=>'DESC'])
                                    ->first();

        return $settings;
    }

    public function getWOItemPartDetails($wo_item_id){
        $this->CustomerAircraftWOItemParts = $this->getController()->fetchTable('CustomerAircraftWOItemParts');
        $woitempartsdet = $this->CustomerAircraftWOItemParts->find('all')
                                                                ->where(['CustomerAircraftWOItemParts.wo_item_id'=>$wo_item_id])
                                                                ->select($this->CustomerAircraftWOItemParts)
                                                                ->toArray();
        
        return $woitempartsdet;
    }
}