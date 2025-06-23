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
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class AircraftHistoryComponent extends Component {
    public array $components = ['Authentication.Authentication', 'Inventory'];

    protected \App\Model\Table\PlaneHistoriesTable $PlaneHistories;
    protected \App\Model\Table\AirframeComponentHistoriesTable $AirframeComponentHistories;
    protected \App\Model\Table\SubComponentHistoriesTable $SubComponentHistories;
    protected \App\Model\Table\AirframeComponentTimeHistoriesTable $AirframeComponentTimeHistories;
    protected \App\Model\Table\AirframeComponentPartHistoriesTable $AirframeComponentPartHistories;
    protected \App\Model\Table\AtaCodeHistoriesTable $AtaCodeHistories;
    protected \App\Model\Table\DispositionHistoriesTable $DispositionHistories;
    protected \App\Model\Table\AdsbStatusHistoriesTable $AdsbStatusHistories;
    protected \App\Model\Table\PilotHistoriesTable $PilotHistories;
    protected \App\Model\Table\PilotsTable $Pilots;
    protected \App\Model\Table\DutyAssignmentsTable $DutyAssignments;
    protected \App\Model\Table\PilotCertificatesTable $PilotCertificates;
    protected \App\Model\Table\PilotTrainingsTable $PilotTrainings;
    protected \App\Model\Table\PilotCheckingsTable $PilotCheckings;
    protected \App\Model\Table\UsersTable $Users;
    protected \App\Model\Table\PlanesTable $Planes;
    protected \App\Model\Table\FlightlogHistoriesTable $FlightlogHistories;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function savePlaneHistory($entity){
        $planeHistoryModel = $this->getController()->fetchTable('PlaneHistories');
        
        $planeHistory = $planeHistoryModel->newEmptyEntity();
        $planeHistory->plane_id = $entity->id;
        
        $planeHistory->title = 'Plane `'.$entity->plane_name.'` was created.';
        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $planeHistory->user_id = $authUserData['id'];
        $planeHistory->description = $description;

        $planeHistoryModel->save($planeHistory);    
    }

    public function saveAirframeComponentHistory($entity){
        $airframeComponentHistoryModel = $this->getController()->fetchTable('AirframeComponentHistories');

        $aircraftComponentHistory = $airframeComponentHistoryModel->newEmptyEntity();
        $aircraftComponentHistory->plane_id = $entity->plane_id;
        $aircraftComponentHistory->airframe_component_id = $entity->id;
        
        $aircraftComponentHistory->title = 'Airframe Component was created.';
        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $aircraftComponentHistory->user_id = $authUserData['id'];
        $aircraftComponentHistory->description = $description;

        $airframeComponentHistoryModel->save($aircraftComponentHistory);
    }

    public function saveSubComponentHistory($entity){
        $subComponentHistoryModel = $this->getController()->fetchTable('SubComponentHistories');

        $subComponentHistory = $subComponentHistoryModel->newEmptyEntity();
        $subComponentHistory->plane_id = $entity->plane_id;
        $subComponentHistory->sub_component_id = $entity->id;
        
        $subComponentHistory->title = 'Sub Component `'.$entity->title.'` was created.';
        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $subComponentHistory->user_id = $authUserData['id'];
        $subComponentHistory->description = $description;

        $subComponentHistoryModel->save($subComponentHistory);
    }

    public function saveSubComponent2History($entity){
        $subComponentHistoryModel = $this->getController()->fetchTable('SubComponentHistories');

        $subComponentHistory = $subComponentHistoryModel->newEmptyEntity();
        $subComponentHistory->sub_component_id = $entity->id;
        
        $subComponentHistory->title = 'Sub Component2 `'.$entity->title.'` was created.';
        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $subComponentHistory->user_id = $authUserData['id'];
        $subComponentHistory->description = $description;

        $subComponentHistoryModel->save($subComponentHistory);
    }

    public function saveAirframeComponentTimeHistory($entity){
        $airframeComponentTimeHistoryModel = $this->getController()->fetchTable('AirframeComponentTimeHistories');

        $aircraftComponentTimeHistory = $airframeComponentTimeHistoryModel->newEmptyEntity();
        $aircraftComponentTimeHistory->plane_id = $entity->plane_id;
        $aircraftComponentTimeHistory->airframe_component_time_id = $entity->id;
        
        $aircraftComponentTimeHistory->title = 'Airframe Component Time was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $aircraftComponentTimeHistory->user_id = $authUserData['id'];
        $aircraftComponentTimeHistory->description = $description;

        $airframeComponentTimeHistoryModel->save($aircraftComponentTimeHistory);
    }

    public function saveAirframeComponentPartHistory($entity){
        $airframeComponentPartHistoryModel = $this->getController()->fetchTable('AirframeComponentPartHistories');

        $aircraftComponentPartHistory = $airframeComponentPartHistoryModel->newEmptyEntity();
        $aircraftComponentPartHistory->plane_id = $entity['plane_id'];
        $aircraftComponentPartHistory->airframe_component_part_id = $entity['airframe_component_part_id'];
        
        $aircraftComponentPartHistory->title = 'Airframe Component Part was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $aircraftComponentPartHistory->user_id = $authUserData['id'];
        $aircraftComponentPartHistory->description = $description;

        $airframeComponentPartHistoryModel->save($aircraftComponentPartHistory);
    }

    public function saveATACodeHistory($entity){
        $ataCodeHistoryModel = $this->getController()->fetchTable('AtaCodeHistories');

        $ataCodeHistory = $ataCodeHistoryModel->newEmptyEntity();
        $ataCodeHistory->ata_code_id = $entity->id;
        
        $ataCodeHistory->title = 'ATA Code `'.$entity->ata_code.'` was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $ataCodeHistory->user_id = $authUserData['id'];
        $ataCodeHistory->description = $description;

        $ataCodeHistoryModel->save($ataCodeHistory);
    }

    public function saveDispositionHistory($entity){
        $dispositionHistoryModel = $this->getController()->fetchTable('DispositionHistories');

        $dispositionHistory = $dispositionHistoryModel->newEmptyEntity();
        $dispositionHistory->disposition_id = $entity->id;
        
        $dispositionHistory->title = 'Disposition `'.$entity->title.'` was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $dispositionHistory->user_id = $authUserData['id'];
        $dispositionHistory->description = $description;

        $dispositionHistoryModel->save($dispositionHistory);
    }

    public function saveAdsbStatusHistory($entity){
        $adsbStatusHistoryModel = $this->getController()->fetchTable('AdsbStatusHistories');

        $adsbStatusHistory = $adsbStatusHistoryModel->newEmptyEntity();
        $adsbStatusHistory->adsb_status_id = $entity->id;
        
        $adsbStatusHistory->title = 'AD/SB Class `'.$entity->title.'` was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $adsbStatusHistory->user_id = $authUserData['id'];
        $adsbStatusHistory->description = $description;

        $adsbStatusHistoryModel->save($adsbStatusHistory);
    }

    public function savePositionHistory($entity){
        $positionHistoryModel = $this->getController()->fetchTable('PositionHistories');

        $positionHistory = $positionHistoryModel->newEmptyEntity();
        $positionHistory->position_id = $entity->id;
        
        $positionHistory->title = 'Position `'.$entity->title.'` was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $positionHistory->user_id = $authUserData['id'];
        $positionHistory->description = $description;

        $positionHistoryModel->save($positionHistory);
    }

    public function savePilotHistory($entity){
        $pilotHistoryModel = $this->getController()->fetchTable('PilotHistories');

        $pilotHistory = $pilotHistoryModel->newEmptyEntity();
        $pilotHistory->pilot_id = $entity['pilot_id'];
        
        $pilotname = $entity['first_name'];
        $pilotname .= !empty($entity['middle_name']) ? ' '.$entity['middle_name'] : '';
        $pilotname .= ' '.$entity['last_name'];

        $pilotHistory->title = 'Pilot `'.$pilotname.'` was created.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $pilotHistory->user_id = $authUserData['id'];
        $pilotHistory->description = $description;

        $pilotHistoryModel->save($pilotHistory);
    }

    public function updatePilotHistory($entity){
        if(!empty($entity['pilot_id'])){
            $pilotesModel = $this->getController()->fetchTable('Pilots');
            $pilotes = $pilotesModel->get($entity['pilot_id']);
            $authUserData = $this->Authentication->getResult()->getData();

            $pilotHistoryModel = $this->getController()->fetchTable('PilotHistories');
            $dutyAssignmentsModel = $this->getController()->fetchTable('DutyAssignments');
            $dutyAssignments = $dutyAssignmentsModel->find('all')->where(['pilot_id'=>$entity['pilot_id']])->order(['id'=>'DESC'])->first();
            
            $pilotHistory = $pilotHistoryModel->newEmptyEntity();
            $pilotHistory->pilot_id = $entity['pilot_id'];

            $pilotname = $entity['first_name'];
            $pilotname .= !empty($entity['middle_name']) ? ' '.$entity['middle_name'] : '';
            $pilotname .= ' '.$entity['last_name'];
            
            $pilotHistory->title = 'Pilot `'.$pilotname.'` was updated.';
            
            if(!empty(@$dutyAssignments->modified)){
                $modified_from = str_replace('-', '/', @$dutyAssignments->modified);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity['modified'])){
                $modified_to = str_replace('-', '/', $entity['modified']);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity['pilot_id'] != @$dutyAssignments->pilot_id){
                $description .= 'Pilot was changed from "'.@$dutyAssignments->pilot_id.'" to "'.$entity['pilot_id'].'".<br/>';
            }
            if(isset($entity['director_operation']) && $entity['director_operation'] != @$dutyAssignments->director_operation){
                $description .= 'Director of Operations was changed from "'.@$dutyAssignments->director_operation.'" to "'.$entity['director_operation'].'".<br/>';
            }
            if(isset($entity['chief_pilot']) && $entity['chief_pilot'] != @$dutyAssignments->chief_pilot){
                $description .= 'Chief Pilot was changed from "'.@$dutyAssignments->chief_pilot.'" to "'.$entity['chief_pilot'].'".<br/>';
            }
            if(isset($entity['director_maintenance']) && $entity['director_maintenance'] != @$dutyAssignments->director_maintenance){
                $description .= 'Director of Maintenance was changed from "'.@$dutyAssignments->director_maintenance.'" to "'.$entity['director_maintenance'].'".<br/>';
            }
            if(isset($entity['PIC_type1']) && $entity['PIC_type1'] != @$dutyAssignments->PIC_type1){
                $description .= 'PIC type1 was changed from "'.@$dutyAssignments->PIC_type1.'" to "'.$entity['PIC_type1'].'".<br/>';
            }
            if(isset($entity['PIC_designation1']) && $entity['PIC_designation1'] != @$dutyAssignments->PIC_designation1){
                $description .= 'PIC Designation1 was changed from "'.@$dutyAssignments->PIC_designation1.'" to "'.$entity['PIC_designation1'].'".<br/>';
            }
            if(isset($entity['PIC_date_assigned1']) && $entity['PIC_date_assigned1'] != @$dutyAssignments->PIC_date_assigned1){
                $description .= 'PIC Date Assigned1 was changed from "'.@$dutyAssignments->PIC_date_assigned1.'" to "'.$entity['PIC_date_assigned1'].'".<br/>';
            }
            if(isset($entity['PIC_date_unassigned1']) && $entity['PIC_date_unassigned1'] != @$dutyAssignments->PIC_date_unassigned1){
                $description .= 'PIC Date Unassigned1 was changed from "'.@$dutyAssignments->PIC_date_unassigned1.'" to "'.$entity['PIC_date_unassigned1'].'".<br/>';
            }
            if(isset($entity['PIC_type2']) && $entity['PIC_type2'] != @$dutyAssignments->PIC_type2){
                $description .= 'PIC Type2 was changed from "'.@$dutyAssignments->PIC_type2.'" to "'.$entity['PIC_type2'].'".<br/>';
            }
            if(isset($entity['PIC_designation2']) && $entity['PIC_designation2'] != @$dutyAssignments->PIC_designation2){
                $description .= 'PIC Designation2 was changed from "'.@$dutyAssignments->PIC_designation2.'" to "'.$entity['PIC_designation2'].'".<br/>';
            }
            if(isset($entity['PIC_date_assigned2']) && $entity['PIC_date_assigned2'] != @$dutyAssignments->PIC_date_assigned2){
                $description .= 'PIC Date Assigned2 was changed from "'.@$dutyAssignments->PIC_date_assigned2.'" to "'.$entity['PIC_date_assigned2'].'".<br/>';
            }
            if(isset($entity['PIC_date_unassigned2']) && $entity['PIC_date_unassigned2'] != @$dutyAssignments->PIC_date_unassigned2){
                $description .= 'PIC Date Unassigned2 was changed from "'.@$dutyAssignments->PIC_date_unassigned2.'" to "'.$entity['PIC_date_unassigned2'].'".<br/>';
            }
            if(isset($entity['SIC_type1']) && $entity['SIC_type1'] != @$dutyAssignments->SIC_type1){
                $description .= 'SIC Type1 was changed from "'.@$dutyAssignments->SIC_type1.'" to "'.$entity['SIC_type1'].'".<br/>';
            }
            if(isset($entity['SIC_designation1']) && $entity['SIC_designation1'] != @$dutyAssignments->SIC_designation1){
                $description .= 'SIC Designation1 was changed from "'.@$dutyAssignments->SIC_designation1.'" to "'.$entity['SIC_designation1'].'".<br/>';
            }
            if(isset($entity['SIC_date_assigned1']) && $entity['SIC_date_assigned1'] != @$dutyAssignments->SIC_date_assigned1){
                $description .= 'SIC Date Assigned1 was changed from "'.@$dutyAssignments->SIC_date_assigned1.'" to "'.$entity['SIC_date_assigned1'].'".<br/>';
            }
            if(isset($entity['SIC_date_unassigned1']) && $entity['SIC_date_unassigned1'] != @$dutyAssignments->SIC_date_unassigned1){
                $description .= 'SIC Date Unassigned1 was changed from "'.@$dutyAssignments->SIC_date_unassigned1.'" to "'.$entity['SIC_date_unassigned1'].'".<br/>';
            }
            if(isset($entity['SIC_type2']) && $entity['SIC_type2'] != @$dutyAssignments->SIC_type2){
                $description .= 'SIC Type2 was changed from "'.@$dutyAssignments->SIC_type2.'" to "'.$entity['SIC_type2'].'".<br/>';
            }
            if(isset($entity['SIC_designation2']) && $entity['SIC_designation2'] != @$dutyAssignments->SIC_designation2){
                $description .= 'SIC Designation2 was changed from "'.@$dutyAssignments->SIC_designation2.'" to "'.$entity['SIC_designation2'].'".<br/>';
            }
            if(isset($entity['SIC_date_assigned2']) && strtotime($entity['SIC_date_assigned2']) != strtotime(@$dutyAssignments->SIC_date_assigned2)){
                $description .= 'SIC Date Assigned2 was changed from "'.@$dutyAssignments->SIC_date_assigned2.'" to "'.$entity['SIC_date_assigned2'].'".<br/>';
            }
            if(isset($entity['SIC_date_unassigned2']) && $entity['SIC_date_unassigned2'] != @$dutyAssignments->SIC_date_unassigned2){
                $description .= 'SIC Date Unassigned2 was changed from "'.@$dutyAssignments->SIC_date_unassigned2.'" to "'.$entity['SIC_date_unassigned2'].'".<br/>';
            }
            if(isset($entity['GI_type1']) && $entity['GI_type1'] != @$dutyAssignments->GI_type1){
                $description .= 'Ground Instructor Type1 was changed from "'.@$dutyAssignments->GI_type1.'" to "'.$entity['GI_type1'].'".<br/>';
            }
            if(isset($entity['GI_designation1']) && strtotime($entity['GI_designation1']) != strtotime(@$dutyAssignments->GI_designation1)){
                $description .= 'Ground Instructor Designation1 was changed from "'.@$dutyAssignments->GI_designation1.'" to "'.$entity['GI_designation1'].'".<br/>';
            }
            if(isset($entity['GI_date_assigned1']) && $entity['GI_date_assigned1'] != @$dutyAssignments->GI_date_assigned1){
                $description .= 'Ground Instructor Date Assigned1 was changed from "'.@$dutyAssignments->GI_date_assigned1.'" to "'.$entity['GI_date_assigned1'].'".<br/>';
            }
            if(isset($entity['GI_date_unassigned1']) && $entity['GI_date_unassigned1'] != @$dutyAssignments->GI_date_unassigned1){
                $description .= 'Ground Instructor Date Unassigned1 was changed from "'.@$dutyAssignments->GI_date_unassigned1.'" to "'.$entity['GI_date_unassigned1'].'".<br/>';
            }
            if(isset($entity['GI_type2']) && $entity['GI_type2'] != @$dutyAssignments->GI_type2){
                $description .= 'Ground Instructor Type2 was changed from "'.@$dutyAssignments->GI_type2.'" to "'.$entity['GI_type2'].'".<br/>';
            }
            if(isset($entity['GI_designation2']) && strtotime($entity['GI_designation2']) != strtotime(@$dutyAssignments->GI_designation2)){
                $description .= 'Ground Instructor Designation2 was changed from "'.@$dutyAssignments->GI_designation2.'" to "'.$entity['GI_designation2'].'".<br/>';
            }
            if(isset($entity['GI_date_assigned2']) && $entity['GI_date_assigned2'] != @$dutyAssignments->GI_date_assigned2){
                $description .= 'Ground Instructor Date Assigned2 was changed from "'.@$dutyAssignments->GI_date_assigned2.'" to "'.$entity['GI_date_assigned2'].'".<br/>';
            }
            if(isset($entity['GI_date_unassigned2']) && $entity['GI_date_unassigned2'] != @$dutyAssignments->GI_date_unassigned2){
                $description .= 'Ground Instructor Date Unassigned2 was changed from "'.@$dutyAssignments->GI_date_unassigned2.'" to "'.$entity['GI_date_unassigned2'].'".<br/>';
            }
            if(isset($entity['FI_type1']) && strtotime($entity['FI_type1']) != strtotime(@$dutyAssignments->FI_type1)){
                $description .= 'Flight Instructor Type1 was changed from "'.@$dutyAssignments->FI_type1.'" to "'.$entity['FI_type1'].'".<br/>';
            }
            if(isset($entity['FI_designation1']) && $entity['FI_designation1'] != @$dutyAssignments->FI_designation1){
                $description .= 'Flight Instructor Designation1 was changed from "'.@$dutyAssignments->FI_designation1.'" to "'.$entity['FI_designation1'].'".<br/>';
            }
            if(isset($entity['FI_date_assigned1']) && $entity['FI_date_assigned1'] != @$dutyAssignments->FI_date_assigned1){
                $description .= 'Flight Instructor Date Assigned1 was changed from "'.@$dutyAssignments->FI_date_assigned1.'" to "'.$entity['FI_date_assigned1'].'".<br/>';
            }
            if(isset($entity['FI_date_unassigned1']) && strtotime($entity['FI_date_unassigned1']) != strtotime(@$dutyAssignments->FI_date_unassigned1)){
                $description .= 'Flight Instructor Date Unassigned1 was changed from "'.@$dutyAssignments->FI_date_unassigned1.'" to "'.$entity['FI_date_unassigned1'].'".<br/>';
            }
            if(isset($entity['FI_type2']) && strtotime($entity['FI_type2']) != strtotime(@$dutyAssignments->FI_type2)){
                $description .= 'Flight Instructor Type2 was changed from "'.@$dutyAssignments->FI_type2.'" to "'.$entity['FI_type2'].'".<br/>';
            }
            if(isset($entity['FI_designation2']) && $entity['FI_designation2'] != @$dutyAssignments->FI_designation2){
                $description .= 'Flight Instructor Designation2 was changed from "'.@$dutyAssignments->FI_designation2.'" to "'.$entity['FI_designation2'].'".<br/>';
            }
            if(isset($entity['FI_date_assigned2']) && $entity['FI_date_assigned2'] != @$dutyAssignments->FI_date_assigned2){
                $description .= 'Flight Instructor Date Assigned2 was changed from "'.@$dutyAssignments->FI_date_assigned2.'" to "'.$entity['FI_date_assigned2'].'".<br/>';
            }
            if(isset($entity['FI_date_unassigned2']) && strtotime($entity['FI_date_unassigned2']) != strtotime(@$dutyAssignments->FI_date_unassigned2)){
                $description .= 'Flight Instructor Date Unassigned2 was changed from "'.@$dutyAssignments->FI_date_unassigned2.'" to "'.$entity['FI_date_unassigned2'].'".<br/>';
            }
            if(isset($entity['CA_type1']) && $entity['CA_type1'] != @$dutyAssignments->CA_type1){
                $description .= 'Check Airmen Type1 was changed from "'.@$dutyAssignments->CA_type1.'" to "'.$entity['CA_type1'].'".<br/>';
            }
            if(isset($entity['CA_designation1']) && $entity['CA_designation1'] != @$dutyAssignments->CA_designation1){
                $description .= 'Check Airmen Designation1 was changed from "'.@$dutyAssignments->CA_designation1.'" to "'.$entity['CA_designation1'].'".<br/>';
            }
            if(isset($entity['CA_date_assigned1']) && strtotime($entity['CA_date_assigned1']) != strtotime(@$dutyAssignments->CA_date_assigned1)){
                $description .= 'Check Airmen Date Assigned1 was changed from "'.@$dutyAssignments->CA_date_assigned1.'" to "'.$entity['CA_date_assigned1'].'".<br/>';
            }
            if(isset($entity['CA_date_unassigned1']) && $entity['CA_date_unassigned1'] != @$dutyAssignments->CA_date_unassigned1){
                $description .= 'Check Airmen Date Unassigned1 was changed from "'.@$dutyAssignments->CA_date_unassigned1.'" to "'.$entity['CA_date_unassigned1'].'".<br/>';
            }
            if(isset($entity['CA_type2']) && $entity['CA_type2'] != @$dutyAssignments->CA_type2){
                $description .= 'Check Airmen Type2 was changed from "'.@$dutyAssignments->CA_type2.'" to "'.$entity['CA_type2'].'".<br/>';
            }
            if(isset($entity['CA_designation2']) && $entity['CA_designation2'] != @$dutyAssignments->CA_designation2){
                $description .= 'Check Airmen Designation2 was changed from "'.@$dutyAssignments->CA_designation2.'" to "'.$entity['CA_designation2'].'".<br/>';
            }
            if(isset($entity['CA_date_assigned2']) && strtotime($entity['CA_date_assigned2']) != strtotime(@$dutyAssignments->CA_date_assigned2)){
                $description .= 'Check Airmen Date Assigned2 was changed from "'.@$dutyAssignments->CA_date_assigned2.'" to "'.$entity['CA_date_assigned2'].'".<br/>';
            }
            if(isset($entity['CA_date_unassigned2']) && $entity['CA_date_unassigned2'] != @$dutyAssignments->CA_date_unassigned2){
                $description .= 'Check Airmen Date Unassigned2 was changed from "'.@$dutyAssignments->CA_date_unassigned2.'" to "'.$entity['CA_date_unassigned2'].'".<br/>';
            }

            $pilotCertificatesModel = $this->getController()->fetchTable('PilotCertificates');
            $pilotCertificates = $pilotCertificatesModel->find('all')->where(['pilot_id'=>$entity['pilot_id']])->order(['id'=>'DESC'])->first();

            if(isset($entity['pilot_id']) && $entity['pilot_id'] != $pilotCertificates->pilot_id){
                $description .= 'Pilot was changed from "'.$pilotCertificates->pilot_id.'" to "'.$entity['pilot_id'].'".<br/>';
            }
            if(isset($entity['medical_class']) && $entity['medical_class'] != $pilotCertificates->medical_class){
                $description .= 'Medical Class was changed from "'.$pilotCertificates->medical_class.'" to "'.$entity['medical_class'].'".<br/>';
            }
            if(isset($entity['medical_limitation']) && $entity['medical_limitation'] != $pilotCertificates->medical_limitation){
                $description .= 'Medical Limitations was changed from "'.$pilotCertificates->medical_limitation.'" to "'.$entity['medical_limitation'].'".<br/>';
            }
            if(isset($entity['medical_frequency']) && $entity['medical_frequency'] != $pilotCertificates->medical_frequency){
                $description .= 'Medical Frequency was changed from "'.$pilotCertificates->medical_frequency.'" to "'.$entity['medical_frequency'].'".<br/>';
            }
            if(isset($entity['medical_last_completed']) && strtotime($entity['medical_last_completed']) != strtotime($pilotCertificates->medical_last_completed)){
                $description .= 'Medical Last Completed was changed from "'.$pilotCertificates->medical_last_completed.'" to "'.$entity['medical_last_completed'].'".<br/>';
            }
            if(isset($entity['medical_nextdue']) && !empty($pilotCertificates->medical_nextdue) && !empty($entity['medical_nextdue']) && strtotime($entity['medical_nextdue']) != strtotime($pilotCertificates->medical_nextdue)){
                $description .= 'Medical Next Due was changed from "'.$pilotCertificates->medical_nextdue.'" to "'.$entity['medical_nextdue'].'".<br/>';
            }
            if(isset($entity['medical_nextdue_status']) && $entity['medical_nextdue_status'] != $pilotCertificates->medical_nextdue_status){
                $description .= 'Medical Next Due Status was changed from "'.$pilotCertificates->medical_nextdue_status.'" to "'.$entity['medical_nextdue_status'].'".<br/>';
            }
            if(isset($entity['passport_frequency']) && $entity['passport_frequency'] != $pilotCertificates->passport_frequency){
                $description .= 'Passport Frequency was changed from "'.$pilotCertificates->passport_frequency.'" to "'.$entity['passport_frequency'].'".<br/>';
            }
            if(isset($entity['passport_last_completed']) && strtotime($entity['passport_last_completed']) != strtotime($pilotCertificates->passport_last_completed)){
                $description .= 'Passport Last Completed was changed from "'.$pilotCertificates->passport_last_completed.'" to "'.$entity['passport_last_completed'].'".<br/>';
            }
            if(isset($entity['passport_nextdue']) && !empty($entity['passport_nextdue']) && !empty($pilotCertificates->passport_nextdue) && strtotime($entity['passport_nextdue']) != strtotime($pilotCertificates->passport_nextdue)){
                $description .= 'Passport Next Due was changed from "'.$pilotCertificates->passport_nextdue.'" to "'.$entity['passport_nextdue'].'".<br/>';
            }
            if(isset($entity['passport_nextdue_status']) && $entity['passport_nextdue_status'] != $pilotCertificates->passport_nextdue_status){
                $description .= 'Passport Next Due Status was changed from "'.$pilotCertificates->passport_nextdue_status.'" to "'.$entity['passport_nextdue_status'].'".<br/>';
            }
            if(isset($entity['DL_frequency']) && $entity['DL_frequency'] != $pilotCertificates->DL_frequency){
                $description .= 'Drivers License Frequency was changed from "'.$pilotCertificates->DL_frequency.'" to "'.$entity['DL_frequency'].'".<br/>';
            }
            if(isset($entity['DL_last_completed']) && strtotime($entity['DL_last_completed']) != strtotime($pilotCertificates->DL_last_completed)){
                $description .= 'Drivers License Last Completed was changed from "'.$pilotCertificates->DL_last_completed.'" to "'.$entity['DL_last_completed'].'".<br/>';
            }
            if(isset($entity['DL_nextdue']) && !empty($entity['DL_nextdue']) && !empty($pilotCertificates->DL_nextdue) && strtotime($entity['DL_nextdue']) != strtotime($pilotCertificates->DL_nextdue)){
                $description .= 'Drivers License Next Due was changed from "'.$pilotCertificates->DL_nextdue.'" to "'.$entity['DL_nextdue'].'".<br/>';
            }
            if(isset($entity['DL_nextdue_status']) && $entity['DL_nextdue_status'] != $pilotCertificates->DL_nextdue_status){
                $description .= 'Drivers License Next Due Status was changed from "'.$pilotCertificates->DL_nextdue_status.'" to "'.$entity['DL_nextdue_status'].'".<br/>';
            }
            if(isset($entity['TPC_frequency']) && $entity['TPC_frequency'] != $pilotCertificates->TPC_frequency){
                $description .= 'Temporary Pilot Certificates Frequency was changed from "'.$pilotCertificates->TPC_frequency.'" to "'.$entity['TPC_frequency'].'".<br/>';
            }
            if(isset($entity['TPC_last_completed']) && strtotime($entity['TPC_last_completed']) != strtotime($pilotCertificates->TPC_last_completed)){
                $description .= 'Temporary Pilot Certificate Last Completed was changed from "'.$pilotCertificates->TPC_last_completed.'" to "'.$entity['TPC_last_completed'].'".<br/>';
            }
            if(isset($entity['TPC_nextdue']) && !empty($entity['TPC_nextdue']) && !empty($pilotCertificates->TPC_nextdue) && strtotime($entity['TPC_nextdue']) != strtotime($pilotCertificates->TPC_nextdue)){
                $description .= 'Temporary Pilot Certificate Next Due was changed from "'.$pilotCertificates->TPC_nextdue.'" to "'.$entity['TPC_nextdue'].'".<br/>';
            }
            if(isset($entity['TPC_nextdue_status']) && $entity['TPC_nextdue_status'] != $pilotCertificates->TPC_nextdue_status){
                $description .= 'Temporary Pilot Certificate Next Due Status was changed from "'.$pilotCertificates->TPC_nextdue_status.'" to "'.$entity['TPC_nextdue_status'].'".<br/>';
            }

            $pilotTrainingsModel = $this->getController()->fetchTable('PilotTrainings');
            $pilotTrainings = $pilotTrainingsModel->find('all')->where(['pilot_id'=>$entity['pilot_id']])->order(['id'=>'DESC'])->first();

            if(isset($entity['pilot_id']) && $entity['pilot_id'] != $pilotTrainings->pilot_id){
                $description .= 'Pilot was changed from "'.$pilotTrainings->pilot_id.'" to "'.$entity['pilot_id'].'".<br/>';
            }
            if(isset($entity['AFT_check']) && $entity['AFT_check'] != $pilotTrainings->AFT_check){
                $description .= 'Aircraft Flight Training was changed from "'.$pilotTrainings->AFT_check.'" to "'.$entity['AFT_check'].'".<br/>';
            }
            if(isset($entity['AFT_month']) && $entity['AFT_month'] != $pilotTrainings->AFT_month){
                $description .= 'Aircraft Flight Training Base Month was changed from "'.$pilotTrainings->AFT_month.'" to "'.$entity['AFT_month'].'".<br/>';
            }
            if(isset($entity['AFT_frequency']) && $entity['AFT_frequency'] != $pilotTrainings->AFT_frequency){
                $description .= 'Aircraft Flight Training Frequency was changed from "'.$pilotTrainings->AFT_frequency.'" to "'.$entity['AFT_frequency'].'".<br/>';
            }
            if(isset($entity['AFT_last_completed']) && $entity['AFT_last_completed'] != $pilotTrainings->AFT_last_completed){
                $description .= 'Aircraft Flight Training Last Completed was changed from "'.$pilotTrainings->AFT_last_completed.'" to "'.$entity['AFT_last_completed'].'".<br/>';
            }
            
            if(isset($entity['AFT_nextdue']) && strtotime($entity['AFT_nextdue']) != strtotime($pilotTrainings->AFT_nextdue)){
                $description .= 'Aircraft Flight Training Next Due was changed from "'.$pilotTrainings->AFT_nextdue.'" to "'.$entity['AFT_nextdue'].'".<br/>';
            }
            if(isset($entity['AFT_nextdue_status']) && $entity['AFT_nextdue_status'] != $pilotTrainings->AFT_nextdue_status){
                $description .= 'Aircraft Flight Training Next Due Status was changed from "'.$pilotTrainings->AFT_nextdue_status.'" to "'.$entity['AFT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['EDT_check']) && $entity['EDT_check'] != $pilotTrainings->EDT_check){
                $description .= 'Emergency Drill Training was changed from "'.$pilotTrainings->EDT_check.'" to "'.$entity['EDT_check'].'".<br/>';
            }
            if(isset($entity['EDT_month']) && $entity['EDT_month'] != $pilotTrainings->EDT_month){
                $description .= 'Emergency Drill Training Base Month was changed from "'.$pilotTrainings->EDT_month.'" to "'.$entity['EDT_month'].'".<br/>';
            }
            if(isset($entity['EDT_frequency']) && $entity['EDT_frequency'] != $pilotTrainings->EDT_frequency){
                $description .= 'Emergency Drill Training Frequency was changed from "'.$pilotTrainings->EDT_frequency.'" to "'.$entity['EDT_frequency'].'".<br/>';
            }
            if(isset($entity['EDT_last_completed']) && $entity['EDT_last_completed'] != $pilotTrainings->EDT_last_completed){
                $description .= 'Emergency Drill Training Last Completed was changed from "'.$pilotTrainings->EDT_last_completed.'" to "'.$entity['EDT_last_completed'].'".<br/>';
            }
            if(isset($entity['EDT_nextdue']) && strtotime($entity['EDT_nextdue']) != strtotime($pilotTrainings->EDT_nextdue)){
                $description .= 'Emergency Drill Training Next Due was changed from "'.$pilotTrainings->EDT_nextdue.'" to "'.$entity['EDT_nextdue'].'".<br/>';
            }
            if(isset($entity['EDT_nextdue_status']) && $entity['EDT_nextdue_status'] != $pilotTrainings->EDT_nextdue_status){
                $description .= 'Emergency Drill Training Next Due Status was changed from "'.$pilotTrainings->EDT_nextdue_status.'" to "'.$entity['EDT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['CWOT_check']) && $entity['CWOT_check'] != $pilotTrainings->CWOT_check){
                $description .= 'Cold Weather Operations Training was changed from "'.$pilotTrainings->CWOT_check.'" to "'.$entity['CWOT_check'].'".<br/>';
            }
            if(isset($entity['CWOT_month']) && $entity['CWOT_month'] != $pilotTrainings->CWOT_month){
                $description .= 'Cold Weather Operations Training Base Month was changed from "'.$pilotTrainings->CWOT_month.'" to "'.$entity['CWOT_month'].'".<br/>';
            }
            if(isset($entity['CWOT_frequency']) && $entity['CWOT_frequency'] != $pilotTrainings->CWOT_frequency){
                $description .= 'Cold Weather Operations Training Frequency was changed from "'.$pilotTrainings->CWOT_frequency.'" to "'.$entity['CWOT_frequency'].'".<br/>';
            }
            if(isset($entity['CWOT_last_completed']) && $entity['CWOT_last_completed'] != $pilotTrainings->CWOT_last_completed){
                $description .= 'Cold Weather Operations Training Last Completed was changed from "'.$pilotTrainings->CWOT_last_completed.'" to "'.$entity['CWOT_last_completed'].'".<br/>';
            }
            if(isset($entity['CWOT_nextdue']) && strtotime($entity['CWOT_nextdue']) != strtotime($pilotTrainings->CWOT_nextdue)){
                $description .= 'Cold Weather Operations Training Next Due was changed from "'.$pilotTrainings->CWOT_nextdue.'" to "'.$entity['CWOT_nextdue'].'".<br/>';
            }
            if(isset($entity['CWOT_nextdue_status']) && $entity['CWOT_nextdue_status'] != $pilotTrainings->CWOT_nextdue_status){
                $description .= 'Cold Weather Operations Training Next Due Status was changed from "'.$pilotTrainings->CWOT_nextdue_status.'" to "'.$entity['CWOT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['CRM_check']) && $entity['CRM_check'] != $pilotTrainings->CRM_check){
                $description .= 'Crew Resource Management was changed from "'.$pilotTrainings->CRM_check.'" to "'.$entity['CRM_check'].'".<br/>';
            }
            if(isset($entity['CRM_month']) && $entity['CRM_month'] != $pilotTrainings->CRM_month){
                $description .= 'Crew Resource Management Base Month was changed from "'.$pilotTrainings->CRM_month.'" to "'.$entity['CRM_month'].'".<br/>';
            }
            if(isset($entity['CRM_frequency']) && $entity['CRM_frequency'] != $pilotTrainings->CRM_frequency){
                $description .= 'Crew Resource Management Frequency was changed from "'.$pilotTrainings->CRM_frequency.'" to "'.$entity['CRM_frequency'].'".<br/>';
            }
            if(isset($entity['CRM_last_completed']) && $entity['CRM_last_completed'] != $pilotTrainings->CRM_last_completed){
                $description .= 'Crew Resource Management Last Completed was changed from "'.$pilotTrainings->CRM_last_completed.'" to "'.$entity['CRM_last_completed'].'".<br/>';
            }
            if(isset($entity['CRM_nextdue']) && strtotime($entity['CRM_nextdue']) != strtotime($pilotTrainings->CRM_nextdue)){
                $description .= 'Crew Resource Management Next Due was changed from "'.$pilotTrainings->CRM_nextdue.'" to "'.$entity['CRM_nextdue'].'".<br/>';
            }
            if(isset($entity['CRM_nextdue_status']) && $entity['CRM_nextdue_status'] != $pilotTrainings->CRM_nextdue_status){
                $description .= 'Crew Resource Management Next Due Status was changed from "'.$pilotTrainings->CRM_nextdue_status.'" to "'.$entity['CRM_nextdue_status'].'".<br/>';
            }
            if(isset($entity['EFB_check']) && $entity['EFB_check'] != $pilotTrainings->EFB_check){
                $description .= 'Electronic Flight Bag was changed from "'.$pilotTrainings->EFB_check.'" to "'.$entity['EFB_check'].'".<br/>';
            }
            if(isset($entity['EFB_month']) && $entity['EFB_month'] != $pilotTrainings->EFB_month){
                $description .= 'Electronic Flight Bag Base Month was changed from "'.$pilotTrainings->EFB_month.'" to "'.$entity['EFB_month'].'".<br/>';
            }
            if(isset($entity['EFB_frequency']) && $entity['EFB_frequency'] != $pilotTrainings->EFB_frequency){
                $description .= 'Electronic Flight Bag Frequency was changed from "'.$pilotTrainings->EFB_frequency.'" to "'.$entity['EFB_frequency'].'".<br/>';
            }
            if(isset($entity['EFB_last_completed']) && $entity['EFB_last_completed'] != $pilotTrainings->EFB_last_completed){
                $description .= 'Electronic Flight Bag Last Completed was changed from "'.$pilotTrainings->EFB_last_completed.'" to "'.$entity['EFB_last_completed'].'".<br/>';
            }
            if(isset($entity['EFB_nextdue']) && strtotime($entity['EFB_nextdue']) != strtotime($pilotTrainings->EFB_nextdue)){
                $description .= 'Electronic Flight Bag Next Due was changed from "'.$pilotTrainings->EFB_nextdue.'" to "'.$entity['EFB_nextdue'].'".<br/>';
            }
            if(isset($entity['EFB_nextdue_status']) && $entity['EFB_nextdue_status'] != $pilotTrainings->EFB_nextdue_status){
                $description .= 'Electronic Flight Bag Next Due Status was changed from "'.$pilotTrainings->EFB_nextdue_status.'" to "'.$entity['EFB_nextdue_status'].'".<br/>';
            }
            if(isset($entity['EGT_check']) && $entity['EGT_check'] != $pilotTrainings->EGT_check){
                $description .= 'Emergency Ground Training was changed from "'.$pilotTrainings->EGT_check.'" to "'.$entity['EGT_check'].'".<br/>';
            }
            if(isset($entity['EGT_month']) && $entity['EGT_month'] != $pilotTrainings->EGT_month){
                $description .= 'Emergency Ground Training Base Month was changed from "'.$pilotTrainings->EGT_month.'" to "'.$entity['EGT_month'].'".<br/>';
            }
            if(isset($entity['EGT_frequency']) && $entity['EGT_frequency'] != $pilotTrainings->EGT_frequency){
                $description .= 'Emergency Ground Training Frequency was changed from "'.$pilotTrainings->EGT_frequency.'" to "'.$entity['EGT_frequency'].'".<br/>';
            }
            if(isset($entity['EGT_last_completed']) && $entity['EGT_last_completed'] != $pilotTrainings->EGT_last_completed){
                $description .= 'Emergency Ground Training Last Completed was changed from "'.$pilotTrainings->EGT_last_completed.'" to "'.$entity['EGT_last_completed'].'".<br/>';
            }
            if(isset($entity['EGT_nextdue']) && strtotime($entity['EGT_nextdue']) != strtotime($pilotTrainings->EGT_nextdue)){
                $description .= 'Emergency Ground Training Next Due was changed from "'.$pilotTrainings->EGT_nextdue.'" to "'.$entity['EGT_nextdue'].'".<br/>';
            }
            if(isset($entity['EGT_nextdue_status']) && $entity['EGT_nextdue_status'] != $pilotTrainings->EGT_nextdue_status){
                $description .= 'Emergency Ground Training Next Due was changed from "'.$pilotTrainings->EGT_nextdue_status.'" to "'.$entity['EGT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['GIT_check']) && $entity['GIT_check'] != $pilotTrainings->GIT_check){
                $description .= 'Global/International Training was changed from "'.$pilotTrainings->GIT_check.'" to "'.$entity['GIT_check'].'".<br/>';
            }
            if(isset($entity['GIT_month']) && $entity['GIT_month'] != $pilotTrainings->GIT_month){
                $description .= 'Global/International Training Base Month was changed from "'.$pilotTrainings->GIT_month.'" to "'.$entity['GIT_month'].'".<br/>';
            }
            if(isset($entity['GIT_frequency']) && $entity['GIT_frequency'] != $pilotTrainings->GIT_frequency){
                $description .= 'Global/International Training Frequency was changed from "'.$pilotTrainings->GIT_frequency.'" to "'.$entity['GIT_frequency'].'".<br/>';
            }
            if(isset($entity['GIT_last_completed']) && $entity['GIT_last_completed'] != $pilotTrainings->GIT_last_completed){
                $description .= 'Global/International Training Last Completed was changed from "'.$pilotTrainings->GIT_last_completed.'" to "'.$entity['GIT_last_completed'].'".<br/>';
            }
            if(isset($entity['GIT_nextdue']) && strtotime($entity['GIT_nextdue']) != strtotime($pilotTrainings->GIT_nextdue)){
                $description .= 'Global/International Training Next Due was changed from "'.$pilotTrainings->GIT_nextdue.'" to "'.$entity['GIT_nextdue'].'".<br/>';
            }
            if(isset($entity['GIT_nextdue_status']) && $entity['GIT_nextdue_status'] != $pilotTrainings->GIT_nextdue_status){
                $description .= 'Global/International Training Next Due was changed from "'.$pilotTrainings->GIT_nextdue_status.'" to "'.$entity['GIT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['Hz_check']) && $entity['Hz_check'] != $pilotTrainings->Hz_check){
                $description .= 'Hazmat was changed from "'.$pilotTrainings->Hz_check.'" to "'.$entity['Hz_check'].'".<br/>';
            }
            if(isset($entity['Hz_month']) && $entity['Hz_month'] != $pilotTrainings->Hz_month){
                $description .= 'Hazmat Base Month was changed from "'.$pilotTrainings->Hz_month.'" to "'.$entity['Hz_month'].'".<br/>';
            }
            if(isset($entity['Hz_frequency']) && $entity['Hz_frequency'] != $pilotTrainings->Hz_frequency){
                $description .= 'Hazmat Frequency was changed from "'.$pilotTrainings->Hz_frequency.'" to "'.$entity['Hz_frequency'].'".<br/>';
            }
            if(isset($entity['Hz_last_completed']) && $entity['Hz_last_completed'] != $pilotTrainings->Hz_last_completed){
                $description .= 'Hazmat Last Completed was changed from "'.$pilotTrainings->Hz_last_completed.'" to "'.$entity['Hz_last_completed'].'".<br/>';
            }
            if(isset($entity['Hz_nextdue']) && strtotime($entity['Hz_nextdue']) != strtotime($pilotTrainings->Hz_nextdue)){
                $description .= 'Hazmat Next Due was changed from "'.$pilotTrainings->Hz_nextdue.'" to "'.$entity['Hz_nextdue'].'".<br/>';
            }
            if(isset($entity['Hz_nextdue_status']) && $entity['Hz_nextdue_status'] != $pilotTrainings->Hz_nextdue_status){
                $description .= 'Hazmat Next Due was changed from "'.$pilotTrainings->Hz_nextdue_status.'" to "'.$entity['Hz_nextdue_status'].'".<br/>';
            }
            if(isset($entity['IR_check']) && $entity['IR_check'] != $pilotTrainings->IR_check){
                $description .= 'Indoctrination/Recurrent 135 was changed from "'.$pilotTrainings->IR_check.'" to "'.$entity['IR_check'].'".<br/>';
            }
            if(isset($entity['IR_month']) && $entity['IR_month'] != $pilotTrainings->IR_month){
                $description .= 'Indoctrination/Recurrent 135 Base Month was changed from "'.$pilotTrainings->IR_month.'" to "'.$entity['IR_month'].'".<br/>';
            }
            if(isset($entity['IR_frequency']) && $entity['IR_frequency'] != $pilotTrainings->IR_frequency){
                $description .= 'Indoctrination/Recurrent 135 Frequency was changed from "'.$pilotTrainings->IR_frequency.'" to "'.$entity['IR_frequency'].'".<br/>';
            }
            if(isset($entity['IR_last_completed']) && $entity['IR_last_completed'] != $pilotTrainings->IR_last_completed){
                $description .= 'Indoctrination/Recurrent 135 Last Completed was changed from "'.$pilotTrainings->IR_last_completed.'" to "'.$entity['IR_last_completed'].'".<br/>';
            }
            if(isset($entity['IR_nextdue']) && strtotime($entity['IR_nextdue']) != strtotime($pilotTrainings->IR_nextdue)){
                $description .= 'Indoctrination/Recurrent 135 Next Due was changed from "'.$pilotTrainings->IR_nextdue.'" to "'.$entity['IR_nextdue'].'".<br/>';
            }
            if(isset($entity['IR_nextdue_status']) && $entity['IR_nextdue_status'] != $pilotTrainings->IR_nextdue_status){
                $description .= 'Indoctrination/Recurrent 135 Next Due was changed from "'.$pilotTrainings->IR_nextdue_status.'" to "'.$entity['IR_nextdue_status'].'".<br/>';
            }
            if(isset($entity['IRG_check']) && $entity['IRG_check'] != $pilotTrainings->IRG_check){
                $description .= 'Initial or Recurrent Ground was changed from "'.$pilotTrainings->IRG_check.'" to "'.$entity['IRG_check'].'".<br/>';
            }
            if(isset($entity['IRG_month']) && $entity['IRG_month'] != $pilotTrainings->IRG_month){
                $description .= 'Initial or Recurrent Ground Base Month was changed from "'.$pilotTrainings->IRG_month.'" to "'.$entity['IRG_month'].'".<br/>';
            }
            if(isset($entity['IRG_frequency']) && $entity['IRG_frequency'] != $pilotTrainings->IRG_frequency){
                $description .= 'Initial or Recurrent Ground Frequency was changed from "'.$pilotTrainings->IRG_frequency.'" to "'.$entity['IRG_frequency'].'".<br/>';
            }
            if(isset($entity['IRG_last_completed']) && $entity['IRG_last_completed'] != $pilotTrainings->IRG_last_completed){
                $description .= 'Initial or Recurrent Ground Last Completed was changed from "'.$pilotTrainings->IRG_last_completed.'" to "'.$entity['IRG_last_completed'].'".<br/>';
            }
            if(isset($entity['IRG_nextdue']) && strtotime($entity['IRG_nextdue']) != strtotime($pilotTrainings->IRG_nextdue)){
                $description .= 'Initial or Recurrent Ground Next Due was changed from "'.$pilotTrainings->IRG_nextdue.'" to "'.$entity['IRG_nextdue'].'".<br/>';
            }
            if(isset($entity['IRG_nextdue_status']) && $entity['IRG_nextdue_status'] != $pilotTrainings->IRG_nextdue_status){
                $description .= 'Initial or Recurrent Ground Next Due was changed from "'.$pilotTrainings->IRG_nextdue_status.'" to "'.$entity['IRG_nextdue_status'].'".<br/>';
            }
            if(isset($entity['ICAT_check']) && $entity['ICAT_check'] != $pilotTrainings->ICAT_check){
                $description .= 'Instructor/Check Airman Training was changed from "'.$pilotTrainings->ICAT_check.'" to "'.$entity['ICAT_check'].'".<br/>';
            }
            if(isset($entity['ICAT_month']) && $entity['ICAT_month'] != $pilotTrainings->ICAT_month){
                $description .= 'Instructor/Check Airman Training Base Month was changed from "'.$pilotTrainings->ICAT_month.'" to "'.$entity['ICAT_month'].'".<br/>';
            }
            if(isset($entity['ICAT_frequency']) && $entity['ICAT_frequency'] != $pilotTrainings->ICAT_frequency){
                $description .= 'Instructor/Check Airman Training Frequency was changed from "'.$pilotTrainings->ICAT_frequency.'" to "'.$entity['ICAT_frequency'].'".<br/>';
            }
            if(isset($entity['ICAT_last_completed']) && $entity['ICAT_last_completed'] != $pilotTrainings->ICAT_last_completed){
                $description .= 'Instructor/Check Airman Training Last Completed was changed from "'.$pilotTrainings->ICAT_last_completed.'" to "'.$entity['ICAT_last_completed'].'".<br/>';
            }
            if(isset($entity['ICAT_nextdue']) && strtotime($entity['ICAT_nextdue']) != strtotime($pilotTrainings->ICAT_nextdue)){
                $description .= 'Instructor/Check Airman Training Next Due was changed from "'.$pilotTrainings->ICAT_nextdue.'" to "'.$entity['ICAT_nextdue'].'".<br/>';
            }
            if(isset($entity['ICAT_nextdue_status']) && $entity['ICAT_nextdue_status'] != $pilotTrainings->ICAT_nextdue_status){
                $description .= 'Instructor/Check Airman Training Next Due Status was changed from "'.$pilotTrainings->ICAT_nextdue_status.'" to "'.$entity['ICAT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['LBFT_check']) && $entity['LBFT_check'] != $pilotTrainings->LBFT_check){
                $description .= 'Lithium Battery Fire Training was changed from "'.$pilotTrainings->LBFT_check.'" to "'.$entity['LBFT_check'].'".<br/>';
            }
            if(isset($entity['LBFT_month']) && $entity['LBFT_month'] != $pilotTrainings->LBFT_month){
                $description .= 'Lithium Battery Fire Training Base Month was changed from "'.$pilotTrainings->LBFT_month.'" to "'.$entity['LBFT_month'].'".<br/>';
            }
            if(isset($entity['LBFT_frequency']) && $entity['LBFT_frequency'] != $pilotTrainings->LBFT_frequency){
                $description .= 'Lithium Battery Fire Training Frequency was changed from "'.$pilotTrainings->LBFT_frequency.'" to "'.$entity['LBFT_frequency'].'".<br/>';
            }
            if(isset($entity['LBFT_last_completed']) && $entity['LBFT_last_completed'] != $pilotTrainings->LBFT_last_completed){
                $description .= 'Lithium Battery Fire Training Last Completed was changed from "'.$pilotTrainings->LBFT_last_completed.'" to "'.$entity['LBFT_last_completed'].'".<br/>';
            }
            if(isset($entity['LBFT_nextdue']) && strtotime($entity['LBFT_nextdue']) != strtotime($pilotTrainings->LBFT_nextdue)){
                $description .= 'Lithium Battery Fire Training Next Due was changed from "'.$pilotTrainings->LBFT_nextdue.'" to "'.$entity['LBFT_nextdue'].'".<br/>';
            }
            if(isset($entity['LBFT_nextdue_status']) && $entity['LBFT_nextdue_status'] != $pilotTrainings->LBFT_nextdue_status){
                $description .= 'Lithium Battery Fire Training Next Due Status was changed from "'.$pilotTrainings->LBFT_nextdue_status.'" to "'.$entity['LBFT_nextdue_status'].'".<br/>';
            }
            if(isset($entity['RVSM_check']) && $entity['RVSM_check'] != $pilotTrainings->RVSM_check){
                $description .= 'RVSM Training was changed from "'.$pilotTrainings->RVSM_check.'" to "'.$entity['RVSM_check'].'".<br/>';
            }
            if(isset($entity['RVSM_month']) && $entity['RVSM_month'] != $pilotTrainings->RVSM_month){
                $description .= 'RVSM Training Base Month was changed from "'.$pilotTrainings->RVSM_month.'" to "'.$entity['RVSM_month'].'".<br/>';
            }
            if(isset($entity['RVSM_frequency']) && strtotime($entity['RVSM_frequency']) != strtotime($pilotTrainings->RVSM_frequency)){
                $description .= 'RVSM Training Frequency was changed from "'.$pilotTrainings->RVSM_frequency.'" to "'.$entity['RVSM_frequency'].'".<br/>';
            }
            if(isset($entity['RVSM_last_completed']) && $entity['RVSM_last_completed'] != $pilotTrainings->RVSM_last_completed){
                $description .= 'RVSM Training Last Completed was changed from "'.$pilotTrainings->RVSM_last_completed.'" to "'.$entity['RVSM_last_completed'].'".<br/>';
            }
            if(isset($entity['RVSM_nextdue']) && strtotime($entity['RVSM_nextdue']) != strtotime($pilotTrainings->RVSM_nextdue)){
                $description .= 'RVSM Training Next Due was changed from "'.$pilotTrainings->RVSM_nextdue.'" to "'.$entity['RVSM_nextdue'].'".<br/>';
            }
            if(isset($entity['RVSM_nextdue_status']) && $entity['RVSM_nextdue_status'] != $pilotTrainings->RVSM_nextdue_status){
                $description .= 'RVSM Training Next Due Status was changed from "'.$pilotTrainings->RVSM_nextdue_status.'" to "'.$entity['RVSM_nextdue_status'].'".<br/>';
            }
            if(isset($entity['ST_check']) && $entity['ST_check'] != $pilotTrainings->ST_check){
                $description .= 'Security Training was changed from "'.$pilotTrainings->ST_check.'" to "'.$entity['ST_check'].'".<br/>';
            }
            if(isset($entity['ST_month']) && $entity['ST_month'] != $pilotTrainings->ST_month){
                $description .= 'Security Training Base Month was changed from "'.$pilotTrainings->ST_month.'" to "'.$entity['ST_month'].'".<br/>';
            }
            if(isset($entity['ST_frequency']) && $entity['ST_frequency'] != $pilotTrainings->ST_frequency){
                $description .= 'Security Training Frequency was changed from "'.$pilotTrainings->ST_frequency.'" to "'.$entity['ST_frequency'].'".<br/>';
            }
            if(isset($entity['ST_last_completed']) && $entity['ST_last_completed'] != $pilotTrainings->ST_last_completed){
                $description .= 'Security Training Last Completed was changed from "'.$pilotTrainings->ST_last_completed.'" to "'.$entity['ST_last_completed'].'".<br/>';
            }
            if(isset($entity['ST_nextdue']) && strtotime($entity['ST_nextdue']) != strtotime($pilotTrainings->ST_nextdue)){
                $description .= 'Security Training Next Due was changed from "'.$pilotTrainings->ST_nextdue.'" to "'.$entity['ST_nextdue'].'".<br/>';
            }
            if(isset($entity['ST_nextdue_status']) && $entity['ST_nextdue_status'] != $pilotTrainings->ST_nextdue_status){
                $description .= 'Security Training Next Due Status was changed from "'.$pilotTrainings->ST_nextdue_status.'" to "'.$entity['ST_nextdue_status'].'".<br/>';
            }

            $pilotCheckingsModel = $this->getController()->fetchTable('PilotCheckings');
            $pilotCheckings = $pilotCheckingsModel->find('all')->where(['pilot_id'=>$entity['pilot_id']])->order(['id'=>'DESC'])->first();

            if($entity['pilot_id'] != $pilotCheckings->pilot_id){
                $description .= 'Pilot was changed from "'.$pilotCheckings->pilot_id.'" to "'.$entity['pilot_id'].'".<br/>';
            }
            if(isset($entity['AS_check']) && $entity['AS_check'] != $pilotCheckings->AS_check){
                $description .= 'Aircraft Specific was changed from "'.$pilotCheckings->AS_check.'" to "'.$entity['AS_check'].'".<br/>';
            }
            if(isset($entity['AS_month']) && $entity['AS_month'] != $pilotCheckings->AS_month){
                $description .= 'Aircraft Specific Month was changed from "'.$pilotCheckings->AS_month.'" to "'.$entity['AS_month'].'".<br/>';
            }
            if(isset($entity['AS_frequency']) && $entity['AS_frequency'] != $pilotCheckings->AS_frequency){
                $description .= 'Aircraft Specific Frequency was changed from "'.$pilotCheckings->AS_frequency.'" to "'.$entity['AS_frequency'].'".<br/>';
            }
            if(isset($entity['AS_last_completed']) && $entity['AS_last_completed'] != $pilotCheckings->AS_last_completed){
                $description .= 'Aircraft Specific Last Completed was changed from "'.$pilotCheckings->AS_last_completed.'" to "'.$entity['AS_last_completed'].'".<br/>';
            }
            if(isset($entity['AS_nextdue']) && strtotime($entity['AS_nextdue']) != strtotime($pilotCheckings->AS_nextdue)){
                $description .= 'Aircraft Specific Next Due was changed from "'.$pilotCheckings->AS_nextdue.'" to "'.$entity['AS_nextdue'].'".<br/>';
            }
            if(isset($entity['AS_nextdue_status']) && $entity['AS_nextdue_status'] != $pilotCheckings->AS_nextdue_status){
                $description .= 'Aircraft Specific Next Due Status was changed from "'.$pilotCheckings->AS_nextdue_status.'" to "'.$entity['AS_nextdue_status'].'".<br/>';
            }
            if(isset($entity['ICC_check']) && $entity['ICC_check'] != $pilotCheckings->ICC_check){
                $description .= 'Instrument Currency Check was changed from "'.$pilotCheckings->ICC_check.'" to "'.$entity['ICC_check'].'".<br/>';
            }
            if(isset($entity['ICC_month']) && $entity['ICC_month'] != $pilotCheckings->ICC_month){
                $description .= 'Instrument Currency Check Month was changed from "'.$pilotCheckings->ICC_month.'" to "'.$entity['ICC_month'].'".<br/>';
            }
            if(isset($entity['ICC_frequency']) && $entity['ICC_frequency'] != $pilotCheckings->ICC_frequency){
                $description .= 'Instrument Currency Check Frequency was changed from "'.$pilotCheckings->ICC_frequency.'" to "'.$entity['ICC_frequency'].'".<br/>';
            }
            if(isset($entity['ICC_last_completed']) && $entity['ICC_last_completed'] != $pilotCheckings->ICC_last_completed){
                $description .= 'Instrument Currency Check Last Completed was changed from "'.$pilotCheckings->ICC_last_completed.'" to "'.$entity['ICC_last_completed'].'".<br/>';
            }
            if(isset($entity['ICC_nextdue']) && strtotime($entity['ICC_nextdue']) != strtotime($pilotCheckings->ICC_nextdue)){
                $description .= 'Instrument Currency Check Next Due was changed from "'.$pilotCheckings->ICC_nextdue.'" to "'.$entity['ICC_nextdue'].'".<br/>';
            }
            if(isset($entity['ICC_nextdue_status']) && $entity['ICC_nextdue_status'] != $pilotCheckings->ICC_nextdue_status){
                $description .= 'Instrument Currency Check Next Due Status was changed from "'.$pilotCheckings->ICC_nextdue_status.'" to "'.$entity['ICC_nextdue_status'].'".<br/>';
            }
            if(isset($entity['OW_check']) && $entity['OW_check'] != $pilotCheckings->OW_check){
                $description .= '135.293 (a) 1, 4-8 Oral/Written was changed from "'.$pilotCheckings->OW_check.'" to "'.$entity['OW_check'].'".<br/>';
            }
            if(isset($entity['OW_month']) && $entity['OW_month'] != $pilotCheckings->OW_month){
                $description .= '135.293 (a) 1, 4-8 Oral/Written Month was changed from "'.$pilotCheckings->OW_month.'" to "'.$entity['OW_month'].'".<br/>';
            }
            if(isset($entity['OW_frequency']) && $entity['OW_frequency'] != $pilotCheckings->OW_frequency){
                $description .= '135.293 (a) 1, 4-8 Oral/Written Frequency was changed from "'.$pilotCheckings->OW_frequency.'" to "'.$entity['OW_frequency'].'".<br/>';
            }
            if(isset($entity['OW_last_completed']) && $entity['OW_last_completed'] != $pilotCheckings->OW_last_completed){
                $description .= '135.293 (a) 1, 4-8 Oral/Written Last Completed was changed from "'.$pilotCheckings->OW_last_completed.'" to "'.$entity['OW_last_completed'].'".<br/>';
            }
            if(isset($entity['OW_nextdue']) && strtotime($entity['OW_nextdue']) != strtotime($pilotCheckings->OW_nextdue)){
                $description .= '135.293 (a) 1, 4-8 Oral/Written Next Due was changed from "'.$pilotCheckings->OW_nextdue.'" to "'.$entity['OW_nextdue'].'".<br/>';
            }
            if(isset($entity['LC_check']) && $entity['LC_check'] != $pilotCheckings->LC_check){
                $description .= '135.299 Line Check was changed from "'.$pilotCheckings->LC_check.'" to "'.$entity['LC_check'].'".<br/>';
            }
            if(isset($entity['LC_month']) && $entity['LC_month'] != $pilotCheckings->LC_month){
                $description .= '135.299 Line Check Month was changed from "'.$pilotCheckings->LC_month.'" to "'.$entity['LC_month'].'".<br/>';
            }
            if(isset($entity['LC_frequency']) && $entity['LC_frequency'] != $pilotCheckings->LC_frequency){
                $description .= '135.299 Line Check Frequency was changed from "'.$pilotCheckings->LC_frequency.'" to "'.$entity['LC_frequency'].'".<br/>';
            }
            if(isset($entity['LC_last_completed']) && $entity['LC_last_completed'] != $pilotCheckings->LC_last_completed){
                $description .= '135.299 Line Check Last Completed was changed from "'.$pilotCheckings->LC_last_completed.'" to "'.$entity['LC_last_completed'].'".<br/>';
            }
            if(isset($entity['LC_nextdue']) && strtotime($entity['LC_nextdue']) != isset($pilotCheckings->LC_nextdue)){
                $description .= '135.299 Line Check Next Due was changed from "'.$pilotCheckings->LC_nextdue.'" to "'.$entity['LC_nextdue'].'".<br/>';
            }
            if(isset($entity['LC_nextdue_status']) && $entity['LC_nextdue_status'] != $pilotCheckings->LC_nextdue_status){
                $description .= '135.299 Line Check Next Due Status was changed from "'.$pilotCheckings->LC_nextdue_status.'" to "'.$entity['LC_nextdue_status'].'".<br/>';
            }
            if(isset($entity['APC_check']) && $entity['APC_check'] != $pilotCheckings->APC_check){
                $description .= 'Auto Pilot Check (Single Pilot) was changed from "'.$pilotCheckings->APC_check.'" to "'.$entity['APC_check'].'".<br/>';
            }
            if(isset($entity['APC_month']) && $entity['APC_month'] != $pilotCheckings->APC_month){
                $description .= 'Auto Pilot Check (Single Pilot) Month was changed from "'.$pilotCheckings->APC_month.'" to "'.$entity['APC_month'].'".<br/>';
            }
            if(isset($entity['APC_frequency']) && $entity['APC_frequency'] != $pilotCheckings->APC_frequency){
                $description .= 'Auto Pilot Check (Single Pilot) Frequency was changed from "'.$pilotCheckings->APC_frequency.'" to "'.$entity['APC_frequency'].'".<br/>';
            }
            if(isset($entity['APC_last_completed']) && $entity['APC_last_completed'] != $pilotCheckings->APC_last_completed){
                $description .= 'Auto Pilot Check (Single Pilot) Last Completed was changed from "'.$pilotCheckings->APC_last_completed.'" to "'.$entity['APC_last_completed'].'".<br/>';
            }
            if(isset($entity['APC_nextdue']) && strtotime($entity['APC_nextdue']) != strtotime($pilotCheckings->APC_nextdue)){
                $description .= 'Auto Pilot Check (Single Pilot) Next Due was changed from "'.$pilotCheckings->APC_nextdue.'" to "'.$entity['APC_nextdue'].'".<br/>';
            }
            if(isset($entity['APC_nextdue_status']) && $entity['APC_nextdue_status'] != $pilotCheckings->APC_nextdue_status){
                $description .= 'Auto Pilot Check (Single Pilot) Next Due Status was changed from "'.$pilotCheckings->APC_nextdue_status.'" to "'.$entity['APC_nextdue_status'].'".<br/>';
            }
            if(isset($entity['IO_check']) && $entity['IO_check'] != $pilotCheckings->IO_check){
                $description .= 'Instructor Observation was changed from "'.$pilotCheckings->IO_check.'" to "'.$entity['IO_check'].'".<br/>';
            }
            if(isset($entity['IO_month']) && $entity['IO_month'] != $pilotCheckings->IO_month){
                $description .= 'Instructor Observation Month was changed from "'.$pilotCheckings->IO_month.'" to "'.$entity['IO_month'].'".<br/>';
            }
            if(isset($entity['IO_frequency']) && $entity['IO_frequency'] != $pilotCheckings->IO_frequency){
                $description .= 'Instructor Observation Frequency was changed from "'.$pilotCheckings->IO_frequency.'" to "'.$entity['IO_frequency'].'".<br/>';
            }
            if(isset($entity['IO_last_completed']) && $entity['IO_last_completed'] != $pilotCheckings->IO_last_completed){
                $description .= 'Instructor Observation Last Completed was changed from "'.$pilotCheckings->IO_last_completed.'" to "'.$entity['IO_last_completed'].'".<br/>';
            }
            if(isset($entity['IO_nextdue']) && strtotime($entity['IO_nextdue']) != strtotime($pilotCheckings->IO_nextdue)){
                $description .= 'Instructor Observation Next Due was changed from "'.$pilotCheckings->IO_nextdue.'" to "'.$entity['IO_nextdue'].'".<br/>';
            }
            if(isset($entity['IO_nextdue_status']) && $entity['IO_nextdue_status'] != $pilotCheckings->IO_nextdue_status){
                $description .= 'Instructor Observation Next Due Status was changed from "'.$pilotCheckings->IO_nextdue_status.'" to "'.$entity['IO_nextdue_status'].'".<br/>';
            }
            if(isset($entity['CAO_check']) && $entity['CAO_check'] != $pilotCheckings->CAO_check){
                $description .= 'Check Airman Observation was changed from "'.$pilotCheckings->CAO_check.'" to "'.$entity['CAO_check'].'".<br/>';
            }
            if(isset($entity['CAO_month']) && $entity['CAO_month'] != $pilotCheckings->CAO_month){
                $description .= 'Check Airman Observation Month was changed from "'.$pilotCheckings->CAO_month.'" to "'.$entity['CAO_month'].'".<br/>';
            }
            if(isset($entity['CAO_frequency']) && $entity['CAO_frequency'] != $pilotCheckings->CAO_frequency){
                $description .= 'Check Airman Observation Frequency was changed from "'.$pilotCheckings->CAO_frequency.'" to "'.$entity['CAO_frequency'].'".<br/>';
            }
            if(isset($entity['CAO_last_completed']) && $entity['CAO_last_completed'] != $pilotCheckings->CAO_last_completed){
                $description .= 'Check Airman Observation Last Completed was changed from "'.$pilotCheckings->CAO_last_completed.'" to "'.$entity['CAO_last_completed'].'".<br/>';
            }
            if(isset($entity['CAO_nextdue']) && strtotime($entity['CAO_nextdue']) != strtotime($pilotCheckings->CAO_nextdue)){
                $description .= 'Check Airman Observation Next Due was changed from "'.$pilotCheckings->CAO_nextdue.'" to "'.$entity['CAO_nextdue'].'".<br/>';
            }
            if(isset($entity['CAO_nextdue_status']) && $entity['CAO_nextdue_status'] != $pilotCheckings->CAO_nextdue_status){
                $description .= 'Check Airman Observation Next Due Status was changed from "'.$pilotCheckings->CAO_nextdue_status.'" to "'.$entity['CAO_nextdue_status'].'".<br/>';
            }

            if(!empty($description) && !empty($modified_from) && !empty($modified_to)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            }
            
            $pilotHistory->user_id = $authUserData['id'];
            $pilotHistory->description = $description;
            if(!empty($description)){
                $pilotHistoryModel->save($pilotHistory);
            }
        }
    }

    public function savePilotDocumentToHistory($entity){
        $pilotesModel = $this->getController()->fetchTable('Pilots');
        $usersModel = $this->getController()->fetchTable('Users');
        $pilotes = $pilotesModel->get($entity->pilot_id);
        $users = $usersModel->get($pilotes->user_id);

        $pilotHistoryModel = $this->getController()->fetchTable('PilotHistories');
        
        $pilotHistory = $pilotHistoryModel->newEmptyEntity();
        $pilotHistory->pilot_id = $entity->pilot_id;

        $pilotname = $users->first_name;
        $pilotname .= !empty($users->middle_name) ? ' '.$users->middle_name : '';
        $pilotname .= ' '.$users->last_name;
        
        $pilotHistory->title = 'Pilot `'.$pilotname.'` document was uploaded.';
        $description = serialize($entity);
        
        $authUserData = $this->Authentication->getResult()->getData();
        $pilotHistory->user_id = $authUserData['id'];
        $pilotHistory->description = $description;

        $pilotHistoryModel->save($pilotHistory);
    }

    public function saveFlightLogCrewHistory($entity){
        $planeModel = $this->getController()->fetchTable('Planes');
        $planes = $planeModel->get($entity['plane_id']);

        $flightlogHistoriesModel = $this->getController()->fetchTable('FlightlogHistories');
        $flightlogHistory = $flightlogHistoriesModel->newEmptyEntity();
        
        $flightlogHistory->flightlog_id = $entity['flightlog_id'];

        $flightlogHistory->title = 'Crew Information for Aircraft '.$planes->plane_name.' was created.';

        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $flightlogHistory->user_id = $authUserData['id'];
        $flightlogHistory->description = $description;

        $flightlogHistoriesModel->save($flightlogHistory);
    }

    public function saveFlightLogDetailsHistory($entity){
        $planeModel = $this->getController()->fetchTable('Planes');
        $planes = $planeModel->get($entity->plane_id);

        $flightlogHistoriesModel = $this->getController()->fetchTable('FlightlogHistories');
        $flightlogHistory = $flightlogHistoriesModel->newEmptyEntity();
        
        $flightlogHistory->flightlog_id = $entity->flightlog_id;

        $flightlogHistory->title = 'Flight Schedule Details for Aircraft '.$planes->plane_name.' was created.';

        $description = serialize($entity);

        $authUserData = $this->Authentication->getResult()->getData();
        $flightlogHistory->user_id = $authUserData['id'];
        $flightlogHistory->description = $description;

        $flightlogHistoriesModel->save($flightlogHistory);
    }

}