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

class PilotComponent extends Component {
    
    public function savePilotAndAssociatedData($pilot=array(), $postData=array(), $type=null)
    {
        $pilotModel = TableRegistry::get('Pilots');
        $pilot = $pilotModel->patchEntity($pilot, $postData);

        if ($pilotModel->save($pilot)) {

            $postData['pilot_id'] = $pilot->id;

            //Save Duty Assignment data
            $dutyAssignModel = TableRegistry::get('DutyAssignments');
            $dutyAssignModel->saveDutyAssignmentData($postData);

            //Save Pilot Certificates data
            $pilotCertModel = TableRegistry::get('PilotCertificates');
            $pilotCertModel->savePilotCertificateData($postData);

            //Save Pilot Checking data
            $pilotCheckModel = TableRegistry::get('PilotCheckings');
            $pilotCheckModel->savePilotCheckingData($postData);

            //Save Pilot Training data
            $pilotTrainModel = TableRegistry::get('PilotTrainings');
            $pilotTrainModel->savePilotTrainingData($postData);

            return true;  
        }
        return false;
    }

    //Years list
    public function getYearsList()
    {
        $options = [];
        for($i=10; $i<11;$i++) {
            $options[$i] = $i.' Years';
        }
        return $options;
    }

    //DL Years list
    public function getDLYearsList()
    {
        $options = []; 
        for($i=1; $i<11;$i++) {
            $options[$i] = $i.' Years';
        }
        return $options;
    }

    //Months list
    public function getMonthsList()
    {
        $monthsList = [
                    'january'=>'January',
                    'february'=>'February',
                    'march'=>'March',
                    'april'=>'April',
                    'may'=>'May',
                    'june'=>'June',
                    'july'=>'July',
                    'august'=>'August',
                    'september'=>'September',
                    'october'=>'October',
                    'november'=>'November',
                    'december'=>'December'
                ];
        return $monthsList;
    }

    //Days list
    public function getDaysList()
    {
        $daysList = [
                '120'=>'120 Days',
                //'60'=>'60 Days',
                //'30'=>'30 Days'
            ];
        return $daysList;
    }

    //Class list
    public function getClassList()
    {
        $classList = [
            'first'=>'First Class',
            'second'=>'Second Class',
            'third'=>'Third Class'
        ];
        return $classList;
    }

    //Pilots list
    public function getPilots() {
        $pilotModel = TableRegistry::get('Pilots');
        $pilots = $pilotModel->find()->select(['Pilots.id', 'Users.first_name', 'Users.last_name'])->contain(['Users'])->enableHydration(false)->toArray();
        $results = array();
        foreach ($pilots as $key => $value) {
            $results[$value['id']] = $value['user']['last_name'].', '.$value['user']['first_name'];
        }
        return $results;
    }

    public function allPilots() {
        $pilotModel = TableRegistry::get('Pilots');
        $pilots = $pilotModel->find()->select(['Pilots.id', 'Users.first_name', 'Users.last_name'])->contain(['Users'])->enableHydration(false)->toArray();
        $results = array();
        foreach ($pilots as $key => $value) {
            $results[$key]['id'] = $value['id'];
            $results[$key]['first_name'] = $value['user']['first_name'];
            $results[$key]['last_name'] = $value['user']['last_name'];
        }
        return $results;
    }

    //Pilots Name
    public function getPilotName($id) {
        $pilotModel = TableRegistry::get('Pilots');
        $pilot = $pilotModel->find()->where(['Pilots.id'=>$id])->select(['Pilots.id', 'Users.first_name', 'Users.last_name'])->contain(['Users'])->enableHydration(false)->first();
        $name = '';
        if(!empty($pilot['user']['first_name']) || !empty($pilot['user']['last_name'])) {
            $name =  ucfirst($pilot['user']['first_name'].' '.$pilot['user']['last_name']);
        }
        return $name;
    }

    //Add years
    public function getYNextDue($frequency=null, $lastCompleted=null) {
        $res = '';
        $frequency = (int)trim($frequency);
        if(!empty($frequency) && !empty($lastCompleted)) {
            $res = date('m/Y', strtotime("+".$frequency." years", strtotime($lastCompleted)));
        }
        return $res;
    }

