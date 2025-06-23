<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>
<script src=
    "//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js">
    </script>
<script type="text/javascript">
    
    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable({
            'columnDefs': [
                { 'orderable': false, 'targets': '_all' },
                { className: "check noExl", "targets": [ 0 ] }
            ],
            'order': [0, 'asc'],
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

        $('.chkBoxCls').change(function() {
            alert("hello");
            /*var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
            var checkedcount = $('input.chkBoxCls:checked').length;
            alert(checkedcount);
            if(totalCheckboxes == checkedcount) {
                $('#ckbCheckAll').prop('checked', true);
            } else {
                $('#ckbCheckAll').prop('checked', false);
            }*/
        });

        $('#searchItem, #searchBy').bind("keyup change", function(){
            //dataTable.search($(this).val()).draw();
            if(/*$("#searchItem").val() != '' && */$("#searchBy").val() != ''){
                dataTable.columns(0).search($("#searchItem").val()).columns(1).search($("#searchBy").val()).draw();
            }else{
                dataTable.columns(0).search($("#searchItem").val()).columns(1).search($("#searchBy").val()).draw();
            }
            
        });

        $('#actionSel').bind("change", function(){
            var checkboxarr = [];
            $("input:checkbox[name=childcheckbox]:checked").each(function(){
                checkboxarr.push($(this).val());
            });
            if($(this).val() == 'Edit'){
                if(checkboxarr.length > 0){
                    var ids = checkboxarr[0];
                    window.location = "<?php echo $this->Url->build(['controller'=>'Parts', 'action'=>'edit']); ?>"+'/'+ids;
                }else{
                    $('#actionSel').val('');
                    alert("Please select a field which you want to edit");
                }
            }else if($(this).val() == 'Export'){
                $("#datatable").table2excel({
                    name: "Backup file for HTML content",
                    filename: "parts-report.xls",
                    exclude: ".noExl", 
                    preserveColors: false 
                });
            }else if($(this).val() == 'Bulk Add'){
                $("#partsModel").modal('show');
            }else if($(this).val() == 'Remove'){
                if(checkboxarr.length > 0){
                    if(confirm("Are you sure you want to delete")){
                        var url = "<?php echo $this->Url->build(['controller'=>'Parts', 'action'=>'delete']); ?>";
                        var ids = checkboxarr.join(',');
                        $("#actionForm").attr("action",url);
                        $("#actionForm").append("<input type='hidden' name='id' value='"+ids+"'/>");
                        $("#actionForm").submit();
                    }
                    return false; 
                }else{
                    $('#actionSel').val('');
                    alert("Please select a field which you want to delete");
                }
            }
            //console.log(checkboxarr);
        });

        $('#FilterBy').on('change', function(){
            dataTable.order([$(this).val(), 'asc']).draw();
        }) 

        $('#btn_upload').click(function(){

            var fd = new FormData();
            var files = $('#file_name')[0].files[0];
            fd.append('file_name',files);

            // AJAX request
            $.ajax({
                url: "<?php echo $this->Url->build(['controller'=>'Parts', 'action'=>'bulkadd']); ?>",
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        //$('.displayGroup').html(obj.data);
                        $('#errorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        location.reload(true);
                    } else {
                        $('#errorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }
                }
            });
        });

    } );

    
</script>
<style>
    .dataTables_filter {
        display: none;
    }

    #searchItem{
        border-radius: 5px;
    }

    table.dataTable>thead .sorting::before,
    table.dataTable>thead .sorting_asc::before,
    table.dataTable>thead .sorting_desc::before,
    table.dataTable>thead .sorting_asc_disabled::before,
    table.dataTable>thead .sorting_desc_disabled::before {
        right: 0;
        content: "";
    }
 
    table.dataTable>thead .sorting::after,
    table.dataTable>thead .sorting_asc::after,
    table.dataTable>thead .sorting_desc::after,
    table.dataTable>thead .sorting_asc_disabled::after,
    table.dataTable>thead .sorting_desc_disabled::after {
        right: 0;
        content: "";
    }
 
    table.dataTable>thead>tr>th:not(.sorting_disabled),
    table.dataTable>thead>tr>td:not(.sorting_disabled) {
        padding-right: 4px;
        padding-left: 4px;
    }
    
    table.dataTable>thead>tr>th,
    table.dataTable>thead>tr>td {
        padding-right: 4px;
        padding-left: 4px;
    }

    .actionWrap .dropdown-toggle {
        width:100px !important;
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
                        <option>All</option>
                        <option>SKU #</option>
                        <option>Part #</option>
                        <option>Description</option>
                        <option>Tail #</option>
                        <option>Origin</option>
                    </select>
                </div>
                <div class="inputWrap btn-group sortWrap" style="margin-left:10px;">
                    <select class="selectpicker" id="FilterBy">
                        <option value="">-Filter By-</option>
                        <option value="1">SKU #</option>
                        <option value="2">Part #</option>
                        <option value="3">Description</option>
                        <option>Tail #</option>
                        <option value="4">Origin</option>
                    </select>
                </div>

                <div class="inputWrap btn-group sortWrap actionWrap" style="margin-left:10px;">
                    <select class="selectpicker" id="actionSel">
                        <option value="">Action</option>
                        <?php
                        if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                        ?>
                        <option>Edit</option>
                        <?php } ?>
                        <option>Export</option>
                        <?php
                        if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                        ?>
                        <option>Bulk Add</option>
                        <?php } if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1) { ?>
                        <option>Remove</option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatable" class="table dataTable table2excel" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
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

<div id="partsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span> Bulk Add</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <span id="errorMsg"></span>
                <form method='post' action='' enctype="multipart/form-data">
                    Select file : <input type='file' name='file_name' id='file_name' class='form-control' ><br>
                    <input type='button' class='btn btn-info' value='Upload' id='btn_upload'>
                </form>
            </div>
            <div class="modal-footer">
                
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>