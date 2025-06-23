<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Http\Session;
use Cake\Datasource\FactoryLocator;

/**
 * AtaCodes Model
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
class AtaCodesTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config):void
    {
        parent::initialize($config);
        $this->setTable('ata_codes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');
                        
        $validator
            ->scalar('ata_code')
            ->maxLength('ata_code', 100)
            ->notEmptyString('ata_code');
        
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules):RulesChecker
    {
        $rules->add($rules->isUnique(['category_name']));
        return $rules;
    }*/

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->id)){
            $ataCodesModel =  FactoryLocator::get('Table')->get('AtaCodes');
            $ataCodes = $ataCodesModel->get($entity->id);
            $ataCodeHistoriesModel =  FactoryLocator::get('Table')->get('AtaCodeHistories');
            
            $ataCodeHistory = $ataCodeHistoriesModel->newEmptyEntity();
            $ataCodeHistory->ata_code_id = $entity->id;

            $ataCodeHistory->title = 'Ata Code `'.$ataCodes->ata_code.'` was updated.';
            
            if(!empty($ataCodes->modified)){
                $modified_from = str_replace('-', '/', $ataCodes->modified);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->modified)){
                $modified_to = str_replace('-', '/', $entity->modified);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity->ata_code != $ataCodes->ata_code){
                $description .= 'ATA Code was changed from "'.$ataCodes->ata_code.'" to "'.$entity->ata_code.'".<br/>';
            }
            if($entity->status != $ataCodes->status){
                $description .= 'Status was changed from "'.$ataCodes->status.'" to "'.$entity->status.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $ataCodeHistory->user_id = $sessionUser['id'];
                $ataCodeHistory->description = $description;
                $ataCodeHistoriesModel->save($ataCodeHistory);
            }
        }

        return true;
    }


}
