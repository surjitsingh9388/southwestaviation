<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">Department/Job Title</h2>
            <div class="float-right mb-5">
                <button type="button" class="btn btn-default add_department_btn">Add Department/Job Title</button>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="table-responsive">
                <table class="table mb-0" id="user_departments_table">
                    <thead>
                        <tr>
                            <th id="user_department_td">Department/Job Title</th>
                            <th id="user_dept_added_by_td">Added By</th>
                            <th id="user_dept_created_at">Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user_departments_list">
                        <?php
                        foreach($userdepartmentlist as $department){
                        ?>
                        <tr>
                            <td><?php echo $department['department_name']; ?></td>
                            <td><?php echo $department['users']['full_name']; ?></td>
                            <td><?php echo date('m/d/Y', strtotime($department['created_at'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-default user_department_edit" data-val="<?=$department['id']?>" title="Edit">Edit</button>
                                <button type="button" class="btn btn-default user_department_delete" data-val="<?=$department['id']?>" title="Delete">Delete</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
echo $this->element('UserDepartments/create_department');
echo $this->Html->script('user_departments'); 
?>
<script type="text/javascript">
    var saveUserDepartmentsURL = "<?php echo $this->Url->build(['controller'=>'UserDepartments', 'action'=>'saveUserDepartments']); ?>";
    var deleteUserDepartmentsURL = "<?php echo $this->Url->build(['controller'=>'UserDepartments', 'action'=>'deleteUserDepartments',]); ?>";
</script>