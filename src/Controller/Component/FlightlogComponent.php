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
use Cake\Datasource\FactoryLocator;

class FlightlogComponent extends Component {
    public array $components = ['User', 'Plane', 'Pilot', 'Timezone'];
    
    protected \App\Model\Table\FlightlogsTable $Flightlogs;
    protected \App\Model\Table\AirframeComponentTimesTable $AirframeComponentTimes;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function getFlightTime($ftArr)
    {
        $result = '00:00';
        if(!empty($ftArr['leg_date']) && !empty($ftArr['startTime']) && !empty($ftArr['takeoff_timezone']) && !empty($ftArr['endTime']) && !empty($ftArr['landing_timezone'])) {
            $ftArr['leg_date'] = date('m/d/Y', strtotime($ftArr['leg_date']));
            $takeoffTime = date('Y-m-d', strtotime($ftArr['leg_date'])).' '.date('H:i:s', strtotime($ftArr['startTime']));
            
            //Check if landing/taxi in time lies in next day
            if(strtotime($ftArr['endTime']) < strtotime($ftArr['startTime'])) {
                $ftArr['leg_date'] = date('m/d/Y', strtotime("+1 days", strtotime($ftArr['leg_date'])));
            }
            
            $landingTime = date('Y-m-d', strtotime($ftArr['leg_date'])).' '.date('H:i:s', strtotime($ftArr['endTime']));

            $takeoffTZ = $this->Timezone->getTimezoneNameByCode($ftArr['takeoff_timezone']);
            $landingTZ = $this->Timezone->getTimezoneNameByCode($ftArr['landing_timezone']);
            $d1 = new \DateTime($takeoffTime, new \DateTimeZone($takeoffTZ));
            $d2 = new \DateTime($landingTime, new \DateTimeZone($landingTZ));
            $diff = $d1->diff($d2);
            $diffHr = $diff->h + ($diff->days * 24);
            if(!empty($diffHr) && $diffHr < 10) {
                $diffHr = '0'.$diffHr;
            } elseif (empty($diffHr)) {
                $diffHr = '00';
            }

            $diffMin = $diff->i;
            if(!empty($diffMin) && $diffMin < 10) {
                $diffMin = '0'.$diffMin;
            } elseif (empty($diffMin)) {
                $diffMin = '00';
            }
            //$result = $diff->h + ($diff->days * 24).':'.$diff->i;
            $result = $diffHr.':'.$diffMin;
        }
        return $result;
    }

    //Convert time to decimal
    public function convertTimeDecimal($blkTime) {
        $time = explode(":", $blkTime);
        return $time[0] + round($time[1] / 60, 1, PHP_ROUND_HALF_DOWN);
    }

