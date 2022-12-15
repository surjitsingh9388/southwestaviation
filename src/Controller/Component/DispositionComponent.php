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

class DispositionComponent extends Component {
    /**
     * GetDispositions method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getDispositions($id = null) {
        $dispModel = TableRegistry::get('Dispositions');
        $dispRes = $dispModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->toArray();
        return $dispRes;
    }

    //Get disposition title
    public function dispTitle($dispId)
    {
        $dispModel = TableRegistry::get('Dispositions');
        $res = '';
        if(!empty($dispId)) {
            $result = $dispModel->find()->where(['Dispositions.id'=>$dispId])->select(['Dispositions.id', 'Dispositions.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }
    
}