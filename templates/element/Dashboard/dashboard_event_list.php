<div id="dashboardEventListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Event List</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div class="col-xs-12">
                        <?php if(!empty($dashboardMenuItems->action_delete) || $user_role == '1'){ ?>
                        <button type="button" class="btn btn-default delete_dashboard_event">Delete Event</button>
                        <?php } if(!empty($dashboardMenuItems->action_add) || $user_role == '1'){ ?>
                        <button type="button" class="btn btn-default float-right fetchDashboardPopup" data-val="dashboard_event_add">Add Event</button>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Event Description</th>
                                        <th scope="col">Start Date</th>
                                        <th scope="col">End Date</th>
                                    </tr>
                                </thead>
                                <tbody class="dashboard-event-list">
                                    <?php
                                    echo $this->Dashboard->getDashboardEventListHTML($dashboardeventlist);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
