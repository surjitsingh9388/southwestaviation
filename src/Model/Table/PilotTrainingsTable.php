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
    public function initialize(array $config):void
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
        
        if (!empty($entity->AFT_last_completed)) {
            $entity->AFT_last_completed = $service->dateFormatBeforeSave($entity->AFT_last_completed);
        }

        if (!empty($entity->EDT_last_completed)) {
            $entity->EDT_last_completed = $service->dateFormatBeforeSave($entity->EDT_last_completed);
        }

        if (!empty($entity->CWOT_last_completed)) {
            $entity->CWOT_last_completed = $service->dateFormatBeforeSave($entity->CWOT_last_completed);
        }

        if (!empty($entity->CRM_last_completed)) {
            $entity->CRM_last_completed = $service->dateFormatBeforeSave($entity->CRM_last_completed);
        }

        if (!empty($entity->EFB_last_completed)) {
            $entity->EFB_last_completed = $service->dateFormatBeforeSave($entity->EFB_last_completed);
        }

        if (!empty($entity->EGT_last_completed)) {
            $entity->EGT_last_completed = $service->dateFormatBeforeSave($entity->EGT_last_completed);
        }

        if (!empty($entity->GIT_last_completed)) {
            $entity->GIT_last_completed = $service->dateFormatBeforeSave($entity->GIT_last_completed);
        }

        if (!empty($entity->Hz_last_completed)) {
            $entity->Hz_last_completed = $service->dateFormatBeforeSave($entity->Hz_last_completed);
        }

        if (!empty($entity->IR_last_completed)) {
            $entity->IR_last_completed = $service->dateFormatBeforeSave($entity->IR_last_completed);
        }

        if (!empty($entity->IRG_last_completed)) {
            $entity->IRG_last_completed = $service->dateFormatBeforeSave($entity->IRG_last_completed);
        }

        if (!empty($entity->ICAT_last_completed)) {
            $entity->ICAT_last_completed = $service->dateFormatBeforeSave($entity->ICAT_last_completed);
        }

        if (!empty($entity->LBFT_last_completed)) {
            $entity->LBFT_last_completed = $service->dateFormatBeforeSave($entity->LBFT_last_completed);
        }

        if (!empty($entity->RVSM_last_completed)) {
            $entity->RVSM_last_completed = $service->dateFormatBeforeSave($entity->RVSM_last_completed);
        }

        if (!empty($entity->ST_last_completed)) {
            $entity->ST_last_completed = $service->dateFormatBeforeSave($entity->ST_last_completed);
        }
        
        return true;
    }

    //Save Pilot Training data
    public function savePilotTrainingData($postData)
    {
        $pilotTraining = $this->newEmptyEntity();
        if (!empty($postData['AFT_last_completed'])) {
            $postData['AFT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['AFT_last_completed']);
        }

        if (!empty($postData['EDT_last_completed'])) {
            $postData['EDT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['EDT_last_completed']);
        }

        if (!empty($postData['CWOT_last_completed'])) {
            $postData['CWOT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['CWOT_last_completed']);
        }

        if (!empty($postData['CRM_last_completed'])) {
            $postData['CRM_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['CRM_last_completed']);
        }

        if (!empty($postData['EFB_last_completed'])) {
            $postData['EFB_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['EFB_last_completed']);
        }

        if (!empty($postData['EGT_last_completed'])) {
            $postData['EGT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['EGT_last_completed']);
        }

        if (!empty($postData['GIT_last_completed'])) {
            $postData['GIT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['GIT_last_completed']);
        }

        if (!empty($postData['Hz_last_completed'])) {
            $postData['Hz_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['Hz_last_completed']);
        }

        if (!empty($postData['IR_last_completed'])) {
            $postData['IR_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['IR_last_completed']);
        }

        if (!empty($postData['IRG_last_completed'])) {
            $postData['IRG_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['IRG_last_completed']);
        }

        if (!empty($postData['ICAT_last_completed'])) {
            $postData['ICAT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['ICAT_last_completed']);
        }

        if (!empty($postData['LBFT_last_completed'])) {
            $postData['LBFT_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['LBFT_last_completed']);
        }

        if (!empty($postData['RVSM_last_completed'])) {
            $postData['RVSM_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['RVSM_last_completed']);
        }

        if (!empty($postData['ST_last_completed'])) {
            $postData['ST_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['ST_last_completed']);
        }
        $pilotTraining = $this->patchEntity($pilotTraining, $postData);
        if($this->save($pilotTraining)) {
            return true;
        }
        return false;
    }

}
