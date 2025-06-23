<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;

class AirportComponent extends Component{
    
    /**
     * This function returns fbo details
     * @return array $airport
     */
    public function getAirportInformations() {
        $airportInfo = array();
        $airportsModel = $this->getController()->fetchTable('Airports');
        $airportInfo = $airportsModel->find()->contain(['Addresses' => ['Cities', 'States'], 'ElectronicAddresses' => ['Companies']])->toArray();
        //echo '<pre>';print_r($airportInfo);exit;
        return $airportInfo;
    }
    
    /**
     * This function returns the flight number list
     * @return array $flightNumbers
     */
    public function getFlightNumberList() {
        $flightNumbers = array();
        $flightNumberModel = $this->getController()->fetchTable('flight_numbers');
        $flightNumberResult = $flightNumberModel->find('all')->select(['flight_number']);
        if (!$flightNumberResult->isEmpty()) {
            $flightNumbers = $flightNumberResult->toArray();
        }
        return $flightNumbers;
    }
    
    /**
     * This function returns the list of plane names
     * @return array $planes
     */
    public function getPlaneList() {
        $planes = array();
        $planeModel = $this->getController()->fetchTable('planes');
        $planeResult = $planeModel->find('all')->select(['plane_name']);
        if (!$planeResult->isEmpty()) {
            $planes = $planeResult->toArray();
        }
        return $planes;
    }
}
