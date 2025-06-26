<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
//pr($sessionUser);die;
?>
<div class="col-md-12 upload-file-area col-sm-12 col-xs-12">
    <div >
        <!--div class="col-md-4">
            <div class="technical_publication_create_box" onclick="myFunction()">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <div>Create</div>
            </div>
            <div id="create_btn_dropdown" class="create_btn_dropdown">
                <a href="javascript:void(0);" data-val="folder"><i class="fa fa-folder-o" aria-hidden="true"></i> Folder</a>
                <a href="javascript:void(0);" data-val="document"><i class="fa fa-file-o" aria-hidden="true"></i> Document</a>
            </div>
        </div-->
        <?php if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){ ?>
            <div class="tech-btn">
        <div >
            <div class="technical_publication_folder_box" data-val="folder">
                <i class="fa fa-folder-o" aria-hidden="true"></i>
                <div>Create folder</div>
            </div>
        </div>
     

        <div >
            <div class="technical_publication_upload_box" onclick="$('#tech_publication_attachment').trigger('click'); return false;">
                <input type="hidden" name="folderpath" id="folderpath" value="<?php echo $folderpath; ?>" />
                <input type="hidden" name="pageaction" id="pageaction" value="<?php echo $action; ?>" />
                <input type="hidden" name="pageparams" id="pageparams" value="<?php echo implode('/',$params); ?>" />
                <input type="hidden" name="main_page_id" id="main_page_id" value="<?php echo $main_page_id; ?>" />
                <input type="hidden" name="subpage_id" id="subpage_id" value="<?php echo $subpage_id; ?>" />
                <i class="fa fa-upload" aria-hidden="true"></i>
                <div>Upload or drop</div>
            </div>
            <input type="file" name="files[]" id="tech_publication_attachment" style="display:none;" multiple="">
        </div>
           </div>
        <?php } ?>
    </div>
    <!-- <div class="col-md-8"></div> -->
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <h3><?php echo $heading.' Publications'; ?></h3>
    <h5 class="techpubl_h5">
        <?php 
        $actionurl = '';
        $count = count($folderarr)-1;
        if($count > 0){
            foreach($folderarr as $keys=>$folders){
                $actionurl .= empty($actionurl) ? $folders : DS.$folders;
                $url = $this->Url->build(array('controller' => $controllerName, 'action' => $actionurl));
                if($keys < $count){
            ?>
                <a href="<?php echo $url; ?>" class="tech_publ_link"><?php echo $keys == 0 ? $heading : $folders; ?></a> <?php echo DS; ?>
            <?php
                }else{
                    echo $count == 0 ? $heading : $folders;
                }
            }
        }
        ?>
    </h5>
    <div >
        <table class="table mb-0 table-striped table-bordered table-responsive" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Who can access</th>
                    <th>Created By</th>
                    <th>Modified</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="tbl_tech_publication">
                <?php
                foreach($technicalPublications as $techpubl){
                    $url = $this->Url->build(array('controller' => $controllerName, 'action' => $folderpath.DS.$techpubl['folder_file_name']));

                    $filelocation = $mainfolder.DS.$folderpath.DS.$techpubl['folder_file_name'];
                ?>  
                <tr>
                    <td class="tech_publ_link">
                        <?php if(!empty($techpubl['is_folder'])){ ?>
                            <span class="document-management-icon icon-folder"></span>&nbsp;
                            <?php 
                            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1){
                                echo $this->Html->link($techpubl['folder_file_name'], array('controller' => $controllerName, 'action' => $folderpath.DS.$techpubl['folder_file_name'])); 
                            }else{
                                echo $techpubl['folder_file_name'];
                            }
                            ?>
                        <?php 
                        }else{ 
                            $ext = substr(strrchr($techpubl['folder_file_name'] , '.'), 1);
                            $ext = strtolower($ext);

                            $iconcss = '';
                            $imageextension = ['apng', 'png', 'avif', 'gif', 'jpg', 'jpeg', 'jfif', 'pjpeg', 'pjp', 'svg', 'webp'];
                            if($ext == 'pdf'){
                                $iconcss = 'icon-pdf';
                            }else if($ext == 'doc' || $ext == 'docx'){
                                $iconcss = 'icon-doc';
                            }else if($ext == 'xls' || $ext == 'xlsx'){
                                $iconcss = 'icon-excel';
                            }else if($ext == 'txt'){
                                $iconcss = 'icon-text';
                            }else if(in_array($ext, $imageextension)){
                                $iconcss = 'icon-image';
                            }else{
                                $iconcss = 'icon-generic';
                            }
                            
                            $filelocation = DS.$mainfolder.DS.$folderpath.DS.$techpubl['folder_file_name'];
                            
                            echo '<span class="document-management-icon '.$iconcss.'"></span>'."&nbsp;&nbsp;";
                            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1){
                                echo $this->Html->link($techpubl['folder_file_name'], $filelocation, ['target'=>'_blank']);
                            }else{
                                echo $techpubl['folder_file_name'];
                            }
                        }    
                        ?>
                    </td>
                    <td>
                        <?php 
                        $permission_user_count = count($techpubl['permission_users']);
                        $permission_user_count = !empty($permission_user_count) ? $permission_user_count.' members' : '0 member'; 
                        $permission_users = !empty($permission_user_count) ? implode("<br/>", $techpubl['permission_users']) : '';
                        ?>
                        
                        <span data-toggle="tooltip" data-placement="top" data-html="true" title="<?php echo $permission_users; ?>">
                        <?php echo $permission_user_count; ?>
                        </span>
                    </td>
                    <td><?php echo $techpubl['users']['full_name']; ?></td>
                    <td><?php echo $techpubl['created_at']; ?></td>
                    <td>
                        <div class="split-btn">
                            <button class="btn-dropdown btn-default">Action</button>
                            <button class="icon-part dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-caret-down"></i>
                            </button>                               
                            <ul class="dropdown-content dropdown-menu">
                                <?php 
                                if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1){  
                                if(!empty($techpubl['is_folder'])){
                                ?>
                                <li>
                                    <a href="<?php echo $this->Url->build(array('controller' => $controllerName, 'action' => $folderpath.DS.$techpubl['folder_file_name'])); ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                <?php 
                                }else{
                                ?>
                                    <a href="<?php echo $this->Url->build($filelocation); ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                <?php
                                }
                                ?>
                                </li>
                                
                                <li>
                                    <a href="javascript:void(0);" class="tech-publ-download-folder" data-val="<?php echo $filelocation; ?>" is_folder="<?php echo $techpubl['is_folder']; ?>"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                </li>
                                <?php 
                                } 
                                ?>
                                </li>
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" data-val="<?php echo $techpubl['id']; ?>" folder-path="<?php echo $folderpath; ?>" class="tech_publ_delete_btn"><i class="fa fa-trash-o"></i> Delete</a>
                                </li>
                                <?php } ?>
                                <?php
                                if($sessionUser['role_id'] == '1'){
                                ?>
                                <li>
                                    <a href="javascript:void(0);" class="tech_publ_permission" data-val="<?php echo $techpubl['id']; ?>"><i class="fa fa-lock" aria-hidden="true"></i> Access</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                       
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
    var deleteTechPublicationURL = "<?php echo $this->Url->build(['controller'=>$controllerName, 'action'=>'delete']); ?>";
    var uploadTechPublicationFileURL = "<?php echo $this->Url->build(['controller'=>$controllerName, 'action'=>'uploadTechPublicationFile']); ?>";
    var techPublPermissionPopupURL = "<?php echo $this->Url->build(['controller'=>'TechnicalPublicationSwas', 'action'=>'techPublPermissionPopup']); ?>";
    var saveTechPublPermissionURL = "<?php echo $this->Url->build(['controller'=>'TechnicalPublicationSwas', 'action'=>'saveTechPublPermission']); ?>";
    var downloadFolderURL = "<?php echo BASE_URL.$this->Url->build(['controller'=>'TechnicalPublicationSwas', 'action'=>'downloadFolder']); ?>";
</script>
<?php 
echo $this->Html->css('technical_publications');
echo $this->Html->script('technical_publications'); 
?>
