<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\App;

class UserTimeClockHelper extends Helper
{
    public function loadTimeClockForDateHTML($timeClockData){
        $timeclockhtml = '';
        foreach($timeClockData as $key=>$clock){ 
            $activeclass= '';
            if($key == '0'){
                $activeclass= 'time_clock_adjustment_tr_active';
            }
            $timeclockhtml .='<tr class="time_clock_adjustment_tr '.$activeclass.'" data-val="'.$clock['id'].'">';
            $timeclockhtml .='<td>'.date('Y-m-d h:i:s A', strtotime($clock['in_time'])).'</td>';
            $timeclockhtml .='<td>'.(!empty($clock['out_time']) ? date('Y-m-d h:i:s A', strtotime($clock['out_time'])) : '').'</td>';
            $timeclockhtml .='<td></td>';
            $timeclockhtml .='</tr>';
        }

        return $timeclockhtml;
    }

}