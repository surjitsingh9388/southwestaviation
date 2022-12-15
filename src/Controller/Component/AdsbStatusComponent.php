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

class AdsbStatusComponent extends Component {
    /**
     * GetAdsbStatus method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getAdsbStatus($id = null) {
        $adsbModel = TableRegistry::get('AdsbStatus');
        $adsbRes = $adsbModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->where(['AdsbStatus.status'=>'active'])->order(['AdsbStatus.title'=>'ASC'])->toArray();
        return $adsbRes;
    }

    //Get AD/SB title
    public function adsbTitle($adsbId)
    {
        $adsbModel = TableRegistry::get('AdsbStatus');
        $res = '';
        if(!empty($adsbId)) {
            $result = $adsbModel->find()->where(['AdsbStatus.id'=>$adsbId])->select(['AdsbStatus.id', 'AdsbStatus.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }
    
}