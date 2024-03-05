<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerOTCInfoInvoicePart extends entity{
    protected $_accessible = [
        'customer_id'=>true,
        'otc_invoice_id'=>true,
        'part_number'=>true,
        'superseding_part_number'=>true,
        'part_description'=>true,
        'qty_needed'=>true,
        'qty_stock'=>true,
        'qty_cust_owned'=>true,
        'donot_deduct_from_stock'=>true,
        'price_each'=>true,
        'give_discount_percentage'=>true,
        'part_total_prices'=>true,
        'part_conditions'=>true,
        'serial_number'=>true,
        'aoc_charge'=>true,
        'part_ship_in'=>true,
        'drop_ship_charges'=>true,
        'ship_out'=>true,
        'misc_charges'=>true,
        'hazardous_fee'=>true,
        'part_taxable'=>true,
        'drop_ship_part'=>true,
        'show_net_with_price'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>