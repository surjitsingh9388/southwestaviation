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

class FlightlogComponent extends Component {
    public $components = ['Plane', 'Timezone'];

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
                        <div class="flElmCls">
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
                                <select name="leg_type" class="form-control col-md-6 col-xs-12">
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
    
}