<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use Cake\I18n\FrozenTime;
use App\Service\AppService;

/**
 * FlightLegDetails Model
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
class FlightLegDetailsTable extends Table
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
        $this->setTable('flight_leg_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Pilots', [
            'foreignKey' => 'pilot_id',
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
            ->integer('pilot_id')
            ->allowEmptyString('pilot_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        if (!empty($entity->selected_date)) {
            $entity->selected_date = $service->dateFormatBeforeSave($entity->selected_date);
        }        
        return true;
    }

    //Save Flight Leg data
    public function saveFlightLegData($postData)
    {
        if(!empty($postData)) {
            if (!empty($postData['id'])) {
                $flightLeg = $this->get($postData['id']);
            } else {
                $flightLeg = $this->newEmptyEntity();
            }
            if(!empty($postData['selected_date'])){
                $postData['selected_date'] = FrozenTime::createFromFormat('m/d/Y', $postData['selected_date']);
            }
            $flightLeg = $this->patchEntity($flightLeg, $postData);
            if($this->save($flightLeg)) {
                return true;
            }
            return false;
        }
    }

}