    //Add months
    public function getMMNextDue($frequency=null, $lastCompleted=null) {
        $frequency = (int)trim($frequency);
        $res = '';
        if(!empty($frequency) && !empty($lastCompleted)) {
            $res = date('m/Y', strtotime("+".$frequency." months", strtotime($lastCompleted)));
        }
        return $res;
    }

    //Add days
    public function getDNextDue($frequency=null, $lastCompleted=null) {
        $res = '';
        $frequency = (int)trim($frequency);
        if(!empty($frequency) && !empty($lastCompleted)) {
            $res = date('m/Y', strtotime("+".$frequency." days", strtotime($lastCompleted)));
        }
        return $res;
    }

    //Add months(Checking/Training)
    public function getMNextDue($baseMonth=null, $frequency=null, $lastCompleted=null) {
        $frequency = (int)trim($frequency);
        $res  = '';
        if(!empty($baseMonth) && !empty($frequency) && !empty($lastCompleted)) {
            //Added frequency in last completed
            $date1 = date('d-m-Y', strtotime("+".$frequency." months", strtotime($lastCompleted)));

            //Month of above date
            $frqAddMonth = strtolower(date('F', strtotime($date1)));
            
            //Custom date(base month with current year)
            $date2 = date('d-m-Y', strtotime($baseMonth.date("Y")));

            //Months difference if base month and last completed date's month is different
            $mDiff = $this->getMonthInterval($date1, $date2);
            
            //If base month and last completed date's month is same
            $res = date('m/Y', strtotime($date1));

            //If base month and last completed date's month is not same
            /*if($baseMonth != $frqAddMonth) 
            {
                $yr = date('Y', strtotime($lastCompleted));
                $res = date('m/Y', strtotime("+".$frequency." months", strtotime($baseMonth.$yr)));
            }*/ 
            
            if($frequency == 6) 
            {
                $yr = date('Y', strtotime($lastCompleted));
                $res = date('m/Y', strtotime("+".$frequency." months", strtotime($baseMonth.$yr)));
            } 
            elseif($mDiff < 0 && $baseMonth != $frqAddMonth) 
            {
                $yr = date('Y', strtotime("+1 years", strtotime($date1)));
                $res = date('m/Y', strtotime($baseMonth.$yr));
            }
        }
        return $res;
    }

    //Get months interval
    public function getMonthInterval($from, $to) {
        $month_in_year = 12;
        $date_from = getdate(strtotime($from));
        $date_to = getdate(strtotime($to));
        return ($date_to['year'] - $date_from['year']) * $month_in_year -
            ($month_in_year - $date_to['mon']) +
            ($month_in_year - $date_from['mon']);
    }

    //Tolltip
    public function toolTipMsg() {
        $tooltip = '<span class="input-group-addon" style="padding: 1px 5px;">
                        <span class="remT" style="cursor:pointer;font-size:15px;padding:0;"><i class="fa fa-close"></i></span>
                        <br>
                        <a href="#" class="nextDTTip" data-toggle="tooltip" title="This Next Due is direct updated!"><i class="fa fa-info-circle"></i></a>
                    </span>';
        return $tooltip;
    }

    //Get status color
    public function getStatusColor($nextdue=null, $lastCompleted=null) {
        $colorCls = '';
        if(!empty($nextdue) && !empty($lastCompleted)) {
            $lcDay    = date('d', strtotime($lastCompleted));  
            $nextdue  = date("m/d/Y", strtotime(str_replace('/', '-', $lcDay.'/'.$nextdue)));
            $currDate = date('m/d/Y');

            $date1    = new \DateTime($nextdue);
            $date2    = new \DateTime($currDate);
            $daysDiff = $date2->diff($date1)->format("%a");

            if($date1 < $date2) {
                $daysDiff = !empty($daysDiff) ? -$daysDiff : 0;
            }
            
            /*if(!empty($daysDiff) && $daysDiff < 0) {
                $colorCls  = 'ndred';
            } elseif(!empty($daysDiff) && $daysDiff > 0 && $daysDiff <= 60) {
                $colorCls  = 'ndblue';
            } elseif (!empty($daysDiff) && $daysDiff > 60) {
                $colorCls  = 'ndgreen';
            }*/

            if(!empty($daysDiff) && $daysDiff < 0 && $daysDiff > -20) {
                $colorCls  = 'ndyellow';
            } elseif(!empty($daysDiff) && $daysDiff < -20) {
                $colorCls  = 'ndred';
            } elseif(!empty($daysDiff) && $daysDiff > 0 && $daysDiff < 10) {
                $colorCls  = 'ndblue';
            } elseif (!empty($daysDiff) && $daysDiff >= 10) {
                $colorCls  = 'ndgreen';
            }
        }
        return $colorCls;
    }

