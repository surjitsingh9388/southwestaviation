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


class AirframeIssuingAuthorityComponent extends Component {
    protected \App\Model\Table\AirframeIssuingAuthoritiesTable $AirframeIssuingAuthorities;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    //Get moc id
    public function getIssuingAuthorityId($title)
    {
        $issuingAuthorityModel = $this->getController()->fetchTable('AirframeIssuingAuthorities');
        $res = '';
        if(!empty($title)) {
            $result = $issuingAuthorityModel->find()->where(['LOWER(AirframeIssuingAuthorities.title)'=>strtolower($title)])->select('AirframeIssuingAuthorities.id')->enableHydration(false)->first();
            if(!empty($result['id'])) {
                $res = $result['id'];
            }         
        }
        return $res;
    }
    
}