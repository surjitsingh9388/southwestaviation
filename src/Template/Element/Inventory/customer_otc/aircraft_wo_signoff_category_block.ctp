<div class="col-md-12 mt10 pd0">
    <div class="float-left"><span class="wosignoff-color-box1"></span>Atleast one incomplete sign-off</div>
    <div class="float-right"><span class="wosignoff-color-box2"></span>Primary (or RII) sign-off incomplete</div>
</div>
<fieldset class="scheduler-border mt10">
    <legend class="scheduler-border wosignoffleg">Item #<?php echo $wosignoffitems['wo_item_position']; ?></legend>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">Sign-off Categories</div>
            <div class="col-md-8 wosignoffcategory">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Sign-off</th>
                            <th scope="col">Inspected By</th>
                            <th scope="col">Inspect Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $wo_item_id = !empty($wosignoffitems->id) ? $wosignoffitems->id : '';
                        $woItemSignOff = unserialize(WOITEMSIGNOFF);
                        foreach($woItemSignOff as $id =>$signoff){
                            $inspected_by = '';
                            $inspected_date = '';
                            if(!empty($woitemsignoffdet[$id])){
                                $singoffdata = $woitemsignoffdet[$id];

                                $inspected_by = !empty(@$singoffdata['users']['full_name']) ? @$singoffdata['users']['full_name'] : '';
                                $inspected_date = !empty(@$singoffdata['inspected_date']) ? date('m/d/Y', strtotime($singoffdata['inspected_date'])) : '';
                            }
                        ?>
                        <tr class="signoff-categories-tr" wo-item-id="<?php echo $wo_item_id; ?>" signoff-category = "<?php echo $id; ?>">
                            <td><?php echo $signoff; ?></td>
                            <td><?php echo $inspected_by; ?></td>
                            <td><?php echo $inspected_date; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4 signoff-category-insp-info">
                <?php echo $this->element('Inventory/customer_otc/wo_item_signoff_inspection_info', ['wosignoffitems'=>$wosignoffitems, 'wo_item_id'=>$wo_item_id]); ?>
            </div>
        </div>
    </div>
</fieldset>