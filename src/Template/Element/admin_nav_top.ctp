<nav class="topHeader">
    <ul class="toogleWrap">
        <li class="menu-head">
            <div >
                <?php
                    echo $this->Html->link($this->Html->image('/images/logo.png'), array('controller' => 'Reports', 'action'=>'customReport'), array('alt'=>'SOUTHWEST AVAITION SPECIALTIES, LLC', 'escape' => false));
                ?>
            </div>
            <a href="javascript:void(0);" class="push_menu toggle-side menu_toggle"> <span class="glyphicon  glyphicon-align-justify pull-right"></span></a>
        </li>
    </ul>
    <div class="dropdown">
        <?php 
        $sessionUser = $this->Session->read('Auth.User'); 
        if ( !empty($sessionUser) ) {
        ?>
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
        <?php } ?>
    </div>
</nav>