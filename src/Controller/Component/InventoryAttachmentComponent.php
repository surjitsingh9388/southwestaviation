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
use Cake\Routing\Router;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class InventoryAttachmentComponent extends Component {
    public array $components = ['AircraftWOItemHistory', 'Authentication.Authentication'];

    protected \App\Model\Table\InventoryLocationHistoriesTable $InventoryLocationHistories;
    protected \App\Model\Table\InventoryLocationsTable $InventoryLocations;
    protected \App\Model\Table\InventoryItemHistoriesTable $InventoryItemHistories;
    protected \App\Model\Table\InventoryItemsTable $InventoryItems;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function getAttachedFiles($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invlocatt.id, invlocatt.file_name, invlocatt.file_size, u.full_name as uploaded_by, invlocatt.created FROM inventory_attachments invlocatt join users u on invlocatt.uploaded_by = u.id WHERE invlocatt.inventory_id = :inventory_id and invlocatt.status = "1"',
                                ['inventory_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');

        /*$attachedfiles = $connection
                            ->newQuery()
                            ->select('id, file_name, file_size, uploaded_by, created')
                            ->from('inventory_attachments')
                            ->where(['inventory_id' => $id, 'status'=>1], ['created' => 'datetime'])
                            ->order(['id' => 'ASC'])
                            ->execute()
                            ->fetchAll('assoc');*/
        
        return $attachedfiles;
    }

    public function saveAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_attachments', [
                    'inventory_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function saveInventoryLocAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            $InventoryLocationHistoriesModel = $this->getController()->fetchTable('InventoryLocationHistories');

            $inventoryLocHistory = $InventoryLocationHistoriesModel->newEmptyEntity();
            $InventoryLocationsModel = $this->getController()->fetchTable('InventoryLocations');

            $inventorylocations = $InventoryLocationsModel->get($id);

            $inventoryLocHistory->inventory_location_id = $id;
            $inventoryLocHistory->title = 'Location '.$inventorylocations->location_name.' was updated.';
            $inventoryLocHistory->user_id = $authUserData['id'];
            $description = '';

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_location_attachments', [
                    'inventory_location_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);

                $description .= 'New Attachment File '.$postData['filenames'][$i].' Added<br/>';
            }

            $inventoryLocHistory->description = $description;
            $InventoryLocationHistoriesModel->save($inventoryLocHistory);
        }
    }

    public function deleteAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_attachments', ['id' => $id]);

        return $resp;
    }

    public function deleteInventoryLocAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_location_attachments', ['id' => $id]);

        return $resp;
    }

    public function getInventoryLocAttachments($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invlocatt.id, invlocatt.file_name, invlocatt.file_size, u.full_name as uploaded_by, invlocatt.created FROM inventory_location_attachments invlocatt join users u on invlocatt.uploaded_by = u.id WHERE invlocatt.inventory_location_id = :inventory_location_id and invlocatt.status = "1"',
                                ['inventory_location_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');
        
        return $attachedfiles;
    }

    public function getInvItemsAttachedFiles($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invlocatt.id, invlocatt.file_name, invlocatt.file_size, u.full_name as uploaded_by, invlocatt.created FROM inventory_item_attachments invlocatt join users u on invlocatt.uploaded_by = u.id WHERE invlocatt.inventory_item_id = :inventory_item_id and invlocatt.status = "1"',
                                ['inventory_item_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');
        
        
        return $attachedfiles;
    }

    public function saveInvItemsAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            $InventoryItemHistoriesModel = $this->getController()->fetchTable('InventoryItemHistories');

            $inventoryHistory = $InventoryItemHistoriesModel->newEmptyEntity();
            $InventoryItemsModel = $this->getController()->fetchTable('InventoryItems');

            $inventoryitems = $InventoryItemsModel->get($id);

            $inventoryHistory->inventory_item_id = $id;
            $inventoryHistory->title = 'Inventory Item '.$inventoryitems->part_number.' was updated';
            $inventoryHistory->user_id = $authUserData['id'];
            $description = '';

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_item_attachments', [
                    'inventory_item_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);

                $description .= 'New Attachment File '.$postData['filenames'][$i].' Added<br/>';
            }

            $inventoryHistory->description = $description;
            $InventoryItemHistoriesModel->save($inventoryHistory);
        }
    }

    public function deleteInvItemsAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_item_attachments', ['id' => $id]);

        return $resp;
    }

    public function saveInvPOAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_purchase_order_attachments', [
                    'inventory_po_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteInvPOAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_purchase_order_attachments', ['id' => $id]);

        return $resp;
    }

    public function getInvPOAttachedFiles($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invpoatt.id, invpoatt.file_name, invpoatt.file_size, u.full_name as uploaded_by, invpoatt.created FROM inventory_purchase_order_attachments invpoatt join users u on invpoatt.uploaded_by = u.id WHERE invpoatt.inventory_po_id = :inventory_po_id and invpoatt.status = "1"',
                                ['inventory_po_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');
        
        
        return $attachedfiles;
    }


    public function saveInvROAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_repair_order_attachments', [
                    'inventory_ro_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteInvROAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_repair_order_attachments', ['id' => $id]);

        return $resp;
    }

    public function getInvROAttachedFiles($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invpoatt.id, invpoatt.file_name, invpoatt.file_size, u.full_name as uploaded_by, invpoatt.created FROM inventory_repair_order_attachments invpoatt join users u on invpoatt.uploaded_by = u.id WHERE invpoatt.inventory_ro_id = :inventory_ro_id and invpoatt.status = "1"',
                                ['inventory_ro_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');
        
        
        return $attachedfiles;
    }

    public function saveInvShippingOrderAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_shipping_order_attachments', [
                    'inventory_shipping_order_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteInvShippingOrderAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_shipping_order_attachments', ['id' => $id]);

        return $resp;
    }

    public function getInvShippingOrderAttachedFiles($id){
        $connection = ConnectionManager::get('default');
        
        $attachedfiles = $connection
                            ->execute(
                                'SELECT invpoatt.id, invpoatt.file_name, invpoatt.file_size, u.full_name as uploaded_by, invpoatt.created FROM inventory_shipping_order_attachments invpoatt join users u on invpoatt.uploaded_by = u.id WHERE invpoatt.inventory_shipping_order_id = :inventory_shipping_order_id and invpoatt.status = "1"',
                                ['inventory_shipping_order_id' => $id],
                                ['created' => 'datetime']
                            )
                            ->fetchAll('assoc');
        
        
        return $attachedfiles;
    }

    public function uploadSelectedFilesToServer($postData, $filelocation, $foldername){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');

        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td>'.$filesize.'</td>
                <td class="text-uppercase">'.date("d-M-Y").'</td>
                <td>'.$authUserData['full_name'].'</td>
                <td><i class="fa fa-times deleteattachment" title="Remove File"></i></td>
            </tr>';
        }

        return $tblrow;
    }

    public function uploadInvPORecFilesToServer($postData, $filelocation, $foldername){
        $fldname = $postData['ids'];

        $attachment = $postData[$fldname]; 
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $fldarr = explode('inventoryattachment', $fldname);
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames'.$fldarr[1].'[]" value="'.$name.'">
                <input type="hidden" name="filesize'.$fldarr[1].'[]" value="'.$filesize.'">
                </td>
                <td><i class="fa fa-times deleteattachment" title="Remove File"></i></td>
            </tr>';
        }

        return $tblrow;
    }

    public function saveInvCustomerAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_customer_attachments', [
                    'inventory_customer_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteInvCustomerAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('inventory_customer_attachments', ['id' => $id]);

        return $resp;
    }

    public function uploadCustWOFilesToServer($postData, $filelocation, $foldername, $tableName=''){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td></td>
            </tr>';
            if(!empty($tableName)){
                $connection = ConnectionManager::get('default');

                $woitemphotoes = $connection
                        ->execute(
                            'SELECT max(position) as positions FROM '.$tableName.' WHERE wo_item_id = :wo_item_id and status = "1" limit 1',
                            ['wo_item_id' => $postData['wo_item_id']],
                            ['created' => 'datetime']
                        )
                        ->fetch('assoc');
                
                $position = !empty($woitemphotoes) ? $woitemphotoes['positions']+1 : '1';
                
                $attachmentdata = [
                                    'wo_item_id'=>$postData['wo_item_id'],
                                    'file_name' => $name,
                                    'file_size' => $filesize,
                                    'position'=>$position,
                                    'added_by' => $authUserData['id'],
                                    'created_at'=>date("Y-m-d H:i:s")
                                ];

                $resp = $connection->insert($tableName, $attachmentdata, ['created' => 'datetime']);
                $inserted_id = $resp->lastInsertId($tableName);

                $history_title = ($tableName == 'customer_aircraft_wo_item_photo') ? 'Item Photo was uploaded.' : 'Item File was uploaded.';
                
                $this->AircraftWOItemHistory->saveWOItemTabAttachmentDataToHistory($attachmentdata, $history_title);

                $trclassName = 'aircraft-wo-item-file';
                if($tableName == 'customer_aircraft_wo_item_photo'){
                    $trclassName = 'aircraft-wo-item-photo';
                }
                $tblrow = '<tr class="'.$trclassName.'" data-val="'.$inserted_id.'">
                    <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                    <input type="hidden" name="filenames[]" value="'.$name.'">
                    <input type="hidden" name="filesize[]" value="'.$filesize.'">
                    </td>
                    <td></td>
                </tr>';
            }
        }

        return $tblrow;
    }

    public function saveAircraftInfoMedia($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('customer_otc_aircraft_attachments', [
                    'aircraft_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                    'created'=>new \Cake\I18n\FrozenTime('now'),
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteAircraftMediaAttachment($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('customer_otc_aircraft_attachments', ['id' => $id]);

        return $resp;
    }

    public function uploadAircraftInfoFilesToServer($postData, $filelocation, $foldername){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');

        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td>'.$filesize.'</td>
                <td class="text-uppercase">'.date("d-M-Y").'</td>
                <td>'.$authUserData['full_name'].'</td>
                <td><i class="fa fa-times deleteAircraftAttachment" title="Remove File"></i></td>
            </tr>';
        }

        return $tblrow;
    }

    public function saveWOOSRVendorMedia($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('customer_aircraft_wo_osr_vendor_attachments', [
                    'osr_vendor_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                    'created'=>new \Cake\I18n\FrozenTime('now'),
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteWOOSRVendorMedia($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('customer_aircraft_wo_osr_vendor_attachments', ['id' => $id]);

        return $resp;
    }

    public function uploadWOOSRVendorMediaToServer($postData, $filelocation, $foldername){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');

        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                            <td class="document-name">
                                <span class="document-management-icon '.$iconcss.'"></span>
                                <a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                                <input type="hidden" name="filenames[]" value="'.$name.'">
                                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                            </td>
                            <td>'.$filesize.'</td>
                            <td class="text-uppercase">'.date("d-M-Y").'</td>
                            <td>'.$authUserData['full_name'].'</td>
                            <td><i class="fa fa-times deleteWOOSRVendorMedia" title="Remove File"></i></td>
                        </tr>';
        }

        return $tblrow;
    }

    public function saveWOOSRPurchaseOrderMedia($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');
            $authUserData = $this->Authentication->getResult()->getData();

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('customer_aircraft_wo_osr_info_po_attachments', [
                    'osr_info_po_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $authUserData['id'],
                    'status' => "1",
                    'created'=>new \Cake\I18n\FrozenTime('now'),
                ], ['created' => 'datetime']);
            }
        }
    }

    public function deleteWOOSRPurchaseOrderMedia($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('customer_aircraft_wo_osr_info_po_attachments', ['id' => $id]);

        return $resp;
    }

    public function uploadWOOSRPurchaseOrderMediaToServer($postData, $filelocation, $foldername){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');

        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                            <td class="document-name">
                                <span class="document-management-icon '.$iconcss.'"></span>
                                <a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                                <input type="hidden" name="filenames[]" value="'.$name.'">
                                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                            </td>
                            <td>'.$filesize.'</td>
                            <td class="text-uppercase">'.date("d-M-Y").'</td>
                            <td>'.$authUserData['full_name'].'</td>
                            <td><i class="fa fa-times deleteWOOSRPurchaseOrderMedia" title="Remove File"></i></td>
                        </tr>';
        }

        return $tblrow;
    }

    public function deleteAttachmentFileFromTable($id, $tableName){
        $connection = ConnectionManager::get('default');

        $results = $connection->execute(
                                        'SELECT * FROM '.$tableName.' WHERE id = :attachment_id',
                                        ['attachment_id' => $id],
                                        ['created' => 'datetime']
                                    )->fetch('assoc');

        $history_title = ($tableName == 'customer_aircraft_wo_item_photo') ? 'Item Photo was deleted.' : 'Item File was deleted.';
        $history_description = '`'.$results['file_name'].'` deleted from work order item.';
        $attachmentdata = (object)$results;
        $this->AircraftWOItemHistory->saveWOItemTabDeletedDataToHistory($attachmentdata, $history_title, $history_description);

        $resp = $connection->delete($tableName, ['id' => $id]);

        return $resp;
    }

    public function uploadInventoryToolFilesToServer($postData, $filelocation, $foldername, $tableName=''){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        $authUserData = $this->Authentication->getResult()->getData();
        
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
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td></td>
            </tr>';
            if(!empty($tableName)){
                $connection = ConnectionManager::get('default');

                $resp = $connection->insert($tableName, [
                    'tool_id'=>$postData['tool_id'],
                    'file_name' => $name,
                    'file_size' => $filesize,
                    'added_by' => $authUserData['id'],
                ], ['created' => 'datetime']);
                $inserted_id = $resp->lastInsertId($tableName);

                $trclassName = 'inventory-tool-file';
                if($tableName == 'customer_aircraft_wo_item_photo'){
                    $trclassName = 'inventory-tool-photo';
                }
                $tblrow = '<tr class="'.$trclassName.'" data-val="'.$inserted_id.'">
                    <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                    <input type="hidden" name="filenames[]" value="'.$name.'">
                    <input type="hidden" name="filesize[]" value="'.$filesize.'">
                    </td>
                    <td></td>
                </tr>';
            }
        }

        return $tblrow;
    }

}