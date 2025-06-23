<div id="dashboardNewsFeedAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php if(!empty(@$newsfeeds->id)){ echo 'Edit'; }else{echo 'Add';} ?> News Feed</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($newsfeeds, array('class' => 'form-horizontal form-label-left', 'id' => 'frmDashboardNewsFeedAdd'));
                    ?>
                    <input type="hidden" name="news_feed_id" value="<?php echo @$newsfeeds->id; ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">News Feed</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('news_feed', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'news_feed')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Speed</label>
                                <div class="form-input-frame">
                                    <?php
                                    $newfeedspeed = [];
                                    for($i=1; $i<=10; $i++){
                                        $newfeedspeed[$i] = $i;
                                    }
                                    $news_feed_speed = !empty($newsfeeds->news_feed_speed) ? $newsfeeds->news_feed_speed : '2';
                                    echo $this->Form->control('news_feed_speed', array('options' => $newfeedspeed, 'empty' => 'Select speed...', 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'news_feed_speed'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">News Feed Status</label>
                                <div class="form-input-frame">
                                    <?php
                                    $newfeedstatus = ['0'=>'Inactive', '1'=>'Active'];
                                    echo $this->Form->control('status', array('options' => $newfeedstatus, 'empty' => 'Select status...', 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'status'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php 
                $isdisabled = '';
                if((empty($dashboardMenuItems->action_edit) && !empty(@$newsfeeds->id)) && $user_role != '1'){ 
                    $isdisabled = 'disabled';
                }
                ?>
                <button type="button" class="btn btn-primary saveDashboardNewsFeed" <?php echo $isdisabled; ?>>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>