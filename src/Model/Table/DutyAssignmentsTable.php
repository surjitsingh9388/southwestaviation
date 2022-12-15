<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * DutyAssignments Model
 *
 * @property \App\Model\Table\PilotsTable|\Cake\ORM\Association\BelongsTo $Pilots
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
class DutyAssignmentsTable extends Table
{
    //use SoftDeleteTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable('duty_assignments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Pilots', [
            'foreignKey' => 'pilot_id'
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
            ->notEmpty('pilot_id');

        return $validator;
    }

    //Before save change date format
    public function beforeSave($options = array())
    {
        $entity = $options->getData('entity');
        
        //PIC
        if (!empty($entity->PIC_date_assigned1)) {
            $entity->PIC_date_assigned1 = $this->Planes->dateFormatBeforeSave($entity->PIC_date_assigned1);
        }

        if (!empty($entity->PIC_date_unassigned1)) {
            $entity->PIC_date_unassigned1 = $this->Planes->dateFormatBeforeSave($entity->PIC_date_unassigned1);
        }

        if (!empty($entity->PIC_date_assigned2)) {
            $entity->PIC_date_assigned2 = $this->Planes->dateFormatBeforeSave($entity->PIC_date_assigned2);
        }

        if (!empty($entity->PIC_date_unassigned2)) {
            $entity->PIC_date_unassigned2 = $this->Planes->dateFormatBeforeSave($entity->PIC_date_unassigned2);
        }

        //SIC
        if (!empty($entity->SIC_date_assigned1)) {
            $entity->SIC_date_assigned1 = $this->Planes->dateFormatBeforeSave($entity->SIC_date_assigned1);
        }

        if (!empty($entity->SIC_date_unassigned1)) {
            $entity->SIC_date_unassigned1 = $this->Planes->dateFormatBeforeSave($entity->SIC_date_unassigned1);
        }

        if (!empty($entity->SIC_date_assigned2)) {
            $entity->SIC_date_assigned2 = $this->Planes->dateFormatBeforeSave($entity->SIC_date_assigned2);
        }

        if (!empty($entity->SIC_date_unassigned2)) {
            $entity->SIC_date_unassigned2 = $this->Planes->dateFormatBeforeSave($entity->SIC_date_unassigned2);
        }

        //GI
        if (!empty($entity->GI_date_assigned1)) {
            $entity->GI_date_assigned1 = $this->Planes->dateFormatBeforeSave($entity->GI_date_assigned1);
        }

        if (!empty($entity->GI_date_unassigned1)) {
            $entity->GI_date_unassigned1 = $this->Planes->dateFormatBeforeSave($entity->GI_date_unassigned1);
        }

        if (!empty($entity->GI_date_assigned2)) {
            $entity->GI_date_assigned2 = $this->Planes->dateFormatBeforeSave($entity->GI_date_assigned2);
        }

        if (!empty($entity->GI_date_unassigned2)) {
            $entity->GI_date_unassigned2 = $this->Planes->dateFormatBeforeSave($entity->GI_date_unassigned2);
        }

        //FI
        if (!empty($entity->FI_date_assigned1)) {
            $entity->FI_date_assigned1 = $this->Planes->dateFormatBeforeSave($entity->FI_date_assigned1);
        }

        if (!empty($entity->FI_date_unassigned1)) {
            $entity->FI_date_unassigned1 = $this->Planes->dateFormatBeforeSave($entity->FI_date_unassigned1);
        }

        if (!empty($entity->FI_date_assigned2)) {
            $entity->FI_date_assigned2 = $this->Planes->dateFormatBeforeSave($entity->FI_date_assigned2);
        }

        if (!empty($entity->FI_date_unassigned2)) {
            $entity->FI_date_unassigned2 = $this->Planes->dateFormatBeforeSave($entity->FI_date_unassigned2);
        }

        //CA
        if (!empty($entity->CA_date_assigned1)) {
            $entity->CA_date_assigned1 = $this->Planes->dateFormatBeforeSave($entity->CA_date_assigned1);
        }

        if (!empty($entity->CA_date_unassigned1)) {
            $entity->CA_date_unassigned1 = $this->Planes->dateFormatBeforeSave($entity->CA_date_unassigned1);
        }

        if (!empty($entity->CA_date_assigned2)) {
            $entity->CA_date_assigned2 = $this->Planes->dateFormatBeforeSave($entity->CA_date_assigned2);
        }

        if (!empty($entity->CA_date_unassigned2)) {
            $entity->CA_date_unassigned2 = $this->Planes->dateFormatBeforeSave($entity->CA_date_unassigned2);
        }
        
        return true;
    }

    //Save Duty Assignment data
    public function saveDutyAssignmentData($postData)
    {
        if(!empty($postData)) {
            //Unset values if checkbox not selected
            if(isset($postData['PIC_type1']) && ($postData['PIC_type1'] == 'false' || $postData['PIC_type1'] == '')) {
                unset($postData['PIC_type1']);
                unset($postData['PIC_designation1']);
                unset($postData['PIC_date_assigned1']);
                unset($postData['PIC_date_unassigned1']);
            }

            if(isset($postData['PIC_type2']) && ($postData['PIC_type2'] == 'false' || $postData['PIC_type2'] == '')) {
                unset($postData['PIC_type2']);
                unset($postData['PIC_designation2']);
                unset($postData['PIC_date_assigned2']);
                unset($postData['PIC_date_unassigned2']);
            }

            if(isset($postData['SIC_type1']) && ($postData['SIC_type1'] == 'false' || $postData['SIC_type1'] == '')) {
                unset($postData['SIC_type1']);
                unset($postData['SIC_designation1']);
                unset($postData['SIC_date_assigned1']);
                unset($postData['SIC_date_unassigned1']);
            }

            if(isset($postData['SIC_type2']) && ($postData['SIC_type2'] == 'false' || $postData['SIC_type2'] == '')) {
                unset($postData['SIC_type2']);
                unset($postData['SIC_designation2']);
                unset($postData['SIC_date_assigned2']);
                unset($postData['SIC_date_unassigned2']);
            }

            if(isset($postData['GI_type1']) && ($postData['GI_type1'] == 'false' || $postData['GI_type1'] == '')) {
                unset($postData['GI_type1']);
                unset($postData['GI_designation1']);
                unset($postData['GI_date_assigned1']);
                unset($postData['GI_date_unassigned1']);
            }

            if(isset($postData['GI_type2']) && ($postData['GI_type2'] == 'false' || $postData['GI_type2'] == '')) {
                unset($postData['GI_type2']);
                unset($postData['GI_designation2']);
                unset($postData['GI_date_assigned2']);
                unset($postData['GI_date_unassigned2']);
            }

            if(isset($postData['FI_type1']) && ($postData['FI_type1'] == 'false' || $postData['FI_type1'] == '')) {
                unset($postData['FI_type1']);
                unset($postData['FI_designation1']);
                unset($postData['FI_date_assigned1']);
                unset($postData['FI_date_unassigned1']);
            }

            if(isset($postData['FI_type2']) && ($postData['FI_type2'] == 'false' || $postData['FI_type2'] == '')) {
                unset($postData['FI_type2']);
                unset($postData['FI_designation2']);
                unset($postData['FI_date_assigned2']);
                unset($postData['FI_date_unassigned2']);
            }

            if(isset($postData['CA_type1']) && ($postData['CA_type1'] == 'false' || $postData['CA_type1'] == '')) {
                unset($postData['CA_type1']);
                unset($postData['CA_designation1']);
                unset($postData['CA_date_assigned1']);
                unset($postData['CA_date_unassigned1']);
            }

            if(isset($postData['CA_type2']) && ($postData['CA_type2'] == 'false' || $postData['CA_type2'] == '')) {
                unset($postData['CA_type2']);
                unset($postData['CA_designation2']);
                unset($postData['CA_date_assigned2']);
                unset($postData['CA_date_unassigned2']);
            }

            $dutyAssignment = $this->newEntity();
            $dutyAssignment = $this->patchEntity($dutyAssignment, $postData);
            if($this->save($dutyAssignment)) {
                return true;
            }
            return false;
        }
    }
}
