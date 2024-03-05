<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\App;

class InventoryStatusHTMLHelper extends Helper
{
    public function getInventoriesStatusHTML($status){
        $statushtml = '';
                
        if($status == '1'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-active">Active</div>';
        }else if($status == '2'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-installed">Installed</div>';
        }else if($status == '3'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-consumed">Consumed</div>';
        }else if($status == '4'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-discarded">Discarded</div>';
        }else if($status == '5'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-damaged">Damaged</div>';
        }else if($status == '6'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-unavailable">Unavailable</div>';
        }else if($status == '7'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-outforrepair">Out For Repair</div>';
        }else if($status == '8'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-unrepairable">Unrepairable</div>';
        }else if($status == '9'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-onorder">In-Transit</div>';
        }else if($status == '10'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-shipped">Shipped</div>';
        }else if($status == '11'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-allocated">Allocated</div>';
        }else if($status == '12'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-quarantined">Quarantined</div>';
        }else if($status == '13'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator pi-status-needs-repair">Need Rapair</div>';
        }else if($status == '14'){
            $statushtml = '<div data-html="true" placement="left" class="inventorystatus physical-inventory-status-indicator">Inactive</div>';
        }

        return $statushtml;
    }

    public function getPurchaseOrderOrderStatusHTML($status, $po_status, $po_type, $exchange_status){
        $statushtml = '';
        if($status == '1'){
            if($po_status == '1'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial</div>';
            }else if($po_status == '0'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
            }else if($po_status == '3'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
            }else if($po_status == '5'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">PO Created</div>';
            }else if($po_status == '2'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Sent</div>';
            }else if($po_status == '4'){
                $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</div>';
            }
        }else{
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status po-inactive">Inactive</div>';
        }

        if($po_type == '2'){
            if($exchange_status == '1'){
                $statushtml .= '<div class="exchange-status-indicator ml-10">
                                        <span title="Core exchange status" class="badge badge-dark exchange-status-badge">
                                        <i class="fa fa-exchange"></i>
                                        <span>&nbsp;None</span>
                                    </span>
                                </div>';
            }else if($exchange_status == '2'){
                $statushtml .= '<div class="exchange-status-indicator ml-10">
                                        <span title="Core exchange status" class="badge badge-dark exchange-status-badge exchange-status-open">
                                        <i class="fa fa-exchange"></i>
                                        <span>&nbsp;Open</span>
                                    </span>
                                </div>';
            }else if($exchange_status == '3'){
                $statushtml .= '<div class="exchange-status-indicator ml-10">
                                        <span title="Core exchange status" class="badge badge-dark exchange-status-badge exchange-status-returned">
                                        <i class="fa fa-exchange"></i>
                                        <span>&nbsp;Returned</span>
                                    </span>
                                </div>';
            }
        }

        return $statushtml;
    }

    public function getRepairOrderStatusHTML($status, $ro_status){
        if($ro_status == '1'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial Received</span>';
        }else if($ro_status == '0'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</span>';
        }else if($ro_status == '3'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</span>';
        }else if($ro_status == '2'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">To Vendor</span>';
        }else if($ro_status == '4'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</span>';
        }
        
        if($status == '2'){
            $statushtml .= '<span data-html="true" placement="left" class="inventory_request_status">Inactive</span>'; 
        }

        return $statushtml;
    }

    public function getShippingOrderStatusHTML($status, $shipping_order_status){
        if($shipping_order_status == '1'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-partial">Partial</div>';
        }else if($shipping_order_status == '0'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
        }else if($shipping_order_status == '3'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-cancelled">Canceled</div>';
        }else if($shipping_order_status == '5'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">RO Created</div>';
        }else if($shipping_order_status == '2'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-shipped">Shipped</div>';
        }else if($shipping_order_status == '4'){
            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-received">Received</div>';
        }

        if($status == '2'){
            $statushtml .= '<span data-html="true" placement="left" class="inventory_request_status">Inactive</span>'; 
        }

        return $statushtml;
    }

    public function getInventoryRequestStatusHTML($status, $request_status){
        if($request_status == '1'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Approved</span>';
        }else if($request_status == '0'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Pending Review</span>';
        }else if($request_status == '2'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</span>';
        }else if($request_status == '5'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-created">PO Created</span>';
        }else if($request_status == '3'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-denied">Denied</span>';
        }else if($request_status == '4'){
            $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</span>';
        }
        
        if($status == '0'){
            $statushtml .= '<span data-html="true" placement="left" class="inventory_request_status">Inactive</span>'; 
        }

        return $statushtml;
    }
}

?>