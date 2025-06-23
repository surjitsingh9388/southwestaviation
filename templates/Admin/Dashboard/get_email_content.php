<table id="datatable" class="table table-striped table-bordered table-hover table-responsive">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="10%"><?php echo __('Date Time'); ?></th>
            <th width="10%"><?php echo __('Email Type'); ?></th>
            <th width="35%"><?php echo __('To'); ?></th>
            <th width="25%" pull-right><?php echo __('Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
            if (count($emailQueueData) > 0) {
                // $emailType = ['Become_Member', 'Contact_Us', 'Current_Routes', 'How_It_Works', 'Nominate_A_Destination'];
                $i =1;
                foreach ($emailQueueData as $row) { 
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row->created->format('M j, Y g:i A'); ?></td>
            <td><?php echo str_replace("_", " ", $row->email_type); ?></td>
            <td>
                <?php 
                    $emailData = json_decode($row->email_data);
                    if (!empty($emailData->emailUsersList)) {
                        $emailUsersList = (array) $emailData->emailUsersList;
                        echo implode(', ', array_values($emailUsersList)); 
                    }
                ?>
            </td>
            <td>
                <?php
                echo $this->Html->link('<i class="fa fa-folder"></i> View', 'javascript:void(0)', array('class' => 'btn btn-primary btn-xs', 'escape' => false, 'onclick'=>"viewEmail(". $row->id .", '".$row->email_type."')"));
                echo $this->Html->link("<i class='fa fa-envelope-o'></i> Resend Email", ['controller'=>'Dashboard', 'action' => 'saveEmailQueue/'.$row->id, /*'prefix' => false*/], ['class' => 'btn btn-success btn-xs sendMail', 'escape' => false]);
                ?>
            </td>
        </tr>
        <?php
                $i++; 
                } 
            } else { ?>
                <tr><td colspan="5" class="noResult">No Records Found.</td></tr>
            <?php }
        ?>
    </tbody>
</table>