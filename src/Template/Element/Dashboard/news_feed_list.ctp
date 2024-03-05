<div id="dashboardNewsFeedListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>News Feed List</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div class="col-md-12">
                        <?php if(!empty($dashboardMenuItems->action_delete) || $user_role == '1'){ ?>
                        <button type="button" class="btn btn-default delete_dashboard_news_feed">Delete News Feed</button>
                        <?php } if(!empty($dashboardMenuItems->action_add) || $user_role == '1'){ ?>
                        <button type="button" class="btn btn-default float-right fetchDashboardPopup" data-val="dashboard_news_feed_add">Add News Feed</button>
                        <?php } ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">News Feed</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody class="dashboard-news-feed-list">
                                <?php
                                echo $this->Dashboard->getNewsFeedListHTML($newsfeedlist);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>