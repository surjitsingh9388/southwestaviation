<?php 
if(!empty($menuItems)) {
?>
<table width="100%" class="Panel" border="1">
    <tr style="background-color: #f0f0f0;">
        <th style="vertical-align: bottom; text-align: center;">Allow</th>
        <th style="vertical-align: bottom; text-align: center;" rowspan="3" colspan="3">Menu Items</th>
        <th style="text-align: center;" colspan="7">Actions</th>
    </tr>
    <tr style="background-color: #f0f0f0;">
        <td style="vertical-align: middle; text-align: center;" rowspan="2"><?php echo $this->Form->input('allCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'allCheckUncheck', 'label' => false, 'title' => 'Check All']); ?></td>
        <th class="text-alignment">Add/Create</th>
        <th class="text-alignment">Edit</th>
        <th class="text-alignment">View</th>
        <th class="text-alignment">Delete</th>
        <th class="text-alignment">Approve/Deny</th>
        <th class="text-alignment">Reopen Work Order</th>
        <th class="text-alignment">Final Inspections</th>
        <th class="text-alignment">Clear Signoff</th>
    </tr>
    <tr style="background-color: #f0f0f0;">
        <th class="text-alignment"><?php echo $this->Form->input('allAddCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllAdd', 'label' => false, 'title' => 'Check All Add']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allEditCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllEdit', 'label' => false, 'title' => 'Check All Edit']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allViewCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllView', 'label' => false, 'title' => 'Check All View']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allDeleteCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllDelete', 'label' => false, 'title' => 'Check All Delete']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allApproveDenyCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllApproveDeny', 'label' => false, 'title' => 'Check All Approve/Deny']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allReopenWorkOrderCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllReopenWorkOrder', 'label' => false, 'title' => 'Check All Reopen Work Order']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allFinalInspectionsCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllFinalInspections', 'label' => false, 'title' => 'Check All Final Inspections']); ?></th>
        <th class="text-alignment"><?php echo $this->Form->input('allClearSignoffCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllClearSignoff', 'label' => false, 'title' => 'Check All Clear Signoff']); ?></th>
    </tr>
    <?php 
    foreach ($menuItems as $key => $menuItem) {
        if(is_array($menuItem)) {
            $checked = false;
            if(array_key_exists($key, $temp)) {
                $data= $temp[$key];
                $checked = true;
            } else {
                $checked = false;
                $data['action_add']=0;
                $data['action_edit']=0;
                $data['action_view']=0;
                $data['action_delete']=0;
            }
            foreach ($menuItem as $key1 => $subItems) {
                if($key1 == 'Inventory'){
                    echo '<tr style="background-color: #f0f0f0;">
                            <th style="vertical-align: top; text-align: center;">'.$this->Form->input('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked]).'</th>
                            <th style="" colspan="3"><span style="margin-left:5px;">'.$key1.'</span></th>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_add check_add main_action_add_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_edit check_edit main_action_edit_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_view check_view main_action_view_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_delete check_delete main_action_delete_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_approve_deny', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_approve_deny check_approve_deny main_action_approve_deny_'.$key]).'</td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment"></td>
                        <tr>';
                }else if($key1 == 'Maintenance'){
                    echo '<tr style="background-color: #f0f0f0;">
                            <th style="vertical-align: top; text-align: center;">'.$this->Form->input('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked]).'</th>
                            <th style="" colspan="3"><span style="margin-left:5px;">'.$key1.'</span></th>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_add check_add main_action_add_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_edit check_edit main_action_edit_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_view check_view main_action_view_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_delete check_delete main_action_delete_'.$key]).'</td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_reopen_work_order', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_reopen_work_order check_reopen_work_order main_action_reopen_work_order_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_final_inspections', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_final_inspections check_final_inspections main_action_final_inspections_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_clear_signoff', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_clear_signoff check_clear_signoff main_action_clear_signoff_'.$key]).'</td>
                        <tr>';
                }else{
                    echo '<tr style="background-color: #f0f0f0;">
                            <th style="vertical-align: top; text-align: center;">'.$this->Form->control('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked]).'</th>
                            <th style="" colspan="3"><span style="margin-left:5px;">'.$key1.'</span></th>
                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_add check_add main_action_add_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_edit check_edit main_action_edit_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_view check_view main_action_view_'.$key]).'</td>
                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_delete check_delete main_action_delete_'.$key]).'</td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment"></td>
                            <td class="text-alignment"></td>
                        <tr>';
                }
                    
                foreach ($subItems as $key2 => $value) {
                    if(!is_array($value)) {
                        if(array_key_exists($key2, $temp)) {
                            $data= $temp[$key2];
                            $checked = true;
                        } else {
                            $checked = false;
                            $data['action_add']=0;
                            $data['action_edit']=0;
                            $data['action_view']=0;
                            $data['action_delete']=0;
                            $data['action_approve_deny']=0;
                            $data['action_reopen_work_order']=0;
                            $data['action_final_inspections']=0;
                            $data['action_clear_signoff']=0;
                        }
                        if(!empty($value) && $value == 'Generate Report') {
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td colspan="5"></td>
                                </tr>';
                        } elseif(!empty($value) && $value == 'Aircraft') {
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    
                                    <td colspan="2">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-12"><span style="margin-left:10px;">'.$value.'</span></div>
                                            
                                            <div class="col-md-7 col-sm-7 col-xs-12 airSelCls" style="padding:2px;min-width:200px;">'.$this->Form->control('aircraft_ids', array('options'=>$airArr, 'class'=>'form-control col-md-12 col-sm-12 col-xs-12', 'label'=>false, 'id'=>'airSelId', 'style'=>'', 'multiple'=>'multiple', 'value'=>$selAirIds)).'</div>
                                        </div>
                                    </td>
                                    
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                </tr>';
                        } elseif(!empty($value) && $value == 'Requests') {
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_approve_deny', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_approve_deny_'.$key2, 'class' => 'chield_'.$key. ' action_approve_deny_'.$key. ' action check_approve_deny approve_deny', 'checked' => $data['action_approve_deny']=='1'?true:false]).'</td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                </tr>';
                            
                        } elseif(!empty($value) && $value == 'Work Orders') {
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_reopen_work_order', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_reopen_work_order_'.$key2, 'class' => 'chield_'.$key. ' action_reopen_work_order_'.$key. ' action check_reopen_work_order reopen_work_order', 'checked' => (!empty($data['action_reopen_work_order']) && $data['action_reopen_work_order']=='1') ?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_final_inspections', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_final_inspections_'.$key2, 'class' => 'chield_'.$key. ' action_final_inspections_'.$key. ' action check_final_inspections final_inspections', 'checked' => (!empty($data['action_final_inspections']) && $data['action_final_inspections']=='1') ?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_clear_signoff', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_clear_signoff_'.$key2, 'class' => 'chield_'.$key. ' action_clear_signoff_'.$key. ' action check_clear_signoff clear_signoff', 'checked' => (!empty($data['action_clear_signoff']) && $data['action_clear_signoff']=='1') ?true:false]).'</td>
                                </tr>';
                            
                        }else {
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                </tr>';
                        }
                    } else {
                        foreach ($value as $key3 => $valueLast) {
                            if(array_key_exists($key2, $temp)) {
                                $data= $temp[$key2];
                                $checked = true;
                            } else {
                                $checked = false;
                                $data['action_add']=0;
                                $data['action_edit']=0;
                                $data['action_view']=0;
                                $data['action_delete']=0;
                                $data['action_approve_deny']=0;
                                $data['action_reopen_work_order']=0;
                                $data['action_final_inspections']=0;
                                $data['action_clear_signoff']=0;
                            }
                            echo '<tr>
                                    <td></td>
                                    <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key.' lastChield_parent_'.$key2, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                    <td style="" colspan="2"><span style="margin-left:5px;">'.$key3.'</span></td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'__view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                    <td class="text-alignment"></td>
                                </tr>';
                            if($key3 == 'SWAS' || $key3 == 'BVAC' || $key3 == 'RJC'){
                                continue;
                            }
                            foreach ($valueLast as $key4 => $value4) {
                                foreach ($value4 as $key5 => $value5) {
                                    if(array_key_exists($key5, $temp)) {
                                        $data= $temp[$key5];
                                        $checked = true;
                                    } else {
                                        $checked = false;
                                        $data['action_add']=0;
                                        $data['action_edit']=0;
                                        $data['action_view']=0;
                                        $data['action_delete']=0;
                                        $data['action_approve_deny']=0;
                                        $data['action_reopen_work_order']=0;
                                        $data['action_final_inspections']=0;
                                        $data['action_clear_signoff']=0;
                                    }
                                    echo '<tr>
                                            <td></td>
                                            <td></td>
                                            <td style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key5.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key5, 'id' => 'chield_'.$key.'_'.$key2, 'checked' => $checked]).'</td>
                                            <td style=""><span style="margin-left:5px;">'.$value5.'</span></td>
                                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key5.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key5.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key5.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key5.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                            <td class="text-alignment"></td>
                                            <td class="text-alignment"></td>
                                            <td class="text-alignment"></td>
                                            <td class="text-alignment"></td>
                                        </tr>';
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if(array_key_exists($key, $temp)) {
                $data= $temp[$key];
                $checked = true;
            } else {
                $checked = false;
                $data['action_add']=0;
                $data['action_edit']=0;
                $data['action_view']=0;
                $data['action_delete']=0;
                $data['action_approve_deny']=0;
                $data['action_reopen_work_order']=0;
                $data['action_final_inspections']=0;
                $data['action_clear_signoff']=0;
            }
            echo '<tr>
                    <th style="vertical-align: top; text-align: center">'.$this->Form->control('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked ]).'</th>
                    <th style=""  colspan="3"><span style="margin-left:5px;">'.$menuItem.'</span></th>
                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                    <td class="text-alignment">'.$this->Form->control('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                    <td class="text-alignment"></td>
                    <td class="text-alignment"></td>
                    <td class="text-alignment"></td>
                    <td class="text-alignment"></td>
                </tr>';
        }  
    }
    ?>
