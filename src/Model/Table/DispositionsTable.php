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
 * Dispositions Model
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
class DispositionsTable extends Table
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
        $this->setTable('dispositions');
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
    public function validationDefault(Validator $validator):Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');
                        
        $validator
            ->scalar('title')
            ->maxLength('title', 100)
            ->notEmptyString('title');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->id)){
            $dispositionsModel =  FactoryLocator::get('Table')->get('Dispositions');
            $dispositions = $dispositionsModel->get($entity->id);
            $dispositionHistoriesModel =  FactoryLocator::get('Table')->get('DispositionHistories');
            
            $dispositionHistory = $dispositionHistoriesModel->newEmptyEntity();
            $dispositionHistory->disposition_id = $entity->id;

            $dispositionHistory->title = 'Disposition `'.$dispositions->title.'` was updated.';
            
            if(!empty($dispositions->modified)){
                $modified_from = str_replace('-', '/', $dispositions->modified);
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
            if($entity->title != $dispositions->title){
                $description .= 'Disposition was changed from "'.$dispositions->title.'" to "'.$entity->title.'".<br/>';
            }
            if($entity->status != $dispositions->status){
                $description .= 'Status was changed from "'.$dispositions->status.'" to "'.$entity->status.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $session = new Session();
                $sessionUser = $session->read('Auth');
                $dispositionHistory->user_id = $sessionUser['id'];
                $dispositionHistory->description = $description;
                $dispositionHistoriesModel->save($dispositionHistory);
            }
        }

        return true;
    }

}
