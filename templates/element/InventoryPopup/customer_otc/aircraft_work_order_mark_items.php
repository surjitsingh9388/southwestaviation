<div id="aircarftWOMarkItemsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Mark Items</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $owner_authentication = ['1'=>'Open', '2'=>'Yes', '3'=>'No'];

                    $listofitems = '';
                    $aircraftwoitemdata = '';
                    $woitemidarr = [];

                    foreach($aircraftwoitems as $woitem){
                        $woitemidarr[] = $woitem['id'];
                        if($woitem['id'] == $wo_item_id){
                            $aircraftwoitemdata = $woitem;
                        }
                        $listofitems .='<tr class="">';
                        $listofitems .='<td>'.$woitem['wo_item_position'].'</td>';
                        $listofitems .='<td>'.$woitem['wo_discrepancy'].'</td>';
                        $listofitems .='<td>'.(!empty($woitem['wo_item_overviews']['owner_authentication']) ? $owner_authentication[$woitem['wo_item_overviews']['owner_authentication']] : '').'</td>';
                        $listofitems .='</tr>';
                    }

                    echo $this->Form->create($aircraftwoitemdata, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOMarkItems'));
                    ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="wo_item_ids" id="wo_item_ids" value="<?php echo implode(',', $woitemidarr); ?>" />

                        <fieldset class="scheduler-border mt10">
                            <legend class="scheduler-border wosignoffleg">Item # Info</legend>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Item No.</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('wo_item_position', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'mark_wo_item_position')); ?>
                                    </div>
                                </div>
                            </div>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Discrepancy</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('wo_discrepancy', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'mark_wo_discrepancy', 'readonly'=>'readonly')); ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <p>Owner Authorization</p>
                            
                            <input type="radio" name="owner_authentication" id="mark_owner_authentication" value="2" disabled <?php if($aircraftwoitemdata['wo_item_overviews']['owner_authentication'] == '2'){ echo "checked";} ?>  />&nbsp;Yes&nbsp;&nbsp;

                            <input type="radio" name="owner_authentication" id="mark_owner_authentication" value="3" disabled <?php if($aircraftwoitemdata['wo_item_overviews']['owner_authentication'] == '3'){ echo "checked";} ?> />&nbsp;No&nbsp;&nbsp;

                            <input type="radio" name="owner_authentication" id="mark_owner_authentication" value="1" disabled <?php if($aircraftwoitemdata['wo_item_overviews']['owner_authentication'] == '1'){ echo "checked";} ?> />&nbsp;Open

                            <button type="button" class="btn btn-default float-right wo-mark-all-item-yes">Mark All Items "Yes"</button>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <fieldset class="scheduler-border mt10">
                            <legend class="scheduler-border wosignoffleg">List of Items</legend>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Item</th>
                                        <th scope="col">Discrepancy</th>
                                        <th scope="col">Authorization</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $listofitems; ?>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveAircraftWODetBTN">Save</button-->
            </div>
        </div>
    </div>
</div>
<script>
    var woMarkAllItemsYesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'woMarkAllItemsYes']); ?>";
</script>