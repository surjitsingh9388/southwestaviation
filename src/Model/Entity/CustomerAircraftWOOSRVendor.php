<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOSRVendor extends entity{
    protected $_accessible = [
        'vendor_name'=>true,
        'vendor_contact'=>true,
        'vendor_terms'=>true,
        'vendor_phone'=>true,
        'address'=>true,
        'ship_address'=>true,
        'alt_phone'=>true,
        'address2'=>true,
        'ship_address2'=>true,
        'alt_phone2'=>true,
        'city'=>true,
        'ship_city'=>true,
        'fax'=>true,
        'state'=>true,
        'zip'=>true,
        'ship_state'=>true,
        'ship_zip'=>true,
        'email'=>true,
        'country'=>true,
        'ship_country'=>true,
        'website'=>true,
        'account_number'=>true,
        'no_days_until_cores_due'=>true,
        'website_notes'=>true,
        'currency'=>true,
        'approval_expires'=>true,
        'vendor_class'=>true,
        'aleays_use_this_currency'=>true,
        'show_country_on_printouts'=>true,
        'has_separate_shipping_address'=>true,
        'is_approved'=>true,
        'not_approved'=>true,
        'does_tool_calbrations'=>true,
        'does_outside_repair'=>true,
        'po_tax_rate'=>true,
        'total_amount_spent'=>true,
        'vendor_notes'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>