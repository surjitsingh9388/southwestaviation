<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
//use SoftDelete\Model\Table\SoftDeleteTrait;
use Cake\Datasource\FactoryLocator;

class TechnicalPublicationsTable extends Table
{
    public function initialize(array $config):void
    {
        parent::initialize($config);

        $this->setTable('technical_publications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->hasMany('TechnicalPublicationPermissions', [
            'foreignKey' => 'technical_publication_id',
        ]);

        $this->hasMany('Children', [
            'className' => 'TechnicalPublications',
            'foreignKey' => 'parent_id',
        ]);

        $this->belongsTo('ParentPublication', [
            'className' => 'TechnicalPublications',
            'foreignKey' => 'parent_id',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'added_by',
        ]);
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

        return $validator;
    }

    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');

        if(!empty($entity->id)){
            $technicalPublicationsModel = FactoryLocator::get('Table')->get('TechnicalPublications');            
            $technicalPublications = $technicalPublicationsModel->get($entity->id);

            $technicalPublicationHistoriesModel = FactoryLocator::get('Table')->get('TechnicalPublicationHistories');
            
            $technicalPublicationHistory = $technicalPublicationHistoriesModel->newEmptyEntity();
            
            $main_folder_name = 'SWAS';
            if($entity->main_page_id == '2'){
                $main_folder_name = 'BVAC';
            }else if($entity->main_page_id == '3'){
                $main_folder_name = 'RJC';
            }

            $technicalPublicationHistory->technical_publication_id = $entity->id;

            $technicalPublicationHistory->title = 'Technical Publication '.$main_folder_name.' was updated.';
            
            if(!empty($technicalPublications->updated_at)){
                $modified_from = str_replace('-', '/', $technicalPublications->updated_at);
                $modified_from = date("Y-m-d h:i A", strtotime($modified_from));
            }else{
                $modified_from = '';
            }

            if(!empty($entity->updated_at)){
                $modified_to = str_replace('-', '/', $entity->updated_at);
                $modified_to = date("Y-m-d h:i A", strtotime($modified_to));
            }else{
                $modified_to = '';
            }

            $description = '';
            if($entity->parent_id != $technicalPublications->parent_id){
                $description .= 'Parent Id was changed from "'.$technicalPublications->parent_id.'" to "'.$entity->parent_id.'".<br/>';
            }

            if($entity->is_folder != $technicalPublications->is_folder){
                $description .= 'Is Folder was changed from "'.$technicalPublications->is_folder.'" to "'.$entity->is_folder.'".<br/>';
            }

            if($entity->folder_file_name != $technicalPublications->folder_file_name){
                $description .= 'Folder/File Name was changed from "'.$technicalPublications->folder_file_name.'" to "'.$entity->folder_file_name.'".<br/>';
            }

            if($entity->permission_user_ids != $technicalPublications->permission_user_ids){
                $description .= 'Permission User was changed from "'.$technicalPublications->permission_user_ids.'" to "'.$entity->permission_user_ids.'".<br/>';
            }

            if(!empty($description)){
                $description .= 'Last updated was changed from "'.$modified_from.'" to "'.$modified_to.'".<br/>';
            
                $technicalPublicationHistory->user_id = $entity->updated_by;
                $technicalPublicationHistory->description = $description;
                
                $technicalPublicationHistoriesModel->save($technicalPublicationHistory);
            }

        }

        return true;
    }
    
}
