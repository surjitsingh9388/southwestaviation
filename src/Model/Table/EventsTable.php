<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;
use App\Service\AppService;

class EventsTable extends Table
{
    public function initialize(array $config):void
    {
        parent::initialize($config);
        $this->setTable('events');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        //$this->Planes = FactoryLocator::get('Table')->get('Planes');
    }
    
    /*public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        
        return $validator;
    }*/

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        $service = new AppService();
        
        if(!empty($entity->event_start_date)) {
            $entity->event_start_date = $service->dateFormatBeforeSave($entity->event_start_date);
        }

        if(!empty($entity->event_end_date)) {
            $entity->event_end_date = $service->dateFormatBeforeSave($entity->event_end_date);
        }
        
        return true;
    }
}

?>