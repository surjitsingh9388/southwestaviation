<?php
$sessionUser = $this->request->getSession()->read('Auth');

$renderTree = function ($items, $level = 0, $mainId = null) use (&$renderTree) {
    foreach ($items as $item) {
        $perm = $item->technical_publication_permissions[0] ?? null;
        
        echo '<tr data-id="'.$item->id.'" 
                  data-parent="'.($item->parent_id ?? 0).'" 
                  data-main="'.$item->main_page_id.'" 
                  class="'.($item->parent_id ? 'child-row parent-'.$item->parent_id : 'root-row').'">';

        echo '<td style="padding-left:'.($level * 20).'px;">';
        echo '<input type="checkbox" class="row-checkbox" data-id="'.$item->id.'"> ';

        if ($item->is_folder == '1') {
            echo '<span class="toggle-btn" data-id="'.$item->id.'">[+]</span> ';
            echo '<span class="document-management-icon icon-folder"></span> ';
        } else {
            $ext = strtolower(pathinfo($item->folder_file_name, PATHINFO_EXTENSION));
            $iconcss = match ($ext) {
                'pdf' => 'icon-pdf',
                'doc', 'docx' => 'icon-doc',
                'xls', 'xlsx' => 'icon-excel',
                'txt' => 'icon-text',
                default => 'icon-generic',
            };
            echo '<span class="document-management-icon ' . $iconcss . '"></span>&nbsp;';
        }

        echo h($item->folder_file_name) . '</td>';

        echo '<td><input type="checkbox" name="permissions['.$item->id.'][add]" value="1"></td>';
        echo '<td><input type="checkbox" name="permissions['.$item->id.'][edit]" value="1"></td>';
        echo '<td><input type="checkbox" name="permissions['.$item->id.'][view]" value="1"></td>';
        echo '<td><input type="checkbox" name="permissions['.$item->id.'][delete]" value="1"></td>';
        echo '<td><button type="button" class="btn btn-primary view_tech_publication_permission" data-val="'.$item->id.'">View Access</button></td>';
        echo '</tr>';

        if (!empty($item->children)) {
            $renderTree($item->children, $level + 1, $item->main_page_id);
        }
    }
};

?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Technical Publication Permissions</h2>
        </div>

        <div class="page-content mt-35">
            <?php echo $this->Form->create(null, [
                'url' => ['action' => 'savePermissions'],
                'class' => 'form-horizontal form-label-left',
                'id' => 'frmAssignMenu',
                'role' => 'form',
                'data-toggle' => 'validator'
            ]); ?>
            
            <div class="panel panel-default">
                
                <div class="panel-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">User <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <?php echo $this->Form->control('user_id', array('options' => $allAdmins, 'empty' => 'Select User', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'label' => false, 'data-show-subtext' => true, 'data-live-search' => true)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12" style="padding:0px;">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <?php
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'id' => 'frmClicked', 'class' => 'btn btn-success clickedd']);
                                    ?>
                                    <button class="btn btn-success buttonload" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i> <?php echo ucfirst(strtolower(SUBMITING)); ?>
                                    </button>
                                    <?php
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                    echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-heading">
                    <h3 class="panel-title">Permissions List</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group" id="menuItemTable">
                        <?= $this->Form->create(null, ['url' => ['action' => 'savePermissions']]) ?>
                        <table width="100%" class="table table-bordered permission-matrix Panel" border="1">
                            <thead>
                                <tr style="background-color: #f0f0f0;">
                                    <th>Folder/File Name</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>View</th>
                                    <th>Delete</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grouped as $mainId => $tree): ?>
                                    <tr class="main-heading root-row" data-id="main_<?= $mainId ?>" data-main="<?= $mainId ?>" style="background-color:#f0f0f0;">
                                        <td>
                                            <input type="checkbox" class="row-checkbox main-heading-checkbox" data-id="main_<?= $mainId ?>"> 
                                            <strong><?= $mainPageHeadings[$mainId] ?? '' ?></strong>
                                        </td>
                                        <td><input type="checkbox" name="permissions[main_<?= $mainId ?>][add]" value="1"></td>
                                        <td><input type="checkbox" name="permissions[main_<?= $mainId ?>][edit]" value="1"></td>
                                        <td><input type="checkbox" name="permissions[main_<?= $mainId ?>][view]" value="1"></td>
                                        <td><input type="checkbox" name="permissions[main_<?= $mainId ?>][delete]" value="1"></td>
                                    </tr>

                                    <?php $renderTree($tree, 1, $mainId); // ✅ pass mainId ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<!-- User Permissions Modal -->
