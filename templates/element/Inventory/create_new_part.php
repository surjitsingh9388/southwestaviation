<?php echo $this->Html->script('jquery.tokeninput'); ?>
<?php echo $this->Html->css('token-input-facebook.css'); ?>
<?php
$alterpartnumber = [];
if(!empty($invenotryitems->alternate_part_number)){
    $alterpartnumberarr = explode(',', $invenotryitems->alternate_part_number);
    foreach($alterpartnumberarr as $altpartnumber){
        $alterpartnumber[] = ['id'=>$altpartnumber, 'name'=>$altpartnumber];
    }
}

$tagsdata = [];
if(!empty($invenotryitems->tags)){
    $tagsarr = explode(',', $invenotryitems->tags);
    foreach($tagsarr as $tag){
        $tagsdata[] = ['id'=>$tag, 'name'=>$tag];
    }
}

?>
<script>
$(function() {
  
    var newToken;
    
    $('#alternate-part-number').tokenInput([], {
        theme: "facebook",
        hintText: "",
        noResultsText: "Enter alternate part numbers...",
        searchingText: "",
        preventDuplicates: true,
        prePopulate:<?php echo json_encode($alterpartnumber); ?>,
        onAdd: function(item){
          newToken = null;
        },
        onReady: function(){          
          $("#token-input-alternate-part-number").keyup(function(event) {
            
            if (event.keyCode === 13 || event.keyCode === 9 || event.keyCode === 188) // return, tab, or comma
            {
              if (newToken) $('#alternate-part-number').tokenInput("add", {id: newToken, name: newToken});
            }
            
            newToken = $("tester").text();
          });
        }
    }); 

    $('#tags').tokenInput([], {
        theme: "facebook",
        hintText: "Enter a tag...",
        noResultsText: "",
        searchingText: "",
        preventDuplicates: true,
        prePopulate:<?php echo json_encode($tagsdata); ?>,
        onAdd: function(item){
          newToken = null;
        },
        onReady: function(){          
          $("#token-input-tags").keyup(function(event) {
            
            if (event.keyCode === 13 || event.keyCode === 9 || event.keyCode === 188) // return, tab, or comma
            {
              if (newToken) $('#tags').tokenInput("add", {id: newToken, name: newToken});
            }
            
            newToken = $("tester").text();
          });
        }
    }); 
});

</script>

