<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth'); 
use Cake\Routing\Router;
?>
    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Pre-Planning</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body" style="min-height: 500px; padding: 30px;">
                    <div class="row">
                        <div class="form-group custom-input-chk">
                            <input type="checkbox" name="success" disabled="disabled" checked="checked"> Your pre-planning is complete!
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-2 col-xs-12">
                                <?php
                                echo $this->Html->link("Back to Flight Center", array('controller'=>'Flightlogs', 'action'=>'index'), array('class' => 'btn btn-default col-sm-12 col-xs-12', 'escape' => false));
                                ?>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <?php
                                echo $this->Html->link("Go to Flight Log", array('controller'=>'Flightlogs', 'action'=>'flightlog', $tripid), array('class' => 'btn btn-success col-sm-12 col-xs-12', 'escape' => false));
                                ?>
                            </div>
                            <div class="col-sm-8 col-xs-12">
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>