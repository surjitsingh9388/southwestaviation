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
use Cake\ORM\Locator\LocatorAwareTrait;

class AdsbStatusComponent extends Component {
    protected \App\Model\Table\AdsbStatusesTable $AdsbStatuses;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * GetAdsbStatus method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getAdsbStatus($id = null) {
        $this->AdsbStatuses = $this->getController()->fetchTable('AdsbStatuses');
        $adsbRes = $this->AdsbStatuses->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->where(['AdsbStatuses.status'=>'active'])->order(['AdsbStatuses.title'=>'ASC'])->toArray();
        return $adsbRes;
    }

    //Get AD/SB title
    public function adsbTitle($adsbId)
    {
        $this->AdsbStatuses = $this->getController()->fetchTable('AdsbStatuses');
        $res = '';
        if(!empty($adsbId)) {
            $result = $this->AdsbStatuses->find()->where(['AdsbStatuses.id'=>$adsbId])->select(['AdsbStatuses.id', 'AdsbStatuses.title'])->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }
    
}