<div class="addPartBorder">
    <div class="invaddPageHeading">
        <?php 
        if(isset($isdetailpage)){
            echo 'General Information';
        }else{
            echo 'Primary Information';
        } ?>
    </div>
    <div class="row mt10">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name:&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('name', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Part Number:&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php echo $this->Form->control('part_number', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Is this item serialized?:&nbsp;<span class="required">*</span>
                <i class="fa fa-info-circle" data-toggle="tooltip" title="If items are distinguished from one another by a serial number, select yes."></i>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('is_this_item_serialized',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                        ]); ?>
                        <!--input class="form-check-input" type="radio" name="is_this_item_serialized" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="is_this_item_serialized" id="inlineRadio2" value="0">
                        <label class="form-check-label" for="inlineRadio2">No</label-->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Accepts Install?: <i class="fa fa-info-circle" data-toggle="tooltip" title="Turning this on allows inventory to be installed to items of this type."></i></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('accept_install',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                        ]); ?>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="item_type">Item Type:
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-html="true" rel="tooltip"  title="<div class=&quot;text-left item-type-options-tooltip&quot;>
                        Item Type Options: <br><br>
                        Consumable - Once used, cannot be used again.<br><br>
                        Expendable - Discarded at the end of it's lifecycle and cannot be overhauled.<br><br>
                        Rotable - Repaired or restored as new or overhauled until it is no longer repairable. <br><br>
                        Tool - Device used to complete a task.
                    </div>"></i>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                    echo $this->Form->control('item_type', array('options' => $invItemType, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'item_type')); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <!--div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Capital Equipment?<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('capital_equipment',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                        ]); ?>
                        
                    </div>
                </div>
            </div-->
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Safety Stock Threshold:&nbsp;   <span class="required">*</span>
                    <i class="fa fa-info-circle" data-toggle="tooltip" title="A global minimum threshold level"></i>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('safety_stock_threshold', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '0.00', 'label' => false)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Default UOM:&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $defaultUOM = unserialize(DEFAULT_UOM);
                    echo $this->Form->control('default_uom', array('options' => $defaultUOM, 'empty' => 'Enter a amount of measure ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'default_uom')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Out Right Cost:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $unit_cost = '';
                    if(!empty($invenotryitems->unit_cost)){
                        $unit_cost = '$'.$invenotryitems->unit_cost;
                    }
                    echo $this->Form->control('unit_cost', array('type'=>'text','class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$unit_cost)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group" style="display:none;">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Currency:&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $currency = unserialize(CURRENCY);
                    echo $this->Form->control('currency', array('options' => $currency, 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'currency', 'value'=>'1')); 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Core Charge:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $exchange_cost = '';
                    if(!empty($invenotryitems->exchange_cost)){
                        $exchange_cost = '$'.$invenotryitems->exchange_cost;
                    }
                    echo $this->Form->control('exchange_cost', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$exchange_cost)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Rev:</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php echo $this->Form->control('rev', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Manufacturer:</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('manufacturer_id', array('options' => $manufacturer, 'empty' => 'Enter a manufacturer...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'manufacturer')); 
                    
                    if(!isset($isdetailpage) && !isset($isinvrequestpage)){
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn"><button class="btn btn-primary manufacturerbtn plus-btn-h" type="button"><i class="fa fa-plus"></i></button></div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Weight:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('weight', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => 'Enter weight', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Exchange Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $exchange_price = '';
                    if(!empty($invenotryitems->exchange_price)){
                        $exchange_price = '$'.$invenotryitems->exchange_price;
                    }

                    echo $this->Form->control('exchange_price', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$exchange_price)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Retail Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $retail_price = '';
                    if(!empty($invenotryitems->retail_price)){
                        $retail_price = '$'.$invenotryitems->retail_price;
                    }
                    echo $this->Form->control('retail_price', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$retail_price)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Company Purchase Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $company_purchase_price = '';
                    if(!empty($invenotryitems->company_purchase_price)){
                        $company_purchase_price = '$'.$invenotryitems->company_purchase_price;
                    }
                    echo $this->Form->control('company_purchase_price', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$company_purchase_price)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Overhauled Cost:</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $overhauled_cost = '';
                    if(!empty($invenotryitems->overhauled_cost)){
                        $overhauled_cost = '$'.$invenotryitems->overhauled_cost;
                    }
                    echo $this->Form->control('overhauled_cost', array('type'=>'text', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '$0.00', 'label' => false, 'value'=>$overhauled_cost)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Description:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('description', array('class' => 'form-control col-md-8 col-xs-12', 'label'=> false, 'row mt5s'=>2)); ?>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Notes:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-8 col-xs-12', 'label'=> false, 'row mt5s'=>2)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Tags:
                    <i class="fa fa-info-circle" data-toggle="tooltip" title="Tags should be comma(,) seperated"></i>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('tags', array('class'=>'form-control col-md-8 col-xs-12 label-width-auto', 'placeholder' => '', 'label' => false)); ?>
                    <?php if(!isset($isdetailpage)){ ?>
                    <span>(Hit enter to add tags)</span>
                    <?php } ?>
                </div>
                
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Alternate Part Numbers:</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php echo $this->Form->control('alternate_part_number', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                    <?php if(!isset($isdetailpage)){ ?>
                    <span>(Hit enter to add alternate PN)</span>
                    <?php } ?>
                </div>
                
            </div>
        </div>
    </div>
</div>