<?php
$utilHours = !empty($row['utilization']['hours']) ? $row['utilization']['hours'] : 1;
$utilCycles = !empty($row['utilization']['cycles']) ? $row['utilization']['cycles'] : 1;
?>
<tr class="activeTble">
    <td class="check">
        <?php
        if(!empty($openUrl) && $openUrl == true) {
        ?>
        <?php
            if(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'P') {
                echo '<a href="'.ROOT_DIR.'/admin/airframe_component_parts/edit/'.$row['partDet']['id'].'"><span class="parent-child-circle" title="This item is a parent.">'.$row['partDet']['parentChild'].'</span></a>';
            } elseif(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'C') {
                echo '<a href="'.ROOT_DIR.'/admin/airframe_component_parts/edit/'.$row['partDet']['id'].'"><span class="parent-child-circle" title="Child of '.$row['partDet']['parentName'].'.">'.$row['partDet']['parentChild'].'</span></a>';
            }
        ?>
        <?php
        } elseif(!empty($prChild) && $prChild == true) {
        ?>
        <input type="checkbox" class="chkBoxCls" name="childcheckbox" value="<?php echo $row['partDet']['id']; ?>">
        <?php
        } else {
        ?>
        <input type="checkbox" class="chkBoxCls" name="childcheckbox" value="<?php echo $row['partDet']['id']; ?>">
        <?php
            if(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'P') {
                
                echo '<br><span class="parent-child-circle prChildCls" title="This item is a parent." data-airid="'.$row['plane']['plane_id'].'" data-pid="'.$row['partDet']['pId'].'" data-type="parent" data-cid="'.$row['partDet']['id'].'" data-typ="'.$typ.'" data-act="'.$act.'">'.$row['partDet']['parentChild'].'</span>';

            } elseif(!empty($row['partDet']['parentChild']) && $row['partDet']['parentChild'] == 'C') {

                echo '<br><span class="parent-child-circle prChildCls" title="Child of '.$row['partDet']['parentName'].'." data-airid="'.$row['plane']['plane_id'].'" data-pid="'.$row['partDet']['pId'].'" data-type="child" data-cid="'.$row['partDet']['id'].'" data-typ="'.$typ.'" data-act="'.$act.'">'.$row['partDet']['parentChild'].'</span>';
            }
        ?>
        <?php
        }
        ?>
    </td>
    
    <td class="collapse-tr">
        <?php echo $row['plane']['plane_code']; ?>
    </td>

    <td class="collapse-tr">
        <?php echo $row['ataCode']['ata_value']; ?>
    </td>
    
    <td class="collapse-tr">
        <?php echo $row['ataCode']['reference']."<br>".$row['ataCode']['log_book']."<br>".$row['ataCode']['item_type']; ?>
    </td>

    <td class="collapse-tr">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12"><?php echo $row['partDet']['partDesc']; ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><span class="lblColor">Part Number <br></span><?php echo !empty($row['partDet']['partNum']) ? $row['partDet']['partNum'] : '-'; ?></div>
            <div class="col-md-6"><span class="lblColor">Serial Number <br></span><?php echo !empty($row['partDet']['partSerial']) ? $row['partDet']['partSerial'] : '-'; ?></div>
        </div> 
    </td>
    
    <td class="collapse-tr">
        <?php
        //Current hours cycles 
        $currHC = '';       
        if(!empty($row['currHC']['currHCHrs'])) {
            $currHC .= 'Hours: '.$row['currHC']['currHCHrs'].'<br>';
        }
        if(!empty($row['currHC']['currHCAfl'])) {
            $currHC .= 'Cycles: '.$row['currHC']['currHCAfl'].'<br>';
        }
        echo $currHC; 
        ?>
    </td>

    <td class="collapse-tr">
        <?php
        //Last Compiled With
        $lastCW = '';
        if(!empty($row['lastCW']['lastCwMos'])) {
            $lastCW .= $row['lastCW']['lastCwMos'].'<br>';
        }
        
        if(!empty($row['lastCW']['lastCwHrs'])) {
            $lastCW .= 'Hours: '.$row['lastCW']['lastCwHrs'].'<br>';
        }

        if(!empty($row['lastCW']['lastCwAfl'])) {
            $lastCW .= 'Cycles: '.$row['lastCW']['lastCwAfl'].'<br>';
        }

        echo $lastCW; 
        ?>
    </td>
    
    <td class="collapse-tr">
        <?php
        //Interval
        $reqFeq = '';
        if(!empty($row['reqFeq']['reqFreqMos'])) {
            $reqFeq .= 'Months: '.$row['reqFeq']['reqFreqMos'].'<br>';
        }
        
        if(!empty($row['reqFeq']['reqFreqDays'])) {
            $reqFeq .= 'Days: '.$row['reqFeq']['reqFreqDays'].'<br>';
        }

        if(!empty($row['reqFeq']['reqFreqHrs'])) {
            $reqFeq .= 'Hours: '.$row['reqFeq']['reqFreqHrs'].'<br>';
        }

        if(!empty($row['reqFeq']['reqFreqAfl'])) {
            $reqFeq .= 'Cycles: '.$row['reqFeq']['reqFreqAfl'].'<br>';
        }

        echo $reqFeq; 
        ?>
    </td>
    
    <td class="collapse-tr">
        <?php
        //Next Due
        $nextDue = '';
        if(!empty($row['nextDue']['mos'])) {
            $nextDue .= $row['nextDue']['mos'].'<br>';
        }

        if(!empty($row['nextDue']['hrs'])) {
            $nextDue .= 'Hours: '.$row['nextDue']['hrs'].'<br>';
        }

        if(!empty($row['nextDue']['afl'])) {
            $nextDue .= 'Cycles: '.$row['nextDue']['afl'].'<br>';
        }

        echo $nextDue; 
        ?>
    </td>
    
    <td class="collapse-tr">
        <?php
        //Remaining
        $remaining = '';
        if(!empty($row['remaining']['remMos'])) {
            $remaining .= '<span style="'.$row['alert']['altDColor'].'">Months: '.$row['remaining']['remMos'].'</span><br>';
        }

        if(!empty($row['remaining']['remDays'])) {
            $remaining .= '<span style="'.$row['alert']['altDColor'].'">Days: '.$row['remaining']['remDays'].'</span><br>';
        }

        if(!empty($row['remaining']['remHrs'])) {
            $remaining .= '<span style="'.$row['alert']['altHColor'].'">Hours: '.round($row['remaining']['remHrs'],1).'</span><br>';
        }

        if(!empty($row['remaining']['remAfl'])) {
            $remaining .= '<span style="'.$row['alert']['altAColor'].'">Cycles: '.$row['remaining']['remAfl'].'</span><br>';
        }

        echo $remaining;
        ?>
    </td>

    <td>
        <?php
        $alert = 1;
        $alertVal = 3;
        $alertColor = '';
        if(!empty($row['alert']['altDVal']) || !empty($row['alert']['altHVal']) || !empty($row['alert']['altAVal'])) {
            $alert = 0;
            $alertVal = 2;
            $alertColor = 'style="background-color:#f39c12"';
        }

        //Change sorting for remaining when Projected Due List
        if($typ == 'alert_due') {
            $alert = $remaining;
        }
        ?>

        <?php
        if(!empty($row['nextDue']['mos']) || !empty($row['nextDue']['hrs']) || !empty($row['nextDue']['afl'])) {
            
            if(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] < 0 && ($row['remaining']['totalRemD'] + $row['tolerance']['totalTlrD']) < 0) 
                || (!empty($row['remaining']['remHrs']) && $row['remaining']['remHrs'] < 0 && ($row['remaining']['remHrs'] + $row['tolerance']['tolrHrs']) < 0)
                || (!empty($row['remaining']['remAfl']) && $row['remaining']['remAfl'] < 0 && ($row['remaining']['remAfl'] + $row['tolerance']['tolrAfl']) < 0)) {
        ?>
                <div class="btn btnStatus due-status-overdue">
                    <?php echo $this->Html->link("Past Due", array('controller'=>'AirframeComponentParts', 'action'=>'edit', $row['partDet']['id'], $typ, $act), array('escape' => false)); ?>
                    <input type="hidden" value="0">
                </div>
            <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] < 0 && ($row['remaining']['totalRemD'] + $row['tolerance']['totalTlrD']) > 0) 
                || (!empty($row['remaining']['remHrs']) && $row['remaining']['remHrs'] < 0 && ($row['remaining']['remHrs'] + $row['tolerance']['tolrHrs']) > 0)
                || (!empty($row['remaining']['remAfl']) && $row['remaining']['remAfl'] < 0 && ($row['remaining']['remAfl'] + $row['tolerance']['tolrAfl']) > 0)) {
            ?>
                <div class="btn btnStatus tolerance-status">
                    <?php echo $this->Html->link("Tolerance", array('controller'=>'AirframeComponentParts', 'action'=>'edit', $row['partDet']['id'], $typ, $act), array('escape' => false)); ?>
                    <input type="hidden" value="1">
                </div>
            <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] > 10) 
                || (!empty($row['remaining']['remHrs']) && !empty($utilHours) && $row['remaining']['remHrs']/$utilHours > 10)
                || (!empty($row['remaining']['remAfl']) && !empty($utilCycles) && $row['remaining']['remAfl']/$utilCycles > 10)) {
            ?>
                <div class="btn btnStatus not-due-status" <?php echo $alertColor;?>>
                    <?php echo $this->Html->link("10+ Days", array('controller'=>'AirframeComponentParts', 'action'=>'edit', $row['partDet']['id'], $typ, $act), array('escape' => false)); ?>
                    <input type="hidden" value="<?php echo $alertVal; ?>">
                </div>
            <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] >= 0 && $row['remaining']['totalRemD'] <= 10) 
                || (!empty($row['remaining']['remHrs']) && !empty($utilHours) && ($row['remaining']['remHrs']/$utilHours >= 0 && $row['remaining']['remHrs']/$utilHours <= 10)) 
                || (!empty($row['remaining']['remAfl']) && !empty($utilCycles) && ($row['remaining']['remAfl']/$utilCycles >= 0 && $row['remaining']['remAfl']/$utilCycles <= 10))) {
            ?>
                <div class="btn btnStatus coming-due-status" style="<?php echo $row['alert']['btncolor']; ?>">
                    <?php echo $this->Html->link("0-10 Days", array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id'], $typ, $act), array('escape' => false)); ?>
                    <input type="hidden" value="2">
                </div>
        <?php
            }
        } else {
        ?>
            <div class="btn btnStatus not-due-status">
                <?php echo $this->Html->link("Not Due", array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id'], $typ, $act), array('escape' => false)); ?>
                <input type="hidden" value="4">
            </div>
        <?php
        }
        ?>
    </td>

    <td style="display: none;">
        <?php
        if(!empty($row['nextDue']['mos']) || !empty($row['nextDue']['hrs']) || !empty($row['nextDue']['afl'])) {
            
            if(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] < 0 && ($row['remaining']['totalRemD'] + $row['tolerance']['totalTlrD']) < 0) 
                || (!empty($row['remaining']['remHrs']) && $row['remaining']['remHrs'] < 0 && ($row['remaining']['remHrs'] + $row['tolerance']['tolrHrs']) < 0)
                || (!empty($row['remaining']['remAfl']) && $row['remaining']['remAfl'] < 0 && ($row['remaining']['remAfl'] + $row['tolerance']['tolrAfl']) < 0)) {
        ?>
                <div class="btn btnStatus due-status-overdue">
                    <?php echo $this->Html->link(0, array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id']), array('escape' => false)); ?>
                    <input type="hidden" value="0">
                </div>
        <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] < 0 && ($row['remaining']['totalRemD'] + $row['tolerance']['totalTlrD']) > 0) 
                || (!empty($row['remaining']['remHrs']) && $row['remaining']['remHrs'] < 0 && ($row['remaining']['remHrs'] + $row['tolerance']['tolrHrs']) > 0)
                || (!empty($row['remaining']['remAfl']) && $row['remaining']['remAfl'] < 0 && ($row['remaining']['remAfl'] + $row['tolerance']['tolrAfl']) > 0)) {
        ?>
                <div class="btn btnStatus tolerance-status">
                    <?php echo $this->Html->link(1, array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id']), array('escape' => false)); ?>
                    <input type="hidden" value="1">
                </div>
        <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] > 10) 
                || (!empty($row['remaining']['remHrs']) && !empty($utilHours) && $row['remaining']['remHrs']/$utilHours > 10)
                || (!empty($row['remaining']['remAfl']) && !empty($utilCycles) && $row['remaining']['remAfl']/$utilCycles > 10)) {
        ?>
                <div class="btn btnStatus not-due-status" <?php echo $alertColor;?>>
                    <?php echo $this->Html->link($alertVal, array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id']), array('escape' => false)); ?>
                    <input type="hidden" value="<?php echo $alertVal; ?>">
                </div>
        <?php
            } elseif(((!empty($row['remaining']['remMos']) || !empty($row['remaining']['remDays'])) && $row['remaining']['totalRemD'] >= 0 && $row['remaining']['totalRemD'] <= 10) 
                || (!empty($row['remaining']['remHrs']) && !empty($utilHours) && ($row['remaining']['remHrs']/$utilHours >= 0 && $row['remaining']['remHrs']/$utilHours <= 10)) 
                || (!empty($row['remaining']['remAfl']) && !empty($utilCycles) && ($row['remaining']['remAfl']/$utilCycles >= 0 && $row['remaining']['remAfl']/$utilCycles <= 10))) {
        ?>
                <div class="btn btnStatus coming-due-status">
                    <?php echo $this->Html->link(2, array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id']), array('escape' => false)); ?>
                    <input type="hidden" value="2">
                </div>
        <?php
            }
        } else {
        ?>
            <div class="btn btnStatus not-due-status">
                <?php echo $this->Html->link(4, array('controller' => 'AirframeComponentParts', 'action' => 'edit', $row['partDet']['id']), array('escape' => false)); ?>
                <input type="hidden" value="4">
            </div>
        <?php
        }
        ?>
    </td>

    <td style="display: none;">
        <?php
        echo $alert;
        ?>
    </td>
</tr>