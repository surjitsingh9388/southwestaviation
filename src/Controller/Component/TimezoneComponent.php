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
use Cake\I18n\Date;
use Cake\Datasource\FactoryLocator;


class TimezoneComponent extends Component{

    protected \App\Model\Table\TimezonesTable $Timezones;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * This function returns the list of all timezones
     * @return array
     */
    public function getTimezonesList() {
        $timezones = array();
        $timezoneModel = $this->getController()->fetchTable('Timezones');
        $timezones = $timezoneModel->find('list',[
            'keyField' => 'code',
            'valueField' => 'description'
        ])->toArray();
        
        return $timezones;
    }

    /**
     * This function returns the list of all timezones
     * @return array
     */
    public function getTimezonesData() {
        $timezoneArr = array();
        $timezoneModel = $this->getController()->fetchTable('Timezones');
        $timezones = $timezoneModel->find()->toArray();
        
        foreach ($timezones as $key => $value) {
            $timezoneArr[$value->id] = $value->timezone;
        }
        return $timezoneArr;
    }

    /**
     * This function returns system datetime to converted datetime of different flight zone
     * @return datetime
     */
    public function changeDateTimeCTtoOther($currentDate = null, $flightTimeZone = null) 
    {
        $flightTimeZone =  !empty($flightTimeZone)?$flightTimeZone:'America/Chicago';
        $dt = new \DateTime($currentDate);
        $tz = new \DateTimeZone($flightTimeZone);
        $dt->setTimezone($tz);
        $currentDate = $dt->format('Y-m-d H:i:s');
        return $currentDate;
    }

    /**
     * This function returns converted datetime to CST timezone
     * @return datetime
     */
    public function changeDateTimeToCT($currentDate = null, $flightTimeZone = null) 
    {
        $currentDate = date('Y-m-d H:i:s', strtotime($currentDate));
        $date = new \DateTime($currentDate, new \DateTimeZone($flightTimeZone));
        $date->setTimezone(new \DateTimeZone('America/Chicago'));
        $date = $date->format('Y-m-d H:i:s');
        return $date;
    }

    /**
     * This function returns converted datetime from one timezeone to another timezone
     * @return datetime
     */
    public function changeDateTime($currentDate = null, $flightTimeZone1 = null, $flightTimeZone2 = null) 
    {
        $date = new \DateTime($currentDate, new \DateTimeZone($flightTimeZone1));
        $date->setTimezone(new \DateTimeZone($flightTimeZone2));
        $date = $date->format('Y-m-d H:i:s');
        return $date;
    }
    
    /**
     * This function return the timezone name based on timezone code
     * @return string $timezone
     */
    public function getTimezoneNameByCode($code) {
        $timezone = '';
        $timezoneModel = $this->getController()->fetchTable('Timezones');
        $query = $timezoneModel->find()
                ->select(['timezone'])->where(['code' => $code])->all();
        if (!$query->isEmpty()) {
            $data = $query->first()->toArray();
            $timezone = $data['timezone']; 
        }
        return $timezone;
    }
}
