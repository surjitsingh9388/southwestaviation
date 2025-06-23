<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
use Cake\Routing\Router;
//NOTE: Do not change log_date format(Y-m-d) its used to direct date match with DB
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Aircraft / <?php echo $results['plane_code']; ?></h2>
            <div class="btnWrap">
                <?php echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", array('controller' => 'Reports' ,'action' => 'customReport'), array('class' => 'btn btn-default', 'escape' => false)); ?>
                <?php
                if((!empty($reportAction) && $reportAction['action']['action_add']==1) || $sessionUser['id'] == 1) {
                ?>
                <a class="btn btn-default" href="javascript:void(0);" data-pids="<?= h($results['id']); ?>" id="downloadPdfId">Print Times</a>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="page-content mt-35">
            <div>
                <div class="formBGCls historicalInfo">
                    <div class="addPartBorder">
                        <div class="addPageHeading">General Information</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Aircraft</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['plane_name']; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Owner</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['plane_name']; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Type</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        Aircraft
                                    </div>
                                </div>

                                <?php 
                                $makeModel = explode(" ", $results['plane_type']); 
                                ?>
                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Make</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $makeModel[0]; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Model</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo !empty($makeModel[1]) ? $makeModel[1] : ''; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Part</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo !empty($makeModel[1]) ? $makeModel[1] : ''; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Physical Inventory</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['plane_code'].' Airframe'; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Tail Number</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['plane_code']; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Serial Number</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['plane_serial_number']; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Airworthiness Date</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo date('d-M-Y', strtotime($results['airworthiness_date'])); ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Schedule Rev Level</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['federal_aviation_regulation']; ?>
                                    </div>
                                </div>

                                <div class="form-group"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">Engine Rev Level</div>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $results['federal_aviation_regulation']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="historicalAircraftTime" class="table mb-0">
                        <thead>
                            <tr>
                                <th width="12%">Created Date</th>
                                <th width="13%">Reported Date</th>
                                <th width="10%">Hours</th>
                                <th width="10%">Landings</th>
                                <th width="20%">Last Updated By</th>
                                <th width="15%">Last Updated On</th>
                                <th width="20%">Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($results['airframe_components'])) {
                                foreach ($results['airframe_components'][0]['airframe_component_times'] as $row) {
                                    $historyIds = !empty($row['extra_details'][0]['history_ids']) ? $row['extra_details'][0]['history_ids'] : '';
                            ?>
                                    <tr>
                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-history_ids="<?php echo $historyIds; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>"><?php echo date('d-M-Y', strtotime($row['created'])); ?></td>
                                        
                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            <?php echo date('d-M-Y', strtotime($row['log_date'])); ?>
                                        </td>
                                        
                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            <?php echo $row['hours']; ?>
                                        </td>

                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            <?php echo $row['cycles']; ?>
                                        </td>

                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            
                                        </td>

                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            <?php echo date('d-M-Y', strtotime($row['modified'])); ?>
                                        </td>

                                        <td class="historicalAirId" data-plane_id="<?php echo $results['id']; ?>" data-log_date="<?php echo date('Y-m-d', strtotime($row['log_date'])); ?>">
                                            <?php 
                                            if(!empty($row['extra_details'][0]['reference']) && !empty($row['extra_details'][0]['date']) && strtotime($row['extra_details'][0]['date']) == strtotime($row['log_date'])) {
                                                echo $row['extra_details'][0]['reference'];
                                            } 
                                            ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report time popup -->
<div id="historicalTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Historical Equipment Times</h4>
            </div>
            <div class="modal-body" style="max-height: 580px; overflow-y: auto;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Report time popup -->

<script>
$(document).ready(function() {
    //Component history popup
    $(document).on('click', '.historicalAirId', function(){
        var plane_id = $(this).data('plane_id');
        var log_date = $(this).data('log_date');
        var history_ids = $(this).data('history_ids');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'historicalEquipmentTime']); ?>",
            data: {plane_id:plane_id, log_date:log_date, history_ids:history_ids},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                //console.log(obj.data);
                if(obj.status == 'success') {
                    $('#historicalTimeModel .modal-body').html(obj.data);
                    $('#historicalTimeModel').modal('show');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Generate Aircraft Times PDF
    $('#downloadPdfId').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var pids = $(this).data('pids');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateAircraftTimesPdf']); ?>",
            data: {pids: pids},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    //Open pdf in new link
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                } else {
                    $('.loader').hide();
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });
});
</script>