<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * Crews Model
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
class CrewsTable extends Table
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
        $this->setTable('crews');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Planes', [
            'foreignKey' => 'plane_id'
        ]);

        $this->belongsTo('Flightlogs', [
            'foreignKey' => 'flightlog_id'
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
            ->integer('flightlog_id')
            ->notEmptyString('flightlog_id');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        if (!empty($entity->duty_start_date)) {
            $entity->duty_start_date = $service->dateFormatBeforeSave($entity->duty_start_date);
        }

        if (!empty($entity->duty_on)) {
            $entity->duty_on = $service->dateFormatBeforeSave($entity->duty_on);
        }

        if (!empty($entity->duty_off)) {
            $entity->duty_off = $service->dateFormatBeforeSave($entity->duty_off);
        }

        /*if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $planes = $planeModel->get($entity->plane_id);

            $crewsModel =  FactoryLocator::get('Table')->get('Crews');
            $crews = $crewsModel->get($entity->id);
            
            $flightlogHistoriesModel =  FactoryLocator::get('Table')->get('FlightlogHistories');
            $flightlogHistory = $flightlogHistoriesModel->newEmptyEntity();
            
            $flightlogHistory->flightlog_id = $entity->id;

            $flightlogHistory->title = 'Flight Schedule for Aircraft '.$planes->plane_name.' was updated.';
            
            if(!empty($flightlogHistory->modified)){
                $modified_from = str_replace('-', '/', $flightlogHistory->modified);
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
            if($entity->user_id != $flightlogs->user_id){
                $description .= 'Pilot was changed from "'.$flightlogs->user_id.'" to "'.$entity->user_id.'".<br/>';
            }
            if($entity->plane_id != $flightlogs->plane_id){
                $description .= 'Plane Name was changed from "'.$flightlogs->plane_id.'" to "'.$entity->plane_id.'".<br/>';
            }
            if($entity->trip_id != $flightlogs->trip_id){
                $description .= 'Trip ID was changed from "'.$flightlogs->trip_id.'" to "'.$entity->trip_id.'".<br/>';
            }
            if($entity->flight_from != $flightlogs->flight_from){
                $description .= 'Flying From was changed from "'.$flightlogs->flight_from.'" to "'.$entity->flight_from.'".<br/>';
            }
            if($entity->flight_to != $flightlogs->flight_to){
                $description .= 'Flying To was changed from "'.$flightlogs->flight_to.'" to "'.$entity->flight_to.'".<br/>';
            }
            if($entity->leg_type != $flightlogs->leg_type){
                $description .= 'Leg Type was changed from "'.$flightlogs->leg_type.'" to "'.$entity->leg_type.'".<br/>';
            }
            if($entity->passengers != $flightlogs->passengers){
                $description .= 'Passengers was changed from "'.$flightlogs->passengers.'" to "'.$entity->passengers.'".<br/>';
            }
            if(strtotime($entity->leg_date) != strtotime($flightlogs->leg_date)){
                $description .= 'Leg Start Date was changed from "'.$flightlogs->leg_date.'" to "'.$entity->leg_date.'".<br/>';
            }
            if(strtotime($entity->leg_start) != strtotime($flightlogs->leg_start)){
                $description .= 'Leg Start was changed from "'.$flightlogs->leg_start.'" to "'.$entity->leg_start.'".<br/>';
            }
            if(strtotime($entity->leg_length) != strtotime($flightlogs->leg_length)){
                $description .= 'Leg Length was changed from "'.$flightlogs->leg_length.'" to "'.$entity->leg_length.'".<br/>';
            }
            if($entity->notes != $flightlogs->notes){
                $description .= 'Notes was changed from "'.$flightlogs->notes.'" to "'.$entity->notes.'".<br/>';
            }
            if($entity->is_template != $flightlogs->is_template){
                $description .= 'Is Template was changed from "'.$flightlogs->is_template.'" to "'.$entity->is_template.'".<br/>';
            }
            if($entity->flstatus != $flightlogs->flstatus){
                $description .= 'Flight Status was changed from "'.$flightlogs->flstatus.'" to "'.$entity->flstatus.'".<br/>';
            }
            if($entity->status != $flightlogs->status){
                $description .= 'Status was changed from "'.$flightlogs->status.'" to "'.$entity->status.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $flightlogHistory->user_id = $sessionUser['id'];
                $flightlogHistory->description = $description;
                $planeHistoriesModel->save($flightlogHistory);
            }
        }*/

        return true;
    }
}
