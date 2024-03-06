<nav class="topHeader">
    <ul class="toogleWrap">
        <li class="menu-head">
            <div>
                <?php
                    echo $this->Html->link($this->Html->image('/images/logo.png'), array('controller' => 'Dashboard'), array('alt'=>'SOUTHWEST AVAITION SPECIALTIES, LLC', 'escape' => false));
                ?>
            </div>
            <a href="javascript:void(0);" class="push_menu toggle-side menu_toggle"> <span class="glyphicon  glyphicon-align-justify pull-right"></span></a>
        </li>
    </ul>

    <div class="topheader_right_block col-md-12">
        <div class="header-icon-container">
            <div>
                <a href="javascript:void(0);" class="wo-list-all-message" data-val="all-user" click-source="top_message" title='Time Clock'>
                    <?php echo $this->Html->image('/images/icons/mail_icon.png', array('class'=>'header-time-clock-link')); ?>
                    <span class="message-counter" style="display:none;">0</span>
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Url->build(['controller' => 'InventoryItems']); ?>">
                    <?php echo $this->Html->image('/images/icons/inventory_icon.png', array('title'=>'Item Catalog')); ?>
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action'=>'loadWorkOrder']); ?>">
                    <?php echo $this->Html->image('/images/icons/work_order.png', array('title'=>'Work Order')); ?>
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers']); ?>">
                    <?php echo $this->Html->image('/images/icons/customer_otc.png', array('title'=>'Customer/OTC')); ?>
                </a>
            </div>
            <div>
                <a href="<?php echo $this->Url->build(['controller' => 'Reports', 'action'=>'customReport']); ?>">
                    <?php echo $this->Html->image('/images/icons/information_center.png', array('title'=>'Information Center')); ?>
                </a>
            </div>
        </div>
        <div class="header-icon-container">
            <div>
                <a href="javascript:void(0);" class="fetchUserTimeClockPopup" data-val="time_clock" title='Time Clock'>
                    <?php echo $this->Html->image('/images/icons/time_clock_icon.png', array('class'=>'header-time-clock-link')); ?>
                </a>
            </div>
            
            <div id="holdingBoxDropZone" class="holding-box top-holding-box">
                
                <a class="holdingboxlink" href="<?php echo $this->Url->build(['controller' => 'HoldingBoxes', 'action' => 'index']); ?>">
                    <div id="holding-box">
                        <?php echo $this->Html->image('/images/icons/holdingbox_white_icon.png', array('style'=>'width:22px; height:22px; vertical-align:sub;')); ?>
                        <!--img src="../images/icons/holdingbox_white_icon.png" height="22px" width="22px" style="vertical-align:sub;"-->
                        <span style="font-size: 18px;"> Holding Box
                            <span class="badge holdingitemcount" style="margin-bottom: 3px;">0</span>
                        </span>
                    </div>
                </a>
            </div>

            <?php 
            $sessionUser = $this->Session->read('Auth.User'); 
            if ( !empty($sessionUser) ) {
            ?>
            <div class="dropdown" style="text-align:right !important;">
                <button class="btn btn-link dropdown-toggle" type="button" data-toggle="dropdown">
                    <span><?php echo $sessionUser['full_name']; ?></span>
                    <span class="fa fa-angle-down icon"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <?php
                        echo $this->Html->link("<span>Log Out</span><i class='fa fa-sign-out'></i>",
                        array('controller' => 'Users', 'action' => 'logout'), array('escape' => false));
                        ?>
                    </li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</nav>