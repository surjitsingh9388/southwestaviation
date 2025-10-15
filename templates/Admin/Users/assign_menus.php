<?php
$sessionUser = $this->request->getSession()->read('Auth');;
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Permissions</h2>
            <?php
            if ((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("<i class='fa fa-plus'></i> Add User", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>

        <div class="page-content mt-35">
            <?php echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAssignMenu', 'role' => 'form', 'data-toggle' => 'validator')); ?>
            <div class="panel panel-default">
                <!-- <div class="panel-heading"><h3 class="panel-title">Permissions Access List<i class="pull-right fa fa-question-circle" title="Click me to toggle the popup"></i></h3>
                </div> -->

                <div class="panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">User <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <?php echo $this->Form->control('user_id', array('options' => $allAdmins, 'empty' => 'Select User', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'label' => false, 'data-show-subtext' => true, 'data-live-search' => true)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12" style="padding:0px;">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <?php
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'id' => 'frmClicked', 'class' => 'btn btn-success clickedd']);
                                    ?>
                                    <button class="btn btn-success buttonload" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> <?php echo ucfirst(strtolower(SUBMITING)); ?>
                                    </button>
                                    <?php
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                    echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-heading">
                    <h3 class="panel-title">Permissions List</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group" id="menuItemTable">
                        <table width="100%" class="Panel" border="1">
                            <tr style="background-color: #f0f0f0;">
                                <th style="vertical-align: bottom; text-align: center;">Allow</th>
                                <th style="vertical-align: bottom; text-align: center;" rowspan="3" colspan="3">Menu Items</th>
                                <th style="text-align: center;" colspan="7">Actions</th>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <td style="vertical-align: middle; text-align: center;" rowspan="2"><?php echo $this->Form->control('allCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'allCheckUncheck', 'label' => false, 'title' => 'Check All']); ?></td>
                                <th class="text-alignment equal-width">Add</th>
                                <th class="text-alignment equal-width">Edit</th>
                                <th class="text-alignment equal-width">View</th>
                                <th class="text-alignment equal-width">Delete</th>
                                <th class="text-alignment equal-width">Approve/Deny</th>
                                <th class="text-alignment nowrap ">Reopen Work Order</th>
                                <th class="text-alignment equal-width nowrap">Final Inspections</th>
                                <th class="text-alignment equal-width nowrap">Clear Signoff</th>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <th class="text-alignment "><?php echo $this->Form->control('allAddCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllAdd', 'label' => false, 'title' => 'Check All Add']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allEditCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllEdit', 'label' => false, 'title' => 'Check All Edit']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allViewCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllView', 'label' => false, 'title' => 'Check All View']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allDeleteCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllDelete', 'label' => false, 'title' => 'Check All Delete']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allApproveDenyCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllApproveDeny', 'label' => false, 'title' => 'Check All Approve/Deny']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allReopenWorkOrderCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllReopenWorkOrder', 'label' => false, 'title' => 'Check All Reopen Work Order']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allFinalInspectionsCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllFinalInspections', 'label' => false, 'title' => 'Check All Final Inspections']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->control('allClearSignoffCheckUncheck', ['type' => 'checkbox', 'div' => false, 'class' => 'checkAllClearSignoff', 'label' => false, 'title' => 'Check All Clear Signoff']); ?></th>
                            </tr>
                            <?php
                            foreach ($menuItems as $key => $menuItem) {
                                if (is_array($menuItem)) {
                                    foreach ($menuItem as $key1 => $subItems) {
                                        //pr($subItems);
                                        if ($key1 == 'Inventory') {
                                            echo '<tr style="background-color: #f0f0f0;">
                                                <th style="vertical-align: top; text-align: center;">' . $this->Form->control('menu_item_id.' . $key . '.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_' . $key, 'label' => false, 'value' => $key, 'id' => $key]) . '</th>
                                                <th style="" colspan="3"><span style="margin-left:5px;">' . $key1 . '</span></th>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_add check_add main_action_add_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_edit check_edit main_action_edit_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_view check_view main_action_view_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_delete check_delete main_action_delete_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_approve_deny', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_approve_deny check_approve_deny main_action_approve_deny_' . $key]) . '</td>
                                                <td class="text-alignment">&nbsp;</td>
                                                <td class="text-alignment">&nbsp;</td>
                                                <td class="text-alignment">&nbsp;</td>
                                            <tr>';
                                        } else if ($key1 == 'Maintenance') {
                                            echo '<tr style="background-color: #f0f0f0;">
                                                <th style="vertical-align: top; text-align: center;">' . $this->Form->control('menu_item_id.' . $key . '.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_' . $key, 'label' => false, 'value' => $key, 'id' => $key]) . '</th>
                                                <th style="" colspan="3"><span style="margin-left:5px;">' . $key1 . '</span></th>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_add check_add main_action_add_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_edit check_edit main_action_edit_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_view check_view main_action_view_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_delete check_delete main_action_delete_' . $key]) . '</td>
                                                <td>&nbsp;</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_reopen_work_order', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_reopen_work_order check_reopen_work_order main_action_reopen_work_order_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_final_inspections', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_final_inspections check_final_inspections main_action_final_inspections_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_clear_signoff', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_clear_signoff check_clear_signoff main_action_clear_signoff_' . $key]) . '</td>
                                            <tr>';
                                        } else {
                                            echo '<tr style="background-color: #f0f0f0;">
                                                <th style="vertical-align: top; text-align: center;">' . $this->Form->control('menu_item_id.' . $key . '.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_' . $key, 'label' => false, 'value' => $key, 'id' => $key]) . '</th>
                                                <th style="" colspan="3"><span style="margin-left:5px;">' . $key1 . '</span></th>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_add check_add main_action_add_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_edit check_edit main_action_edit_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_view check_view main_action_view_' . $key]) . '</td>
                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key, 'class' => 'chield_' . $key . ' action_' . $key . ' action_delete check_delete main_action_delete_' . $key]) . '</td>
                                                <td class="text-alignment">&nbsp;</td>
                                                <td class="text-alignment">&nbsp;</td>
                                                <td class="text-alignment">&nbsp;</td>
                                                <td class="text-alignment">&nbsp;</td>
                                            <tr>';
                                        }

                                        foreach ($subItems as $key2 => $value) {
                                            if (!is_array($value)) {
                                                if (!empty($value) && $value == 'Generate Report') {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key2 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key, 'label' => false, 'value' => $key2, 'id' => 'chield_' . $key]) . '</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">' . $value . '</span></td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                            <td colspan="3"></td>
                                                        </tr>';
                                                } else if (!empty($value) && $value == 'Requests') {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key2 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key, 'label' => false, 'value' => $key2, 'id' => 'chield_' . $key]) . '</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">' . $value . '</span></td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_edit_' . $key2, 'class' => 'chield_' . $key . ' action_edit_' . $key . ' action check_edit edit']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_view_' . $key2, 'class' => 'chield_' . $key . ' action_view_' . $key . ' action check_view view']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_delete_' . $key2, 'class' => 'chield_' . $key . ' action_delete_' . $key . ' action check_delete delete']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_approve_deny', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_approve_deny_' . $key2, 'class' => 'chield_' . $key . ' action_approve_deny_' . $key . ' action check_approve_deny approve_deny']) . '</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                        </tr>';
                                                } else if (!empty($value) && $value == 'Work Orders') {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key2 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key, 'label' => false, 'value' => $key2, 'id' => 'chield_' . $key]) . '</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">' . $value . '</span></td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_edit_' . $key2, 'class' => 'chield_' . $key . ' action_edit_' . $key . ' action check_edit edit']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_view_' . $key2, 'class' => 'chield_' . $key . ' action_view_' . $key . ' action check_view view']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_delete_' . $key2, 'class' => 'chield_' . $key . ' action_delete_' . $key . ' action check_delete delete']) . '</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_reopen_work_order', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_reopen_work_order_' . $key2, 'class' => 'chield_' . $key . ' action_reopen_work_order_' . $key . ' action check_reopen_work_order reopen_work_order']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_final_inspections', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_final_inspections_' . $key2, 'class' => 'chield_' . $key . ' action_final_inspections_' . $key . ' action check_final_inspections final_inspections']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_clear_signoff', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_clear_signoff_' . $key2, 'class' => 'chield_' . $key . ' action_clear_signoff_' . $key . ' action check_clear_signoff clear_signoff']) . '</td>
                                                        </tr>';
                                                } else {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key2 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key, 'label' => false, 'value' => $key2, 'id' => 'chield_' . $key]) . '</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">' . $value . '</span></td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_edit_' . $key2, 'class' => 'chield_' . $key . ' action_edit_' . $key . ' action check_edit edit']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_view_' . $key2, 'class' => 'chield_' . $key . ' action_view_' . $key . ' action check_view view']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_delete_' . $key2, 'class' => 'chield_' . $key . ' action_delete_' . $key . ' action check_delete delete']) . '</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                        </tr>';
                                                }
                                            } else {
                                                foreach ($value as $key3 => $valueLast) {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key2 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key . ' lastChield_parent_' . $key2, 'label' => false, 'value' => $key2, 'id' => 'chield_' . $key]) . '</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">' . $key3 . '</span></td><td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_edit_' . $key2, 'class' => 'chield_' . $key . ' action_edit_' . $key . ' action check_edit edit']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_view_' . $key2, 'class' => 'chield_' . $key . ' action_view_' . $key . ' action check_view view']) . '</td>
                                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key2 . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_delete_' . $key2, 'class' => 'chield_' . $key . ' action_delete_' . $key . ' action check_delete delete']) . '</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                            <td class="text-alignment">&nbsp;</td>
                                                        </tr>';
                                                    if ($key3 == 'SWAS' || $key3 == 'BVAC' || $key3 == 'RJC') {
                                                        continue;
                                                    }
                                                    foreach ($valueLast as $key4 => $value4) {
                                                        foreach ($value4 as $key5 => $value5) {
                                                            echo '<tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key5 . '.menu_item_id', ['type' => 'checkbox', 'div' => false, 'class' => 'checkBoxClass child-term action_parent_' . $key . ' chield_' . $key, 'label' => false, 'value' => $key5, 'id' => 'chield_' . $key . '_' . $key2]) . '</td>
                                                                <td style=""><span style="margin-left:5px;">' . $value5 . '</span></td>
                                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key5 . '.action_add', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_add_' . $key2, 'class' => 'chield_' . $key . ' action_add_' . $key . ' action check_add add']) . '</td>
                                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key5 . '.action_edit', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_edit_' . $key2, 'class' => 'chield_' . $key . ' action_edit_' . $key . ' action check_edit edit']) . '</td>
                                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key5 . '.action_view', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_view_' . $key2, 'class' => 'chield_' . $key . ' action_view_' . $key . ' action check_view view']) . '</td>
                                                                <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key5 . '.action_delete', ['type' => 'checkbox', 'div' => false, 'label' => false, 'id' => 'chield_' . $key . '_delete_' . $key2, 'class' => 'chield_' . $key . ' action_delete_' . $key . ' action check_delete delete']) . '</td>
                                                                <td class="text-alignment">&nbsp;</td>
                                                                <td class="text-alignment">&nbsp;</td>
                                                                <td class="text-alignment">&nbsp;</td>
                                                                <td class="text-alignment">&nbsp;</td>
                                                            </tr>';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    echo '<tr>
                                            <th style="vertical-align: top; text-align: center">' . $this->Form->control('menu_item_id.' . $key . '.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_' . $key, 'label' => false, 'value' => $key, 'id' => $key]) . '</th>
                                            <th style=""  colspan="3"><span style="margin-left:5px;">' . $menuItem . '</span></th>
                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_add', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_' . $key . ' check_add']) . ' </td>
                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_edit', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_' . $key . ' check_edit']) . '</td>
                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_view', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_' . $key . ' check_view']) . '</td>
                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_delete', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_' . $key . ' check_delete']) . '</td>
                                            <td class="text-alignment">' . $this->Form->control('menu_item_id.' . $key . '.action_approve_deny', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_' . $key . ' check_approve_deny']) . '</td>
                                            <td class="text-alignment">&nbsp;</td>
                                            <td class="text-alignment">&nbsp;</td>
                                            <td class="text-alignment">&nbsp;</td>
                                        </tr>';
                                }
                            }
                            ?>
                        </table>
                    </div>

                    <div class="form-group" id="ajaxMenuItemTable">
                    </div>
                </div>

                <!--div class="ln_solid"></div-->
                
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<style type="text/css">
    .text-alignment {
        text-align: center;
    }

    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .help-left-margin {
        margin-left: 10px;
        text-align: justify;
        margin-top: -10px;
    }

    /* .bootstrap-select .dropdown-menu .inner {
        max-height: 200px;
        overflow-y: auto;
    } */

    @media screen and (max-width: 897px) {

        th.equal-width,
        th.nowrap {
            white-space: normal !important;
            font-size: 12px;
            min-width: 60px;
        }
    }
