<div id="linkWithAnotherOrderModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Link to Another Order</h4>
            </div>
            <div class="modal-body" style="max-height: 500px;">
                <form class="form-horizontal ng-pristine ng-valid" role="form" id="linkAnotherOrderModalForm" name="linkAnotherOrderModalForm">
                <div class="page-content">
                    <div class="link-order-text">
                        <lable>Selected entries will be linked to new or existing order.</lable>
                    </div>

                    <div class="row mt5">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:5px;">
                                <?php 
                                $linkedordertypearr = array(
                                                                'purchase'=>'Purchase Order',
                                                                'request'=>'Request',
                                                                'shipping'=>'Shipping Order',
                                                                'repair'=>'Repair Order',
                                                            );
                                echo $this->Form->control('linked_order_type', array('options' => $linkedordertypearr, 'empty' => 'Linked Order Type ...', 'class' => 'form-control col-md-12 col-xs-12 selectpicker link-to-new-order', 'data-live-search' => true, 'label' => false, 'id' => 'linked_order_type')); 
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt5">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input link-to-new-order" type="radio" name="ordertype" id="ordertype1" value="new-order" checked>&nbsp;Create New Order
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input link-to-new-order" type="radio" name="ordertype" id="ordertype2" value="existing-order">&nbsp;Add to Existing Order
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="control-label"></div>
                        <div class="form-group ordertypedropdown">
                            
                        </div>  
                    </div>
                    
                    <div class="row mt5"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary invlinkorderbtn">Link</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>