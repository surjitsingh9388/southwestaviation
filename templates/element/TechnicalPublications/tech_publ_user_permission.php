<?php if (!empty($permissions)) { ?>
<table class="table table-bordered align-middle text-center" id="permissionsTable">
    <thead class="table-light">
        <tr>
            <th>User Name</th>
            <th>Add</th>
            <th>Edit</th>
            <th>View</th>
            <th>Delete</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $perm): ?>
        <tr data-id="<?= $perm['id']; ?>">
            <td><?= h($perm['user']['full_name']); ?></td>

            <?php 
            $fields = ['add' => 'action_add', 'edit' => 'action_edit', 'view' => 'action_view', 'delete' => 'action_delete'];
            foreach ($fields as $label => $field): 
                $checked = !empty($perm[$field]) ? 'checked' : '';
            ?>
            <td class="text-center">
                <label class="custom-checkbox">
                    <input type="checkbox" class="perm-checkbox" name="<?= $field ?>" <?= $checked ?> disabled>
                    <span class="checkmark"></span>
                </label>
            </td>
            <?php endforeach; ?>

            <td>
                <button type="button" class="btn btn-sm btn-primary tech-publ-permission-edit-btn">Edit</button>
                <button type="button" class="btn btn-sm btn-success tech-publ-permission-update-btn" style="display:none;">Update</button>
                <button type="button" class="btn btn-sm btn-secondary tech-publ-permission-cancel-btn" style="display:none;">Cancel</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php } ?>


<script>
    $(document).ready(function() {

        // Edit button clicked
        $('#permissionsTable').on('click', '.tech-publ-permission-edit-btn', function() {
            var row = $(this).closest('tr');

            //Store original checkbox states before editing
            row.find('.perm-checkbox').each(function() {
                $(this).data('original', $(this).is(':checked'));
            });

            // Enable checkboxes
            row.find('.perm-checkbox').prop('disabled', false);

            // Toggle buttons
            row.find('.tech-publ-permission-edit-btn').hide();
            row.find('.tech-publ-permission-update-btn, .tech-publ-permission-cancel-btn').show();
        });

        // Cancel button clicked
        $('#permissionsTable').on('click', '.tech-publ-permission-cancel-btn', function() {
            var row = $(this).closest('tr');

            //Restore original checkbox states
            row.find('.perm-checkbox').each(function() {
                var original = $(this).data('original');
                $(this).prop('checked', original);
            });

            // Disable checkboxes again
            row.find('.perm-checkbox').prop('disabled', true);

            // Toggle buttons
            row.find('.tech-publ-permission-update-btn, .tech-publ-permission-cancel-btn').hide();
            row.find('.tech-publ-permission-edit-btn').show();
        });

        // Update button clicked
        $('#permissionsTable').on('click', '.tech-publ-permission-update-btn', function() {
            var row = $(this).closest('tr');
            var id = row.data('id');

            if ($.trim(id) === '') {
                alert("Something went wrong, please try again.");
                return;
            }

            // Gather checkbox data
            var data = {
                id: id,
                action_add: row.find('input[name="action_add"]').is(':checked') ? 1 : 0,
                action_edit: row.find('input[name="action_edit"]').is(':checked') ? 1 : 0,
                action_view: row.find('input[name="action_view"]').is(':checked') ? 1 : 0,
                action_delete: row.find('input[name="action_delete"]').is(':checked') ? 1 : 0,
            };

            $('.loader').show();

            $.ajax({
                url: '<?= $this->Url->build(["controller" => "TechnicalPublicationPermissions", "action" => "updatePermissions"]); ?>',
                type: 'POST',
                data: data,
                headers: { 'X-CSRF-Token': '<?= $this->request->getAttribute("csrfToken"); ?>' },
                success: function(response) {
                    $('.loader').hide();
                    if (response.success) {
                        alert('Permissions updated successfully!');

                        //Save new state as original after successful update
                        row.find('.perm-checkbox').each(function() {
                            $(this).data('original', $(this).is(':checked'));
                        });

                        // Disable checkboxes again
                        row.find('.perm-checkbox').prop('disabled', true);

                        // Toggle buttons
                        row.find('.tech-publ-permission-update-btn, .tech-publ-permission-cancel-btn').hide();
                        row.find('.tech-publ-permission-edit-btn').show();
                    } else {
                        alert('Failed to update permissions.');
                    }
                },
                error: function() {
                    $('.loader').hide();
                    alert('Error while saving data.');
                }
            });
        });

    });

</script>