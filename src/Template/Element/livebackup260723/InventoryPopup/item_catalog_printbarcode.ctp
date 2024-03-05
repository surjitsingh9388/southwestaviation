<div id="printCatalogBarcodesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Print Barcode</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Print Type:</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-check form-check-inline col-md-3 col-xs-12">
                                        <input class="form-check-input catalogprinttyperedio" name="catalogprinttype" type="radio" id="catalogprinttype1" value="1" checked>&nbsp;Part Level
                                    </div>
                                    <div class="form-check form-check-inline col-md-5 col-xs-12">
                                        <input class="form-check-input catalogprinttyperedio" name="catalogprinttype" type="radio" id="catalogprinttype2" value="2">&nbsp;Serial/Lot # Specific
                                    </div>
                                    <div class="col-md-4 col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row catalogprintsize" style="display:none;">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Print Size: </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input" type="radio" name="catalogprintsize" id="catalogprintsize1" value="1" checked>&nbsp;2 inch x 1 inch
                                    </div>
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input" type="radio" name="catalogprintsize" id="catalogprintsize2" value="2">&nbsp;4 inch x 2 inch
                                    </div>
                                    <div class="col-md-4 col-xs-12"></div>    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt5"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary printInventoryCatalogBarcodes">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>