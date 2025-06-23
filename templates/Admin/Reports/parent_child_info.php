<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">
	<?php
		if(!empty($type) && $type == 'parent') {
			echo 'Child Items of #'.$pid;
		} elseif(!empty($type) && $type == 'child') {
			echo 'Parent Items of #'.$cid;
		}
	?>
    </h4>
</div>
<div class="modal-body" style="max-height: 580px; overflow-y: auto;">
	<table id="customReport" class="table table-hover table-header-dark">
		<thead>
		    <tr>
		        <th width="4%"></th>
                <th width="2%"></th>
                <th width="2%">Aircraft</th>
                <th width="4%">ATA</th>
                <th width="14%">Reference & Component & Item Type</th>
                <th width="20%">Description</th>
                <th width="11%">Current Hr/Cy</th>
                <th width="10%">Last C/W</th>
                <th width="8%">Intervals</th>
                <th width="10%">Next Due</th>
                <th width="8%">Remaining</th>
                <th width="7%">Status</th>
                <th width="0%" style="display: none;"></th>
		        <th width="0%" style="display: none;"></th>
		    </tr>
		</thead>
		<tbody id="aircraftPartsList">
		    <?php
		    $subPartIds = array();
		    foreach ($subResults as $row) { 
		        $subPartIds[] = $row['partDet']['id'];                       
		    	echo $this->element('partslist', array('row'=>$row, 'openUrl'=>true, 'typ'=>$typ, 'act'=>$act)); 
		    } 
		    ?>
		</tbody>
	</table>
</div>