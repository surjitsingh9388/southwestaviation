<tr>
    <td>
        <?php 
        $invroitemid = isset($invreqitem->id) ? $invreqitem->id : 0;
        
        if($invreqitem->status != '4'){ ?>
        <a href="javascript:void(0);" style="color: darkred;" class="remove_inventory_item"><i class="fa fa-times fa-2x"></i></a>
        <?php }if(isset($invreqitem->id)){ ?>
        <input type="hidden" name="itemid[]" value="<?php echo $invreqitem->id; ?>">
        <?php } ?>
    </td>
    <td class="invpocount"><?php echo isset($srcount) ? $srcount : ''; ?></td>
    <td>
        <?php
        if($InventoryShippingOrders->shipping_order_status == '4'){
            if(isset($invreqitem['invitms']['name']) || (isset($invtype) && $invtype == 'invitem')){
                $inventoryitemdropdown = '<div class="input-group" style="margin-bottom:0px !important;">';
                foreach($inventoryitems as $val){
                    $selected = '';
                    if($invreqitem->inventory_id == $val->id){
                        $inventoryitemdropdown .= $val['invitm']['name'].' ('.$val['invitm']['part_number'].') (SN: '.$val->serial_no.')';break;
                    }
                }
                $inventoryitemdropdown .= '<input type="hidden"  name="noninventory_item[]">';
            }else{
                $noninventoryitem = isset($invreqitem['noninventory_item']) ? $invreqitem['noninventory_item'] : '';
                $inventoryitemdropdown = '<div class="form-group">'.$noninventoryitem;
                $inventoryitemdropdown .= '<input type="hidden"  name="inventory_id[]">';
            } 
              
        }else{
            if(isset($invreqitem['invitms']['name']) || (isset($invtype) && $invtype == 'invitem')){
                $inventoryitemdropdown = '<div class="input-group" style="margin-bottom:0px !important;"><select name="inventory_id[]" class="form-control col-md-8 col-xs-12 selectpicker inventory_id" aria-label="Default select example">
                <option selected>Select a part number</option>';
                foreach($inventoryitems as $val){
                    $selected = '';
                    if(isset($invreqitem->inventory_id) && $invreqitem->inventory_id == $val->id){
                        $selected = 'selected';
                    }else if(isset($invid) && $invid == $val->id){
                        $selected = 'selected';
                    }
                    $inventoryitemdropdown .= '<option value="'.$val->id.'" '.$selected.'>'.$val['invitm']['name'].' ('.$val['invitm']['part_number'].') (SN: '.$val->serial_no.')</option>';
                }
                $inventoryitemdropdown .= '</select>';
                $inventoryitemdropdown .= '<input type="hidden"  name="noninventory_item[]"><span class="input-group-btn"><button class="btn btn-primary addnewinvitempopup" type="button" tabindex="-1"><i class="fa fa-plus"></i></button></span>';
            }else{
                $noninventoryitem = isset($invreqitem['noninventory_item']) ? $invreqitem['noninventory_item'] : '';
                $inventoryitemdropdown = '<div class="form-group"><input type="text"  name="noninventory_item[]" class="form-control col-md-8 col-xs-12" placeholder="" maxlength="100" value="'.$noninventoryitem.'">';
                $inventoryitemdropdown .= '<input type="hidden"  name="inventory_id[]">';
            } 
        }
        echo $inventoryitemdropdown;  
        ?>
        
        </div>
    </td>
    <td>
        <?php $qty = isset($invreqitem->qty) ? $invreqitem->qty : 1;?>
        <input type="number" name="qty[]" class="form-control col-md-8 col-xs-12 po_qty" placeholder="" required="required" maxlength="100" id="qty" value="<?php echo $qty; ?>">
    </td>
    <td>
        <?php $cost = isset($invreqitem->cost) ? $invreqitem->cost : 0;?>
        <input type="number" name="cost[]" class="form-control col-md-8 col-xs-12 po_cost" placeholder="" maxlength="100" id="cost" value="<?php echo $cost; ?>">
    </td>
    <td>
        <?php 
        $totalcost = $qty*$cost;
        $totalcost = !empty($totalcost) ? $totalcost : '0.00';
        $currencyarr = unserialize(CURRENCY);
        $pocurrency = isset($inventorypurchaseorders['currency']) ? $currencyarr[$inventorypurchaseorders['currency']] : 'USD';
        ?>
        <span class="porowtotal"><?php echo $totalcost; ?></span>&nbsp;<span class="pocurrency"><?php echo $pocurrency; ?></span>
    </td>
    
</tr>