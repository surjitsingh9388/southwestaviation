<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

/**
 * CustomerAircraftWOLaborKits Model
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CustomerAircraftWOLaborKitsTable extends Table
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
        $this->setTable('customer_aircraft_wo_labor_kits');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');        
        
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
            ->allowEmptyString('id');
        
        return $validator;
    }
    
    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();

        /*if (!empty($entity->discrepancy_date)) {
            $entity->discrepancy_date = $service->dateFormatBeforeSave($entity->discrepancy_date);
        }*/
        return true;
    }
    
}
