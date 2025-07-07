<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">  
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('PTO Requests', ['action' => 'index']); ?></h2>
            <div class="btnWrap mb-5">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 userptorequestssave']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder pt10">
                    <div class="row" style="margin-left:0px; margin-right:0px;">
                        <?php 
                        echo $this->element('PTORequests/create_pto_requests'); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var savePTORequestsURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'savePTORequests']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php 
echo $this->Html->script('user_pto_requests');
?>