</table>
<?php 
} 
?>

<style type="text/css">
.text-alignment{
    text-align: center;
}
</style>

<script type="text/javascript">
//When all checkboxes are checked then allow checkbox should checked
function allowAll() {
    var addAll = $('.checkAllAdd').is(':checked');
    var editAll = $('.checkAllEdit').is(':checked');
    var viewAll = $('.checkAllView').is(':checked');
    var deletAll = $('.checkAllDelete').is(':checked');
    var approveDenyAll = $('.checkAllApproveDeny').is(':checked');
    var reopenWorkOrderAll = $('.checkAllReopenWorkOrder').is(':checked');
    var finalInspectionsAll = $('.checkAllFinalInspections').is(':checked');
    var clearSignoffAll = $('.checkAllClearSignoff').is(':checked');
    if(addAll === true && editAll === true && viewAll === true && deletAll === true && approveDenyAll === true && reopenWorkOrderAll === true && finalInspectionsAll === true && clearSignoffAll === true){
        $('.allCheckUncheck').prop('checked', 'checked');
    }
}

$(document).ready(function() {
    $('#airSelId').multiselect();
    $("div").removeClass("checkbox");
    //Make checked all chield checkbox
    $('.selectAll-Chield').click(function(e){
        var id = $(this).attr('id');
        if($(this).is(':checked') === false){
            $('#'+id).removeAttr('checked');
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllAdd').removeAttr('checked');
            $('.checkAllEdit').removeAttr('checked');
            $('.checkAllView').removeAttr('checked');
            $('.checkAllDelete').removeAttr('checked');
            $('.checkAllApproveDeny').removeAttr('checked');
            $('.checkAllReopenWorkOrder').removeAttr('checked');
            $('.checkAllFinalInspections').removeAttr('checked');
            $('.checkAllClearSignoff').removeAttr('checked');
        }
        $('.chield_'+id).prop('checked', this.checked);

        var add = $("input[type='checkbox'].check_add");
        var edit = $("input[type='checkbox'].check_edit");
        var view = $("input[type='checkbox'].check_view");
        var delet = $("input[type='checkbox'].check_delete");
        var approvedeny = $("input[type='checkbox'].check_approve_deny");
        var reopenworkorder = $("input[type='checkbox'].check_reopen_work_order");
        var finalinspections = $("input[type='checkbox'].check_final_inspections");
        var clearsignoff = $("input[type='checkbox'].check_clear_signoff");
        if(add.length == add.filter(":checked").length){
            $('#alladdcheckuncheck').prop('checked', this.checked);
        }

        if(edit.length == edit.filter(":checked").length){
            $('#alleditcheckuncheck').prop('checked', this.checked);
        }

        if(view.length == view.filter(":checked").length){
            $('#allviewcheckuncheck').prop('checked', this.checked);
        }

        if(delet.length == delet.filter(":checked").length){
            $('#alldeletecheckuncheck').prop('checked', this.checked);
        }
        if(approvedeny.length == approvedeny.filter(":checked").length){
            $('#allapprovedenycheckuncheck').prop('checked', this.checked);
        }
        if(reopenworkorder.length == reopenworkorder.filter(":checked").length){
            $('#allreopenworkordercheckuncheck').prop('checked', this.checked);
        }
        if(finalinspections.length == finalinspections.filter(":checked").length){
            $('#allfinalinspectionscheckuncheck').prop('checked', this.checked);
        }
        if(clearsignoff.length == clearsignoff.filter(":checked").length){
            $('#allclearsignoffcheckuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make checked all chield checkbox and as well as parent if not checked
    $(".child-term").click(function(e) {
        var clickedItemStatus = $(this).is(':checked');
        var id = $(this).attr('id');
        var array = id.split('_');
        var status = $('#'+array[1]).is(':checked');
        var lastChield_parent = $('.lastChield_parent_'+array[2]).is(':checked');
        if(lastChield_parent === false){ 
            $('.lastChield_parent_'+array[2]).prop('checked', this.checked);
        }
        if(clickedItemStatus === false){ 
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllAdd').removeAttr('checked');
            $('.checkAllEdit').removeAttr('checked');
            $('.checkAllView').removeAttr('checked');
            $('.checkAllDelete').removeAttr('checked');
            $('.checkAllApproveDeny').removeAttr('checked');
            $('.checkAllReopenWorkOrder').removeAttr('checked');
            $('.checkAllFinalInspections').removeAttr('checked');
            $('.checkAllClearSignoff').removeAttr('checked');

            $('.main_action_add_'+array[1]).prop('checked', this.checked);
            $('.main_action_edit_'+array[1]).prop('checked', this.checked);
            $('.main_action_view_'+array[1]).prop('checked', this.checked);
            $('.main_action_delete_'+array[1]).prop('checked', this.checked);
            $('.main_action_approve_deny_'+array[1]).prop('checked', this.checked);
            $('.main_action_reopen_work_order_'+array[1]).prop('checked', this.checked);
            $('.main_action_final_inspections_'+array[1]).prop('checked', this.checked);
            $('.main_action_clear_signoff_'+array[1]).prop('checked', this.checked);
        }
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }                            
        var tr= $(e.target).closest('tr');
        $('td input:checkbox',tr).prop('checked',this.checked);

        var addParentCheck = $("input[type='checkbox'].action_add_"+array[1]);
        var editParentCheck = $("input[type='checkbox'].action_edit_"+array[1]);
        var viewParentCheck = $("input[type='checkbox'].action_view_"+array[1]);
        var deleteParentCheck = $("input[type='checkbox'].action_delete_"+array[1]);
        var approveDenyParentCheck = $("input[type='checkbox'].action_approve_deny_"+array[1]);
        var reopenWorkOrderParentCheck = $("input[type='checkbox'].action_reopen_work_order_"+array[1]);
        var finalInspectionsParentCheck = $("input[type='checkbox'].action_final_inspection_"+array[1]);
        var clearSignoffParentCheck = $("input[type='checkbox'].action_clear_signoff_"+array[1]);

        if(addParentCheck.length == addParentCheck.filter(":checked").length){
            $('.main_action_add_'+array[1]).prop('checked', this.checked);
        }
        if(editParentCheck.length == editParentCheck.filter(":checked").length){
            $('.main_action_edit_'+array[1]).prop('checked', this.checked);
        }
        if(viewParentCheck.length == viewParentCheck.filter(":checked").length){
            $('.main_action_view_'+array[1]).prop('checked', this.checked);
        }
        if(deleteParentCheck.length == deleteParentCheck.filter(":checked").length){
            $('.main_action_delete_'+array[1]).prop('checked', this.checked);
        }
        if(approveDenyParentCheck.length == approveDenyParentCheck.filter(":checked").length){
            $('.main_action_approve_deny_'+array[1]).prop('checked', this.checked);
        }
        if(reopenWorkOrderParentCheck.length == reopenWorkOrderParentCheck.filter(":checked").length){
            $('.main_action_reopen_work_order_'+array[1]).prop('checked', this.checked);
        }
        if(finalInspectionsParentCheck.length == finalInspectionsParentCheck.filter(":checked").length){
            $('.main_action_final_inspections_'+array[1]).prop('checked', this.checked);
        }
        if(clearSignoffParentCheck.length == clearSignoffParentCheck.filter(":checked").length){
            $('.main_action_clear_signoff_'+array[1]).prop('checked', this.checked);
        }

        var add = $("input[type='checkbox'].check_add");
        var edit = $("input[type='checkbox'].check_edit");
        var view = $("input[type='checkbox'].check_view");
        var delet = $("input[type='checkbox'].check_delete");
        var approve_deny = $("input[type='checkbox'].check_approve_deny");
        var reopen_work_order = $("input[type='checkbox'].check_reopen_work_order");
        var final_inspections = $("input[type='checkbox'].check_final_inspections");
        var clear_signoff = $("input[type='checkbox'].check_clear_signoff");

        if(add.length == add.filter(":checked").length){
            $('#alladdcheckuncheck').prop('checked', this.checked);
        }

        if(edit.length == edit.filter(":checked").length){
            $('#alleditcheckuncheck').prop('checked', this.checked);
        }

        if(view.length == view.filter(":checked").length){
            $('#allviewcheckuncheck').prop('checked', this.checked);
        }

        if(delet.length == delet.filter(":checked").length){
            $('#alldeletecheckuncheck').prop('checked', this.checked);
        }
        if(approve_deny.length == approve_deny.filter(":checked").length){
            $('#allapprovedenycheckuncheck').prop('checked', this.checked);
        }
        if(reopen_work_order.length == reopen_work_order.filter(":checked").length){
            $('#allreopenworkordercheckuncheck').prop('checked', this.checked);
        }
        if(final_inspections.length == final_inspections.filter(":checked").length){
            $('#allfinalinspectionscheckuncheck').prop('checked', this.checked);
        }
        if(clear_signoff.length == clear_signoff.filter(":checked").length){
            $('#allclearsignoffcheckuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all parent checkbox checked if not checked
    $(".action").click(function(e) {
        var clicked = $(this).is(':checked');
        var id = $(this).attr('id');
        var array = id.split('_');
        var classClickedArray = $('#'+id).attr('class');
        var clickedClass = classClickedArray.split(' ');
        var lastItem = clickedClass.pop();
        var id = $(this).attr('id');
        var array = id.split('_');
        var status = $('#'+array[1]).is(':checked');
        var lastChield_parent = $('.lastChield_parent_'+array[3]).is(':checked');
        var a = $("input[type='checkbox'].action_"+array[2]+"_"+array[1]);
        var b = $("input[type='checkbox'].check_"+array[2]);
        
        if(a.length == a.filter(":checked").length){
            $('.main_action_'+array[2]+'_'+array[1]).prop('checked', this.checked);
        }

        if(b.length == b.filter(":checked").length){
            $('#all'+array[2]+'checkuncheck').prop('checked', this.checked);
        }

        if(clicked === false){
            $('.main_action_'+array[2]+'_'+array[1]).removeAttr('checked');
        }

        if(lastChield_parent === false){ 
            $('.lastChield_parent_'+array[3]).prop('checked', this.checked);
        }

        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        var tr= $(e.target).closest('tr');
        var attr = $('td input:checkbox:first',tr).is(':checked');            
        if(attr === false){  
            $('td input:checkbox:first',tr).prop('checked',this.checked);
        }
        if(clicked === false){
            $('.allCheckUncheck').prop('checked', this.checked);
            if(lastItem == 'add'){
                $('.checkAllAdd').removeAttr('checked');
            }
            if(lastItem == 'edit'){
                $('.checkAllEdit').removeAttr('checked');
            }
            if(lastItem == 'view'){
                $('.checkAllView').removeAttr('checked');
            }
            if(lastItem == 'delete'){
                $('.checkAllDelete').removeAttr('checked');
            }
            if(lastItem == 'approve_deny'){
                $('.checkAllApproveDeny').removeAttr('checked');
            }
            if(lastItem == 'reopen_work_order'){
                $('.checkAllReopenWorkOrder').removeAttr('checked');
            }
            if(lastItem == 'final_inspections'){
                $('.checkAllFinalInspections').removeAttr('checked');
            }
            if(lastItem == 'clear_signoff'){
                $('.checkAllClearSignoff').removeAttr('checked');
            }
        }
        allowAll();
    });

    //If only add action is checked then all add action should be checked and as well as all parents will checked
    $(".action_add").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_add_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_add_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllAdd').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only edit action is checked then all edit action should be checked and as well as all parents will checked
    $(".action_edit").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_edit_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_edit_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllEdit').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only view action is checked then all view action should be checked and as well as all parents will checked
    $(".action_view").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_view_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_view_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllView').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only delete action is checked then all delete action should be checked and as well as all parents will checked
    $(".action_delete").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_delete_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_delete_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllDelete').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only approve denay action is checked then all approve deny action should be checked and as well as all parents will checked
    $(".action_approve_deny").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_approve_deny_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_approve_deny_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllApproveDeny').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only reopen work order action is checked then all reopen work order action should be checked and as well as all parents will checked
    $(".action_reopen_work_order").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_reopen_work_order_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_reopen_work_order_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllReopenWorkOrder').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If only final inspections action is checked then all final inspections action should be checked and as well as all parents will checked
    $(".action_final_inspections").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_final_inspections_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_final_inspections_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllFinalInspections').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

     //If only clear signoff action is checked then all clear signoff action should be checked and as well as all parents will checked
     $(".action_clear_signoff").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var viewStatus = $(this).is(':checked');
        var array = id.split('_');
        var classArray = classes.split('_');

        var status = $('#'+array[1]).is(':checked');
        if(status === false){ 
            $('#'+array[1]).prop('checked', this.checked);
        }
        if(viewStatus === true){ 
            $('.action_clear_signoff_'+array[1]).prop('checked', this.checked);
            $('.action_parent_'+array[1]).prop('checked', this.checked);
        }else{
            $('.action_clear_signoff_'+array[1]).prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
            $('.checkAllClearSignoff').prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[2]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[2]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //If menu item has no chield after selecting (Add,Edit,View, Delete and Approve Deny) parent node should be select.
    $(".singleRow").click(function(e) {
        var id = $(this).attr('id');
        var classes = $(this).attr('class').split(' ').pop();
        var classArray = classes.split('_');

        if($(this).is(':checked') === false){
            $('.allCheckUncheck').prop('checked', this.checked);
            if(classArray[1] === 'add'){
                $('.checkAllAdd').removeAttr('checked');
            }
            if(classArray[1] === 'edit'){
                $('.checkAllEdit').removeAttr('checked');
            }
            if(classArray[1] === 'view'){
                $('.checkAllView').removeAttr('checked');
            }
            if(classArray[1] === 'delete'){
                $('.checkAllDelete').removeAttr('checked');
            }
            if(classArray[1] === 'approve_deny'){
                $('.checkAllApproveDeny').removeAttr('checked');
            }
            if(classArray[1] === 'reopen_work_order'){
                $('.checkAllReopenWorkOrder').removeAttr('checked');
            }
            if(classArray[1] === 'final_inspections'){
                $('.checkAllFinalInspections').removeAttr('checked');
            }
            if(classArray[1] === 'clear_signoff'){
                $('.checkAllClearSignoff').removeAttr('checked');
            }
        }
        var status = $('#'+id).is(':checked');
        if(status === false){ 
            $('#'+id).prop('checked', this.checked);
        }
        var b = $("input[type='checkbox'].check_"+classArray[1]);
        if(b.length == b.filter(":checked").length){
            $('#all'+classArray[1]+'checkuncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all checkboxes checked/uncheck
    $(".allCheckUncheck").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $('input[type=checkbox]').prop('checked', this.checked);
        }else{
            $('input[type=checkbox]').prop('checked', this.checked);
        }
    });

    //Make all add checkboxes checked/uncheck
    $(".checkAllAdd").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_add").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_add").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all edit checkboxes checked/uncheck
    $(".checkAllEdit").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_edit").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_edit").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all view checkboxes checked/uncheck
    $(".checkAllView").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_view").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_view").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all delete checkboxes checked/uncheck
    $(".checkAllDelete").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_delete").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_delete").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all approve deny checkboxes checked/uncheck
    $(".checkAllApproveDeny").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_approve_deny").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_approve_deny").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all reopen work order checkboxes checked/uncheck
    $(".checkAllReopenWorkOrder").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_reopen_work_order").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_reopen_work_order").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all final inspections checkboxes checked/uncheck
    $(".checkAllFinalInspections").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_final_inspections").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_final_inspections").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });

    //Make all clear signoff checkboxes checked/uncheck
    $(".checkAllClearSignoff").click(function(e) {
        var status = $(this).is(':checked');
        if(status === true){
            $(".check_clear_signoff").prop('checked', this.checked);
            $(".selectAll-Chield").prop('checked', this.checked);
            $(".checkBoxClass").prop('checked', this.checked);
        }else{
            $(".check_clear_signoff").prop('checked', this.checked);
            $('.allCheckUncheck').prop('checked', this.checked);
        }
        allowAll();
    });
});
</script>