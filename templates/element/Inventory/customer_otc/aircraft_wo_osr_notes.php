<section class="top-form-section">
    <div class="row">
        <?php
        echo $this->Form->create($aircraftwoosrvendors, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOSRVendorNotes'));
        ?>
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label" for="reference">Notes</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->input('vendor_notes', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'vendor_notes')); ?>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>  
</section>