    //Next due process
    public function nextdueProcess($params=array(), $title=null,  $type=null) {
        $res = [];
        $res['nextdue']  = '';
        $res['tooltip']  = '';
        $res['colorcls'] = '';
        $res['disabled'] = '';
        if(!empty($params)) {
            if($type == 'mm') {

                $res['nextdue'] = $this->getMMNextDue($params[$title.'_frequency'], $params[$title.'_last_completed']);
                if(!empty($params[$title.'_nextdue']) && $params[$title.'_nextdue_status'] == 'yes') {
                    $res['nextdue'] = $params[$title.'_nextdue'];
                    $res['tooltip'] = $this->toolTipMsg();
                }
                $res['colorcls'] = $this->getStatusColor($res['nextdue'], $params[$title.'_last_completed']);

            } elseif ($type == 'yy') {

                $res['nextdue'] = $this->getYNextDue($params[$title.'_frequency'], $params[$title.'_last_completed']);
                if(!empty($params[$title.'_nextdue']) && $params[$title.'_nextdue_status'] == 'yes') {
                    $res['nextdue'] = $params[$title.'_nextdue'];
                    $res['tooltip'] = $this->toolTipMsg();
                }
                $res['colorcls'] = $this->getStatusColor($res['nextdue'], $params[$title.'_last_completed']);

            } elseif ($type == 'dd') {

                $res['nextdue'] = $this->getDNextDue($params[$title.'_frequency'], $params[$title.'_last_completed']);
                if(!empty($params[$title.'_nextdue']) && $params[$title.'_nextdue_status'] == 'yes') {
                    $res['nextdue'] = $params[$title.'_nextdue'];
                    $res['tooltip'] = $this->toolTipMsg();
                }
                $res['colorcls'] = $this->getStatusColor($res['nextdue'], $params[$title.'_last_completed']);

            } else {

                $res['nextdue'] = $this->getMNextDue($params[$title.'_month'], $params[$title.'_frequency'], $params[$title.'_last_completed']);
                if(!empty($params[$title.'_nextdue']) && $params[$title.'_nextdue_status'] == 'yes') {
                    $res['nextdue'] = $params[$title.'_nextdue'];
                    $res['tooltip'] = $this->toolTipMsg();
                }
                $res['colorcls'] = $this->getStatusColor($res['nextdue'], $params[$title.'_last_completed']);
            }
        }

        if(empty($res['colorcls'])) {
            $res['disabled'] = 'disabled';
        }
        
        return $res;
    }

