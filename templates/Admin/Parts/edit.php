<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

if(!empty($part->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>
<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.1em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

    #itemGeneral{
        padding:10px;
    }

    .mb-3 {
        margin-bottom: 5px !important;
    }
    h5{
        text-align:center;
        font-weight:bold;
    }
    
    .sinfo{
        margin-top: 15px;
    }

    th{
        background-color:#f9cb9c;
        color:black;
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($part, array('class' => 'form-horizontal form-label-left', 'id' => 'frmParts'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading">Edit Part</h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Finished', ['type' => 'submit', 'class' => 'btn btn-success ml-10']);
                }
                ?>
            </div>
        </div>
       
        <div class="page-content mt-35">
            
            <input type="hidden" name="id" id="id" value="<?php echo $part['id']; ?>">
            
            <div class="formBGCls">
                <section class="top-form-section addPartBorder">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group part-number">
                                <label class="control-label" for="reference">Part Number <span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Part #')); ?>
                                </div>
                            </div><!--Part Number// -->
                            <div class="form-group part-classification">
                                <label class="control-label" for="reference">Part Classification<span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php
                                    $classificationopt = unserialize(PARTS_CLASSIFICATION);
                                    echo $this->Form->control('part_classification', array('options' => $classificationopt, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div><!-- Parts Classification// -->
                        </div>
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="reference">Serial Number <span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Searial #')); ?>
                                </div>
                            </div><!-- Serial Number// -->

                            <div class="form-group d-flex">
                                <label class="control-label" for="reference">Lot Number<span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('lot_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Lot #')); ?>
                                </div>
                            </div><!-- Lot Number// -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-flex">
                                <label class="control-label">Description <span class="required">*</span>:</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('description', array('class' => 'form-control', 'label'=> false, 'rows'=>2, 'placeholder'=>'Description')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> <!-- top-form-section// -->

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">General</a></li>
                            <li><a data-toggle="tab" href="#itemsPricing">Pricing</a></li>
                            <li><a data-toggle="tab" href="#itemsCores">Cores</a></li>
                            <li><a data-toggle="tab" href="#itemsAlts">ALt#s</a></li>
                            <li><a data-toggle="tab" href="#itemsUsage">Usage</a></li>
                            <li><a data-toggle="tab" href="#itemsHistory">History</a></li>
                            <li><a data-toggle="tab" href="#itemsPhotos">Photos</a></li>
                            <li><a data-toggle="tab" href="#itemsFiles">Files</a></li>
                            <li><a data-toggle="tab" href="#itemsNotes">Notes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="col-md-6 mb-md-0 p-md-4">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Stock Information</legend>
                                            <div class="control-group">
                                                <section class="bottom-form-section">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="type_of_part"># in Stock</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('type_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter in Stock')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="owner_of_part"># for Owners</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('family_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter for Owners')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="control-label" for="owner_of_part">&nbsp;</label>
                                                            <div class="form-group d-flex">
                                                            <button style="width:100%;height:35px;" type="button" class="infofromlist">View Details</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row sinfo">
                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="type_of_part">Min Level</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('type_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Min Level')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="owner_of_part">Max Level</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('family_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Max Level')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            &nbsp;
                                                        </div>
                                                    </div>

                                                    <div class="row sinfo">
                                                        <div class="col-md-4">
                                                            <div class="form-gro`up d-flex">
                                                                <label class="control-label" for="type_of_part">Min Order</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('type_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Min Order')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="owner_of_part">Max Order</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('family_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Max Order')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            &nbsp;
                                                        </div>
                                                    </div>

                                                    <div class="row sinfo">
                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="type_of_part">Usage</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('type_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Usage')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="owner_of_part">Lost Sales</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('family_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Lost Sales')); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            &nbsp;
                                                        </div>
                                                    </div>

                                                </section>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6 p-4 ps-md-0">
                                        <section class="bottom-form-section">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="type_of_part">Type of Part<span class="required">*</span>
                                                        </label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('type_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Type of Part')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="owner_of_part">Family Code<span class="required">*</span>
                                                        </label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('family_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Family Code')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="location">Suspending #<span class="required">*</span>
                                                        </label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('suspending', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Suspending')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Supplier Information</legend>
                                            <div class="control-group">
                                                <div class="col-md-8">
                                                    <div class="form-group d-flex">
                                                        <div class="form-input-frame">
                                                            <?php 
                                                            $shelflife = ['N/A'=>'N/A'];
                                                            echo $this->Form->control('supplier', array('options' => $shelflife, 'empty' => '-Supplier-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                    <label class="control-label" for="location">Discount Code
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <div class="col-md-8">
                                                    <div class="form-group d-flex">
                                                        <button style="width:100%;height:35px;" type="button">View Info From List</button>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('discount_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Discount Code')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <section class="bottom-form-section">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="general">General Location</label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter General Location')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="line_code">Line Code</label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('line_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Line Code')); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group d-flex">
                                                        <label class="control-label" for="model">Model</label>
                                                        <div class="form-input-frame">
                                                            <?php echo $this->Form->control('model', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Model')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8" style="padding:0px; !important;">
                                                    <div class="col-md-4">
                                                        <div class="form-group d-flex">
                                                            <label class="control-label" for="weight">Weight</label>
                                                            <div class="form-input-frame">
                                                                <?php echo $this->Form->control('weight', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Weight')); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group d-flex">
                                                            <label class="control-label" for="unit">Unit</label>
                                                            <div class="form-input-frame">
                                                                <?php 
                                                                $shelflife = ['N/A'=>'N/A'];
                                                                echo $this->Form->control('unit', array('options' => $shelflife, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="" style="clear:both; padding:0px; !important;">
                                                        <div class="col-md-8">
                                                            <div class="form-group d-flex">
                                                                <label class="control-label" for="po_qty_label">P/O Qty/Label</label>
                                                                <div class="form-input-frame">
                                                                    <?php echo $this->Form->control('po_qty_label', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter P/O Qty/Label')); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                        Consumable
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" >
                                                        <label class="form-check-label" for="flexCheckChecked">
                                                        Hazardous Part
                                                        </label>
                                                    </div>   
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" >
                                                        <label class="form-check-label" for="flexCheckChecked">
                                                        Has Shelf Life
                                                        </label>
                                                    </div> 
                                                </div> 
                                            </div>
                                        </section>

                                    </div>
                                </div>
                            </div><!-- general-tab-section end -->

                            <div id="itemsPricing" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Pricing Block</p>
                                </div>
                            </div>

                            <div id="itemsCores" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Cores Block</p>
                                </div>
                            </div>

                            <div id="itemsAlts" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Alt#s Block</p>
                                </div>
                            </div>

                            <div id="itemsUsage" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Usage Block</p>
                                </div>
                            </div>

                            <div id="itemsHistory" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>History Block</p>
                                </div>
                            </div>

                            <div id="itemsPhotos" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Photo Block</p>
                                </div>
                            </div>

                            <div id="itemsFiles" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Files Block</p>
                                </div>
                            </div>

                            <div id="itemsNotes" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <p>Notes Block</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<!-- popup -->
<div id="partsAddModel" class="modal fade page-content center" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Details</h5>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <section class="bottom-form-section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group d-flex">
                                <label class="control-label" for="general">Part Number</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Part Number')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th scope="col">Owner</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">PO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td scope="row">1</td>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <button class="btn" type="button">Add</button>
                                <button class="btn" type="button">Remove</button>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 row">
                                    <label class="col-sm-8 col-form-label" for="unit">Total Qty of Parts (STOCK):</label>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0')); ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-8 col-form-label" for="general">Total Qty of Parts (Customers):</label>
                                    <div class="col-sm-4">
                                        <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- popup -->

<?php echo $this->Html->script('parts'); ?>
<script> 
var getComponents = "<?php echo Router::url(['controller'=>'AirframeComponents', 'action'=>'getComponents']); ?>";   

$(document).ready(function() {
    $('#frmParts').validate({ // initialize the plugin
        rules: {
            part_number: {
                required: true,
            },
            serial_number: {
                required: true,
            },
            description: {
                required: true,
            },
            part_classification: {
                required: true,
            },
            lot_number: {
                required: true,
            }
        }
    });

    $("input[type='text'], textarea, select").on("keyup change", function(){
        var errors = 0;
        $("input[type='text'], textarea, select").map(function(){
            if( !$(this).val() ) {
                errors++;
            } 
        });
        if(errors > 0){
            $("button[type='submit']").attr("disabled", "disabled");
        }else{
            $("button[type='submit']").removeAttr("disabled");
        }
    });

    $(".infofromlist").click(function(){
        $("#partsAddModel").modal('show');
    })
});    
</script>