<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use ZipArchive;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class TechnicalPublicationComponent extends Component {
    public array $components = ['Timezone', 'Authentication.Authentication'];

    protected \App\Model\Table\TechnicalPublicationsTable $TechnicalPublications;
    protected \App\Model\Table\UsersTable $Users;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function createNewFolder($mainfolder, $main_page_id, $subpage_id, $postData, $params, $action){
        $authUserData = $this->Authentication->getResult()->getData();
        
        if(count($params) > 0){
            $folder_name = $params[count($params)-1];
            $techpublication = $this->getTechPublByFolderName($folder_name);
            $parent_id = $techpublication->id;
        }else{
            $parent_id = 0;
        }
        $folder_file_name = $postData['folder_name'];
        //pr($postData);die;
        //$action = $this->request->getParam['action'];
        $this->TechnicalPublications = $this->getController()->fetchTable('TechnicalPublications');
        if(!empty($postData['technical_publication_id'])){
            $technicalPublications = $this->TechnicalPublications->get($postData['technical_publication_id']);
        }else{
            $technicalPublications = $this->TechnicalPublications->newEmptyEntity();
        }
        $paramsstring = implode('/', $params);
        if(!empty($postData['technical_publication_id'])){   
            $oldname = $technicalPublications->folder_file_name;
            $oldfoldername = !empty($paramsstring) ? $paramsstring.DS.$oldname : $oldname;
            $old_full_folder_path = WWW_ROOT.$mainfolder.DS.$action.DS.$oldfoldername;
        }
        $folderpath = !empty($paramsstring) ? $paramsstring.DS.$folder_file_name : $folder_file_name;
        
        $full_folder_path = WWW_ROOT.$mainfolder.DS.$action.DS.$folderpath;
        
        if(!empty($postData['technical_publication_id'])){
            $isdircreated = rename($old_full_folder_path, $full_folder_path);
        }else{
            $isdircreated = '';
            if(!file_exists($full_folder_path)) {
                $isdircreated = mkdir($full_folder_path, 0755, true);
            }
        }
        
        if($isdircreated){
            $is_folder = '1';
            
            $technicalPublications->main_page_id        = $main_page_id;
            $technicalPublications->subpage_id          = $subpage_id;
            $technicalPublications->parent_id           = $parent_id;
            $technicalPublications->is_folder           = $is_folder;
            $technicalPublications->folder_file_name    = $folder_file_name;
            $technicalPublications->added_by            = $authUserData['id'];
            $technicalPublications->created_at          = new \Cake\I18n\FrozenTime('now');

            if($this->TechnicalPublications->save($technicalPublications)){
                $redirecturl = !empty($postData['technical_publication_id']) ? $action.'/'.$paramsstring : $action.'/'.$folderpath;
            }else{
                $redirecturl = '';
            }
            return $redirecturl;
        }else{
            $redirecturl = '';
            return $redirecturl;
        }
        
    }
    
    public function getTechnicalPublications($main_page_id, $subpage_id, $parent_folder_name){
        $this->TechnicalPublications = $this->getController()->fetchTable('TechnicalPublications');
        $this->Users = $this->getController()->fetchTable('Users');

        $authUserData = $this->Authentication->getResult()->getData();
        $user_id = $authUserData['id'];

        $whereArr = ['main_page_id'=>$main_page_id, 'subpage_id'=>$subpage_id];
        if($authUserData['role_id'] != '1' && !empty($user_id)){
            $whereArr = ['main_page_id'=>$main_page_id, 'subpage_id'=>$subpage_id, 'OR'=>[['TechnicalPublications.added_by' => $user_id], ['FIND_IN_SET('.$user_id.', TechnicalPublications.permission_user_ids)']]];
        }
        
        if(!empty($parent_folder_name)){
            $parentdet = $this->TechnicalPublications->find('all')->where(['folder_file_name'=>$parent_folder_name])->select(['id'])->first();
            $parent_id = $parentdet->id;
        }else{
            $parent_id = 0;
        }
        $whereArr['parent_id'] = $parent_id;
        
        $technicalPublicationdata = $this->TechnicalPublications->find('all')
                                                    ->where($whereArr)
                                                    ->select($this->TechnicalPublications)
                                                    ->select(['users.full_name'])
                                                    ->join([
                                                        'users'=>[
                                                            'table'=>'users',
                                                            'type'=>'INNER',
                                                            'conditions'=>'TechnicalPublications.added_by = users.id'
                                                        ]
                                                    ]);
        $technicalPublications = [];
        foreach($technicalPublicationdata as $key=>$row){
            $usernamearr = [];
            $technicalPublications[] = $row;

            if(!empty($row['permission_user_ids'])){
                $useridarr = explode(',', $row['permission_user_ids']);
                $userarr = $this->Users->find('all')->where(['id IN'=> $useridarr])->select('full_name');
                
                foreach($userarr as $users){
                    $usernamearr[] = $users['full_name'];
                }
            }
            $technicalPublications[$key]['permission_users'] = $usernamearr;
        }
        return $technicalPublications;
    }

    public function getTechPublByFolderName($name){
        $this->TechnicalPublications = $this->getController()->fetchTable('TechnicalPublications');
        $technicalPublications = $this->TechnicalPublications->find('all')
                                                    ->where(['folder_file_name'=>$name])
                                                    ->select($this->TechnicalPublications)
                                                    ->first();

        return $technicalPublications;
    }

    public function deleteFolderFile($mainfolder, $postData){
        $this->TechnicalPublications = $this->getController()->fetchTable('TechnicalPublications');

        $id = $postData['id'];
        $is_deleted = 0;
        $technicalPublications = $this->TechnicalPublications->get($id);
        if ($this->TechnicalPublications->deleteAll(['TechnicalPublications.id' => $id])) {
            $folderpath = $postData['folder_path'];
            $path = WWW_ROOT.$mainfolder.DS.$folderpath.DS.$postData['delete_folder_name'];
            if(!empty($technicalPublications['is_folder'])){
                $this->removeDirectory($path);
            }else{
                unlink($path);
            }
            $is_deleted = 1;
        }
        return $is_deleted;
    }

    public function removeDirectory($path){
        $files = glob($path.DS.'*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
    }

    public function uploadTechPublicationAttachment($mainfolder, $postData){
        if(!empty($postData['file_name'])) 
        {
            $attachment = $postData['file_name']; 
            $isvalidfile = 1;
            $arr_ext = array('pdf','txt');
            $folderpath = $postData['folderpath'];
            
            $name = $attachment->getClientFilename();
            $type = $attachment->getClientMediaType();
            $size = $attachment->getSize();
            $temp = $attachment->getStream()->getMetadata('uri');
            
            $ext = substr(strrchr($name , '.'), 1);
            
            if($isvalidfile){
                $filelocation = WWW_ROOT.$mainfolder.DS.$folderpath.DS.$name;
                $is_file_uploaded = $this->uploadSelectedFilesToServer($postData, $filelocation);
                
                if(!empty($is_file_uploaded)){
                    $result = array('status'=>'success', 'message'=>"Saved successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
            }
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
        }
        return $result;
    }

    public function uploadSelectedFilesToServer($postData, $filelocation){
        $this->TechnicalPublications = $this->getController()->fetchTable('TechnicalPublications');
        $authUserData = $this->Authentication->getResult()->getData();

        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        
        $attachment = $postData['file_name']; 
        $name = $attachment->getClientFilename();
        $type = $attachment->getClientMediaType();
        $size = $attachment->getSize();
        $temp = $attachment->getStream()->getMetadata('uri');
        $ext = substr(strrchr($name , '.'), 1);
        
        $iconcss = '';
        if($ext == 'pdf'){
            $iconcss = 'icon-pdf';
        }else if($ext == 'doc' || $ext == 'docx'){
            $iconcss = 'icon-doc';
        }else if($ext == 'xls' || $ext == 'xlsx'){
            $iconcss = 'icon-excel';
        }else if($ext == 'txt'){
            $iconcss = 'icon-text';
        }else{
            $iconcss = 'icon-generic';
        }
        
        $is_file_uploaded = 0;
        if(move_uploaded_file($temp, $filelocation)) {
            $is_file_uploaded = 1;
            $params = !empty($postData['params']) ? explode('/',$postData['params']) : [];
            
            if(!empty($params) && count($params) > 0){
                $folder_name = $params[count($params)-1];
                $techpublication = $this->getTechPublByFolderName($folder_name);
                $parent_id = $techpublication->id;
            }else{
                $parent_id = 0;
            }
            $main_page_id = $postData['main_page_id'];
            $subpage_id = $postData['subpage_id'];

            $technicalPublications = $this->TechnicalPublications->newEmptyEntity();

            $technicalPublications->main_page_id    = $main_page_id;
            $technicalPublications->subpage_id      = $subpage_id;
            $technicalPublications->parent_id       = $parent_id;
            $technicalPublications->folder_file_name = $name;
            $technicalPublications->added_by = $authUserData['id'];
            $technicalPublications->created_at = new \Cake\I18n\FrozenTime('now');

            $this->TechnicalPublications->save($technicalPublications);

        }

        return $is_file_uploaded;
    }

    public function getUserDropdownList(){
        $this->Users = $this->getController()->fetchTable('Users');
        $userdet = $this->Users->find('all')->where(['suspended'=>'0', 'role_id !='=>'1'])->select(['Users.id', 'Users.full_name']);
        
        $userlist = [];
        foreach($userdet as $users){
            $userlist[$users['id']] = $users['full_name'];
        }

        return $userlist;
    }

    public function downloadFolder($is_folder, $folder){
        if(!empty($is_folder)){
            $zipArchive = new ZipArchive();
            if ($zipArchive->open($folder.'.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
                die ("An error occurred creating your ZIP file.");
            }
            $this->createZip($is_folder, $zipArchive, $folder);
            @$zipArchive->close();
        }
        $this->downloadZipFolder($is_folder, $folder);
    }

    public function createZip($is_folder, $zipArchive, $folder)
    {
        if (is_dir($folder)) {
            if ($f = opendir($folder)) {
                $files = glob($folder.DS.'*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            $zipArchive->addFile($file) or die ("ERROR: Could not add the file $file");
                        }
                    } else {
                        if (is_dir($file)) {
                            if ($file != '' && $file != '.' && $file != '..') {
                                $zipArchive->addEmptyDir($file);
                                $folder = $file . '/';
                                $this->createZip($is_folder, $zipArchive, $folder);
                            }
                        }
                      }
                }
                closedir($f);
            } else {
                exit("Unable to open directory " . $folder);
            }
        } else {
            exit($folder . " is not a directory.");
        }
    }

    public function downloadZipFolder($is_folder, $folder_name){
        if (file_exists($folder_name)) {
            $folderpatharr = explode('/', $folder_name);
            $archive_file_name = !empty($is_folder) ? $folder_name.'.zip' : $folder_name;
            $zipfoldername = !empty($is_folder) ? $folderpatharr[count($folderpatharr)-1].'.zip' : $folderpatharr[count($folderpatharr)-1];
            header("Content-type: application/zip"); 
            header("Content-Disposition: attachment; filename=$zipfoldername");
            header("Content-length: " . filesize($archive_file_name));
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            readfile("$archive_file_name");
            exit();
        }
    }
}

?>