    //Total hours in a day
    public function getDayHours() {
        $options = []; 
        for($i=0; $i<24;$i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    //Total minutes in hour
    public function getHoursMinute() {
        $options = []; 
        for($i=0; $i<60;$i++) {
            $options[$i] = $i;
        }
        return $options;
    }

    //Add times and display total hours
    public function addTimes(array $times) {
        $seconds = 0;
        foreach ($times as $time)
        {
            if(!empty($time)) {
                list($hour,$minute,$second) = array_pad(explode(':', $time),3,null);
                $seconds += $hour*3600;
                $seconds += $minute*60;
                $seconds += $second;
            }
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    //Time difference
    public function timeDiffFL($time1, $time2)
    {
        if(strtotime($time2) < strtotime($time1)) {
            $time2 = "24:00";
        }

        $time1 = new \DateTime($time1);
        $time2 = new \DateTime($time2);
        $timediff = $time1->diff($time2);
        return $timediff->format('%H:%I');
    }

    //Total duty time if lied in next day
    public function dutyTimeLength($time1, $time2)
    {
        if(strtotime($time2) < strtotime($time1)) {
            $time3 = "24:00";
            $time1 = new \DateTime($time1);
            $time3 = new \DateTime($time3);
            $timediff = $time1->diff($time3);
            $dtDiff = $timediff->format('%H:%I:%S');

            return $this->addTimes([$dtDiff, $time2.':00']);

        } else {
            $time1 = new \DateTime($time1);
            $time2 = new \DateTime($time2);
            $timediff = $time1->diff($time2);
            return $timediff->format('%H:%I');
        }
    }

     //Time difference
    public function crewDutyTime($time1, $time2)
    {
        $nextDayTime = "00:00";
        if(strtotime($time2) < strtotime($time1)) {
            $nextDayTime = $time2;
            $time2 = "24:00";
        }

        $time1 = new \DateTime($time1);
        $time2 = new \DateTime($time2);
        $timediff = $time1->diff($time2);
        $timeDiff = $timediff->format('%H:%I');
        $finalTime = $this->addTwoTimes($timeDiff, $nextDayTime);
        return $finalTime['resflHI'];
    }

    //Add two times, if more then 24 hours the disply next day lies time 
    public function addTwoTimes($time1, $time2) {
        $result = [];
        if(!empty($time1) && !empty($time2)) {
            $secs = strtotime($time2)-strtotime("00:00");
            $result['resflHI'] = date("H:i",strtotime($time1)+$secs);
            $result['resflH'] = date("H",strtotime($time1)+$secs).':00';
            $result['resflt'] = date("i",strtotime($time1)+$secs);
        }
        return $result;
    }

    //Add times - if crossed 24 hour then display next day hour
    public function addTimesMulti($times) {
        
        //$times = array($time1.':00', $time2.':00');
        
        $hou = 0;
        $min = 0;
        $sec = 0;
        $totaltime = '00:00';
        if(is_array($times)) {

            $length = sizeof($times);

            for($x=0; $x <= $length; $x++) {
                $split = explode(":", @$times[$x]); 
                @$hou += @$split[0];
                $min += @$split[1];
                $sec += @$split[2];
            }

            $seconds = $sec % 60;
            $minutes = $sec / 60;
            $minutes = (integer)$minutes;
            $minutes += $min;
            $hours = $minutes / 60;
            $minutes = $minutes % 60;
            $hours = (integer)$hours;
            $hours += $hou % 24;

            $hours = ($hours < 10) ? "0".$hours : $hours;
            $minutes = ($minutes < 10) ? "0".$minutes : $minutes;

            $totaltime = $hours.":".$minutes;
        }
        return $totaltime;
    }

    //Duty time value correction
    public function dtCorrectVal($value)
    { 
        //Duty time
        if((empty($value['duty_start_hour']) || $value['duty_start_hour'] == 0) || $value['duty_start_hour'] < 10) {
            //$value['duty_start_hour'] = '0'.$value['duty_start_hour'];
            $value['duty_start_hour'] = sprintf("%02d", $value['duty_start_hour']);
        }

        if((empty($value['duty_start_minute']) || $value['duty_start_minute'] == 0) || $value['duty_start_minute'] < 10) {
            $value['duty_start_minute'] = '0'.$value['duty_start_minute'];
        }

        if((empty($value['duty_stop_hour']) || $value['duty_stop_hour'] == 0) || $value['duty_stop_hour'] < 10) {
            $value['duty_stop_hour'] = '0'.$value['duty_stop_hour'];
        }

        if((empty($value['duty_stop_minute']) || $value['duty_stop_minute'] == 0) || $value['duty_stop_minute'] < 10) {
            $value['duty_stop_minute'] = '0'.$value['duty_stop_minute'];
        }
        return $value;
    }

    //Days off value correction
    public function doCorrectVal($value)
    { 
        //Days off
        if((empty($value['off_start_hour']) || $value['off_start_hour'] == 0) || $value['off_start_hour'] < 10) {
            $value['off_start_hour'] = '0'.$value['off_start_hour'];
        }

        if((empty($value['off_start_minute']) || $value['off_start_minute'] == 0) || $value['off_start_minute'] < 10) {
            $value['off_start_minute'] = '0'.$value['off_start_minute'];
        }
        return $value;
    }

    //Flight leg value correction
    public function flCorrectVal($value)
    { 
        //Flight leg
        if((empty($value['start_hour']) || $value['start_hour'] == 0) || $value['start_hour'] < 10) {
            $value['start_hour'] = '0'.$value['start_hour'];
        }

        if((empty($value['start_minute']) || $value['start_minute'] == 0) || $value['start_minute'] < 10) {
            $value['start_minute'] = '0'.$value['start_minute'];
        }

        if((empty($value['leg_hour']) || $value['leg_hour'] == 0) || $value['leg_hour'] < 10) {
            $value['leg_hour'] = '0'.$value['leg_hour'];
        }

        if((empty($value['leg_minute']) || $value['leg_minute'] == 0) || $value['leg_minute'] < 10) {
            $value['leg_minute'] = '0'.$value['leg_minute'];
        }

        if((empty($value['night_flt_hour']) || $value['night_flt_hour'] == 0) || $value['night_flt_hour'] < 10) {
            $value['night_flt_hour'] = '0'.$value['night_flt_hour'];
        }

        if((empty($value['night_flt_minute']) || $value['night_flt_minute'] == 0) || $value['night_flt_minute'] < 10) {
            $value['night_flt_minute'] = '0'.$value['night_flt_minute'];
        }

        if((empty($value['ifr_flight_hour']) || $value['ifr_flight_hour'] == 0) || $value['ifr_flight_hour'] < 10) {
            $value['ifr_flight_hour'] = '0'.$value['ifr_flight_hour'];
        }

        if((empty($value['ifr_flight_minute']) || $value['ifr_flight_minute'] == 0) || $value['ifr_flight_minute'] < 10) {
            $value['ifr_flight_minute'] = '0'.$value['ifr_flight_minute'];
        }

        return $value;
    }

    //Get previous date off
    public function getPrevDateOff($pilotId, $prevDate)
    {
        $result = [];
        if(!empty($pilotId) && !empty($prevDate)) {
            $daysOffModel = TableRegistry::get('DaysOff');
            $res = $daysOffModel->find()->where(['DaysOff.pilot_id'=>$pilotId, 'DaysOff.selected_date'=>$prevDate])->first();
            if($res) {
                $result = $res;
            }
        }
        return $result;
    }

    //Get previous date duty time
    public function getPrevDateDuty($pilotId, $prevDate)
    {
        $result = [];
        if(!empty($pilotId) && !empty($prevDate)) {
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $res = $dutyTimeModel->find()->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date'=>$prevDate])->first();
            if($res) {
                $result = $res;
            }
        }
        return $result;
    }

    //Get all current date duty time
    public function getCurrDateDutytime($pilotId, $prevDate)
    {
        $result = [];
        if(!empty($pilotId) && !empty($prevDate)) {
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $res = $dutyTimeModel->find()->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date'=>$prevDate])->enableHydration(false)->toArray();
            if($res) {
                $result = $res;
            }
        }
        return $result;
    }

    //Get flight leg details
    public function getCurrentDateFL($pilotId, $date)
    {
        $result = [];
        if(!empty($pilotId) && !empty($date)) {
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $result = $flightLegModel->find()->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date'=>$date])->order(['FlightLegDetails.id' =>'ASC'])->enableHydration(false)->first();
        }
        return $result;
    }

    //Get flight leg details
    public function getPrevDateFL($pilotId, $prevDate)
    {
        $result = [];
        if(!empty($pilotId) && !empty($prevDate)) {
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $res = $flightLegModel->find()->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date'=>$prevDate])->order(['FlightLegDetails.id' =>'DESC'])->first();
            if($res) {
                $result = $res;
            }
        }
        return $result;
    }

    //Change date format to 'm-d-Y'
    public function changeFormat($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('m/d/Y', strtotime($dateString));
    }

    //Timezone
    public function getTimezoneList_notused()
    {
        $timezone = [
                    'utc-dl-4'=>'(UTC-04:00) Eastern Daylight Time', 
                    'utc-dl-5'=>'(UTC-05:00) Central Daylight Time', 
                    'utc-dl-6'=>'(UTC-06:00) Mountain Daylight Time', 
                    'utc-dl-7'=>'(UTC-07:00) Pacific Daylight Time', 
                    'utc-std-5'=>'(UTC-05:00) Eastern Standard Time', 
                    'utc-std-6'=>'(UTC-06:00) Central Standard Time', 
                    'utc-std-7'=>'(UTC-07:00) Mountain Standard Time', 
                    'utc-std-8'=>'(UTC-08:00) Pacific Standard Time'
                ];
        return $timezone;
    }

}