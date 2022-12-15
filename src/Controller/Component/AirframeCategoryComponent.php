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

class AirframeCategoryComponent extends Component {
    /**
     * GetAirframeCategories method
     * This function is used to get list of all planes.
     *
     * @return array.
     */
    public function getAirCompCats() {
        $airCatModel = TableRegistry::get('AirframeCategories');
        $airCats = $airCatModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'category_name'
        ))->toArray();
        return $airCats;
    }

    public function getRAirCompCats($id = null) {
        $airCatModel = TableRegistry::get('AirframeCategories');
        $airCats = $airCatModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'category_name'
        ))->where(['AirframeCategories.plane_id' => $id])->toArray();
        return $airCats;
    }
    
}