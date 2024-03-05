<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;

class EventsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('events');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->Planes = TableRegistry::get('Planes');
    }
    
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        
        return $validator;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->event_start_date)) {
            $entity->event_start_date = $this->Planes->dateFormatBeforeSave($entity->event_start_date);
        }

        if(!empty($entity->event_end_date)) {
            $entity->event_end_date = $this->Planes->dateFormatBeforeSave($entity->event_end_date);
        }
        
    }
}

?>