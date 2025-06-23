<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;
use Cake\I18n\FrozenTime;
use App\Service\AppService;

/**
 * AircraftDiscrepancies Model
 *
 * @method \App\Model\Entity\Routes get($primaryKey, $options = [])
 * @method \App\Model\Entity\Routes newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Routes[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Routes|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Routes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Routes[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Routes findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AircraftDiscrepanciesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);
        $this->setTable('aircraft_discrepancies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id',
            'joinType' => 'INNER'
        ]);

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');
        
        $validator
            ->integer('plane_id')
            ->notEmptyString('plane_id');
                
        $validator
            ->scalar('discrepancy')
            ->notEmptyString('discrepancy');

        $validator
            ->scalar('discovered_by')
            ->notEmptyString('discovered_by');

        $validator
            ->scalar('discovered_cert')
            ->notEmptyString('discovered_cert');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if (!empty($entity->discrepancy_date)) {
            $entity->discrepancy_date = $service->dateFormatBeforeSave($entity->discrepancy_date);
        }

        if (!empty($entity->mel_repair_by)) {
            $entity->mel_repair_by = $service->dateFormatBeforeSave($entity->mel_repair_by);
        }

        if (!empty($entity->corrected_date)) {
            $entity->corrected_date = $service->dateFormatBeforeSave($entity->corrected_date);
        }

        if(!empty($entity->id)){
            $aircraftDiscrepanciesModel =  FactoryLocator::get('Table')->get('AircraftDiscrepancies');
            $aircraftDiscrepancies = $aircraftDiscrepanciesModel->get($entity->id);
            $aircraftDiscrepancyHistoryModel =  FactoryLocator::get('Table')->get('AircraftDiscrepancyHistories');
            
            $aircraftDiscrepancyHistory = $aircraftDiscrepancyHistoryModel->newEmptyEntity();
            $aircraftDiscrepancyHistory->aircraft_discrepancy_id = $entity->id;

            $aircraftDiscrepancyHistory->title = 'Aircraft Discrepancy `'.$aircraftDiscrepancies->discrepancy.'` was updated.';
            
            if(!empty($aircraftDiscrepancies->modified)){
                $modified_from = str_replace('-', '/', $aircraftDiscrepancies->modified);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->modified)){
                $modified_to = str_replace('-', '/', $entity->modified);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity->plane_id != $aircraftDiscrepancies->plane_id){
                $description .= 'Aircraft was changed from "'.$aircraftDiscrepancies->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if(strtotime($entity->discrepancy_date) != strtotime($aircraftDiscrepancies->discrepancy_date)){
                $description .= 'Date of Discovery was changed from "'.$aircraftDiscrepancies->discrepancy_date.'" to "'.$entity->discrepancy_date.'".<br/>';
            }
            if(strtotime($entity->discrepancy_time) != strtotime($aircraftDiscrepancies->discrepancy_time)){
                $description .= 'Time of Discovery (hrs / mins) was changed from "'.$aircraftDiscrepancies->discrepancy_time.'" to "'.$entity->discrepancy_time.'".<br/>';
            }
            if($entity->discrepancy != $aircraftDiscrepancies->discrepancy){
                $description .= 'Discrepancy was changed from "'.$aircraftDiscrepancies->discrepancy.'" to "'.$entity->discrepancy.'".<br/>';
            }
            if($entity->discovered_by != $aircraftDiscrepancies->discovered_by){
                $description .= 'Discovered By was changed from "'.$aircraftDiscrepancies->discovered_by.'" to "'.$entity->discovered_by.'".<br/>';
            }
            if($entity->discovered_cert != $aircraftDiscrepancies->discovered_cert){
                $description .= 'Cert Number was changed from "'.$aircraftDiscrepancies->discovered_cert.'" to "'.$entity->discovered_cert.'".<br/>';
            }
            if($entity->discrepancy_mel != $aircraftDiscrepancies->discrepancy_mel){
                $description .= 'Defer this discrepancy (MEL/NEF) was changed from "'.$aircraftDiscrepancies->discrepancy_mel.'" to "'.$entity->discrepancy_mel.'".<br/>';
            }
            if($entity->mel_category != $aircraftDiscrepancies->mel_category){
                $description .= 'MEL Category was changed from "'.$aircraftDiscrepancies->mel_category.'" to "'.$entity->mel_category.'".<br/>';
            }
            if($entity->mel_number != $aircraftDiscrepancies->mel_number){
                $description .= 'MEL/NEF Item was changed from "'.$aircraftDiscrepancies->mel_number.'" to "'.$entity->mel_number.'".<br/>';
            }
            if($entity->mel_action != $aircraftDiscrepancies->mel_action){
                $description .= 'MEL O or M action was changed from "'.$aircraftDiscrepancies->mel_action.'" to "'.$entity->mel_action.'".<br/>';
            }
            if($entity->deferred_by != $aircraftDiscrepancies->deferred_by){
                $description .= 'Deferred By was changed from "'.$aircraftDiscrepancies->deferred_by.'" to "'.$entity->deferred_by.'".<br/>';
            }
            if($entity->deferred_cert != $aircraftDiscrepancies->deferred_cert){
                $description .= 'Cert Number was changed from "'.$aircraftDiscrepancies->deferred_cert.'" to "'.$entity->deferred_cert.'".<br/>';
            }
            if($entity->mel_repair_by != $aircraftDiscrepancies->mel_repair_by){
                $description .= 'Repair by 00:00 on was changed from "'.$aircraftDiscrepancies->mel_repair_by.'" to "'.$entity->mel_repair_by.'".<br/>';
            }
            if($entity->discrepancy_corrected != $aircraftDiscrepancies->discrepancy_corrected){
                $description .= 'Add corrective action for this discrepancy was changed from "'.$aircraftDiscrepancies->discrepancy_corrected.'" to "'.$entity->discrepancy_corrected.'".<br/>';
            }
            if($entity->corrected_action != $aircraftDiscrepancies->corrected_action){
                $description .= 'Corrective Action was changed from "'.$aircraftDiscrepancies->corrected_action.'" to "'.$entity->corrected_action.'".<br/>';
            }
            if($entity->corrected_by != $aircraftDiscrepancies->corrected_by){
                $description .= 'Technician Name was changed from "'.$aircraftDiscrepancies->corrected_by.'" to "'.$entity->corrected_by.'".<br/>';
            }
            if($entity->corrected_cert != $aircraftDiscrepancies->corrected_cert){
                $description .= 'A&P Number was changed from "'.$aircraftDiscrepancies->corrected_cert.'" to "'.$entity->corrected_cert.'".<br/>';
            }
            if($entity->corrected_signature != $aircraftDiscrepancies->corrected_signature){
                $description .= 'RTS Signature was changed from "'.$aircraftDiscrepancies->corrected_signature.'" to "'.$entity->corrected_signature.'".<br/>';
            }
            if(strtotime($entity->corrected_date) != strtotime($aircraftDiscrepancies->corrected_date)){
                $description .= 'Date was changed from "'.$aircraftDiscrepancies->corrected_date.'" to "'.$entity->corrected_date.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $aircraftDiscrepancyHistory->user_id = $sessionUser['id'];
                $aircraftDiscrepancyHistory->description = $description;
                $aircraftDiscrepancyHistoryModel->save($aircraftDiscrepancyHistory);
            }
        }

        return true;
    }

    public function saveData($airDisc, $postData)
    {
        if (!empty($postData['discrepancy_date'])) {
            $postData['discrepancy_date'] = FrozenTime::createFromFormat('m/d/Y', $postData['discrepancy_date']);
        }

        if (!empty($postData['mel_repair_by'])) {
            $postData['mel_repair_by'] = FrozenTime::createFromFormat('m/d/Y', $postData['mel_repair_by']);
        }

        if (!empty($postData['corrected_date'])) {
            $postData['corrected_date'] = FrozenTime::createFromFormat('m/d/Y', $postData['corrected_date']);
        }
        
        $airDisc = $this->patchEntity($airDisc, $postData);
        if($this->save($airDisc)) {
            //save to discrepancy history table
            $this->saveAircraftDiscrepancyHistory($airDisc);
            return true;
        }
        return false;
    }

    public function saveAircraftDiscrepancyHistory($entity){
        $aircraftDiscrepancyHistoryModel =  FactoryLocator::get('Table')->get('AircraftDiscrepancyHistories');

        $aircraftDiscrepancyHistory = $aircraftDiscrepancyHistoryModel->newEmptyEntity();
        $aircraftDiscrepancyHistory->aircraft_discrepancy_id = $entity->id;
        
        $aircraftDiscrepancyHistory->title = 'Aircraft Discrepancy `'.$entity->discrepancy.'` was created.';
        $description = serialize($entity);
        
        $session = new Session();
        $sessionUser = $session->read('Auth');
        $aircraftDiscrepancyHistory->user_id = $sessionUser['id'];
        $aircraftDiscrepancyHistory->description = $description;

        $aircraftDiscrepancyHistoryModel->save($aircraftDiscrepancyHistory);
    }

    
}
