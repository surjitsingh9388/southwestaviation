<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
   
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Role Info</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($role, array('class' => 'form-horizontal form-label-left', 'id' => 'frmRole', 'data-parsley-validate')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_name">Role Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Role Name', 'requred' => 'required', 'label' => false)); ?>
                            </div>
                            <?php if(!empty($menuItems)){?>
                            <!-- <i class="pull-right fa fa-question-circle" title="Click me to toggle the popup"></i> -->
                            <?php } ?>
                        </div>
                        
                        <?php if(!empty($menuItems)){ ?>
                        <div class="ln_solid"></div>
                        <table width="100%" class="Panel" border="1">
                            <tr style="background-color: #f0f0f0;">
                                <th style="vertical-align: bottom; text-align: center;">Allow</th>
                                <th style="vertical-align: bottom; text-align: center;" rowspan="3" colspan="3">Menu Items</th>
                                <th style="text-align: center;" colspan="4">Actions</th>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <td style="vertical-align: middle; text-align: center;" rowspan="2"><?php echo $this->Form->input('allCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'allCheckUncheck', 'label' => false, 'title' => 'Check All']); ?></td>
                                <th class="text-alignment">Add/Create</th>
                                <th class="text-alignment">Edit</th>
                                <th class="text-alignment">View</th>
                                <th class="text-alignment">Delete</th>
                            </tr>
                            <tr style="background-color: #f0f0f0;">
                                <th class="text-alignment"><?php echo $this->Form->input('allAddCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllAdd', 'label' => false, 'title' => 'Check All Add']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->input('allEditCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllEdit', 'label' => false, 'title' => 'Check All Edit']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->input('allViewCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllView', 'label' => false, 'title' => 'Check All View']); ?></th>
                                <th class="text-alignment"><?php echo $this->Form->input('allDeleteCheckUncheck', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkAllDelete', 'label' => false, 'title' => 'Check All Delete']); ?></th>
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
                                        echo '<tr style="background-color: #f0f0f0;">
                                                <th style="vertical-align: top; text-align: center;">'.$this->Form->input('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked]).'</th>
                                                <th style="" colspan="3"><span style="margin-left:5px;">'.$key1.'</span></th>
                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_add check_add main_action_add_'.$key]).'</td>
                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_edit check_edit main_action_edit_'.$key]).'</td>
                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_view check_view main_action_view_'.$key]).'</td>
                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key, 'class' => 'chield_'.$key. ' action_'.$key. ' action_delete check_delete main_action_delete_'.$key]).'</td>
                                            <tr>';
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
                                                }
                                                
                                                if(!empty($value) && $value == 'Generate Report') {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">'.$this->Form->input('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                                            <td colspan="3"></td>
                                                        </tr>';
                                                } else {
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">'.$this->Form->input('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">'.$value.'</span></td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
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
                                                    }
                                                    echo '<tr>
                                                            <td></td>
                                                            <td style="vertical-align: top; text-align: center">'.$this->Form->input('menu_item_id.'.$key2.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key.' lastChield_parent_'.$key2, 'label' => false, 'value' => $key2, 'id' => 'chield_'.$key, 'checked' => $checked]).'</td>
                                                            <td style="" colspan="2"><span style="margin-left:5px;">'.$key3.'</span></td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'__view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key2.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                                        </tr>';
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
                                                            }
                                                        echo '<tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="vertical-align: top; text-align: center">'.$this->Form->input('menu_item_id.'.$key5.'.menu_item_id', ['type' => 'checkbox', 'div'=>false, 'class' => 'checkBoxClass child-term action_parent_'.$key. ' chield_'.$key, 'label' => false, 'value' => $key5, 'id' => 'chield_'.$key.'_'.$key2, 'checked' => $checked]).'</td>
                                                                <td style=""><span style="margin-left:5px;">'.$value5.'</span></td>
                                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key5.'.action_add', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_add_'.$key2, 'class' => 'chield_'.$key. ' action_add_'.$key. ' action check_add add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key5.'.action_edit', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_edit_'.$key2, 'class' => 'chield_'.$key. ' action_edit_'.$key. ' action check_edit edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key5.'.action_view', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_view_'.$key2, 'class' => 'chield_'.$key. ' action_view_'.$key. ' action check_view view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                                                <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key5.'.action_delete', ['type' => 'checkbox', 'div'=>false, 'label' => false, 'id' => 'chield_'.$key.'_delete_'.$key2, 'class' => 'chield_'.$key. ' action_delete_'.$key. ' action check_delete delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
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
                                    }
                                    echo '<tr>
                                            <th style="vertical-align: top; text-align: center">'.$this->Form->input('menu_item_id.'.$key.'.menu_item_id', ['type' => 'checkbox', 'class' => 'selectAll-Chield checkBoxClass parent-term parent_'.$key, 'label' => false, 'value' => $key, 'id' => $key, 'checked' => $checked ]).'</th>
                                            <th style=""  colspan="3"><span style="margin-left:5px;">'.$menuItem.'</span></th>
                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_add', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_add', 'checked' => $data['action_add']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_edit', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_edit', 'checked' => $data['action_edit']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_view', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_view', 'checked' => $data['action_view']=='1'?true:false]).'</td>
                                            <td class="text-alignment">'.$this->Form->input('menu_item_id.'.$key.'.action_delete', ['type' => 'checkbox', 'label' => false, 'id' => $key, 'class' => 'singleRow chield_'.$key.' check_delete', 'checked' => $data['action_delete']=='1'?true:false]).'</td>
                                        </tr>';
                                }
                            }
                            ?>
                        </table>
                        <?php } ?>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success submit']);
                                }
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- confirmation popup modal -->
<div id="confirmationPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
    <?php echo  $this->element('permission_confirmation_popup'); ?>
