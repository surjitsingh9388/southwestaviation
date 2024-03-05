<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Dashboard</h2>
        </div>
        <?php 
        $newsfeedmenu = [];
        $eventmenu = [];
        foreach($dashboardMenuItems as $usermenu){ 
            if($usermenu['menu_item_id'] == '67'){
                $newsfeedmenu = $usermenu;
            }else if($usermenu['menu_item_id'] == '68'){
                $eventmenu = $usermenu;
            }
        }
        ?>
        <div class="page-content">
            <div class="col-md-12 pd0">
                <h6>News Feed
                    <?php if(!empty($newsfeedmenu) || $user_id == '1'){ ?>    
                        <button type="button" class="btn btn-default float-right fetchDashboardPopup" data-val="news_feed_list">News Feed List</button>
                    <?php } ?>
                </h6>
                <div class="bg-white news_feed_dashboard">
                    <p class="news_feed"><?php echo !empty($dashboardnewsfeed) ? $dashboardnewsfeed->news_feed : ''; ?></p>
                </div>
            </div>            
        </div>
        <div class="col-md-12 pd0" style="background-color:#fff;">
            <div class="col-md-6 pd0" style="padding-right:5px !important;">
                <div class="dashboard_heading_bar"><span class="dashboard_heading">Time Off Request</span></div>
                <div class="col-md-12">
                    
                </div>
            </div>
            <div class="col-md-6 pd0">
                <div class="dashboard_heading_bar"><span class="dashboard_heading">Time Clock</span></div>
                <div class="btnWrapper" style="display:flow-root !important;">
                    <div class="float-left">
                        <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock">Clock In/Out</button>
                    </div>
                    <div class="float-right">
                        <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock_log">Time Clocks Log</button>
                    </div>
                </div>
    
                <div class="page-content mt-35">
                    <div class="table-responsive clock_log_table_scroll">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th id="toolName">Date <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolDescription">Clock In <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolDescription">Clock Out <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolModel">Hrs. Worked <i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="inventoryToolsList">
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
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 pd0">
            <h6>Event 
            <?php if(!empty($eventmenu) || $user_id == '1'){ ?>    
                <button type="button" class="btn btn-default float-right fetchDashboardPopup" data-val="dashboard_event_list">Event List</button>
            <?php } ?>
            </h6>
            
            <div class="calendar shadow bg-white p-5 event_calender_block">
                <?php echo $this->element('Dashboard/event_calender'); ?>
            </div>
        </div>
    </div>
</div>

<div id="dashboardpopup"></div>

<?php 
echo $this->element('InventoryPopup/customer_otc/load_create_new_work_order');
echo $this->element('Inventory/customer_otc/aircraft_work_order_ajax_url'); 

echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_customers');
echo $this->Html->css('user_time_clock');
echo $this->Html->css('dashboard'); 
echo $this->Html->script('dashboard');
echo $this->Html->script('tinymce/tinymce.min');
?>
<script type="text/javascript">
    var loadTimeClockForDateURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'loadTimeClockForDate',]); ?>";
    var saveUserTimeClockURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'saveUserTimeClock',]); ?>";
    var getUserTimeClockDetailURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'getUserTimeClockDetail',]); ?>";
    var timeClockLogReportsPdfURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'timeClockLogReportsPdf',]); ?>";
    var getPrevNextEventCalenderURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'getPrevNextEventCalender',]); ?>";
    var loadAircraftWorkOrderPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'loadAircraftWorkOrderPopup']); ?>";

    var fetchCustomerOTCPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchCustomerOTCPopup']); ?>";
    var fetchDashboardPopupURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'fetchDashboardPopup']); ?>";
    var saveDashboardEventURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'saveDashboardEvent']); ?>";
    var deleteDashboardEventURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'deleteDashboardEvent']); ?>";
    var saveDashboardNewsFeedURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'saveDashboardNewsFeed']); ?>";
    var deleteDashboardNewsFeedURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'deleteDashboardNewsFeed']); ?>";
    var uploadNewsFeedImageURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'uploadNewsFeedImage']); ?>";
</script>