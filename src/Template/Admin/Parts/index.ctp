<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
    
    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable({
            "bSort": false,
            //"searching": false,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
            "lengthChange": false,
            "ajax":{
                url:"<?php echo $this->Url->build(['controller'=>'Parts', 'action'=>'ajaxManagePartsSearch']); ?>",
                accepts: 'application/json', 
                type: "post",
                error: function(){
                    $(".employees-grid-error").html("");
                    $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employees-grid_processing").css("display","none");
                }
            }
        });

        $("#ckbCheckAll").click(function () {
            $(".chkBoxCls").prop('checked', $(this).prop('checked'));
        });

        $(".chkBoxCls").click(function () {
            alert("hello");
            var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
            var checkedcount = $('input.chkBoxCls:checked').length;
            alert(checkedcount);
            if(totalCheckboxes == checkedcount) {
                $('#ckbCheckAll').prop('checked', true);
            } else {
                $('#ckbCheckAll').prop('checked', false);
            }
        });

        $('#searchItem,#searchBy').bind("keyup change", function(){
            dataTable.search($(this).val()).draw();
        });

        $('#actionSel').bind("change", function(){
            if($(this).val() == 'Edit'){
                var ids = $('input[name="childcheckbox"]:checked').val();
                
                window.location = "<?php echo $this->Url->build(['controller'=>'Parts', 'action'=>'edit']); ?>"+'/'+ids;
            }
        });
        
    } );

    
</script>
<style>
    .dataTables_filter {
        display: none;
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Master Parts List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Create Item", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" type="text" class="form-control" placeholder="Search Part List">
                    <!--div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div-->
                </div>
                <div class="inputWrap btn-group sortWrap">
                    <select class="selectpicker" id="searchBy">
                        <option value="">-Search By-</option>
                        <option>Status</option>
                    </select>
                </div>
                <div class="inputWrap btn-group sortWrap" style="margin-left:10px;">
                    <select class="selectpicker" id="FilterBy">
                        <option value="">-Filter By-</option>
                        <option>Status</option>
                    </select>
                </div>

                <div class="inputWrap btn-group sortWrap" style="margin-left:10px;">
                    <select class="selectpicker" id="actionSel">
                        <option value="">-Action-</option>
                        <option>Edit</option>
                        <option>Delete</option>
                    </select>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Serial Number'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Description'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('SKU#'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('LOT#'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Origin'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Classification'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('IN Stock QTY'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>