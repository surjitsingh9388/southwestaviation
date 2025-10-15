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


class DispositionComponent extends Component {
    protected \App\Model\Table\DispositionsTable $Dispositions;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetDispositions method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getDispositions($id = null) {
        $dispModel = $this->getController()->fetchTable('Dispositions');
        $dispRes = $dispModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'title'
        ))->toArray();
        return $dispRes;
    }

    //Get disposition title
    public function dispTitle($dispId)
    {
        $dispModel = $this->getController()->fetchTable('Dispositions');
        $res = '';
        if(!empty($dispId)) {
            $result = $dispModel->find()->where(['Dispositions.id'=>$dispId])->select(['Dispositions.id', 'Dispositions.title'])->enableHydration(false)->first();
            if(!empty($result['title'])) {
                $res = $result['title'];
            }         
        }
        return $res;
    }

    public function getDispId($title)
    {
        $dispModel = $this->getController()->fetchTable('Dispositions');
        $res = '';
        if(!empty($title)) {
            $result = $dispModel->find()->where(['LOWER(Dispositions.title)'=>strtolower($title)])->select(['Dispositions.id'])->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}