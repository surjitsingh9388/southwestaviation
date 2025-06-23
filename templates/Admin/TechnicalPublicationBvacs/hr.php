<div class="content sliding">
    <div class="outerWrapper">  
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder pt10">
                    <div class="row" style="margin-left:0px; margin-right:0px;">
                        <?php 
                        echo $this->element('TechnicalPublications/create_technical_publications'); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
echo $this->element('TechnicalPublications/popup_file_list');
?>