    public function dispatchTabForm()
    {
        $planes = $this->Plane->getAllPlanes();
        $date = date('m/d/Y');
        $newHtml = '<div class="new-tab">
                <input type="hidden" name="form_type" class="formTypeCls" value="fllog">
                <input type="hidden" name="tabnum" class="tabnum">
                <div class="gettab row" style="padding-top: 10px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-md-6">
                                <h4>Aircraft</h4>
                            </div>
                            <div class="col-md-6">
                                <select name="plane_id" class="form-control selectpicker airListCls" data-show-subtext="true" data-live-search="false" required="required" id="airListId" style="display:block !important;">';
                                    foreach ($planes as $key => $value) {
                                        $newHtml .= '<option value='.$key.'>'.$value.'</option>';
                                    }
                                $newHtml .= '</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <table width="100%">
                                <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                    <tr>
                                        <th>Next Due Item</th>
                                        <th>ToGo</th>
                                    </tr>
                                </thead>
                                <tbody class="airDueRecord">
                                    <tr>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Crew information -->
                        <div class="form-group">
                            <div class="col-md-6">
                                <h4>Crew Information</h4>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-sm btn-success addCrewBtn" style="float:right;"><i class="fa fa-plus"></i> Add Crew Member</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <table width="100%">
                                <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                    <tr>
                                        <th width="8%">Type</th>
                                        <th width="15%">Member</th>
                                        <th width="15%">Duty Date</th>
                                        <th width="15%">Duty On</th>
                                        <th width="15%">Duty Off</th>
                                        <th width="15%">Total</th>
                                        <th width="2%">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="crewMemberList" class="crewMemberList">
                                    <tr class="crewRow">
                                        <td colspan="7" style="text-align: center;">No crew members have been added for this leg.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Crew information -->
                    </div>
                </div>
                <div style="clear: both;"></div>
                <div class="flightInfoCls">
                    <h4>Leg Flight Information</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Flying From</label>
                            <input type="text" name="flight_from" class="form-control flyFromCls" maxlength="4" style="text-transform:uppercase">
                        </div>
                        <div class="col-md-3">
                            <label>Flying To</label>
                            <input type="text" name="flight_to" class="form-control flyToCls" maxlength="4" style="text-transform:uppercase">
                        </div>
                        <div class="col-md-3">
                            <label>Leg Type</label>
                            <select name="leg_type" class="form-control col-md-6 col-xs-12">
                                <option value="135">Part 135</option>
                                <option value="91">Part 91</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label># Passengers</label>
                            <input type="text" name="passengers" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Leg Start Date</label>
                            <input type="text" name="leg_date" class="form-control datePicker" value="'.$date.'">
                        </div>
                        <div class="col-md-3">
                            <label>Leg Start Time</label>
                            <input type="text" name="leg_start" class="form-control legStartCls timePicker keypress" placeholder="00:00">
                        </div>
                        <div class="col-md-3">
                            <label>Leg Length</label>
                            <input type="text" name="leg_length" class="form-control legLengthCls timePicker keypress" placeholder="00:00">
                        </div>
                        <div class="col-md-3">
                            <label>Leg Stop Time</label>
                            <input type="text" name="leg_stop" class="form-control disabledBG legStopCls" placeholder="00:00" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Leg Notes</label>
                            <textarea name="notes" rows="3" cols="50" class="form-control col-md-6 col-xs-12" placeholder="Notes"></textarea>
                        </div>
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="row allBtnCls">
                    <div class="col-md-10 col-sm-10 col-xs-12">
                        <button type="button" class="btn btn-default deleteLeg"><i class="fa fa-times"></i> Delete this Leg</button><button type="button" class="btn btn-success add-before"><i class="fa fa-plus"></i> Leg Before</button><button type="button" class="btn btn-success add-after"><i class="fa fa-plus"></i> Leg After</button><button type="submit" class="btn btn-success saveLaterCls"><i class="fa fa-save"></i> Save for later</button><button type="submit" class="btn btn-success scheduleTrip"><i class="fa fa-plane"></i> Schedule this Trip</button><a class="checkTempCls"><input type="checkbox" name="is_template" class="tempChk"> Save this trip as a template</a>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="flsErrMsg"></div>
                    </div>
                </div>
            </div>
        </form>';

        return $newHtml;
    }

