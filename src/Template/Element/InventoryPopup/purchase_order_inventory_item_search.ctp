<div id="searchInventoryItemModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Inventory Item Search</h4>
            </div>
            <div class="modal-body">
                <div class="mt5">
                    <div class="col-sm-12 mb-10">
                        <input type="text" id="invItmSearchItem" name="search" class="form-control brd-5" placeholder="Search Inventory Item" autocomplete="off">
                        <div class="inventory-item-search-icon">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 inv-itm-search-table table-responsive">
                    <table id="invItemSearchTable" class="table mb-0" width="100%">
                        <thead>
                            <tr>
                                <th id="partNumberId">Part No.<i class="fa fa-fw fa-sort"></th>
                                <th id="partNameId">Name<i class="fa fa-fw fa-sort"></th>
                                <th>Serialized</th>
                                <th id="partTypeId">Type<i class="fa fa-fw fa-sort"></th>
                                <th id="partInStockId">In Stock<i class="fa fa-fw fa-sort"></th>
                            </tr>
                        </thead>
                        <tbody id="invItemSearchList">
                            <?php
                            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
                            foreach($inventoryitems as $key=>$row){
                                $key = $key+1;    
                                $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd';
                            ?>
                            <tr class="mainTR activeTble <?php echo $evenOdd; ?>" data-val="<?php echo $row['id']; ?>">
                                <td class="collapse-tr">
                                    <?php echo $row['part_number']; ?>
                                </td>
                                
                                <td class="collapse-tr">
                                    <?php echo $row['name']; ?>
                                </td>

                                <td class="collapse-tr">
                                    <?php echo $row['is_this_item_serialized'] == 1 ? '<i class="fa fa-check"></i>' : ''; ?>
                                </td>
                                
                                <td class="collapse-tr">
                                    <?php echo !empty($row['item_type']) ? $itemtypearr[$row['item_type']] : ''; ?>
                                </td>

                                <td class="collapse-tr">
                                    <?php echo $row['item_instock']; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary applyCatalogTags">Apply</button-->
            </div>
        </div>
    </div>
</div>