<?php 
$sessionUser = $this->Session->read('Auth.User');
if ( !empty($sessionUser) ) {
    $allMenuItem = $sessionUser['AllMenuItems'];
    $userMenu = $sessionUser['UserMenu'];
}
//pr($userMenu);die;
$controller = $this->request->getParam('controller');
$action = $this->request->getParam('action');
$type = !empty($_GET['type']) ? $_GET['type'] : '';
?>
<div id="sidebar-menu" class="side-bar sliding">
    <ul style="max-height: 700px; overflow-y: auto;">
        <div class="menu">
            <?php
            if(in_array('User Management', $userMenu)) {
                $userStyle = '';
                $userSection = array('Users', 'Roles');
                if (in_array($controller, $userSection)) {
                    $userStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php 
                } else { 
                ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size">
                        <?php echo $this->Html->image('/images/icons/user.png'); ?>
                        </span> User Management</a>

                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $userStyle; ?>>
                        <?php 
                        if(in_array('Users', $userMenu)) {
                            if (($controller == 'Users') && (in_array($action, array('index', 'view', 'add', 'edit')))) { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php } ?>
                            <?php
                                echo $this->Html->link("<i class='fa fa-user'></i> Users", 
                                    array('controller' => 'Users' ,'action' => 'index'), 
                                    array('escape' => false));
                            ?>
                            </li>
                            <?php 
                        }
                        
                        if(in_array('Permissions', $userMenu)) {
                            if ($controller == 'Users' && $action == 'assignMenus') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php } ?>
                            <?php
                                echo $this->Html->link("<i class='fa fa-user'></i> Permissions", 
                                        array('controller' => 'Users' ,'action' => 'assignMenus'), 
                                        array('escape' => false));
                                    
                            ?>
                            </li>
                            <?php 
                        }

                        if(in_array('Roles', $userMenu)) {
                            if ($controller == 'Roles') { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php } ?>
                                <?php
                                    echo $this->Html->link("<i class='fa fa-cubes'></i> Roles", array('controller' => 'Roles', 'action' => 'index'), array('escape' => false));
                                ?>
                            </li>
                            <?php 
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }
            
            if(in_array('Aircraft Management', $userMenu)) {
                $flightSubStyle = '';
                $flightSection = array('Planes', 'AirframeCategories', 'AirframeComponents', 'AirframeComponentTimes', 'AirframeComponentParts', 'AtaCodes');

                if (in_array($controller, $flightSection)) {
                    $flightSubStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php } else { ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size">
                        <?php echo $this->Html->image('/images/icons/army.png'); ?>
                        </span>Aircraft Management</a>

                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $flightSubStyle; ?>>
                        <?php 
                        if(in_array('Aircraft', $userMenu)) {
                            if ($controller == 'Planes' && (in_array($action, array('index', 'view', 'add', 'edit')))) { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='glyphicon glyphicon-plane'></i> <span>Aircraft</span>", 
                                array('controller' => 'Planes', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }
                        
                        if(in_array('Aircraft Components', $userMenu)) {
                            if ($controller == 'AirframeComponents') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Aircraft Components</span>", array('controller' => 'AirframeComponents' ,'action' => 'index'), array('escape' => false));  
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Sub Components', $userMenu)) {
                        ?>  
                            <li>
                                <?php
                                echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Sub Components</span>", array('controller' => 'SubComponents' ,'action' => 'index'), array('escape' => false));
                                ?>
                                <ul style="padding-left: 30px;">
                                    <?php 
                                    if(in_array('Sub Components', $userMenu)) {
                                        if ($controller == 'SubComponents') { 
                                        ?>
                                        <li class="current-page">
                                        <?php } else { ?>
                                        <li>
                                        <?php 
                                        } 
                                        echo $this->Html->link("<i class='fa fa-arrow-right'></i> <span>Sub Component</span>", array('controller' => 'SubComponents' ,'action' => 'index'), array('escape' => false));  
                                        ?>
                                        </li>
                                    <?php
                                    }

                                    if(in_array('Sub Components', $userMenu)) {
                                        if ($controller == 'SubComponents') { 
                                        ?>
                                        <li class="current-page">
                                        <?php } else { ?>
                                        <li>
                                        <?php 
                                        } 
                                        echo $this->Html->link("<i class='fa fa-arrow-right'></i> <span>Sub Component 1-1</span>", array('controller' => 'SubComponents' ,'action' => 'index2'), array('escape' => false));  
                                        ?>
                                        </li>
                                    <?php
                                    }

                                    ?>
                                </ul>
                            </li>
                        <?php
                        }

                        if(in_array('Airframe Component Times', $userMenu)) {
                            if ($controller == 'AirframeComponentTimes') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }  
                            echo $this->Html->link("<i class='fa fa-clock-o'></i> <span>Component Times</span>", 
                                array('controller' => 'AirframeComponentTimes' ,'action' => 'index'), 
                                array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Airframe Component Parts', $userMenu)) {
                            if ($controller == 'AirframeComponentParts') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-cogs'></i> <span>Component Parts</span>", 
                                array('controller' => 'AirframeComponentParts' ,'action' => 'index'), 
                                array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('ATA Codes', $userMenu)) {
                            if ($controller == 'AtaCodes') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class=' fa fa-tasks'></i> <span>ATA Codes</span>", 
                                array('controller' => 'AtaCodes' ,'action' => 'index'), 
                                array('escape' => false));
                                ?>
                            </li>
                        <?php
                        }

                        if(in_array('Dispositions', $userMenu)) {
                            if ($controller == 'Dispositions') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class=' fa fa-tasks'></i> <span>Dispositions</span>", 
                                array('controller' => 'Dispositions' ,'action' => 'index'), 
                                array('escape' => false));
                                ?>
                            </li>
                        <?php
                        }

                        if(in_array('AD/SB Status', $userMenu)) {
                            if ($controller == 'AdsbStatus') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class=' fa fa-tasks'></i> <span>AD/SB Status</span>", 
                                array('controller' => 'AdsbStatus' ,'action' => 'index'), 
                                array('escape' => false));
                                ?>
                            </li>
                        <?php
                        }

                        if(in_array('Clone Records', $userMenu)) {
                            if ($controller == 'CloneRecords') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-copy'></i> <span>Clone Records</span>", 
                                array('controller' => 'CloneRecords' ,'action' => 'index'), 
                                array('escape' => false));
                                ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }
            
            if(in_array('Custom Reports', $userMenu)) {
                if ($controller == 'Reports' && $action == 'customReport') { ?>
                <li class="current-page">
                <?php } else { ?>
                <li>
                <?php
                }
                echo $this->Html->link("<span class='icon-size'>".$this->Html->image('/images/icons/Courses.png')."</span> Reports", array('controller' => 'Reports', 'action' => 'customReport'), array('escape' => false));
                ?>
                </li>
            <?php 
            }
            
            if(in_array('Maintenance', $userMenu)) {
                $flightSubStyle = '';
                $flightSection = array('Reports');

                if (in_array($controller, $flightSection)) {
                    $flightSubStyle = 'style="display: block;"';
                ?>
                    <li class="current-page active dropdown">
                <?php } else { ?>
                    <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                    <span class="icon-size">
                    <?php echo $this->Html->image('/images/icons/repair.png'); ?>
                    </span>Maintenance</a>

                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $flightSubStyle; ?>>
                        <?php 
                        if(in_array('Information Center', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'customReport') { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Information Center</span>", array('controller' => 'Reports', 'action' => 'customReport'), array('escape' => false));
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Aircraft/Equipment', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Equipment') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Aircraft/Equipment</span>", array('controller' => 'Reports' ,'action' => '#'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }
                        
                        if(in_array('Due List', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'maintenance' && $type == 'maintenance') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Due List</span>", 
                                array('controller' => 'Reports', 'action' => 'maintenance', 'type'=>'maintenance'), 
                                array('escape' => false));  
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Maintenance Items', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'maintenance' && $type == 'maintenanceItems') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Maintenance Items</span>", array('controller' => 'Reports' ,'action' => 'maintenance', 'type'=>'maintenanceItems'), array('escape' => false)); 
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Non-Routine', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Non-Routine') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Non-Routine</span>", 
                                array('controller' => 'Reports' ,'action' => '#'), 
                                array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Work Orders', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Work Orders') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Work Orders</span>", 
                                    array('controller' => 'Reports', 'action' => '#'), 
                                    array('escape' => false));   
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Work Completed', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Work Completed') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Work Completed</span>", 
                                array('controller' => 'Reports' ,'action' => '#'), 
                                array('escape' => false));  
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Logbooks', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'index') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Logbooks</span>", 
                                array('controller' => 'Reports' ,'action' => '#'), 
                                array('escape' => false));   
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Checklists', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Checklists') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Checklists</span>", 
                                array('controller' => 'Reports', 'action' => '#'), 
                                array('escape' => false)); 
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Reliability Reports', $userMenu)) {
                            if ($controller == 'Reports' && $action == 'Reliability') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class=' fa fa-tasks'></i> <span>Reliability Reports</span>", array('controller' => 'Reports' ,'action' => '#'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }

            if(in_array('Aircraft Discrepancies', $userMenu)) {
                $flightSubStyle = '';
                $flightSection = array('AircraftDiscrepancies');

                if (in_array($controller, $flightSection)) {
                    $flightSubStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php } else { ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size">
                        <?php echo $this->Html->image('/images/icons/army.png'); ?>
                        </span>Aircraft Discrepancies</a>

                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $flightSubStyle; ?>>
                        <?php 
                        if(in_array('Aircraft Discrepancy', $userMenu)) {
                            if ($controller == 'AircraftDiscrepancies' && (in_array($action, array('index', 'view', 'add', 'edit')))) { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='glyphicon glyphicon-plane'></i> <span>Discrepancy Log</span>", 
                                array('controller' => 'AircraftDiscrepancies', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }

            if(in_array('Crew', $userMenu)) {
                $flightSubStyle = '';
                $flightSection = array('Pilots');

                if (in_array($controller, $flightSection)) {
                    $flightSubStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php } else { ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size"><i class="fa fa-group"></i></span>Crew</a>

                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $flightSubStyle; ?>>
                        <?php 
                        if(in_array('Crew Profile', $userMenu) && $sessionUser['id'] != 1) {
                            if($controller == 'Pilots' && $action == 'profile') { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Profile</span>", 
                                array('controller' => 'Pilots', 'action' => 'profile'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Crew List', $userMenu)) {
                            if ($controller == 'Pilots' && (in_array($action, array('index', 'view', 'add', 'edit')))) { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Crew List</span>", 
                                array('controller' => 'Pilots', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Crew Details', $userMenu)) {
                            if ($controller == 'Pilots' && $action == 'crewCurrency') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Currency</span>", array('controller' => 'Pilots' ,'action' => 'crewCurrency'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Crew Duty', $userMenu)) {
                            if ($controller == 'Pilots' && $action == 'crewDuty') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Flight & Duty</span>", array('controller' => 'Pilots' ,'action' => 'crewDuty'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Crew Reports', $userMenu)) {
                            if ($controller == 'Pilots' && $action == 'crewReports') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-file'></i> <span>Reports</span>", array('controller' => 'Pilots' ,'action' => 'crewReports'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }

                        if(in_array('Crew Documents', $userMenu)) {
                            if ($controller == 'Pilots' && $action == 'crewDocuments') {
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            }
                            echo $this->Html->link("<i class='fa fa-files-o'></i> <span>Documents</span>", array('controller' => 'Pilots' ,'action' => 'crewDocuments'), array('escape' => false));
                            ?>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }
            
            if(in_array('Flights', $userMenu)) {
                $flightSubStyle = '';
                $flightSection = array('Flightlogs');

                if (in_array($controller, $flightSection)) {
                    $flightSubStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php } else { ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size"><i class="fa fa-plane"></i></span>Flights</a>
                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $flightSubStyle; ?>>
                        <?php 
                        if(in_array('Dashboard', $userMenu)) {
                            if($controller == 'Flightlogs' && (in_array($action, array('activity')))) { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Dashboard</span>", 
                                array('controller' => 'Flightlogs', 'action' => 'activity'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Flight Center', $userMenu)) {
                            if($controller == 'Flightlogs' && (in_array($action, array('index')))) { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Flight Center</span>", 
                                array('controller' => 'Flightlogs', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Flight Calendar', $userMenu)) {
                            if($controller == 'Flightlogs' && (in_array($action, array('calendar')))) { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Flight Calendar</span>", 
                                array('controller' => 'Flightlogs', 'action' => 'calendar'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Flightlog Report', $userMenu)) {
                            if($controller == 'Flightlogs' && $action == 'reports') { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Flightlog Report</span>", 
                                array('controller' => 'Flightlogs', 'action' => 'reports'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            }
            ?>
        </div>
    </ul>
</div>