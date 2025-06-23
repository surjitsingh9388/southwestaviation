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
 * PilotCheckings Model
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
class PilotCheckingsTable extends Table
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
        $this->setTable('pilot_checkings');
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
        
        if (!empty($entity->AS_last_completed)) {
            $entity->AS_last_completed = $service->dateFormatBeforeSave($entity->AS_last_completed);
        }

        if (!empty($entity->ICC_last_completed)) {
            $entity->ICC_last_completed = $service->dateFormatBeforeSave($entity->ICC_last_completed);
        }

        if (!empty($entity->OW_last_completed)) {
            $entity->OW_last_completed = $service->dateFormatBeforeSave($entity->OW_last_completed);
        }

        if (!empty($entity->LC_last_completed)) {
            $entity->LC_last_completed = $service->dateFormatBeforeSave($entity->LC_last_completed);
        }

        if (!empty($entity->APC_last_completed)) {
            $entity->APC_last_completed = $service->dateFormatBeforeSave($entity->APC_last_completed);
        }

        if (!empty($entity->IO_last_completed)) {
            $entity->IO_last_completed = $service->dateFormatBeforeSave($entity->IO_last_completed);
        }

        if (!empty($entity->CAO_last_completed)) {
            $entity->CAO_last_completed = $service->dateFormatBeforeSave($entity->CAO_last_completed);
        }
        return true;
    }

    //Save Pilot Checking data
    public function savePilotCheckingData($postData)
    {
        $pilotCheck = $this->newEmptyEntity();
        if (!empty($postData['AS_last_completed'])) {
            $postData['AS_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['AS_last_completed']);
        }

        if (!empty($postData['ICC_last_completed'])) {
            $postData['ICC_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['ICC_last_completed']);
        }

        if (!empty($postData['OW_last_completed'])) {
            $postData['OW_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['OW_last_completed']);
        }

        if (!empty($postData['LC_last_completed'])) {
            $postData['LC_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['LC_last_completed']);
        }

        if (!empty($postData['APC_last_completed'])) {
            $postData['APC_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['APC_last_completed']);
        }

        if (!empty($postData['IO_last_completed'])) {
            $postData['IO_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['IO_last_completed']);
        }

        if (!empty($postData['CAO_last_completed'])) {
            $postData['CAO_last_completed'] = FrozenTime::createFromFormat('m/d/Y', $postData['CAO_last_completed']);
        }
        $pilotCheck = $this->patchEntity($pilotCheck, $postData);
        if($this->save($pilotCheck)) {
            return true;
        }
        return false;
    }

}