    public function flightlogTabForm()
    {
        $planes = $this->Plane->getAllPlanes();
        $timezones = $this->Timezone->getTimezonesList();                    
        $date = date('m/d/Y');
        $newHtml = '<div class="new-tab">
                <input type="hidden" class="newTabCls" value="new">
                <input type="hidden" name="form_type" value="fldetail">
                <input type="hidden" name="tabnum" class="tabnum">
                <div class="flightFields">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h2>Route: <span class="displayFlyFrom">?</span>-<span class="displayFlyTo">?</span></h2>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row" style="padding-top: 10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Aircraft Information</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    <div class="flHeading">
                        <div class="flHElmCls">Aircraft</div>
                        <div class="flHElmCls">Next Due Item</div>
                        <div class="flHElmCls">ToGo</div>
                    </div>
                    <div class="flItemCls">
h                        <div class="flElmCls">
                            <div class="form-group">
                                <div style="width: 300px;">
                                    <select name="plane_id" class="form-control selectpicker airListCls" data-show-subtext="true" data-live-search="false" required="required" style="display:block !important;">';
                                    foreach ($planes as $key => $value) {
                                        $newHtml .= '<option value='.$key.'>'.$value.'</option>';
                                    }
                                $newHtml .= '</select>
                                </div>
                            </div>
                        </div>
                        <div class="flElmCls">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 airDueItem">
                                    N/A
                                </div>
                            </div>
                        </div>
                        <div class="flElmCls">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 airDueToGo">
                                    N/A
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <h4>Crew Information</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-sm btn-success addCrewBtn" style="float:right;"><i class="fa fa-plus"></i> Add Crew Member</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table width="100%">
                            <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                <tr>
                                    <th width="8%">Type</th>
                                    <th width="15%">Crew Member</th>
                                    <th width="15%">Pilot Flying</th>
                                    <th width="15%">Duty Start Date</th>
                                    <th width="15%">Duty On</th>
                                    <th width="15%">Duty Off</th>
                                    <th width="15%">Total Time</th>
                                    <th width="2%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody class="crewMemberList">
                                <tr class="crewRow">
                                    <td colspan="8" style="text-align: center;">No crew members have been added for this leg.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row" style="padding-top: 20px;margin-top: 15px;">
                        <h4 class="col-md-2 col-xs-12">Flight Information</h4>
                        <div class="col-md-4 col-xs-12">
                            
                        </div>
                    </div>
                    <div class="flightInfoCls">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Flying From</label>
                                <input type="text" name="flight_from" class="form-control flyFromCls" maxlength="4" style="text-transform:uppercase">
                            </div>
                            <div class="col-md-6">
                                <label>Flying To</label>
                                <input type="text" name="flight_to" class="form-control flyToCls" maxlength="4" style="text-transform:uppercase">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Leg Start Date</label>
                                <input type="text" name="leg_date" class="form-control datePicker keypressFT legDateCls" value="'.$date.'">
                            </div>
                            <div class="col-md-6">
                                <label># Passengers</label>
                                <input type="text" name="passengers" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Taxi Out</label>
                                <input type="text" name="taxi_out" class="form-control timePicker keypress taxiOutId" placeholder="00:00">
                            </div>
                            <div class="col-md-3">
                                <label>Take Off</label>
                                <input type="text" name="taxi_off" data-ar="123" class="form-control timePicker keypressFT takeOffId" placeholder="00:00">
                            </div>
                            <div class="col-md-3">
                                <label>Take Off Timezone</label>
                                <select name="takeoff_timezone" class="form-control timezoneCls takeoffTZCls">';
                                    foreach ($timezones as $key => $value) {
                                        $newHtml .= '<option value='.$key.'>'.$value.'</option>';
                                    }
                                $newHtml .= '</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Landing</label>
                                <input type="text" name="landing" class="form-control timePicker keypressFT landingId" placeholder="00:00">
                            </div>
                            <div class="col-md-3">
                                <label>Landing Timezone</label>
                                <select name="landing_timezone" class="form-control timezoneCls landingTZCls">';
                                    foreach ($timezones as $key => $value) {
                                        $newHtml .= '<option value='.$key.'>'.$value.'</option>';
                                    }
                                $newHtml .= '</select>
                            </div>
                            <div class="col-md-6">
                                <label>Taxi In</label>
                                <input type="text" name="taxi_in" class="form-control timePicker keypress taxiInId" placeholder="00:00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Hobbs Take Off</label>
                                <input type="text" name="takeoff_hobbs" class="form-control" placeholder="0.0">
                            </div>
                            <div class="col-md-6">
                                <label>Hobbs Landing</label>
                                <input type="text" name="landing_hobbs" class="form-control" placeholder="0.0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Flight Time</label>
                                <input type="text" name="flight_time" class="form-control disabledBG flightTimeId" placeholder="00:00" disabled>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <input type="text" name="flight_time_decimal" class="form-control disabledBG ftDecimal" placeholder="0.0" disabled>
                            </div>
                            <div class="col-md-3">
                                <label>Block Time </label>
                                <input type="text" name="block_time" class="form-control disabledBG blockTimeId" placeholder="00:00" disabled>
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <input type="text" name="block_time_decimal" class="form-control disabledBG decimalBlkTime" placeholder="0.0" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Approaches</label>
                                <input type="text" name="approaches" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>Approach Type</label>
                                <select name="approach_type" class="form-control col-md-6 col-xs-12">
                                    <option value="vis">VIS</option>
                                    <option value="vor">VOR</option>
                                    <option value="gps">GPS</option>
                                    <option value="loc">LOC</option>
                                    <option value="ils">ILS</option>
                                    <option value="ndb">NDB</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Takeoff Type</label>
                                <div>
                                    <input type="radio" name="takeoff_type" value="day" checked> Day
                                    <input type="radio" name="takeoff_type" value="night"> Night
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Landing Type</label>
                                <div>
                                    <input type="radio" name="landing_type" value="day" checked> Day
                                    <input type="radio" name="landing_type" value="night"> Night
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Night Time</label>
                                <input type="text" name="night_time" class="form-control" placeholder="0.0">
                            </div>

                            <div class="col-md-6">
                                <label>Inst</label>
                                <input type="text" name="inst" class="form-control" placeholder="0.0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>FOB</label>
                                <input type="text" name="fuel_on_board" class="form-control fuelKeypress fobId" placeholder="Fuel-on-board">
                            </div>
                            <div class="col-md-2">
                                <label>Fuel Purchased</label>
                                <input type="text" name="fuel_purchased" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-1">
                                <label>Fuel Type</label>
                                <select name="fuel_type" class="form-control col-md-6 col-xs-12 approachesCls">
                                    <option value="gals">Gals</option>
                                    <option value="lbs">Lbs</option>
                                    <option value="lts">Lts</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Ending Fuel</label>
                                <input type="text" name="ending_fuel" class="form-control fuelKeypress endFId" placeholder="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Starting Fuel</label>
                                <input type="text" name="fuel_total" class="form-control disabledBG startFuelId" placeholder="0" disabled>
                            </div>
                            <div class="col-md-3">
                                <label>Fuel Cost</label>
                                <input type="text" name="fuel_cost" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label>Fuel Burn</label>
                                <input type="text" name="fuel_burn" class="form-control disabledBG fuelBurnId" placeholder="0" style="background-color: #ddd;" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label> Post-Leg APU Time</label>
                                <input type="text" name="apu_time" class="form-control" placeholder="0.0">
                            </div>
                            <div class="col-md-6">
                                <label> Post-Leg APU Time</label>
                                <select name="leg_type" class="form-control col-md-6 col-xs-12 legType">
                                    <option value="135">Part 135</option>
                                    <option value="91">Part 91</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Notes</label>
                                <textarea name="notes" rows="3" cols="50" class="form-control col-md-6 col-xs-12" placeholder="Notes"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>

                    <div class="row">
                        <div class="manifestData"></div>
                        <div class="col-md-12">
                            <button class="btn btn-warning manifestWarning" type="button">
                                <i class="fa fa-warning"></i> Manifest not complete. Click here to complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>';

        return $newHtml;
    }

