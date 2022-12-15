<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

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
    public function initialize(array $config)
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
            ->integer('plane_id')
            ->notEmpty('plane_id');
                
        $validator
            ->scalar('discrepancy')
            ->notEmpty('discrepancy');

        $validator
            ->scalar('discovered_by')
            ->notEmpty('discovered_by');

        $validator
            ->scalar('discovered_cert')
            ->notEmpty('discovered_cert');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if (!empty($entity->discrepancy_date)) {
            $entity->discrepancy_date = $this->Planes->dateFormatBeforeSave($entity->discrepancy_date);
        }

        if (!empty($entity->mel_repair_by)) {
            $entity->mel_repair_by = $this->Planes->dateFormatBeforeSave($entity->mel_repair_by);
        }

        if (!empty($entity->corrected_date)) {
            $entity->corrected_date = $this->Planes->dateFormatBeforeSave($entity->corrected_date);
        }

        return true;
    }

    public function saveData($airDisc, $postData)
    {
        $airDisc = $this->patchEntity($airDisc, $postData);
        if($this->save($airDisc)) {
            return true;
        }
        return false;
    }

    
}
