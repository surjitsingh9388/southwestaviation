<div id="aircarftOptionWOATACodeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter ATA Code</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($woitematacodes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemATACode'));
                    ?>
                    <input type="hidden" name="wo_ata_code_id" id="wo_ata_code_id" value="<?php echo @$woitematacodes->id; ?>" />
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">ATA Code</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('item_ata_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'item_ata_code')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveAircraftWOATACodebtn">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>