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

class AddressComponent extends Component{

    /**
     * This function returns the list of all countries
     * @return array $countries
     */
    public function getCountryList() {
        $countryList = $countries = array();
        $countryModel = TableRegistry::get('Countries');
        $countries = $countryModel->find('list', array (
                'keyField' => 'id',
                'valueField' => 'name'
            ))->toArray();
        return $countries;
    }
    
    /**
     * This function returns the list of all states of selected country
     * @return array $states
     */
    public function getStateListByCountryId($countryId) {
        $states = array();
        $stateModel = TableRegistry::get('States');
        $states = $stateModel->find('list', array (
                    'keyField' => 'id', 
                    'valueField' => 'name'
            ))->where(array('country_id' => $countryId))->toArray();
        return $states;
    }
    
    /**
     * This function returns the list of all cities of selected state
     * @return array $states
     */
    public function getCityListByStateId($stateId) {
        $cities = array();
        $cityModel = TableRegistry::get('Cities');
        $cities = $cityModel->find('list', array (
                'keyField' => 'id', 
                'valueField' => 'name'
            ))->where(array('Cities.state_id' => $stateId))->toArray();
        return $cities;
    }
}
