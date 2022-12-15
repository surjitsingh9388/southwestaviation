<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * DaysOff Model
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
class DaysOffTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('days_off');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Pilots', [
            'foreignKey' => 'pilot_id',
            'joinType' => 'INNER'
        ]);

        $this->Planes = TableRegistry::get('Planes');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');
        
        $validator
            ->integer('pilot_id')
            ->allowEmpty('pilot_id');
        
        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        if (!empty($entity->selected_date)) {
            $entity->selected_date = $this->Planes->dateFormatBeforeSave($entity->selected_date);
        }

        if (!empty($entity->start_date)) {
            $entity->start_date = $this->Planes->dateFormatBeforeSave($entity->start_date);
        }

        if (!empty($entity->end_date)) {
            $entity->end_date = $this->Planes->dateFormatBeforeSave($entity->end_date);
        }

        return true;
    }

    //Save Days Off data
    public function saveDaysOffData($postData)
    {
        if(!empty($postData)) {
            if (!empty($postData['id'])) {
                $daysOff = $this->get($postData['id']);
            } else {
                $daysOff = $this->newEntity();
            }
            $daysOff = $this->patchEntity($daysOff, $postData);
            if($this->save($daysOff)) {
                return true;
            }
            return false;
        }
    }

}
