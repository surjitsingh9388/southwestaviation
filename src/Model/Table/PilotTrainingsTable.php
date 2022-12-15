<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * PilotTrainings Model
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
class PilotTrainingsTable extends Table
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
        $this->setTable('pilot_trainings');
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
        if (!empty($entity->AFT_last_completed)) {
            $entity->AFT_last_completed = $this->Planes->dateFormatBeforeSave($entity->AFT_last_completed);
        }

        if (!empty($entity->EDT_last_completed)) {
            $entity->EDT_last_completed = $this->Planes->dateFormatBeforeSave($entity->EDT_last_completed);
        }

        if (!empty($entity->CWOT_last_completed)) {
            $entity->CWOT_last_completed = $this->Planes->dateFormatBeforeSave($entity->CWOT_last_completed);
        }

        if (!empty($entity->CRM_last_completed)) {
            $entity->CRM_last_completed = $this->Planes->dateFormatBeforeSave($entity->CRM_last_completed);
        }

        if (!empty($entity->EFB_last_completed)) {
            $entity->EFB_last_completed = $this->Planes->dateFormatBeforeSave($entity->EFB_last_completed);
        }

        if (!empty($entity->EGT_last_completed)) {
            $entity->EGT_last_completed = $this->Planes->dateFormatBeforeSave($entity->EGT_last_completed);
        }

        if (!empty($entity->GIT_last_completed)) {
            $entity->GIT_last_completed = $this->Planes->dateFormatBeforeSave($entity->GIT_last_completed);
        }

        if (!empty($entity->Hz_last_completed)) {
            $entity->Hz_last_completed = $this->Planes->dateFormatBeforeSave($entity->Hz_last_completed);
        }

        if (!empty($entity->IR_last_completed)) {
            $entity->IR_last_completed = $this->Planes->dateFormatBeforeSave($entity->IR_last_completed);
        }

        if (!empty($entity->IRG_last_completed)) {
            $entity->IRG_last_completed = $this->Planes->dateFormatBeforeSave($entity->IRG_last_completed);
        }

        if (!empty($entity->ICAT_last_completed)) {
            $entity->ICAT_last_completed = $this->Planes->dateFormatBeforeSave($entity->ICAT_last_completed);
        }

        if (!empty($entity->LBFT_last_completed)) {
            $entity->LBFT_last_completed = $this->Planes->dateFormatBeforeSave($entity->LBFT_last_completed);
        }

        if (!empty($entity->RVSM_last_completed)) {
            $entity->RVSM_last_completed = $this->Planes->dateFormatBeforeSave($entity->RVSM_last_completed);
        }

        if (!empty($entity->ST_last_completed)) {
            $entity->ST_last_completed = $this->Planes->dateFormatBeforeSave($entity->ST_last_completed);
        }
        
        return true;
    }

    //Save Pilot Training data
    public function savePilotTrainingData($postData)
    {
        $pilotTraining = $this->newEntity();
        $pilotTraining = $this->patchEntity($pilotTraining, $postData);
        if($this->save($pilotTraining)) {
            return true;
        }
        return false;
    }

}
