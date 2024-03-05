<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class CustomerAircraftWOOptionGenInfoDeposit extends entity{
    protected $_accessible = [
        'general_info_id'=>true,
        'amount_to_add'=>true,
        'currency'=>true,
        'payment_method'=>true,
        'check_number'=>true,
        'credit_card_type'=>true,
        'name_of_credit_card'=>true,
        'credit_card'=>true,
        'more_info'=>true,
        'expires'=>true,
        'cc_authoration_number'=>true,
        'deposit_date'=>true,
        'paid_by'=>true,
        'deposit_status'=>true,
        'added_by'=>true,
        'updated_by'=>true,
        'created_at'=>true,
        'updated_at'=>true,
    ];
}

?>