<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AirframeComponentPart Entity
 *
 * @property int $id
 * @property int $plane_id
 * @property int $airframe_component_id
 * @property int $airframe_category_id
 * @property string $ata_code
 * @property string $mfg_code
 * @property string $item_type
 * @property string $ad_sb_number
 * @property string $ad_sb_status
 * @property string $amendment
 * @property string $authority
 * @property int $position_id
 * @property string $description
 * @property string $notes
 * @property string $disposition
 * @property string $reference
 * @property string $requirement_type
 * @property string $tags
 * @property string $part_number
 * @property string $serial_number
 * @property string $installed_status
 * @property string $work_description
 * @property string $discrepancy_status
 * @property string $hardware
 * @property string $software
 * @property string $mm_ref
 * @property string $ops_numbers
 * @property int $avg_man_hrs
 * @property int $approx_price
 * @property int $manufacturer
 * @property string $admin_notes
 * @property string $work_card
 * @property string $position
 * @property string $revision
 * @property string $version
 * @property int $parent_id
 * @property int $clone_id
 * @property string $quick_ref
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $updated_by
 */
class AirframeComponentPart extends Entity
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
        'id' => true,
        'plane_id' => true,
        'airframe_component_id' => true,
        'airframe_category_id' => true,
        'ata_code' => true,
        'mfg_code' => true,
        'item_type' => true,
        'item_type_id' => true,
        'ad_sb_number' => true,
        'ad_sb_status' => true,
        'amendment' => true,
        'authority' => true,
        'issuing_authority_id' => true,
        'position_id' => true,
        'requirement_source_id' => true,
        'moc_id' => true,
        'moc' => true,
        'description' => true,
        'notes' => true,
        'disposition'=>true,
        'reference'=>true,
        'requirement_type'=>true,
        'requirement_type_id'=>true,
        'tags'=>true,
        'part_number' => true,
        'serial_number' => true,
        'installed_status' => true,
        'work_description' => true,
        'discrepancy_status' => true,
        'hardware' => true,
        'software' => true,
        'mm_ref' => true,
        'ops_numbers' => true,
        'avg_man_hrs' => true,
        'approx_price' => true,
        'manufacturer' => true,
        'admin_notes' => true,
        'work_card' => true,
        'position' => true,
        'revision' => true,
        'version' => true,
        'parent_id' => true,
        'clone_id' => true,
        'quick_ref' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];
}
