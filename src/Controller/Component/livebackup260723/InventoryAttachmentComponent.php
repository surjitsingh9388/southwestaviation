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

class InventoryAttachmentComponent extends Component {
    public $components = ['Auth'];

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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_attachments', [
                    'inventory_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
        }
    }

    public function saveInventoryLocAttachment($id, $postData){
        if(isset($postData['filenames']) && !empty($postData['filenames'])){
            $connection = ConnectionManager::get('default');

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_location_attachments', [
                    'inventory_location_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_item_attachments', [
                    'inventory_item_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
                    'status' => "1",
                ], ['created' => 'datetime']);
            }
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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_purchase_order_attachments', [
                    'inventory_po_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_repair_order_attachments', [
                    'inventory_ro_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_shipping_order_attachments', [
                    'inventory_shipping_order_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
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
        
        $temp = $postData['file_name']['tmp_name'];
        $name = $postData['file_name']['name'];
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
            $filesize = ($postData['file_name']['size']/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td>'.$filesize.'</td>
                <td class="text-uppercase">'.date("d-M-Y").'</td>
                <td>'.$this->Auth->user('full_name').'</td>
                <td><i class="fa fa-times deleteattachment" title="Remove File"></i></td>
            </tr>';
        }

        return $tblrow;
    }

    public function uploadInvPORecFilesToServer($postData, $filelocation, $foldername){
        $fldname = $postData['ids'];

        $temp = $postData[$fldname]['tmp_name'];
        $name = $postData[$fldname]['name'];
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
            $filesize = ($postData[$fldname]['size']/1000).' KB';
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

            for($i=0; $i<count($postData['filenames']); $i++){
                $resp = $connection->insert('inventory_customer_attachments', [
                    'inventory_customer_id'=>$id,
                    'file_name' => $postData['filenames'][$i],
                    'file_size' => $postData['filesize'][$i],
                    'uploaded_by' => $this->Auth->user('id'),
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

}