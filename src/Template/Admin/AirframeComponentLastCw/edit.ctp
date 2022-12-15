<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Airframe Components Last CW</h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <!--serch box-->
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Update Airframe Component Last CW</h2>
                    <?php
                        $sessionArray = $this->Session->read('Auth.User');
                        echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php echo $this->Form->create($airCompLastCW, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCompLastCW')); ?>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Plane Name <span class="required">*</span>
                            </label>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Plane', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'planeName')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Airframe Component <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12 fff" id="airCompsList">
                                <?php echo $this->Form->control('airframe_component_id', array('options' => $airComps, 'empty' => 'Select Aircraft Component', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_category_id">Airframe Component Category <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('airframe_component_category_id', array('options' => $airCompCats, 'empty' => 'Select Aircraft Component Category', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_part_id">Airframe Component Part <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('airframe_component_part_id', array('options' => $airCompParts, 'empty' => 'Select Aircraft Component Part', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_date">Last C/W Date 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('last_cw_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'cwDatepicker', 'placeholder' => 'Log Date', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hrs">Hours
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('hrs', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Hours', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="afl">Cycles
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('afl', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Cycles', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="msc">Misc
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('msc', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Misc', 'label' => false)); ?>
                            </div>
                        </div>
                        
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']);
                                }
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset', 'id' => 'reset']);
                                ?>
                            </div>
                        </div>
                        <?php echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label'=> false)); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>    
$(document).ready(function() {
    $('#cwDatepicker').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('#reset').click(function() {
       var validator = $("#frmAirCompLastCW").validate();
       validator.resetForm();
    });

    $('#planeName').on('change', function() {
        var planeId = $( this ).val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'AirframeComponents', 'action'=>'getComponents']); ?>",
            data: {planeId:planeId},
            async : true,
            success: function(response) {
                $("#airCompsList").html(response);
                $("#airframe_component_id").selectpicker();
                $("#airframe_component_id.selectpicker").rules('add', {
                    required: true,
                    messages: {
                        required: "Please select component."
                    }
                });
                // show validation message on change
                $('#frmAirCompPart select.selectpicker').on('change', function(e) {
                    $('#frmAirCompPart').validate().element($(this));
                });
            }                   
        }); 
    });
    
});    
</script>
