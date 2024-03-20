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
    <ul style="max-height: calc(100vh - 57px); overflow-y: auto;">
        <div class="menu">
            <li class="current-page">
                <a href="<?php echo $this->Url->build(['controller'=>'Dashboard']); ?>">
                    <span class="icon-size">
                    <?php echo $this->Html->image('/images/icons/Dashboard.png'); ?>
                    </span> 
                    Dashboard
                </a>
            </li>
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

                        if(in_array('Active Time Clock', $userMenu)) {
                            if ($controller == 'UserTimeClocks') { ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php } ?>
                                <?php
                                    echo $this->Html->link("<i class='fa fa-clock-o'></i> Active Time Clock", array('controller' => 'UserTimeClocks', 'action' => 'index'), array('escape' => false));
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
                            if ($controller == 'InventoryCustomers' && $action == 'Work Orders') { 
                            ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Work Orders</span>", 
                                    array('controller' => 'InventoryCustomers', 'action' => 'loadWorkOrder'), 
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

            if(in_array('Inventory', $userMenu)) {
                $partsSubStyle = '';
                $partsSection = array('Inventory');

                if (in_array($controller, $partsSection)) {
                    $partsSubStyle = 'style="display: block;"';
                ?>
                <li class="current-page active dropdown">
                <?php } else { ?>
                <li class="dropdown">
                <?php 
                }
                ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="icon-size"><i class="fa fa-gears"></i></span>Inventory</a>
                    <ul class="nav dropdown-menu dropdown-usermenu" <?php echo $partsSubStyle; ?>>
                        <?php 
                        if(in_array('Information Center', $userMenu)) {
                            if($controller == 'Inventories' && (in_array($action, array('index')))) { 
                        ?>
                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Information Center</span>", 
                                array('controller' => 'Inventories', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Item Catalog', $userMenu)) {
                            if($controller == 'InventoryItems' && (in_array($action, array('index')))) { 
                        ?>

                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Item Catalog</span>", 
                                array('controller' => 'InventoryItems', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Locations', $userMenu)) {
                            if($controller == 'InventoryLocations' && (in_array($action, array('index')))) { 
                        ?>

                            <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Locations</span>", 
                                array('controller' => 'InventoryLocations', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Requests', $userMenu)) {
                            if($controller == 'InventoryRequests' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Requests</span>", 
                                array('controller' => 'InventoryRequests', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Reports', $userMenu)) {
                            if($controller == 'InventoryReports' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Reports</span>", 
                                array('controller' => 'InventoryReports', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Purchase Orders', $userMenu)) {
                            if($controller == 'InventoryPurchaseOrders' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Purchase Orders</span>", 
                                array('controller' => 'InventoryPurchaseOrders', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Repair Orders', $userMenu)) {
                            if($controller == 'InventoryRepairOrders' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Repair Orders</span>", 
                                array('controller' => 'InventoryRepairOrders', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Shipping Orders', $userMenu)) {
                            if($controller == 'InventoryShippingOrders' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Shipping Orders</span>", 
                                array('controller' => 'InventoryShippingOrders', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Customer/OTC', $userMenu)) {
                            if($controller == 'InventoryCustomers' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Customer/OTC</span>", 
                                array('controller' => 'InventoryCustomers', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Vendors', $userMenu)) {
                            if($controller == 'InventoryVendors' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Vendors</span>", 
                                array('controller' => 'InventoryVendors', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }

                        if(in_array('Manufacturers', $userMenu)) {
                            if($controller == 'InventoryManufacturers' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Manufacturers</span>", 
                                array('controller' => 'InventoryManufacturers', 'action' => 'index'), 
                                array('escape' => false)); 
                            ?>
                            </li>                            
                        <?php
                        }
                        
                        if(in_array('Tools', $userMenu)) {
                            if($controller == 'InventoryTools' && (in_array($action, array('index')))) { 
                        ?>
                        <li class="current-page">
                            <?php } else { ?>
                            <li>
                            <?php 
                            } 
                            echo $this->Html->link("<i class='fa fa-tasks'></i> <span>Tools</span>", 
                                array('controller' => 'InventoryTools', 'action' => 'index'), 
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
            <li class="show-in-mob for-mobile" style="clear: both">
                <div class="header-icon-container">
                    <div>
                        <a href="javascript:void(0);" class="wo-list-all-message" data-val="all-user" click-source="top_message" title='Time Clock'>
                            <?php echo $this->Html->image('/images/icons/mail_icon.png', array('class'=>'header-time-clock-link')); ?>
                            <span class="message-counter" style="display:none;">0</span>
                        </a>
                    </div>
                    <div>
                        <a href="<?php echo $this->Url->build(['controller' => 'InventoryItems']); ?>",  title="Item Catalog">
                            <?php echo $this->Html->image('/images/icons/inventory_icon.png', array('title'=>'Item Catalog')); ?>
                        </a>
                    </div>
                    <div>
                        <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action'=>'loadWorkOrder']); ?>" title="Work Order">
                            <?php echo $this->Html->image('/images/icons/work_order.png', array('title'=>'Work Order')); ?>
                        </a>
                    </div>
                    <div>
                        <a href="<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action'=>'index']); ?>" title="Customer/OTC">
                            <?php echo $this->Html->image('/images/icons/customer_otc.png', array('title'=>'Customer/OTC')); ?>
                        </a>
                    </div>
                    <div>
                        <a href="<?php echo $this->Url->build(['controller' => 'Reports', 'action'=>'customReport']); ?>" title="Information Center">
                            <?php echo $this->Html->image('/images/icons/information_center.png', array('title'=>'Information Center')); ?>
                        </a>
                    </div>
                </div>
            </li>
            <li class="show-in-mob for-mobile" style="clear: both">
                <div id="holdingBoxDropZone" class="holding-box top-holding-box mobButton">
                    
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
            </li>
        </div>
    </ul>
</div>