    public function fldata($flid)
    {
        $flModel = $this->getController()->fetchTable('Flightlogs');
        $res = $flModel->find() 
                        ->where([
                            'Flightlogs.id'=>$flid,
                            'Flightlogs.status'=>'closed'
                        ])
                        ->contain([
                            'FlightlogDetails',
                            'Crews'=>[
                                'fields'=>['id', 'flightlog_id', 'crew_member']
                            ]
                        ])
                        ->enableHydration(false)
                        ->first();
        $flres = [];
        if(!empty($res)) {
            $flres['legdate'] = date('m-d-Y', strtotime($res['leg_date'])).' '.date('H:i', strtotime($res['flightlog_detail']['taxi_out']));
            $flres['tripid'] = $res['trip_id'];
            $flres['crewName'] = !empty($res['crews']) ? $this->Pilot->getPilotName($res['crews'][0]['crew_member']) : '';
            $flres['flight_from'] = $res['flight_from'];
            $flres['flight_to'] = $res['flight_to'];
            $flres['approaches'] = !empty($res['flightlog_detail']['approaches']) ? $res['flightlog_detail']['approaches'] : 0;
            $flres['leg_type'] = $res['leg_type'];
            $flres['passengers'] = $res['passengers'];

            //Flight time
            $takeOff = date('H:i', strtotime($res['flightlog_detail']['taxi_off']));
            $landing = date('H:i', strtotime($res['flightlog_detail']['landing']));            
            $ftArr = array(
                        'leg_date'=>$res['leg_date'],
                        'startTime'=>$takeOff, 
                        'takeoff_timezone'=>$res['flightlog_detail']['takeoff_timezone'], 
                        'endTime'=>$landing, 
                        'landing_timezone'=>$res['flightlog_detail']['landing_timezone']
                    );
            $fltTime = $this->getFlightTime($ftArr);
            //Decimal time
            $flres['timeDecimal'] = $this->convertTimeDecimal($fltTime);

            //Fuel burn
            $startFuel = !empty($res['flightlog_detail']['fuel_on_board']) ? $res['flightlog_detail']['fuel_on_board']:0;
            $endFuel = !empty($res['flightlog_detail']['ending_fuel']) ? $res['flightlog_detail']['ending_fuel']:0;   
            $flres['fuelBurn'] = $startFuel - $endFuel;
        }
        return $flres;
    }

