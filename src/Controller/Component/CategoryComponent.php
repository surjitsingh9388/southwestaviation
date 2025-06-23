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


class CategoryComponent extends Component {
    protected \App\Model\Table\AirframeCategoriesTable $AirframeCategories;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetCategories method
     * This function is used to get list of all planes.
     *
     * @return array.
     */
    public function getAirCompCats() {
        $airCompCatModel = $this->getController()->fetchTable('AirframeCategories');
        $airCompCats = $airCompCatModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'category_name'
        ))->toArray();
        return $airCompCats;
    }
    
}