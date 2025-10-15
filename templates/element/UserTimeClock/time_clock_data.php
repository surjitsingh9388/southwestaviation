<?php
foreach($usertimeclocklist as $timeclock){
?>
<tr>
    <td><?php echo date('m/d/Y', strtotime($timeclock['in_time'])); ?></td>
    <td><?php echo date('h:i A', strtotime($timeclock['in_time'])); ?></td>
    <td><?php echo !empty($timeclock['out_time']) ? date('h:i A', strtotime($timeclock['out_time'])) : ''; ?></td>
    <td><?php echo $timeclock['totaltime']; ?></td>
</tr>
<?php } ?>