<div class="modal fade page-content" role="dialog" style="background: transparent;" id="userPermissionModal" style="display:none;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Permissions</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="user_permssion_content_block"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .text-alignment {
        text-align: center;
    }

    .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .help-left-margin {
        margin-left: 10px;
        text-align: justify;
        margin-top: -10px;
    }

    /* .bootstrap-select .dropdown-menu .inner {
        max-height: 200px;
        overflow-y: auto;
    } */

    @media screen and (max-width: 897px) {

        th.equal-width,
        th.nowrap {
            white-space: normal !important;
            font-size: 12px;
            min-width: 60px;
        }
    }

    #userPermissionModal table th,
    #userPermissionModal table td {
        text-align: left;
    }

    /* Hide native checkbox */
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        cursor: default;
    }

    /* Custom checkbox container */
    .custom-checkbox .checkmark {
        display: inline-block;
        width: 20px;
        height: 20px;
        background-color: #e9ecef; /* unchecked color */
        border: 1px solid #adb5bd;
        border-radius: 4px;
        position: relative;
    }

    /* Checked state (blue) */
    .custom-checkbox input:checked + .checkmark {
        background-color: #0d6efd; /* Bootstrap primary blue */
        border-color: #0d6efd;
    }

    /* Tick mark */
    .custom-checkbox input:checked + .checkmark::after {
        content: "";
        position: absolute;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

</style>

<script>
$(document).ready(function(){

    // Collapse children initially
    $("tr.child-row").hide();

    // Expand/Collapse toggle
    $(document).on("click", ".toggle-btn", function(){
        let id = $(this).data("id");
        let btn = $(this);
        if (btn.text() === "[+]") {
            btn.text("[-]");
            $("tr.parent-" + id).show();
        } else {
            btn.text("[+]");
            hideChildren(id);
        }
    });

    function hideChildren(parentId) {
        $("tr.parent-" + parentId).each(function(){
            let childId = $(this).data("id");
            $(this).hide();
            $(this).find(".toggle-btn").text("[+]");
            hideChildren(childId);
        });
    }

    // ✅ Cascade child checkboxes when row-checkbox clicked
    $(document).on("change", ".row-checkbox", function(){
        let row = $(this).closest("tr");
        let id = $(this).data("id");
        let checked = $(this).is(":checked");

        // tick/un-tick full row
        row.find("input[type=checkbox]").prop("checked", checked);

        // cascade to children
        cascadeChildren(id, checked);

        // update main heading state
        updateMainHeading(row.data("main"));
    });

    function cascadeChildren(parentId, checked) {
        $("tr[data-parent='" + parentId + "']").each(function(){
            $(this).find("input[type=checkbox]").prop("checked", checked);
            let childId = $(this).data("id");
            cascadeChildren(childId, checked);
        });
    }

    $(document).on("change", "tr.main-heading input[type=checkbox]", function(){
        let row = $(this).closest("tr");
        let mainId = row.data("main");
        let colIndex = $(this).closest("td").index();
        let checked = $(this).is(":checked") === false ? true : false;

        // apply to all child rows
        $("tr[data-main='" + mainId + "']").each(function(){
            $(this).find("td:eq(" + colIndex + ") input[type=checkbox]").prop("checked", checked);
        });

        // if this is the row-checkbox in first column → cascade all
        if ($(this).hasClass("row-checkbox")) {
            $("tr[data-main='" + mainId + "']").each(function(){
                $(this).find("input[type=checkbox]").prop("checked", checked);
            });
        }
    });

    // ✅ Column-level cascade (Add/Edit/View/Delete individually)
    $(document).on("change", "tr.main-heading td input[type=checkbox]:not(.row-checkbox)", function(){
        let row = $(this).closest("tr");
        let mainId = row.data("main");
        let colIndex = $(this).closest("td").index();
        let checked = $(this).is(":checked");

        $("tr[data-main='" + mainId + "']").each(function(){
            $(this).find("td:eq(" + colIndex + ") input[type=checkbox]").prop("checked", checked);
        });
    });

    // ✅ Update main heading state based on children
    function updateMainHeading(mainId){
        let mainRow = $("tr.main-heading[data-main='" + mainId + "']");

        // 1st column (row-checkbox)
        let allRows = $("tr[data-main='" + mainId + "']").not(mainRow);
        let allChecked = true;
        allRows.each(function(){
            if (!$(this).find(".row-checkbox").is(":checked")) {
                allChecked = false;
                return false;
            }
        });
        mainRow.find(".row-checkbox").prop("checked", allChecked);

        // Loop each permission column (Add/Edit/View/Delete)
        mainRow.find("td:gt(0)").each(function(index){
            let colIndex = $(this).index();
            let colAllChecked = true;
            allRows.each(function(){
                if (!$(this).find("td:eq(" + colIndex + ") input[type=checkbox]").is(":checked")) {
                    colAllChecked = false;
                    return false;
                }
            });
            $(this).find("input[type=checkbox]").prop("checked", colAllChecked);
        });
    }

    // ✅ When child column checkbox changes → recheck main heading
    $(document).on("change", "tbody tr:not(.main-heading) input[type=checkbox]", function(){
        let row = $(this).closest("tr");
        let mainId = row.data("main");
        updateMainHeading(mainId);
    });

    // ✅ Also run after loading permissions via AJAX
    function refreshAllMainHeadings(){
        $("tr.main-heading").each(function(){
            updateMainHeading($(this).data("main"));
        });
    }

    // Example: after AJAX load
    $("#user-id").on("change", function(){
        let userId = $(this).val();
        if (!userId){
            $(".permission-matrix input[type=checkbox]").prop("checked", false);
            return;
        } 

        $(".permission-matrix input[type=checkbox]").prop("checked", false);

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'TechnicalPublicationPermissions', 'action' => 'getUserPermissions']) ?>/" + userId,
            type: "GET",
            success: function(response){
                if(response.permissions){
                    for (let mainId in response.permissions) {
                        for (let pubId in response.permissions[mainId]) {
                            let perm = response.permissions[mainId][pubId];
                            $("tr[data-id='"+pubId+"'] .row-checkbox").prop("checked", true);
                            if (perm.add)    $("input[name='permissions["+pubId+"][add]']").prop("checked", true);
                            if (perm.edit)   $("input[name='permissions["+pubId+"][edit]']").prop("checked", true);
                            if (perm.view)   $("input[name='permissions["+pubId+"][view]']").prop("checked", true);
                            if (perm.delete) $("input[name='permissions["+pubId+"][delete]']").prop("checked", true);
                            expandParents(pubId);
                        }
                    }
                    refreshAllMainHeadings(); // ✅ sync states
                }
            }
        });
    });

    function expandParents(childId){
        let row = $("tr[data-id='"+childId+"']");
        let parentId = row.data("parent");
        if(parentId && parentId !== 0){
            $("tr[data-id='"+parentId+"']").show();
            $("tr[data-id='"+parentId+"'] .toggle-btn").text("[-]");
            $("tr.parent-"+parentId).show();
            expandParents(parentId);
        }
    }

});

$(document).on('click', '.view_tech_publication_permission', function(e){
    var technical_publication_id = $(this).attr('data-val');
    if($.trim(technical_publication_id) != ''){
        $('.loader').show();
        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'TechnicalPublicationPermissions', 'action' => 'getPermissionByTechPublId']) ?>",
            type: "POST",
            data:{'technical_publication_id':technical_publication_id},
            success: function(response){
                $('.loader').hide();
                if(response != 'Failed'){
                    $('#user_permssion_content_block').html(response);
                    $('#userPermissionModal').modal('show');
                }else{
                    alert("Something went wrong.");
                }
            }
        }); 
    }
});
</script>

