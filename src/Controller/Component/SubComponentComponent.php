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
use Cake\Routing\Router;

class SubComponentComponent extends Component {
    
    public function getSubComps($planeId = null, $id = null) {
        $subCompModel = TableRegistry::get('SubComponents');
        $subComps = $subCompModel->find('all')->where(['SubComponents.id' => $id, 'SubComponents.plane_id' => $planeId])->select(['id', 'title'])->enableHydration(false)->toArray();

        $results = array();
        foreach ($subComps as $key => $value) {
            $results[$value['id']] = $value['title'];
        }
        return $results;
    }

    public function subCompName($id=null) {
        $subCompModel = TableRegistry::get('SubComponents');
        return $subCompModel->get($id)->title;
    }
        
}