</div>

<div class="loader"> 
    <?php
    echo '<i class="fa fa-spinner fa-spin" style="color: #FFFFFF; top:40%; position: fixed; left:50%; font-size: 50px;"></i>'; 
    ?>
</div>

<div id="helpTextPopup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="helpTextModal" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Permission Help</h4>
            </div>
            <div class="modal-body" id="popupMsg">
                <h5>Login As Permission:</h5>
                    <p class="help-left-margin">1- If User has add/edit permission for "Login as" then Login As button will come on user list page.</p>
            </div>
            <div class="modal-footer">
                <button class="closeHelpText btn" id="closeBtn" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- end confirmation popup modal -->
<style type="text/css">
    .text-alignment{
        text-align: center;
    }
    .modal-body{
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .help-left-margin{
        margin-left: 10px;
        text-align: justify;
        margin-top: -10px;
    }
    .fa-question-circle{
        font-size: 25px;
    }
</style>
<script>
//When all checkboxes are checked then allow checkbox should checked
function allowAll() {
    var addAll   = $('.checkAllAdd').is(':checked');
    var editAll  = $('.checkAllEdit').is(':checked');
    var viewAll  = $('.checkAllView').is(':checked');
    var deletAll = $('.checkAllDelete').is(':checked');
    if(addAll === true && editAll === true && viewAll === true && deletAll === true){
        $('.allCheckUncheck').prop('checked', 'checked');
    }
}

$(document).ready(function() {
    $("div").removeClass("checkbox");

    //popup help text information
    $('.fa-question-circle').click(function(e){
        $("#helpTextPopup").modal('show');
    });

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
        }
        $('.chield_'+id).prop('checked', this.checked);

        var add = $("input[type='checkbox'].check_add");
        var edit = $("input[type='checkbox'].check_edit");
        var view = $("input[type='checkbox'].check_view");
        var delet = $("input[type='checkbox'].check_delete");
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
            $('.main_action_add_'+array[1]).prop('checked', this.checked);
            $('.main_action_edit_'+array[1]).prop('checked', this.checked);
            $('.main_action_view_'+array[1]).prop('checked', this.checked);
            $('.main_action_delete_'+array[1]).prop('checked', this.checked);
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

        var add = $("input[type='checkbox'].check_add");
        var edit = $("input[type='checkbox'].check_edit");
        var view = $("input[type='checkbox'].check_view");
        var delet = $("input[type='checkbox'].check_delete");
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

    //If menu item has no chield after selecting (Add,Edit,View and Delete) parent node should be select.
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

    $( "#frmRole" ).validate( {
        rules: {
            'role_name': {
                required: true,
                minlength: 5
            }
        },
        messages: {
            'role_name': {
                required: "Please enter role name.",
                minlength: "Role name must consist of at least 5 characters."
            }
        },
        submitHandler: function (form) {
            //if($('.submit').is(":clicked")){
                $("#confirmationPopup").modal('show');
                $('#submitForm').click(function () {
                    form.submit();
                });
            //}else{
                //form.submit();
            //}
        },
        errorClass: "error",
        errorElement: "label"
    });
    
    $('#reset').click(function() {
       var validator = $("#frmRole").validate();
       validator.resetForm();
    });
});     
</script>