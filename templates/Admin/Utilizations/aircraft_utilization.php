<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
use Cake\Routing\Router;
?>

<div class="content sliding">
    <div class="outerWrapper">
        <h2 class="heading border-btm">Aircraft Equipment Utilizations - <?php echo $results['plane_code']; ?></h2>
        <div class="page-content mt-35">        
            <div class="table-responsive">
                <table id="aircraftUtilization" class="table mb-0">
                    <thead>
                        <tr>
                            <th width="40%">Equipment Type</th>
                            <th width="20%">Hours</th>
                            <th width="20%">Cycles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($results['airframe_components'] as $key => $value) {
                            if($value['log_book'] == 'Airframe') {
                                $component = $value['log_book'];
                            } else {
                                $component = $value['log_book'].' '.$value['position'];
                            }

                            if(!empty($value['utilizations'][0]['id'])) {
                                $utilId = $value['utilizations'][0]['id'];
                            } else {
                                $utilId = "";
                            }

                            if(!empty($value['utilizations'][0]['hours'])) {
                                $hours = $value['utilizations'][0]['hours'];
                            } else {
                                $hours = "";
                            }

                            if(!empty($value['utilizations'][0]['cycles'])) {
                                $cycles = $value['utilizations'][0]['cycles'];
                            } else {
                                $cycles = "";
                            }
                        ?>
                            <tr class="utilRCls" data-planeid="<?php echo $results['id']; ?>" data-compid="<?php echo $value['id']; ?>" data-utilid="<?php echo $utilId; ?>">
                                <td><?php echo $component; ?></td>
                                <td class="utilUpHour<?php echo $value['id']; ?>"><?php echo $hours; ?></td>
                                <td class="utilUpCycles<?php echo $value['id']; ?>"><?php echo $cycles; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Utilization popup to update values -->
<div id="utilResModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="utilTimeFrm" method="POST">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Utilization - <span class="utilCompName"></span></h4>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span id="errorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?php
                    if((!empty($actionItems) && ($actionItems['action']['action_add']==1 || $actionItems['action']['action_edit']==1)) || $sessionUser['id'] == 1) {
                    ?>
                    <input type="button" value="Save" class="btn btn-primary" id="saveUtilBtn">
                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Utilization popup to update values -->

<?php //echo $this->element('utilization_popup'); ?>

<script>
$(document).ready(function() {
    //Utilization popup
    $(document).on('click', '.utilRCls', function() {
        var planeId = $(this).data('planeid');
        var compId = $(this).data('compid');
        var utilId = $(this).data('utilid');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Utilizations', 'action'=>'getUtilRecord']); ?>",
            data: {planeId: planeId, compId: compId, utilId: utilId},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                //console.log(obj.data);
                if(obj.status == 'success') {
                    $('#utilResModel .modal-body').html(obj.data);
                    $('#utilResModel .utilCompName').html(obj.comp);
                    $('#utilResModel').modal('show');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Save Report Time details
    $(document).on('click', '#saveUtilBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        //if($('#utilTimeFrm').valid()) {
            var data = $('#utilTimeFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Utilizations', 'action'=>'addUtilization']); ?>",
                type : 'post',
                data : data,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.utilUpHour'+obj.data.compId).html(obj.data.hours);
                        $('.utilUpCycles'+obj.data.compId).html(obj.data.cycles);
                        $('#errorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        location.reload(true);
                    } else {
                        $('#errorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }                    
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    $('#utilResModel').modal('hide');
                },
                complete: function () {
                    $('#utilResModel').modal('hide');
                }
            });
        //}
    });

    //Validation on report time form
    $("#utilTimeFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'hours': {
                required: true
            },
            'cycles': {
                required: true
            }
        },
        messages: {
            'hours': {
                required: "Please enter hours."
            },
            'cycles': {
                required: "Please enter cycles."
            }
        }
    });
});
</script>