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
use Cake\Datasource\FactoryLocator;

class AddressComponent extends Component{

    protected \App\Model\Table\CountriesTable $Countries;
    protected \App\Model\Table\StatesTable $States;
    protected \App\Model\Table\CitiesTable $Cities;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * This function returns the list of all countries
     * @return array $countries
     */
    public function getCountryList() {
        $this->Countries = $this->getController()->fetchTable('Countries');

        $countryList = $countries = array();
        $countries = $this->Countries->find('list', array (
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
        $this->States = $this->getController()->fetchTable('States');

        $states = array();
        $states = $this->States->find('list', array (
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
        $this->Cities = $this->getController()->fetchTable('Cities');
        
        $cities = array();
        $cities = $this->Cities->find('list', array (
                'keyField' => 'id', 
                'valueField' => 'name'
            ))->where(array('Cities.state_id' => $stateId))->toArray();
        return $cities;
    }
}
