<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Part Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class CustomerOTCAircraft extends Entity
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
        'customer_id'=>true,
        'aircraft_registration_number'=>true,
        'aircraft_make_id'=>true,
        'aircraft_model_id'=>true,
        'aircraft_year'=>true,
        'aircraft_engine_type'=>true,
        'aircraft_serial'=>true,
        'aircraft_class'=>true,
        'aircraft_location'=>true,
        'aircraft_parts_program'=>true,
        'aircraft_fuel_code'=>true,
        'aircraft_fleet_ac'=>true,
        'aircraft_is_owner'=>true,
        'aircraft_rate'=>true,
        'aircraft_labor_discount'=>true,
        'aircraft_labor_discount_percentage'=>true,
        'aircraft_part_discount'=>true,
        'aircraft_part_discount_percentage'=>true,
        'aircraft_labor'=>true,
        'aircraft_parts'=>true,
        'aircraft_hide_on_upcoming_report'=>true,
        'aircraft_use_contract_pricing'=>true,
        'aircraft_part_discount_over_cost'=>true,
        'aircraft_use_specific_rate_hrs'=>true,
        'aircraft_use_specific_rate_hrs_val'=>true,
        'aircraft_default_department'=>true,
        'aircraft_qbclass_override'=>true,
        'aircraft_estimate'=>true,
        'aircraft_log_book'=>true,
        'aircract_applicable_report'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}