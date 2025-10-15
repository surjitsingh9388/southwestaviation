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
use Cake\Datasource\FactoryLocator;


class AirframeMocComponent extends Component {
    protected \App\Model\Table\AirframeMocsTable $AirframeMocs;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetAirframeMocs method
     * This function is used to get list of all moc.
     *
     * @return array.
     */
    public function getMocs($id = null) {
        $mocsModel = $this->getController()->fetchTable('AirframeMocs');
        $mocRes = $mocsModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->toArray();
        return $mocRes;
    }

    //Get moc title
    public function mocTitle($mocId)
    {
        $mocsModel = $this->getController()->fetchTable('AirframeMocs');
        $res = '';
        if(!empty($mocId)) {
            $result = $mocsModel->find()->where(['AirframeMocs.id'=>$mocId])->select(['AirframeMocs.id', 'AirframeMocs.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }

    //Get moc id
    public function getMocsId($title)
    {
        $mocsModel = $this->getController()->fetchTable('AirframeMocs');
        $res = '';
        if(!empty($title)) {
            $result = $mocsModel->find()->where(['LOWER(AirframeMocs.title)'=>strtolower($title)])->select('AirframeMocs.id')->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}