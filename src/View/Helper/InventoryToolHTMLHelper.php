<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\App;

class InventoryToolHTMLHelper extends Helper
{
    public function toolsTableHTML($toolsdata){
        $toolshtml = '';
        foreach($toolsdata as $key=>$tools){ 
            $key = $key+1;
            $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd'; 
            
            $toolshtml .='<tr class="mainTR activeTble inventory_tool_list '.$evenOdd.'" data-val="'.$tools['id'].'">';
            $toolshtml .='<td class="collapse-tr">'.$tools['tool_name'].'</td>';
            $toolshtml .='<td class="collapse-tr">'.$tools['equipment_description'].'</td>';
            $toolshtml .='<td class="collapse-tr">'.$tools['model_no'].'</td>';
            $toolshtml .='<td class="collapse-tr">'.$tools['serial_no'].'</td>';
            $toolshtml .='<td class="collapse-tr">'.$tools['tool_location'].'</td>';
            $toolshtml .='</tr>';
        }

        return $toolshtml;
    }

    public function certifielHistoryTableHTML($certificationhistories){
        $certifiedhistoryhtml = '';
        foreach($certificationhistories as $key=>$row){ 
            $certified_history_list_active = '';
            if($key == '0'){
                $certified_history_list_active = 'certified_history_list_active';
            }
            $key = $key+1;
            $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd'; 
            
            $was_in_calibration_chk = '';
            if($row['was_in_calibration'] == '1'){
                $was_in_calibration_chk = 'checked';
            }

            $certifiedhistoryhtml .='<tr class="mainTR activeTble certified_history_list '.$certified_history_list_active.' '.$evenOdd.'" data-val="'.$row['id'].'">';
            $certifiedhistoryhtml .='<td class="collapse-tr">'.$row['date_sent_out'].'</td>';
            $certifiedhistoryhtml .='<td class="collapse-tr">'.$row['date_received_back'].'</td>';
            $certifiedhistoryhtml .='<td class="collapse-tr"><input type="checkbox" value="1"'.$was_in_calibration_chk.'></td>';
            $certifiedhistoryhtml .='<td class="collapse-tr">'.$row['adjustment_needed'].'</td>';
            $certifiedhistoryhtml .='</tr>';
        }

        return $certifiedhistoryhtml;
    }

    public function workOrderHistoryTableHTML($wohistorydata){
        $wohistoryhtml = '';
        foreach($wohistorydata as $key=>$tools){ 
            $key = $key+1;
            $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd'; 
            
            $wohistoryhtml .='<tr class="mainTR activeTble inventory_tool_list '.$evenOdd.'" data-val="'.$tools['id'].'">';
            $wohistoryhtml .='<td class="collapse-tr">'.$tools['work_order']['work_order_no'].'</td>';
            $wohistoryhtml .='<td class="collapse-tr">'.$tools['wo_item']['wo_item_position'].'</td>';
            $wohistoryhtml .='<td class="collapse-tr">'.$tools['inv_tool']['calibration_date'].'</td>';
            $wohistoryhtml .='<td class="collapse-tr">'.$tools['inv_tool']['due_date'].'</td>';
            $wohistoryhtml .='<td class="collapse-tr">'.date('Y-m-d', strtotime($tools['inv_tool']['created_at'])).'</td>';
            $wohistoryhtml .='</tr>';
        }

        return $wohistoryhtml;
    }
}

?>