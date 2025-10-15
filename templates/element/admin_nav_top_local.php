<?php
$sessionUser = $this->request->getSession()->read('Auth');
?>
<nav class="topHeader">
    <ul class="toogleWrap">
        <li class="menu-head">
            <div>
                <?php
                $urlarr = $sessionUser['role_id'] != '15' ? array('controller' => 'Dashboard') : array('controller' => 'Reports', 'action' => 'customReport');
                echo $this->Html->link($this->Html->image('/images/logo.png'), $urlarr, array('alt' => 'SOUTHWEST AVIATION SPECIALTIES, LLC', 'escape' => false));
                ?>
            </div>
            <a href="javascript:void(0);" class="push_menu toggle-side menu_toggle"> <span class="glyphicon  glyphicon-align-justify pull-right"></span></a>
        </li>
    </ul>
    <?php
    if (!empty($sessionUser)) {
        $allMenuItem = $sessionUser['AllMenuItems'];
        $userMenu = $sessionUser['UserMenu'];
    }
    ?>
    <div class="topheader_right_block col-md-12">
        <div class="header-icon-container hide-in-mob">
            <div>
                <a href="javascript:void(0);" class="wo-list-all-message" data-val="all-user" click-source="top_message" title='Messages'>
                    <?php echo $this->Html->image('/images/icons/mail_icon.png', array('class' => 'header-time-clock-link')); ?>
                    <span class="message-counter" style="display:none;">0</span>
                </a>
            </div>
            <?php if (in_array('Item Catalog', $userMenu)) { ?>
                <div>
                    <a href="<?php echo $this->Url->build(['controller' => 'InventoryItems']); ?>" title="Item Catalog">
                        <?php echo $this->Html->image('/images/icons/inventory_icon.png', array('title' => 'Item Catalog')); ?>
                    </a>
                </div>
            <?php }
            if (in_array('Work Orders', $userMenu)) { ?>
                <div>
                    <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'loadWorkOrder']); ?>" title="Work Order">
                        <?php echo $this->Html->image('/images/icons/work_order.png', array('title' => 'Work Order')); ?>
                    </a>
                </div>
            <?php }
            if (in_array('Customer/OTC', $userMenu)) { ?>
                <div>
                    <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'index']); ?>" title="Customer/OTC">
                        <?php echo $this->Html->image('/images/icons/customer_otc.png', array('title' => 'Customer/OTC')); ?>
                    </a>
                </div>
            <?php }
            if (in_array('Information Center', $userMenu)) { ?>
                <div>
                    <a href="<?php echo $this->Url->build(['controller' => 'Reports', 'action' => 'customReport']); ?>" title="Information Center">
                        <?php echo $this->Html->image('/images/icons/information_center.png', array('title' => 'Information Center')); ?>
                    </a>
                </div>
            <?php } ?>
            <div>
                <a href="javascript:void(0);" class="fetchUserTimeClockPopup" data-val="time_clock" title='Time Clock'>
                    <?php echo $this->Html->image('/images/icons/time_clock_icon.png', array('class' => 'header-time-clock-link')); ?>
                </a>
            </div>
        </div>

        <div class="col-md-6 searchBox">

            <div class="row">
                <!-- Filter Dropdown -->
                <div class="col-md-4 col-xs-4">
                    <?php
                    $searchDropDown = unserialize(SEARCHDROPDOWN);
                    echo $this->Form->control('header_filter', [
                        'options' => $searchDropDown,
                        'empty' => 'Select Filter',
                        'class' => 'form-control selectpicker',
                        'data-show-subtext' => true,
                        'data-live-search' => true,
                        'label' => false,
                        'id' => 'header_filter',
                        'value' => 'all'
                    ]);
                    ?>
                </div>
                <!-- Search Input + Button  -->
                <div class="col-md-8 col-xs-8">
                    <div class="input-group">
                        <input type="text" id="search_txt" class="form-control" placeholder="Search..." aria-label="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="button" id="header_search_btn">
                                <i class="glyphicon glyphicon-search" aria-hidden="true"></i> Search
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search List -->
            <div class="searchList" style="margin-top: 10px;">
                <ul class="list-group" id="header_search_list"></ul>
            </div>
        </div>
        <div class="header-icon-container">
            <!--div id="holdingBoxDropZone" class="holding-box top-holding-box hide-in-mob">
                
                <a class="holdingboxlink" href="<?php echo $this->Url->build(['controller' => 'HoldingBoxes', 'action' => 'index']); ?>">
                    <div id="holding-box">
                        <?php echo $this->Html->image('/images/icons/holdingbox_white_icon.png', array('style' => 'width:22px; height:22px; vertical-align:sub;')); ?>
                        <span style="font-size: 18px;"> Holding Box
                            <span class="badge holdingitemcount" style="margin-bottom: 3px;">0</span>
                        </span>
                    </div>
                </a>
            </div-->

            <?php
            if (!empty($sessionUser)) {
            ?>
                <div class="dropdown text-right">
                    <button class="btn btn-link dropdown-toggle d-flex align-items-center" type="button" data-toggle="dropdown">
                        <span class="mr-2"><?php echo $sessionUser['full_name']; ?></span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <?php
                            echo $this->Html->link(
                                "<span>Log Out</span> <i class='fa fa-sign-out'></i>",
                                ['controller' => 'Users', 'action' => 'logout'],
                                ['escape' => false]
                            );
                            ?>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
</nav>
