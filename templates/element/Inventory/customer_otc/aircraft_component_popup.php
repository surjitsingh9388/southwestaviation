<div id="aircraftComponentDetModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Aircraft & Compnent</h4>
            </div>
            <div class="modal-body" style="min-height: 580px; overflow-y: auto;">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCompPartSelect'));
                        ?>
                        <table class="table table-bordered" id="aircraft_component_block">
                            <thead>
                                <tr>
                                    <th class="col-sm-2">Aircraft</th>
                                    <th class="col-sm-3">Component</th>
                                    <th class="col-sm-1">Action</th>
                                </tr>
                            </thead>
                            <tbody id="aircraft_comp_select_block">
                                <?php 
                                if(!empty($selectedAircraft)){
                                    foreach($selectedAircraft as $keys=>$aircraftId){
                                        $componentId = $selectedComponent[$keys];
                                        echo $this->element('aircraft_component_add_row', ['planes'=>$planes, 'components'=>$components[$aircraftId], 'aircraftId'=>$aircraftId, 'componentId'=>$componentId, 'counter'=>$keys+1]);
                                    }
                                }else{
                                    echo $this->element('aircraft_component_add_row', ['planes'=>$planes, 'components'=>[], 'aircraftId'=>'', 'componentId'=>'', 'counter'=>'1']);
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php echo $this->Form->end(); ?>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default aircraft_comp_addmorebtn">Add More</button>
                    </div> 
                </div>
            </div> 

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary aircraft_component_selectbtn">Select</button>
            </div>
        </div>
    </div>
</div>