    //Flightlog report data
    public function flReportData($airId, $startDt, $endDt, $type)
    {
        $flHtml = '';
        if(!empty($airId) && !empty($startDt) && !empty($endDt)) {
            $selRec = [
                        'AirframeComponentTimes.id',
                        'AirframeComponentTimes.plane_id',
                        'AirframeComponentTimes.flightlog_id',
                        'AirframeComponentTimes.airframe_component_id',
                        'AirframeComponentTimes.hours',
                        'AirframeComponentTimes.cycles',
                        'AirframeComponentTimes.log_date',
                        'AirframeComponentTimes.user_id',
                        'AirframeComponentTimes.hours_accrued',
                        'AirframeComponentTimes.cycles_accrued',
                        'AirframeComponentTimes.created',
                    ];

            //Comp times
            $airCompTimeModel = $this->getController()->fetchTable('AirframeComponentTimes');
            $comptimes = $airCompTimeModel->find()
                        ->select($selRec)
                        ->where(['AirframeComponentTimes.plane_id'=>$airId, 'AirframeComponentTimes.created >='=>$startDt, 'AirframeComponentTimes.created <='=>$endDt])
                        ->contain([
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.plane_id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ]
                            ]
                        ])
                        ->order(['AirframeComponentTimes.id'=>'DESC'])
                        ->enableHydration(false)
                        ->toArray();
            
