<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DutyAssignment Entity
 *
 * @property int $id
 * @property int $pilot_id
 * @property string $director_operation
 * @property string $chief_pilot
 * @property string $director_maintenance
 * @property string $PIC_type1
 * @property string $PIC_designation1
 * @property \Cake\I18n\FrozenTime $PIC_date_assigned1
 * @property \Cake\I18n\FrozenTime $PIC_date_unassigned1
 * @property string $PIC_type2
 * @property string $PIC_designation2
 * @property \Cake\I18n\FrozenTime $PIC_date_assigned2
 * @property \Cake\I18n\FrozenTime $PIC_date_unassigned2
 * @property string $SIC_type1
 * @property string $SIC_designation1
 * @property \Cake\I18n\FrozenTime $SIC_date_assigned1
 * @property \Cake\I18n\FrozenTime $SIC_date_unassigned1
 * @property string $SIC_type2
 * @property string $SIC_designation2
 * @property \Cake\I18n\FrozenTime $SIC_date_assigned2
 * @property \Cake\I18n\FrozenTime $SIC_date_unassigned2
 * @property string $GI_type1
 * @property string $GI_designation1
 * @property \Cake\I18n\FrozenTime $GI_date_assigned1
 * @property \Cake\I18n\FrozenTime $GI_date_unassigned1
 * @property string $GI_type2
 * @property string $GI_designation2
 * @property \Cake\I18n\FrozenTime $GI_date_assigned2
 * @property \Cake\I18n\FrozenTime $GI_date_unassigned2
 * @property string $FI_type1
 * @property string $FI_designation1
 * @property \Cake\I18n\FrozenTime $FI_date_assigned1
 * @property \Cake\I18n\FrozenTime $FI_date_unassigned1
 * @property string $FI_type2
 * @property string $FI_designation2
 * @property \Cake\I18n\FrozenTime $FI_date_assigned2
 * @property \Cake\I18n\FrozenTime $FI_date_unassigned2
 * @property string $CA_type1
 * @property string $CA_designation1
 * @property \Cake\I18n\FrozenTime $CA_date_assigned1
 * @property \Cake\I18n\FrozenTime $CA_date_unassigned1
 * @property string $CA_type2
 * @property string $CA_designation2
 * @property \Cake\I18n\FrozenTime $CA_date_assigned2
 * @property \Cake\I18n\FrozenTime $CA_date_unassigned2
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Pilot $pilot
 */
class DutyAssignment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        '*' => true
    ];
}
