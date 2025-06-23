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

class AtaCodeComponent extends Component {
    protected \App\Model\Table\AtaCodesTable $AtaCodes;
    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetAtaCodes method
     * This function is used to get list of all ata codes.
     *
     * @return array.
     */
    public function getAtaCodes($id = null) {
        $ataModel = $this->getController()->fetchTable('AtaCodes');
        $ataCodes = $ataModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'ata_code'
        ))->toArray();
        return $ataCodes;
    }

    //Get ata code
    public function ataCode($ataId)
    {
        $res = '';
        if(!empty($ataId)) {
            $ataCodeModel = $this->getController()->fetchTable('AtaCodes');
            $result = $ataCodeModel->find()->where(['AtaCodes.id'=>$ataId])->select(['AtaCodes.id', 'AtaCodes.ata_code'])->enableHydration(false)->first();
            $ataCode = explode("-", $result['ata_code']);
            
            if((int)$ataCode[0] > 0 && (int)$ataCode[0] < 10) {
                $res = (int)$ataCode[0];
            } else {
                $res = $ataCode[0];
            }            
        }
        return $res;
    }

    public function ataTitle($ataId)
    {
        $res = '';
        if(!empty($ataId)) {
            $ataCodeModel = $this->getController()->fetchTable('AtaCodes');
            $result = $ataCodeModel->find()->where(['AtaCodes.id'=>$ataId])->select(['AtaCodes.id', 'AtaCodes.ata_code'])->enableHydration(false)->first();            
            if(!empty($result)) {
                $res = $result['ata_code'];
            } else {
                $res = '';
            }            
        }
        return $res;
    }
    
}