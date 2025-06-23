<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;
?>     
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Pilot Information</h2>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
            <?php 
            if(!empty($pilot)) { 
            ?>
                <div class="form-horizontal form-label-left">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Pilot Information</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Name:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->full_name; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Email Address:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->email; ?>
                                            <input type="hidden" name="email" value="<?php echo $pilot->user->email; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Certificate Number:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            if(!empty($pilot['certificate_number']) && $pilot['certificate_number'] != '000') { 
                                                echo $pilot['certificate_number'];
                                            } 
                                            ?>
                                            <input type="hidden" name="certificate_number" value="<?php echo $pilot['certificate_number']; ?>">
                                        </div>
                                    </div>

                                    <?php
                                    if (isset($pilot->user->phone_ext)) {
                                        $phoneExt = !empty($pilot->user->phone_ext) ? $pilot->user->phone_ext : '+1';
                                    } else {
                                        $phoneExt = '+1';
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Primary Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            echo $phoneExt.'- '.$pilot->user->phone; 
                                            ?>
                                            <input type="hidden" name="phone_ext" value="<?php echo $phoneExt; ?>">
                                            <input type="hidden" name="phone" value="<?php echo $pilot->user->phone; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Secondary Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            if(!empty($pilot['secondary_phone'])) { 
                                                echo $phoneExt.'- '.$pilot['secondary_phone'];
                                            } 
                                            ?>
                                            <input type="hidden" name="secondary_phone" value="<?php echo $pilot['secondary_phone']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Emergency Contact:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot['emergency_contact']; ?>
                                            <input type="hidden" name="emergency_contact" value="<?php echo $pilot['emergency_contact']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Emergency Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            if(!empty($pilot['emergency_phone'])) { 
                                                echo $phoneExt.'- '.$pilot['emergency_phone'];
                                            } 
                                            ?>
                                            <input type="hidden" name="emergency_phone" value="<?php echo $pilot['emergency_phone']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Address:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->addresses[0]->address_line1; ?>
                                            <input type="hidden" name="addresses.0.address_line1" value="<?php echo $pilot->user->address_line1; ?>">
                                        </div>
                                    </div>
                                                            
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Country:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->addresses[0]->country->name; ?>
                                            <input type="hidden" name="addresses.0.country_id" value="<?php echo $pilot->user->addresses[0]->country_id; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">State:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->addresses[0]->state->name; ?>
                                            <input type="hidden" name="addresses.0.state_id" value="<?php echo $pilot->user->addresses[0]->state_id; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">City:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->addresses[0]->city->name; ?>
                                            <input type="hidden" name="addresses.0.city_id" value="<?php echo $pilot->user->addresses[0]->city_id; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Zip Code:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->addresses[0]->zip_code; ?>
                                            <input type="hidden" name="addresses.0.zip_code" value="<?php echo $pilot->user->addresses[0]->zip_code; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
            } else { 
            ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                    No record available
                    </div>
                </div>
            <?php 
            } 
            ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('pilots_popup'); ?>

<?php echo $this->Html->script('pilots'); ?>
<script>    
var getCitiesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getCitiesList']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
//var isEmailExist  = "<?php //echo $this->Url->build(['controller' => 'Pilots', 'action' => 'isEmailExist']); ?>";
var specifyInfo   = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'specifyInfo']); ?>";
var crewCurrency  = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'crewCurrency']);?>";
</script>
