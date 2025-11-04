<div class="content sliding test">
    <div class="outerWrapper dashboardSection">
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
            <div class="align-center-items justify-content-between newHeader">
                <h6>News Feed</h6>
                <?php if(!empty($newsfeedmenu) || $user_id == '1'){ ?>    
                <button type="button" class="btn btn-default fetchDashboardPopup" data-val="news_feed_list" style="margin:0px !important">News Feed List</button>
                <?php } ?>
            </div>
            
            <div class="bg-white news_feed_dashboard">
                <p class="news_feed"><marquee behavior="scroll" direction="left" scrollamount="<?php echo $dashboardnewsfeed->news_feed_speed; ?>"><?php echo !empty($dashboardnewsfeed) ? $dashboardnewsfeed->news_feed : ''; ?></marquee></p>
            </div>
        </div>
        <div class="m-0 row">
            <div class="col-lg-6 col-md-12 col-xs-12 pd0 mb-10 tableBg">
                <div class="dashboard_heading_bar">
                    <span class="dashboard_heading">PTO Dashboard</span>
                </div>
                <div class="btnWrapper p-12">
                    <button type="button" class="btn btn-default pto_request_history_btn">PTO History</button>
                    <button type="button" class="btn btn-default pto_request_create_btn">Create</button>
                </div>
                <div class="page-content mt-35">
                    <div class="table-responsive clock_log_table_scroll">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap" id="toolName">Date <i class="fa fa-fw fa-sort"></i></th>
                                    <th  class="text-nowrap" id="toolDescription">Previous Balance <i class="fa fa-fw fa-sort"></i></th>
                                    <th class="text-nowrap" id="toolDescription">Hours Used/Gained <i class="fa fa-fw fa-sort"></i></th>
                                    <th  class="text-nowrap" id="toolModel">New Balance <i class="fa fa-fw fa-sort"></i></th>
                                    <th class="text-nowrap" id="toolModel">Approved <i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($ptorequestlist as $ptorequest){
                                ?>
                                <tr>
                                    <td><?php echo date('m/d/Y', strtotime($ptorequest['created_at'])); ?></td>
                                    <td><?php echo $ptorequest['previous_balance']; ?></td>
                                    <td><?php echo $ptorequest['hours_used_gained']; ?></td>
                                    <td><?php echo $ptorequest['new_balance']; ?></td>
                                    <td><i class="fa fa-check"></i></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-xs-12 pd0 mb-10 tableBg">
                <div class="dashboard_heading_bar"><span class="dashboard_heading">Time Clock</span></div>
                <div class="btnWrapper p-12">
                    <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock">Clock In/Out</button>
                    <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock_log">Time Clocks Log</button>
                </div>
    
                <div class="page-content mt-35">
                    <div class="table-responsive clock_log_table_scroll">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th id="toolName">Date <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolDescription">Clock In <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolDescription">Clock Out <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolModel">Hrs. Worked <i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="inventoryToolsList">
                                <?php echo $this->element('UserTimeClock/time_clock_data'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 pd0">
            <div class="align-center-items justify-content-between">
                <h6>Event </h6>
                <?php if(!empty($eventmenu) || $user_id == '1'){ ?>    
                    <button type="button" class="btn btn-default fetchDashboardPopup mb-0" data-val="dashboard_event_list">Event List</button>
                <?php } ?>
            </div>
            
            <div class="calendar shadow bg-white p-5 event_calender_block mb-10">
                <?php echo $this->element('Dashboard/event_calender'); ?>
            </div>
        </div>
    </div>
</div>

<div id="dashboardpopup"></div>

<?php 
echo $this->element('InventoryPopup/customer_otc/load_create_new_work_order');
echo $this->element('Inventory/customer_otc/aircraft_work_order_ajax_url'); 
echo $this->element('Dashboard/event_detail_popup'); 

echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_customers');
echo $this->Html->css('user_time_clock');
echo $this->Html->css('dashboard'); 
echo $this->Html->script('dashboard');
echo $this->Html->script('tinymce/tinymce.min');
echo $this->Html->script('user_pto_requests');
?>
<script type="text/javascript">
    var loadTimeClockForDateURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'loadTimeClockForDate',]); ?>";
    var saveUserTimeClockURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'saveUserTimeClock',]); ?>";
    var getUserTimeClockDetailURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'getUserTimeClockDetail',]); ?>";
    var timeClockLogReportsPdfURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'timeClockLogReportsPdf',]); ?>";
    var getPrevNextEventCalenderURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'getPrevNextEventCalender',]); ?>";

    var fetchCustomerOTCPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchCustomerOTCPopup']); ?>";
    var fetchDashboardPopupURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'fetchDashboardPopup']); ?>";
    var saveDashboardEventURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'saveDashboardEvent']); ?>";
    var deleteDashboardEventURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'deleteDashboardEvent']); ?>";
    var saveDashboardNewsFeedURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'saveDashboardNewsFeed']); ?>";
    var deleteDashboardNewsFeedURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'deleteDashboardNewsFeed']); ?>";
    var uploadNewsFeedImageURL = "<?php echo $this->Url->build(['controller'=>'Dashboard', 'action'=>'uploadNewsFeedImage']); ?>";
    var createPTORequestPopupURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'createPTORequestPopup']); ?>";

</script>
