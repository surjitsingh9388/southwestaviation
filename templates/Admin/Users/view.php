<?php
/**
 * view user details page
 */
$sessionUser = $this->request->getSession()->read('Auth');;
?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">User Information</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">User Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('User Id:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->id); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Title:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->title); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('First Name:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->first_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Middle Name:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->middle_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Last Name:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->last_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Suffix:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->suffix); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Full Name:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->full_name); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Email:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->email); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Mobile Number:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->phone); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Office/Home Phone Number:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo h($user->phone_ext) . '-' . h($user->home_phone); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Role:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $user->has('role') ? $this->Html->link($user->role->role_name, ['controller' => 'Roles', 'action' => 'view', $user->role->id]) : ''; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Status:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $user->suspended ? __('Suspended') : __('Active'); ?>
                            </div>
                        </div>
                    </div>
                   
                    <div class="panel-heading">
                        <h3 class="panel-title">Address Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Address Line1:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->address_line1) ? h($user->addresses[0]->address_line1) : ''; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Address Line2:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->address_line2) ? h($user->addresses[0]->address_line2) : ''; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('City:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->city->name) ? h($user->addresses[0]->city->name) : ''; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Zip Code:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->zip_code) ? h($user->addresses[0]->zip_code) : ''; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('State:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->state->name) ? h($user->addresses[0]->state->name) : ''; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <label class="control-label pull-right"><?php echo __('Country:'); ?></label>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo isset($user->addresses[0]->country->name) ? h($user->addresses[0]->country->name) : ''; ?>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>