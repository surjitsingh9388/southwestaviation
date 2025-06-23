<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->getSession()->read('Auth'); ?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">Active Time Clock Users</h2>
            <div class="float-right mb-5">
                <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock_log">Time Clocks Log</button>
                <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock_adjustment">Time Clock Adjustment</button>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th id="toolName">Employee <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolDescription">Hours <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolModel">Time Login <i class="fa fa-fw fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="inventoryToolsList">
                        <?php
                        foreach($usertimeclocklist as $timeclock){
                        ?>
                        <tr>
                            <td><?php echo $timeclock['full_name']; ?></td>
                            <td><?php echo $timeclock['totaltime']; ?></td>
                            <td><?php echo date('h:i A', strtotime($timeclock['in_time'])); ?></td>
                        </tr>
                        <?php } ?>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->css('user_time_clock'); ?>
<script type="text/javascript">
    var loadTimeClockForDateURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'loadTimeClockForDate',]); ?>";
    var saveUserTimeClockURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'saveUserTimeClock',]); ?>";
    var getUserTimeClockDetailURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'getUserTimeClockDetail',]); ?>";
    var timeClockLogReportsPdfURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'timeClockLogReportsPdf',]); ?>";
    var deleteUserTimeClockURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'deleteUserTimeClock',]); ?>";
</script>