            $results = [];
            foreach ($comptimes as $key => $value) {
                $logDT = date("m-d-Y H:i", strtotime($value['created']));
                if(!empty($value['flightlog_id'])) {
                    $flres = $this->fldata($value['flightlog_id']);
                    
                    $results[$logDT][$value['flightlog_id']]['id'] = $value['id'];
                    $results[$logDT][$value['flightlog_id']]['plane_id'] = $value['plane_id'];
                    $results[$logDT][$value['flightlog_id']]['flightlog_id'] = $value['flightlog_id'];
                    $results[$logDT][$value['flightlog_id']]['airframe_component_id'] = $value['airframe_component_id'];
                    
                    if(!empty($flres)) {
                        $results[$logDT][$value['flightlog_id']]['tripid'] = $flres['tripid'];
                        $results[$logDT][$value['flightlog_id']]['crewName'] = $flres['crewName'];
                        $results[$logDT][$value['flightlog_id']]['flight_from'] = $flres['flight_from'];
                        $results[$logDT][$value['flightlog_id']]['flight_to'] = $flres['flight_to'];
                        $results[$logDT][$value['flightlog_id']]['approaches'] = $flres['approaches'];
                        $results[$logDT][$value['flightlog_id']]['passengers'] = $flres['passengers'];
                        $results[$logDT][$value['flightlog_id']]['leg_type'] = $flres['leg_type'];
                        $results[$logDT][$value['flightlog_id']]['timeDecimal'] = $flres['timeDecimal'];
                        $results[$logDT][$value['flightlog_id']]['fuelBurn'] = $flres['fuelBurn'];
                    } else {
                        $results[$logDT][$value['flightlog_id']]['tripid'] = '';
                        $results[$logDT][$value['flightlog_id']]['crewName'] = '';
                        $results[$logDT][$value['flightlog_id']]['flight_from'] = '';
                        $results[$logDT][$value['flightlog_id']]['flight_to'] = '';
                        $results[$logDT][$value['flightlog_id']]['approaches'] = '';
                        $results[$logDT][$value['flightlog_id']]['passengers'] = '';
                        $results[$logDT][$value['flightlog_id']]['leg_type'] = '';
                        $results[$logDT][$value['flightlog_id']]['timeDecimal'] = '';
                        $results[$logDT][$value['flightlog_id']]['fuelBurn'] = '';
                    }
                    
                    if(!empty($value['airframe_component']['log_book']) && $value['airframe_component']['log_book'] == 'Airframe') {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['airHr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['airCy'] = $value['cycles'];
                        
                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 1) {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['eng1Hr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['eng1Cy'] = $value['cycles'];

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 2) {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['eng2Hr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['eng2Cy'] = $value['cycles'];
                    } else {
                        $results[$logDT][$value['flightlog_id']]['date'] = '';
                        $results[$logDT][$value['flightlog_id']]['airHr'] = '';
                        $results[$logDT][$value['flightlog_id']]['airCy'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng1Hr'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng1Cy'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng2Hr'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng2Cy'] = '';
                    }
                } else {
                    $results[$logDT]['id'] = $value['id'];
                    $results[$logDT]['plane_id'] = $value['plane_id'];
                    $results[$logDT]['flightlog_id'] = $value['flightlog_id'];
                    $results[$logDT]['airframe_component_id'] = $value['airframe_component_id'];
                    $username = $this->User->getUserName($value['user_id']);
                    
                    if(!empty($value['airframe_component']['log_book']) && $value['airframe_component']['log_book'] == 'Airframe') {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['airHr'] = $value['hours'];
                        $results[$logDT]['airCy'] = $value['cycles'];

                        if(!empty($username)) {
                            $results[$logDT]['afEntry'] = 'Manual Entry: ('.$username.') Air Frame +'.$value['hours_accrued'].', L/G +'.$value['cycles_accrued'];
                        }

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 1) {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['eng1Hr'] = $value['hours'];
                        $results[$logDT]['eng1Cy'] = $value['cycles'];
                        
                        if(!empty($username)) {
                            $results[$logDT]['eng1Entry'] = 'Manual Entry: ('.$username.') Eng 1 +'.$value['hours_accrued'].', Eng 1 Cyc +'.$value['cycles_accrued'];
                        }                        

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 2) {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['eng2Hr'] = $value['hours'];
                        $results[$logDT]['eng2Cy'] = $value['cycles'];
                        
                        if(!empty($username)) {
                            $results[$logDT]['eng2Entry'] = 'Manual Entry: ('.$username.') Eng 2 +'.$value['hours_accrued'].', Eng 2 Cyc +'.$value['cycles_accrued'];
                        }
                    } else {

                        $results[$logDT]['date'] = '';
                        $results[$logDT]['airHr'] = '';
                        $results[$logDT]['airCy'] = '';
                        $results[$logDT]['eng1Hr'] = '';
                        $results[$logDT]['eng1Cy'] = '';
                        $results[$logDT]['eng2Hr'] = '';
                        $results[$logDT]['eng2Cy'] = '';
                        $results[$logDT]['afEntry'] = '';
                        $results[$logDT]['eng1Entry'] = '';
                        $results[$logDT]['eng2Entry'] = '';
                    }
                }
            }

            if(!empty($results)) {
                foreach ($results as $key => $value) {
                    if(!empty($value) && count($value) < 8) {
                        foreach ($value as $key2 => $value2) {
                            //Remove tripid link for pdf
                            if(!empty($type) && $type=='reportpdf') {
                                $link = strtoupper($value2['tripid']);
                            } else {
                                $link = '<a class="openFLCls" style="cursor:pointer;color:#428bca;" href="flightlog/'.$value2['tripid'].'" target="_blank">'.strtoupper($value2['tripid']).'</a>';
                            }

                            $flHtml .= '<tr class="flRow">
                                    <td style="line-height: 2.5;">'.$value2['date'].'</td>
                                    <td style="line-height: 2.5;">'.$link.'</td>
                                    <td style="line-height: 2.5;">'.$value2['crewName'].'</td>
                                    <td style="line-height: 2.5;">'.strtoupper($value2['flight_from']).'</td>
                                    <td style="line-height: 2.5;">'.strtoupper($value2['flight_to']).'</td>
                                    <td style="line-height: 2.5;">'.$value2['timeDecimal'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['fuelBurn'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['passengers'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['approaches'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['leg_type'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['airHr'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['airCy'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['eng1Hr'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['eng2Hr'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['eng1Cy'].'</td>
                                    <td style="line-height: 2.5;">'.$value2['eng2Cy'].'</td>
                                </tr>';
                        }
                    } else {
                        $flHtml .= '<tr class="flRow">
                                        <td style="line-height: 2.5;">'.$value['date'].'</td>
                                        <td colspan="9" style="text-align:left;line-height: 2.5;">'.(!empty($value['eng2Entry']) ? $value['eng2Entry'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airHr']) ? $value['airHr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airCy']) ? $value['airCy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Hr']) ? $value['eng1Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Hr']) ? $value['eng2Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Cy']) ? $value['eng1Cy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Cy']) ? $value['eng2Cy'] : '').'</td>
                                    </tr>
                                    <tr class="flRow">
                                        <td style="line-height: 2.5;">'.$value['date'].'</td>
                                        <td colspan="9" style="text-align:left;line-height: 2.5;">'.(!empty($value['eng1Entry']) ? $value['eng1Entry'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airHr']) ? $value['airHr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airCy']) ? $value['airCy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Hr']) ? $value['eng1Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Hr']) ? $value['eng2Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Cy']) ? $value['eng1Cy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Cy']) ? $value['eng2Cy'] : '').'</td>
                                    </tr>
                                    <tr class="flRow">
                                        <td style="line-height: 2.5;">'.$value['date'].'</td>
                                        <td colspan="9" style="text-align:left;line-height: 2.5;">'.(!empty($value['afEntry']) ? $value['afEntry'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airHr']) ? $value['airHr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['airCy']) ? $value['airCy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Hr']) ? $value['eng1Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Hr']) ? $value['eng2Hr'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng1Cy']) ? $value['eng1Cy'] : '').'</td>
                                        <td style="line-height: 2.5;">'.(!empty($value['eng2Cy']) ? $value['eng2Cy'] : '').'</td>
                                    </tr>';
                    }
                }
            }
        }
        return $flHtml;
    }
    
}