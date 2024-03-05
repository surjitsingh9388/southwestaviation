<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerOTCInfoInvoice extends entity{
    protected $_accessible = [
        'customer_id'=>true,
        'otc_invoice_no'=>true,
        'invoice_customer_phone'=>true,
        'invoice_date_created'=>true,
        'invoice_created_by'=>true,
        'invoice_ship_to'=>true,
        'invoice_terms'=>true,
        'invoice_buyer_contact'=>true,
        'invoice_customer_po_no'=>true,
        'invoice_shipping_method'=>true,
        'additional_ship_in_cost'=>true,
        'other_shipping_address'=>true,
        'invoice_status'=>true,
        'additional_ship_out_cost'=>true,
        'invoice_tracking_number'=>true,
        'status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>