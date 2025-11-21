<?php echo $this->Html->script('jquery.tokeninput'); ?>
<?php echo $this->Html->css('token-input-facebook.css'); ?>

<script>
    $(function() {
        var newToken;
        $('#catalogtags').tokenInput([], {
            theme: "facebook",
            hintText: "Enter a tag...",
            preventDuplicates: true,
            zindex: '9999',
            onAdd: function(item){
                newToken = null;
            },
            onReady: function(){          
                $("#token-input-catalogtags").keyup(function(event) {
                    
                    if (event.keyCode === 13 || event.keyCode === 9 || event.keyCode === 188) // return, tab, or comma
                    {
                    if (newToken) $('#catalogtags').tokenInput("add", {id: newToken, name: newToken});
                    }
                    
                    newToken = $("tester").text();
                });
            }
        }); 
    }); 
</script>

<div id="applyTagsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Apply Inventory Tags</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Tags
                                <i container="body" placement="right" class="fa fa-info-circle bootstrap-input-tooltip" style="margin-left: 5px;" data-toggle="tooltip" title="Type tag and hit enter key"></i>
                            </label>
                            <div class="col-sm-9 form-label-input-wrapper">
                                <?php echo $this->Form->control('catalogtags', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div  class="clearfix" style="margin-top: 10px;">
                    <div  class="col-md-7 col-sm-7">
                        Add to selected items or replace existing tags?
                    </div>
                    <div  class="col-md-5 col-sm-5">
                        <div  class="btn-group pull-right">
                            <label  class="btn btn-default tagsbtnactivity active" data-val='1' style="cursor: pointer;">Add</label>
                            <label  class="btn btn-default tagsbtnactivity" data-val='2' style="cursor: pointer;">Replace</label>
                        </div>
                    </div>
                    <div  class="col-sm-12" style="height: 30px;">
                        <p  class="error-text" style="display:none;">Selected items will have all of their existing tags replaced with the above tags.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary applyCatalogTags">Apply</button>
            </div>
        </div>
    </div>
</div>