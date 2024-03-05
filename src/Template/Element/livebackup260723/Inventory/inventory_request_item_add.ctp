<tr>
    <td>
        <a href="javascript:void(0);" style="color: darkred;" class="remove_inventory_item"><i class="fa fa-times fa-2x"></i></a>
        <?php if(isset($invreqitem->id)){ ?>
        <input type="hidden" name="itemid[]" value="<?php echo $invreqitem->id; ?>">
        <?php } ?>
    </td>
    <td>
    
    <?php
    if(isset($invreqitem['invitms']['name']) || (isset($invtype) && $invtype == 'invitem')){
        $inventoryitemdropdown = '<div class="input-group" style="margin-bottom:0px !important;"><select name="inventory_item_id[]" class="form-control col-md-8 col-xs-12 selectpicker inventory_item_id" aria-label="Default select example">
        <option selected>Select a part number</option>';
        foreach($inventoryitems as $val){
            $selected = '';
            if(isset($invreqitem->inventory_item_id) && $invreqitem->inventory_item_id == $val->id){
                $selected = 'selected';
            }
            $inventoryitemarr[$val->id] = $val->name.' ('.$val->part_number.')';
            $inventoryitemdropdown .= '<option value="'.$val->id.'" '.$selected.'>'.$val->name.' ('.$val->part_number.')'.'</option>';
        }
        $inventoryitemdropdown .= '</select>';
        $inventoryitemdropdown .= '<input type="hidden"  name="noninventory_item[]"><span class="input-group-btn"><button class="btn btn-primary addnewinvitempopup" type="button" tabindex="-1"><i class="fa fa-plus"></i></button></span>';
    }else{
        $noninventoryitem = isset($invreqitem['noninventory_item']) ? $invreqitem['noninventory_item'] : '';
        $inventoryitemdropdown = '<div class="form-group"><input type="text"  name="noninventory_item[]" class="form-control col-md-8 col-xs-12" placeholder="" maxlength="100" value="'.$noninventoryitem.'">';
        $inventoryitemdropdown .= '<input type="hidden"  name="inventory_item_id[]">';
    } 
    echo $inventoryitemdropdown; ?>
    
    </div>
    </td>
    <?php $qty = isset($invreqitem->qty) ? $invreqitem->qty : 1;?>
    <td><input type="number" name="qty[]" class="form-control col-md-8 col-xs-12" placeholder="" required="required" maxlength="100" id="requested-by" value="<?php echo $qty; ?>"></td>
    <td>
        <?php
        $defaultUOM = unserialize(DEFAULT_UOM);
        $uomdropdown = '<select name="uom[]" class="form-control col-md-8 col-xs-12 selectpicker" aria-label="Default select example" required="required">
        <option selected>Select a unit of measure</option>';
        foreach($defaultUOM as $key=>$val){
            $uomdropdown .= '<option value="'.$key.'" selected>'.$val.'</option>';
        }
        $uomdropdown .= '</select>';
        echo $uomdropdown;
        ?>
    </td>
    <td>
        <?php
        $locationdropdown = '<select name="location_id[]" class="form-control col-md-8 col-xs-12 selectpicker" aria-label="Default select example">
        <option selected>Select a location</option>';
        foreach($location as $key=>$val){
            $selected = '';
            if(isset($invreqitem->location_id) && $invreqitem->location_id == $key){
                $selected = 'selected';
            }
            $locationdropdown .= '<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
        }
        $locationdropdown .= '</select>';
        echo $locationdropdown;
        ?>
    </td>
</tr>