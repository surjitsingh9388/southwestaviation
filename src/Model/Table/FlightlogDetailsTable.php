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
 * FlightlogDetails Model
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
class FlightlogDetailsTable extends Table
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
        $this->setTable('flightlog_details');
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

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        if (!empty($entity->leg_date)) {
            $entity->leg_date = $service->dateFormatBeforeSave($entity->leg_date);
        }

        if(!empty($entity->id)){
            $planeModel =  FactoryLocator::get('Table')->get('Planes');
            $planes = $planeModel->get($entity->plane_id);

            $flightlogsModel =  FactoryLocator::get('Table')->get('FlightlogDetails');
            $flightlogs = $flightlogsModel->get($entity->id);

            $flightlogHistoriesModel =  FactoryLocator::get('Table')->get('FlightlogHistories');
            $flightlogHistory = $flightlogHistoriesModel->newEmptyEntity();
            
            $flightlogHistory->flightlog_id = $entity->id;

            $flightlogHistory->title = 'Flight Schedule for Aircraft '.$entity->plane_name.' was updated.';
            
            if(!empty($flightlogs->modified)){
                $modified_from = str_replace('-', '/', $flightlogs->modified);
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
            if($entity->takeoff_timezone != $flightlogs->takeoff_timezone){
                $description .= 'Take Off Timezone was changed from "'.$flightlogs->takeoff_timezone.'" to "'.$entity->takeoff_timezone.'".<br/>';
            }
            if($entity->landing_timezone != $flightlogs->landing_timezone){
                $description .= 'Landing Timezone was changed from "'.$flightlogs->landing_timezone.'" to "'.$entity->landing_timezone.'".<br/>';
            }
            if(strtotime($entity->taxi_out) != strtotime($flightlogs->taxi_out)){
                $description .= 'Taxi Out was changed from "'.$flightlogs->taxi_out.'" to "'.$entity->taxi_out.'".<br/>';
            }
            if(strtotime($entity->taxi_off) != strtotime($flightlogs->taxi_off)){
                $description .= 'Take Off was changed from "'.$flightlogs->taxi_off.'" to "'.$entity->taxi_off.'".<br/>';
            }
            if($entity->landing != $flightlogs->landing){
                $description .= 'Landing was changed from "'.$flightlogs->landing.'" to "'.$entity->landing.'".<br/>';
            }
            if(strtotime($entity->taxi_in) != strtotime($flightlogs->taxi_in)){
                $description .= 'Taxi In was changed from "'.$flightlogs->taxi_in.'" to "'.$entity->taxi_in.'".<br/>';
            }
            if($entity->takeoff_hobbs != $flightlogs->takeoff_hobbs){
                $description .= 'Hobbs Take Off was changed from "'.$flightlogs->takeoff_hobbs.'" to "'.$entity->takeoff_hobbs.'".<br/>';
            }
            if($entity->landing_hobbs != $flightlogs->landing_hobbs){
                $description .= 'Hobbs Landing was changed from "'.$flightlogs->landing_hobbs.'" to "'.$entity->landing_hobbs.'".<br/>';
            }
            if($entity->approaches != $flightlogs->approaches){
                $description .= 'Approaches was changed from "'.$flightlogs->approaches.'" to "'.$entity->approaches.'".<br/>';
            }
            if($entity->approach_type != $flightlogs->approach_type){
                $description .= 'Approach Type was changed from "'.$flightlogs->approach_type.'" to "'.$entity->approach_type.'".<br/>';
            }
            if($entity->takeoff_type != $flightlogs->takeoff_type){
                $description .= 'Takeoff Type was changed from "'.$flightlogs->takeoff_type.'" to "'.$entity->takeoff_type.'".<br/>';
            }
            if($entity->landing_type != $flightlogs->landing_type){
                $description .= 'Landing Type was changed from "'.$flightlogs->landing_type.'" to "'.$entity->landing_type.'".<br/>';
            }
            if($entity->night_time != $flightlogs->night_time){
                $description .= 'Night Time was changed from "'.$flightlogs->night_time.'" to "'.$entity->night_time.'".<br/>';
            }
            if($entity->inst != $flightlogs->inst){
                $description .= 'Inst was changed from "'.$flightlogs->inst.'" to "'.$entity->inst.'".<br/>';
            }
            if($entity->fuel_on_board != $flightlogs->fuel_on_board){
                $description .= 'FOB was changed from "'.$flightlogs->fuel_on_board.'" to "'.$entity->fuel_on_board.'".<br/>';
            }
            if($entity->fuel_purchased != $flightlogs->fuel_purchased){
                $description .= 'Fuel Purchased was changed from "'.$flightlogs->fuel_purchased.'" to "'.$entity->fuel_purchased.'".<br/>';
            }
            if($entity->fuel_type != $flightlogs->fuel_type){
                $description .= 'Fuel Type was changed from "'.$flightlogs->fuel_type.'" to "'.$entity->fuel_type.'".<br/>';
            }
            if($entity->ending_fuel != $flightlogs->ending_fuel){
                $description .= 'Ending Fuel was changed from "'.$flightlogs->ending_fuel.'" to "'.$entity->ending_fuel.'".<br/>';
            }
            if($entity->fuel_total != $flightlogs->fuel_total){
                $description .= 'Starting Fuel was changed from "'.$flightlogs->fuel_total.'" to "'.$entity->fuel_total.'".<br/>';
            }
            if($entity->fuel_cost != $flightlogs->fuel_cost){
                $description .= 'Fuel Cost was changed from "'.$flightlogs->fuel_cost.'" to "'.$entity->fuel_cost.'".<br/>';
            }
            if($entity->fuel_burn != $flightlogs->fuel_burn){
                $description .= 'Fuel Burn was changed from "'.$flightlogs->fuel_burn.'" to "'.$entity->fuel_burn.'".<br/>';
            }
            if($entity->apu_time != $flightlogs->apu_time){
                $description .= 'Post-Leg APU Time was changed from "'.$flightlogs->apu_time.'" to "'.$entity->apu_time.'".<br/>';
            }
            
            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $flightlogHistory->user_id = $sessionUser['id'];
                $flightlogHistory->description = $description;
                $planeHistoriesModel->save($flightlogHistory);
            }
        }

        return true;
    }
}