</style>

<script>
    //When all checkboxes are checked then allow checkbox should checked
    function allowAll() {
        var addAll = $('.checkAllAdd').is(':checked');
        var editAll = $('.checkAllEdit').is(':checked');
        var viewAll = $('.checkAllView').is(':checked');
        var approveDenyAll = $('.checkAllApproveDeny').is(':checked');
        var reopenWorkOrderAll = $('.checkAllReopenWorkOrder').is(':checked');
        var finalInspectionsAll = $('.checkAllFinalInspections').is(':checked');
        var clearSignoffAll = $('.checkAllClearSignoff').is(':checked');

        if (addAll === true && editAll === true && viewAll === true && deletAll === true && approveDenyAll === true && reopenWorkOrderAll === true && finalInspectionsAll === true && clearSignoffAll === true) {
            $('.allCheckUncheck').prop('checked', 'checked');
        }
    }
    $(document).ready(function() {

        $("div").removeClass("checkbox");
        //popup help text information
        $('.fa-question-circle').click(function(e) {
            $("#helpTextPopup").modal('show');
        });

        //Make checked all chield checkbox
        $('.selectAll-Chield').click(function(e) {
            var id = $(this).attr('id');
            if ($(this).is(':checked') === false) {
                $('#' + id).removeAttr('checked');
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
            $('.chield_' + id).prop('checked', this.checked);

            var add = $("input[type='checkbox'].check_add");
            var edit = $("input[type='checkbox'].check_edit");
            var view = $("input[type='checkbox'].check_view");
            var delet = $("input[type='checkbox'].check_delete");
            var approvedeny = $("input[type='checkbox'].check_approve_deny");
            var reopenworkorder = $("input[type='checkbox'].check_reopen_work_order");
            var finalinspections = $("input[type='checkbox'].check_final_inspections");
            var clearsignoff = $("input[type='checkbox'].check_clear_signoff");

            if (add.length == add.filter(":checked").length) {
                $('#alladdcheckuncheck').prop('checked', this.checked);
            }

            if (edit.length == edit.filter(":checked").length) {
                $('#alleditcheckuncheck').prop('checked', this.checked);
            }

            if (view.length == view.filter(":checked").length) {
                $('#allviewcheckuncheck').prop('checked', this.checked);
            }

            if (delet.length == delet.filter(":checked").length) {
                $('#alldeletecheckuncheck').prop('checked', this.checked);
            }

            if (approvedeny.length == approvedeny.filter(":checked").length) {
                $('#allapprovedenycheckuncheck').prop('checked', this.checked);
            }

            if (reopenworkorder.length == reopenworkorder.filter(":checked").length) {
                $('#allreopenworkordercheckuncheck').prop('checked', this.checked);
            }

            if (finalinspections.length == finalinspections.filter(":checked").length) {
                $('#allfinalinspectionscheckuncheck').prop('checked', this.checked);
            }

            if (clearsignoff.length == clearsignoff.filter(":checked").length) {
                $('#allclearsignoffcheckuncheck').prop('checked', this.checked);
            }

            allowAll();
        });

        //Make checked all chield checkbox and as well as parent if not checked
        $(".child-term").click(function(e) {
            var clickedItemStatus = $(this).is(':checked');
            var id = $(this).attr('id');
            var array = id.split('_');
            var status = $('#' + array[1]).is(':checked');
            var lastChield_parent = $('.lastChield_parent_' + array[2]).is(':checked');
            if (lastChield_parent === false) {
                $('.lastChield_parent_' + array[2]).prop('checked', this.checked);
            }
            if (clickedItemStatus === false) {
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllAdd').removeAttr('checked');
                $('.checkAllEdit').removeAttr('checked');
                $('.checkAllView').removeAttr('checked');
                $('.checkAllDelete').removeAttr('checked');
                $('.checkAllApproveDeny').removeAttr('checked');
                $('.checkAllReopenWorkOrder').removeAttr('checked');
                $('.checkAllFinalInspections').removeAttr('checked');
                $('.checkAllClearSignoff').removeAttr('checked');

                $('.main_action_add_' + array[1]).prop('checked', this.checked);
                $('.main_action_edit_' + array[1]).prop('checked', this.checked);
                $('.main_action_view_' + array[1]).prop('checked', this.checked);
                $('.main_action_delete_' + array[1]).prop('checked', this.checked);
                $('.main_action_approve_deny_' + array[1]).prop('checked', this.checked);
                $('.main_action_reopen_work_order_' + array[1]).prop('checked', this.checked);
                $('.main_action_final_inspections_' + array[1]).prop('checked', this.checked);
                $('.main_action_clear_signoff_' + array[1]).prop('checked', this.checked);
            }
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            var tr = $(e.target).closest('tr');
            $('td input:checkbox', tr).prop('checked', this.checked);

            var addParentCheck = $("input[type='checkbox'].action_add_" + array[1]);
            var editParentCheck = $("input[type='checkbox'].action_edit_" + array[1]);
            var viewParentCheck = $("input[type='checkbox'].action_view_" + array[1]);
            var deleteParentCheck = $("input[type='checkbox'].action_delete_" + array[1]);
            var approveDenyParentCheck = $("input[type='checkbox'].action_approve_deny_" + array[1]);
            var reopenWorkOrderParentCheck = $("input[type='checkbox'].action_reopen_work_order_" + array[1]);
            var finalInspectionsParentCheck = $("input[type='checkbox'].action_final_inspections_" + array[1]);
            var clearSignoffParentCheck = $("input[type='checkbox'].action_clear_signoff_" + array[1]);

            if (addParentCheck.length == addParentCheck.filter(":checked").length) {
                $('.main_action_add_' + array[1]).prop('checked', this.checked);
            }
            if (editParentCheck.length == editParentCheck.filter(":checked").length) {
                $('.main_action_edit_' + array[1]).prop('checked', this.checked);
            }
            if (viewParentCheck.length == viewParentCheck.filter(":checked").length) {
                $('.main_action_view_' + array[1]).prop('checked', this.checked);
            }
            if (deleteParentCheck.length == deleteParentCheck.filter(":checked").length) {
                $('.main_action_delete_' + array[1]).prop('checked', this.checked);
            }
            if (approveDenyParentCheck.length == approveDenyParentCheck.filter(":checked").length) {
                $('.main_action_approve_deny_' + array[1]).prop('checked', this.checked);
            }
            if (reopenWorkOrderParentCheck.length == reopenWorkOrderParentCheck.filter(":checked").length) {
                $('.main_action_reopen_work_order_' + array[1]).prop('checked', this.checked);
            }
            if (finalInspectionsParentCheck.length == finalInspectionsParentCheck.filter(":checked").length) {
                $('.main_action_final_inspections_' + array[1]).prop('checked', this.checked);
            }
            if (clearSignoffParentCheck.length == clearSignoffParentCheck.filter(":checked").length) {
                $('.main_action_clear_signoff_' + array[1]).prop('checked', this.checked);
            }

            var add = $("input[type='checkbox'].check_add");
            var edit = $("input[type='checkbox'].check_edit");
            var view = $("input[type='checkbox'].check_view");
            var delet = $("input[type='checkbox'].check_delete");
            var approvedeny = $("input[type='checkbox'].check_approve_deny");
            var reopenworkorder = $("input[type='checkbox'].check_reopen_work_order");
            var finalinspections = $("input[type='checkbox'].check_final_inspections");
            var clearsignoff = $("input[type='checkbox'].check_clear_signoff");

            if (add.length == add.filter(":checked").length) {
                $('#alladdcheckuncheck').prop('checked', this.checked);
            }

            if (edit.length == edit.filter(":checked").length) {
                $('#alleditcheckuncheck').prop('checked', this.checked);
            }

            if (view.length == view.filter(":checked").length) {
                $('#allviewcheckuncheck').prop('checked', this.checked);
            }

            if (delet.length == delet.filter(":checked").length) {
                $('#alldeletecheckuncheck').prop('checked', this.checked);
            }

            if (approvedeny.length == approvedeny.filter(":checked").length) {
                $('#allapprovedenycheckuncheck').prop('checked', this.checked);
            }

            if (reopenworkorder.length == reopenworkorder.filter(":checked").length) {
                $('#allreopenworkordercheckuncheck').prop('checked', this.checked);
            }

            if (finalinspections.length == finalinspections.filter(":checked").length) {
                $('#allfinalinspectionscheckuncheck').prop('checked', this.checked);
            }

            if (clearsignoff.length == clearsignoff.filter(":checked").length) {
                $('#allclearsignoffcheckuncheck').prop('checked', this.checked);
            }

            allowAll();
        });

        //Make all parent checkbox checked if not checked
        $(".action").click(function(e) {
            var clicked = $(this).is(':checked');
            var id = $(this).attr('id');
            var array = id.split('_');
            var classClickedArray = $('#' + id).attr('class');
            var clickedClass = classClickedArray.split(' ');
            var lastItem = clickedClass.pop();
            var id = $(this).attr('id');
            var array = id.split('_');
            var status = $('#' + array[1]).is(':checked');
            var lastChield_parent = $('.lastChield_parent_' + array[3]).is(':checked');
            var a = $("input[type='checkbox'].action_" + array[2] + "_" + array[1]);
            var b = $("input[type='checkbox'].check_" + array[2]);

            if (a.length == a.filter(":checked").length) {
                $('.main_action_' + array[2] + '_' + array[1]).prop('checked', this.checked);
            }

            if (b.length == b.filter(":checked").length) {
                $('#all' + array[2] + 'checkuncheck').prop('checked', this.checked);
            }

            if (clicked === false) {
                $('.main_action_' + array[2] + '_' + array[1]).removeAttr('checked');
            }

            if (lastChield_parent === false) {
                $('.lastChield_parent_' + array[3]).prop('checked', this.checked);
            }

            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            var tr = $(e.target).closest('tr');
            var attr = $('td input:checkbox:first', tr).is(':checked');
            if (attr === false) {
                $('td input:checkbox:first', tr).prop('checked', this.checked);
            }
            if (clicked === false) {
                $('.allCheckUncheck').prop('checked', this.checked);
                if (lastItem == 'add') {
                    $('.checkAllAdd').removeAttr('checked');
                }
                if (lastItem == 'edit') {
                    $('.checkAllEdit').removeAttr('checked');
                }
                if (lastItem == 'view') {
                    $('.checkAllView').removeAttr('checked');
                }
                if (lastItem == 'delete') {
                    $('.checkAllDelete').removeAttr('checked');
                }
                if (lastItem == 'approvedeny') {
                    $('.checkAllApproveDeny').removeAttr('checked');
                }
                if (lastItem == 'reopenworkorder') {
                    $('.checkAllReopenWorkOrder').removeAttr('checked');
                }
                if (lastItem == 'finalinspections') {
                    $('.checkAllFinalInspections').removeAttr('checked');
                }
                if (lastItem == 'clearsignoff') {
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_add_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_add_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllAdd').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_edit_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_edit_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllEdit').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_view_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_view_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllView').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_delete_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_delete_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllDelete').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
            }
            allowAll();
        });

        //If only approve deny action is checked then all approve deny action should be checked and as well as all parents will checked
        $(".action_approve_deny").click(function(e) {
            var id = $(this).attr('id');
            var classes = $(this).attr('class').split(' ').pop();
            var viewStatus = $(this).is(':checked');
            var array = id.split('_');
            var classArray = classes.split('_');

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_approve_deny_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_approve_deny_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllApproveDeny').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_reopen_work_order_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_reopen_work_order_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllReopenWorkOrder').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_final_inspections_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_final_inspections_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllFinalInspections').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
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

            var status = $('#' + array[1]).is(':checked');
            if (status === false) {
                $('#' + array[1]).prop('checked', this.checked);
            }
            if (viewStatus === true) {
                $('.action_clear_signoff_' + array[1]).prop('checked', this.checked);
                $('.action_parent_' + array[1]).prop('checked', this.checked);
            } else {
                $('.action_clear_signoff_' + array[1]).prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
                $('.checkAllClearSignoff').prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[2]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[2] + 'checkuncheck').prop('checked', this.checked);
            }
            allowAll();
        });

        //If menu item has no chield after selecting (Add,Edit,View and Delete) parent node should be select.
        $(".singleRow").click(function(e) {
            var id = $(this).attr('id');
            var classes = $(this).attr('class').split(' ').pop();
            var classArray = classes.split('_');

            if ($(this).is(':checked') === false) {
                $('.allCheckUncheck').prop('checked', this.checked);
                if (classArray[1] === 'add') {
                    $('.checkAllAdd').removeAttr('checked');
                }
                if (classArray[1] === 'edit') {
                    $('.checkAllEdit').removeAttr('checked');
                }
                if (classArray[1] === 'view') {
                    $('.checkAllView').removeAttr('checked');
                }
                if (classArray[1] === 'delete') {
                    $('.checkAllDelete').removeAttr('checked');
                }
                if (classArray[1] === 'approve_deny') {
                    $('.checkAllApproveDeny').removeAttr('checked');
                }
                if (classArray[1] === 'reopen_work_order') {
                    $('.checkAllReopenWorkOrder').removeAttr('checked');
                }
                if (classArray[1] === 'final_inspections') {
                    $('.checkAllFinalInspections').removeAttr('checked');
                }
                if (classArray[1] === 'clear_signoff') {
                    $('.checkAllClearSignoff').removeAttr('checked');
                }
            }
            var status = $('#' + id).is(':checked');
            if (status === false) {
                $('#' + id).prop('checked', this.checked);
            }
            var b = $("input[type='checkbox'].check_" + classArray[1]);
            if (b.length == b.filter(":checked").length) {
                $('#all' + classArray[1] + 'checkuncheck').prop('checked', this.checked);
            }
            allowAll();
        });

        // To remove error message on change of select picker
        $('#frmAssignMenu select.selectpicker').on('change', function(e) {
            $('#frmAssignMenu').validate().element($(this));
        });

        //Form validation
        $("#frmAssignMenu").validate({
            ignore: [],
            rules: {
                'user_id': {
                    required: true
                }
            },
            messages: {
                'user_id': {
                    required: "Please select user."
                }
            },
            errorClass: "error",
            errorElement: "label",
            errorPlacement: function(error, element) {
                if (element.hasClass('selectpicker')) {
                    error.insertAfter(element.next('.btn-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function(event, validator) {
                // formInvalidHandler(validator.errorList);
                $.each(validator.errorList, function(index, item) {
                    var jelm = $(item.element);
                    if (jelm.hasClass('selectpicker') && (jelm.parents('div.select').find('label.error').length != 0 || jelm.parents('div.select').find('label.error').length != 1)) {
                        jelm.siblings('.bootstrap-select').find('.selectpicker').focus();
                        return false;
                    }
                });
            }
        });

        //On button click submit form with loader
        $('#frmClicked').on('click', function(e) {
            if ($("#frmAssignMenu").valid()) {
                if ($('#frmAssignMenu input[type=checkbox]:checked').length > 0) {
                    $('body').addClass('backgroundFixed');
                    $('#overlay').show();
                    $('#frmAssignMenu').submit();
                } else {
                    e.preventDefault();
                    alert('Please select at least one permission for user.');
                }
            } else {
                $('.selectpicker').focus();
                var validator = $("#frmAssignMenu").validate();
                $('#user-id-error').focus().select();
                validator.focusInvalid();
                $('#user-id-error').focus().select();
                return false;
            }
        });

        $('#user-id').on('change', function() {
            var userID = $("#user-id").val();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'getAccessList']); ?>",
                data: {
                    user_id: userID
                },
                async: true,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(response) {
                    if (response != '') {
                        $('#menuItemTable').remove();
                        $('#ajaxMenuItemTable').html(response);
                    } else {
                        $('#ajaxMenuItemTable').find('input[type=checkbox]:checked').removeAttr('checked');
                    }
                },
                complete: function() {
                    $('.loader').hide();
                }
            });
        });
        //Make all checkboxes checked/uncheck
        $(".allCheckUncheck").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $('input[type=checkbox]').prop('checked', this.checked);
            } else {
                $('input[type=checkbox]').prop('checked', this.checked);
            }
        });

        //Make all add checkboxes checked/uncheck
        $(".checkAllAdd").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_add").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_add").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all edit checkboxes checked/uncheck
        $(".checkAllEdit").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_edit").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_edit").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all view checkboxes checked/uncheck
        $(".checkAllView").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_view").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_view").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all delete checkboxes checked/uncheck
        $(".checkAllDelete").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_delete").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_delete").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all approve deny checkboxes checked/uncheck
        $(".checkAllApproveDeny").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_approve_deny").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_approve_deny").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all reopen work order checkboxes checked/uncheck
        $(".checkAllReopenWorkOrder").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_reopen_work_order").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_reopen_work_order").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all final inspections checkboxes checked/uncheck
        $(".checkAllFinalInspections").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_final_inspections").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_final_inspections").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
        //Make all clear signoff ckboxes checked/uncheck
        $(".checkAllClearSignoff").click(function(e) {
            var status = $(this).is(':checked');
            if (status === true) {
                $(".check_clear_signoff").prop('checked', this.checked);
                $(".selectAll-Chield").prop('checked', this.checked);
                $(".checkBoxClass").prop('checked', this.checked);
            } else {
                $(".check_clear_signoff").prop('checked', this.checked);
                $('.allCheckUncheck').prop('checked', this.checked);
            }
            allowAll();
        });
    });
</script>
