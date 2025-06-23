<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth'); 
use Cake\Routing\Router;
?>
    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Schedule</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body" style="min-height: 500px; padding: 30px;">
                    <div class="row">
                        <div class="form-group custom-input-chk">
                            <input type="checkbox" name="success" disabled="disabled" checked="checked"> Your flight has been successfully saved!
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group" style="text-align: center;">
                            What would you like to do now?
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-12">
                                <?php
                                echo $this->Html->link("Back to Flight Center", array('controller'=>'Flightlogs', 'action'=>'index'), array('class' => 'btn btn-success col-sm-12 col-xs-12', 'escape' => false));
                                ?>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <button type="submit" class='btn btn-success col-sm-12 col-xs-12' id='uploadTripPopup', data-tripid="<?php echo $tripid; ?>">Upload Trip Files (<span class="filesCountCls"><?php echo $tfcount; ?></span>)</button>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
echo $this->element('flightlog_popup'); 
echo $this->Html->script('/js/flightlogs');
?>

<script>
var saveTripFiles = "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'saveTripFiles']); ?>";
var editTFPopup = "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'editTFPopup']); ?>";
var tripFilesList = "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'tripFilesList']); ?>";
var deleteTripFile = "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'deleteTripFile']); ?>";  
</script>