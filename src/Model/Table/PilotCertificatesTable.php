<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * PilotCertificates Model
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
class PilotCertificatesTable extends Table
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
        $this->setTable('pilot_certificates');
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
        if (!empty($entity->medical_last_completed)) {
            $entity->medical_last_completed = $this->Planes->dateFormatBeforeSave($entity->medical_last_completed);
        }

        if (!empty($entity->passport_last_completed)) {
            $entity->passport_last_completed = $this->Planes->dateFormatBeforeSave($entity->passport_last_completed);
        }

        if (!empty($entity->DL_last_completed)) {
            $entity->DL_last_completed = $this->Planes->dateFormatBeforeSave($entity->DL_last_completed);
        }

        if (!empty($entity->TPC_last_completed)) {
            $entity->TPC_last_completed = $this->Planes->dateFormatBeforeSave($entity->TPC_last_completed);
        }
        return true;
    }

    //Save Pilot Certificate data
    public function savePilotCertificateData($postData)
    {
        $pilotCertificate = $this->newEntity();
        $pilotCertificate = $this->patchEntity($pilotCertificate, $postData);
        if($this->save($pilotCertificate)) {
            return true;
        }
        return false;
    }

}
