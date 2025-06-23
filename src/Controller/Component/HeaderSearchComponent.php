<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class HeaderSearchComponent extends Component {
    public array $components = ['Authentication.Authentication'];

    public function searchAircraft($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, plane_name, plane_code FROM `planes` where plane_name LIKE '%".$search_txt."%' or plane_code LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchAirframeComponent($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT ac.id, ac.description, ac.log_book FROM `airframe_components` as ac where ac.log_book LIKE '%".$search_txt."%' or ac.description LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }
    
    public function searchSubComponent($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, title FROM `sub_components` where title LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchAirframeCompTimes($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT AirframeComponentTimes.`id`, Planes.`plane_code`, AirframeComponents.`log_book` FROM `airframe_component_times` AirframeComponentTimes LEFT JOIN `planes` Planes ON AirframeComponentTimes.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentTimes.`airframe_component_id` = AirframeComponents.`id` where Planes.`plane_code` LIKE '%".$search_txt."%' or AirframeComponents.`log_book` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchAirframeCompPart($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT AirframeComponentParts.`id`, Planes.`plane_code`, AirframeComponents.`log_book`,  AirframeComponentParts.`ata_code` FROM `airframe_component_parts` AirframeComponentParts LEFT JOIN `planes` Planes ON AirframeComponentParts.`plane_id` = Planes.`id` LEFT JOIN `airframe_components` AirframeComponents ON AirframeComponentParts.`airframe_component_id` = AirframeComponents.`id` where Planes.`plane_code` LIKE '%".$search_txt."%' or AirframeComponents.`log_book` LIKE '%".$search_txt."%' or AirframeComponentParts.`ata_code` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchATACode($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT AtaCodes.`id`, AtaCodes.`ata_code` FROM `ata_codes` AtaCodes where AtaCodes.`ata_code` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchDisposition($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT Dispositions.`id`, Dispositions.`title` FROM `dispositions` Dispositions where Dispositions.`title` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchADSB($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT AdsbStatus.`id`, AdsbStatus.`title` FROM `adsb_status` AdsbStatus where AdsbStatus.`title` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchPilots($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT Pilots.`id`, Users.`full_name`, Roles.`role_name` FROM `pilots` Pilots LEFT JOIN `users` Users ON Pilots.`user_id` = Users.`id` LEFT JOIN `roles` Roles ON Users.`role_id` = Roles.`id` where Users.`full_name` LIKE '%".$search_txt."%' or Roles.`role_name` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryItems($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, `name`, part_number FROM inventory_items where `name` LIKE '%".$search_txt."%' or part_number LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventory($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, serial_no FROM inventories where `serial_no` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryLocation($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, location_name FROM inventory_locations where `location_name` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryRequest($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, request_number FROM inventory_requests where `request_number` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchPurchaseOrders($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, po_number FROM inventory_purchase_orders where `po_number` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchRepairOrders($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, ro_number FROM inventory_repair_orders where `ro_number` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchShippingOrders($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, shipping_order_number FROM inventory_shipping_orders where `shipping_order_number` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryCustomer($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, customer_name FROM inventory_customers where `customer_name` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryVendor($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, `name` FROM inventory_vendors where `name` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchInventoryManufacturer($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, `name` FROM inventory_part_manufacturers where `name` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }

    public function searchWorkOrder($search_txt){
        $connection = ConnectionManager::get('default');
                
        $results = $connection->execute(
            "SELECT id, `work_order_no` FROM customer_aircraft_work_orders where `work_order_no` LIKE '%".$search_txt."%'")->fetchAll('assoc');

        return $results;
    }
}

?>